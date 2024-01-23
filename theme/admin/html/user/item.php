<article class="item">
	<header>
		<h2>
			<span class="item-id">#<?=$USER['ATTR']['ID']?></span>
			<i class="fa fa-user"></i><?=escapeHTML($USER['ATTR']['FULLNAME'])?>
		</h2>
		<ul class="item-meta">
			<li>
				<i class="fa fa-clock-o"></i>
				<time datetime="<?=$USER['ATTR']['TIME_INSERT']?>"><?=parseDatetime($USER['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
			</li>
			<li>
				<i class="fa fa-user-secret"></i>
				<?=escapeHTML($USER['ATTR']['USERNAME'])?>
			</li>
			<li class="item-meta-right">
				<i class="fa fa-envelope-o"></i>
				<a href="mailto:<?=escapeHTML($USER['ATTR']['MAILADDR'])?>"><?=escapeHTML($USER['ATTR']['MAILADDR'])?></a>
			</li>
		</ul>
	</header>
	<blockquote cite="<?=$USER['URL']?>">
		<?php if(isset($USER['FILE']['LIST'][0])): ?>
			<img class="item-image" src="<?=$USER['FILE']['LIST'][0]?>" alt="">
		<?php endif; ?>
		<p><?=excerpt($USER['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($USER['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($USER['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?>:</strong> <code><?=escapeHTML($value)?></code></li>
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
