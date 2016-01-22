<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.

if(is_user_logged_in()) return; // Not necessary.
?>
<?php do_action(__NAMESPACE__.'__before_login_box'); ?>

	<div id="login-box" class="modal fade" aria-hidden="true" aria-labelledby="login-box-title" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times-circle"></i>
					</button>
					<h4 id="login-box-title" class="modal-title">
						<i class="fa fa-sign-in"></i> <?php echo __('Account Login', $theme->text_domain); ?>
					</h4>
				</div>

				<div class="modal-body">
					<?php do_action(__NAMESPACE__.'__inside_login_box_before'); ?>
					<form method="post" action="<?php echo esc_attr(site_url('/wp-login.php', 'login_post')); ?>" role="form">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr($theme->redirect_to()); ?>" />

						<div class="username form-group">
							<div class="username input-group">
								<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
								<input type="text" name="log" placeholder="<?php echo esc_attr(__('Username', $theme->text_domain)); ?>" class="form-control" />
							</div>
						</div>
						<div class="password form-group">
							<div class="password input-group">
								<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
								<input type="password" name="pwd" placeholder="<?php echo esc_attr(__('Password', $theme->text_domain)); ?>" class="form-control" />
							</div>
						</div>
						<?php do_action( 'login_form' ); ?>
						<div class="row">
							<div class="col-md-6">
								&nbsp;<label class="checkbox-inline">
									<input type="checkbox" name="rememberme" value="forever" />
									<?php echo __('Yes, remember me.', $theme->text_domain); ?>
								</label>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary width-100">
									<?php echo __('Log Me In', $theme->text_domain); ?> <i class="fa fa-check"></i>
								</button>
							</div>
						</div>
					</form>
					<?php do_action(__NAMESPACE__.'__inside_login_box_after'); ?>
				</div>

				<div class="modal-footer">
					<?php if($theme->options['navbar_login_registration_via_ajax'] && get_option('users_can_register')): ?>
						<a href="#registration-box" data-dismiss="modal" data-toggle="modal" class="pull-left">
							<i class="fa fa-user"></i> <?php echo __('Register (New User)', $theme->text_domain); ?>
						</a>
					<?php elseif(get_option('users_can_register')): ?>
						<a href="<?php echo esc_attr($theme->registration_url()); ?>" class="pull-left">
							<i class="fa fa-user"></i> <?php echo __('Register (New User)', $theme->text_domain); ?>
						</a>
					<?php else: ?>
						<a href="#" data-dismiss="modal" class="no-text-decor pull-left">
							<i class="fa fa-times-circle"></i> <?php echo __('Dismiss', $theme->text_domain); ?>
						</a>
					<?php endif; ?>
					<?php if($theme->options['navbar_login_registration_via_ajax'] && get_option('users_can_register')): ?>
						<a href="<?php echo esc_attr(wp_lostpassword_url()); ?>" target="_blank">
							<?php echo __('Lost Password?', $theme->text_domain); ?>
						</a>
					<?php else: ?>
						<a href="<?php echo esc_attr(wp_lostpassword_url()); ?>">
							<?php echo __('Lost Password?', $theme->text_domain); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_login_box'); ?>

<?php if($theme->options['navbar_login_registration_via_ajax'] && get_option('users_can_register')): ?>
	<?php require_once dirname(__FILE__).'/registration-box.php'; ?>
<?php endif; ?>