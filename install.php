<?
	$MSJ = "";

	function simple_encrypt($key, $plain_text) {
		
		$c_t = crypt($plain_text,$key);
		return base64_encode($c_t);
		
	}	
	
	switch($_GET['paso']) {
		case '1':

		  $campos = array('{KEY}','{DBSERVER}','{DBUSER}','{DBPASS}','{DBNAME}','{DBPREF}','{APPPATH}','{WEBNAME}');
		  $valores = array($_POST['key'],$_POST['dbserver'],$_POST['dbuser'],$_POST['dbpass'],$_POST['dbname'],$_POST['dbpref'],$_POST['apppath'],$_POST['wname']);
			
			#CREO EL FICHERO DE CONFIGURACION
			$handle = fopen("config.temp", "rb");
			$fConfig = fopen("config.php","w");

			while (!feof($handle)) 
				fwrite($fConfig, str_replace($campos, $valores, fread($handle, 8192)));
			
			fclose($handle);
			fclose($fConfig);
			unlink("config.temp");
			chmod('config.php', 0776);
			$MSJ .= "<span class='txtVerde'>[OK]</span> Configuration file created. <br> <br>";
			########
			
			#CREO FICHERO DE  BD CON LAS TABLAS BASICAS (USUARIO, CONFIGURACION)
			$handle = fopen("install.sql", "rb");
			$fSQL = fopen("tempsql.sql","w");

			while (!feof($handle)) 
				fwrite($fSQL, str_replace($campos, $valores, fread($handle, 8192)));
			
			fclose($handle);
			fclose($fSQL);
			
			chmod('tempsql.sql', 0776);
			$MSJ .= "<span class='txtVerde'>[OK]</span> SQL generated. <br> <br>";
			#####
			
			#CONECTANDO CON LA BD
			$con = mysql_connect($_POST['dbserver'],$_POST['dbuser'],$_POST['dbpass']);
			if (!$con){
				die('DB Connect error: ' . mysql_error());
			} else {
				mysql_select_db($_POST['dbname']) or die(mysql_error());
			}
			########
			
			#CREADO DATOS BD
			$handle = fopen("tempsql.sql","rb");

			while (!feof($handle)) {
				$sql = str_replace($campos, $valores, fgets($handle));

			  	mysql_query($sql);
			}
			fclose($handle);

			unlink("tempsql.sql");
			unlink("install.sql");
			
			#CREO REGISTROS BASICOS
			$aux_pass = simple_encrypt("jmyphp",$valores[0]);
			mysql_query("INSERT INTO `".$_POST['dbpref']."user` (`id`, `name`, `surname`, `email`, `user`, `pass`, `level`, `comments`, `date_registration`, `active`, `last_pass_change`, `login_attempts`, `last_ip_connection`) 
							VALUES (NULL, 'Admin', '', '".$_POST['admin_email']."', 'admin', '".$aux_pass."', '100', 'comments', CURRENT_TIMESTAMP, 'Y', '".date('Y-m-d')."', '0', '0.0.0.0');");
							
			mysql_close();
			$MSJ .= "<span class='txtVerde'>[OK]</span> DB loaded correctly<br> <br>";			
			########
	
			#ACTUALIZO HTACCES...
			$aux_file = "htaccess";
			$handle = fopen($aux_file, "rb");
			$newF = fopen(".".$aux_file,"w");

			while (!feof($handle)) {  fwrite($newF, str_replace($campos, $valores, fread($handle, 8192)));		}
			
			fclose($handle);
			fclose($newF);
			
			unlink($aux_file);
			
			unlink("install.php");

			$MSJ .= "<span class='txtVerde'>[OK]</span> All went well, you can now access! <br> <br>";			
			##
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Content-Style-Type" content="text/css" />
   
    <title>Instalaci&oacute;n</title>
       
	<style>
		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;		
			background-color: #FFFFFF;	
		}
		#tabla-install {
			width: 325px;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
			border: 1px solid #000000;
			margin: 0px auto;
		}
		#tabla-install th{
			background-color: #000000;
			color: #FFFFFF;
			font-size: 24px;
			font-weight: bolder;
			padding: 6px;
			text-align: left;
		}
		#tabla-install td{
			padding: 5px;
			background-color: #FFFFFF;
		}
		a {
			text-decoration: none;
			color: #4d4d4d;
		}
		a {
			color:#000000;
			font-weight: bolder;
		}
		.txtVerde {
			color: #2e5e3b;
			font-weight: bolder;
			font-size: 14px;
		}
	</style>
	
</head>

<body>

	<p>&nbsp;</p>

	<? 	if($MSJ != "" ) { #PROCESO DE INSTALACION?>
			<table cellpadding="0" cellspacing="0" id="tbl-install">
				<tr><th>Installation</th></tr>
				<tr> <td align="right"><?= $MSJ ?></td></tr>
				<tr> <td align="right"><a href='login/'>Log in</a></td></tr>
			</table> 
	<?	}	?>

	<? if($_GET['paso'] != 1 ) { #FORMULARIO DE INSTALACION?>
		<form action="install.php?paso=1" method="post">
		
			<input type="hidden" name="theme"  value="admin" />	
			<input type="hidden" name="idioma" value="es_ES" />
			<input type="hidden" name="key"  value="<?=(date('dmYGisu').rand(0,1000000000))?>" />
			<input type="hidden" name="apppath" size="25"  value="<?=substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/')+1) ?>" />
			
			<img src="http://twistedwave.com/images/InstallIcon.png" alt="INSTALACION"  align="left" style="vertical-align: middle;" /><br/>
			<span style="font-size: 24px; font-weight: bolder;">Simple PHP Base<br/>Installation</span>
			<table cellpadding="0" cellspacing="0" id="tabla-install">
				
				<tr><th colspan="2">Database Configuration</th></tr>
				<tr>
					<td align="right">Web name</td>
					 <td align="right"><input type="text" name="wname" size="25"  value="Untitled" /></td>
				</tr>
				<tr>
					<td align="right">Admin e-mail</td>
					 <td align="right"><input type="text" name="admin_email" size="25"  value="" /></td>
				</tr>
				<tr>
					<td align="right">DB Host</td>
					 <td align="right"><input type="text" name="dbserver" size="25"  value="localhost" /></td>
				</tr>
				<tr>
					<td align="right">DB User</td>
					 <td align="right"><input type="text" name="dbuser" size="25"  value="root" /></td>
				</tr>
				<tr>
					<td align="right">DB Pass</td>
					 <td align="right"><input type="text" name="dbpass" size="25" value="m4l4b4rs" /></td>
				</tr>	
				<tr>
					<td align="right">DB Name</td>
					 <td align="right"><input type="text" name="dbname" size="25" value="baseapp" /></td>
				</tr>	
				<tr>
					<td align="right">DB Prefix:</td>
					 <td align="right"><input type="text" name="dbpref" size="25" value="test_" /></td>
				</tr>	
				
				<tr>
					<td align="right" colspan="2" align="center"><input  type="submit" value="Install" /></td>
				</tr>
				
			</table>
			
		</form>
	<? } ?>

</body>
</html>