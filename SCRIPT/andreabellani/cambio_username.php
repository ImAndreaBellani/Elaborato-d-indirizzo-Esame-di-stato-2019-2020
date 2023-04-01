<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$vecchio_username = $_POST['username0'];
	$nuovo_username1 = $_POST['username1'];
	$nuovo_username2 = $_POST['username2'];
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
					if (strcmp($username, $vecchio_username)!=0) //è stato inserito lo username attuale?
						{
							print("
								<script>
									alert('devi inserire il tuo username attuale!');
									window.open('".$host."/menu.php', '_self');
								</script>
							");
						}
					else
						{
							if (strcmp($nuovo_username1, $nuovo_username2)!=0) //i 2 username sono uguali?
								{
									print("
										<script>
											alert('l\' inserimento del nuovo username non corrisponde');
											window.open('".$host."/menu.php', '_self');
										</script>
									");
								}
							else
								{
									if ($nuovo_username1=="") //è stato inserito un nuovo username diverso da ""?
										{
											print("
												<script>
													alert('questo username non e\' ammissibile');
													window.open('".$host."/menu.php', '_self');
												</script>
											");
										}
									else
										{
											//ricerca del nuovo username nel database
											$query = "SELECT Username FROM utente WHERE Username='".$nuovo_username1."';";
											$is_equal=mysqli_fetch_array(mysqli_query($con, $query));
											if ($is_equal[0] != "") //se qualcuno ha già preso quello username
												{
													print("
														<script>
															alert('questo username e\' gia\' presente');
															window.open('".$host."/menu.php', '_self');
														</script>
													");
												}
											else
												{
													//cambio username
													$query = "UPDATE utente SET Username='".$nuovo_username1."' WHERE ID='".$id."';";
													mysqli_query($con, $query);
													print("
														<script>
															alert('lo username e\' stato cambiato, e\' necessario rieseguire l\'accesso');
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
							alert('le tue credenziali sono stati cambiate in una sessione diversa questa, e\' necessario rieseguire l\'accesso');
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