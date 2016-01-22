<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.

$_p = stripslashes_deep($_POST);
?>
<form id="contact-form" method="post" action="#contact-form">

	<?php if(!empty($_p['theme']['contact_form'])): ?>

		<?php if(!empty($GLOBALS[__NAMESPACE__.'__contact_form_success'])): ?>
			<div class="alert alert-success">
				<?php $_p['theme']['contact_form'] = array(); ?>
				<?php if(isset($attr['thank_you_message'])) echo (string)$attr['thank_you_message']; ?>
			</div>
		<?php elseif(!empty($GLOBALS[__NAMESPACE__.'__contact_form_errors'])): ?>
			<div class="alert alert-danger">
				<ul class="no-margin">
					<li><?php echo implode('</li><li>', $GLOBALS[__NAMESPACE__.'__contact_form_errors']); ?></li>
				</ul>
			</div>
		<?php
		else: // Failure. Report this here to avoid confusion.
			?>
			<div class="alert alert-warning">
				<?php echo __('Unknown error; mail server failure. Please try again later.', $theme->text_domain); ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
			<input type="text" name="theme[contact_form][name]" value="<?php echo esc_attr((string)@$_p['theme']['contact_form']['name']); ?>" placeholder="<?php echo esc_attr(__('Full Name', $theme->text_domain)); ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
			<input type="email" name="theme[contact_form][email]" value="<?php echo esc_attr((string)@$_p['theme']['contact_form']['email']); ?>" placeholder="<?php echo esc_attr(__('Email Address', $theme->text_domain)); ?>" class="form-control" />
		</div>
	</div>

	<div class="form-group row">
		<div class="col-md-8">
			<textarea name="theme[contact_form][message]" rows="7" class="form-control" placeholder="<?php echo esc_attr(__('Message', $theme->text_domain)); ?>"><?php echo esc_textarea((string)@$_p['theme']['contact_form']['message']); ?></textarea>
		</div>
		<div class="col-md-4">
			<div class="inline-block pull-right">
				<h5 class="text-center no-margin"><?php echo __('Enter Security Code', $theme->text_domain); ?></h5>
				<?php if(isset($attr['recaptcha_theme'], $attr['recaptcha_lang']) && $theme->options['recaptcha_public_key']): ?>
					<div data-toggle="recaptcha" data-theme="<?php echo esc_attr((string)$attr['recaptcha_theme']); ?>" data-lang="<?php echo esc_attr((string)$attr['recaptcha_lang']); ?>"></div>
				<?php else: echo '<div class="alert alert-danger"><p>'.__('Missing reCAPTCHA config. Please check theme options.', $theme->text_domain).'</p></div>'; endif; ?>
			</div>
		</div>
	</div>

	<div class="form-group no-b-margin">
		<?php if(isset($attr['from'], $attr['to'], $attr['subject'])): // These come from the `[contact_form /]` shortcode. ?>
			<input type="hidden" name="theme[contact_form][from]" value="<?php echo esc_attr($theme->xencrypt((string)$attr['from'])); ?>" />
			<input type="hidden" name="theme[contact_form][to]" value="<?php echo esc_attr($theme->xencrypt((string)$attr['to'])); ?>" />
			<input type="hidden" name="theme[contact_form][subject]" value="<?php echo esc_attr($theme->xencrypt((string)$attr['subject'])); ?>" />
		<?php endif; ?>
		<button type="submit" class="btn btn-primary width-100">
			<i class="fa fa-check"></i> <?php echo __('Submit', $theme->text_domain); ?>
		</button>
	</div>

</form>