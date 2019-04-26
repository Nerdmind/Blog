<h1><i class="fa fa-sign-in"></i><?=$Language->text('authentication_text')?></h1>
<p><?=$Language->text('authentication_desc')?></p>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>" />

	<?php if($FORM['INFO']): ?>
		<div class="flex flex-direction-column">
			<ul id="message-list">
				<?php foreach($FORM['INFO'] as $message): ?>
					<li><?=$message?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="flex">
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-user-secret"></i></div>
			<div class="form-label-flex"><label for="form_username"><?=$Language->text('label_username')?></label></div>
			<div class="form-field-flex"><input id="form_username" name="username" value="<?=escapeHTML($FORM['DATA']['USERNAME'])?>" /></div>
		</div>
	</div>
	<div class="flex">
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-key"></i></div>
			<div class="form-label-flex"><label for="form_password"><?=$Language->text('label_password')?></label></div>
			<div class="form-field-flex"><input type="password" id="form_password" name="password" /></div>
		</div>
	</div>
	<div class="flex flex-padding background">
		<input type="submit" name="auth" value="<?=$Language->text('login')?>" />
	</div>
</form>