<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$uploads_dir = "C:\\Users\\Andrea\\Desktop\\Andrea\\Scuola\\Informatica\\Laboratorio\\PHP\\usbwebserver\\root\\andreabellani\\Documenti";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$NUM_MAX_USERS = 1000; 
	$NUM_MAX_DOCUMENTI_PER_USER = 10;
	/*
		si calcola il massimo dei documenti
		(gli ID dei documenti potranno andare da 1 a "NUM_MAX_DOCUMENTI_TOTALI")
	*/
	$NUM_MAX_DOCUMENTI_TOTALI = $NUM_MAX_USERS*$NUM_MAX_DOCUMENTI_PER_USER;
	if ($_SESSION['id'] != "")
		{
			$username = $_SESSION['username'];
			$password = $_SESSION['password'];
			$id = $_SESSION['id'];
			$con = mysqli_connect($nomeUSB,$userUSB,$passUSB, $dbUSB);
			$query = "SELECT Username FROM utente WHERE ID='".$id."';";
			$actual_username=mysqli_fetch_array(mysqli_query($con, $query));
			$query = "SELECT Password FROM utente WHERE ID='".$id."';";
			$actual_password=mysqli_fetch_array(mysqli_query($con, $query));
			if (($actual_password[0] == $password) AND ($actual_username[0] == $username))
				{
					for ($i=0; $i<$NUM_MAX_DOCUMENTI_TOTALI; $i++) //si scorrono tutti i possibili valori per l'ID
						{
							if (isset($_POST[$i])) //se è stata selezionato un campo nel form precedente con un nome uguale ad "i"
								{	
									$query = "SELECT Nome FROM documento WHERE ID='".$i."';"; //formula la query per ottenerne il nome
									$files = mysqli_fetch_array(mysqli_query($con, $query)); //invia la query
									//elimina il documento dalla cartella
									unlink($uploads_dir."\\".$files[0]);
									mysqli_query($con, "DELETE FROM documento WHERE ID=".$i." ;"); //elimina la entry dalla tabella
								}
						}
					//setta la skin a quella di default (evitare che un utente possa tenersi una skin premium anche quando
					//ha meno di tot. documenti caricati)
					mysqli_query($con, "UPDATE utente SET FKNomeSkin='Default' WHERE Username='".$username."';");
					//comunica all'utente quanto è accaduto
					print("
						<script>
							alert('documenti eliminati, la skin in uso e\' stata settata su \'Default\'');
							window.open('".$host."/lemiepubblicazioni.php', '_self');
						</script>
					");
				}
			else
				{
					session_unset();
					session_destroy();	
					print("
						<script>
							alert('le tue credenziali sono stati cambiate, e\' necessario rieseguire l\'accesso');
							window.close();
						</script>
					");
				}
		}
	else
		{
			session_unset();
			session_destroy();
			print("
				<script>
					alert('non sei autorizzato ad accedere a questa area');
					window.close();
				</script>
			");
		}
?>