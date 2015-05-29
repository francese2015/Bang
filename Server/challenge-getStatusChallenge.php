 <?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina che permette un visualizzare i dati della tabella challenge.                   :://
	//:: la stringa dovrà essere composta nel seguente modo:                                   :://
	//:: '{"CAMPO DA RICERCARE":"VALORE"}'                                                     :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://

	include "connection.php";
	
	$connection = connectionBangServer();
	
	$variable = $_GET["challenge"];
	//'{"id_player":"1","id":"1"}';
	
	$challenge = '{';
	$var_tmp = json_decode($variable);
	$id_player = $var_tmp->id_player;
	$id_challenge = $var_tmp->id;
	
	if(executionNumRows(isPlayerOne($id_player,$id_challenge),$connection) == 1){
		$challenge = $challenge.'"player_one":"'.$id_player.'",';
	}else{
		$challenge = $challenge.'"player_two":"'.$id_player.'",';
	}
	
	$challenge = $challenge.'"id":"'.$id_challenge.'"}';
	
	$valueExecutionQuery = executionSelectQuery(selectQueryIntoDatabase("challenge",$challenge,NULL),$connection);
	
	if($valueExecutionQuery == 0)
		echo '{"return":"-1"}';
	else{
		$arr = array();
		
		$row = $valueExecutionQuery[0];
		$tmp = array('status'=>$row[7]);
		array_push($arr,$tmp);
		
		$result = json_encode($arr);
		echo $result;
	}
	
	closeConnectionBangServer($connection);
	
?>