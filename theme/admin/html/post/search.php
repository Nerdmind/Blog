<?php
function categorySelectList($category_tree, $selected = NULL, $prefix = '') {
	foreach($category_tree as $category) {
		$option = '<option value="%s"%s>%s%s [%d]</option>';
		$select = ($category['ID'] == $selected) ? ' selected' : '';

		printf($option, $category['ID'], $select, $prefix, escapeHTML($category['NAME']), $category['ID']);

		if(isset($category['CHILDS'])) {
			# If there are children, call self and pass children array.
			(__FUNCTION__)($category['CHILDS'], $selected, $prefix.'– ');
		}
	}
}
?>
<main id="main-content" <?=!$POSTS ?: 'class="wide"'?>>
<h1><i class="fa fa-search"></i><?=$Language->text('title_post_search')?></h1>
<p><?=$Language->text('search_post_desc')?></p>

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
			<input id="form_query" type="search" name="q" placeholder="<?=$Language->text('placeholder_search')?>" value="<?=escapeHTML($QUERY)?>" required autofocus />
		</div>

		<label for="form_category">
			<i class="fa fa-tag"></i><?=$Language->text('label_category')?></label>

		<div class="form-grid-item">
			<select id="form_category" name="category">
				<option value="">[ –– <?=$Language->text('label_category')?> –– ]</option>
				<?=categorySelectList($FORM['CATEGORY_TREE'], $FORM['DATA']['CATEGORY']);?>
			</select>
		</div>

		<label for="form_user">
			<i class="fa fa-user"></i><?=$Language->text('label_user')?></label>

		<div class="form-grid-item">
			<select id="form_user" name="user">
				<option value="">[ –– <?=$Language->text('label_user')?> –– ]</option>
				<?php foreach($FORM['USER_LIST'] as $user): ?>
					<option value="<?=$user['ID']?>"<?=($FORM['DATA']['USER'] == $user['ID']) ? ' selected' : '' ?>><?=escapeHTML($user['FULLNAME'])?> [<?=$user['USERNAME']?>]</option>
				<?php endforeach; ?>
			</select>
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
