<?php

/* FUNCTION: TO GET EXTENSION OF FILENAME */
	function Gextension($archivo) {
		$temp = explode(".",$archivo);
		$temp2 = count($temp) - 1;
		$ext = $temp[$temp2];
		return $ext;
	}	
/* ===================================== */ 
	
?>