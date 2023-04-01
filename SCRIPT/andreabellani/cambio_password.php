<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$vecchia_password = $_POST['password0'];
	$nuova_password1 = $_POST['password1'];
	$nuova_password2 = $_POST['password2'];
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
					if (strcmp($password, $vecchia_password)!=0) //è stata inserita la password attuale?
						{
							print("
								<script>
									alert('devi inserire la tua password attuale!');
									window.open('".$host."/menu.php', '_self');
								</script>
							");
						}
					else
						{
							if (strcmp($nuova_password1, $nuova_password2)!=0) //le 2 password sono uguali?
								{
									print("
										<script>
											alert('l\' inserimento della nuova password non corrisponde');
											window.open('".$host."/menu.php', '_self');
										</script>
									");
								}
							else
								{
									if ($nuova_password1=="") //è stato inserito una password diversa da ""?
										{
											print("
												<script>
													alert('questa password non e\' ammissibile');
													window.open('".$host."/menu.php', '_self');
												</script>
											");
										}
									else
										{
											if (strcmp($nuova_password1, $vecchia_password)==0) //se la password nuova è uguale a quella vecchia
												{
													print("
														<script>
															alert('questa e\' gia\' la tua password');
															window.open('".$host."/menu.php', '_self');
														</script>
													");
												}
											else
												{
													//cambio password
													$query = "UPDATE utente SET Password='".$nuova_password1."' WHERE ID='".$id."';";
													mysqli_query($con, $query);
													print("
														<script>
															alert('la password e\' stata cambiata, e\' necessario rieseguire l\'accesso');
															window.open('".$host."/logout.php');
															window.close();
														</script>
													");
												}
										}
								}
						}
				}
			else
				{
					session_unset();
					session_destroy();	
					print("
						<script>
							alert('le tue credenziali sono stati cambiate in una sessione diversa da questa, e\' necessario rieseguire l\'accesso');
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