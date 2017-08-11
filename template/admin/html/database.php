<h1><i class="fa fa-database"></i><?=$Language->text('overview_database_text')?></h1>
<p><?=$Language->text('overview_database_desc')?></p>

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
		<textarea id="content-editor" placeholder="<?=$Language->text('database_warning')?>" name="command"><?=escapeHTML($FORM['COMMAND'])?></textarea>
	</div>

<?php if($FORM['RESULT']): ?>
	<div class="flex flex-padding background flex-direction-column">
		<pre id="database-result"><?=escapeHTML($FORM['RESULT'])?></pre>
	</div>
<?php endif; ?>

	<div class="flex flex-padding background">
		<input id="insert-button" type="submit" name="execute" value="Execute" />
	</div>
</form>