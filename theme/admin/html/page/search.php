<main id="main-content" <?=!$PAGES ?: 'class="wide"'?>>
<h1><i class="fa fa-search"></i><?=$Language->text('title_page_search')?></h1>
<p><?=$Language->text('search_page_desc')?></p>

<form id="search-form" method="GET">
	<div class="form-border-box background padding">
		<input id="search-text" type="search" name="q" placeholder="<?=$Language->text('placeholder_search')?>" value="<?=escapeHTML($QUERY)?>" />
	</div>
	<div class="form-border-box background padding">
		<input id="update-button" type="submit" value="<?=$Language->text('search')?>" />
	</div>
</form>

<div class="item-container post grid">
	<?php foreach($PAGES as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>
</main>
