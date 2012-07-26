<?	
	global $globals,$db;
	$web = new simple	( 	false,	#¿REQUIERE HACER LOGIN?
							0 ,	 #¿CUAL ES EL NIVEL REQUERIDO PARA LA PAGINA?
							'login' );	#CONTROL BBDD
	$web->Title = _LOGIN_;
?>

	<? if( ( !$noLogin ) && ( !$_SESSION[$globals['auth']] ) ) { ?>
		
	<div id="login">
		
       <form action="" method="post" id="loginForm">
        
        	<h1><?=_LOGIN_ ?></h1>
        	
           	<?=_USER_ ?><br/>
           	<input type="text" name="user" /><br/>
           	
           	<?=_PASS_ ?><br/>
           	<input type="password" name="pass" /><br/>
			
			<input name="signin" type="Submit"  value="<?=_SIGNIN_ ?>" />
            
        </form> 
        
	</div>
        
	<? } ?>

<? $web->loadPage('admin'); ?>
