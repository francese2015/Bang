<?php
    //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per registrare un duello.                                                  :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DATABASE":"VALORE"}'. LA STRINGA NON DEVE ESSERE VUOTA.                      :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();

	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si concatena la variabile d'ingresso con la variabile status 2.                       :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$training = $_GET["training"];
	$json_variable = json_decode($training);
	$valueExecutionQuery = NULL;
	
	$id_player = $json_variable->id_player;
	$typology = $json_variable->typology;
	
	$getPlayer = '{"id_player":"'.$id_player.'"}';
	$stringForDatabase = "";
	
	if(executionNumRows(selectQueryIntoDatabase("training",$getPlayer,NULL),$connection) == 0){
		$acceleration = "0.00";
		$timestamp = "0.00";
		
		if($typology === "2" || $typology === "1"){
			$stringForDatabase = '{"id_player":"'.$id_player.'","typology":"'.$typology.'"}';
		}else{
			$acceleration = $json_variable->acceleration;
			$timestamp = $json_variable->timestamp;
			$stringForDatabase = '{"id_player":"'.$id_player.'","typology":"'.$typology.'","acceleration":"'.$acceleration.'","timestamp":"'.$timestamp.'","best_acceleration":"'.$acceleration.'","best_timestamp":"'.$timestamp.'"}';
		}
		
		$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("training",$stringForDatabase),$connection);
		
		if($valueExecutionQuery)
			echo '{"return":"0","insert":"0","typology":"'.$typology.'","acceleration":"'.$acceleration.'","timestamp":"'.$timestamp.'","best_acceleration":"'.$acceleration.'","best_timestamp":"'.$timestamp.'"}';
		else
			echo '{"return":"-1","insert":"0"}';
		
	}else{
		$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("training",$getPlayer,NULL),$connection);
		
		$old_typology = NULL;
		$old_acceleration = NULL;
		$old_timestamp = NULL;
		$best_acceleration = NULL;
		$best_timestamp = NULL;
	
		$acceleration = "0.00";
		$timestamp = "0.00";
		
		if($valueExecutionQuery == 0)
			echo '{"return":"-1","select":"0"}';
		else{
			$row = $valueExecutionQuery[0];
			$old_typology = $row[1];
			$old_acceleration = $row[2];
			$old_timestamp = $row[3];
			$best_acceleration = $row[4];
			$best_timestamp = $row[5];
		}
		
		if($typology === "2" || $typology === "1"){
			$acceleration = "0.00";
			$timestamp = "0.00";	
			$stringForDatabase = '{"id_player":"'.$id_player.'","typology":"'.$typology.'","acceleration":"'.$acceleration.'","timestamp":"'.$timestamp.'"}';
		}else{
			$acceleration = $json_variable->acceleration;
			$timestamp = $json_variable->timestamp;
			
			if(floatval($acceleration) < floatval($best_acceleration)){
				$best_acceleration = $acceleration;
			}
			if(floatval($timestamp) < floatval($best_timestamp)){
				$best_timestamp = $timestamp;
			}
			
			$stringForDatabase = '{"id_player":"'.$id_player.'","typology":"'.$typology.'","acceleration":"'.$acceleration.'","timestamp":"'.$timestamp.'","best_acceleration":"'.$best_acceleration.'","best_timestamp":"'.$best_timestamp.'"}';
		}
		
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("training",$stringForDatabase),$connection);
		if($valueExecutionQuery)
			echo '{"return":"0","insert":"-1","typology":"'.$typology.'","acceleration":"'.$acceleration.'","timestamp":"'.$timestamp.'","old_typology":"'.$old_typology.'","old_acceleration":"'.$old_acceleration.'","old_timestamp":"'.$old_timestamp.'","best_acceleration":"'.$best_acceleration.'","best_timestamp":"'.$best_timestamp.'"}';
		else
			echo '{"return":"-1","insert":"-1"}';
		
	}
	
	closeConnectionBangServer($connection);
	 
?>