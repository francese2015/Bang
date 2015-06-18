<?php
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Pagina php contenente tutte le funzioni che saranno utilizzate nelle pagine php.      :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	//:: Creato da: Valentino Vivone.                                                          :://
	//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	
	function connectionBangServer(){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Connessione al database presente su openshift. $servername, $username e $password sono variabili  :://
        //:: che immagazzinano valori rilasciati da openshift per accedere al database.		                   :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$servername = "127.11.16.2:3306";	$username = "admin3kEW5iP";		$password = "a7l1DrcJDJN-";

		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Si crea la connessione con il server.  :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		$connection = mysql_connect($servername, $username, $password);

		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Check della connessione.               :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		if (!$connection) {
			die("Connection failed: ".mysql_error());
		}
		
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Creazione connessione con BangServer.  :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		$database = mysql_select_db ("bangserver", $connection);
		
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Check della connessione.               :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		if (!$database) {
			die("Connection database failed: ". mysql_error());
		}
		
		return $connection;
	}
	
	function connectionChurchbellServer(){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Connessione al database presente su openshift. $servername, $username e $password sono variabili  :://
        //:: che immagazzinano valori rilasciati da openshift per accedere al database.		                   :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$servername = "127.11.16.2:3306";	$username = "admin3kEW5iP";		$password = "a7l1DrcJDJN-";

		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Si crea la connessione con il server.  :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		$connection = mysql_connect($servername, $username, $password);

		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Check della connessione                :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		if (!$connection) {
			die("Connection failed: ".mysql_error());
		}
		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Si crea connessione con il database ChurchbellServer  :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$database = mysql_select_db ("churchbellserver", $connection);
		
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Check della connessione                :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		if (!$database) {
			die("Connection database failed: ". mysql_error());
		}
		
		return $connection;
	}
	
	function closeConnectionBangServer($server){
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Chiude la connessione                  :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		$close_connection = mysql_close($server);
		if(!$close_connection) {
			die("Close connectione failed: ".mysql_error());
		}
		return $close_connection;
	}
	
	function convertStringToInt($variable){
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Conversione String in intero           :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		return intval($variable);
	}
	
	function convertStringToFloat($variable){
		//:::::::::::::::::::::::::::::::::::::::::::://
		//:: Conversione String in float.           :://
		//:::::::::::::::::::::::::::::::::::::::::::://
		return floatval($variable);
	}
	
	function getTheNamesOfTheFields($variable){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Funzione che permette di, mediante l'uso della funzione multiexplode, ritornare     :://
		//:: un array contenente i campi presenti nella variabile stringa $variable.             :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$exploded = multiexplode(array("{","}",",","\":\"","\":","\""),$variable);
		$length_exploded = count($exploded);
		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Le posizioni dei campi del database stanno in posizioni pari.                       :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$fields = array();
		for($i=0; $i<$length_exploded; $i=$i+2){
			if($exploded[$i] != "")
				array_push($fields,$exploded[$i]);
		}
		return $fields;
	}
	
	function getTheValuesOfTheFields($variable){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Funzione che permette di, mediante l'uso della funzione multiexplode, ritornare     :://
		//:: un array contenente i campi presenti nella variabile stringa $variable.             :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$exploded = multiexplode(array("{","}",",","\":\"","\":","\""),$variable);
		$length_exploded = count($exploded);
		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Le posizioni dei valori del database stanno in posizioni dispari.                   :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$values = array();
		for($i=1; $i<$length_exploded; $i=$i+2){
			if($exploded[$i] != ""){
				
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//:: Qualora la stringa $exploded[$i] fosse un valore INTEGER, si memorizza il corrispettivo integer :://
				//:: nell'array $values; caso inverso, si effettua un nuovo multiexplode per constatare se           :://
				//:: tale variabile non è in formato DATA. Qualora non fosse una variabile DATA, si memorizza        :://
				//:: direttamente nella variabile $values, altrimenti si effettua il multiexplode e si memorizza     :://
				//:: il risultato. Lo stesso controllo si effettua per vedere se il valore è FLOAT                   :://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				
				$stringOrInteger = convertStringToInt($exploded[$i]);
				$stringOrFloat = convertStringToFloat($exploded[$i]);
				
				if( ($stringOrInteger != 0) and ($stringOrInteger === $stringOrFloat) ){
					array_push($values,$stringOrInteger);
				}else{
					if($stringOrInteger < $stringOrFloat){
						array_push($values,$stringOrFloat);
					}else{
						
						//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
						//:: Il multiexplode per le parentesi ( e ) è prettamente per il campo data.             :://
						//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
					
						$expl = multiexplode(array("(",")"),$exploded[$i]);
						
						$length_tmp = count($expl);
						if( $length_tmp == 1){
							array_push($values,$exploded[$i]);
						}else{
							array_push($values,$expl[1]);
						}
					}
				}
			}
		}
		return $values;
	}
	
	function multiexplode ($delimiters,$string) {
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Multiexplore() = concatenazioni di split.                                           :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}

	function insertQueryIntoDatabase($table,$stringJson){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione crea una stringa per l'inserimento all'interno del database.                                               :://
		//:: 1) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_DATABASE":"VALORE_DA_INSERIRE"}';                                         :://
		//:: 2) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_DATABASE":"VALORE_DA_INSERIRE","CAMPO_DATABASE":"VALORE_DA_INSERIRE"}';   :://
		//::                                                                                                                            :://
		//:: Si possono inserire campi e valori senza dover tener conto del loro ordine, l'importante è che la stringa in input         :://
		//:: NON SIA VUOTA.                                                                                                             :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$fields = getTheNamesOfTheFields($stringJson);
		$values = getTheValuesOfTheFields($stringJson);
		$sizeArray = count($fields);
		
		$returnString = "INSERT INTO ".$table." (";
		for ($i=0; $i<$sizeArray-1; $i++){
			$returnString = $returnString.$fields[$i].",";
		}
		$returnString = $returnString.$fields[$sizeArray-1].") VALUES (";
		for ($i=0; $i<$sizeArray-1; $i++){
			$returnString = $returnString."'".$values[$i]."',";
		}
		$returnString = $returnString."'".$values[$sizeArray-1]."');";
		
		return $returnString;		
	}

	function selectQueryIntoDatabase($table, $stringJson, $orderSelection){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione crea una stringa per la lettura dei dati del database.                                                            :://
		//:: 1) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_DA_RICERCARE":"VALORE_DA_INSERIRE"}';                                            :://
		//:: 2) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_DA_RICERCARE":"VALORE_DA_INSERIRE","CAMPO_DA_RICERCARE":"VALORE_DA_INSERIRE"}';  :://
		//::                                                                                                                                   :://
		//:: Si possono inserire campi e valori senza dover tener conto del loro ordine; Per una SELECT ALL, basta inserire una                :://
		//:: STRINGA => '{}'; Per avere un ORDER BY, basta inserirlo nell'apposito parametro, altrimenti basta inserire NULL.                  :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$fields = getTheNamesOfTheFields($stringJson);
		$values = getTheValuesOfTheFields($stringJson);
		$sizeArray = count($fields);
		
		$order_by = "";
		if($orderSelection != NULL)
			$order_by = " ORDER BY ".$orderSelection;
		
		$returnString = "SELECT * FROM ".$table;
		if($sizeArray > 0){
			if($sizeArray == 1){
				$returnString = $returnString." WHERE ".$fields[0]."='".$values[0]."'";
			}else{
				$returnString = $returnString." WHERE ";
				for($i=0;$i<$sizeArray-1;$i++){
					$returnString = $returnString.$fields[$i]."='".$values[$i]."' AND ";
				}
				$returnString = $returnString.$fields[$sizeArray-1]."='".$values[$sizeArray-1]."'";
			}
		}
		$returnString = $returnString.$order_by.";";
		
		return $returnString;
	}
	
	function updateQueryIntoDatabase($table,$stringJson){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione crea una stringa per la modifica dei dati del database.                                                            :://
		//:: 1) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_PER LA RICERCA":"VALORE_DELLA_RICERCA","CAMPO_DA_MODIFICARE":"APPOSITO_VALORE"}'; :://
		//::                                                                                                                                    :://
		//:: Si possono inserire campi e valori senza dover tener conto del loro ordine;                                                        :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$fields = getTheNamesOfTheFields($stringJson);
		$values = getTheValuesOfTheFields($stringJson);
		$sizeArray = count($fields);
		
		$returnString = "UPDATE ".$table." SET ";
		if($sizeArray == 2){
			$returnString = $returnString.$fields[1]."='".$values[1]."'";
		}else{
			for ($i=1; $i<$sizeArray-1; $i++){
				$returnString = $returnString.$fields[$i]."='".$values[$i]."',";
			}
			$returnString = $returnString.$fields[$sizeArray-1]."='".$values[$sizeArray-1]."'";
		}
		$returnString = $returnString." WHERE ".$fields[0]."='".$values[0]."';";
		return $returnString;
	}
	
	function deleteQueryIntoDatabase($table,$stringJson){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione crea una stringa per la cancellazione dei dati del database.                                                             :://
		//:: 1) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_PER_LA_RICERCA":"VALORE_DELLA_RICERCA"}';                                               :://
		//:: 2) IL FORMATO DELLA STRINGA IN INPUT => '{"CAMPO_PER_LA_RICERCA":"VALORE_DELLA_RICERCA","CAMPO_PER_LA_RICERCA":"VALORE_DELLA_RICERCA"}'; :://                                                                                                                                  :://
		//:: Si possono inserire campi e valori senza dover tener conto del loro ordine;                                                              :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$fields = getTheNamesOfTheFields($stringJson);
		$values = getTheValuesOfTheFields($stringJson);
		$sizeArray = count($fields);
		
		$returnString = "DELETE FROM ".$table." WHERE ";
		if($sizeArray == 1){
			$returnString = $returnString.$fields[0]."='".$values[0]."'";
		}else{
			for ($i=0; $i<$sizeArray-1; $i++){
				$returnString = $returnString.$fields[$i]."='".$values[$i]."' AND ";
			}
			$returnString = $returnString.$fields[$sizeArray-1]."='".$values[$sizeArray-1]."'";
		}
		$returnString = $returnString.";";
		return $returnString;	
	}
	
	function executionSelectQuery($query, $connection){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Esecuzione della query per la select. L'output presenterà un array contenente le tuple        :://
		//:: che sono state richieste.                                                                     ::// 
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		
		$resultQuery = mysql_query($query, $connection);
		if(!$resultQuery)
			return $resultQuery;
		
		$resultSelect = array();
		if (mysql_num_rows($resultQuery) != 0) {
			while ($row = mysql_fetch_array($resultQuery)) {
				$length_row = count($row);
				$var_tmp = array();
				for($i=0;$i<$length_row;$i++){
					array_push($var_tmp,$row[$i]);
				}
				array_push($resultSelect,$var_tmp);
			}
			mysql_free_result($resultQuery);
		}
		return $resultSelect;
	}
	
	function executionQuery($query, $connection){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Esecuzione per le query di INSERT, UPDATE E DELETE                                            :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		return mysql_query($query, $connection);;
	}
	
	function isPlayerOne($id_player,$id_challenge){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "challenge". Mediante la creazione della stringa sottostante,  :://
		//:: si potrà , per l'appunto, creare una query per l'operazione SELECT mirata alla ricerca del duello e del primo giocatore. :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$returnString = "SELECT * FROM challenge WHERE id='".$id_challenge."' AND player_one='".$id_player."';";	
		return $returnString;
	}
	
	function isPlayerTwo($id_player,$id_challenge){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "challenge". Mediante la creazione della stringa sottostante,    :://
		//:: si potrà , per l'appunto, creare una query per l'operazione SELECT mirata alla ricerca del duello e del secondo giocatore. :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
		$returnString = "SELECT * FROM challenge WHERE id='".$id_challenge."' AND player_two='".$id_player."';";	
		return $returnString;
	}
	
	function executionNumRows($query, $connection){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per qualsiasi operazione richieda un conteggio di righe.     :://
		//:: Creata con l'idea di progettare funzioni booleane. Il parametro $query dovrà contenere una stringa      :://
		//:: "SELECT...", ALTRIMENTI PRESENTERà DEGLI ERRORI.                                                        :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$resultQuery = mysql_query($query, $connection);
		if(!$resultQuery)
			return $resultQuery;
		
		return mysql_num_rows($resultQuery);
	}
	
	function getChallengeWithIdPlayer($id_player){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "challenge". Mediante la creazione della stringa sottostante,    :://
		//:: si potrà , per l'appunto, creare una query per l'operazione SELECT mirata alla ricerca del duello e del suo status dove,   :://
		//:: tra i partecipanti al duello, vi è il giocatore XXX.                                                                       :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://	
		$returnString = "SELECT * FROM challenge WHERE player_one='".$id_player."' OR player_two='".$id_player."';";	
		return $returnString;
	}
	
	function getChallengeChurchbellWithIdPlayer($id_player){		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "duels_queue". Mediante la creazione della stringa sottostante,  :://
		//:: si potrà , per l'appunto, creare una query per l'operazione SELECT mirata alla ricerca del duello e del suo status dove,   :://
		//:: tra i partecipanti al duello, vi è il giocatore XXX.                                                                       :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$returnString = "SELECT * FROM duels_queue WHERE player_one='".$id_player."' OR player_two='".$id_player."';";	
		return $returnString;
	}
	
	function selectGetChallengeWithIdPlayer($table, $stringJson, $orderSelection){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "challenge". La stringa passata come parametro                      :://
		//:: dovrà contenere l'id del giocatore, che verrà confrontato con i campi player_one e player_two. Il risultato sarà una stringa  :://
		//:: da eseguire per il ritorno delle tuple inerenti all'id.                                                                       :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://

		$fields = getTheNamesOfTheFields($stringJson);
		$values = getTheValuesOfTheFields($stringJson);
		$sizeArray = count($fields);
		
		$order_by = "";
		if($orderSelection != NULL)
			$order_by = " ORDER BY ".$orderSelection." LIMIT 20";
		
		$returnString = "SELECT * FROM ".$table;
		if($sizeArray > 0){
			if($sizeArray == 1){
				$returnString = $returnString." WHERE ".$fields[0]."='".$values[0]."'";
			}else{
				$returnString = $returnString." WHERE ";
				for($i=0;$i<$sizeArray-1;$i++){
					$returnString = $returnString.$fields[$i]."='".$values[$i]."' OR ";
				}
				$returnString = $returnString.$fields[$sizeArray-1]."='".$values[$sizeArray-1]."'";
			}
		}
		$returnString = $returnString.$order_by.";";
		
		return $returnString;
	}
	
	function availableAcceleration($winn, $loo, $old_acc, $new_acc){
		//:::::::::::::::::::::::::::::::::::::://
		//:: Media = (x1+x2+..+xn)/n          :://
		//:::::::::::::::::::::::::::::::::::::://
		$n = $winn + $loo;
		$old = $old_acc * $n;
		
		$n = $n + 1;
		$new = ($old + $new_acc)/$n;
		return $new;
	}
	
	function andTheWinnerIs($diff_time1, $diff_time2){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione rilascia il vincitore. Poiché i partecipanti sono due e          :://
		//:: nella tabella challenge esistono i campi player_one e player_two, per semplicità :://
		//:: se vince il player_one la funzione rilascia il valore 1, viceversa altrimenti.   :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		if($diff_time1 < $diff_time2){
			return 1;
		}else{
			return 2;
		}
	}
	
	function addWorLorC($val){
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Funzione per incrementare i valori passati in input.  :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		return $val+1;
	}
	
	function getaddress($lat,$lng){
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione rilascia la locazione passando latitudine e longitudine          :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
		$json = @file_get_contents($url);
		$data=json_decode($json);
		$status = $data->status;
		if($status=="OK")
		return $data->results[0]->formatted_address;
		else
		return false;
	}
	
	function lastIdChallenge(){		
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: Questa funzione ritorna l'ultimo ID del duello inserito    :://
		//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		return mysql_insert_id();
	}
	
	function getGlobalChart(){		
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		//:: [AD-HOC] => questa funzione è fatta ad-hoc per la tabella "chart". Mediante la creazione della stringa sottostante,   :://
		//:: si potrà , per l'appunto, creare una query per l'operazione SELECT mirata alla ricerca della classifica globale.      :://
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
		$returnString = "SELECT * FROM chart  ORDER BY global_chart DESC LIMIT 20;";	
		return $returnString;
	}
	
	return;
	
?>