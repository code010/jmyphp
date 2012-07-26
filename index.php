<?php 

	session_start();
	
	include 'libs/core.php';
	
	if($_GET['f']=="")
		@include "pages/".(($_GET['page'] == '') ? 'index':$_GET['page']).".php";
	else
		@include "pages/".$_GET['f']."/".(($_GET['page'] == '') ? 'index':$_GET['page']).".php";
	
?>

