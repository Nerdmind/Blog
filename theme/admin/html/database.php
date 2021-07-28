<main id="main-content">
<h1><i class="fa fa-database"></i><?=$Language->text('overview_database_text')?></h1>
<p><?=$Language->text('overview_database_desc')?></p>

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
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>" />

	<div class="form-border-box">
		<textarea id="content-editor" placeholder="<?=$Language->text('database_warning')?>" name="command" required autofocus><?=escapeHTML($FORM['COMMAND'])?></textarea>
	</div>

<?php if($FORM['RESULT']): ?>
	<div class="form-border-box background padding">
		<pre><?=escapeHTML($FORM['RESULT'])?></pre>
	</div>
<?php endif; ?>

	<div class="form-border-box background padding">
		<input id="insert-button" type="submit" name="execute" value="Execute" />
	</div>
</form>
</main>
