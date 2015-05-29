<?php
    //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per ottenere le statistiche del giocatore con id passato come parametro.   :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO":"VALORE"}'                                                                  :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Carlos Borges.                                                             :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Modificato da: Valentino Vivone.                                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$playerID = $_GET["stats"];
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("stats",$playerID,NULL),$connection);
	
	if($valueExecutionQuery == 0){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Lettura non avvenuta.                                                                 :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Lettura avvenuta con successiva creazione dell'array JSON.                            :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$arr = array();
		for($i=0;$i<count($valueExecutionQuery);$i++){
			$row = $valueExecutionQuery[$i];
			$tmp = array('num_faults'=>$row[1],'num_winner_matches'=>$row[2],'num_loose_matches'=>$row[3],'acceleration'=>$row[4]);
			array_push($arr,$tmp);
		}
		$result = json_encode($arr);
		echo $result;
	}
	
	closeConnectionBangServer($connection);
	
?>