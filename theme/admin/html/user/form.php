<?php if($FORM['INFO']): ?>
	<div id="message-list-wrapper">
		<ul id="message-list">
			<?php foreach($FORM['INFO'] as $message): ?>
				<li><?=$message?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>">

<?php if($FORM['TYPE'] !== 'DELETE'): ?>
	<div class="form-grid">
		<label for="form_fullname">
			<i class="fa fa-user"></i><?=$Language->text('label_fullname')?></label>

		<div class="form-grid-item first">
			<input id="form_fullname" name="fullname" value="<?=escapeHTML($FORM['DATA']['FULLNAME'])?>" required autofocus>
		</div>

		<label for="form_username">
			<i class="fa fa-user-secret"></i><?=$Language->text('label_username')?></label>

		<div class="form-grid-item">
			<input id="form_username" name="username" value="<?=escapeHTML($FORM['DATA']['USERNAME'])?>" required>
		</div>

		<label for="form_password">
			<i class="fa fa-key"></i><?=$Language->text('label_password')?></label>

		<div class="form-grid-item">
			<input id="form_password" name="password" placeholder="NO CHANGE" type="password">
		</div>

		<label for="form_mailaddr">
			<i class="fa fa-envelope-o"></i><?=$Language->text('label_mailaddr')?></label>

		<div class="form-grid-item">
			<input id="form_mailaddr" name="mailaddr" value="<?=escapeHTML($FORM['DATA']['MAILADDR'])?>" required>
		</div>

		<label for="form_slug">
			<i class="fa fa-link"></i><?=$Language->text('label_slug')?></label>

		<div class="form-grid-item">
			<input id="form_slug" name="slug" value="<?=escapeHTML($FORM['DATA']['SLUG'])?>">
		</div>

		<label for="form_time_insert">
			<i class="fa fa-clock-o"></i><?=$Language->text('label_insert')?></label>

		<div class="form-grid-item">
			<input id="form_time_insert" name="time_insert" placeholder="YYYY-MM-DD HH:II:SS" value="<?=escapeHTML($FORM['DATA']['TIME_INSERT'])?>">
		</div>

		<label for="form_time_update">
			<i class="fa fa-pencil"></i><?=$Language->text('label_update')?></label>

		<div class="form-grid-item">
			<input id="form_time_update" name="time_update" placeholder="<?=escapeHTML($FORM['DATA']['TIME_UPDATE'] ?: 'CURRENT_TIMESTAMP')?>" value="">
		</div>
	</div>

	<div id="content-editor-wrapper" class="form-border-box">
		<div id="button-list-wrapper">
			<ul id="markdown-list" class="button-list markdown">
				<li data-markdown="bold" class="fa fa-bold" title="<?=$Language->text('markdown_bold')?>"></li>
				<li data-markdown="italic" class="fa fa-italic" title="<?=$Language->text('markdown_italic')?>"></li>
				<li data-markdown="heading" class="fa fa-header" title="<?=$Language->text('markdown_heading')?>"></li>
				<li data-markdown="link" class="fa fa-link" title="<?=$Language->text('markdown_link')?>"></li>
				<li data-markdown="image" class="fa fa-picture-o" title="<?=$Language->text('markdown_image')?>"></li>
				<li data-markdown="code" class="fa fa-code" title="<?=$Language->text('markdown_code')?>"></li>
				<li data-markdown="quote" class="fa fa-quote-right" title="<?=$Language->text('markdown_quote')?>"></li>
				<li data-markdown="list_ul" class="fa fa-list-ul" title="<?=$Language->text('markdown_list_ul')?>"></li>
				<li data-markdown="list_ol" class="fa fa-list-ol" title="<?=$Language->text('markdown_list_ol')?>"></li>
			</ul>
		</div>
		<textarea id="content-editor" name="body" placeholder="[…]" required><?=escapeHTML($FORM['DATA']['BODY'])?></textarea>
	</div>
	<div id="emoticon-list-wrapper" class="form-border-box background padding">
		<ul id="emoticon-list" class="button-list emoticons">
			<?php foreach(getUnicodeEmoticons() as $emoticon => $explanation):?>
				<li data-emoticon="<?=$emoticon?>" title="<?=$explanation?>"><?=$emoticon?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="form-border-box background padding">
		<input id="form_argv" name="argv" maxlength="250" placeholder="[ARGUMENT_ONE=foo|ARGUMENT_TWO=bar …]" value="<?=escapeHTML($FORM['DATA']['ARGV'])?>">
	</div>
<?php else: ?>
	<div class="form-border-box background padding">
		<?=$HTML?>
	</div>
<?php endif; ?>

	<div class="form-border-box background padding">
		<?php if($FORM['TYPE'] === 'INSERT'): ?>
			<input id="insert-button" type="submit" name="insert" value="<?=$Language->text('insert')?>">
		<?php elseif($FORM['TYPE'] === 'UPDATE'): ?>
			<input id="update-button" type="submit" name="update" value="<?=$Language->text('update')?>">
		<?php elseif($FORM['TYPE'] === 'DELETE'): ?>
			<input id="delete-button" type="submit" name="delete" value="<?=$Language->text('delete')?>" data-text="<?=$Language->text('sure')?>">
		<?php endif; ?>
	</div>
</form>
