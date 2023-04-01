<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$NUM_MAX_USERS = 1000; //costante che indica il numero massimo di utenti ospitabili
	if ($_POST['password0'] != $_POST['password']) //controllo se le password inserite sono identiche
		{
			session_destroy();
			print(" 
				<script>
					alert('le password non corrispondono, reinserirle uguali per continuare');
					window.close();
				</script>
			");
		}
	else
		{
			if (($_POST['password0'] == "")||($_POST['password'] == "")||($_POST['username'] == "")) //controllo della presenza di tutti i campi
				{
					session_destroy();
					print("
						<script>
							alert('non sono stati inseriti tutti i campi');
							window.close();
						</script>
					");
				}
			else
				{
					$con = mysqli_connect($nomeUSB,$userUSB,$passUSB,$dbUSB);
					if (mysqli_connect_errno())
						{
							session_destroy();
  							print("
								<script>
									alert('si e\' verificato un errore durante la connessione al database');
									window.close();
								</script>
							");
						}
					else
						{
							//formulazione della query per la ricerca di una entry con quello username
							$query = "SELECT Username FROM utente WHERE Username='".$_POST['username']."';";
							$check = mysqli_query($con, $query);
							if (!($check)) //se la query non ha restituito qualcosa perché non è stata formulata correttamente
								{
									session_destroy();
  									print("
										<script>
											alert('ops! qualcosa e\' andato storto');
											window.close();
										</script>
									");
								}
							else
								{
									$is_in=mysqli_fetch_array($check);
									//controllo se il risultato della query è vuoto (se è vuoto, significa che nessuno ha quello username)
									if ($is_in[0]!="")
										{
											session_destroy();
  											print("
												<script>
													alert('lo username inserito non e\' disponibile');
													window.close();
												</script>
											");
										}
									else
										{
											$query = "SELECT COUNT(*) FROM utente;"; //query per sapere il numero di utenti
											$num_actual_users=mysqli_fetch_array(mysqli_query($con, $query));
											if ($num_actual_users[0] >= $NUM_MAX_USERS) //controllo se non si è superato il numero massimo di utenti
												{
													session_destroy();
  													print("
														<script>
															alert('abbiamo raggiunto il numero massimo di utenze ospitabili');
															window.close();
														</script>
													");
												}
											else
												{
													do //estrazione del numero random da dare all'ID del nuovo utente
														{
															$random = rand(1, $NUM_MAX_USERS);
															$num=mysqli_fetch_array(mysqli_query($con, "SELECT ID FROM documento WHERE ID=".$random.";"));
														}		
													while($num[0]!="");
													$query = "INSERT INTO utente VALUES (".$random." ,'" . $_POST['username'] . "' , '" . $_POST['password'] . "' ,'Default');"; //inserimento del nuovo utente
													if (mysqli_query($con, $query)) //se la query ha avuto sucesso
														{
															print("
																<script>
																	alert('la registrazione e\' andata a buon fine');
																	window.open('".$host."/lemiepubblicazioni.php', '_self');
																</script>
															");
															$_SESSION['username'] = $_POST['username'];
															$_SESSION['password'] = $_POST['password'];
															$_SESSION['id'] = $random;
														}
													else
														{
															session_destroy();
  															print("
																<script>
																	alert('ops! qualcosa e\' andato storto');
																	window.close();
																</script>
															");
														}
												}
										}
								}
						}
				}
		}
?>