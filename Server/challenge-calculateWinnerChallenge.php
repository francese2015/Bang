<?php
    //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per calcolare il vincitore del duello.                                            :://
	//:: La stringa dovrà essere composta nel seguente modo:                                          :://	
	//:: '{"id_duels":"1","player_one":"1","player_two":"2","num_faults1":"0","num_faults2":"0",      :://
	//:: "acceleration1":"3","acceleration2":"5","diff_timestamp1":"1","diff_timestamp2":"2"}';       :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da:  Valentino Vivone.                                                                :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$input = $_GET["challenge"];
	
	$jsonString = json_decode($input);
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Si inizializzano le variabili da utilizzare andando a parsare il json dato in input.         :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$id_duels = $jsonString->id_duels;	
	$player_one = $jsonString->player_one;	
	$player_two = $jsonString->player_two;	
	$num_faults_one = $jsonString->num_faults1;
	$num_faults_two = $jsonString->num_faults2;	
	$acceleration_one = $jsonString->acceleration1;	
	$acceleration_two = $jsonString->acceleration2;
	$diff_timestamp_one = $jsonString->diff_timestamp1;	
	$diff_timestamp_two = $jsonString->diff_timestamp2;
	
	if ((andTheWinnerIs($diff_timestamp_one, $diff_timestamp_two) == 1) and ($num_faults_one == 0)){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se il primo giocatore ha sparato in minor tempo, allora è designato come vincitore del duello. :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$id_winner = $player_one;
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Quindi, si modifica la tupla del challenge con l'ID del vincitore nel campo id_winner.         :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$challenge = '{"id":"'.$id_duels.'","id_winner":"'.$id_winner.'"}';
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challenge),$connection);
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Si prendono le statistiche dei giocatori, e la classifica, e si effettuano i calcoli.  :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore uno.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_one.'"}';
		$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery1 != 0){
			$row = $valueExecutionQuery1[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_one.'","num_faults":"'.($jsonString->num_faults+$num_faults_one).'","num_winner_matches":"'.addWorLorC($jsonString->num_winner_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_one).'"}';
		$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore due.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_two.'"}';
		$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery2 != 0){
			$row = $valueExecutionQuery2[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_two.'","num_faults":"'.($jsonString->num_faults+$num_faults_two).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_two).'"}';
		$valueExecutionQuery2 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		//::::::::::::::::::::::::::://
		//::      Classifica.      :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getChart = '{"id_player":"'.$player_one.'"}';
		$valueExecutionQuery3 = executionSelectQuery(selectQueryIntoDatabase("chart",$getChart,NULL),$connection);
		if($valueExecutionQuery3 != 0){
			$row = $valueExecutionQuery3[0];
			$tmp = '{"global_chart":"'.$row[1].'"}';
		}
		$jsonString = json_decode($tmp);							
		$chart = '{"id_player":"'.$player_one.'","global_chart":"'.addWorLorC($jsonString->global_chart).'"}';
		$valueExecutionQuery3 = executionQuery(updateQueryIntoDatabase("chart",$chart),$connection);
		
		if($valueExecutionQuery and $valueExecutionQuery1 and $valueExecutionQuery2 and $valueExecutionQuery3)
			echo '{"return":"0","id_winner":"'.$id_winner.'"}';
		else
			echo '{"return":"-1"}';
	}
	
	if ((andTheWinnerIs($diff_timestamp_one, $diff_timestamp_two) == 2) and ($num_faults_two == 0)){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se il secondo giocatore ha sparato in minor tempo, allora è designato come vincitore del duello. :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$id_winner = $player_two;
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Quindi, si modifica la tupla del challenge con l'ID del vincitore nel campo id_winner.         :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$challenge = '{"id":"'.$id_duels.'","id_winner":"'.$id_winner.'"}';
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challenge),$connection);
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Si prendono le statistiche dei giocatori, e la classifica, e si effettuano i calcoli.  :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore due.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_two.'"}';
		$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery1 != 0){
			$row = $valueExecutionQuery1[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_two.'","num_faults":"'.($jsonString->num_faults+$num_faults_two).'","num_winner_matches":"'.addWorLorC($jsonString->num_winner_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_two).'"}';
		$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore uno.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_one.'"}';
		$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery2 != 0){
			$row = $valueExecutionQuery2[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_one.'","num_faults":"'.($jsonString->num_faults+$num_faults_one).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_one).'"}';
		$valueExecutionQuery2 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		//::::::::::::::::::::::::::://
		//::      Classifica.      :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getChart = '{"id_player":"'.$player_two.'"}';
		$valueExecutionQuery3 = executionSelectQuery(selectQueryIntoDatabase("chart",$getChart,NULL),$connection);
		if($valueExecutionQuery3 != 0){
			$row = $valueExecutionQuery3[0];
			$tmp = '{"global_chart":"'.$row[1].'"}';
		}
		$jsonString = json_decode($tmp);			
		$chart = '{"id_player":"'.$player_two.'","global_chart":"'.addWorLorC($jsonString->global_chart).'"}';
		$valueExecutionQuery3 = executionQuery(updateQueryIntoDatabase("chart",$chart),$connection);
		
		if($valueExecutionQuery and $valueExecutionQuery1 and $valueExecutionQuery2 and $valueExecutionQuery3)
			echo '{"return":"0","id_winner":"'.$id_winner.'"}';
		else
			echo '{"return":"-1"}';
	}
	
	if(($num_faults_one != 0) and ($num_faults_two != 0)){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Se il secondo e il primo giocatore hanno sbagliato il bersaglio, entrambe hanno perso.    :://	
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Si prendono le statistiche dei giocatori, e la classifica, e si effettuano i calcoli.     :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Quindi, si modifica la tupla del challenge con l'ID del vincitore nel campo id_winner.         :://
		//:: Avendo entrambe fatto fault, hanno perso e quindi l'id_winner sarà 0                           :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$challenge = '{"id":"'.$id_duels.'","id_winner":"0"}';
		$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challenge),$connection);
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore due.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_two.'"}';
		$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery1 != 0){
			$row = $valueExecutionQuery1[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_two.'","num_faults":"'.($jsonString->num_faults+$num_faults_two).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_two).'"}';
		$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		//::::::::::::::::::::::::::://
		//:: Stats giocatore uno.  :://
		//::::::::::::::::::::::::::://
		$tmp = NULL;
		$getStats = '{"id_player":"'.$player_one.'"}';
		$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
		if($valueExecutionQuery2 != 0){
			$row = $valueExecutionQuery2[0];
			$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
		}
		$jsonString = json_decode($tmp);
		$stats = '{"id_player":"'.$player_one.'","num_faults":"'.($jsonString->num_faults+$num_faults_one).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_one).'"}';
		$valueExecutionQuery2 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
		
		$id_winner = NULL;
		if($valueExecutionQuery1 and $valueExecutionQuery2)
			echo '{"return":"0","id_winner":"'.$id_winner.'"}';
		else
			echo '{"return":"-1"}';
	}else{
		if ((andTheWinnerIs($diff_timestamp_one, $diff_timestamp_two) == 1) and ($num_faults_one != 0)){
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Se il primo giocatore ha sparato in minor tempo, però ha mancato il sbagliato,  :://
			//:: allora il giocatore due è designato come vincitore del duello.                  :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			$id_winner = $player_two;
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Quindi, si modifica la tupla del challenge con l'ID del vincitore nel campo id_winner.         :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			$challenge = '{"id":"'.$id_duels.'","id_winner":"'.$id_winner.'"}';
			$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challenge),$connection);
			
			//::::::::::::::::::::::::::://
			//:: Stats giocatore due.  :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getStats = '{"id_player":"'.$player_two.'"}';
			$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
			if($valueExecutionQuery1 != 0){
				$row = $valueExecutionQuery1[0];
				$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
			}
			$jsonString = json_decode($tmp);
			$stats = '{"id_player":"'.$player_two.'","num_faults":"'.($jsonString->num_faults+$num_faults_two).'","num_winner_matches":"'.addWorLorC($jsonString->num_winner_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_two).'"}';
			$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
			
			//::::::::::::::::::::::::::://
			//:: Stats giocatore uno.  :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getStats = '{"id_player":"'.$player_one.'"}';
			$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
			if($valueExecutionQuery2 != 0){
				$row = $valueExecutionQuery2[0];
				$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
			}
			$jsonString = json_decode($tmp);
			$stats = '{"id_player":"'.$player_one.'","num_faults":"'.($jsonString->num_faults+$num_faults_one).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_one).'"}';
			$valueExecutionQuery2 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
			
			//::::::::::::::::::::::::::://
			//::      Classifica.      :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getChart = '{"id_player":"'.$player_two.'"}';
			$valueExecutionQuery3 = executionSelectQuery(selectQueryIntoDatabase("chart",$getChart,NULL),$connection);
			if($valueExecutionQuery3 != 0){
				$row = $valueExecutionQuery3[0];
				$tmp = '{"global_chart":"'.$row[1].'"}';
			}
			$jsonString = json_decode($tmp);			
			$chart = '{"id_player":"'.$player_two.'","global_chart":"'.addWorLorC($jsonString->global_chart).'"}';
			$valueExecutionQuery3 = executionQuery(updateQueryIntoDatabase("chart",$chart),$connection);
			
			if($valueExecutionQuery and $valueExecutionQuery1 and $valueExecutionQuery2 and $valueExecutionQuery3)
				echo '{"return":"0","id_winner":"'.$id_winner.'"}';
			else
				echo '{"return":"-1"}';
		}
		
		if ((andTheWinnerIs($diff_timestamp_one, $diff_timestamp_two) == 2) and ($num_faults_two != 0)){
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Se il secondo giocatore ha sparato in minor tempo, però ha mancato il sbagliato,  :://
			//:: allora il giocatore uno è designato come vincitore del duello.                    :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			$id_winner = $player_one;
			
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Quindi, si modifica la tupla del challenge con l'ID del vincitore nel campo id_winner.         :://
			//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			$challenge = '{"id":"'.$id_duels.'","id_winner":"'.$id_winner.'"}';
			$valueExecutionQuery = executionQuery(updateQueryIntoDatabase("challenge",$challenge),$connection);
			
			//::::::::::::::::::::::::::://
			//:: Stats giocatore uno.  :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getStats = '{"id_player":"'.$player_one.'"}';
			$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
			if($valueExecutionQuery1 != 0){
				$row = $valueExecutionQuery1[0];
				$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
			}
			$jsonString = json_decode($tmp);
			$stats = '{"id_player":"'.$player_one.'","num_faults":"'.($jsonString->num_faults+$num_faults_one).'","num_winner_matches":"'.addWorLorC($jsonString->num_winner_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_one).'"}';
			$valueExecutionQuery1 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
			
			//::::::::::::::::::::::::::://
			//:: Stats giocatore due.  :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getStats = '{"id_player":"'.$player_two.'"}';
			$valueExecutionQuery2 = executionSelectQuery(selectQueryIntoDatabase("stats",$getStats,NULL),$connection);
			if($valueExecutionQuery2 != 0){
				$row = $valueExecutionQuery2[0];
				$tmp = '{"num_faults":"'.$row[1].'","num_winner_matches":"'.$row[2].'","num_loose_matches":"'.$row[3].'","acceleration":"'.$row[4].'"}';
			}
			$jsonString = json_decode($tmp);
			$stats = '{"id_player":"'.$player_two.'","num_faults":"'.($jsonString->num_faults+$num_faults_two).'","num_loose_matches":"'.addWorLorC($jsonString->num_loose_matches).'","acceleration":"'.availableAcceleration($jsonString->num_winner_matches,$jsonString->num_loose_matches,$jsonString->acceleration,$acceleration_two).'"}';
			$valueExecutionQuery2 = executionQuery(updateQueryIntoDatabase("stats",$stats),$connection);
			
			//::::::::::::::::::::::::::://
			//::      Classifica.      :://
			//::::::::::::::::::::::::::://
			$tmp = NULL;
			$getChart = '{"id_player":"'.$player_one.'"}';
			$valueExecutionQuery3 = executionSelectQuery(selectQueryIntoDatabase("chart",$getChart,NULL),$connection);
			if($valueExecutionQuery3 != 0){
				$row = $valueExecutionQuery3[0];
				$tmp = '{"global_chart":"'.$row[1].'"}';
			}
			$jsonString = json_decode($tmp);							
			$chart = '{"id_player":"'.$player_one.'","global_chart":"'.addWorLorC($jsonString->global_chart).'"}';
			$valueExecutionQuery3 = executionQuery(updateQueryIntoDatabase("chart",$chart),$connection);
			
			if($valueExecutionQuery and $valueExecutionQuery1 and $valueExecutionQuery2 and $valueExecutionQuery3)
				echo '{"return":"0","id_winner":"'.$id_winner.'"}';
			else
				echo '{"return":"-1"}';
		}
	
	}
	
	closeConnectionBangServer($connection);	
?>