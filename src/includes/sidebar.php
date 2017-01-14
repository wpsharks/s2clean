<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.

if($theme->no_sidebar() || !is_active_sidebar('primary-rs'))
	return; // Not applicable in these cases.
?>
<?php do_action(__NAMESPACE__.'__before_sidebar'); ?>

	<div id="sidebar" class="panel panel-default hidden-sm hidden-xs hidden-print" aria-hidden="true" role="dialog">

		<div class="toggle" title="<?php echo esc_attr(__('Toggle Sidebar', $theme->text_domain)); ?>">
			<button class="btn btn-primary no-outline">
				<i class="fa fa-chevron-left"></i>
			</button>
		</div>

		<div class="widgets panel-body auto-y-overflow">
			<div class="data clearfix">
				<?php dynamic_sidebar('primary-rs'); ?>
			</div>
		</div>

	</div>

<?php do_action(__NAMESPACE__.'__after_sidebar'); ?>
