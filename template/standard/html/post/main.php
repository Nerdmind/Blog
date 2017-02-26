<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Post Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$user = "<a href=\"{$USER['URL']}\" title=\"alias »{$USER['ATTR']['USERNAME']}«\">{$USER['ATTR']['FULLNAME']}</a>";
$time = "<time datetime=\"{$POST['ATTR']['TIME_INSERT']}\" title=\"".parseDatetime($POST['ATTR']['TIME_INSERT'], '[W]')."\">".parseDatetime($POST['ATTR']['TIME_INSERT'], $Language->template('date_format'))."</time>";
?>
<h1><i class="fa fa-newspaper-o"></i><?=escapeHTML($POST['ATTR']['NAME'])?></h1>
<p><?=$Language->template('post_main_heading_desc', [$user, $time])?></p>

<section id="content" class="post">
	<?=$POST['BODY']['HTML']?>
</section>

<section id="site-navi">

	<?php if($POST['PREV']): ?>
		<div><a href="<?=$POST['PREV']['URL']?>" title="<?=$Language->text('prev_post')?> »<?=escapeHTML($POST['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($POST['NEXT']): ?>
		<div><a href="<?=$POST['NEXT']['URL']?>" title="<?=$Language->text('next_post')?> »<?=escapeHTML($POST['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>

<script>
	var prevPageURL = <?php echo json_encode($POST['PREV'] ? $POST['PREV']['URL'] : FALSE); ?>;
	var nextPageURL = <?php echo json_encode($POST['NEXT'] ? $POST['NEXT']['URL'] : FALSE); ?>;

	document.addEventListener('keyup', function(event) {
		if(!event.ctrlKey && !event.shiftKey) {
			(event.keyCode === 37 && prevPageURL) && (window.location.href = prevPageURL);
			(event.keyCode === 39 && nextPageURL) && (window.location.href = nextPageURL);
		}
	}, false)
</script>