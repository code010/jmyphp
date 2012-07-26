<?php

	global $db;

	if($_POST['id_item'] != "" && $_POST['table_item'] != "" ) {
		
		$valores_campos				= array(
											"pass" 					=> simple_encrypt($_POST['pass'],$globals['key']), 
											"ultimo_cambio_pass" 	=> date('Y-m-d'),
											"fecha" 				=> date("Y-m-d 00:00:00",strtotime(str_replace("/", "-", $_POST['fecha']) . " 00:00"))
											);
		
		$campos 					= explode(",", $_POST['params_item']);
		$identificador 				= $_POST['id_item'];
		$tabla 						= $_POST['table_item'];
		$nombre 					= $_POST['name_item'];
	}
	
	$accion 						= $_GET['val2'];
	$id 							= $_GET['val1'];

	if( $_POST['id'] != '' ) 	$id = $_POST['id'] ; 
	if( $id === 0 )				$isNewItem = true;
	if( $id == 0 )				$id = '';

	switch($accion) {
		
		case 'save':
			
			#  U P D A T E   I T E M   #
			if($id != ''){
				
				$i = 0;
				foreach($campos as $campo) {
					
					if($i > 0 ) $update_query .=  " , "; 
					
					if($valores_campos[$campo] != '')
						$update_query .=  $campo . " = '" .  simple_cleanQuery($valores_campos[$campo]) . "'";
					else
						$update_query .=  $campo . " = '" .  simple_cleanQuery($_POST[$campo]) . "'";
					
					$i++;
				}
				
				$update_query  = "UPDATE  ".$tabla." SET " . $update_query . " WHERE ". $identificador . " = '" . $id . "' ";
				$result = $db->execute($update_query);
				
				$_SESSION['simple_message'] = _INFO_UPDATED_;
				$_SESSION['simple_message_class'] = "green_message";
				
			#  I N S E R T   I T E M   #
			} else {

				$insert_query_campos =  "";
				$insert_query_values =  "";
				$i = 0;
				foreach($campos as $campo) {
					
					if($i > 0 ) {
						$insert_query_values .=  ", ";
						$insert_query_campos .=  ", ";
					}
					
					$insert_query_campos .=  $campo;
					
					if($valores_campos[$campo] != '') 
						$insert_query_values .=  "'" .  simple_cleanQuery($valores_campos[$campo]) . "'";
					else 
						$insert_query_values .=  "'" .  simple_cleanQuery($_POST[$campo]) . "'";
					
					$i++;
				}
				
				$insert_query = "INSERT INTO  ".$tabla." ( ". $insert_query_campos . " ) VALUES ( " . $insert_query_values . " )  ";
				$result = $db->execute($insert_query);
				
				$_SESSION['simple_message'] = _INFO_SAVED_;
				$_SESSION['simple_message_class'] = "green_message";
				
				$id = $db->lastInsertedId();
				
			}

			#UPLOAD IMAGE FOR NEW
			if($_FILES['imagen']['size'] > 0) {
				$nombre_archivo_aux = "/img/news/photo". $id . ".jpg" ;
				$nombre_archivo = ".".$nombre_archivo_aux ;
				$tipo_archivo = $_FILES['imagen']['type'];
				$tamano_archivo = $_FILES['imagen']['size'];
				$temp_archivo = $_FILES['imagen']['tmp_name'];
					
				if (file_exists($nombre_archivo)) unlink($nombre_archivo);
				
				#REDIMENSIONO LA IMAGEN A 330 DE ANCHO # # # # #
				list($width, $height) = getimagesize($temp_archivo);
				$newwidth = 220;
				$newheight = ( $height * $newwidth ) / $width;
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				$source = imagecreatefromjpeg($temp_archivo);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagejpeg($thumb,$nombre_archivo,100);
				#FIN REDIMENSION # # # # # # # # # # # # # # # #			
			}

			#UPLOAD FILE FOR NEW
			if($_FILES['adjunto']['size'] > 0 && $_FILES['adjunto']['tmp_name'] != "") {
				
				$fileNameOK = ereg_replace("[^A-Za-zñÑ_áéíóú.0-9]", "", $_FILES['adjunto']['name']);
				$nombre_archivo = "./uploads/".$id. "_file.". Gextension($_FILES['adjunto']['name']);
				
				if (file_exists($nombre_archivo)) unlink($nombre_archivo);
			
			    if (move_uploaded_file($_FILES['adjunto']['tmp_name'], $nombre_archivo)) chmod($nombre_archivo, 0644);
				
				$db->execute("UPDATE ".db_pref."new SET adjunto = '".$fileNameOK."' WHERE id = ".$id);
										
			}				
		break;

		#  D E L E T E   I T E M  #
		case 'delete':
			$result = $db->execute("DELETE FROM  ".$tabla." WHERE " . $identificador . "= '" . simple_cleanQuery($id)."' ");
			$_SESSION['simple_message'] = _INFO_DELETED_;
			$_SESSION['simple_message_class'] = "green_message";
			$id = "";
		break;	
	}

	if( $id != '' )  {
		
		#  I T E M  #
		$this->Object = $db->queryUniqueObject("SELECT * FROM  ".$tabla." WHERE ".$identificador ." = '" . simple_cleanQuery($id)."' "); 
		$this->Object->$identificador = $id;
		 
	} else {

		# IF NOT NEW ITEM, IS LIST PAGE,  NEEDED LIST OBJECT AND JS & PAGINATION #
				
		#  L I S T  I T E M  #
		if($this->BD_table != "") {
			$sql_where 					=  "1";
			$tam_pagination 	 		= 4;
			if($_POST) {
				$sql_query = "SELECT * FROM ".$_POST['table_item'] ;
				$sql_order = "	ORDER BY " . $_POST['order_item'] . " " . $_POST['order_dir_item'];
			
				$params 					= explode(",", $_POST['params_item'] );
				$pages_tam 					= $_POST['size_pagination'];
				$pages_ini 					= $_POST['start_pagination'];
				$filters 					= explode(',',$_POST['filters']);
			} else {
				$sql_query = "SELECT * FROM ".$this->BD_table ;
			
				$pages_tam 					= 10;
				$pages_ini 					= 0;
			}
		
			for($j=0;$j<count($filters);$j++) { 
				if(isset($_POST['filter_'.$filters[$j]])) { 
					$sql_where .= " AND ". $filters[$j] . " LIKE '%".$_POST['filter_'.$filters[$j]]."%' ";
				}
			}

			$pages_max = $db->countOf($this->BD_table, $sql_where);

			$this->Object = $db->query( $sql_query . " WHERE " .$sql_where . $sql_order . " LIMIT " . $pages_ini . ", " .  $pages_tam );

			//INICIO PAGINACION
			$simple_pagination = "";
						
			if(!isset($pages_ini)) $pages_ini = 0;	
			else {
				if($pages_ini < 0) $pages_ini = 0;
				if($pages_ini >= $pages_max) $pages_ini = $pages_max - $pages_tam;
			}
		
			if($pages_ini < 0 ) $pages_ini = 0;
		
			$pages = $pages_max / $pages_tam;
			$pactual = $pages_ini / $pages_tam;
			$this_pag = $pages_ini;
		
			$simple_pagination .= "\n<ul>\n";
		
			if($pactual != 0 ){
				$simple_pagination .= "	<li><a href=\"javascript: pagination_CambiaPag('-',".$pages_tam.");\">"._PREV_."</a></li>\n";
				if($pactual > ($tam_pagination-1)) {
					$simple_pagination .= "	<li><a href=\"javascript: pagination_IrPag(0);\">1</a></li>\n";
					if($pactual > ($tam_pagination))  $simple_pagination .= "	<li>...</li>\n";
				}
			}
		
			if(ceil($pages)>1) {
				$contp = 0;
				for($i = 0 ; $i < $pages ; $i++) {
					if($contp < ($tam_pagination+$pactual)){
						if($i > ($pactual-$tam_pagination)){
							if ($i == $pactual) {
								if($i==0) 
									$simple_pagination .= "	<li class='selected'>".($i+1)."</li>\n";
								else
									$simple_pagination .= "	<li class='selected'>".($i+1)."</li>\n";							
							
							} else {
								$simple_pagination .= "	<li><a href=\"javascript: pagination_IrPag(".($i*$pages_tam).");\">".($i+1)."</a></li>\n";
							} 
						}
					}
					$contp++;
				}
			}
		
			if(($pactual+1) != ceil($pages) ) {
				if($pages_max > $pages_tam){
					if($pactual < ( ($pages) - ($tam_pagination)  ))  {
						if($pactual < ( ($pages) - ($tam_pagination+1)  ))  $simple_pagination .= "	<li>...</li>";
						$simple_pagination .= "\n	<li><a href=\"javascript: pagination_IrPag(".($pages_tam*(ceil($pages)-1)).");\">".ceil($pages)."</a></li>\n";
					}
					$simple_pagination .= "	<li class='last'><a href=\"javascript: pagination_CambiaPag('+',".$pages_tam.");\">"._NEXT_."</a></li>\n";
				}
			}
		
		 	$simple_pagination .= "</ul>";
				  
			if($pages_ini < 0) $pages_ini = 0;			
				
			$this->Pagination = $simple_pagination;
			$this->PaginationIni = $pages_ini;
			$this->PaginationSize = $pages_tam;

			$this->PaginationInputs = '
						<input type="hidden" id="id_item" name="id_item" value="id" />
						<input type="hidden" id="table_item" name="table_item" value="'.$this->BD_table.'" />
						<input type="hidden" id="mwDestination_item" name="mwDestination_item" value="'.$this->ModalWDest.'" />
						<input type="hidden" id="level_item" name="level_item" value="'.$this->Level.'" />
						<input type="hidden" id="start_pagination" name="start_pagination" value="'.$this->PaginationIni.'" />
						<input type="hidden" id="size_pagination" name="size_pagination" value="'.$this->PaginationSize.'" />';
		
			//JS FOR PAGINATION
			if(!$isNewItem )  {  ?>
			
				<script type="text/javascript">

					$(document).ready(function() {
						<? if($_SESSION['simple_message'] != "") { echo "$('.simple_message').fadeIn(200).delay(800).slideUp(1600);"; } #DISPLAY INFO MESSAGE# ?>
						<? if($_GET['val1'] !='ajax') echo 'pagination_loadTableList();'; ?>
					});
			
					//MODAL WINDOW CONTROL
					function showModalWindow(url) {
							$.post(url, $("#frmPagination").serialize(), function(data) { $('#modalWindow').html(data).fadeIn(600); });
					}
				
					function showModalWindowDelete(url) {
						if(confirm('Are you sure?')) {
							$.post(url, $("#frmPagination").serialize(), function(data) { $('#modalWindow').html(data); pagination_loadTableList(); });
					  		$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
					    	}
						pagination_loadTableList(); 
					}
				
					//PAGE LIST CONTROL
					function pagination_loadTableList() {
						// Ajax Form options & initialize
						$('#frmPagination').ajaxForm({ 
						    target:     '#main', 
						    url:        'ajax', 
						    success:    function() { 
							$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
						    } 
						});   
						     			
				    		$('#frmPagination').submit();
					}
				
					function pagination_IrPag(position){
						$('#start_pagination').attr('value',position);
						pagination_loadTableList();
					}
				
					function pagination_CambiaPag(Acc,Tam) {
						if(Acc == '+')
							$('#start_pagination').attr('value',parseInt($('#start_pagination').val())+parseInt(Tam));
						else
							$('#start_pagination').attr('value',parseInt($('#start_pagination').val())-parseInt(Tam));
						pagination_loadTableList();
					}
				
				</script>
	<?	
			//FIN PAGINACION

			}

		} else {
			
			#  E M P T Y   I T E M  #
			class aux { var $id = 0; }
			$new = new aux();
			$this->Object = $new;

		}
	}

?>

