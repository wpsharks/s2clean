<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_registration_box'); ?>

	<div id="registration-box" class="modal fade" aria-hidden="true" aria-labelledby="registration-box-title" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times-circle"></i>
					</button>
					<h4 id="registration-box-title" class="modal-title">
						<i class="fa fa-user"></i> <?php echo __('Quick Registration', $theme->text_domain); ?>
					</h4>
				</div>

				<div class="modal-body">
					<?php do_action(__NAMESPACE__.'__inside_registration_box_before'); ?>
					<form method="post" action="<?php echo esc_attr(site_url('/wp-login.php?action=register', 'login_post')); ?>" role="form">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr($theme->redirect_to()); ?>" />

						<div class="email form-group">
							<div class="email input-group">
								<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
								<input type="text" name="email" placeholder="<?php echo esc_attr(__('Email', $theme->text_domain)); ?>" class="form-control" />
							</div>
						</div>
						<div class="username form-group">
							<div class="username input-group">
								<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
								<input type="text" name="username" placeholder="<?php echo esc_attr(__('Username', $theme->text_domain)); ?>" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="password form-group">
									<div class="password input-group">
										<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
										<input type="password" name="password" placeholder="<?php echo esc_attr(__('Password', $theme->text_domain)); ?>" class="form-control" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="password2 form-group">
									<div class="password2 input-group">
										<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
										<input type="password" name="password2" placeholder="<?php echo esc_attr(__('Again to confirm...', $theme->text_domain)); ?>" class="form-control" />
									</div>
								</div>
							</div>
						</div>
						<div class="recaptcha form-group">
							<?php if($theme->options['recaptcha_public_key'] && $theme->options['navbar_login_registration_recaptcha_theme'] && $theme->options['navbar_login_registration_recaptcha_lang']): ?>
								<div data-toggle="recaptcha" data-theme="<?php echo esc_attr($theme->options['navbar_login_registration_recaptcha_theme']); ?>" data-lang="<?php echo esc_attr($theme->options['navbar_login_registration_recaptcha_lang']); ?>"></div>
							<?php else: echo '<div class="alert alert-danger"><p>'.__('Missing reCAPTCHA config. Please check theme options.', $theme->text_domain).'</p></div>'; endif; ?>
						</div>
						<div class="row">
							<div class="col-md-6">
								&nbsp;<label class="checkbox-inline">
									<input type="checkbox" name="remember" value="forever" />
									<?php echo __('Yes, remember me.', $theme->text_domain); ?>
								</label>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary width-100">
									<?php echo __('Register &amp; Log Me In', $theme->text_domain); ?> <i class="fa fa-check"></i>
								</button>
							</div>
						</div>
					</form>
					<?php do_action(__NAMESPACE__.'__inside_registration_box_after'); ?>
				</div>

			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_registration_box'); ?>