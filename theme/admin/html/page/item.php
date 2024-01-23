<article class="item">
	<header>
		<h2>
			<span class="item-id">#<?=$PAGE['ATTR']['ID']?></span>
			<i class="fa fa-file-text-o"></i><?=escapeHTML($PAGE['ATTR']['NAME'])?>
		</h2>
		<ul class="item-meta">
			<li>
				<i class="fa fa-clock-o"></i>
				<time datetime="<?=$PAGE['ATTR']['TIME_INSERT']?>"><?=parseDatetime($PAGE['ATTR']['TIME_INSERT'], $Language->text('date_format'))?></time>
			</li>
			<li>
				<i class="fa fa-user"></i>
				<a href="<?=Application::getAdminURL("user/update.php?id={$USER['ATTR']['ID']}")?>" title="<?=$Language->text('update_user')?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a>
			</li>
		</ul>
	</header>
	<blockquote cite="<?=$PAGE['URL']?>">
		<?php if(isset($PAGE['FILE']['LIST'][0])): ?>
			<img class="item-image" src="<?=$PAGE['FILE']['LIST'][0]?>" alt="">
		<?php endif; ?>
		<p><?=excerpt($PAGE['BODY']['HTML']())?></p>
	</blockquote>

	<?php if($PAGE['ARGV']): ?>
		<ul class="arguments">
			<?php foreach($PAGE['ARGV'] as $argument => $value): ?>
				<li><strong><?=$argument?>:</strong> <code><?=escapeHTML($value)?></code></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<footer>
		<ul>
			<li><a href="<?=$PAGE['URL']?>" target="_blank" title="<?=$Language->text('select_page')?>"><i class="fa fa-external-link"></i><span class="hidden"><?=$Language->text('select_page')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("page/update.php?id={$PAGE['ATTR']['ID']}")?>" title="<?=$Language->text('update_page')?>"><i class="fa fa-pencil-square-o"></i><span class="hidden"><?=$Language->text('update_page')?></span></a></li>
			<li><a href="<?=Application::getAdminURL("page/delete.php?id={$PAGE['ATTR']['ID']}")?>" title="<?=$Language->text('delete_page')?>"><i class="fa fa-trash-o"></i><span class="hidden"><?=$Language->text('delete_page')?></span></a></li>
		</ul>
	</footer>
</article>
