<li class="item-list-li post">
	<header>
		<h2><i class="fa fa-newspaper-o"></i><?=escapeHTML($POST['ATTR']['NAME'])?><span>#<?=$POST['ID']?></span></h2>
		<div>
			<a class="brackets" href="<?=Application::getAdminURL("user/update.php?id={$USER['ID']}")?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
			<time class="brackets" datetime="<?=$POST['ATTR']['TIME_INSERT']?>"><?=parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->template('date_format'))?></time>
		</div>
	</header>
	<blockquote cite="<?=$POST['URL']?>">
		<p><?=excerpt($POST['BODY']['HTML'])?></p>
	</blockquote>

	<?php if($POST['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($POST['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?></strong>: <span class="blue"><?=escapeHTML($value)?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<footer>
		<ul>
			<li><a href="<?=$POST['URL']?>" target="_blank" title="<?=$Language->text('select_post')?>"><i class="fa fa-external-link"></i></a></li>
			<li><a href="<?=Application::getAdminURL("post/update.php?id={$POST['ID']}")?>" title="<?=$Language->text('update_post')?>"><i class="fa fa-pencil-square-o"></i></a></li>
			<li><a href="<?=Application::getAdminURL("post/delete.php?id={$POST['ID']}")?>" title="<?=$Language->text('delete_post')?>"><i class="fa fa-trash-o"></i></a></li>
		</ul>
	</footer>
</li>