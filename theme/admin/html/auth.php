<main id="main-content">
<h1><i class="fa fa-sign-in"></i><?=$Language->text('authentication_text')?></h1>
<p><?=$Language->text('authentication_desc')?></p>

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

	<div class="form-grid two-columns">
		<label for="form_username">
			<i class="fa fa-user-secret"></i><?=$Language->text('label_username')?></label>

		<div class="form-grid-item">
			<input id="form_username" name="username" value="<?=escapeHTML($FORM['DATA']['USERNAME'])?>" required autofocus>
		</div>

		<label for="form_password">
			<i class="fa fa-key"></i><?=$Language->text('label_password')?></label>

		<div class="form-grid-item">
			<input type="password" id="form_password" name="password" required>
		</div>
	</div>

	<div class="form-border-box background padding nobordertop">
		<input type="submit" name="auth" value="<?=$Language->text('login')?>">
	</div>
</form>
</main>
