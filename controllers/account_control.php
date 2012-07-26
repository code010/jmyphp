<?php
global $globals,$db;
$id_user =  $_SESSION['user_id'];
$accion =  (isset($_POST['accion'])) ? $_POST['accion'] : "";

if($id_user != ''){
	
		$this->Object = $db->queryUniqueObject("SELECT * FROM  ".db_pref."user WHERE id = '" . $_SESSION['user_id'] ."' "); 
		
		switch($accion) {
		
			case 'save':
						
						if($this->Object->pass == simple_encrypt($_POST['passOld'],$globals['key']) || $_POST['pass'] == '' ){
							
							$update_query = "UPDATE  ".db_pref."user SET 
															name = '".$_POST['name']."',
															surname= '".$_POST['surname']."',
															email = '".$_POST['email']."',
															user = '".$_POST['user']."',";
															
							if($_POST['pass'] != '' ) 	
								$update_query .= "pass = '".simple_encrypt($_POST['pass'],$globals['key'])."',";
							
							$update_query .= "comments= '".$_POST['comments']."',
															active = 'Y',
															last_pass_change = NOW()
														WHERE id = ".$id_user;
														
							$result = $db->execute($update_query);
							
							$this->Object = $db->queryUniqueObject("SELECT * FROM  ".db_pref."user WHERE id = '" . $_SESSION['user_id'] ."' ");
							
							$_SESSION['expired_pass'] = false;
							
							$_SESSION['simple_message'] = _MSG_UPDATED_ACCOUNT_;
							$_SESSION['simple_message_class'] = "green_message";
							
						} else {
							$_SESSION['simple_message'] = _MSG_BAD_PASS_;
							$_SESSION['simple_message_class'] = "red_message";
						}
				break;	
								
		}

} 


?>
