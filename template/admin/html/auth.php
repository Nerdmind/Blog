<h1><?=$Language->template('authentication_text')?></h1>
<p><?=$Language->template('authentication_desc')?></p>

<?php if(isset($FORM['INFO'])): ?>
	<?php foreach($FORM['INFO'] as $message): ?>
		<div class="red"><?=$message?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=Application::getSecurityToken()?>" />

	<section class="flex">
		<section>
			<div class="form-icon-flex"><i class="fa fa-user-secret"></i></div>
			<div class="form-label-flex"><label for="L_USERNAME"><?=$Language->template('label_username')?></label></div>
			<div class="form-field-flex"><input id="L_USERNAME" name="username" value="<?=escapeHTML($FORM['DATA']['USERNAME'])?>" /></div>
		</section>
	</section>
	<section class="flex">
		<section>
			<div class="form-icon-flex"><i class="fa fa-key"></i></div>
			<div class="form-label-flex"><label for="L_PASSWORD"><?=$Language->template('label_password')?></label></div>
			<div class="form-field-flex"><input type="password" id="L_PASSWORD" name="password" /></div>
		</section>
	</section>
	<section class="flex flex-padding background">
		<input type="submit" name="auth" value="Auth" />
	</section>
</form>