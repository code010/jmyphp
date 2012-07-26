<?php

	global $globals;
	
	$pagAuth = ($lvlRequired > $_SESSION['user_lvl'] ) ? false : true ;
	if($_SESSION[$globals['auth']] && $_SESSION['user_id'] > 0 )	{	
		
		if($_SESSION['expired_pass'] && ($_GET['page'] != 'admin-account')) header("Location: ".apppath."admin-account/");
		
		if(!$pagAuth)	{
				echo "<script>location.href='".apppath."';</script>";
				die();		
				exit();
		}
		
	} else {
		echo "<script>location.href='".apppath."login/';</script>";
		die();
		exit();
	}

?>
