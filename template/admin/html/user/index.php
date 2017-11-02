<h1><i class="fa fa-user"></i><?=$Language->text('user_overview')?></h1>
<p class="actions-before"><?=$Language->text('overview_user_desc')?></p>
<ul class="actions">
	<li><a href="<?=Application::getAdminURL('user/insert.php')?>" title="<?=$Language->text('insert_user')?>"><i class="fa fa-pencil-square-o"></i><?=$Language->text('insert')?></a></li>
</ul>

<div class="item-container user">
	<?php foreach($LIST['USERS'] as $user): ?>
		<?php echo $user; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>