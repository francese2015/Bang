<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che fà sì che il duellante accetti la sfida.                               :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                        :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Init delle variabili utilizzate.                                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$acceptDuels = NULL;
	$getChallenge = NULL;
	$insertChurchbell = NULL;
	
	$connection = connectionBangServer();
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Nella tabella status questo valore indica sostanzialmente l'accettazione del duello.  :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$status = '"status":"0"';
	
	$acceptChallenge = $_GET["challenge"];//'{"id":"4"}'; 
	$tmp = $acceptChallenge;
	$acceptChallenge = $acceptChallenge.$status;	
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Modifica dello status                                                                 :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$acceptChallenge),$connection);
	
	if($valueExecutionQuery)
		$acceptDuels = 0;
	else
		$acceptDuels = -1;
	
	$acceptChallenge = $tmp;
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$acceptChallenge,NULL),$connection);
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si crea la variabile per la tabella duels_queue.                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if($valueExecutionQuery == 0)
		$getChallenge = -1;
	else{
		$row = $valueExecutionQuery[0];
		$acceptChallenge = '{"id_challenge":"'.$row[0].'","player_one":"'.$row[1].'","player_two":"'.$row[2].'","challenge_date":"('.$row[5].')"}';
		$getChallenge = 0;
	}
	
	closeConnectionBangServer($connection);
	$connection = connectionChurchbellServer();
	
	$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("duels_queue",$acceptChallenge),$connection);
	
	if($valueExecutionQuery)
		$insertChurchbell = 0;
	else
		$insertChurchbell = -1;
	
	$return = '{"acceptDuels":"'.$acceptDuels.'","getChallenge":"'.$getChallenge.'","insertChurchbell":"'.$insertChurchbell.'"}';
	
	closeConnectionBangServer($connection);
	echo $return;
	
?>