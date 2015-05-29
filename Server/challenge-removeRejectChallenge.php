<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina che permette un update della tabella challenge. Si modifica il campo remove_challenge, :://
	//:: che dopo aver fatto la reject è a 0, con l'ID del duellante che ha rimosso. Qualora           :://
	//:: fosse diverso da 0, significa che un duellante già ha effettuato la rimozione, e quindi se    :://
	//:: viene effettuata un altra rimozione automaticamente il campo remove_challenge viene posto     :://
	//:: a 3. L'ultimo controllo vede se il campo è 3: 1) se non lo è, la tupla non si cancella,       :://
	//:: 2) altrimenti viene rimossa la tupla.                                                         :://
	//:: La stringa dovrà essere composta nel seguente modo:                                           :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                                :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                                  :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	include "connection.php";
	
	$connection = connectionBangServer();
	//{"id":"x", "id_player":"y"}
	
	$challenge = $_GET["challenge"];
	$jsonString = json_decode($challenge);
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Init delle variabili                                                                  :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$idChallenge = $jsonString->id;
	$idPlayer = $jsonString->id_player;
	$returnPlayer = NULL;
	$returnGetChallenge = NULL;
	$returnDeleteChallenge = 0;
	$returnDeleteDareChallenge = 0;
	$returnPlayer = -1;
	$removeChallenge = NULL;
	$playerOne = NULL;
	$playerTwo = NULL;
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Prendo il duello.Recupero gli ID dei duellanti.                                       :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$tmp = '{"id":"'.$idChallenge.'"}';
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$tmp,NULL),$connection);
	
	if($valueExecutionQuery == 0)
		$returnGetChallenge = -1;
	else{
		$row = $valueExecutionQuery[0];
		$playerOne = $row[1];
		$playerTwo = $row[2];
		$removeChallenge = $row[8];
		$returnGetChallenge = 0;
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Confronto l'ID passato con quello del primo giocatore.                                :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se la remove_challenge è 0, nessuno l'ha letta e ci metto il mio ID. Altrimenti 3.    :://
	//:: Sesso processo se fossi il secondo giocatore                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if($idPlayer === $playerOne){
		if($removeChallenge == 0){
			$removeChallenge = $idPlayer;
		}else{
			$removeChallenge = 3;
		}
		
		$tmp = '{"id":"'.$idChallenge.'","remove_challenge":"'.$removeChallenge.'"}';
		
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$tmp),$connection);
		if($valueExecutionQuery)
			$returnPlayer = 0;
		else
			$returnPlayer = -1;
	}else{
		if($idPlayer === $playerTwo){
			if($removeChallenge == 0){
				$removeChallenge = $idPlayer;
			}else{
				$removeChallenge = 3;
			}
			
			$tmp = '{"id":"'.$idChallenge.'","remove_challenge":"'.$removeChallenge.'"}';
			
			$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$tmp),$connection);
			if($valueExecutionQuery)
				$returnPlayer = 0;
			else
				$returnPlayer = -1;
		}	
	}
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se la remove_challenge è 3, procedo con la rimozione della tupla.                     :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$tmp = '{"id":"'.$idChallenge.'"}';
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$tmp,NULL),$connection);
	
	if($valueExecutionQuery == 0)
		$returnGetChallenge = -1;
	else{
		$returnGetChallenge = 0;
		$row = $valueExecutionQuery[0];
	
		if($row[8] == 3){
			$valueExecutionQuery1 = executionQuery(deleteQueryIntoDatabase("challenge",$tmp),$connection);
			if($valueExecutionQuery1)
				$returnDeleteChallenge = 0;
			else
				$returnDeleteChallenge = -1;
			
			$tmp = '{"challenge_id":"'.$idChallenge.'"}';
			$valueExecutionQuery2 = executionQuery(deleteQueryIntoDatabase("dare",$tmp),$connection);
			if($valueExecutionQuery2)
				$returnDeleteDareChallenge = 0;
			else
				$returnDeleteDareChallenge = -1;
			
		}
	}
	
	$return = '{"returnPlayer":"'.$returnPlayer.'","returnGetChallenge":"'.$returnGetChallenge.'","returnDeleteDareChallenge":"'.$returnDeleteDareChallenge.'","returnDeleteChallenge":"'.$returnDeleteChallenge.'"}';
	closeConnectionBangServer($connection);
	echo $return;
	
?>