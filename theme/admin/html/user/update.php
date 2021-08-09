<main id="main-content">
<h1>
	<span class="item-id">#<?=$USER['ATTR']['ID']?></span>
	<i class="fa fa-pencil-square-o"></i><?=$Language->text('update_user')?>
</h1>
<p class="actions-before"><?=$Language->text('update_user_desc')?></p>
<ul class="actions">
	<li><a href="<?=$USER['URL']?>" title="<?=$Language->text('select_user')?>"><i class="fa fa-external-link"></i><?=$Language->text('select')?></a></li>
	<li><a href="<?=Application::getAdminURL("user/delete.php?id={$USER['ATTR']['ID']}")?>" title="<?=$Language->text('delete_user')?>"><i class="fa fa-trash-o"></i><?=$Language->text('delete')?></a></li>
</ul>

<?=$HTML?>
</main>
