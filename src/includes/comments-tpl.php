<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<?php if(post_password_required()) return; ?>

<?php if(have_comments()): ?>

	<?php do_action(__NAMESPACE__.'__before_comments'); ?>

	<div id="comments" class="panel panel-default" role="complementary">

		<div class="panel-heading">

			<?php if(comments_open()): ?>
				<a href="#respond" class="open btn btn-success pull-right hidden-sm hidden-xs">
					<?php echo __('Comments Open (Join Discussion<em>!</em>)', $theme->text_domain); ?>
				</a>
			<?php else: // Discussion is over :-) ?>
				<button class="closed btn btn-default pull-right hidden-sm hidden-xs" disabled="disabled">
					<?php echo __('Comments Closed (Discussion Ended)', $theme->text_domain); ?>
				</button>
			<?php endif; ?>

			<h3 class="panel-title text-ellipsis">
				<i class="fa fa-comments"></i>
				<?php echo sprintf(_n('<strong>One Comment</strong> <em>on</em> "%2$s"', '<strong>%1$s Comments</strong> <em>on</em> "%2$s"', get_comments_number(),
				                      __NAMESPACE__), number_format_i18n(get_comments_number()), '<span>'.esc_html(get_the_title()).'</span>'); ?>
			</h3>

		</div>

		<div class="panel-body">

			<div class="data clearfix">
				<ul class="list media-list no-margin">
					<?php wp_list_comments(array('type'        => 'comment',
					                             'avatar_size' => (integer)$theme->options['comment_avatar_size'],
					                             'walker'      => new comwalker()));

					$max_num_comment_pages = wp_query()->max_num_comment_pages; // Preserve.

					if($theme->options['pings_display_enable'] && get_query_var('cpage') <= 1)
						wp_list_comments(array('type' => 'pings', 'page' => 1, 'per_page' => PHP_INT_MAX, 'walker' => new comwalker()));

					wp_query()->max_num_comment_pages = $max_num_comment_pages; ?>
				</ul>
			</div>

			<?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
				<hr />
				<ul class="pager no-b-margin">
					<li class="previous"><?php echo get_previous_comments_link(); ?></li>
					<li class="next"><?php echo get_next_comments_link(); ?></li>
				</ul>
			<?php endif; ?>

		</div>

	</div>

	<?php do_action(__NAMESPACE__.'__after_comments'); ?>

<?php endif; ?>

<?php if(!is_attachment() && comments_open()): ?>

	<?php do_action(__NAMESPACE__.'__before_comment_form'); ?>

	<div id="comment-form"><?php // Primary wrapper. ?>
		<div id="respond" class="comment-reply-form panel panel-primary hidden-print">

			<div class="panel-heading">
				<h3 class="panel-title font-150 text-ellipsis">

					<?php if(!get_comments_number()): ?>
						<i class="fa fa-comments pull-right"></i>
						<?php echo __('Post a Comment', $theme->text_domain); ?> <i class="fa fa-comment-o"></i>
						<span class="i-hidden-sm i-hidden-xs"><?php echo __('— you can be the first :-)', $theme->text_domain); ?></span>

					<?php else: // Use functions; these are somewhat dynamic. ?>
						<?php echo get_cancel_comment_reply_link('<i class="fa fa-times-circle pull-right"></i>'); ?>

						<span class="stand-alone-variation">
							<?php comment_form_title(__('Post a Comment', $theme->text_domain), // Comment on the article.
							                         __('Add a Reply to "%1$s"', $theme->text_domain)); ?> <i class="fa fa-comment-o"></i>
						</span>
						<span class="threaded-variation">
							<?php comment_form_title(__('Add a Reply', $theme->text_domain), // A threaded reply to someone else.
							                         __('Add a Reply to "%1$s"', $theme->text_domain)); ?> <i class="fa fa-comment-o"></i>
						</span>
					<?php endif; ?>

				</h3>
			</div>

			<div class="panel-body">
				<div class="data clearfix">

					<?php if(!is_user_logged_in() && get_option('comment_registration')): ?>
						<p class="must-log-in">
							<?php if($theme->options['navbar_login_box_enable']): ?>
								<?php echo __('You must be <a href="#login-box" title="Account Login" data-toggle="modal">logged in</a> to post a comment.', $theme->text_domain); ?>
							<?php else: ?>
								<?php echo sprintf(__('You must be <a href="%1$s" title="Account Login">logged in</a> to post a comment.', $theme->text_domain),
								                   esc_attr(wp_login_url(get_permalink()))); ?>
							<?php endif; ?>
						</p>
						<?php do_action('comment_form_must_log_in_after'); ?>
					<?php else: ?>
						<form method="post" action="<?php echo esc_attr(site_url('/wp-comments-post.php')); ?>" role="form">
							<?php do_action('comment_form_top'); ?>

							<?php if(is_user_logged_in()): ?>
								<p class="logged-in-as hidden-xs">
									<?php echo sprintf(__('Logged in as <a href="%1$s"><strong>%2$s</strong></a> — <a href="%3$s">Log out?</a>', $theme->text_domain),
									                   esc_attr(get_edit_user_link()), esc_html(wp_get_current_user()->display_name), esc_attr(wp_logout_url(get_permalink()))); ?>
								</p>
							<?php else: $current_commenter = stripslashes_deep(wp_get_current_commenter()); ?>
								<div class="author form-group">
									<div class="author input-group">
										<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
										<input type="text" name="author" value="<?php echo esc_attr($current_commenter['comment_author']); ?>" placeholder="<?php echo esc_attr(__('Name', $theme->text_domain)); ?>" class="form-control" />
									</div>
								</div>
								<div class="email form-group">
									<div class="email input-group">
										<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
										<input type="email" name="email" value="<?php echo esc_attr($current_commenter['comment_author_email']); ?>" placeholder="<?php echo esc_attr(__('Email', $theme->text_domain)); ?>" class="form-control" />
									</div>
								</div>
								<div class="url form-group">
									<div class="url input-group">
										<span class="input-group-addon"><i class="fa fa-link fa-fw"></i></span>
										<input type="url" name="url" value="<?php echo esc_attr($current_commenter['comment_author_url']); ?>" placeholder="<?php echo esc_attr(__('Website URL', $theme->text_domain)); ?>" class="form-control" />
									</div>
								</div>
							<?php endif; ?>

							<div class="comment form-group">
								<?php if($theme->options['md_enable_flavor']): // Markdown. ?>
									<div class="markdown-enabled pull-right l-margin hidden-xs">
										<a target="_blank" rel="external nofollow" data-toggle="popover"
										   href="<?php echo esc_attr($theme->options['md_syntax_url']); ?>"
										   data-theme-content-source="wrapMdSyntax" data-html="true" data-trigger="hover" data-placement="left">
											<img src="<?php echo esc_attr($theme->url('/client-s/images/md-32x20.png')); ?>" alt="Markdown" class="opacity-fade-hover" />
										</a>
									</div>
								<?php endif; ?>
								<?php if($theme->options['embedly_enable']): // Embedly? ?>
									<div class="embedly-enabled pull-right l-margin hidden-xs">
										<a target="_blank" rel="external nofollow" data-toggle="popover"
										   href="<?php echo esc_attr($theme->options['embedly_syntax_url']); ?>"
										   data-theme-content-source="wrapEmbedlySyntax" data-html="true" data-trigger="hover" data-placement="left">
											<img src="<?php echo esc_attr($theme->url('/client-s/images/em-32x20.png')); ?>" alt="Embedly®" class="opacity-fade-hover" />
										</a>
									</div>
								<?php endif; ?>
								<?php if(!$theme->options['md_enable_flavor']): // HTML is only choice. ?>
									<div class="allowed-tags pull-right hidden-xs">
										<a href="#" class="dotted-text-decor" data-toggle="popover"
										   data-theme-content-source="wrapAllowedTags" data-html="true" data-trigger="hover" data-placement="left">
											<?php echo __('allowed HTML tags', $theme->text_domain); ?>
										</a>
									</div>
								<?php endif; ?>
								<label for="comment-message">
									<?php echo __('Your message goes here...', $theme->text_domain); ?></label>
								<textarea id="comment-message" name="comment" rows="5" class="form-control" data-toggle="taboverride"></textarea>
								<?php echo apply_filters('comment_form_field_comment', ''); // For Comment Mail™ integration. ?>
							</div>

							<div class="submit form-group no-b-margin">
								<div class="row">
									<div class="col-md-6">
										<button id="comment-message-preview-button" type="button" class="btn btn-default width-100">
											<i class="fa fa-eye"></i> <?php echo __('Preview', $theme->text_domain); ?>
										</button>
									</div>
									<div class="col-md-6">
										<button type="submit" class="btn btn-default width-100">
											<i class="fa fa-check"></i> <?php echo __('Submit', $theme->text_domain); ?>
										</button>
									</div>
								</div>
							</div>

							<?php echo get_comment_id_fields(); ?>
							<?php do_action('comment_form', get_the_ID()); ?>

							<div><div id="comment-message-preview"></div></div>

						</form>
					<?php endif; ?>

				</div>
			</div>

		</div>
	</div>
	<?php do_action(__NAMESPACE__.'__after_comment_form'); ?>

<?php endif; ?>
