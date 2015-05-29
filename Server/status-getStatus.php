<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php che permette di visualizzare i dati della tabella status                   :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE"}'                                                     :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$status = $_GET["status"];
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("status",$status,NULL),$connection);
	
	if($valueExecutionQuery == 0){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: La ricerca non è andata a buon fine.                                                  :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}else{		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Ricerca è andata a buon fine, con successiva creazione dell'array JSON di ritorno.    :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$arr = array();
		for($i=0;$i<count($valueExecutionQuery);$i++){
			$row = $valueExecutionQuery[$i];
			$tmp = array('id'=>$row[0],'description'=>$row[1]);
			array_push($arr,$tmp);
		}
		$result = json_encode($arr);
		echo $result;
	}
	
	closeConnectionBangServer($connection);
	
?>