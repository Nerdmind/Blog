<h1>
	<i class="fa fa-user"></i><?=$Language->text('user_overview')?>
	<a class="brackets" href="<?=Application::getAdminURL("user/insert.php")?>"><?=$Language->text('insert')?></a>
</h1>
<p><?=$Language->text('overview_user_desc')?></p>

<div class="item-container user">
	<?php foreach($LIST['USERS'] as $user): ?>
		<?php echo $user; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>