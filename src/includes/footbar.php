<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.

if($theme->no_footbar() || !is_active_sidebar('primary-fb'))
	return; // Not applicable in these cases.
?>
<?php do_action(__NAMESPACE__.'__before_footbar'); ?>

	<div id="footbar" class="well <?php echo esc_attr('size-col-md-'.$theme->options['footbar_col_size']); ?> hidden-print" role="menubar">

		<div class="widgets container<?php echo ($theme->is_fluid()) ? '-fluid' : ''; ?>">
			<div class="data row clearfix">
				<?php dynamic_sidebar('primary-fb'); ?>
			</div>
		</div>

	</div>

<?php do_action(__NAMESPACE__.'__after_footbar'); ?>
