<h1><i class="fa fa-newspaper-o"></i><?=$Language->text('category_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_category_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('category/insert.php')?>" title="<?=$Language->text('insert_category')?>"><i class="fa fa-pencil-square-o"></i><?=$Language->text('insert')?></a></li>
	<li><a href="<?=Application::getAdminURL('category/search.php')?>" title="<?=$Language->text('search_category')?>"><i class="fa fa-search"></i><?=$Language->text('search')?></a></li>
</ul>

<div class="item-container post">
	<?php foreach($LIST['CATEGORIES'] as $category): ?>
		<?php echo $category; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>