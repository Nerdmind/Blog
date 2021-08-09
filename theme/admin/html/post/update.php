<main id="main-content">
<h1>
	<span class="item-id">#<?=$POST['ATTR']['ID']?></span>
	<i class="fa fa-pencil-square-o"></i><?=$Language->text('update_post')?>
</h1>
<p class="actions-before"><?=$Language->text('update_post_desc')?></p>
<ul class="actions">
	<li><a href="<?=$POST['URL']?>" title="<?=$Language->text('select_post')?>"><i class="fa fa-external-link"></i><?=$Language->text('select')?></a></li>
	<li><a href="<?=Application::getAdminURL("post/delete.php?id={$POST['ATTR']['ID']}")?>" title="<?=$Language->text('delete_post')?>"><i class="fa fa-trash-o"></i><?=$Language->text('delete')?></a></li>
</ul>

<?=$HTML?>
</main>
