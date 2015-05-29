<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che permette di cancellare un utente dal database.                         :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE"}'                                                     :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$userAccount = $_GET["user_account"];
	
	$valueExecutionQuery = executionQuery(deleteQueryIntoDatabase("user_account",$userAccount),$connection);
	
	if($valueExecutionQuery){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Cancellazione avvenuta.                                                               :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"0"}';
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Cancellazione non avvenuta.                                                           :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}'; 
	}
	
	closeConnectionBangServer($connection);
	
?>