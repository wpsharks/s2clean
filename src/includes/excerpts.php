<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.

$page        = get_query_var('paged');
$total_pages = wp_query()->max_num_pages;
?>
<?php do_action(__NAMESPACE__.'__before_excerpts'); ?>

	<div id="excerpts">

		<?php if($total_pages > 1): ?>
			<div class="pagination-details y-margin clearfix hidden-xs">
				<ul class="pager pull-right no-margin">
					<li><?php echo get_previous_posts_link(__('« Prev Page', $theme->text_domain)); ?></li>
					<li><?php echo get_next_posts_link(__('Next Page »', $theme->text_domain)); ?></li>
				</ul>
				<div class="current-page-info t-margin-sm">
					<span class="label label-default font-110">
						<?php echo sprintf(__('Page %1$s of %2$s', $theme->text_domain),
							(($page) ? $page : 1), $total_pages); ?>
					</span>
				</div>
			</div>
		<?php endif; ?>

		<?php while(have_posts()): the_post(); ?>
			<div class="<?php echo esc_attr(implode(' ', get_post_class('panel panel-'.((is_super_sticky()) ? 'primary' : 'default')))); ?>" role="contentinfo">

				<div class="panel-heading">

					<?php if(get_post_type() === 'post'): ?>
						<?php echo $theme->calendar_date(array('class' => 'pull-right')); ?>
					<?php endif; ?>

					<?php if(comments_open() || get_comments_number()): ?>
						<div class="comments pull-right hidden-xs">
							<a href="<?php echo esc_attr(get_comments_link()); ?>" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="left"
							   title="<?php echo esc_attr((comments_open()) ? ((get_comments_number()) ? __('Join discussion!', $theme->text_domain) : __('You can be the first!', $theme->text_domain)) : __('Read discussion.', $theme->text_domain)); ?>">
								<i class="fa fa-comments"></i> <?php echo __('Comments', $theme->text_domain); ?> <span class="badge"><?php echo esc_html(get_comments_number()); ?></span>
							</a>
						</div>
					<?php endif; ?>

					<h3 class="panel-title font-150">
						<i class="fa <?php echo esc_attr($theme->format_icon_class()); ?>"></i>
						<a href="<?php echo esc_attr(get_permalink()); ?>" rel="bookmark" class="permalink">
							<?php echo $theme->title(); ?>
						</a>
					</h3>

				</div>

				<div class="panel-body">

					<div class="data clearfix">
						<?php if(!is_search() && has_post_thumbnail() && !post_password_required()): ?>
							<a href="<?php echo esc_attr(get_permalink()); ?>" rel="bookmark" class="thumbnail perspective-box-shadow pull-left r-margin b-margin-sm">
								<?php echo get_the_post_thumbnail(NULL, 'post-thumbnail', array('class' => 'img-rounded')); ?>
							</a>
						<?php endif; ?>
						<?php echo $theme->excerpt(); ?>
					</div>

					<div class="read-more t-margin pull-right hidden-print">
						<?php echo $theme->read_more(); ?>
					</div>

					<div class="meta t-margin">
						<i class="fa fa-user"></i>
						<em><?php echo (get_post_type() === 'page')
								? __('Page created by:', $theme->text_domain)
								: __('Posted by:', $theme->text_domain); ?></em>
						<a href="<?php echo esc_attr(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"><?php echo esc_html(get_the_author()); ?></a>
						<?php echo $theme->taxonomies(array('before' => '<span class="spacer"></span>')); ?>
					</div>

				</div>

			</div>
		<?php endwhile; ?>

		<?php if($total_pages > 1): ?>
			<div class="pagination-details y-margin clearfix">
				<ul class="pager pull-right no-margin">
					<li><?php echo get_previous_posts_link(__('« Prev Page', $theme->text_domain)); ?></li>
					<li><?php echo get_next_posts_link(__('Next Page »', $theme->text_domain)); ?></li>
				</ul>
				<div class="current-page-info t-margin-sm hidden-xs">
					<span class="label label-default font-110">
						<?php echo sprintf(__('Page %1$s of %2$s', $theme->text_domain),
							(($page) ? $page : 1), $total_pages); ?>
					</span>
				</div>
			</div>
		<?php endif; ?>

	</div>

<?php do_action(__NAMESPACE__.'__after_excerpts'); ?>
