<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_content'); ?>

	<div id="content" class="error e-404 panel panel-default" role="article">
		<div class="panel-body">
			<div class="data entry clearfix">
				<img src="<?php echo esc_attr(set_url_scheme($theme->options['404_img_url'])); ?>"
				     alt="<?php echo esc_attr(__('404 — Page Not Found', $theme->text_domain)); ?>"
				     class="img-responsive center" />
			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_content'); ?>
