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

<?php if($FORM['TYPE'] !== 'DELETE'): ?>
	<div class="flex flex-responsive">
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-database"></i></div>
			<div class="form-label-flex"><label for="form_id">ID</label></div>
			<div class="form-field-flex"><input<?=($FORM['TYPE'] === 'UPDATE') ? ' disabled="disabled"' : '';?> id="form_id" name="id" type="number" placeholder="[AUTO_INCREMENT]" value="<?=escapeHTML($FORM['DATA']['ID'])?>" /></div>
		</div>
		<div class="flex-item">
		</div>
	</div>
	<div class="flex flex-responsive">
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-file-text-o"></i></div>
			<div class="form-label-flex"><label for="form_name"><?=$Language->text('label_name')?></label></div>
			<div class="form-field-flex"><input id="form_name" name="name" value="<?=escapeHTML($FORM['DATA']['NAME'])?>" /></div>
		</div>
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-link"></i></div>
			<div class="form-label-flex"><label for="form_slug"><?=$Language->text('label_slug')?></label></div>
			<div class="form-field-flex"><input id="form_slug" name="slug" value="<?=escapeHTML($FORM['DATA']['SLUG'])?>" /></div>
		</div>
	</div>
	<div class="flex flex-responsive">
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="form_time_insert"><?=$Language->text('label_insert')?></label></div>
			<div class="form-field-flex"><input id="form_time_insert" name="time_insert" placeholder="[YYYY-MM-DD HH:II:SS]" value="<?=escapeHTML($FORM['DATA']['TIME_INSERT'])?>" /></div>
		</div>
		<div class="flex-item">
			<div class="form-icon-flex"><i class="fa fa-clock-o"></i></div>
			<div class="form-label-flex"><label for="form_time_update"><?=$Language->text('label_update')?></label></div>
			<div class="form-field-flex"><input id="form_time_update" name="time_update" placeholder="<?=escapeHTML($FORM['DATA']['TIME_UPDATE'] ? $FORM['DATA']['TIME_UPDATE'] : '[CURRENT_TIMESTAMP]')?>" value="" /></div>
		</div>
	</div>
<?php else: ?>
	<div class="flex flex-padding background flex-direction-column">
		<?=$HTML?>
	</div>
<?php endif; ?>

	<div class="flex flex-padding background">
		<?php if($FORM['TYPE'] === 'INSERT'): ?>
			<input id="insert-button" type="submit" name="insert" value="<?=$Language->text('insert')?>" />
		<?php elseif($FORM['TYPE'] === 'UPDATE'): ?>
			<input id="update-button" type="submit" name="update" value="<?=$Language->text('update')?>" />
		<?php elseif($FORM['TYPE'] === 'DELETE'): ?>
			<input id="delete-button" type="submit" name="delete" value="<?=$Language->text('delete')?>" data-text="<?=$Language->text('sure')?>" />
		<?php endif; ?>
	</div>
</form>