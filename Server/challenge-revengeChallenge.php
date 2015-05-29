<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che permette un inserimento nella tabella challenge, qualora il duellante  :://
	//:: voglia effettuare una rivincita.                                                      :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                        :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	//{"id":"222"}
	$connection = connectionBangServer();
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si crea una variabile da concatenare. Status 2 perchè in questo caso viene creato un duello nuovo.   :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$status = '"status":"2"';
	$challenge = $_GET["challenge"];
	$new_challenge = NULL;
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$challenge),$connection);
	
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Recupero dati di quel duello specifico. Se va male il recupero, si ritorna -1.                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if($valueExecutionQuery == 0)
		echo '{"return":"-1"}';
	else{
		$row = $valueExecutionQuery[0];
		$new_challenge = '{"player_one":"'.$row[1].'","player_two":"'.$row[2].'","latitude":"'.$row[3].'","longitude":"'.$row[4].'","challenge_date":"('.$row[5].')"}';
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si inserisce il nuovo duello creato.                                                                :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery1 = executionQuery(insertQueryIntoDatabase("challenge",$new_challenge.$status),$connection);
	
	$json_variable = json_decode($new_challenge);
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si creano le variabili per inserimenti nella tabella dare.                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$idPlayer_one = $json_variable->player_one;
	$idPlayer_two = $json_variable->player_two;
	$lastId = lastIdChallenge();
	
	$dare1 = '{"user_account_id":"'.$idPlayer_one.'","challenge_id":"'.$lastId.'"}';
	$dare2 = '{"user_account_id":"'.$idPlayer_two.'","challenge_id":"'.$lastId.'"}';
	
	$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("dare",$dare1),$connection);
	$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("dare",$dare2),$connection);
	
	if($valueExecutionQuery1)
		echo '{"return":"0"}';
	else
		echo '{"return":"-1"}';
	
	closeConnectionBangServer($connection);
	 
?>