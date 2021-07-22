<main id="main-content" <?=!$POSTS ?: 'class="wide"'?>>
<h1><i class="fa fa-search"></i><?=$Language->text('title_post_search')?></h1>
<p><?=$Language->text('search_post_desc')?></p>

<form id="search-form" method="GET">
	<div class="form-grid no-bottom-border">
		<label for="form_query">
			<i class="fa fa-search"></i><?=$Language->text('search')?></label>

		<div class="form-grid-item first">
			<input id="form_query" type="search" name="q" placeholder="<?=$Language->text('placeholder_search')?>" value="<?=escapeHTML($QUERY)?>" />
		</div>
	</div>
	<div class="form-border-box background padding">
		<input id="update-button" type="submit" value="<?=$Language->text('search')?>" />
	</div>
</form>

<div class="item-container post grid">
	<?php foreach($POSTS as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</div>

<?php if($PAGINATION): ?>
	<?=$PAGINATION['HTML']?>
<?php endif ?>
</main>
