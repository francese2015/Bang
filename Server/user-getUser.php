<?php	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Questa pagina php permette di acquisire tutti gli utenti all'interno del database.    :://
	//:: La stringa da comporre dovrà essere impostata nel seguente modo:                      :://
	//:: '{"CAMPO DATABASE":"VALORE"}'; SE LA STRINGA SARA' VUOTA, IL RISULTATO SARA'          :://
	//:: SIMILE AD UNA SEMPLICE OPERAZIONE DI SELECT * FROM.                                   :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creata da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$userAccount = $_GET["user_account"];
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("user_account",$userAccount,NULL),$connection);
	
	if($valueExecutionQuery == 0){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Lettura non avvenuta.                                                                 :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		echo '{"return":"-1"}';
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Lettura è avvenuta con successo, con successiva creazione dell'array di JSON.         :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$arr = array();
		for($i=0;$i<count($valueExecutionQuery);$i++){
			$row = $valueExecutionQuery[$i];
			$tmp = array('id'=>$row[0],'locale'=>$row[1],'gender'=>$row[2],'email'=>$row[3],'first_name'=>$row[4],'last_name'=>$row[5],'region'=>$row[6]);
			array_push($arr,$tmp);
		}
		$result = json_encode($arr);
		echo $result;
	}
	
	closeConnectionBangServer($connection);
	
?>