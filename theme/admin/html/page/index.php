<main id="main-content" class="wide">
<h1><i class="fa fa-folder-open"></i><?=$Language->text('page_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_page_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('page/insert.php')?>" title="<?=$Language->text('insert_page')?>"><i class="fa fa-plus"></i><?=$Language->text('insert')?></a></li>
	<li><a href="<?=Application::getAdminURL('page/search.php')?>" title="<?=$Language->text('search_page')?>"><i class="fa fa-search"></i><?=$Language->text('search')?></a></li>
</ul>

<div class="item-container page grid">
	<?php foreach($LIST['PAGES'] as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>
</main>
