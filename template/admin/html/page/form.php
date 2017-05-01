<?php if(isset($FORM['INFO'])): ?>
	<?php foreach($FORM['INFO'] as $message): ?>
		<div class="red"><?=$message?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>" />

<?php if($FORM['TYPE'] !== 'DELETE'): ?>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-database"></i></div>
			<div class="form-label-flex"><label for="form_id">ID</label></div>
			<div class="form-field-flex"><input<?=($FORM['TYPE'] === 'UPDATE') ? ' disabled="disabled"' : '';?> id="form_id" name="id" type="number" placeholder="[AUTO_INCREMENT]" value="<?=escapeHTML($FORM['DATA']['ID'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-user"></i></div>
			<div class="form-label-flex"><label for="form_user"><?=$Language->template('label_user')?></label></div>
			<div class="form-field-flex">
				<select id="form_user" name="user">
					<?php foreach($FORM['USER_LIST'] as $user): ?>
						<option value="<?=$user['ID']?>"<?=($FORM['DATA']['USER'] === $user['ID']) ? ' selected' : '' ?>><?=escapeHTML($user['FULLNAME'])?> [<?=$user['USERNAME']?>]</option>
					<?php endforeach; ?>
				</select>
			</div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-file-text-o"></i></div>
			<div class="form-label-flex"><label for="form_name"><?=$Language->template('label_name')?></label></div>
			<div class="form-field-flex"><input id="form_name" name="name" value="<?=escapeHTML($FORM['DATA']['NAME'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-link"></i></div>
			<div class="form-label-flex"><label for="form_slug"><?=$Language->template('label_slug')?></label></div>
			<div class="form-field-flex"><input id="form_slug" name="slug" value="<?=escapeHTML($FORM['DATA']['SLUG'])?>" /></div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="form_time_insert"><?=$Language->template('label_insert')?></label></div>
			<div class="form-field-flex"><input id="form_time_insert" name="time_insert" placeholder="[YYYY-MM-DD HH:II:SS]" value="<?=escapeHTML($FORM['DATA']['TIME_INSERT'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="form_time_update"><?=$Language->template('label_update')?></label></div>
			<div class="form-field-flex"><input id="form_time_update" name="time_update" placeholder="<?=escapeHTML($FORM['DATA']['TIME_UPDATE'] ? $FORM['DATA']['TIME_UPDATE'] : '[CURRENT_TIMESTAMP]')?>" value="" /></div>
		</section>
	</section>
	<section class="flex flex-padding">
		<textarea id="content-editor" name="body" placeholder="[…]"><?=escapeHTML($FORM['DATA']['BODY'])?></textarea>
	</section>
	<section class="flex flex-padding background flex-arguments">
		<input id="form_argv" name="argv" maxlength="100" placeholder="[ARGUMENT_FOO=one|ARGUMENT_BAR=two …]" value="<?=escapeHTML($FORM['DATA']['ARGV'])?>" />
	</section>
	<section class="flex flex-padding background flex-emoticons">
		<ul id="emoticon-list" class="button-list emoticons">
			<?php foreach(getEmoticons() as $emoticon => $data):?>
				<li data-emoticon="<?=$emoticon?>" title="<?=$data[1]?>"><?=$data[0]?></li>
			<?php endforeach; ?>
		</ul>
	</section>
	<section class="flex flex-padding background">
		<ul id="markdown-list" class="button-list markdown">
			<li data-markdown="bold" class="fa fa-bold" title="<?=$Language->template('markdown_bold')?>"></li>
			<li data-markdown="italic" class="fa fa-italic" title="<?=$Language->template('markdown_italic')?>"></li>
			<li data-markdown="heading" class="fa fa-header" title="<?=$Language->template('markdown_heading')?>"></li>
			<li data-markdown="link" class="fa fa-link" title="<?=$Language->template('markdown_link')?>"></li>
			<li data-markdown="image" class="fa fa-picture-o" title="<?=$Language->template('markdown_image')?>"></li>
			<li data-markdown="code" class="fa fa-code" title="<?=$Language->template('markdown_code')?>"></li>
			<li data-markdown="quote" class="fa fa-quote-right" title="<?=$Language->template('markdown_quote')?>"></li>
			<li data-markdown="list_ul" class="fa fa-list-ul" title="<?=$Language->template('markdown_list_ul')?>"></li>
			<li data-markdown="list_ol" class="fa fa-list-ol" title="<?=$Language->template('markdown_list_ol')?>"></li>
		</ul>
	</section>
<?php else: ?>
	<section class="flex flex-padding background flex-direction-column">
		<?=$HTML?>
	</section>
<?php endif; ?>

	<section class="flex flex-padding background">
		<?php if($FORM['TYPE'] === 'INSERT'): ?>
			<input type="submit" name="insert" value="<?=$Language->text('insert')?>" />
		<?php elseif($FORM['TYPE'] === 'UPDATE'): ?>
			<input type="submit" name="update" value="<?=$Language->text('update')?>" />
		<?php elseif($FORM['TYPE'] === 'DELETE'): ?>
			<input type="submit" name="delete" value="<?=$Language->text('delete')?>" id="delete-button" data-text="<?=$Language->template('sure')?>" />
		<?php endif; ?>
	</section>
</form>