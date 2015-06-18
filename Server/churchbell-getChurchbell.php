<?php
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che permette di ottenere tutta la history dei duelli svolti. Si fà un'interrogazione alla :://
	//:: tabella duels_queue del database churchbell. Esso contiene data, ora, duello e giocatori             :://
	//:: La stringa dovrà essere composta nel seguente modo:                                                  :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                                       :://	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                                         :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionChurchbellServer();
	
	$churchbell = $_GET["churchbell"];//{id_player:y}
	$json = json_decode($churchbell);
	$idPlayer = $json->id_player;
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Poichè non conosco il mio ruolo all'interno del duello, faccio questa ricerca                        :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$tmp = '{"player_one":"'.$idPlayer.'","player_two":"'.$idPlayer.'"}';
	
	$valueExecutionQuery = executionSelectQuery(selectGetChallengeWithIdPlayer("duels_queue",$tmp,"challenge_date ASC"),$connection);
	
	closeConnectionBangServer($connection);
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Terminata, chiudo la connessione con churchbell e ne apro una con bang.                              :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$connection = connectionBangServer();
	
	if($valueExecutionQuery == 0){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se la ricerca di prima non è andata a buon fine                                                      :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}else{
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se la ricerca di prima è andata a buon fine                                                          :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$arr = array();
		for($i=0;$i<count($valueExecutionQuery);$i++){
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Init delle variabili utilizzate successivamente.                                                     :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			$row = $valueExecutionQuery[$i];
			$id_challenge = $row[0];
			$challenge_date = $row[1];
			$playerOne = $row[2];
			$playerTwo = $row[3];
			$idWinner = NULL;
			$NominativoPlayerOne = NULL;
			$NominativoPlayerTwo = NULL;
			$NominativoWinner = NULL;
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Cerco il duello all'interno della tabella challenge di bang.                                         :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			$tmp = '{"id":"'.$id_challenge.'"}';
			$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("challenge",$tmp,NULL),$connection);
			if($valueExecutionQuery1 == 0){				
				echo '{"return":"-1"}';				
			}else{
				$row1 = $valueExecutionQuery1[0];
				$idWinner = $row1[6];
			}
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Se non lo trovo, ritorno -1; altrimenti salvo l'ID del duellante vincente.                           :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Faccio un controllo se il campo id_winner è NULL o vuoto. Qualora lo fosse, l'output sarà            :://
			//:: un'array vuoto, viceversa conterrà i dati sotto descritti                                            :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			if($idWinner != NULL or $idWinner != ""){
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Faccio una select sul player_one per prendermi il nominativo                                         :://
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://			
				$tmp = '{"id":"'.$playerOne.'"}';
				$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("user_account",$tmp,NULL),$connection);
				if($valueExecutionQuery2 == 0){				
					echo '{"return":"-1"}';				
				}else{
					$row2 = $valueExecutionQuery2[0];
					$NominativoPlayerOne = $row2[4]." ".$row2[5];
				}
				
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Faccio una select sul player_two per prendermi il nominativo                                         :://
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://			
				$tmp = '{"id":"'.$playerTwo.'"}';
				$valueExecutionQuery3 = executionSelectQuery(selectQueryIntoDatabase("user_account",$tmp,NULL),$connection);
				if($valueExecutionQuery3 == 0){				
					echo '{"return":"-1"}';				
				}else{
					$row3 = $valueExecutionQuery3[0];
					$NominativoPlayerTwo = $row3[4]." ".$row3[5];
				}
				
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Se l'ID del vincente fosse uguale a quello passato, allora significa che sono il vincitore del       :://
				//:: duello. Quindi il risultato sarà 0. Viceversa il risultato sarà 1.                                   :://
				//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://			
				if($idWinner == $idPlayer){
					$NominativoWinner = 0;
				}else{
					$NominativoWinner = 1;
				}
				
				$tmp = array('challenge_date'=>$challenge_date,'idMaster'=>$playerOne,'playerOne'=>$NominativoPlayerOne,'playerTwo'=>$NominativoPlayerTwo,'winner'=>$NominativoWinner);
				array_push($arr,$tmp);
			}
		}
		$result = json_encode($arr);
		echo $result;
	}
	
	closeConnectionBangServer($connection);
	
?>