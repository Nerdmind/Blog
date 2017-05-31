<h1><i class="fa fa-database"></i><?=$Language->template('overview_database_text')?></h1>
<p><?=$Language->template('overview_database_desc')?></p>

<?php if(isset($FORM['INFO'])): ?>
	<?php foreach($FORM['INFO'] as $message): ?>
		<div class="red"><?=$message?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>" />

	<div class="flex">
		<textarea id="content-editor" placeholder="<?=$Language->template('database_warning')?>" name="command"><?=escapeHTML($FORM['COMMAND'])?></textarea>
	</div>

<?php if($FORM['RESULT']): ?>
	<div class="flex flex-padding background flex-direction-column">
		<pre id="database-result"><?=escapeHTML($FORM['RESULT'])?></pre>
	</div>
<?php endif; ?>

	<div class="flex flex-padding background">
		<input type="submit" name="execute" value="Execute" />
	</div>
</form>