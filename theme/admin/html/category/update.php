<main id="main-content">
<h1>
	<span class="item-id">#<?=$CATEGORY['ATTR']['ID']?></span>
	<i class="fa fa-pencil-square-o"></i><?=$Language->text('update_category')?>
</h1>
<p class="actions-before"><?=$Language->text('update_category_desc')?></p>
<ul class="actions">
	<li><a href="<?=$CATEGORY['URL']?>" title="<?=$Language->text('select_category')?>"><i class="fa fa-external-link"></i><?=$Language->text('select')?></a></li>
	<li><a href="<?=Application::getAdminURL("category/delete.php?id={$CATEGORY['ATTR']['ID']}")?>" title="<?=$Language->text('delete_category')?>"><i class="fa fa-trash-o"></i><?=$Language->text('delete')?></a></li>
</ul>

<?=$HTML?>
</main>
