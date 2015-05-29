<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php verrà usata per il ritiro dalla sfida.                                     :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DATABASE":"VALORE"}'. LA STRINGA NON DEVE ESSERE VUOTA.                      :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	//{id:x,id_player:y}
	
	$connection = connectionBangServer();
	
	$userAccount = $_GET["challenge"];
	$jsonString = json_decode($userAccount);
	
	$id = $jsonString->id;
	$idPlayer = $jsonString->id_player;
	$nameField = NULL;
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se è il player one, nameField si setterà a 1, altrimenti 2                            :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if(executionNumRows(isPlayerOne($idPlayer,$id),$connection) == 0){
		$nameField = 2;
	}else{
		$nameField = 1;
	}
	
	$tmpStr = '{"id":"'.$id.'"}';
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si cerca il duello. Se non và a buon fine, si ritorna -1.                             :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$tmpStr,NULL),$connection);
	$tmp = NULL;
	if($valueExecutionQuery == 0)
		echo '{"return":"-1"}';
	else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se nameField è 1, allora il vincitore sarà il player two, viceversa altrimenti.       :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		if($nameField == 1){
			$row = $valueExecutionQuery[0];
			$tmp = '{"id":"'.$row[0].'","id_winner":"'.$row[2].'","status":"0"}';
		}
		if($nameField == 2){
			$row = $valueExecutionQuery[0];
			$tmp = '{"id":"'.$row[0].'","id_winner":"'.$row[1].'","status":"0"}';
		}
	}
	
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$tmp),$connection);
	
	if($valueExecutionQuery)
		echo '{"return":"0"}';
	else
		echo '{"return":"-1"}';
	
	closeConnectionBangServer($connection);
	
?>