<?php

	session_start();
	include "../config.php";
	include "../libs/functions.php";
	include "../libs/db.php";
	include "../libs/session.php";
	global $db;

	$archivo = $db->queryUniqueObject("SELECT * FROM ".db_pref."new WHERE id =".$_GET['f']);
	$file = "./".$archivo->id."_file.".Gextension($archivo->adjunto);
	
	if(file_exists($file) && $file != "/" ) {
		$lenght = filesize($file);
		if(Gextension($file)=="html"){
			header("Content-Type: text/html; charset=utf-8");
		} else {
			header("Content-Type: application/force-download");
			header('Content-Disposition: attachment; filename="'.$archivo->adjunto.'"');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Pragma: public");
			header("Content-Transfer-Encoding: binary");
			header('Content-Length: '.$lenght);
		}
		
		readfile($file); 		  
		 	
	} 

?>