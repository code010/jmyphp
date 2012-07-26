<? 
	$web = new simple( true , 100, 'module' );
	$web->Title = _MODULES_;
?>

	<h1><?=_MODULES_?></h1>

	<div class="tableList">
		<table cellpadding="0" cellspacing="0">
			
			<thead>
				<tr>
					<th><?=_NAME_?></th>
					<th class="last"><a href="javascript: " onclick="$('#modalWindow').fadeIn(600);"><?=_ADD_?></a></th>
				</tr>
			</thead>
			
			<tbody>
			<? while ($module = $db->fetchNextObject($result_list)) {  ?>
				<tr>
					<td><a href="<?=apppath . "admin/" . str_replace("modul_","",$module->id) ?>s/"><?=$module->id?></a></td>
					<td><a href="#"  class="item_delete"><?=_DELETE_?></a></td>
				</tr>
			<? } ?>
			
				<tr>
					<td colspan="2" id="pagination">&nbsp;</td>
				</tr>
							
			</tbody>
			
		</table>
	</div>
	
	<div id="modalWindow">
		
		<h1>New module</h1>
		<a href="javascript:" onclick="$('#modalWindow').fadeOut(600);" id="close"><?=_CLOSE_?></a>
		
		<form method="post" action="">
			<div>
				Module name<br/>
				<input type="text" id="module_name" name="module_name" value="" /><br/>
				
				Params<br/>
				<textarea id="module_params" name="module_params"></textarea><br/>
				
				<input type="submit" value="Create" />
			</div>
		</form>
		
	</div>
		
<? $web->loadPage(); ?>