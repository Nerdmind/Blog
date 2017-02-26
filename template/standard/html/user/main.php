<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User Main Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-user"></i><?=escapeHTML($USER['ATTR']['FULLNAME'])?></h1>
<p><em><?=$Language->template('user_main_heading_desc', [escapeHTML($USER['ATTR']['USERNAME']), $COUNT['POST'], $COUNT['PAGE']])?></em></p>

<section id="content" class="user">
	<?=$USER['BODY']['HTML']?>
</section>

<section id="site-navi">

	<?php if($USER['PREV']): ?>
		<div><a href="<?=$USER['PREV']['URL']?>" title="<?=$Language->text('prev_user')?> »<?=escapeHTML($USER['PREV']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<?php if($USER['NEXT']): ?>
		<div><a href="<?=$USER['NEXT']['URL']?>" title="<?=$Language->text('next_user')?> »<?=escapeHTML($USER['NEXT']['ATTR']['NAME'])?>«"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>

</section>

<script>
	var prevPageURL = <?php echo json_encode($USER['PREV'] ? $USER['PREV']['URL'] : FALSE); ?>;
	var nextPageURL = <?php echo json_encode($USER['NEXT'] ? $USER['NEXT']['URL'] : FALSE); ?>;

	document.addEventListener('keyup', function(event) {
		if(!event.ctrlKey && !event.shiftKey) {
			(event.keyCode === 37 && prevPageURL) && (window.location.href = prevPageURL);
			(event.keyCode === 39 && nextPageURL) && (window.location.href = nextPageURL);
		}
	}, false)
</script>