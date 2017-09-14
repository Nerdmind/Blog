<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: User List Template               [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
?>
<h1><i class="fa fa-user"></i><?=$Language->text('user_overview')?></h1>
<p><?=$Language->text('user_overview_heading_desc', $PAGINATION['THIS'])?></p>

<div class="item-container user">
	<?php foreach($LIST['USERS'] as $user): ?>
		<?php echo $user; ?>
	<?php endforeach; ?>
</div>

<?=$PAGINATION['HTML']?>