<article class="item">
	<header>
		<h2><i class="fa fa-user"></i><?=escapeHTML($USER['ATTR']['FULLNAME'])?></h2>
		<div>
			<span class="brackets item-id">#<?=$USER['ATTR']['ID']?></span>
			<time class="brackets" datetime="<?=$USER['ATTR']['TIME_INSERT']?>"><?=parseDatetime($USER['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
		</div>
	</header>
	<blockquote cite="<?=$USER['URL']?>">
		<p><?=excerpt($USER['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($USER['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($USER['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?>:</strong> <span><?=escapeHTML($value)?></span></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<footer>
		<ul>
			<li><a href="<?=$USER['URL']?>" target="_blank" title="<?=$Language->text('select_user')?>"><i class="fa fa-external-link"></i><span class="hidden"><?=$Language->text('select_user')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("user/update.php?id={$USER['ATTR']['ID']}")?>" title="<?=$Language->text('update_user')?>"><i class="fa fa-pencil-square-o"></i><span class="hidden"><?=$Language->text('update_user')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("user/delete.php?id={$USER['ATTR']['ID']}")?>" title="<?=$Language->text('delete_user')?>"><i class="fa fa-trash-o"></i><span class="hidden"><?=$Language->text('delete_user')?></span></a></li>
		</ul>
	</footer>
</article>