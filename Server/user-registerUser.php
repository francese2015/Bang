<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Questa pagina php verrà usata per la registrazione di nuovi utenti.     :://
	//:: La stringa da utilizzare dovrà essere impostata nel seguente modo:      :://
	//:: '{"CAMPO DATABASE":"VALORE"}'. LA STRINGA NON DEVE ESSERE VUOTA.        :://
	//:: Qualora l'utente è già registrato, si ritorna un valore identificativo  :://
	//:: che fà sì che non ci sia un errore da parte del database.               :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creata da: Valentino Vivone.                                            :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$userAccount = $_GET["user_account"];
	$jsonString = json_decode($userAccount);
	
	$id = $jsonString->id;
	$tmp = '{"id":"'.$id.'"}';
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Registrazione delle statistiche e classifiche di default dell'utente. :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$stats = '{"id_player":"'.$jsonString->id.'","num_faults":"0","num_winner_matches":"0","num_loose_matches":"0","acceleration":"0.00"}';
	$chart = '{"id_player":"'.$jsonString->id.'","global_chart":"0"}';
	
	if(executionNumRows(selectQueryIntoDatabase("user_account",$tmp,NULL),$connection) === 0){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se l'utente non è registrato, viene inserito nel database insieme alle sue statistiche   :://
		//:: di default (sono postate a zero), e alla posizione in classifica (stessa cosa).          :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("user_account",$userAccount),$connection);
	
		$valueExecutionQuery1 = executionQuery(insertQueryIntoDatabase("stats",$stats),$connection);
		
		$valueExecutionQuery2 = executionQuery(insertQueryIntoDatabase("chart",$chart),$connection);
		
		if($valueExecutionQuery and $valueExecutionQuery1 and $valueExecutionQuery2){
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Registrazione andata a buon fine                                      :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			echo '{"return":"0"}'; 
		}else{
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Registrazione non avvenuta                                            :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			echo '{"return":"-1"}'; 
		}
		
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: L'utente è già registrato                                             :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-2","message":"sei gi&agrave; registrato."}';
	}
	
	
	
	closeConnectionBangServer($connection);
	
?>