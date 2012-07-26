<? 	
	$web = new simple( true , 100, 'iud');
	$conf = $web->Object;
?>
	
	<form name="frmconf" id="frmconf" method="post">
		
		<h1><?=_CONFIG_?></h1>
		<a href="#" id="close"><?=_CLOSE_?></a>
		
		<table class="tableData">
			
			<tr>
	        	<td class="leftColData"><?=_NAME_?></td>
	        	<td><input name="name" type="text" id="name"  value="<?=$conf->name?>" /></td>
	      	</tr>
			<tr>
	        	<td class="leftColData"><?=_VALUE_?></td>
	        	<td><input name="value" type="text" id="value"  value="<?=$conf->value?>" /></td>
	      	</tr>  

			<!-- BUTTONS -->
			<tr>
				<td colspan="2" class="buttonsData">
					<input type="button" id="save" name="save" value="<?=_SAVE_?>" />
				  <? if($conf->id != '') { ?>
					<input type="button" id="delete" name="delete" value="<?=_DELETE_?>" />
				  <? } ?>
				</td>
			</tr>
		</table>
	
		<input type="hidden" id="id" name="id" value="<?=$conf->id?>" />
		<input type="hidden" id="params_item" name="params_item" value="name,value" />
		<input type="hidden" id="table_item" name="table_item" value="<?=db_pref?>config" />
		<input type="hidden" id="name_item" name="name_item" value="Configuration" />
		<input type="hidden" id="id_item" name="id_item" value="id" />
		
	</form>

	<script language="JavaScript">
	
	    // S A V E
		$('#save').click(function () { 
			$('#frmconf').ajaxForm({ 
			    target:     '#modalWindow', 
			    url:        '<?=apppath?>admin/config/<?=$conf->id?>/save/', 
			    success:    function() { 
				$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
			    } 
			});   
		    $('#frmconf').submit(); 
		});
		    
		// D E L E T E
		$('#delete').click(function () { 
			$('#frmconf').ajaxForm({ 
			    target:     '#modalWindow', 
			    url:        '<?=apppath?>admin/config/<?=$conf->id?>/delete/', 
			    success:    function() { 
				$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
			    } 
			});   
		    $('#frmconf').submit(); 
		});
			
		$('#close').click(function () { $('#modalWindow').fadeOut(1000); pagination_loadTableList(); });

	</script>
  
<? $web->loadPage('ajax'); ?>
