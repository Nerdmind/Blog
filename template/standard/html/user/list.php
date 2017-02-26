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
<p><?=$Language->template('user_overview_heading_desc', $PAGINATION['THIS'])?></p>

<ul class="item-list user">
	<?php foreach($LIST['USERS'] as $user): ?>
		<?php echo $user; ?>
	<?php endforeach; ?>
</ul>

<?=$PAGINATION['HTML']?>