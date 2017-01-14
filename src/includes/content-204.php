<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_content'); ?>

	<div id="content" class="error e-204 panel panel-default" role="article">
		<div class="panel-body">
			<div class="data entry clearfix">
				<?php if(is_search()): ?>
					<img src="<?php echo esc_attr(set_url_scheme($theme->options['204_no_results_img_url'])); ?>"
					     alt="<?php echo esc_attr(__('204 — No Search Results', $theme->text_domain)); ?>"
					     class="img-responsive center" />
				<?php elseif(is_archive()): ?>
					<img src="<?php echo esc_attr(set_url_scheme($theme->options['204_archive_empty_img_url'])); ?>"
					     alt="<?php echo esc_attr(__('204 — Archive Empty', $theme->text_domain)); ?>"
					     class="img-responsive center" />
				<?php else: // Treat it like a 404 error. ?>
					<img src="<?php echo esc_attr(set_url_scheme($theme->options['404_img_url'])); ?>"
					     alt="<?php echo esc_attr(__('404 — Page Not Found', $theme->text_domain)); ?>"
					     class="img-responsive center" />
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_content'); ?>
