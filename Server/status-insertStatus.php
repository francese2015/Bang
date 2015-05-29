<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina che permette un inserimento nella tabella status.                              :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DATABASE":"VALORE"}'                                                         :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$status = $_GET["status"];
	
	$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("status",$status),$connection);
	
	if($valueExecutionQuery){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Inserimento andato a buon fine.                                                       :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"0"}';
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Inserimento non è andato a buon fine.                                                 :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}
	closeConnectionBangServer($connection);
	
?>