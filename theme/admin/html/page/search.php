<main id="main-content" <?=!$PAGES ?: 'class="wide"'?>>
<h1><i class="fa fa-search"></i><?=$Language->text('title_page_search')?></h1>
<p><?=$Language->text('search_page_desc')?></p>

<?php if($FORM['INFO']): ?>
	<div id="message-list-wrapper">
		<ul id="message-list">
			<?php foreach($FORM['INFO'] as $message): ?>
				<li><?=$message?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>

<form id="search-form" method="GET">
	<div class="form-grid no-bottom-border">
		<label for="form_query">
			<i class="fa fa-search"></i><?=$Language->text('search')?></label>

		<div class="form-grid-item first">
			<input id="form_query" type="search" name="q" placeholder="<?=$Language->text('placeholder_search')?>" value="<?=escapeHTML($QUERY)?>" required autofocus>
		</div>
	</div>
	<div class="form-border-box background padding">
		<input id="update-button" type="submit" value="<?=$Language->text('search')?>">
	</div>
</form>

<div class="item-container post grid">
	<?php foreach($PAGES as $page): ?>
		<?php echo $page; ?>
	<?php endforeach; ?>
</div>

<?php if($PAGINATION): ?>
	<?=$PAGINATION['HTML']?>
<?php endif ?>
</main>
