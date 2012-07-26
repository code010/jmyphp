<? global $globals ?>

<? if($_SESSION[$globals['auth']] ) {  ?>
	
	<h1><a href="<?=apppath?>">Content Management System</a></h1>
	
	<div class="userPanel">
		<a href='<?=apppath?>account/'><?=$_SESSION['user_nom']?></a>  |  
		<a href='<?=apppath?>logout/'><?=_EXIT_?></a>
	</div>
	
	<ul class="menu">
		
		<li <?= ($_GET['page'] == 'users') ? 'class="selected"' : ''; ?>> 
			<a href='<?=apppath?>admin/users/' /><?=_USERS_?></a>
		</li>
		
		<li <?= ($_GET['page'] == 'modules') ? 'class="selected"' : ''; ?>> 
			<a href='<?=apppath?>admin/modules/' /><?=_MODULES_?></a>
		</li>		
		
		<li class="<?= ($_GET['page'] == 'configs') ? 'selected' : ''; ?>">
			<a href='<?=apppath?>admin/configs/'><?=_CONFIG_?></a>
		</li>
		
		<li class="last <?= ($_GET['page'] == 'news') ? 'selected' : ''; ?>">
			<a href='<?=apppath?>admin/news/'>News</a>
		</li>
			
	</ul>
			
<? } ?>
