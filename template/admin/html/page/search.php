<h1>
	<i class="fa fa-search"></i><?=$Language->text('title_page_search')?>
	<a class="brackets" href="<?=Application::getAdminURL("page/insert.php")?>"><?=$Language->text('insert')?></a>
</h1>
<p><?=$Language->text('search_page_desc')?></p>

<form id="search-form" method="GET">
	<div class="flex flex-padding background">
		<input id="search-text" type="search" name="q" placeholder="<?=$Language->text('placeholder_search')?>" value="<?=escapeHTML($QUERY)?>" />
	</div>
	<div class="flex flex-padding background">
		<input id="update-button" type="submit" value="<?=$Language->text('search')?>" />
	</div>
</form>

<div class="item-container post">
	<?php foreach($PAGES as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>