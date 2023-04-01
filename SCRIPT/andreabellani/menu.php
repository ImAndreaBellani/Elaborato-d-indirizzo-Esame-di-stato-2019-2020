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
			$id = $_SESSION["id"];
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
							<head>
								<title>
									Il mio profilo
								</title>
								<script type='text/javascript'>
									function chiudi ()
										{
											window.open('".$host."/logout.php', '_self');
										}
									function carica ()
										{
											window.open('".$host."/carica.php', '_self');
										}
									function pubblicazioni ()
										{
											window.open('".$host."/lemiepubblicazioni.php', '_self');
										}
									function elimina ()
										{
											if (prompt('inserisci \"ELIMINA\"') == 'ELIMINA') //l'utente deve scrivere 'ELIMINA'
												{
													if (prompt('inserisci la tua password') == '".$password."') //l'utente deve inserire la sua password
														{
															window.open('".$host."/elimina_profilo.php', '_self'); //processo di eliminazione avviato
														}
													else
														{
															alert('questa non e\' la tua password');
														}
												}		
										}
									function info ()
										{
											window.open('".$host."/info.php', '_self');
										}
								</script>
								<link rel='icon' href='icon.png' type='image/png' />
								<link rel='stylesheet' type='text/css' href='".$host."/Skin/".$skin.".css'>
								<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
								<script src='https://kit.fontawesome.com/a076d05399.js'></script>
							</head>
							<body>
								<table>
									<tr class='menubar'>
										<th>
											<button onclick='pubblicazioni()' class='generic_buttons'>
												<i class='fas fa-folder-open'></i>
												Le mie pubblicazioni
											</button>
										</th>
										<th>
											<button onClick='carica()' class='generic_buttons'>
												<i class='fas fa-upload'></i>
												Carica un tuo documento!
											</button>
										</th>
										<th>
											<button class='delete_buttons' onClick='elimina()' class='generic_buttons'>
												<i class='fa fa-user-times'></i>
												Elimina profilo
											</button>
										</th>
										<th>
											<button class='delete_buttons' onClick='chiudi()' class='generic_buttons'>
												<i class='fas fa-sign-out-alt'></i>
												Log out
											</button>
										</th>
									</tr>
									<tr>
										<th colspan='2'>
											<form action='".$host."/cambio_username.php' class='menu_form' method='post'>
												<br>Il tuo vecchio username:<br>
												<input type='text' name='username0' class='actual_username'><br>
												Il tuo nuovo username:<br>
												<input type='text' name='username1' class='new_username'><br>
												Reinserisci il nuovo username:<br>
												<input type='text' name='username2' class='new_username'><br>
												<button type='submit' class='generic_buttons'>
													<i class='fa fa-refresh'></i>
													Cambia
												</button>
											</form>
										</th>
										<th colspan='2'>
											<form action='".$host."/cambio_password.php' class='menu_form' method='post'>
												<br>La tua vecchia password:<br>
												<input type='password' name='password0' class='actual_password'><br>
												La tua nuova password:<br>
												<input type='password' name='password1' class='new_password'><br>
												Reinserisci la nuova password:<br>
												<input type='password' name='password2' class='new_password'><br>
												<button type='submit' class='generic_buttons'>
													<i class='fa fa-refresh'></i>
													Cambia
												</button>
											</form>
										</th>
									</tr>
								</table>
								<form action='".$host."/cambia_skin.php' method='post' class='menu_form'>
									Scegli la tua skin:<br>
									<select name='skin'><br>");
										$query = "SELECT COUNT(*) FROM skin;";
										$n_skin=mysqli_fetch_array(mysqli_query($con, $query));
										for ($i=0; $i<$n_skin[0]; $i++)
											{
												$query = "SELECT * FROM Skin LIMIT ".$i.", 1;";
												$skin=mysqli_fetch_array(mysqli_query($con, $query));
												print("
													<option value='".$skin[0]."'>".$skin[0]." | documenti richiesti: ".$skin[1]." | ".$skin[2]."</option>
													");
											}
										print("
									</select><br>
									<button type='submit' class='generic_buttons'>
										<i class='fa fa-refresh'></i>
										Cambia
									</button>
								</form>
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