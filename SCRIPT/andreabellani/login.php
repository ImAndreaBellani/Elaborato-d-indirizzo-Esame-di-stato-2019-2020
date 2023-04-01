<?php
	session_start();	
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$con = mysqli_connect($nomeUSB,$userUSB,$passUSB, $dbUSB);
	$query = "SELECT Username FROM utente WHERE Username='".$_POST['username']."'"; //query per trovare lo username inserito
	$my_query = mysqli_query($con, $query);
	if ($my_query) //se la query è stata formulata correttamente
		{
			$exists = mysqli_fetch_array($my_query);
			if ($exists[0]=="") //se non è stato restituito un risultato
				{
					session_destroy();
					print("
						<script>
							alert('questo username non e\' presente nel nostro database');
							window.close();
						</script>
					");
				}
			else
				{
					$query = "SELECT Password FROM utente WHERE Username='".$_POST['username']."';"; //ottieni la password di quello username
					$my_query = mysqli_query($con, $query); //questa query è la stessa di prima, ma selezione un'intestazione differente, un controllo sarebbe superfluo
					$pass = mysqli_fetch_array($my_query);
					if ($pass[0] != $_POST['password']) //se le password non corrispondono
						{
							session_destroy();
							print("
								<script>
									alert('la password non e\' corretta');
									window.close();
								</script>
							");
						}
					else
						{
							$query = "SELECT ID FROM utente WHERE Username='".$_POST['username']."';"; //ottieni l'ID (servirà nelle prossime pagine)
							$my_query = mysqli_query($con, $query);
							$id = mysqli_fetch_array($my_query);
							$_SESSION['id'] = $id[0];
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['password'] = $_POST['password'];
							print("
								<script>
									window.open('".$host."/lemiepubblicazioni.php', '_self');
								</script>
							");
						}
				}
		}
	else
		{
			session_destroy();
			print("
				<script>
					alert('ops, qualcosa e\' andato storto');
					window.close();
				</script>
			");
		}
?>