<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	if ($_SESSION['id'] != "")
		{
			$username = $_SESSION['username'];
			$password = $_SESSION['password'];
			$id = $_SESSION["id"];
			$con = mysqli_connect($nomeUSB,$userUSB,$passUSB, $dbUSB);
			$query = "SELECT Username FROM utente WHERE ID='".$id."';";
			$actual_username=mysqli_fetch_array(mysqli_query($con, $query));
			$query = "SELECT Password FROM utente WHERE ID='".$id."';";
			$actual_password=mysqli_fetch_array(mysqli_query($con, $query));
			if (($actual_password[0] == $password) AND ($actual_username[0] == $username))
				{
					//quanti documenti richiede la skin selezionata?
					$n_doc_richiesti = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM skin WHERE Nome='".$_POST['skin']."';"));
					//quanti documenti sono associati a questo utente?
					$n_doc_utente = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM documento WHERE FKIDUtente='".$id."';"));
					//l'utente può permettersi la skin?
					if ($n_doc_utente[0]>=$n_doc_richiesti[1])
						{
							//settaggio della skin
							mysqli_query($con, "UPDATE utente SET FKNomeSkin='".$_POST['skin']."' WHERE ID='".$id."';");
							print("
								<script>
									window.open('".$host."/menu.php', '_self');
								</script>
							");
						}
					else
						{
							print("
								<script>
									alert('non hai caricato abbastanza documenti per sbloccare \'".$_POST['skin']."\' ');
									window.open('".$host."/menu.php', '_self');
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