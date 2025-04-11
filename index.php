<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/dashboard/');
	exit;
?>

<footer>
  <p>&copy; 2025 Alexander Ocampo Hernandez. Todos los derechos reservados.</p>
</footer>

Something is wrong with the XAMPP installation :-(
