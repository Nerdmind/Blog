<h1><?=$Language->template('search_result_heading_text', escapeHTML($SEARCH['TEXT']))?></h1>
<p><?=$Language->template('search_result_heading_desc')?></p>

<form action="" method="GET">
	<input autofocus type="search" name="q" placeholder="<?=$Language->template('search_form_placeholder')?>" value="<?=escapeHTML($SEARCH['TEXT'])?>" />

	<select name="d">
		<option value=""><?=$Language->text('date_d')?></option>

		<?php foreach($FORM['OPTIONS']['D'] as $option): ?>
			<option value="<?=$option?>"<?=($FORM['SELECT']['D'] === $option) ? ' selected' : '' ?>><?=$option?></option>
		<?php endforeach; ?>
	</select>
	<select name="m">
		<option value=""><?=$Language->text('date_m')?></option>

		<?php foreach($FORM['OPTIONS']['M'] as $option): ?>
			<option value="<?=$option?>"<?=($FORM['SELECT']['M'] === $option) ? ' selected' : '' ?>><?=$option?></option>
		<?php endforeach; ?>
	</select>
	<select name="y">
		<option value=""><?=$Language->text('date_y')?></option>

		<?php foreach($FORM['OPTIONS']['Y'] as $option): ?>
			<option value="<?=$option?>"<?=($FORM['SELECT']['Y'] === $option) ? ' selected' : '' ?>><?=$option?></option>
		<?php endforeach; ?>
	</select>

	<input type="submit" value="<?=$Language->text('search')?>" />
</form>

<ul class="item-list post">
	<?php foreach($RESULT['LIST'] as $post): ?>
		<?php echo $post; ?>
	<?php endforeach; ?>
</ul>