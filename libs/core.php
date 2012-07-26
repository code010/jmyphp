<?php
	
	include 'config.php';
	include 'libs/db.php';
	include 'libs/functions.php';
		
	ob_start();
	
	class simple {
		
		var $sessid = "";
		
		var $Title = "";
		var $META_keywords = "";
		var $META_description = "";
		var $Head = "";
		var $Body = "";
		var $Extra = "";
		var $Theme = "admin";
		var $AppTitle = "";
		var $Pages = "";
		var $Object = "";
		var $ModalWDest = "";
		var $BD_table = "";
		var $Pagination = "";
		var $PaginationIni = "0";
		var $PaginationSize = "10";
		var $PaginationInputs = "";
		var $Level = "";
		
		function loadPage( $definedTheme = '')	{
			
			global $globals,$db;

			if($this->META_keywords == "") $this->META_keywords = $globals['META_keywords'];
			if($this->META_description == "") $this->META_description = $globals['META_description'];
			$this->AppTitle =  $this->getConfig('web_name ');
			
			$this->Body=ob_get_contents();
			
			if( $definedTheme != '' ) $this->Theme = $definedTheme;
	
			ob_end_clean();
		
			if($this->Theme == 'ajax' || $this->Theme == 'blank') 
				echo $this->Head.$this->Body;
			else 
				include 'themes/'.$this->Theme."/main.php";
				
			$db->close();
			
		}
	
		public function __construct($withSession = true, $lvlRequired = 1, $controller = "", $params = "")	{
	
			$this->Level = $lvlRequired;
			$this->BD_table = ($params['table']!= "")?db_pref.$params['table']:"";
			$this->ModalWDest = apppath."admin/".$params['table']."/";
			
			$_SESSION['simple_message'] = "";
			$_SESSION['simple_message_class'] = "";	
			 
			require_once 'lang/'.$_SESSION['lang'].'.php';	
			if( $withSession ) include 'libs/session.php';
			if( $controller != "" ) include 'controllers/'.$controller.'_control.php';

			$getHead= ob_get_contents();
			ob_end_clean();
			ob_start();
			
			if($_SESSION['simple_message'] != "" ) echo "<div class='simple_message ".$_SESSION['simple_message_class']."'>".$_SESSION['simple_message']."</div>";
			
			$this->Head = $getHead;
			
		}
	
		public function getLayer($name)	{
			
			include "themes/".$this->Theme."/".$name.".php";
			
		}
		
		public function getConfig($value)	{
			
			global $db;
			$res = $db->queryUniqueValue("SELECT value FROM ".db_pref ."config WHERE name = '".$value."'");
			return $res;		
			
		}
		
	
	}

	function simple_encrypt($key, $plain_text) {
		
		$c_t = crypt($plain_text,$key);
		return base64_encode($c_t);
		
	}		
	
	function simple_customError($errno, $errstr)	{
		 
		global $globals;
		
		if($globals['debug_app'] && $errno != '8' ) {
			echo "<b>ERROR:</b> [$errno] $errstr <br>";
		} 
			
	}	
	
	function simple_cleanQuery($string) {
		
		if(get_magic_quotes_gpc()) $string = stripslashes($string);
	
		$string = mysql_real_escape_string($string);
	
		return $string;
		
	}
	
?>

