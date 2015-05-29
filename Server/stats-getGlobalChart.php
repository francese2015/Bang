<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php per ottenere le statistiche del giocatore con id passato come parametro.   :://
	//:: La stringa dovrà essere composta nel seguente modo:                                   :://	
	//:: '{"CAMPO DA RICERCARE":"VALORE DA RICERCARE"}'                                        :://	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Carlos Borges.                                                             :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Modificato da: Valentino Vivone.                                                      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	include "connection.php";
	
	$connection = connectionBangServer();
	
	$playerID = $_GET["chart"];
	$jsonString = json_decode($playerID);
	
	$idPlayer = $jsonString->id_player;
	$globalChart = NULL;
	$globalPoints = NULL;
	$arrReturn = array();
	$arr = array();
	
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Cerco di trovare, all'interno della tabella chart, la mia posizione nella classifica.  :://
	//:: Qualora esisto nella tabella, viene salvato la posizione e i punti GLOBALI. Se ci sono :://
	//:: errori, si ritorna -1.                                                                 :://
	//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("chart","{}","global_chart DESC"),$connection);
	if($valueExecutionQuery1 == 0)
		echo '{"return":"-1"}';
	else{
		for($j=0;$j<count($valueExecutionQuery1);$j++){
			$row1 = $valueExecutionQuery1[$j];
			if($row1[0] == $idPlayer){
				$globalChart = $j+1;
				$globalPoints = $row1[1];
				break;
			}
		}
	}
	
	$tmp = array('global_chart'=>$globalChart,'globalPoints'=>$globalPoints);
	array_push($arr,$tmp);
	
	array_push($arrReturn,$arr);
	$arr = array();
	
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: L'array di JSON che si crea conterrà la posizione globale di tutti i duellanti.       :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	$valueExecutionQuery = executionSelectQuery(getGlobalChart(),$connection);
	if($valueExecutionQuery == 0){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: lettura dalla tabella chart non riuscita correttamente.                               :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	  echo '{"return":"-1"}';
	}else{
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: lettura da chart riuscita correttamente.                                              :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: L'array di JSON che si crea conterrà la posizione globale dei duellanti.              :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		if(count($valueExecutionQuery)>=20){
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Controlle se ci sono più di 6 row.                                                    :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			for($i=0;$i<20;$i++){
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Aggiunta dei primi 6 posti.                                                           :://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
				$row = $valueExecutionQuery[$i];
				 
				$strTmp = '{"id":"'.$row[0].'"}';
				$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("user_account",$strTmp,NULL),$connection);
				if($valueExecutionQuery1 == 0){
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					//:: Lettura non avvenuta.                                                                 :://
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
					echo '{"return":"-1"}';
				}else{
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					//:: Lettura avvenuta con successivo ritorno del nominativo dei duellanti.                 :://
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					for($j=0;$j<count($valueExecutionQuery1);$j++){
						$row1 = $valueExecutionQuery1[$j];
						$vsName = $row1[4]." ".$row1[5];
					}
				}
			
				 $tmp = array('vsName'=>$vsName,'global_chart'=>$row[1]);
				 array_push($arr,$tmp);
			  }
			  array_push($arrReturn,$arr);
			  $arr = array();
		}else{
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			//:: Se ci sono meno di 6 row.                                                             :://
			//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
			for($i=0;$i<count($valueExecutionQuery);$i++){
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Aggiunta dei primi n<6 posti.                                                         :://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
				$row = $valueExecutionQuery[$i];
				 
				$strTmp = '{"id":"'.$row[0].'"}';
				$valueExecutionQuery1 = executionSelectQuery(selectQueryIntoDatabase("user_account",$strTmp,NULL),$connection);
				if($valueExecutionQuery1 == 0){
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					//:: Lettura non avvenuta.                                                                 :://
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
			
					echo '{"return":"-1"}';
				}else{
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					//:: Lettura avvenuta con successivo ritorno del nominativo dei duellanti.                 :://
					//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					for($j=0;$j<count($valueExecutionQuery1);$j++){
						$row1 = $valueExecutionQuery1[$j];
						$vsName = $row1[4]." ".$row1[5];
					}
				}
			
				$tmp = array('vsName'=>$vsName,'global_chart'=>$row[1]);
				array_push($arr,$tmp);
			}
			array_push($arrReturn,$arr);
		} 
	}
	$result = json_encode($arrReturn);
	echo $result;
	
	closeConnectionBangServer($connection);
?>