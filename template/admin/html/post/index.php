<h1>
	<i class="fa fa-newspaper-o"></i><?=$Language->text('post_overview')?>
	<a class="brackets" href="<?=Application::getAdminURL("post/insert.php")?>"><?=$Language->text('insert')?></a>
</h1>
<p><?=$Language->text('overview_post_desc')?></p>

<div class="item-container post">
	<?php foreach($LIST['POSTS'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>