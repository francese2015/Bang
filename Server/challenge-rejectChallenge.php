<?php
    //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per rifiutare il duello con id passato come parametro tramite get.         :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DATABASE":"VALORE"}'. LA STRINGA NON DEVE ESSERE VUOTA.                      :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Carlos Borges.                                                             :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Modificato da: Valentino Vivone.                                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	$return0 = NULL;
	$return1 = NULL;
	$status = '"status":"3"';
	$challengeID = $_GET["id_challenge"];
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se si usa questa pagina, allora la variabile da inserire sarà concatenata con la variabile status 3  :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challengeID.$status),$connection);
	
	if($valueExecutionQuery)
		$return0 = true;
	else
		$return0 = false;
		
	$remove = '"remove_challenge":"0"';// 0 remove challenge
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: remove_challenge sarà modificata a 0, cosi da permettere una rimozione della tupla successivamente.  :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("challenge",$challengeID.$remove),$connection);
	
	if($valueExecutionQuery1)
		$return1 = true;
	else
		$return1 = false;
	
	if(($return0 == true) and ($return1 == true))
		echo '{"return":"0"}';
	else
		echo '{"return":"-1"}';
	
	closeConnectionBangServer($connection);	
?>
