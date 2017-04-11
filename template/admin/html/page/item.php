<li class="item-list-li page">
	<header>
		<h2><i class="fa fa-file-text-o"></i><?=escapeHTML($PAGE['ATTR']['NAME'])?><span>#<?=$PAGE['ID']?></span></h2>
		<div><a class="brackets" href="<?=Application::getAdminURL("user/update.php?id={$USER['ID']}")?>"><?=escapeHTML($USER['ATTR']['FULLNAME'])?></a></div>
	</header>
	<blockquote cite="<?=$PAGE['URL']?>">
		<p><?=excerpt($PAGE['BODY']['HTML'])?></p>
	</blockquote>
	<footer>
		<ul>
			<li><a href="<?=$PAGE['URL']?>" target="_blank" title="<?=$Language->text('select_page')?>"><i class="fa fa-external-link"></i></a></li>
			<li><a href="<?=Application::getAdminURL("page/update.php?id={$PAGE['ID']}")?>" title="<?=$Language->text('update_page')?>"><i class="fa fa-pencil-square-o"></i></a></li>
			<li><a href="<?=Application::getAdminURL("page/delete.php?id={$PAGE['ID']}")?>" title="<?=$Language->text('delete_page')?>"><i class="fa fa-trash-o"></i></a></li>
		</ul>
	</footer>
</li>