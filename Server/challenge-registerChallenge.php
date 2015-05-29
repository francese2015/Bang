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
	$random = rand(1, 4);

	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si concatena la variabile d'ingresso con la variabile status 2.                       :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$status = '"status":"2"';
	$challenge = $_GET["challenge"];
	$json_variable = json_decode($challenge);
	
	$idPlayer_one = $json_variable->player_one;
	$idPlayer_two = $json_variable->player_two;
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Recupero data in input dello sfidante. Quì si inizializzano le variabili che saranno utili.  :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$tmpDate = $json_variable->challenge_date;
	$tmpChallengeDate = multiexplode(array("(",")"),$tmpDate);
	$challengeDate = explode(" ",$tmpChallengeDate[1]);
	$dateInput = $challengeDate[0];
	$hoursInput = $challengeDate[1];
	$tmpDate = explode(":",$hoursInput);
	$hourInput = intval($tmpDate[0]);
	$minuteInput = intval($tmpDate[1]);
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Ovviamente, il duellante che dà il via alla sfida non può inserire un duello avente un orario, :://
	//:: all'interno delle proprie sfide, in un range minore di 15 minuti. Qualora lo facesse, viene    :://
	//:: restituito un output specifico.                                                                :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::;:://
	$research = '{"player_one":"'.$idPlayer_one.'"}';
	
	$valueControlDate = executionSelectQuery(selectQueryIntoDatabase("challenge",$research,"challenge_date DESC"),$connection);
	
	$flag = 0;
	
	for($i=0;$i<count($valueControlDate);$i++){
		$row = $valueControlDate[$i];
		$tmp = '{"challenge_date":"('.$row[5].')"}';
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Recupero data in database dello sfidante.                                                      :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::;:://
		$json_variable1 = json_decode($tmp);
		$tmpDate = $json_variable1->challenge_date;
		$tmpChallengeDate = multiexplode(array("(",")"),$tmpDate);
		$challengeDate = explode(" ",$tmpChallengeDate[1]);
		$dateDB = $challengeDate[0];
		$hoursDB = $challengeDate[1];
		$tmpDate = explode(":",$hoursDB);
		$hourDB = intval($tmpDate[0]);
		$minuteDB = intval($tmpDate[1]);	
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione crea le ore in minuti.                                                         :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::;:://
		if($dateInput === $dateDB){
			$tmpHI = $hourInput*60;
			$tmpHDB = $hourDB*60;
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Controllo del range di 15 minuti.                                                              :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::;:://
			if((($tmpHI+$minuteInput) >= ($tmpHDB+$minuteDB-7)) and (($tmpHI+$minuteInput) < ($tmpHDB+$minuteDB+8)) ){
				$flag = 1;
			}
		}
	}
	
	if($flag == 0){		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se si rispetta il range, allora si inserisce all'interno del database.                         :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::;:://
		$valueExecutionQuery1 = executionQuery(insertQueryIntoDatabase("challenge",$challenge.$status),$connection);
		$lastId = lastIdChallenge();
		
		$dare1 = '{"user_account_id":"'.$idPlayer_one.'","challenge_id":"'.$lastId.'"}';
		$dare2 = '{"user_account_id":"'.$idPlayer_two.'","challenge_id":"'.$lastId.'"}';
		
		$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("dare",$dare1),$connection);
		$valueExecutionQuery = executionQuery(insertQueryIntoDatabase("dare",$dare2),$connection);
		
		$tmp = '{"id":"'.$lastId.'","round_time":"'.$random.'"}';
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$tmp),$connection);
		
		if($valueExecutionQuery1)
			echo '{"return":"0","last_id":"'.$lastId.'","random_round":"'.$random.'"}';
		else
			echo '{"return":"-1"}';
	
	}else{
		echo '{"return":"-2"}';
	}
	
	closeConnectionBangServer($connection);
	 
?>