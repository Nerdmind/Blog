<h1><i class="fa fa-dashboard"></i><?=$Language->text('overview_dashboard_text')?></h1>
<p><?=$Language->text('overview_dashboard_desc')?></p>

<h2><i class="fa fa-newspaper-o"></i><?=$Language->text('last_post')?></h2>
<p>
	<strong><?=$Language->text('posts')?>:</strong> <?=$COUNT['POST']?>
	| <a href="<?=Application::getAdminURL('post/')?>"><?=$Language->text('post_overview')?></a>
	| <a href="<?=Application::getAdminURL('post/insert.php')?>"><?=$Language->text('insert')?></a>
	| <a href="<?=Application::getAdminURL('post/search.php')?>"><?=$Language->text('search')?></a>
</p>
<?php if(!empty($LAST['POST'])): ?>
	<div class="item-container post">
		<?=$LAST['POST']?>
	</div>
<?php else: ?>
	<p><em><?=$Language->text('home_no_posts')?></em></p>
<?php endif; ?>

<h2><i class="fa fa-file-text-o"></i><?=$Language->text('last_page')?></h2>
<p>
	<strong><?=$Language->text('pages')?>:</strong> <?=$COUNT['PAGE']?>
	| <a href="<?=Application::getAdminURL('page/')?>"><?=$Language->text('page_overview')?></a>
	| <a href="<?=Application::getAdminURL('page/insert.php')?>"><?=$Language->text('insert')?></a>
	| <a href="<?=Application::getAdminURL('page/search.php')?>"><?=$Language->text('search')?></a>
</p>

<?php if(!empty($LAST['PAGE'])): ?>
	<div class="item-container page">
		<?=$LAST['PAGE']?>
	</div>
<?php else: ?>
	<p><em><?=$Language->text('home_no_pages')?></em></p>
<?php endif; ?>

<h2><i class="fa fa-user"></i><?=$Language->text('last_user')?></h2>
<p>
	<strong><?=$Language->text('users')?>:</strong> <?=$COUNT['USER']?>
	| <a href="<?=Application::getAdminURL('user/')?>"><?=$Language->text('user_overview')?></a>
	| <a href="<?=Application::getAdminURL('user/insert.php')?>"><?=$Language->text('insert')?></a>
</p>

<?php if(!empty($LAST['USER'])): ?>
	<div class="item-container user">
		<?=$LAST['USER']?>
	</div>
<?php else: ?>
	<p><em><?=$Language->text('home_no_users')?></em></p>
<?php endif; ?>