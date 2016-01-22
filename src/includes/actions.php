<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	class actions // Action handlers.
	{
		/**
		 * @var theme Theme instance.
		 */
		protected $theme; // Set by constructor.

		public function __construct()
		{
			$this->theme = theme();

			if(empty($_REQUEST['theme'])) return;
			foreach((array)$_REQUEST['theme'] as $action => $args)
				if(method_exists($this, $action)) $this->{$action}($args);
		}

		protected function save_options($args)
		{
			if(!current_user_can('edit_theme_options'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			if(!empty($_FILES[__NAMESPACE__]['tmp_name']['import_options']))
			{
				$import_file_contents = // This should be a JSON file.
					file_get_contents($_FILES[__NAMESPACE__]['tmp_name']['import_options']);
				unlink($_FILES[__NAMESPACE__]['tmp_name']['import_options']);

				$args = wp_slash(json_decode($import_file_contents, TRUE)); // As new options.
				unset($args['crons_setup']); // Unset; CANNOT be imported (installation-specific).
			}
			$args                 = array_map('trim', stripslashes_deep((array)$args));
			$this->theme->options = array_merge($this->theme->default_options, $args);

			if(!trim($this->theme->options['cache_dir'], '\\/'." \t\n\r\0\x0B"))
				$this->theme->options['cache_dir'] = $this->theme->default_options['cache_dir'];

			update_option(__NAMESPACE__.'_options', $this->theme->options);

			$redirect_to = self_admin_url('/admin.php'); // Redirect preparations.
			$query_args  = array('page' => __NAMESPACE__, __NAMESPACE__.'__updated' => '1');
			$redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

			wp_redirect($redirect_to).exit(); // All done :-)
		}

		protected function restore_default_options($args)
		{
			if(!current_user_can('edit_theme_options'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			delete_option(__NAMESPACE__.'_options');
			$this->theme->options = $this->theme->default_options;

			$redirect_to = self_admin_url('/admin.php'); // Redirect preparations.
			$query_args  = array('page' => __NAMESPACE__, __NAMESPACE__.'__restored' => '1');
			$redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

			wp_redirect($redirect_to).exit(); // All done :-)
		}

		protected function export_options($args)
		{
			if(!current_user_can('edit_theme_options'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			ini_set('zlib.output_compression', FALSE);
			if(function_exists('apache_setenv'))
				apache_setenv('no-gzip', '1');

			header('Accept-Ranges: none');
			header('Content-Encoding: none');
			header('Content-Type: application/json; charset=UTF-8');
			header('Content-Length: '.strlen($export = json_encode($this->theme->options)));
			header('Content-Disposition: attachment; filename="'.__NAMESPACE__.'-options.json"');

			exit($export); // Deliver the export file.
		}

		protected function update_sync($args)
		{
			if(!current_user_can('update_themes'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			$args = array_map('trim', stripslashes_deep((array)$args));

			if(empty($args['username'])) $args['username'] = $this->theme->options['update_sync_username'];
			if(empty($args['password'])) $args['password'] = $this->theme->options['update_sync_password'];
			if(!isset($args['version_check'])) $args['version_check'] = $this->theme->options['update_sync_version_check'];

			$update_sync_url       = 'https://www.websharks-inc.com/products/update-sync.php';
			$update_sync_post_vars = array('data' => array('slug'     => str_replace('_', '-', __NAMESPACE__).'-pro', 'version' => 'latest-stable',
			                                               'username' => $args['username'], 'password' => $args['password']));

			$update_sync_response = wp_remote_post($update_sync_url, array('body' => $update_sync_post_vars));
			$update_sync_response = json_decode(wp_remote_retrieve_body($update_sync_response), TRUE);

			if(!is_array($update_sync_response) || !empty($update_sync_response['error'])
			   || empty($update_sync_response['version']) || empty($update_sync_response['zip'])
			) // Report errors in all of these cases. Redirect errors to `update-sync` page.
			{
				if(!empty($update_sync_response['error'])) $error = $update_sync_response['error'];
				else $error = __('Unknown error. Please wait 15 minutes and try again.', $this->theme->text_domain);

				$redirect_to = self_admin_url('/admin.php'); // Redirect preparations.
				$query_args  = array('page' => __NAMESPACE__.'-update-sync', __NAMESPACE__.'__error' => $error);
				$redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

				wp_redirect($redirect_to).exit(); // Done; with errors.
			}
			$this->theme->options['update_sync_username']           = $args['username']; // Update username.
			$this->theme->options['update_sync_password']           = $args['password']; // Update password.
			$this->theme->options['update_sync_version_check']      = $args['version_check']; // Check version?
			$this->theme->options['last_update_sync_version_check'] = time(); // Update this; we just checked :-)
			update_option(__NAMESPACE__.'_options', $this->theme->options); // Save each of these options.

			$notices = (is_array($notices = get_option(__NAMESPACE__.'_notices'))) ? $notices : array();
			unset($notices['persistent-update-sync-version']); // Dismiss this notice.
			update_option(__NAMESPACE__.'_notices', $notices); // Update notices.

			$redirect_to = self_admin_url('/update.php'); // Runs update routines in WordPress.
			$query_args  = array('action'                         => 'upgrade-theme', 'theme' => str_replace('_', '-', __NAMESPACE__),
			                     '_wpnonce'                       => wp_create_nonce('upgrade-theme_'.str_replace('_', '-', __NAMESPACE__)),
			                     __NAMESPACE__.'__update_version' => $update_sync_response['version'],
			                     __NAMESPACE__.'__update_zip'     => base64_encode($update_sync_response['zip']));
			$redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

			wp_redirect($redirect_to).exit(); // All done :-)
		}

		protected function dismiss_notice($args)
		{
			if(!current_user_can('edit_theme_options'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			$args = array_map('trim', stripslashes_deep((array)$args));
			if(empty($args['key'])) return; // Nothing to dismiss.

			$notices = (is_array($notices = get_option(__NAMESPACE__.'_notices'))) ? $notices : array();
			unset($notices[$args['key']]); // Dismiss this notice.
			update_option(__NAMESPACE__.'_notices', $notices);

			wp_redirect(remove_query_arg('theme')).exit();
		}

		protected function dismiss_error($args)
		{
			if(!current_user_can('edit_theme_options'))
				return; // Nothing to do.

			if(empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce']))
				return; // Unauthenticated POST data.

			nocache_headers(); // Disallow browser caching.

			$args = array_map('trim', stripslashes_deep((array)$args));
			if(empty($args['key'])) return; // Nothing to dismiss.

			$errors = (is_array($errors = get_option(__NAMESPACE__.'_errors'))) ? $errors : array();
			unset($errors[$args['key']]); // Dismiss this error.
			update_option(__NAMESPACE__.'_errors', $errors);

			wp_redirect(remove_query_arg('theme')).exit();
		}

		protected function ajax_registration($args)
		{
			$_this = $this; // This reference.
			// On `template_redirect` so #navbar functions properly.
			add_action('template_redirect', function () use ($_this, $args)
			{
				$_this->_ajax_registration($args);
			});
		}

		protected function _ajax_registration($args)
		{
			$errors    = array(); // Initialize errors.
			$orig_args = $args; // We'll pass these for login below.
			$args      = array_map('trim', stripslashes_deep((array)$args));

			nocache_headers(); // Disallow browser caching.
			header('Content-Type: application/json; charset=UTF-8');

			if(!empty($_SERVER['HTTP_ORIGIN'])) // Allow SSL registrations from non-SSL origins.
				if(preg_match('/^https?\:\/\/'.preg_quote((string)$_SERVER['HTTP_HOST'], '/').'(?:[\/?]|$)/i', (string)$_SERVER['HTTP_ORIGIN']))
					header('Access-Control-Allow-Origin: '.(string)$_SERVER['HTTP_ORIGIN']).
					header('Access-Control-Allow-Credentials: true');

			if(empty($args['email'])) // No email address?
				$errors[] = __('Missing Email Address, please try again.', $this->theme->text_domain);

			if(empty($args['username'])) // Totally empty?
				$errors[] = __('Missing Username, please try again.', $this->theme->text_domain);

			if(empty($args['password'])) // Totally empty?
				$errors[] = __('Missing Password, please try again.', $this->theme->text_domain);

			if(empty($args['password2'])) // Totally empty?
				$errors[] = __('Missing Password confirmation, please try again.', $this->theme->text_domain);

			if(!empty($args['password']) && !empty($args['password2']) && $args['password'] !== $args['password2'])
				$errors[] = __('Mismatch on Password fields, please try again.', $this->theme->text_domain);

			if(!$this->theme->options['recaptcha_private_key'])
				$errors[] = __('Missing reCAPTCHA private key in theme options.', $this->theme->text_domain);

			else if(empty($args['recaptcha_challenge_field']) || empty($args['recaptcha_response_field']))
				$errors[] = __('Missing reCAPTCHA security code.', $this->theme->text_domain);

			else if(is_wp_error($recaptcha_response = wp_remote_post(
					'https://www.google.com/recaptcha/api/verify', // Verify.
					array('body' => array('remoteip'   => $this->theme->current_ip(),
					                      'challenge'  => $args['recaptcha_challenge_field'],
					                      'response'   => $args['recaptcha_response_field'],
					                      'privatekey' => $this->theme->options['recaptcha_private_key']
					)))) || stripos(wp_remote_retrieve_body($recaptcha_response), 'true') !== 0
			) $errors[] = __('Invalid reCAPTCHA security code.', $this->theme->text_domain);

			/** @var $user_id integer|\WP_Error For IDEs; so `$user_id` is as it should be. */
			if(!$errors && is_wp_error($user_id = wp_create_user($args['username'], $args['password'], $args['email'])))
				$errors[] = $user_id->get_error_message();

			if(empty($user_id) || $errors) // Errors via AJAX (stop here).
				exit(json_encode(array('errors' => $errors, 'user' => NULL)));

			$this->_ajax_login($orig_args); // Log them into the site now.
		}

		protected function ajax_login($args)
		{
			$_this = $this; // This reference.
			// On `template_redirect` so #navbar functions properly.
			add_action('template_redirect', function () use ($_this, $args)
			{
				$_this->_ajax_login($args);
			});
		}

		protected function _ajax_login($args)
		{
			$errors = array(); // Initialize errors.
			$args   = array_map('trim', stripslashes_deep((array)$args));

			nocache_headers(); // Disallow browser caching.
			header('Content-Type: application/json; charset=UTF-8');

			if(!empty($_SERVER['HTTP_ORIGIN'])) // Allow SSL logins from non-SSL origins.
				if(preg_match('/^https?\:\/\/'.preg_quote((string)$_SERVER['HTTP_HOST'], '/').'(?:[\/?]|$)/i', (string)$_SERVER['HTTP_ORIGIN']))
					header('Access-Control-Allow-Origin: '.(string)$_SERVER['HTTP_ORIGIN']).
					header('Access-Control-Allow-Credentials: true');

			if(empty($args['username'])) // Totally empty?
				$errors[] = __('Missing Username, please try again.', $this->theme->text_domain);

			if(empty($args['password'])) // Totally empty?
				$errors[] = __('Missing Password, please try again.', $this->theme->text_domain);

			if(!$errors && is_wp_error($user = wp_authenticate($args['username'], $args['password'])))
				$errors[] = $user->get_error_message();

			if(empty($user) || $errors) // Errors via AJAX (stop here).
				exit(json_encode(array('errors' => $errors, 'user' => NULL)));

			$user_basics = (object) // Converts into a \stdClass instance.
			array('ID'           => $user->ID, 'user_login' => $user->user_login,
			      'first_name'   => $user->first_name, 'last_name' => $user->last_name,
			      'display_name' => $user->display_name, 'user_email' => $user->user_email);

			$secure_cookie = ''; // Based on `is_ssl()` (default behavior).
			if(!empty($_SERVER['HTTP_ORIGIN'])) // Be specific in this case.
				$secure_cookie = (stripos((string)$_SERVER['HTTP_ORIGIN'], 'https') === 0);

			wp_set_auth_cookie($user->ID, !empty($args['remember']), $secure_cookie);
			wp_set_current_user($user->ID); // So navbar considers the user.

			ob_start(); // Rebuild the navbar.
			include dirname(__FILE__).'/navbar.php';
			$navbar = ob_get_clean(); // Collect navbar buffer.

			exit(json_encode(array('errors' => array(), 'user' => $user_basics, 'navbar' => $navbar)));
		}

		protected function ajax_comment_preview($args)
		{
			$args = array_map('trim', stripslashes_deep((array)$args));

			nocache_headers(); // Disallow browser caching.
			header('Content-Type: text/plain; charset=UTF-8');

			$user = wp_get_current_user();

			$comment = new \stdClass; // Comment object class.
			// See: http://codex.wordpress.org/Function_Reference/get_comment

			$comment->comment_ID      = 0; // Not a real comment ID.
			$comment->comment_post_ID = !empty($args['singular_id'])
				? (integer)$args['singular_id'] : 0;

			if($comment->comment_post_ID) // Setup post data if possible.
				$GLOBALS['post'] = get_post($comment->comment_post_ID);

			$comment->comment_author       = $user->ID ? $user->display_name : 'Anonymous';
			$comment->comment_author_email = $user->ID ? $user->user_email : 'preview@example.com';
			$comment->comment_author_url   = $user->ID ? $user->user_url : 'http://preview.example.com';
			$comment->comment_author_IP    = $this->theme->current_ip(); // Current IP address.

			$comment->comment_date     = current_time('mysql'); // Local time.
			$comment->comment_date_gmt = current_time('mysql', TRUE); // UTC time.

			$comment->comment_content = !empty($args['message']) ? wp_slash($args['message']) : '';
			$comment->comment_content = apply_filters('pre_comment_content', $comment->comment_content);
			$comment->comment_content = stripslashes($comment->comment_content);

			$comment->comment_karma    = 0; // No karma yet.
			$comment->comment_approved = '1'; // Approved during preview.
			$comment->comment_agent    = (string)$_SERVER['HTTP_USER_AGENT'];
			$comment->comment_type     = ''; // No special type.
			$comment->comment_parent   = 0; // No parent in preview.
			$comment->user_id          = $user->ID ? $user->ID : 0;

			$comment_text = $comment->comment_content; // Like `comment_text()`.
			$comment_text = apply_filters('get_comment_text', $comment_text, $comment);
			$comment_text = apply_filters('comment_text', $comment_text, $comment);

			$preview = // Construct HTML preview (panel layout).
				'<div class="panel panel-info t-margin no-b-margin">'."\n".

				'  <div class="panel-heading">'."\n".

				'     <a href="#" id="comment-message-preview-close" class="no-text-decor"'.
				'        title="'.esc_attr(__('Close Message Preview', $this->theme->text_domain)).'">'.
				'        <i class="fa fa-eye-slash pull-right"></i></a>'."\n".

				'     <h3 class="panel-title font-90">'."\n".
				'        '.__('Message Preview', $this->theme->text_domain)."\n".
				'     </h3>'."\n".

				'  </div>'."\n".

				'  <div class="panel-body no-x-overflow">'."\n".

				'     <div class="data clearfix">'."\n".
				'        '.$comment_text."\n".
				'     </div>'."\n".

				'  </div>'."\n".

				'</div>';
			exit(apply_filters(__METHOD__, $preview, get_defined_vars()));
		}

		protected function contact_form($args)
		{
			$args = array_map('trim', stripslashes_deep((array)$args));

			nocache_headers(); // Disallow browser caching.

			$errors = array(); // Initialize errors.

			if(empty($args['name']))
				$errors[] = __('Missing name.', $this->theme->text_domain);

			if(empty($args['email']))
				$errors[] = __('Missing email address.', $this->theme->text_domain);

			else if(!is_email($args['email']))
				$errors[] = __('Invalid email address.', $this->theme->text_domain);

			if(empty($args['message']))
				$errors[] = __('Missing message.', $this->theme->text_domain);

			if(empty($args['from']) || !($args['from'] = $this->theme->xdecrypt($args['from'])))
				$errors[] = __('Missing "from" address in theme options.', $this->theme->text_domain);

			if(empty($args['to']) || !($args['to'] = $this->theme->xdecrypt($args['to'])))
				$errors[] = __('Missing "to" address in theme options.', $this->theme->text_domain);

			if(empty($args['subject']) || !($args['subject'] = $this->theme->xdecrypt($args['subject'])))
				$errors[] = __('Missing "subject" in theme options.', $this->theme->text_domain);

			if(!$this->theme->options['recaptcha_private_key'])
				$errors[] = __('Missing reCAPTCHA private key in theme options.', $this->theme->text_domain);

			else if(empty($_REQUEST['recaptcha_challenge_field']) || empty($_REQUEST['recaptcha_response_field']))
				$errors[] = __('Missing reCAPTCHA security code.', $this->theme->text_domain);

			else if(is_wp_error($recaptcha_response = wp_remote_post(
					'https://www.google.com/recaptcha/api/verify', // Verify security code.
					array('body' => array('remoteip'   => $this->theme->current_ip(), // Current IP address.
					                      'challenge'  => stripslashes((string)$_REQUEST['recaptcha_challenge_field']),
					                      'response'   => stripslashes((string)$_REQUEST['recaptcha_response_field']),
					                      'privatekey' => $this->theme->options['recaptcha_private_key']
					)))) || stripos(wp_remote_retrieve_body($recaptcha_response), 'true') !== 0
			) $errors[] = __('Invalid reCAPTCHA security code.', $this->theme->text_domain);

			$success                                         = FALSE;
			$GLOBALS[__NAMESPACE__.'__contact_form_success'] =& $success;
			$GLOBALS[__NAMESPACE__.'__contact_form_errors']  =& $errors;

			if(!$errors) $success // True if the email was sent successfully; to the MTA at least.
				= wp_mail($args['to'], $args['subject'], esc_html($args['message']),
				          'From: '.$args['from']."\r\n".'Reply-To: "'.esc_html($args['name']).'" <'.esc_html($args['email']).'>'."\r\n".
				          'Content-Type: text/plain; charset=UTF-8');
		}
	}

	new actions(); // Initialize/handle actions.
}