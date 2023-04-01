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
										Le mie pubblicazioni
									</title>
									<!--
										funzioni da attivare nel momento in cui un pulsante della menubar viene cliccato
									-->
									<script type='text/javascript'>
										function chiudi () <!-- log out -->
											{
												window.location.replace('".$host."/logout.php', '_self');
											}
										function carica () <!-- carica un tuo documento -->
											{
												window.location.replace('".$host."/carica.php', '_self');
											}
										function ilmioprofilo () <!-- vai al tuo profilo -->
											{
												window.location.replace('".$host."/menu.php', '_self');
											}
									</script>
									<!-- importazione di icone e skin -->	
									<link rel='icon' href='icon.png' type='image/png' /> 
									<link rel='stylesheet' type='text/css' href='".$host."/Skin/".$skin.".css'>
									<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
									<script src='https://kit.fontawesome.com/a076d05399.js'></script>
								</head>
								<body>
									<table>
										<!-- creazione menubar -->
										<tr class='menubar'>
											<th colspan='3'>
												<button onClick='ilmioprofilo()' class='generic_buttons'>
													<i class='fa fa-user'></i>
													Il mio profilo
												</button>
											</th>
											<th colspan='3'>
												<button onClick='chiudi()' class='delete_buttons'>
													<i class='fas fa-sign-out-alt'></i>
													Log out
												</button>
											</th>
										</tr>
										<!-- creazione intestazione tabella -->
										<tr>
											<th>
												<button class='delete_buttons' id='status' style='background-color: orange' title='topic'>
													<i class='fas fa-book'></i>
												</button>
											</th>
											<th>
												<button class='delete_buttons' id='status' style='background-color: brown' title='documento'>
													<i class='far fa-file'></i>
												</button>
											</th>
											<th>
												<button class='delete_buttons' id='status' style='background-color: magenta' title='giudizio'>
													<i class='fas fa-balance-scale'></i>
												</button>
											</th>
											<th>
												<div class='delete_buttons' id='status' title='status'>
													<i class='fa fa-eye'></i>
												</div>
											</th>
											<form action='".$host."/elimina_documenti.php' method='post'>
											<th>
												<div class='delete_buttons' id='download' title='download'>
													<i class='fa fa-download'></i>
												</div>
											</th>
											<th>
												<button type='submit' class='delete_buttons' id='delete' title='Cliccami per eliminarli!'>
													<i class='fa fa-trash'></i>
												</button>
											</th>
										</tr>
						");
					$n_righe=mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM documento;")); //conteggio righe
					//creazione della tabella
					for ($i=0; $i<$n_righe[0]; $i++) //per ogni riga
						{
							$risultato = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM documento LIMIT ".$i.", 1;")); //seleziona tutti i campi della riga "i"
							$token = strtok($risultato[1], "$"); //estrai il suo nome
							$token = strtok("$"); //rimuovi il carattere separatore "$" (così da non stampare anche l'ID)
							if ($risultato[4]==$id) //se l'ID dell'utente corrisponde con quello memorizzato nell'attributo col vincolo di foreign key
								{
									/**
										in base allo status del documento, imposta una faccina, 
										un colore ed un tooltip al passaggio del mouse sopra la faccina
									**/	
									switch ($risultato[3])
										{
											case 0:
												$icon = "far fa-frown-o";
												$color = "crimson";
												$tooltip = "non approvato";
											break;
											case 1:
												$icon = "far fa-meh-blank";
												$color = "aqua";
												$tooltip = "non ancora guardato";
											break;
											case 2:
												$icon = "far fa-meh-o";
												$color = "gold";
												$tooltip = "da migliorare";
											break;
											case 3:
												$icon = "far fa-smile-o";
												$color = "lime";
												$tooltip = "approvato";
											break;
										}
									print("
										<tr style='border: 2px solid black; background-color: ".$color."'> <!-- stampa la riga del colore in base allo status -->
											<th class='cells'>".$risultato[5]."</th> <!-- stampa il topic del documento -->
											<th class='cells' style='text-align: right'>".$token."</th> <!-- stampa il nome senza il suo ID -->
											<th class='cells'>".$risultato[2]."</th> <!-- stampa il giudizio -->
											<th class='cells' title='".$tooltip."'><i class='".$icon."'></i></th> <!-- stampa la faccina in base allo status -->
											<th class='cells' style='text-align: center; background-color: blueviolet'> <!-- stampa la cella per il download -->
												<a href='".$host."/Documenti/".$risultato[1]."'> <!-- link al file sul server -->
													<i class='fa fa-download'></i>
												</a>
											</th>
											<th class='cells' style='background-color: red'> <!-- stampa la checkbox per l'eliminazione -->
												<input name=".$risultato[0]." type='checkbox'> <!-- la checkbox avrà come nome l'ID del documento -->
											</th>
										</tr>
									");
								}
						}
					print("
									</table>
								</form>
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
								<table>
									<th colspan='3'> 
										<button onClick='carica()' class='generic_buttons' style='width:80%'>
											<i class='fas fa-upload'></i>
											Carica un tuo documento!
										</button>
									</th>
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