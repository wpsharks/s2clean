<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_content'); ?>

	<div id="content" class="<?php echo esc_attr(implode(' ', get_post_class($theme->no_panel() ? 'no-panel' : 'panel panel-default'))); ?>" role="article">
		<div class="<?php echo esc_attr($theme->no_panel() ? 'no-panel-body' : 'panel-body'); ?>">

			<?php if(is_single() && !$theme->no_topper()): ?>

				<?php do_action(__NAMESPACE__.'__before_content_topper'); ?>

				<div class="topper page-header no-t-margin">

					<?php do_action(__NAMESPACE__.'__before_content_topper_inside'); ?>

					<?php if(is_sticky() || $theme->no_topper_date()): ?>
						<i class="fa <?php echo esc_attr($theme->format_icon_class()); ?> fa-5x text-shadow pull-right l-margin"></i>
					<?php else: echo $theme->calendar_date(array('class' => 'pull-right l-margin')); endif; ?>

					<?php if($theme->options['shortlinks_display_enable'] && !$theme->no_topper_shortlink()): ?>
						<?php echo $theme->shortlink_copier(array('class' => 'font-80 pull-right l-margin hidden-sm hidden-xs')); ?>
					<?php endif; ?>

					<h1 class="no-t-margin<?php echo in_array(get_post_format(), array('aside', 'status', 'link'), TRUE) ? ' hidden' : ''; ?>">
						<?php echo $theme->title(); ?>
					</h1>

					<div class="meta">
						<i class="fa fa-user"></i>
						<em><?php echo __('Posted by:', $theme->text_domain); ?></em>
						<a href="<?php echo esc_attr(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"><?php echo esc_html(get_the_author()); ?></a>
						<?php echo $theme->taxonomies(array('before' => '<span class="spacer"></span>')); ?>
					</div>

					<?php do_action(__NAMESPACE__.'__after_content_topper_inside'); ?>

				</div>

				<?php do_action(__NAMESPACE__.'__after_content_topper'); ?>

			<?php endif; ?>

			<?php do_action(__NAMESPACE__.'__becore_content_data'); ?>

			<div class="data entry clear clearfix">

				<?php do_action(__NAMESPACE__.'__becore_content_data_inside'); ?>

				<?php echo $theme->content(); ?>

				<?php do_action(__NAMESPACE__.'__after_content_data_inside'); ?>

			</div>

			<?php do_action(__NAMESPACE__.'__after_content_data'); ?>

			<?php echo $theme->link_pages(); ?>

			<?php do_action(__NAMESPACE__.'__after_content_link_pages'); ?>

		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_content'); ?>
