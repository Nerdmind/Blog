<main id="main-content">
<h1>
	<span class="item-id">#<?=$PAGE['ATTR']['ID']?></span>
	<i class="fa fa-pencil-square-o"></i><?=$Language->text('update_page')?>
</h1>
<p class="actions-before"><?=$Language->text('update_page_desc')?></p>
<ul class="actions">
	<li><a href="<?=$PAGE['URL']?>" title="<?=$Language->text('select_page')?>"><i class="fa fa-external-link"></i><?=$Language->text('select')?></a></li>
	<li><a href="<?=Application::getAdminURL("page/delete.php?id={$PAGE['ATTR']['ID']}")?>" title="<?=$Language->text('delete_page')?>"><i class="fa fa-trash-o"></i><?=$Language->text('delete')?></a></li>
</ul>

<?=$HTML?>
</main>
