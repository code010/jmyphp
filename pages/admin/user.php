<? 	
	$web = new simple( true , 100, 'iud');
	$user = $web->Object;
?>
	
	<form name="frmUser" id="frmUser" method="post">
		  	
		<h1><?=_USER_?></h1>
		<a href="#" id="close"><?=_CLOSE_?></a>
		
		<table class="tableData">
			<tr>
				<td class="leftColData"><?=_NAME_?></td>
				<td><input name="name" type="text" id="name"  value="<?=$user->name?>" /></td>
			</tr>
			<tr>
				<td class="leftColData"><?=_SURNAME_?></td>
				<td><input name="surname" type="text" id="surname"  value="<?=$user->surname?>" /></td>
			</tr>
			<tr>
				<td class="leftColData">E-mail</td>
				<td><input name="email" type="text" id="email"  value="<?=$user->email?>" /></td>
			</tr>
			<tr>
				<td class="leftColData"><?=_LEVEL_?></td>
				<td>
					<select name="level"  id="level">
						<option value="100" <?= ($user->level == '100' ) ? " selected " : ""  ?>><?=_LVL_ADMIN_?></option>
						<option value="50" <?= ($user->level == '50' ) ? " selected " : ""  ?>><?=_LVL_USER_?></option>
						<!-- <option value="10" <?= ($user->level == '10' ) ? " selected " : ""  ?>><?=_LVL_GUEST_?></option> -->
					</select>
				</td>
			</tr>
			<tr>
				<td class="leftColData"><?=_USER_?></td>
				<td><input name="user" type="text" id="user"   value="<?=$user->user?>" /></td>
			</tr>
			<tr>
				<td class="leftColData"><?=_PASS_?></td>
				<td><input name="pass" type="text" id="pass"  value=""  /></td>
			</tr>
			<tr>
				<td class="leftColData"><?=_COMMENTS_?></td>
				<td><textarea name="comments" id="comments"  rows="6"><?=$user->comments?></textarea></td>
			</tr>
			<tr>
				<td class="leftColData"><?=_ACTIVE_?></td>
				<td><input name="active" type="checkbox" id="active" value="Y" <?= ($user->active == 'Y' ) ? " checked='checked' " : ""  ?> /></td>
			</tr>      
			<!-- BUTTONS -->
			<tr>
				<td colspan="2" class="buttonsData">
					<input type="button" id="save" name="save" value="<?=_SAVE_?>" />
				  <? if($user->id != '') { ?>
					<input type="button" id="delete" name="delete" value="<?=_DELETE_?>" />
				  <? } ?>
				</td>
			</tr>
		</table>
	
		<input type="hidden" id="id" name="id" value="<?=$user->id?>" />
		<input type="hidden" id="params_item" name="params_item" value="name,surname,email,user,pass,level,comments,last_pass_change,active,login_attempts" />
		<input type="hidden" id="table_item" name="table_item" value="<?=db_pref?>user" />
		<input type="hidden" id="name_item" name="name_item" value="User" />
		<input type="hidden" id="id_item" name="id_item" value="id" />
			
	</form>

	<script language="JavaScript">
	
	    	// S A V E
		$('#save').click(function () { 
				// Ajax Form options & initialize
				$('#frmUser').ajaxForm({ 
				    target:     '#modalWindow', 
				    url:        '<?=apppath?>admin/user/<?=$user->id?>/save/', 
				    success:    function() { 
					$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
				    } 
				});   
				     			
		    $('#frmUser').submit(); 
		});
		    
		// D E L E T E
		$('#delete').click(function () { 

				// Ajax Form options & initialize
				$('#frmUser').ajaxForm({ 
				    target:     '#modalWindow', 
				    url:        '<?=apppath?>admin/user/<?=$user->id?>/delete/', 
				    success:    function() { 
					$('.simple_message').css('top',$(window).scrollTop()).fadeIn(200).delay(800).slideUp(1600);
				    } 
				});   
				     			
		    $('#frmUser').submit(); 

		});
			
		$('#close').click(function () { $('#modalWindow').fadeOut(1000); pagination_loadTableList(); });

	</script>
  
<? $web->loadPage('ajax'); ?>
					 					 
