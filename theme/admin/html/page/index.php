<h1><i class="fa fa-file-text-o"></i><?=$Language->text('page_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_page_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('page/insert.php')?>" title="<?=$Language->text('insert_page')?>"><i class="fa fa-pencil-square-o"></i><?=$Language->text('insert')?></a></li>
	<li><a href="<?=Application::getAdminURL('page/search.php')?>" title="<?=$Language->text('search_page')?>"><i class="fa fa-search"></i><?=$Language->text('search')?></a></li>
</ul>

<div class="item-container page">
	<?php foreach($LIST['PAGES'] as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>