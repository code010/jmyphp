<? 
	$web = new simple( true , 100, 'iud', array(table =>'user') );
	$web->Title = _USERS_;
	$users = $web->Object;
?>

	<h1><?=_USERS_?></h1>

	<div class="tableList">
		<table cellpadding="0" cellspacing="0">
			
			<thead>
				<tr>
					<th><?=_NAME_?></th>
					<th><?=_EDIT_?></th>
					<th class="last"><a href="javascript: showModalWindow('<?=$web->ModalWDest ?>0/')" class="item_add"><?=_ADD_?></a></th>
				</tr>
				
			</thead>
			
			<tbody>
				
			<? while ($user = $db->fetchNextObject($users)) { ?>
				<tr>
					<td>
						<?=$user->name?>
					</td>
					<td>
						<a href="javascript: showModalWindow('<?=$web->ModalWDest . $user->id ?>/')" class="item_edit"><?=_EDIT_?></a>
					</td>
					<td>
						<a href="javascript: showModalWindowDelete('<?=$web->ModalWDest . $user->id ?>/delete/')" class="item_delete"><?=_DELETE_?></a>
					</td>
				</tr>
			<? } ?>	
								
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="3" id="pagination">
						<?=$web->Pagination?>
					</td>
				</tr>
			</tfoot>
		  	
		</table>
	</div>
	
	<form id="frmPagination" method="post">

		<input type="hidden" id="order_item" name="order_item" value="id" />
		<input type="hidden" id="order_dir_item" name="order_dir_item" value="asc" />
		
		<input type="hidden" id="filters" name="filters" value="name,surname" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_surname" name="filter_surname" value="" />

		<?=$web->PaginationInputs ?>
		
	</form>

	<div id="modalWindow" ></div>
	
<? $web->loadPage(($_GET['val1']!='ajax')?'admin':'ajax'); ?>

