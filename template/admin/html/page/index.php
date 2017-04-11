<h1><i class="fa fa-file-text-o"></i><?=$Language->text('page_overview')?><a class="brackets" href="<?=Application::getAdminURL("page/insert.php")?>"><?=$Language->text('insert')?></a></h1>
<p><?=$Language->template('overview_page_desc')?></p>

<ul class="item-list page">
	<?php foreach($LIST['PAGES'] as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</ul>

<?=$PAGINATION['HTML']?>