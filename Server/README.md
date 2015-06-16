-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	Le variabili json che vengono passate in input devono contenere:

		1) Per l'inserimento nel database:
			1.1) {"campo del database":"valore da inserire in quel campo"}
			1.2) {"campo del database":"valore da inserire in quel campo",...,"campo del database":"valore da inserire in quel campo"}
	
		Ovviamente, per l'inserimento, il json non può essere vuoto, altrimenti il database non inserisce e si può incappare in 
		un eccezione dettata dagli standard dei database.

		2) Per la modifica e la cancellazione nel database:
			2.1) {"campo del database da ricercare":"valore da ricercare in quel campo","campo del database da modificare":"valore da modificare in quel campo"}
			2.2) {"campo del database da ricercare":"valore da ricercare in quel campo","campo del database da modificare":"valore da modificare in quel campo",...,"campo del database da modificare":"valore da modificare in quel campo"}

		In questo caso, come nel precedente, il json deve contenere almeno una chiave valore, altrimenti si và in errore.
	
		3) Per la letture dei dati nel database: 		
			3.1) {"campo del database da ricercare":"valore da ricercare in quel campo"}
			3.2) {"campo del database da ricercare":"valore da ricercare in quel campo",...,"campo del database da ricercare":"valore da ricercare in quel campo"}
			3.3) {}
	
		A differenza delle precedenti, qualora il json non contiene chiavi, vengono restituite tutte le tuple di quella tabella
	
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	Quando si vuole richiamare la funzione che crea la query (ad esempio updateQueryIntoDatabase()):
		1) Il primo paramentro deve contenere la tabella in cui si vuole operare
		2) Il secondo parametro deve contenere la variabile json formattata nel precedente modo

	Una volta creata la query, l'output uscente da questa funzione deve essere passato il input alla/e funzioni che generano
	l'esecuzione della query:

		1) Se si tratta di un inserimento, modifica o cancellazione -> si deve utilizzare executionQuery()
		2) Se si tratta di una lettura -> si deve utilizzare executionSelectQuery()

-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	Inoltre, ci sono anche altre funzioni create ad-hoc per qualche operazione non prevista precedentemente.
	In quel caso, nella connection.php - pagina php contenente tutte le funzioni utilizzare - sono stati 
	riportati, sotto ogni funzione, dei commenti che spiegano la loro logica, nonchè il loro utilizzo e le
	loro caratteristiche.