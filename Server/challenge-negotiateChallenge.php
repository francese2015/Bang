<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per modificare il duello con id passato come parametro tramite get.        :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE","CAMPO DA MODIFICARE":"VALORE"}'                      :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	$str_tmp = $_GET["challenge"];
	
	$var_tmp = json_decode($str_tmp);
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Prelevo le entry e i valori dal JSON passato.                                         :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$id_player = $var_tmp->id_player;
	$id_challenge = $var_tmp->id;
	$latitude = $var_tmp->latitude;
	$longitude = $var_tmp->longitude;
	$challenge_date = $var_tmp->challenge_date;
	
	$negotiateChallenge = '{"id":"'.$var_tmp->id.'"';
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se l'ID passato appartiene al giocatore 1, allora lo status è 2. Viceversa altrimenti. :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if(executionNumRows(isPlayerOne($id_player,$id_challenge),$connection) == 1){
		$status = '"status":"2"';
	}else{
		$status = '"status":"1"';
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Poiché non si saprà a priori quante negoziazioni avverranno, allora è giusto effettuare un    :://
    //:: controllo di cui sopra in quanto si deve relazionare tale duello con uno status appropriato.  :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Se la latitudine e longitudine passate sono vuote, allora sicuramente ciò che si deve modificare      :://
	//:: sarà la data, viceversa altrimenti. Questa supposizione è giusta dal momento che questa               :://
	//:: pagina php viene chiamata successivamente a un disagio per questi due motivi (Lat, Long, Data).       :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	if($latitude != "" and $longitude != ""){
		$new_latitude = ',"latitude":"'.$latitude.'"';
		$new_longitude = ',"longitude":"'.$longitude.'"';
		$negotiateChallenge = $negotiateChallenge.$new_latitude.$new_longitude;
	}
	if($challenge_date != ""){
		$new_challenge_date = ',"challenge_date":"'.$challenge_date.'"';
		$negotiateChallenge = $negotiateChallenge.$new_challenge_date;
	}
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Conclusione della stringa query.                                                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$negotiateChallenge = $negotiateChallenge.'}';
	$negotiateChallenge = $negotiateChallenge.$status;
	
	$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$negotiateChallenge),$connection);
	
	if($valueExecutionQuery)
		echo '{"return":"0"}';
	else
		echo '{"return":"-1"}';
	
	closeConnectionBangServer($connection);
	
?>