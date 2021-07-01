<main id="main-content" class="wide">
<h1><i class="fa fa-tags"></i><?=$Language->text('category_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_category_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('category/insert.php')?>" title="<?=$Language->text('insert_category')?>"><i class="fa fa-plus"></i><?=$Language->text('insert')?></a></li>
</ul>

<div class="item-container category grid">
	<?php foreach($LIST['CATEGORIES'] as $category): ?>
		<?php echo $category; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>
</main>
