<h1><i class="fa fa-search"></i><?=$Language->text('search_base_heading_text')?></h1>
<p><?=$Language->text('search_base_heading_desc')?></p>

<?php if($SEARCH['INFO']): ?>
	<div class="red"><?=$SEARCH['INFO']?></div>
<?php endif; ?>

<form action="" method="GET">
	<input autofocus type="search" name="q" placeholder="<?=$Language->text('search_form_placeholder')?>" value="<?=escapeHTML($SEARCH['TEXT'])?>" />

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