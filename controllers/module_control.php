<?php

	global $db;

	if($_POST['module_name']!="" && $_POST['module_params']!=""){
		
		$campos =  explode(",",$_POST['module_params']);
		$nombreM = $_POST['module_name'];
		
		#CREO EL REGISTRO EN LA TABLA DE MODULOS ##################################################
		$result = $db->execute("INSERT INTO ".db_pref ."module (id, content) 
								VALUES ('modul_".$_POST['module_name']."', '".$_POST['module_params']."') ");
		
		#CREO LA TABLA EN LA BD ###############################################################
		$sql = "CREATE TABLE IF NOT EXISTS `".db_pref.$nombreM."` (  `id` int(12) NOT NULL auto_increment, ";		
		foreach($campos as $campo) $sql .= "`".$campo."` TEXT collate latin1_spanish_ci NOT NULL default '', ";
		$sql .= " PRIMARY KEY  (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;";
		$result = $db->execute($sql);
			
		#CREO EL FORMULARIO ###############################################################
		$vista = $db->queryUniqueObject("SELECT * FROM ".db_pref ."module WHERE id = 'page' ");
		 
		$ourFileName = "pages/admin/".$nombreM . ".php";
	
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
		
		$i = 0;
		$lista_campos = "";
		$lista_campos_flat = "";
		foreach($campos as $campo) {
			$lista_campos .= '
			<tr>
	        	<td class="leftColData">'.ucfirst($campo).'</td>
	        	<td><input name="'.$campo.'" type="text" id="'.$campo.'"  value="<?=$'.$nombreM.'->'.$campo.'?>" /></td>
	      	</tr>';
			if($i>0) $lista_campos_flat .=',';
			$lista_campos_flat .=$campo;			
			$i++;
		}
		
		$aux_content = str_replace("{CAMPOSG}",$lista_campos,$vista->content);
		$aux_content = str_replace("{CAMPOSGLIST}",$lista_campos_flat,$aux_content);
		$aux_content = str_replace("{NOMG}",$nombreM,$aux_content);
		
		fputs($ourFileHandle, $aux_content);
		fclose($ourFileHandle);

		#CREO EL LISTADO ###############################################################
		$vista_lista = $db->queryUniqueObject("SELECT * FROM ".db_pref ."module WHERE id = 'list' ");
		 
		$ourFileName = "pages/admin/".$nombreM . "s.php";
	
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
				
		$aux_content = str_replace("{CAMPO1}",$campos[0],$vista_lista->content);
		$aux_content = str_replace("{NOMG}",$nombreM,$aux_content);
		
		fputs($ourFileHandle, $aux_content);
		fclose($ourFileHandle);		
		###############################################################
		

		$_SESSION['simple_message'] = $nombreM . _INFO_SAVED_;
		$_SESSION['simple_message_class'] = "green_message";
		
	}

	$query = "SELECT * FROM ".db_pref ."module where id like 'modul_%' ";	#SELECT CON EL LISTADO
	$result = $db->query($query); 
	
?>