<h1><i class="fa fa-newspaper-o"></i><?=$Language->text('post_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_post_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('post/insert.php')?>" title="<?=$Language->text('insert_post')?>"><i class="fa fa-pencil-square-o"></i><?=$Language->text('insert')?></a></li>
	<li><a href="<?=Application::getAdminURL('post/search.php')?>" title="<?=$Language->text('search_post')?>"><i class="fa fa-search"></i><?=$Language->text('search')?></a></li>
</ul>

<div class="item-container post">
	<?php foreach($LIST['POSTS'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>