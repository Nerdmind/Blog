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
			<div class="form-label-flex"><label for="L_ID">ID</label></div>
			<div class="form-field-flex"><input<?=($FORM['TYPE'] === 'UPDATE') ? ' disabled="disabled"' : '';?> id="L_ID" name="id" placeholder="[AUTO_INCREMENT]" value="<?=escapeHTML($FORM['DATA']['ID'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-key"></i></div>
			<div class="form-label-flex"><label for="L_PASSWORD"><?=$Language->template('label_password')?></label></div>
			<div class="form-field-flex"><input id="L_PASSWORD" name="password" placeholder="[NO CHANGE]" value="<?=escapeHTML($FORM['DATA']['PASSWORD'])?>" type="password" /></div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-user"></i></div>
			<div class="form-label-flex"><label for="L_FULLNAME"><?=$Language->template('label_fullname')?></label></div>
			<div class="form-field-flex"><input id="L_FULLNAME" name="fullname" value="<?=escapeHTML($FORM['DATA']['FULLNAME'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-envelope-o"></i></div>
			<div class="form-label-flex"><label for="L_MAILADDR"><?=$Language->template('label_mailaddr')?></label></div>
			<div class="form-field-flex"><input id="L_MAILADDR" name="mailaddr" value="<?=escapeHTML($FORM['DATA']['MAILADDR'])?>" /></div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-user-secret"></i></div>
			<div class="form-label-flex"><label for="L_USERNAME"><?=$Language->template('label_username')?></label></div>
			<div class="form-field-flex"><input id="L_USERNAME" name="username" value="<?=escapeHTML($FORM['DATA']['USERNAME'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-link"></i></div>
			<div class="form-label-flex"><label for="L_SLUG"><?=$Language->template('label_slug')?></label></div>
			<div class="form-field-flex"><input id="L_SLUG" name="slug" value="<?=escapeHTML($FORM['DATA']['SLUG'])?>" /></div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="L_TIME_INSERT"><?=$Language->template('label_insert')?></label></div>
			<div class="form-field-flex"><input id="L_TIME_INSERT" name="time_insert" placeholder="[YYYY-MM-DD HH:II:SS]" value="<?=escapeHTML($FORM['DATA']['TIME_INSERT'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="L_TIME_UPDATE"><?=$Language->template('label_update')?></label></div>
			<div class="form-field-flex"><input id="L_TIME_UPDATE" name="time_update" placeholder="<?=escapeHTML($FORM['DATA']['TIME_UPDATE'] ? $FORM['DATA']['TIME_UPDATE'] : '[CURRENT_TIMESTAMP]')?>" value="" /></div>
		</section>
	</section>
	<section class="flex flex-padding">
		<textarea id="content-editor" name="body" placeholder="[…]"><?=escapeHTML($FORM['DATA']['BODY'])?></textarea>
	</section>
	<section class="flex flex-padding background flex-emoticons">
		<ul class="button-list emoticons">
			<?php foreach(getEmoticons() as $emoticon => $data):?>
				<li onmousedown="emoticonReplace('<?=$emoticon?>')" title="<?=$data[1]?>"><?=$data[0]?></li>
			<?php endforeach; ?>
		</ul>
	</section>
	<section class="flex flex-padding background">
		<ul class="button-list markdown">
			<li onmousedown="markdownReplace('bold');" class="fa fa-bold" title="<?=$Language->template('markdown_bold')?>"></li>
			<li onmousedown="markdownReplace('italic');" class="fa fa-italic" title="<?=$Language->template('markdown_italic')?>"></li>
			<li onmousedown="markdownReplace('heading');" class="fa fa-header" title="<?=$Language->template('markdown_heading')?>"></li>
			<li onmousedown="markdownReplace('link');" class="fa fa-link" title="<?=$Language->template('markdown_link')?>"></li>
			<li onmousedown="markdownReplace('image');" class="fa fa-picture-o" title="<?=$Language->template('markdown_image')?>"></li>
			<li onmousedown="markdownReplace('code');" class="fa fa-code" title="<?=$Language->template('markdown_code')?>"></li>
			<li onmousedown="markdownReplace('quote');" class="fa fa-quote-right" title="<?=$Language->template('markdown_quote')?>"></li>
			<li onmousedown="markdownReplace('list_ul');" class="fa fa-list-ul" title="<?=$Language->template('markdown_list_ul')?>"></li>
			<li onmousedown="markdownReplace('list_ol');" class="fa fa-list-ol" title="<?=$Language->template('markdown_list_ol')?>"></li>
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