<?php
	session_start();
	$host ="https://account.andreabellani.com/andreabellani";
	$uploads_dir = 'C:\Users\Andrea\Desktop\Andrea\Scuola\Informatica\Laboratorio\PHP\usbwebserver\root\andreabellani\Documenti';
	$nomeUSB = "localhost";
	$userUSB = "root";
	$passUSB = "Rorapandi";
	$dbUSB = "account";
	$NUM_MAX_USER = 1000;
	$NUM_MAX_DOCUMENTI_PER_USER = 10;
	$NUM_MAX_DOCUMENTI_TOTALI = $NUM_MAX_USER*$NUM_MAX_DOCUMENTI_PER_USER;
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
					if ($_FILES["file1"]["name"]=="") //il file caricato ha nome vuoto? (ovvero: "non è stato caricato nulla?")
						{
							print("
								<script type='text/javascript'>
									alert('non hai caricato nessun file!');
									window.open('".$host."/carica.php', '_self');
								</script>
							");
						}
					else
						{
							$query = "SELECT FormatoRichiesto FROM topic WHERE Nome='".$_POST["topic"]."';"; //query per sapere quale sia il formato richiesto
							$formato_richiesto=mysqli_fetch_array(mysqli_query($con, $query));
							if (pathinfo($_FILES["file1"]["name"], PATHINFO_EXTENSION) == $formato_richiesto[0]) //il formato corrisponde
								{
									//conteggio del numero di documenti caricati dall'utente
									$query = "SELECT COUNT(*) FROM documento WHERE FKIDUtente='".$id."'";
									$actual_doc_num=mysqli_fetch_array(mysqli_query($con, $query)); //invio della query
									if ($actual_doc_num[0] >= $NUM_MAX_DOCUMENTI_PER_USER) //l'utente ha raggiunto il limite di file caricati?
										{
											print("
												<script type='text/javascript'>
													alert('ci dispiace ma hai raggiunto il limite di upload');
													window.open('".$host."/lemiepubblicazioni.php', '_self');
												</script>
											");
										}
									else
										{
											if (strpos($_FILES["file1"]["name"], "$") !== false) //il file contiene il carattere speciale "$"?
												{
													print("
														<script>
															alert('\'$\' non e\' un carattere ammissibile nel titolo del file');
															window.open('".$host."/carica.php', '_self');
														</script>
													");
												}				
											else
												{
													do //generazione del numero random per l'ID
														{
															$random = rand(1, $NUM_MAX_DOCUMENTI_TOTALI);
															$num=mysqli_fetch_array(mysqli_query($con, "SELECT ID FROM documento WHERE ID=".$random.";"));
														}		
													while($num[0]!="");
													$pname = $random."$".$_FILES["file1"]["name"]; //in "$pname" viene memorizzato il nuovo nome del file [ID]+[$]+[NOME ORIGINALE]
													//invio il file alla cartella e, controllo se l'upload è andato a buon fine
													if (move_uploaded_file($_FILES["file1"]["tmp_name"], $uploads_dir.'/'.$pname))
														{
															//aggiungi la nuova entry a 'documento
															$query = "INSERT INTO documento VALUES (".$random.",'".$pname."', 'Non abbiamo ancora controllato questo file', 1, ".$id.", '".$_POST["topic"]."');";
															mysqli_query($con, $query);
															print("
																<script>
																	alert('il file e\' stato caricato');
																	window.open('".$host."/lemiepubblicazioni.php', '_self');
																</script>
															");		
														}
													else
														{
															print("
																<script>
																	alert('l\'upload non e\' andato a buon fine a causa di un errore');
																	window.open('".$host."/carica.php', '_self');
																</script>
															");
														}
												}
										}
								}
							else
								{
									print("
										<script type='text/javascript'>
											alert('questo tipo di file non e\' ammesso per il topic selezionato');
											window.open('".$host."/carica.php', '_self');
										</script>
									");
								}
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