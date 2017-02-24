<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Pagination Template              [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<section id="site-navi">
	<?php if($THIS > 1): ?>
		<div><a href="<?=sprintf($HREF, $THIS-1)?>"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<section>
		<div>
			<ol>
				<?php for($currentItem = 1; $currentItem <= $LAST; ++$currentItem): ?>
					<?php
					$href = sprintf($HREF, $currentItem);
					$class = NULL;
					$currentItemHTML = $currentItem;
					if($currentItem === $THIS) {
						$class = ' class="active"';
					}

					echo '<li'.$class.'><a href="'.$href.'">'.$currentItemHTML.'</a></li>';
					?>

				<?php endfor; ?>
			</ol>
		</div>
	</section>

	<?php if($THIS < $LAST): ?>
		<div><a href="<?=sprintf($HREF, $THIS+1)?>"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>
</section>

<script>
	var prevPageURL = <?php echo json_encode($THIS > 1 ? sprintf($HREF, $THIS-1) : FALSE); ?>;
	var nextPageURL = <?php echo json_encode($THIS < $LAST ? sprintf($HREF, $THIS+1) : FALSE); ?>;

	document.addEventListener('keyup', function(event) {
		if(!event.ctrlKey && !event.shiftKey) {
			(event.keyCode === 37 && prevPageURL) && (window.location.href = prevPageURL);
			(event.keyCode === 39 && nextPageURL) && (window.location.href = nextPageURL);
		}
	}, false)
</script>