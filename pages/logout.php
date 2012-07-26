<?php

	session_start();
	global $globals;

	$_SESSION[$globals['auth']] = false;

	session_unset();
	session_destroy();

	echo "<script>location.href='".apppath."login/';</script>";

?>