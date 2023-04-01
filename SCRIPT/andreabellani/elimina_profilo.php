<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$uploads_dir = 'C:\\Users\\Andrea\\Desktop\\Andrea\\Scuola\\Informatica\\Laboratorio\\PHP\\usbwebserver\\root\\andreabellani\\Documenti';
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$NUM_MAX_USER = 1000;
	$NUM_MAX_DOCUMENTI_PER_USER = 10;
	$NUM_MAX_DOCUMENTI_TOTALI = $NUM_MAX_USER*$NUM_MAX_DOCUMENTI_PER_USER;
	if ($_SESSION['id'] != "") //se nella variabile della sessione è scritto qualcosa (altrimenti significa che la pagina è stata aperta da qualcuno che non ha effettuato l'accesso
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
					for ($i=0; $i<$NUM_MAX_DOCUMENTI_TOTALI; $i++) //per ogni ID possibile di un documento
						{
							//si controlla se il suo FKIDUtente è uguale all'ID dell'utente che vuole eliminarsi
							$query = "SELECT Nome FROM documento WHERE ID=".$i." AND FKIDUtente=".$id.";";
							$nome_file = mysqli_fetch_array(mysqli_query($con, $query));
							if ($nome_file[0]!="") //se quel file corrisponde all'utente che vuole eliminarsi
								{
									unlink($uploads_dir."\\".$nome_file[0]); //eliminazione del file dalla directory
								}
						}
					//eliminazione di tutte le entry dei suoi documenti
					$query = "DELETE FROM documento WHERE FKIDUtente=".$id.";";
					mysqli_query($con, $query);
					//eliminazione dell'utente
					$query = "DELETE FROM utente WHERE ID=".$id.";";
					mysqli_query($con, $query);
					print("
						<script>
							alert('la tua utenza ed i tuoi file sono stati eliminati con successo, speriamo di rivederti presto');
							window.open('".$host."/logout.php');
							window.close();
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