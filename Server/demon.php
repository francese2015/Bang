<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che fà sì che il duellante accetti la sfida.                               :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                        :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$dateFor = date("Y-m-d h");
	$minutes = date("i");
	$seconds = date("s");
	$realMinutes = "";
	switch($minutes){
		case "00":
			$realMinutes = "55";
			break;
		case "01":
			$realMinutes = "56";
			break;
		case "02":
			$realMinutes = "57";
			break;
		case "03":
			$realMinutes = "58";
			break;
		case "04":
			$realMinutes = "59";
			break;
		default:
			$realMinutes = intval($minutes) - 5;
			break;
	}
	
	$data = $dateFor.":".$realMinutes.":".$seconds;
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Nella tabella status questo valore indica sostanzialmente l'accettazione del duello.  :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$status = '{"status":"0"}';
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$status,"challenge_date DESC"),$connection);
	
	if($valueExecutionQuery == 0)
		echo '{"return":"-1"}';
	else{
		for($j=0;$j<count($valueExecutionQuery);$j++){
			$row = $valueExecutionQuery[$j];
			var $dateIntoDatabase = $row[5];
			if($data === $dateIntoDatabase){
				var strTmp = '{"id":"'+$row[0]'"}';
				$valueExecutionQuery1 = executionQuery(deleteQueryIntoDatabase("challenge",$strTmp),$connection);
			}
		}
	}
	
	closeConnectionBangServer($connection);
	
?>