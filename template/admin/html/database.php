<h1><i class="fa fa-database"></i><?=$Language->template('overview_database_text')?></h1>
<p><?=$Language->template('overview_database_desc')?></p>

<?php if(isset($FORM['INFO'])): ?>
	<?php foreach($FORM['INFO'] as $message): ?>
		<div class="red"><?=$message?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form action="" method="POST">
	<input type="hidden" name="token" value="<?=$FORM['TOKEN']?>" />

	<section class="flex flex-padding">
		<textarea id="content-editor" placeholder="<?=$Language->template('database_warning')?>" name="command"><?=escapeHTML($FORM['COMMAND'])?></textarea>
	</section>

<?php if($FORM['RESULT']): ?>
	<section class="flex flex-padding background flex-direction-column">
		<pre id="database-result"><?=escapeHTML($FORM['RESULT'])?></pre>
	</section>
<?php endif; ?>

	<section class="flex flex-padding background">
		<input type="submit" name="execute" value="Execute" />
	</section>
</form>