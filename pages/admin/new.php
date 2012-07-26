<?     
    $web = new simple( true , 100, 'iud');
    $new = $web->Object;
?>
    
    <form name="frmnoticia" id="frmnoticia" method="post" enctype="multipart/form-data">
              
        <h1><?=_NEW_?></h1>
        <a href="#" id="close"><?=_CLOSE_?></a>
        
        <table class="tableData">
            
			<tr>
	        	<td class="leftColData"><?=_TITLE_?></td>
	        	<td><input name="titulo" type="text" id="titulo"  value="<?=$new->titulo?>" /></td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_SUBTITLE_?></td>
	        	<td><input name="subtitulo" type="text" id="subtitulo"  value="<?=$new->subtitulo?>" /></td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_DESCRIPTION_?></td>
	        	<td><textarea name="descripcion" id="descripcion"><?=$new->descripcion?></textarea></td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_DATE_?></td>
	        	<td>
	        		<?
	        			if($new->fecha == "0000-00-00" || $new->fecha == "" ) {
							$lafecha = date('d/m/Y');
						} else {
							$lafecha = date('d/m/Y',strtotime($new->fecha));
						}
	        		?>
	        		<input name="fecha" type="text" id="fecha"  value="<?=$lafecha?>" />
	        	</td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_ATTACH_?></td>
	        	<td>
	        		<?= ($new->adjunto != "") ? "<a href='".apppath."uploads/d.php?f=".$new->id."'>".$new->adjunto."</a><br/>":""; ?>
	        		<input name="adjunto" type="file" id="adjunto"  value="" />
	        	</td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_IMAGE_?></td>
	        	<td>
	        		<?= (file_exists("./img/news/photo".$new->id.".jpg")) ? "<img src='".apppath."img/news/photo".$new->id.".jpg' height='80' /><br/>" : ""; ?>
	        		<input name="imagen" type="file" id="imagen"  value="" />
	        	</td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_ACTIVE_?></td>
	        	<td><input name="activo" type="checkbox" id="activo"  value="S" <?=($new->activo == 'S')?" checked='checked' ":"";?> /></td>
	      	</tr>
            
            <tr>
                <td colspan="2" class="buttonsData">
                    <input type="button" id="save" name="save" value="<?=_SAVE_?>" />
                  <? if($new->id != '') { ?>
                    <input type="button" id="delete" name="delete" value="<?=_DELETE_?>" />
                  <? } ?>
                </td>
            </tr>
        </table>
    
        <input type="hidden" id="id" name="id" value="<?=$new->id?>" />
        <input type="hidden" id="params_item" name="params_item" value="titulo,subtitulo,descripcion,fecha,adjunto,activo" />
		<input type="hidden" id="table_item" name="table_item" value="<?=db_pref?>new" />
        <input type="hidden" id="name_item" name="name_item" value="new" />
        <input type="hidden" id="id_item" name="id_item" value="id" />
    </form>

    <script language="JavaScript">
    
	    // S A V E
		$('#save').click(function () { 
			$("body").append("<div class='waiting'><div>Uploading<br/><img src='<?=apppath?>img/admin/uploading.gif' /></div></div>")

			tinyMCE.execCommand('mceRemoveControl',true,'descripcion');
			$('#frmnoticia').ajaxForm({ 
			    target:     '#modalWindow', 
			    url:        '<?=apppath?>admin/new/<?=$new->id?>/save/', 
			    success:    function() { 
				$('.waiting').slideUp(1000);
				$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
			    } 
			});   
		    $('#frmnoticia').submit(); 
		});
		    
		// D E L E T E
		$('#delete').click(function () { 
			$('#frmnoticia').ajaxForm({ 
			    target:     '#modalWindow', 
			    url:        '<?=apppath?>admin/new/<?=$new->id?>/delete/', 
			    success:    function() { 
				$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
			    } 
			});   
		    $('#frmnoticia').submit(); 
		});
           
        // C L O S E
        $('#close').click(function () {
			tinyMCE.execCommand('mceRemoveControl',true,'descripcion');
			$('#modalWindow').fadeOut(1000); 
			pagination_loadTableList(); 
		});

		// E D I T O R
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			elements: "template_content",
			language : "es",
			width : "350",
			height: "200",
			plugins : "autolink,style,layer,paste,xhtmlxtras,template",
	
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,cut,copy,paste,pastetext,pasteword,|,undo,redo",
			theme_advanced_buttons2 : "link,unlink,|,hr,removeformat,|,justifyleft,justifycenter,justifyright,justifyfull",
		  	theme_advanced_buttons3 : "",        
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left"
		});
		
		// I N I T   J Q U E R Y
		jQuery(document).ready(function () {
			$('#fecha').simpleDatepicker({ startdate: 2012, enddate: 2022 }); 	// Call script for calendar 
		});
		
    </script>
  
<? $web->loadPage('ajax'); ?>
