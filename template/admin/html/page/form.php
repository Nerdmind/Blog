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
			<div class="form-icon-flex"><i class="fa fa-user"></i></div>
			<div class="form-label-flex"><label for="L_USER"><?=$Language->template('LABEL_USER')?></label></div>
			<div class="form-field-flex">
				<select id="L_USER" name="user">
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
			<div class="form-label-flex"><label for="L_NAME"><?=$Language->template('LABEL_NAME')?></label></div>
			<div class="form-field-flex"><input id="L_NAME" name="name" value="<?=escapeHTML($FORM['DATA']['NAME'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-link"></i></div>
			<div class="form-label-flex"><label for="L_SLUG"><?=$Language->template('LABEL_SLUG')?></label></div>
			<div class="form-field-flex"><input id="L_SLUG" name="slug" value="<?=escapeHTML($FORM['DATA']['SLUG'])?>" /></div>
		</section>
	</section>
	<section class="flex flex-responsive">
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="L_TIME_INSERT"><?=$Language->template('LABEL_INSERT')?></label></div>
			<div class="form-field-flex"><input id="L_TIME_INSERT" name="time_insert" placeholder="[YYYY-MM-DD HH:II:SS]" value="<?=escapeHTML($FORM['DATA']['TIME_INSERT'])?>" /></div>
		</section>
		<section>
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="L_TIME_UPDATE"><?=$Language->template('LABEL_UPDATE')?></label></div>
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
			<li onmousedown="markdownReplace('bold');" class="fa fa-bold" title="Bold"></li>
			<li onmousedown="markdownReplace('italic');" class="fa fa-italic" title="Italic"></li>
			<li onmousedown="markdownReplace('header');" class="fa fa-header" title="Heading"></li>
			<li onmousedown="markdownReplace('link');" class="fa fa-link" title="Link"></li>
			<li onmousedown="markdownReplace('image');" class="fa fa-picture-o" title="Image"></li>
			<li onmousedown="markdownReplace('code');" class="fa fa-code" title="Code"></li>
			<li onmousedown="markdownReplace('quote');" class="fa fa-quote-right" title="Quote"></li>
			<li onmousedown="markdownReplace('list_ul');" class="fa fa-list-ul" title="List [unordered]"></li>
			<li onmousedown="markdownReplace('list_ol');" class="fa fa-list-ol" title="List [ordered]"></li>
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