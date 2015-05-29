<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina che permette un update della tabella status.                                   :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE","CAMPO DA MODIFICARE":"VALORE"}'                      :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$status = $_GET["status"];
	
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("status",$status),$connection);
	
	if($valueExecutionQuery){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: La modifica è andata a buon fine.                                                     :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"0"}';
	}
	else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: La modifica non è andata a buon fine.                                                 :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}
	
	closeConnectionBangServer($connection);
	
?>