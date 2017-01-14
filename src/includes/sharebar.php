<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.

if($theme->no_sharebar()) return; // Not applicable in this case.
?>
<?php do_action(__NAMESPACE__.'__before_sharebar'); ?>

	<div id="sharebar" class="hidden-sm hidden-xs hidden-print" role="menubar">

		<?php echo $theme->shortcodes(array('heading'  => '', 'vertical' => TRUE, 'labels' => FALSE, 'tooltips' => 'right',
		                                    'services' => $theme->options['sharebar_services']), '', 'share_btn_icons'); ?>

	</div>

<?php do_action(__NAMESPACE__.'__after_sharebar'); ?>
