<h1>
	<i class="fa fa-file-text-o"></i><?=$Language->text('page_overview')?>
	<a class="brackets" href="<?=Application::getAdminURL("page/insert.php")?>"><?=$Language->text('insert')?></a>
</h1>
<p><?=$Language->text('overview_page_desc')?></p>

<div class="item-container page">
	<?php foreach($LIST['PAGES'] as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>