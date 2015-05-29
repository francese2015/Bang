<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per ottenere i duelli del giocatore con id passato come parametro.         :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE"}'                                                     :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	$challenge = $_GET["challenge"];//'{"id_player":"1"}';
	
	$var_tmp = json_decode($challenge);
	$id_player = $var_tmp->id_player;
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se la funzione sottostante è uguale a 0, allora il duellante non ha duelli.           :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if(executionNumRows(getChallengeWithIdPlayer($id_player),$connection) == 0){
		echo '{"return":"-2","message":"Il giocatore non &egrave; stato sfidato da nessuno n&egrave; ha sfidato qualcuno."}';
	}else{
		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Poichè il duellante non conosce il suo status (P1 o P2) allora si cerca di scoprirlo. :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$challenge = '{"player_one":"'.$id_player.'","player_two":"'.$id_player.'"}';
		$valueExecutionQuery = executionSelectQuery(selectGetChallengeWithIdPlayer("challenge",$challenge,"status ASC"),$connection);
		
		if($valueExecutionQuery == 0)
			echo '{"return":"-1"}';
		else{
			
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Trovato l'ID del duello, restituisco i dati relativi                                  :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			$arr = array();
			for($i=0;$i<count($valueExecutionQuery);$i++){
				$row = $valueExecutionQuery[$i];
				
				$vsName = NULL;
				if(executionNumRows(isPlayerOne($id_player,$row[0]),$connection) != 0){
					$vsName = $row[2];
				}else{
					$vsName = $row[1];
				}
				
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Se la ricerca non avviene, si ritorna -1, altrimenti un array di JSON con i dati.     :://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				$strTmp = '{"id":"'.$vsName.'"}';
				
				$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("user_account",$strTmp,NULL),$connection);
				
				if($valueExecutionQuery1 == 0)
					echo '{"return":"-1"}';
				else{
					for($j=0;$j<count($valueExecutionQuery1);$j++){
						$row1 = $valueExecutionQuery1[$j];
						$vsName = $row1[4]." ".$row1[5];
					}
				}
				$realAddress = NULL;
				
				
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: getaddress(x,y) vuole delle coordinate (Lat,Long) in modo da ritornare il luogo esatto. :://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				$address= getaddress($row[3],$row[4]);
				if($address){
					$realAddress = $address;
				}else{
					$realAddress = "Not found";
				}
				
				$tmp = array('id'=>$row[0],'player_one'=>$row[1],'player_two'=>$row[2],'latitude'=>$row[3],'longitude'=>$row[4],'challenge_date'=>$row[5],'id_winner'=>$row[6],'status'=>$row[7],'vsName'=>$vsName,'remove_challenge'=>$row[8],'address'=>$realAddress);
				array_push($arr,$tmp);
			}
			
			$result = json_encode($arr);
			
			echo $result;
		}
	}
	closeConnectionBangServer($connection);
?>