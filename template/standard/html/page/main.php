<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Page Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$user = "<a href=\"{$USER['URL']}\" title=\"alias »{$USER['ATTR']['USERNAME']}«\">{$USER['ATTR']['FULLNAME']}</a>";
$time = "<time datetime=\"{$PAGE['ATTR']['TIME_INSERT']}\" title=\"".parseDatetime($PAGE['ATTR']['TIME_INSERT'], '[W]')."\">".parseDatetime($PAGE['ATTR']['TIME_INSERT'], $Language->template('date_format'))."</time>";
?>
<h1><i class="fa fa-file-text-o"></i><?=escapeHTML($PAGE['ATTR']['NAME'])?></h1>
<p><?=$Language->template('page_main_heading_desc', [$user, $time])?></p>

<section id="content" class="page">
	<?=$PAGE['BODY']['HTML']?>
</section>

<section id="site-navi">

	<?php if($PAGE['PREV']): ?>
		<div><a href="<?=$PAGE['PREV']['URL']?>" title="<?=$Language->text('prev_page')?> »<?=escapeHTML($PAGE['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($PAGE['NEXT']): ?>
		<div><a href="<?=$PAGE['NEXT']['URL']?>" title="<?=$Language->text('next_page')?> »<?=escapeHTML($PAGE['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>

<script>
	var prevPageURL = <?php echo json_encode($PAGE['PREV'] ? $PAGE['PREV']['URL'] : FALSE); ?>;
	var nextPageURL = <?php echo json_encode($PAGE['NEXT'] ? $PAGE['NEXT']['URL'] : FALSE); ?>;

	document.addEventListener('keyup', function(event) {
		if(!event.ctrlKey && !event.shiftKey) {
			(event.keyCode === 37 && prevPageURL) && (window.location.href = prevPageURL);
			(event.keyCode === 39 && nextPageURL) && (window.location.href = nextPageURL);
		}
	}, false)
</script>