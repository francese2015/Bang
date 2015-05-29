<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina che permette un update della tabella user_account.             :://
	//:: La stringa dovrà essere composta nel seguente modo:                   :://
	//:: '{"CAMPO DA RICERCARE":"VALORE","CAMPO DA MODIFICARE":"VALORE"}'      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$userAccount = $_GET["user_account"];
	
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("user_account",$userAccount),$connection);
	
	if($valueExecutionQuery){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Il campo è stato modificato                                           :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"0"}'; 
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Il campo non è stato modificato                                      :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}'; 
	}
	closeConnectionBangServer($connection);
	
?>