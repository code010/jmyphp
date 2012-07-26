<?php

	global $db,$globals;
	$noLogin = false;
	
	if($_POST) { 
	
		$Luser = simple_cleanQuery($_POST['user']);
		$Lpass = simple_encrypt(simple_cleanQuery($_POST['pass']),$globals['key']);
		$user = $db->queryUniqueObject("SELECT * FROM  ".db_pref."user WHERE user = '".$Luser."'  "); 
	
		if($user){
			
			if($user->active == 'Y' ) {
				
				if($user->pass == $Lpass ) {
					
					$diferenciaDias = ((((mktime(0, 0, 0, date("m")  , date("d"), date("Y")) - strtotime($user->last_pass_change))/60)/60)/24);
	
					$db->execute("UPDATE  ".db_pref."user SET  login_attempts=0 WHERE id=".$user->id);
				
					$_SESSION[$globals['auth']] = true;
					$_SESSION['user_id'] = $user->id;
					$_SESSION['user_nom'] = $user->name;
					$_SESSION['user_lvl'] = $user->level;
					$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['user_date_login'] = date('dmY');
						
					if($diferenciaDias >= $this->getConfig('login_expires')) {
						 
						$_SESSION['expired_pass'] = true;
						$_SESSION['simple_message'] = _MSG_EXPIRED_LOGIN_;
						$_SESSION['simple_message_class'] = "blue_message";
						echo "<script>location.href='".apppath."account/';</script>";
						
					} else {
	
						$_SESSION['expired_pass'] = false;
						echo "<script>location.href='".apppath."';</script>";
	
					}
					$noLogin = true;
						
				} else {
					$_SESSION['simple_message'] = _MSG_INCORRECT_LOGIN_;
					$_SESSION['simple_message_class'] = "red_message";		
										 
					$nIntentos = ($user->login_attempts + 1);
					if($nIntentos > $this->getConfig('login_attempts')) {
						$db->execute("UPDATE  ".db_pref."user SET login_attempts=".$nIntentos.", active='N' WHERE id=".$user->id);
					} else {
						$db->execute("UPDATE  ".db_pref."user SET  login_attempts=".$nIntentos." WHERE id=".$user->id);
					}
					
				}
				
			} else {
				
				$_SESSION['simple_message'] = _MSG_BLOQUED_LOGIN_;
				$_SESSION['simple_message_class'] = "red_message";
				
			}
			
		} else {
			
			$_SESSION['simple_message'] = _MSG_INCORRECT_LOGIN_;
			$_SESSION['simple_message_class'] = "red_message";
			
		}
		
	} 

?>