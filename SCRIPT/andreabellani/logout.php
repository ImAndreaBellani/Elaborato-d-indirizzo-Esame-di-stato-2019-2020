<?php
	session_start(); //entra nella sessione corrente
	session_unset(); //metti a null tutte le variabili della sessione
	session_destroy(); //dealloca la sessione
	print("
		<script>
			window.close();
		</script>
	");
?>