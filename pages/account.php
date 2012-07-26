<? 	
	$web = new simple(true,1,'account');  
	$web->Title = _ACCOUNT_;
	$user = $web->Object;
?>
	
	<form name="frmAccount" id="frmAccount" method="post" action="">
		<input type="hidden" id="accion" name="accion" value="save" />
		
		<h1><?=_ACCOUNT_?></h1>
		
		<table class="tableAccount" cellpadding="0" cellspacing="0">
			<tr>
				<td width="85"><?=_NAME_?></td>
				<td><input name="name" type="text" id="name" value="<?=$user->name?>" /></td>
			</tr>
			<tr>
				<td><?=_SURNAME_?></td>
				<td><input name="surname" type="text" id="surname" value="<?=$user->surname?>" /></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input name="email" type="text" id="email" value="<?=$user->email?>" /></td>
			</tr>
			<tr>
				<td><?=_USER_?></td>
				<td><input name="user" type="text" id="user"  value="<?=$user->user?>"  readonly="readonly" /></td>
			</tr>
			<tr>
				<td><?=_OLD_PASS_?></td>
				<td><input name="passOld" type="password" id="passOld" value=""  /></td>
			</tr>
			<tr>
				<td><?=_NEW_PASS_?></td>
				<td><input name="pass" type="password" id="pass" value=""  /></td>
			</tr>
			<tr>
				<td><?=_RE_PASS_?></td>
				<td><input name="Rpass" type="password" id="Rpass" value=""  /></td>
			</tr>
			<tr>
				<td valign="top"><?=_COMMENTS_?></td>
				<td><textarea name="comments" id="comments" rows="6" ><?=$user->comments?></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<input type="submit" id="save" name="save" value="<?=_SAVE_?>" />
				</td>
			</tr>
		</table>
		
	</form>

<? $web->loadPage('admin'); ?>
