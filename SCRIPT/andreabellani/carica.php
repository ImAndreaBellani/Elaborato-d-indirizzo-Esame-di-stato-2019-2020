<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
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
					$r_color = mysqli_fetch_array(mysqli_query($con, "SELECT FKNomeSkin FROM utente WHERE Username='".$username."';"));
					$skin = $r_color[0];
					print("
						<html>
							<title>
								Carica
							</title>
							<head>
								<script type='text/javascript'>
									function chiudi ()
										{
											window.location.replace('".$host."/logout.php', '_self');
										}
									function ilmioprofilo ()
										{
											window.location.replace('".$host."/menu.php', '_self');
										}
									function lemiepubblicazioni ()
										{
											window.location.replace('".$host."/lemiepubblicazioni.php', '_self');
										}
								</script>	
								<link rel='icon' href='icon.png' type='image/png' />
								<link rel='stylesheet' type='text/css' href='".$host."/Skin/".$skin.".css'>
								<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
								<script src='https://kit.fontawesome.com/a076d05399.js'></script>
							</head>
						<body>
							<table>
								<tr  class='menubar'>
									<th>
										<button onClick='ilmioprofilo()' class='generic_buttons'>
											<i class='fa fa-user'></i>
											Il mio profilo
										</button>
									</th>
									<th>
										<button onClick='lemiepubblicazioni()' class='generic_buttons'>
											<i class='fas fa-folder-open'></i>
											Le mie pubblicazioni
										</button>
									</th>
									<th>
										<button onClick='chiudi()' class='delete_buttons'>
											<i class='fas fa-sign-out-alt'></i>
											Log out
										</button>
									</th>
								</tr>
								<tr>
									<th colspan='3'>
										<!-- form per l'upload-->
										<form method='post' action='".$host."/upload.php' method='post' enctype='multipart/form-data' class='menu_form'>
										<br>
										Scegli il topic su cui vuoi caricare il file:
										<br>
										<br>
										<select name='topic' required>
										<br>
										<option disabled selected value> seleziona il topic </option>"); //prima voce senza nessun topic
										$query = "SELECT COUNT(*) FROM topic;"; //numero di topic
										$n_topic=mysqli_fetch_array(mysqli_query($con, $query));
										for ($i=0; $i<$n_topic[0]; $i++) //fino a quando ci sono topic da inserire nel menù a tendina
											{
												$query = "SELECT * FROM topic LIMIT ".$i.", 1;"; //inseriscilo
												$topic=mysqli_fetch_array(mysqli_query($con, $query));
												print
													("
														<option value='".$topic[0]."'> <!-- agli il valore del nome del topic -->
															".$topic[0]." | ".$topic[1]." | ".$topic[2]." <!-- scrivi nome, formato e descrizione -->
														</option>
													");
											}
										print
											("
											</select>
											<br>
											<br>
											<input type='file' name='file1' class='file_sub'> <!-- inserisci l'oggetto per caricare il file -->
											<br>
											<br>
											<button type='submit' name='submit' class='generic_buttons'> <!-- bottone per l'invio -->
												<i class='fas fa-upload'></i>
												Carica!
											</button>
										</th>
									</form>
								</tr>
							</table>
						</body>
					</html>
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