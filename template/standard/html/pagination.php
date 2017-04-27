<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Pagination Template              [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<div id="site-navi">
	<?php if($THIS > 1): ?>
		<div><a id="prev-site" href="<?=sprintf($HREF, $THIS-1)?>"><i class="fa fa-arrow-left"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-left"></i></a></div>
	<?php endif; ?>

	<div>
		<ol>
			<?php
			for($current = 1; $current <= $LAST; ++$current) {
				$class = '';
				$href = sprintf($HREF, $current);

				if($current === $THIS) {
					$class = ' class="active"';
				}

				echo "<li{$class}><a href=\"{$href}\">{$current}</a></li>";
			}
			?>
		</ol>
	</div>

	<?php if($THIS < $LAST): ?>
		<div><a id="next-site" href="<?=sprintf($HREF, $THIS+1)?>"><i class="fa fa-arrow-right"></i></a></div>
	<?php else: ?>
		<div><a class="disabled"><i class="fa fa-arrow-right"></i></a></div>
	<?php endif; ?>
</div>