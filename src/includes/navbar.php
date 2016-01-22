<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.

if($theme->no_navbar()) return; // Not applicable in this case.
?>
<?php do_action(__NAMESPACE__.'__before_navbar'); ?>

	<nav id="navbar" class="navbar navbar-<?php echo esc_attr($theme->options['navbar_class']); ?> navbar-fixed-top hidden-print" role="navigation">
		<div class="container<?php echo ($theme->is_fluid()) ? '-fluid' : ''; ?>">

			<div id="navbar-header" class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menus">
					<span class="sr-only"><?php echo __('Toggle Navigation', $theme->text_domain); ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="<?php echo esc_attr(home_url('/')); ?>" title="<?php echo esc_attr($theme->seo_site()); ?>" class="navbar-brand">
					<img src="<?php echo esc_attr(set_url_scheme($theme->options['navbar_brand_img_url'])); // Based on theme options. ?>"
					     style="width:<?php echo esc_attr($theme->options['navbar_brand_img_width']); // Based on theme options. ?>;"
					     alt="<?php echo esc_attr($theme->seo_site()); ?>" title="<?php echo esc_attr($theme->seo_site()); ?>" />
				</a>
			</div>

			<div id="navbar-menus" class="collapse navbar-collapse">

				<?php if(has_nav_menu('primary-ls')) // Only if we DO have this menu.
					wp_nav_menu(array('fallback_cb'    => FALSE, 'container' => FALSE, 'depth' => 0,
					                  'theme_location' => 'primary-ls', 'menu_class' => 'nav navbar-nav',
					                  'walker'         => new navwalker())); ?>

				<?php if($theme->options['navbar_login_box_enable']): ?>
					<ul class="nav navbar-nav navbar-right">
						<?php if(is_user_logged_in()): ?>
							<li class="logout">
								<a href="<?php echo esc_attr(wp_logout_url($theme->redirect_to(TRUE))); ?>"
								   title="<?php echo esc_attr(sprintf(__('%1$s [Logout]', $theme->text_domain), wp_get_current_user()->display_name)); ?>">
									<i class="fa fa-sign-out"></i> <?php echo __('Logout', $theme->text_domain); ?>
								</a>
							</li>
						<?php else: // Login/Registration. ?>
							<li class="login">
								<a href="#login-box" title="<?php echo esc_attr(__('Account Login', $theme->text_domain)); ?>" data-toggle="modal">
									<i class="fa fa-sign-in"></i> <?php echo get_option('users_can_register') ? __('Login/Register', $theme->text_domain) : __('Login', $theme->text_domain); ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>

				<?php if(has_nav_menu('primary-rs')) // Only if we DO have this menu.
					wp_nav_menu(array('fallback_cb'    => FALSE, 'container' => FALSE, 'depth' => 0,
					                  'theme_location' => 'primary-rs', 'menu_class' => 'nav navbar-nav navbar-right',
					                  'walker'         => new navwalker())); ?>

				<?php if($theme->options['navbar_search_box_enable'] && (!is_ssl() || apply_filters(__NAMESPACE__.'__navbar_search_box_over_ssl', FALSE))): ?>
					<div class="col-md-3 navbar-right">
						<form method="get" action="<?php echo esc_attr(home_url('/', is_ssl() ? 'https' : NULL)); ?>" class="navbar-form" role="search">
							<div class="input-group">
								<input type="text" id="s" name="s" value="<?php echo esc_attr(get_search_query(FALSE)); ?>" class="form-control"
								       placeholder="<?php echo __('Search...', $theme->text_domain); ?>" />
								<span class="input-group-btn">
			                  <button type="submit" class="btn btn-default">
				                  <i class="fa fa-search"></i>
			                  </button>
			               </span>
							</div>
						</form>
					</div>
				<?php endif; ?>

			</div>

		</div>
	</nav>

<?php do_action(__NAMESPACE__.'__after_navbar'); ?>

<?php if($theme->options['navbar_login_box_enable']): ?>
	<?php require_once dirname(__FILE__).'/login-box.php'; ?>
<?php endif; ?>