<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\theme'))
	{
		class theme // Base theme class.
		{
			public $name = 's2Clean'; // Product name.
			public $version = '150402'; // See: `style.css` file.
			public $text_domain = ''; // Defined by class constructor.
			public $default_options = array(); // Defined @ setup.
			public $options = array(); // Defined @ setup.

			public function __construct() // Constructor.
			{
				if(strpos(__NAMESPACE__, '\\') !== FALSE) // Sanity check.
					throw new \exception('Not a root namespace: `'.__NAMESPACE__.'`.');

				$this->text_domain = str_replace('_', '-', __NAMESPACE__);

				add_action('after_setup_theme', array($this, 'setup'));
				add_action('after_switch_theme', array($this, 'activate'));
				add_action('switch_theme', array($this, 'deactivate'));
			}

			public function setup() // Setup theme integrations.
			{
				do_action('before__'.__METHOD__, get_defined_vars());

				load_theme_textdomain($this->text_domain);

				if(!class_exists('\\'.__NAMESPACE__.'\\navwalker'))
					require_once dirname(__FILE__).'/navwalker.php';

				if(!class_exists('\\'.__NAMESPACE__.'\\comwalker'))
					require_once dirname(__FILE__).'/comwalker.php';

				$this->default_options = array(
					// Default options.

					'crons_setup'                                 => '0',
					'uninstall_on_deactivation'                   => '0',
					'nocache_headers_enable'                      => '1',
					'scripts_in_footer'                           => '1',

					'automatic_feed_links_enable'                 => '1',
					'custom_feed_link'                            => '',

					'cache_dir'                                   => __NAMESPACE__.'/cache',

					'md_cache_max_age'                            => '30 days',
					'md_enable_flavor'                            => '0', // 0, `php_markdown_extra`, `parsedown_extra`, or `custom_markdown`.
					'md_enable_line_breaks'                       => '0', // Enable hard line breaks; i.e., like GitHub Flavored Markdown?
					'md_custom_parser'                            => '', // Callable when `md_enable_flavor=custom_markdown`.
					'md_syntax_url'                               => 'http://en.wikipedia.org/wiki/Markdown',

					'default_excerpt_clip_chars'                  => '500', // Related to excerpts.
					'excerpt_read_more_label'                     => __('Continue Reading...', $this->text_domain),
					'excerpt_read_more_label_s'                   => __('Open Full Content...', $this->text_domain),

					'contact_form_from'                           => get_bloginfo('admin_email'), // Default value.
					'contact_form_to'                             => get_bloginfo('admin_email'), // Default value.
					'contact_form_subject'                        => sprintf(__('Contact Form Submission via: %1$s', $this->text_domain),
					                                                         get_bloginfo('name')),

					'shortlinks_display_enable'                   => '0', // Display short links?
					'bitly_shortlinks_enable'                     => '0', // Enable Bitly shortlinks?
					'bitly_access_token'                          => '', // Bitly API access token.

					'sharebar_enable'                             => '1', // See: http://www.addthis.com/services/list
					'sharebar_services'                           => 'facebook,twitter,google_plus,linkedin,pinterest,wordpress,tumblr,email,more',
					'addthis_publisher'                           => '', // Register @ addthis.com.

					'jquery_ui_enable'                            => '', // '' = lazy loading.

					'recaptcha_enable'                            => '', // '' = lazy loading.
					'recaptcha_public_key'                        => '', 'recaptcha_private_key' => '',

					'tabdrop_enable'                              => '', // '' = lazy loading.

					'taboverride_enable'                          => '', // '' = lazy loading.
					'taboverride_size'                            => '0', // Default `0`; tab character.

					'highlight_js_enable'                         => '', // '' = lazy loading.
					'highlight_js_theme'                          => 'ir_black', // Default theme.

					'zeroclip_enable'                             => '', // '' = lazy loading.
					'zeroclip_swf_url'                            => '//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/1.3.2/ZeroClipboard.swf',

					'embedly_enable'                              => '', // '' = lazy loading.
					'embedly_key'                                 => '', // Must register @ embed.ly.
					'embedly_syntax_url'                          => 'http://embed.ly/embed/demos/comments',

					'togetherjs_enable'                           => '', // '' = lazy loading.

					'fancybox_enable'                             => '', // '' = lazy loading.

					'seo_enable'                                  => '1', // Disable if using an SEO plugin.
					'site_seo_name'                               => '', 'site_seo_title' => '', 'site_seo_title_sep' => '|',
					'site_seo_keywords'                           => '', 'site_seo_description' => '', 'site_seo_robots' => 'index,follow',

					'open_graph_enable'                           => '1', // Disable if using a plugin for this.
					'default_open_graph_img_url'                  => 'http://api.webthumbnail.org/?width=500&height=400'.
					                                                 '&format=png&screen=1280&url='.urlencode(home_url('/', 'http')).'#.png',

					'prioritize_remote_addr'                      => '0', // `0|1`; enable?
					'geo_location_tracking_enable'                => '0', // `0|1`; enable?

					'site_custom_wp_head_elements'                => '', // CSS/JS/analytics (perhaps a BG color/image).
					'site_custom_header_elements'                 => '', // Perhaps a logo/PHP routines; whatever.
					'site_custom_front_page_blog_header_elements' => '', // Perhaps a Jumbotron with a custom blog intro.
					'site_custom_content_footer_elements'         => '', // Perhaps links/PHP routines; whatever.
					'site_custom_footer_elements'                 => '', // Perhaps links/PHP routines; whatever.
					'site_custom_wp_footer_elements'              => '', // CSS/JS/analytics (whatever).

					'navbar_class'                                => 'default', // Default?
					'navbar_search_box_enable'                    => '1', 'navbar_login_box_enable' => '1',
					'navbar_login_redirect_to'                    => '%%previous%%', 'navbar_logout_redirect_to' => '%%previous%%',
					'navbar_loginout_redirect_always_http'        => '1', // Keep front-end visitors on the HTTP version of the site.
					'navbar_login_registration_url'               => set_url_scheme(wp_registration_url(), 'relative'),
					'navbar_login_registration_via_ajax'          => '0', // Enable simplified login/registration via AJAX?
					'navbar_login_registration_recaptcha_theme'   => 'clean', 'navbar_login_registration_recaptcha_lang' => 'en',
					'navbar_brand_img_url'                        => $this->url('/client-s/images/navbar-brand.png', 'relative'),
					'navbar_brand_img_width'                      => '95px', // Width of default image.

					'comment_avatar_size'                         => '64', // Related to comments.
					'pings_display_enable'                        => '1', // On by default.

					'footbar_col_size'                            => '4', // `col-md-[12|6|4]`.

					'404_img_url'                                 => $this->url('/client-s/images/404.png', 'relative'),
					'204_no_results_img_url'                      => $this->url('/client-s/images/204-no-results.png', 'relative'),
					'204_archive_empty_img_url'                   => $this->url('/client-s/images/204-archive-empty.png', 'relative'),
					'favicon_url'                                 => $this->url('/client-s/images/favicon.ico', 'relative'),

					'x_frame_options_header'                      => '', // Off by default (must enable explicitly).

					'csp_enable'                                  => '0', 'csp_report_only' => '1',
					'csp'                                         => "default-src *;". // See: content-security-policy.com
					                                                 " style-src 'self' 'unsafe-inline' %%trusted_resources%%;".
					                                                 " script-src 'self' 'unsafe-inline' 'unsafe-eval' %%trusted_resources%%;".
					                                                 " font-src 'self' data %%trusted_resources%%;".
					                                                 " img-src 'self' data %%trusted_resources%%;".
					                                                 " object-src 'self' %%trusted_resources%%;".
					                                                 " media-src 'self' %%trusted_resources%%;".
					                                                 " frame-src 'self' %%trusted_resources%%;".
					                                                 " connect-src 'self' %%trusted_resources%%;",
					// See also: https://developer.mozilla.org/en-US/docs/Security/CSP/CSP_policy_directives.
					'csp_trusted_resources'                       => '*.bootstrapcdn.com *.cloudflare.com *.jsdelivr.net'.
					                                                 ' *.cloudfront.net *.amazonaws.com *.websharks-inc.com'.
					                                                 ' *.google.com *.google-analytics.com *.googleapis.com themes.googleusercontent.com'.
					                                                 ' *.wordpress.org *.wordpress.tv *.videopress.com *.disqus.com *.intensedebate.com *.polldaddy.com *.gravatar.com'.
					                                                 ' *.shareaholic.com *.addthis.com *.embed.ly *.github.com *.codepen.io *.jsfiddle.net *.youtube.com *.vimeo.com *.flickr.com *.togetherjs.com',

					'bootstrap_theme_url'                         => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css',

					'background_css'                              => "#F9F9F9 url('".$this->url('/client-s/images/bg.png', 'relative')."') repeat left top scroll",

					'fonts_location'                              => '//fonts.googleapis.com/css?family=Bitter:400,400italic,700|Noto+Serif:400,400italic,700,700italic|Noto+Sans:400,400italic,700,700italic|Merienda:400,700|Skranji:400,700&subset=latin',
					'fonts_css'                                   => "h1, .h1, h2, .h2, h3, .h3, h4, .h4 { font-family: 'Bitter', serif; font-weight: 700; }"."\n".
					                                                 "h3.panel-title, h4.author { font-weight: 400; } #excerpts h3.panel-title { font-weight: 700; }"."\n\n".
					                                                 "h5, .h5, h6, .h6 { font-family: 'Bitter', serif; font-weight: 400; }"."\n\n".

					                                                 "body, .font-body, .fancybox-title { font-family: 'Noto Serif', serif; }"."\n".
					                                                 "#content > * > .data, #excerpts > * > * > .data { font-family: 'Noto Sans', sans-serif; }"."\n".
					                                                 "#content > * > .data > p:first-of-type:not([class]) { font-family: 'Noto Serif', serif; }"."\n".
					                                                 ".single-format-link #content > * > .data > p:nth-of-type(-n+2):not([class]) { font-family: 'Noto Serif', serif; }"."\n".
					                                                 ".fancy-quote, .single-format-quote #content > * > .data > blockquote:not([class]) { font-family: 'Noto Serif', serif; }"."\n\n".

					                                                 ".font-serify { font-family: 'Bitter', serif; }"."\n".
					                                                 ".font-serif { font-family: 'Noto Serif', serif; }"."\n".
					                                                 ".font-sans-serif { font-family: 'Noto Sans', sans-serif; }"."\n".
					                                                 ".font-cursive { font-family: 'Merienda', cursive; }"."\n".
					                                                 ".font-fantasy { font-family: 'Skranji', fantasy; }",

					'update_sync_username'                        => '', 'update_sync_password' => '',
					'update_sync_version_check'                   => '1', 'last_update_sync_version_check' => '0'

				); // Default options are merged with those defined by the site owner.
				$this->default_options = apply_filters(__METHOD__.'__default_options', $this->default_options, get_defined_vars());
				$this->options         = (is_array($this->options = get_option(__NAMESPACE__.'_options'))) ? $this->options : array();
				$this->options         = array_merge($this->default_options, $this->options); // Merge w/ default options.
				$this->options         = apply_filters(__METHOD__.'__options', $this->options, get_defined_vars());

				if($this->options['md_enable_flavor'] === 'wfm') // Back compat.
					$this->options['md_enable_flavor'] = 'php_markdown_extra';

				if(!($this->options['cache_dir'] = trim($this->options['cache_dir'], '\\/'." \t\n\r\0\x0B")))
					$this->options['cache_dir'] = $this->default_options['cache_dir'];

				else if(stripos($this->options['cache_dir'], 'wp-content') !== FALSE /* Back compat. */)
					$this->options['cache_dir'] = $this->default_options['cache_dir'];

				if(!isset($GLOBALS['content_width'])) // Max inner width possible in this theme.
					$GLOBALS['content_width'] = 1108; // See: http://codex.wordpress.org/Content_Width

				if(!is_admin()) // Front-end only.
					add_action('init', array($this, 'headers'));
				add_action('wp_loaded', array($this, 'actions'));

				add_action('admin_init', array($this, 'check_update_sync_version'));

				add_filter('login_redirect', array($this, 'login_redirect'));
				add_action('wp_logout', array($this, 'logout_redirect'));

				if(!is_admin()) // Front-end only; before admin bar initializes.
					add_action('template_redirect', array($this, 'no_admin_bar'), -1);

				add_action('all_admin_notices', array($this, 'all_admin_notices'));
				add_action('all_admin_notices', array($this, 'all_admin_errors'));

				add_action('admin_menu', array($this, 'add_menu_pages'));
				add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
				add_filter('pre_get_posts', array($this, 'pre_get_posts'));

				if($this->options['automatic_feed_links_enable'])
				{
					add_theme_support('automatic-feed-links');
					if($this->options['custom_feed_link']) // Custom feed?
						add_filter('feed_link', array($this, 'feed_link'), 10, 2);
				}
				if($this->options['bitly_shortlinks_enable']) // Enable custom shortlinks?
					add_filter('pre_get_shortlink', array($this, 'bitly_shortlink'), PHP_INT_MAX, 4);

				add_theme_support('html5', // Definitely; all HTML5 here.
				                  array('search-form', 'comment-form', 'comment-list'));

				add_theme_support('post-formats', array( // Supporting all post formats here; custom formats are NOT possible.
				                                         'aside', 'quote', 'audio', 'video', 'gallery', 'image', 'link', 'chat', 'status'));

				add_theme_support('post-thumbnails'); // Featured images; e.g. post thumbnails.
				set_post_thumbnail_size(512, 128, FALSE); // Size in excerpts.

				add_image_size('thumbnail-large', 512, 256, FALSE).add_image_size('thumbnail-large-crop', 512, 256, TRUE);
				add_image_size('thumbnail-medium', 256, 128, FALSE).add_image_size('thumbnail-medium-crop', 256, 128, TRUE);
				add_image_size('thumbnail-small', 128, 64, FALSE).add_image_size('thumbnail-small-crop', 128, 64, TRUE);
				add_image_size('thumbnail-tiny', 64, 64, FALSE).add_image_size('thumbnail-tiny-crop', 64, 64, TRUE);

				register_nav_menu('primary-ls', __('Primary Navbar :: Left Side', $this->text_domain));
				register_nav_menu('primary-rs', __('Primary Navbar :: Right Side', $this->text_domain));

				register_sidebar( // Expandable sidebar configuration.
					array('name'          => __('Primary Sidebar', $this->text_domain), 'id' => 'primary-rs',
					      'description'   => __('Primary slide-in sidebar (right side).', $this->text_domain),
					      'before_widget' => '<aside id="%1$s" class="widget %2$s">', 'after_widget' => '</aside>',
					      'before_title'  => '<h3 class="widget-title"><span class="label label-default block-display">', 'after_title' => '</span></h3>'));

				register_sidebar( // Expandable sidebar configuration.
					array('name'          => __('Primary Footbar', $this->text_domain), 'id' => 'primary-fb',
					      'description'   => __('Primary footbar (bottom).', $this->text_domain),
					      'before_widget' => '<aside id="%1$s" class="widget col-md-'.$this->options['footbar_col_size'].' %2$s">', 'after_widget' => '</aside>',
					      'before_title'  => '<h3 class="widget-title"><span class="label label-default block-display">', 'after_title' => '</span></h3>'));

				if($this->options['seo_enable']) // SEO functionality enabled?
				{
					remove_action('wp_head', 'noindex', 1);
					remove_action('wp_head', 'wp_no_robots', 1);
				}
				add_action('wp_head', array($this, 'charset_meta_tags'), -1);
				add_action('wp_head', array($this, 'title_seo_meta_tags'), -1);
				add_action('wp_head', array($this, 'viewport_meta_tags'), -1);

				add_action('wp_head', array($this, 'open_graph_meta_tags'), -1);
				add_action('wp_head', array($this, 'theme_meta_tags'), -1);

				add_action('wp_head', array($this, 'favicon_meta_tags'), -1);
				add_action('wp_head', array($this, 'pingback_meta_tags'), -1);

				add_action('wp_head', array($this, 'enqueue_styles'), -1);
				add_action('wp_head', array($this, 'enqueue_scripts'), -1);

				add_action('wp_head', array($this, 'custom_wp_head_elements'), PHP_INT_MAX);
				add_action('wp_footer', array($this, 'custom_wp_footer_elements'), PHP_INT_MAX);

				add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
				add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

				add_filter('cancel_comment_reply_link', array($this, 'cancel_comment_reply'));

				add_shortcode('raw', array($this, 'shortcodes'));
				add_shortcode('tag_cloud', array($this, 'shortcodes'));
				add_shortcode('contact_form', array($this, 'shortcodes'));
				add_shortcode('wp_readme_tabs', array($this, 'shortcodes'));
				add_shortcode('share_btn_icons', array($this, 'shortcodes'));
				add_shortcode('trending_posts', array($this, 'shortcodes'));

				add_filter('the_content', array($this, 'format_typer'), 5);
				add_filter('get_the_excerpt', array($this, 'format_typer'), 5);

				add_filter(__NAMESPACE__.'_get_the_summary', 'wptexturize');
				add_filter(__NAMESPACE__.'_get_the_summary', 'convert_smilies');
				add_filter(__NAMESPACE__.'_get_the_summary', 'convert_chars');
				add_filter(__NAMESPACE__.'_get_the_summary', 'wpautop');
				add_filter(__NAMESPACE__.'_get_the_summary', 'shortcode_unautop');

				if($this->options['md_enable_flavor']) // Markdown is enabled?
				{
					// Remove default pre filters.
					remove_action('init', 'kses_init');
					remove_action('set_current_user', 'kses_init');
					// ↑ KSES actions result in pre filters also.
					remove_filter('content_save_pre', 'balanceTags', 50);
					remove_filter('excerpt_save_pre', 'balanceTags', 50);
					remove_filter('comment_save_pre', 'balanceTags', 50);
					remove_filter('pre_comment_content', 'balanceTags', 50);
					remove_filter('pre_comment_content', 'wp_rel_nofollow', 15);

					// Remove default oEmbed content filters.
					remove_filter('the_content', array($GLOBALS['wp_embed'], 'run_shortcode'), 8);
					remove_filter('the_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);

					// Remove default content filters.
					remove_filter('the_content', 'wptexturize');
					remove_filter('the_content', 'convert_smilies');
					remove_filter('the_content', 'convert_chars');
					remove_filter('the_content', 'wpautop');
					remove_filter('the_content', 'shortcode_unautop');
					remove_filter('the_content', 'capital_P_dangit', 11);
					remove_filter('the_content', 'do_shortcode', 11);

					// Remove default excerpt filters.
					remove_filter('the_excerpt', 'wptexturize');
					remove_filter('the_excerpt', 'convert_smilies');
					remove_filter('the_excerpt', 'convert_chars');
					remove_filter('the_excerpt', 'wpautop');
					remove_filter('the_excerpt', 'shortcode_unautop');
					remove_filter('get_the_excerpt', 'wp_trim_excerpt');

					// Remove default summary filters.
					remove_filter(__NAMESPACE__.'_get_the_summary', 'wptexturize');
					remove_filter(__NAMESPACE__.'_get_the_summary', 'convert_smilies');
					remove_filter(__NAMESPACE__.'_get_the_summary', 'convert_chars');
					remove_filter(__NAMESPACE__.'_get_the_summary', 'wpautop');
					remove_filter(__NAMESPACE__.'_get_the_summary', 'shortcode_unautop');

					// Remove default comment filters.
					remove_filter('comment_text', 'wptexturize');
					remove_filter('comment_text', 'convert_smilies', 20);
					remove_filter('comment_text', 'convert_chars');
					remove_filter('comment_text', 'wpautop', 30);
					remove_filter('comment_text', 'make_clickable', 9);
					remove_filter('comment_text', 'force_balance_tags', 25);
					remove_filter('comment_text', 'capital_P_dangit', 31);

					// Remove this `widget_text` filter on init; added by many plugins.
					add_action('init', function () // We'll handle this (see below).
					{
						remove_filter('widget_text', 'do_shortcode'); # Ditch!

					}, PHP_INT_MAX /* Last filter. */);

					// Add our custom KSES handlers.
					// At priority `10`; when applicable.
					add_action('init', array($this, 'kses_init'));
					add_action('set_current_user', array($this, 'kses_init'));

					$md_filter_priority_start = // Starting filter priority.
						// Should come after priority `1` in order to remain compatible w/ ezPHP.
						// Should come after priority `5`; where our post format typer runs.
						// After priority `10` so any default plugin filters are done already.
						// Should come after priority `10` so that KSES filters are done already.
						apply_filters(__METHOD__.'__md_filter_priority_start', 12, get_defined_vars());

					// Enable MD indent stripping in the ezPHP plugin.
					add_filter('ezphp_strip_md_indents', '__return_true');

					// One-line URLs for oEmbed in WordPres via shortcode.
					add_shortcode('embed', array($GLOBALS['wp_embed'], 'shortcode'));

					// Attach our custom content filters.
					add_filter('the_content', array($this, 'oembed'), $md_filter_priority_start);
					add_filter('the_content', array($this, 'clickable'), $md_filter_priority_start + 2);
					add_filter('the_content', array($this, 'md_parse_cache'), $md_filter_priority_start + 4);
					add_filter('the_content', array($this, 'parse_shortcodes'), $md_filter_priority_start + 6);
					add_filter('the_content', array($this, 'pcs_format'), $md_filter_priority_start + 8);

					// Attach our custom excerpt filters.
					add_filter('get_the_excerpt', array($this, 'excerpt_'), -(PHP_INT_MAX - 10));
					add_filter('get_the_excerpt', array($this, 'oembed'), $md_filter_priority_start);
					add_filter('get_the_excerpt', array($this, 'clickable'), $md_filter_priority_start + 2);
					add_filter('get_the_excerpt', array($this, 'md_parse_cache'), $md_filter_priority_start + 4);
					add_filter('get_the_excerpt', array($this, 'parse_shortcodes'), $md_filter_priority_start + 6);
					add_filter('get_the_excerpt', array($this, 'pcs_format'), $md_filter_priority_start + 8);

					// Attach our custom summary filters.
					add_filter(__NAMESPACE__.'_get_the_summary', array($this, 'oembed'), $md_filter_priority_start);
					add_filter(__NAMESPACE__.'_get_the_summary', array($this, 'clickable'), $md_filter_priority_start + 2);
					add_filter(__NAMESPACE__.'_get_the_summary', array($this, 'md_parse_cache'), $md_filter_priority_start + 4);
					add_filter(__NAMESPACE__.'_get_the_summary', array($this, 'parse_shortcodes'), $md_filter_priority_start + 6);
					add_filter(__NAMESPACE__.'_get_the_summary', array($this, 'pcs_format'), $md_filter_priority_start + 8);

					// Attach our custom text widget filters.
					add_filter('widget_text', array($this, 'oembed'), $md_filter_priority_start);
					add_filter('widget_text', array($this, 'clickable'), $md_filter_priority_start + 2);
					add_filter('widget_text', array($this, 'md_parse_cache'), $md_filter_priority_start + 4);
					add_filter('widget_text', array($this, 'parse_shortcodes'), $md_filter_priority_start + 6);
					add_filter('widget_text', array($this, 'pcs_format'), $md_filter_priority_start + 8);

					// Attach our custom comment filters (all pre-processors).
					// Note that embeds/shortcodes do NOT work in comments; for security purposes.
					add_filter('pre_comment_content', array($this, 'clickable'), $md_filter_priority_start);
					add_filter('pre_comment_content', array($this, 'md_parse_cache'), $md_filter_priority_start + 2);
					add_filter('pre_comment_content', 'wp_rel_nofollow', $md_filter_priority_start + 4);
					add_filter('pre_comment_content', 'force_balance_tags', $md_filter_priority_start + 6);
					add_filter('pre_comment_content', array($this, 'pcs_format'), $md_filter_priority_start + 8);

					if(defined('RAWHTML_PLUGIN_FILE')) // Check for existence of the Raw HTML plugin; it's NOT compatible.
						// Not compatible because it uses `maybe_` variations that apply filters directly; conflicting w/ filters above.
						throw new \exception(__('Sorry, the Raw HTML plugin is not compatible (or even necessary) when your theme is running w/ Markdown processing enabled :-) With Markdown, default WP content filters are disabled and raw HTML can be simply be included together w/ Markdown already (this is how Markdown works). Please visit your Dashboard and disable the Raw HTML plugin to get rid of this error message. Or, you can disable Markdown processing in your theme options, if you prefer.', $this->text_domain));
				}
				add_filter('pre_site_transient_update_themes', array($this, 'pre_site_transient_update_themes'));

				if((integer)$this->options['crons_setup'] < 1382523750) // Only if NOT already setup.
				{
					wp_clear_scheduled_hook('_cron_'.__NAMESPACE__.'_md_cache_purge');
					wp_schedule_event(time() + 60, 'daily', '_cron_'.__NAMESPACE__.'_md_cache_purge');

					$this->options['crons_setup'] = (string)time();
					update_option(__NAMESPACE__.'_options', $this->options);
				}
				add_action('_cron_'.__NAMESPACE__.'_md_cache_purge', array($this, 'md_cache_purge'));

				do_action('after__'.__METHOD__, get_defined_vars());
				do_action(__METHOD__.'_complete', get_defined_vars());
			}

			public function activate()
			{
				// Nothing we need to do here (for now).
			}

			public function deactivate()
			{
				if(!$this->options['uninstall_on_deactivation'])
					return; // Nothing to do here.

				delete_option(__NAMESPACE__.'_options');
				delete_option(__NAMESPACE__.'_notices');
				delete_option(__NAMESPACE__.'_errors');

				$this->md_cache_clear(); // Clear any Markdown cache files.
				wp_clear_scheduled_hook('_cron_'.__NAMESPACE__.'_md_cache_purge');
			}

			public function is_pro()
			{
				static $is; // Static cache.
				if(isset($is)) return $is;

				if(defined($key = strtoupper(__NAMESPACE__).'_PRO'))
					return ($is = TRUE);

				return ($is = FALSE);
			}

			public function is_pro_preview()
			{
				static $is; // Static cache.
				if(isset($is)) return $is;

				if(!empty($_REQUEST[__NAMESPACE__.'_pro_preview']))
					if(!$this->is_pro()) return ($is = TRUE);

				return ($is = FALSE);
			}

			public function is_browser()
			{
				static $is; // Static cache.
				if(isset($is)) return $is;

				$regex = '/(?:msie|trident|gecko|webkit|presto|konqueror|playstation)[\/\s]+[0-9]/i';
				if(!empty($_SERVER['HTTP_USER_AGENT']) && is_string($_SERVER['HTTP_USER_AGENT']))
					if(preg_match($regex, $_SERVER['HTTP_USER_AGENT']))
						return ($is = TRUE);

				return ($is = FALSE);
			}

			public function debug_log($value) // Logs a debug entry.
			{
				if(!WP_DEBUG || !WP_DEBUG_LOG) return; // Debugging NOT enabled at this time.
				$log_entry = apply_filters(__METHOD__, print_r($value, TRUE)."\n\n", get_defined_vars());
				file_put_contents(WP_CONTENT_DIR.'/debug.log', $log_entry, FILE_APPEND);
			}

			public function eval_($string)
			{
				ob_start();
				eval('?>'.trim($string).'<?php ');
				return ob_get_clean();
			}

			public function shortcode_eval($string)
			{
				ob_start();
				eval('?>'.trim($string).'<?php ');
				return do_shortcode(ob_get_clean());
			}

			public function ob_end_clean()
			{
				$ob_levels = ob_get_level(); // Cleans output buffers.
				for($ob_level = 0; $ob_level < $ob_levels; $ob_level++)
					@ob_end_clean(); // May fail on a locked buffer.
				unset($ob_levels, $ob_level);

				return ob_get_level() ? FALSE : TRUE;
			}

			public function headers()
			{
				if(headers_sent()) return; // Nothing we can do.

				header('X-UA-Compatible: IE=edge,chrome=1');

				if($this->options['nocache_headers_enable'])
					nocache_headers(); // No browser cache.

				if($this->options['x_frame_options_header'])
					header('X-Frame-Options: '.$this->options['x_frame_options_header']);

				if(!$this->options['csp_enable'] || !$this->options['csp'])
					return; // Nothing more to do in this case.

				$csp = str_replace('%%trusted_resources%%', // Parse replacement code.
				                   $this->options['csp_trusted_resources'], $this->options['csp']);
				$csp = apply_filters(__METHOD__, $csp, get_defined_vars());

				if($this->options['csp_report_only'])
					header('Content-Security-Policy-Report-Only: '.$csp);
				else header('Content-Security-Policy: '.$csp);
			}

			public function actions()
			{
				if(empty($_REQUEST['theme'])) return;

				require_once dirname(__FILE__).'/actions.php';
			}

			public function pre_get_posts(\WP_Query $query)
			{
				if(isset($_REQUEST['s']) && !$_REQUEST['s'] && $query->is_main_query())
				{
					$query->is_search = TRUE; // Correct WordPress here.
					$query->is_home   = FALSE; // Force false.
				}
				return apply_filters(__METHOD__, $query, get_defined_vars());
			}

			public function login_redirect($redirect_to)
			{
				if(!$this->options['navbar_login_box_enable'])
					return $redirect_to; // Nothing to do.

				if(!$this->options['navbar_loginout_redirect_always_http'])
					return $redirect_to; // Nothing to do.

				if(strpos($redirect_to, 'wp-admin') !== FALSE)
					return $redirect_to; // Nothing to do.

				$redirect_to = preg_replace('/^https\:\/\//i', 'http://', $redirect_to);
				if(stripos($redirect_to, 'http://') !== 0) // Force absolute URL in this case.
				{
					$home_path      = trim((string)parse_url(home_url('/'), PHP_URL_PATH), '/');
					$http_home_base = trim(preg_replace('/\/'.preg_quote($home_path, '/').'\/$/', '', home_url('/', 'http')), '/');
					$redirect_to    = $http_home_base.'/'.ltrim($redirect_to, '/');
				}
				return apply_filters(__METHOD__, $redirect_to, get_defined_vars());
			}

			public function logout_redirect() // Hook: `wp_logout`.
			{
				if(!$this->options['navbar_login_box_enable'])
					return; // Nothing to do.

				if(!$this->options['navbar_loginout_redirect_always_http'])
					return; // Nothing to do.

				$redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to']
					: add_query_arg('loggedout', urlencode('true'), wp_login_url());

				if(strpos($redirect_to, 'wp-login') !== FALSE)
					return; // Nothing to do in this case.

				$redirect_to = preg_replace('/^https\:\/\//i', 'http://', $redirect_to);
				if(stripos($redirect_to, 'http://') !== 0) // Force absolute URL in this case.
				{
					$home_path      = trim((string)parse_url(home_url('/'), PHP_URL_PATH), '/');
					$http_home_base = trim(preg_replace('/\/'.preg_quote($home_path, '/').'\/$/', '', home_url('/', 'http')), '/');
					$redirect_to    = $http_home_base.'/'.ltrim($redirect_to, '/');
				}
				wp_safe_redirect(apply_filters(__METHOD__, $redirect_to, get_defined_vars())).exit();
			}

			public function url($file = '', $scheme = '')
			{
				static $template_directory; // Static cache.

				if(!isset($template_directory)) // Not cached yet?
					$template_directory = get_template_directory_uri();

				$url = $template_directory.'/src'.(string)$file;

				if($scheme) // A specific URL scheme?
					$url = set_url_scheme($url, (string)$scheme);

				return apply_filters(__METHOD__, $url, get_defined_vars());
			}

			public function current_url()
			{
				static $url; // Static cache.
				if(isset($url)) return $url;

				$url = (is_ssl() ? 'https' : 'http').'://';
				$url .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				return apply_filters(__METHOD__, $url, get_defined_vars());
			}

			public function part()
			{
				$post_count_gt0 = post_count_gt0();

				if($post_count_gt0 && is_home_page())
					$part = 'home';

				else if($post_count_gt0 && is_blog_page())
					$part = 'blog';

				else if($post_count_gt0 && is_archive())
					$part = 'archive';

				else if($post_count_gt0 && is_search())
					$part = 'search';

				else if($post_count_gt0 && is_singular())
					$part = trim(get_post_type().'-'.get_post_format(), '-');

				else $part = (is_404()) ? '404' : '204'; // Missing.

				return apply_filters(__METHOD__, $part, get_defined_vars());
			}

			public function post_custom($key)
			{
				$post_id = get_the_ID();

				if(!strlen($key = (string)$key))
					return ''; // No key.

				static $custom = array(); // Static cache.

				if(!isset($custom[$post_id])) // Custom/meta values.
				{
					$custom           = array_slice($custom, 0, 10, TRUE);
					$custom[$post_id] = get_post_custom();
				}
				if(!empty($custom[$post_id][$key][0])) // Has value?
					$value = (string)$custom[$post_id][$key][0];

				return apply_filters(__METHOD__, (!empty($value)) ? $value : '', get_defined_vars());
			}

			public function lang_attributes()
			{
				ob_start();
				language_attributes();
				$attributes = ob_get_clean();

				return apply_filters(__METHOD__, $attributes, get_defined_vars());
			}

			public function html_body_classes()
			{
				static $classes; // Static cache.

				if(isset($classes)) return $classes; // Via cache.

				$classes = get_body_class();

				if($this->is_fluid())
					$classes[] = 'fluid';

				if($this->no_admin_bar())
					$classes[] = 'no-admin-bar';

				if($this->no_navbar())
					$classes[] = 'no-navbar';

				if($this->no_panel())
					$classes[] = 'no-panel';

				if($this->no_topper())
					$classes[] = 'no-topper';

				if($this->no_topper_shortlink())
					$classes[] = 'no-topper-shortlink';

				if($this->no_topper_date())
					$classes[] = 'no-topper-date';

				if($this->no_sidebar())
					$classes[] = 'no-sidebar';

				if($this->no_sharebar())
					$classes[] = 'no-sharebar';

				if($this->no_footbar())
					$classes[] = 'no-footbar';

				$classes[] = 'bs-theme-'.md5($this->options['bootstrap_theme_url']);

				if(!empty($_SERVER['HTTP_USER_AGENT']))
					if(stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== FALSE)
						$classes[] = 'ie9'; // Identify IE 9.

				$classes = apply_filters(__METHOD__, $classes, get_defined_vars());

				return ($classes = implode(' ', $classes)); // Cache string.
			}

			public function page_template()
			{
				if(!is_page()) return ''; // Not applicable.

				$template = basename($this->post_custom('_wp_page_template'), '.php');
				$template = str_replace('page-', '', $template);

				return apply_filters(__METHOD__, $template, get_defined_vars());
			}

			public function charset_meta_tags()
			{
				$tags = '<meta charset="'.esc_attr(get_bloginfo('charset')).'" />'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function title_seo_meta_tags()
			{
				if($this->options['seo_enable'])
				{
					$tags = '<title>'.esc_html($this->seo_title()).'</title>'."\n";
					$tags .= '<meta name="robots" content="'.esc_attr($this->seo_robots()).'" />'."\n";
					$tags .= '<meta name="keywords" content="'.esc_attr($this->seo_keywords()).'" />'."\n";
					$tags .= '<meta name="description" content="'.esc_attr($this->seo_description()).'" />'."\n";
				}
				else $tags = '<title>'.esc_html(wp_title('|', FALSE, 'right')).'</title>'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function open_graph_meta_tags()
			{
				if(!$this->options['open_graph_enable'])
					return; // Nothing to do here.

				$tags = '<meta property="og:site_name" content="'.esc_attr($this->seo_site()).'" />'."\n";
				$tags .= '<meta property="og:title" content="'.esc_attr($this->seo_title()).'" />'."\n";
				$tags .= '<meta property="og:description" content="'.esc_attr($this->seo_description()).'" />'."\n";
				$tags .= '<meta property="og:type" content="'.esc_attr($this->open_graph_type()).'" />'."\n";
				$tags .= '<meta property="og:url" content="'.esc_attr($this->open_graph_url()).'" />'."\n";
				$tags .= $this->open_graph_image_tags()."\n"; // Requires separate routines.

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function theme_meta_tags()
			{
				$vars = array( // Dynamic JS vars.
				               'ajaxUrl'                        => site_url('/wp-load.php', is_ssl() ? 'https' : 'http'),
				               'ajax_i18nError'                 => __('Connection error, please try again.', $this->text_domain),

				               'navbarLoginRegistrationViaAjax' => (integer)$this->options['navbar_login_registration_via_ajax'],
				               'ajaxLoginRegistrationUrl'       => home_url($_SERVER['REQUEST_URI'], 'login_post'),

				               'singularId'                     => (is_singular()) ? get_the_ID() : 0,

				               'embedlyKey'                     => $this->options['embedly_key'],
				               'embedlySyntax'                  => $this->embedly_syntax(),

				               'allowedTags'                    => $this->allowed_tags(),
				               'mdSyntax'                       => $this->md_syntax(),

				               'taboverrideSize'                => (integer)$this->options['taboverride_size'],
				               'reCAPTCHAPublicKey'             => $this->options['recaptcha_public_key'],
				               'addthisPublisher'               => $this->options['addthis_publisher'],

				               'zeroClipSwfUrl'                 => $this->options['zeroclip_swf_url'],
				               'zeroClip_i18nCopy'              => __('Click to Copy', $this->text_domain),
				               'zeroClip_i18nCopied'            => __('Copied!', $this->text_domain),
				               'zeroClip_i18nFallback'          => __('Press <kbd>Ctrl + C</kbd> to copy...', $this->text_domain),

				               'togetherJS_i18nTitle'           => __('TogetherJS™ — Ready to Collaborate?', $this->text_domain),
				               'togetherJS_i18nFserror'         => __('Sorry, TogetherJS™ will not work in fullscreen mode yet  <i class="fa fa-frown-o"></i>', $this->text_domain),
				               'togetherJS_i18nInfo'            => __('<p>TogetherJS™ enables audio chat, text chat, user focus (i.e. cursors), co-browsing, user presence &amp; real-time content sync!</p>', $this->text_domain).
				                                                   __('<p><strong>TIP:</strong> If this is your first time using TogetherJS™, please allow up to 5 minutes for loading. It will take a moment after clicking the `Yes` button. Thanks for your patience <i class="fa fa-smile-o"></i></p>', $this->text_domain));

				$vars = apply_filters(__METHOD__, $vars, get_defined_vars());

				$tags = '<meta property="theme:vars" content="data-json"'.
				        // Note that `allowed_tags()` returns HTML w/ inner encoded entities.
				        // Therefore; MUST use `esc_textarea()` because we NEED double encoding.
				        ' data-json="'.esc_textarea(json_encode($vars)).'" id="theme-vars" />'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function favicon_meta_tags()
			{
				if(!$this->options['favicon_url']) return; // Nothing to do here.

				$tags = '<link rel="shortcut icon" href="'.esc_attr(set_url_scheme($this->options['favicon_url'])).'" />'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function pingback_meta_tags()
			{
				$tags = '<link rel="pingback" href="'.esc_attr(get_bloginfo('pingback_url')).'" />'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function viewport_meta_tags()
			{
				$tags = '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n";

				echo apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function feed_link($link, $feed)
			{
				if(!$this->options['custom_feed_link'])
					return $link; // Nothing custom.

				if(stripos($link, 'comments') !== FALSE || stripos($feed, 'comments') !== FALSE)
					return $link; // Keep this; it's NOT the primary feed in this case.

				return $this->options['custom_feed_link'];
			}

			public function shortlink_copier($args)
			{
				if(is_integer($args)) $args = array('post_id' => $args);

				$defaults = array('post_id' => 0, 'class' => '', 'style' => '', 'attr' => '',
				                  'label'   => __('<i class="fa fa-link"></i> Shortlink (Share)', $this->text_domain));
				$args     = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args     = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				if($args['class']) $args['class'] = ' '.$args['class']; // Whitespace.

				if(!($shortlink = wp_get_shortlink($args['post_id'])))
					return ''; // Not applicable.

				$copier = '<div class="shortlink-copier'.esc_attr($args['class']).'" style="'.esc_attr($args['style']).'"'.
				          ' data-toggle="zeroclip" data-clipboard-text="'.esc_attr($shortlink).'"'.
				          ($args['attr'] ? ' '.trim($args['attr']) : '').'>';

				if($args['label']) // Has a label; i.e. a small heading?
					$copier .= '<div class="text-center">'.$args['label'].'</div>';

				$copier .= '   <form class="form-group">'.
				           '      <div class="input-group input-group-sm">'.
				           '         <input type="text" value="'.esc_attr(preg_replace('/^https?\:\/\//i', '', $shortlink)).'" class="form-control" />'.
				           '         <span class="input-group-addon"><i class="fa fa-clipboard"></i></span>'.
				           '      </div>'.
				           '   </form>';
				$copier .= '</div>';

				return apply_filters(__METHOD__, $copier, get_defined_vars());
			}

			public function bitly_shortlink($shortlink, $id, $context, $allow_slugs)
			{
				if($shortlink !== FALSE) return $shortlink;

				if(!$this->options['bitly_shortlinks_enable'])
					return FALSE; // Nothing to do here.

				if(!$this->options['bitly_access_token'])
					return FALSE; // Not possible.

				if($context === 'post') $post = get_post($id);

				else if($context === 'query' && is_singular())
					$post = get_post(get_queried_object_id());

				if(empty($post) || empty($post->ID))
					return FALSE; // Not possible.

				if(($shortlink = get_post_meta($post->ID, 'shortlink', TRUE)))
					return $shortlink; // Already did this.

				if(!($shortlink = $this->shorten(home_url('?p='.$post->ID))))
					return FALSE; // Not possible at this time.

				update_post_meta($post->ID, 'shortlink', $shortlink);

				return apply_filters(__METHOD__, $shortlink, get_defined_vars());
			}

			public function shorten($long_url)
			{
				$short_url = $long_url; // Initialize.

				if(!$this->options['bitly_access_token'])
					return ($short_url = $long_url);

				$endpoint  = 'https://api-ssl.bitly.com/v3/shorten';
				$post_vars = array('access_token' => $this->options['bitly_access_token'],
				                   'longUrl'      => $long_url);

				$response = wp_remote_post($endpoint, array('body' => $post_vars));
				$response = wp_remote_retrieve_body($response);
				$response = json_decode($response);

				if(is_object($response) && !empty($response->status_code) && $response->status_code === 200)
					if(!empty($response->data->url)) $short_url = $response->data->url;

				return apply_filters(__METHOD__, $short_url, get_defined_vars());
			}

			public function current_ip()
			{
				static $ip; // Static cache.

				if(isset($ip)) // Cached already?
					return $ip; // Cached IP.

				if(!empty($_SERVER['REMOTE_ADDR']) && $this->options['prioritize_remote_addr'])
					if(($_valid_public_ip = $this->valid_public_ip($_SERVER['REMOTE_ADDR'])))
						return ($ip = $_valid_public_ip);

				$sources = array(
					'HTTP_CF_CONNECTING_IP',
					'HTTP_CLIENT_IP',
					'HTTP_X_FORWARDED_FOR',
					'HTTP_X_FORWARDED',
					'HTTP_X_CLUSTER_CLIENT_IP',
					'HTTP_FORWARDED_FOR',
					'HTTP_FORWARDED',
					'HTTP_VIA',
					'REMOTE_ADDR',
				);
				$sources = apply_filters(__METHOD__.'_sources', $sources);

				foreach($sources as $_source) // Try each of these; in order.
				{
					if(!empty($_SERVER[$_source])) // Does the source key exist at all?
						if(($_valid_public_ip = $this->valid_public_ip($_SERVER[$_source])))
							return ($ip = $_valid_public_ip); // A valid public IPv4 or IPv6 address.
				}
				unset($_source, $_valid_public_ip); // Housekeeping.

				if(!empty($_SERVER['REMOTE_ADDR']) && is_string($_SERVER['REMOTE_ADDR']))
					return ($ip = strtolower($_SERVER['REMOTE_ADDR']));

				return ($ip = 'unknown'); // Not possible.
			}

			public function valid_public_ip($list_of_possible_ips)
			{
				if(!$list_of_possible_ips || !is_string($list_of_possible_ips))
					return ''; // Empty or invalid data.

				if(!($list_of_possible_ips = trim($list_of_possible_ips)))
					return ''; // Not possible; i.e., empty string.

				foreach(preg_split('/[\s;,]+/', $list_of_possible_ips, NULL, PREG_SPLIT_NO_EMPTY) as $_possible_ip)
					if(($_valid_public_ip = filter_var(strtolower($_possible_ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)))
						return $_valid_public_ip; // A valid public IPv4 or IPv6 address.
				unset($_possible_ip, $_valid_public_ip); // Housekeeping.

				return ''; // Default return value.
			}

			public function lazyload($lib)
			{
				static $static = array();

				if(isset($static[$lib])) return $static[$lib];

				$post = get_post(); // Optimize; reduce function calls below.

				switch($lib) // Based on the library we're checking.
				{
					case 'jquery_ui': // Load jQuery UI?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(strpos($post->post_content, 'jquery_ui') !== FALSE
							   || strpos($post->post_content, 'jquery-ui') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'tabdrop': // Load tabDrop?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(strpos($post->post_content, '[wp_readme_tabs') !== FALSE
							   || strpos($post->post_content, '[snippet_template slug="product-template"') !== FALSE
							   || strpos($post->post_content, 'tabdrop') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'taboverride': // Load Tab Override?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(comments_open() // Always enable for commenters; e.g. message textarea.
							   || strpos($post->post_content, 'taboverride') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'highlight_js': // Load Highlight.js?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(comments_open() || get_comments_number() || stripos($post->post_content, '<pre') !== FALSE
							   || ($this->options['md_enable_flavor'] && strpos($post->post_content, '```') !== FALSE)
							   || ($this->options['md_enable_flavor'] && strpos($post->post_content, '```') !== FALSE)
							   || strpos($post->post_content, '[snippet_template slug="product-template"') !== FALSE
							   || strpos($post->post_content, '[wp_readme_tabs') !== FALSE
							   || strpos($post->post_content, 'highlight') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'zeroclip': // Load zeroClip?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(($this->options['shortlinks_display_enable'] && is_single())
							   || strpos($post->post_content, 'shortlink') !== FALSE
							   || strpos($post->post_content, 'zeroclip') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'embedly': // Load Embedly?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(comments_open() || get_comments_number() // Always enable for commenters.
							   || in_array(get_post_type(), array('kb_article'), TRUE) // Certain post types.
							   || in_array(get_post_format(), array('audio', 'video', 'image', 'link'), TRUE)
							   || ($this->options['md_enable_flavor'] && strpos($post->post_content, '](') !== FALSE)
							   || ($this->options['md_enable_flavor'] && strpos($post->post_content, ']: ') !== FALSE)
							   || stripos($post->post_content, '<a') !== FALSE
							   || strpos($post->post_content, "\n".'http://') !== FALSE
							   || strpos($post->post_content, "\n".'https://') !== FALSE
							   || strpos($post->post_content, 'embedly') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'togetherjs': // Load TogetherJS?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(strpos($post->post_content, 'togetherjs') !== FALSE)
								$this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'fancybox': // Load Fancy Box?

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(comments_open() || get_comments_number() // Always enable for commenters.
							   || is_attachment() || in_array(get_post_format(), array('gallery', 'image'), TRUE)
							   || strpos($post->post_content, '[snippet_template slug="product-template"') !== FALSE
							   || strpos($post->post_content, '[wp_readme_tabs') !== FALSE
							   || strpos($post->post_content, '[gallery') !== FALSE
							   || strpos($post->post_content, 'fancybox') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.

					case 'recaptcha': // Load reCAPTCHA?

						if($this->options[$lib.'_enable'] === '' && $this->options['navbar_login_box_enable'])
							if($this->options['navbar_login_registration_via_ajax'] && !is_user_logged_in())
								$this->options[$lib.'_enable'] = $static[$lib] = '1';

						if($this->options[$lib.'_enable'] === '' && is_singular())
							if(strpos($post->post_content, '[contact_form') !== FALSE
							   || strpos($post->post_content, 'recaptcha') !== FALSE
							) $this->options[$lib.'_enable'] = $static[$lib] = '1';

						break; // Break switch handler.
				}
				if(!isset($static[$lib])) $static[$lib] = FALSE; // Cache.

				return apply_filters(__METHOD__, $static[$lib], get_defined_vars());
			}

			public function enqueue_styles() // Bootstrap & Theme, Font Awesome, etc, etc.
			{
				$deps = array('bootstrap', 'font-awesome'); // An array of the default theme dependencies.

				wp_enqueue_style('bootstrap', set_url_scheme($this->options['bootstrap_theme_url']), array(), NULL, 'all'); // Chosen by site owner.
				wp_enqueue_style('font-awesome', set_url_scheme('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'), array('bootstrap'), NULL, 'all');

				if(($this->options['jquery_ui_enable'] || $this->lazyload('jquery_ui')) && ($deps[] = 'jquery-ui'))
					wp_enqueue_style('jquery-ui', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/css/base/jquery.ui.all.min.css'), array(), NULL, 'all');

				if(($this->options['highlight_js_enable'] || $this->lazyload('highlight_js')) && $this->options['highlight_js_theme'] && ($deps[] = 'highlight-js'))
					wp_enqueue_style('highlight-js', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/'.$this->options['highlight_js_theme'].'.min.css'), array(), NULL, 'all');

				if(($this->options['fancybox_enable'] || $this->lazyload('fancybox')) && ($deps[] = 'fancybox') && ($deps[] = 'fancybox-buttons') && ($deps[] = 'fancybox-thumbs'))
				{
					wp_enqueue_style('fancybox', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css'), array(), NULL, 'all');
					wp_enqueue_style('fancybox-buttons', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css'), array('fancybox'), NULL, 'all');
					wp_enqueue_style('fancybox-thumbs', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css'), array('fancybox'), NULL, 'all');
				}
				wp_enqueue_style(__NAMESPACE__, get_stylesheet_uri(), $deps, $this->version, 'all'); // Theme (or child theme) styles.

				if($this->options['background_css']) // This includes support for a `#container` BG in fullscreen mode.
					wp_add_inline_style(__NAMESPACE__, 'body { background: '.rtrim($this->options['background_css'], ';').'; }'."\n".
					                                   ' #container:fullscreen { background: '.rtrim($this->options['background_css'], ';').'; }'."\n".
					                                   ' #container:-webkit-full-screen { background: '.rtrim($this->options['background_css'], ';').'; }'."\n".
					                                   ' #container:-moz-full-screen { background: '.rtrim($this->options['background_css'], ';').'; }'."\n".
					                                   ' #container:-ms-fullscreen { background: '.rtrim($this->options['background_css'], ';').'; }');

				if($this->options['fonts_location'] && stripos($this->options['fonts_location'], '</script>') === FALSE)
					wp_enqueue_style('fonts', set_url_scheme($this->options['fonts_location']), array(__NAMESPACE__), NULL, 'all');

				if($this->options['fonts_location'] && $this->options['fonts_css']) wp_add_inline_style('fonts', $this->options['fonts_css']);
			}

			public function enqueue_scripts() // jQuery, Bootstrap, Comments, etc, etc.
			{
				$deps = array('jquery', 'bootstrap'); // An array of the default theme dependencies.

				wp_deregister_script('jquery'); // De-register; we want this from a CDN (for speed).
				wp_deregister_script('comment-reply'); // De-register; may want this in footer.

				wp_enqueue_script('jquery', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['jquery_ui_enable'] || $this->lazyload('jquery_ui')) && ($deps[] = 'jquery-ui') && ($deps[] = 'jquery-ui-bs'))
					wp_enqueue_script('jquery-ui', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js'), array('jquery'), NULL, (boolean)$this->options['scripts_in_footer']).
					wp_enqueue_script('jquery-ui-bs', $this->url('/client-s/js/jquery-ui-bs.js'), array('jquery-ui'), $this->version, (boolean)$this->options['scripts_in_footer']);

				wp_enqueue_script('bootstrap', set_url_scheme('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'), array('jquery'), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['tabdrop_enable'] || $this->lazyload('tabdrop')) && ($deps[] = 'tabdrop'))
					wp_enqueue_script('tabdrop', $this->url('/client-s/js/tabdrop.min.js'), array('jquery', 'bootstrap'), $this->version, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['taboverride_enable'] || $this->lazyload('taboverride')) && ($deps[] = 'taboverride'))
					wp_enqueue_script('taboverride', set_url_scheme('//cdn.jsdelivr.net/taboverride/4.0.2/taboverride.min.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['highlight_js_enable'] || $this->lazyload('highlight_js')) && $this->options['highlight_js_theme'] && ($deps[] = 'highlight-js'))
					wp_enqueue_script('highlight-js', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['zeroclip_enable'] || $this->lazyload('zeroclip')) && $this->options['zeroclip_swf_url'] && ($deps[] = 'zeroclip'))
					wp_enqueue_script('zeroclip', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/1.3.2/ZeroClipboard.min.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['embedly_enable'] || $this->lazyload('embedly')) && $this->options['embedly_key'] && ($deps[] = 'embedly'))
					wp_enqueue_script('embedly', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/embedly-jquery/3.1.1/jquery.embedly.min.js'), array('jquery'), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['togetherjs_enable'] || $this->lazyload('togetherjs')) && ($deps[] = 'togetherjs'))
					wp_enqueue_script('togetherjs', set_url_scheme('//togetherjs.com/togetherjs-min.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(($this->options['fancybox_enable'] || $this->lazyload('fancybox')) && ($deps[] = 'fancybox') && ($deps[] = 'fancybox-buttons') && ($deps[] = 'fancybox-thumbs') && ($deps[] = 'fancybox-media') && ($deps[] = 'mousewheel'))
				{
					wp_enqueue_script('fancybox', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js'), array('jquery'), NULL, (boolean)$this->options['scripts_in_footer']);
					wp_enqueue_script('fancybox-buttons', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js'), array('jquery', 'fancybox'), NULL, (boolean)$this->options['scripts_in_footer']);
					wp_enqueue_script('fancybox-thumbs', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js'), array('jquery', 'fancybox'), NULL, (boolean)$this->options['scripts_in_footer']);
					wp_enqueue_script('fancybox-media', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js'), array('jquery', 'fancybox'), NULL, (boolean)$this->options['scripts_in_footer']);
					wp_enqueue_script('mousewheel', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.3/jquery.mousewheel.min.js'), array('jquery', 'fancybox'), NULL, (boolean)$this->options['scripts_in_footer']);
				}
				if(($this->options['recaptcha_enable'] || $this->lazyload('recaptcha')) && $this->options['recaptcha_public_key'] && $this->options['recaptcha_private_key'] && ($deps[] = 'recaptcha'))
					wp_enqueue_script('recaptcha', set_url_scheme('//www.google.com/recaptcha/api/js/recaptcha_ajax.js'), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if($this->options['fonts_location'] && stripos($this->options['fonts_location'], '</script>') !== FALSE)
					if(preg_match('/\bsrc\s*\=\s*(?P<quote>[\'"])(?P<src>.+?)(?P=quote)/i', $this->options['fonts_location'], $fonts_location))
						wp_enqueue_script('fonts', set_url_scheme($fonts_location['src']), array(), NULL, (boolean)$this->options['scripts_in_footer']);

				if(is_singular() && comments_open() && get_option('thread_comments') && ($deps[] = 'comment-reply')) // Threaded comment replies.
					wp_enqueue_script('comment-reply', includes_url('/js/comment-reply.min.js'), array(), get_bloginfo('version'), (boolean)$this->options['scripts_in_footer']);

				wp_enqueue_script(__NAMESPACE__, $this->url('/client-s/js/theme.min.js'), $deps, $this->version, (boolean)$this->options['scripts_in_footer']);
			}

			public function seo_site()
			{
				if($this->options['site_seo_name'])
					$site = $this->options['site_seo_name'];

				else $site = get_bloginfo('name');

				return apply_filters(__METHOD__, esc_html($site), get_defined_vars());
			}

			public function seo_title($default_with_site_suffix = TRUE)
			{
				if(is_front_page() && $this->options['site_seo_title'])
					$title = $this->options['site_seo_title'];

				else if(is_singular() && ($custom_seo_title = $this->post_custom('seo_title')))
					$title = $custom_seo_title; // Use the custom value if available.

				else $title = // Use default title provided by WordPress®.
					trim(wp_title($this->options['site_seo_title_sep'], FALSE, 'right').
					     (($default_with_site_suffix) ? $this->seo_site() : ''),
					     " \t\n\r\0\x0B".$this->options['site_seo_title_sep']);

				if(!$title) $title = $this->seo_site(); // Always need something.

				return apply_filters(__METHOD__, esc_html($title), get_defined_vars());
			}

			public function seo_keywords()
			{
				if(is_front_page() && $this->options['site_seo_keywords'])
					$keywords = $this->options['site_seo_keywords'];

				else if(is_singular() && ($custom_seo_keywords = $this->post_custom('seo_keywords')))
					$keywords = $custom_seo_keywords; // Use the custom value if available.

				else if(($description = $this->seo_description())) // Automatically.
				{
					$keywords = preg_split('/\W/', strtolower($description), NULL, PREG_SPLIT_NO_EMPTY);
					array_pop($keywords); // Drop last keyword; may have `...`; e.g. incomplete.

					include dirname(__FILE__).'/common-words.php'; // `$common_words` array.
					$keywords = (!empty($common_words)) ? array_diff($keywords, $common_words) : $keywords;

					$keywords = implode(', ', array_slice(array_unique($keywords), 0, 15));
				}
				return apply_filters(__METHOD__, (!empty($keywords)) ? esc_html($keywords) : '', get_defined_vars());
			}

			public function seo_description()
			{
				if(is_front_page() && $this->options['site_seo_description'])
					$description = $this->options['site_seo_description'];

				else if(is_front_page()) $description = $this->clip(get_bloginfo('description'), 100, TRUE, TRUE);

				else if(is_singular() && ($custom_seo_description = $this->post_custom('seo_description')))
					$description = $custom_seo_description; // Custom value; when available.

				else if(is_blog_page()) // After singular check above for custom value.
					$description = __('Latest posts; in chronological order.', $this->text_domain);

				else if(is_singular()) $description = $this->excerpt(100, FALSE); // Automatic clip.

				else if(is_tag()) // Tag archive; this is a list of all posts w/ a specific tag.
					$description = sprintf(__('Archive view; this is a list of all posts with the "%1$s" tag.', $this->text_domain),
					                       single_term_title('', FALSE));

				else if(is_category()) // Category archive; all posts in a specific category.
					$description = sprintf(__('Archive view; this is a list of all posts in the "%1$s" category.', $this->text_domain),
					                       single_term_title('', FALSE));

				else if(is_tax()) // Taxonomy archive; all posts in a specific taxonomy.
					$description = sprintf(__('Archive view; this is a list of all posts in the "%1$s" taxonomy.', $this->text_domain),
					                       single_term_title('', FALSE));

				else if(is_post_type_archive()) // Post type archive.
					$description = sprintf(__('Archive view; this is a list of all %1$s.', $this->text_domain),
					                       strtolower(post_type_archive_title('', FALSE)));

				else if(is_search() && !empty($_REQUEST['s'])) // Search w/ query.
					$description = sprintf(__('Your search for "%1$s" returned %2$s results.', $this->text_domain),
					                       get_search_query(), wp_query()->found_posts);

				else if(is_search() && empty($_REQUEST['s'])) // Search w/ empty query.
					$description = __('All content; listed in chronological order.', $this->text_domain);

				else if(is_year() && get_query_var('m')) // Yearly archive; w/ `m` query var.
					$description = sprintf(__('Archive view; now showing all posts in the year of %1$s.', $this->text_domain),
					                       substr(get_query_var('m'), 0, 4));

				else if(is_year() && get_query_var('year')) // Yearly archive; w/ `year` query var.
					$description = sprintf(__('Archive view; now showing all posts in the year of %1$s.', $this->text_domain),
					                       get_query_var('year'));

				else if(is_month()) // Monthly archive; all posts in a specific month.
					$description = sprintf(__('Archive view; now showing all posts in the month of %1$s.', $this->text_domain),
					                       trim(single_month_title(' ', FALSE)));

				else if(is_day() && get_query_var('m')) // Daily archive; w/ `m` query var.
					$description = sprintf(__('Archive view; now showing all posts on day %1$s of %2$s.', $this->text_domain),
					                       substr(get_query_var('m'), -2), trim(single_month_title(' ', FALSE)));

				else if(is_day() && get_query_var('day')) // Daily archive; w/ `day` query var.
					$description = sprintf(__('Archive view; now showing all posts on day %1$s of %2$s.', $this->text_domain),
					                       get_query_var('day'), trim(single_month_title(' ', FALSE)));

				else if(is_author()) // Author archive; all posts by a specific author.
					$description = sprintf(__('Archive view; this is a list of all posts by %1$s.', $this->text_domain),
					                       get_queried_object()->display_name);

				else if(is_archive()) // Any other type of archive we did not cover above.
					$description = sprintf(__('Current view: this is a list of all posts in the "%1$s" archive.', $this->text_domain),
					                       single_term_title('', FALSE));

				if(empty($description)) $description = $this->clip(get_bloginfo('description'), 100, TRUE, TRUE);

				return apply_filters(__METHOD__, esc_html($description), get_defined_vars());
			}

			public function seo_robots()
			{
				$is_public = (boolean)get_option('blog_public');

				if(!$is_public || isset($_REQUEST['replytocom'])
				   || is_blog_page() || is_search() || is_archive()
				) $robots = (!$is_public) ? 'noindex,nofollow' : 'noindex';

				else if(is_singular() && ($custom_seo_robots = $this->post_custom('seo_robots')))
					$robots = $custom_seo_robots; // Use the custom value if available.

				else if($this->options['site_seo_robots']) // Custom default value?
					$robots = $this->options['site_seo_robots'];

				if(empty($robots)) $robots = 'index,follow'; // Default value.

				return apply_filters(__METHOD__, esc_html($robots), get_defined_vars());
			}

			public function open_graph_type()
			{
				if(is_front_page()) $type = 'website';

				else $type = 'article'; // Other pages (generic value).

				return apply_filters(__METHOD__, esc_html($type), get_defined_vars());
			}

			public function open_graph_url()
			{
				if(is_front_page()) $url = home_url('/');
				else if(is_singular()) $url = get_permalink();
				else if(is_search()) $url = get_search_link();

				if(empty($url)) // Fall back on this auto-detection here.
					$url = set_url_scheme('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

				return apply_filters(__METHOD__, $url, get_defined_vars());
			}

			public function open_graph_image_tags()
			{
				$image_tags = $image_urls = array(); // Initialize.

				if(is_front_page() && $this->options['default_open_graph_img_url'])
					$image_urls[] = $this->options['default_open_graph_img_url'];

				else if(is_singular()) // Collect singular image URLs.
					$image_urls = array_merge($image_urls, $this->open_graph_image_urls());

				if(!$image_urls && $this->options['default_open_graph_img_url'])
					$image_urls[] = $this->options['default_open_graph_img_url'];

				foreach($image_urls as $_image_url)
					$image_tags[] = '<meta property="og:image" content="'.esc_attr($_image_url).'" />';
				unset($_image_url); // Housekeeping.

				$image_tags = $image_tags ? implode("\n", $image_tags) : ''; // Convert to string.

				return apply_filters(__METHOD__, $image_tags, get_defined_vars());
			}

			public function open_graph_image_urls($post_id = NULL)
			{
				if(!($post = get_post($post_id)))
					return array(); // Nothing.

				$image_urls = array(); // Initialize.

				if(has_post_thumbnail($post->ID) // Has featured image?
				   && ($_featured_img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large'))
				) $image_urls[] = $_featured_img[0]; // Featured image URL should always come first!

				if($this->options['md_enable_flavor'] && strpos($post->post_content, '![') !== FALSE
				   && preg_match_all('/(?P<tags>\!\[[^\]]*\]\([^)]+\))/', $post->post_content, $_img)
				) foreach($_img['tags'] as $_img_tag) if(preg_match('/\((?P<value>.*?)[\s")]/', $_img_tag, $_img_tag_src))
					$image_urls[] = $_img_tag_src['value'];

				if(stripos($post->post_content, '<img') !== FALSE
				   && preg_match_all('/(?P<tags>\<img\s+[^>]+\>)/i', $post->post_content, $_img)
				) foreach($_img['tags'] as $_img_tag) if(preg_match('/\s+src\s*=\s*(["\'])(?P<value>.*?)\1/i', $_img_tag, $_img_tag_src))
					$image_urls[] = $_img_tag_src['value'];

				unset($_featured_img, $_img, $_img_tag, $_img_tag_src); // Housekeeping.

				if(!$image_urls && $this->options['default_open_graph_img_url'])
					$image_urls[] = $this->options['default_open_graph_img_url'];

				foreach($image_urls as $_key => &$_image_url)
				{
					$_image_url = wp_specialchars_decode($_image_url, ENT_QUOTES);
					$_image_url = str_ireplace('https://', 'http://', $_image_url);

					if(stripos($_image_url, 'http://') === 0)
						continue; // All set with this one.

					else if(strpos($_image_url, '//') === 0) // Relative scheme?
						$_image_url = set_url_scheme($_image_url, 'http');

					else if(!$_image_url || $_image_url[0] !== '/') // Not absolute?
						unset($image_urls[$_key]); // Ditch this one.

					else $_image_url = site_url($_image_url, 'http');
				}
				unset($_key, $_image_url); // Houskeeping.

				$image_urls = array_unique($image_urls);

				return apply_filters(__METHOD__, $image_urls, get_defined_vars());
			}

			public function is_fluid()
			{
				static $is_fluid; // Static cache.

				if(isset($is_fluid)) return $is_fluid;

				if(!empty($_REQUEST['_fluid'])) $is_fluid = TRUE;

				else $is_fluid = (is_singular() // Singulars only.
				                  && $this->is_true($this->post_custom('fluid')));

				return ($is_fluid = apply_filters(__METHOD__, $is_fluid, get_defined_vars()));
			}

			public function no_admin_bar()
			{
				static $no_admin_bar; // Static cache.

				if(isset($no_admin_bar)) return $no_admin_bar;

				if(!empty($_REQUEST['_no_admin_bar'])) $no_admin_bar = TRUE;

				else if(is_singular() && $this->is_true($this->post_custom('no_admin_bar')))
					$no_admin_bar = TRUE; // Note that `super_clean` does NOT impact this.

				else $no_admin_bar = (!is_admin_bar_showing()); // What WordPress says.

				$no_admin_bar = apply_filters(__METHOD__, $no_admin_bar, get_defined_vars());

				show_admin_bar(($no_admin_bar) ? FALSE : TRUE); // Tell WordPress.

				return $no_admin_bar; // Return after telling WordPress.
			}

			public function no_navbar()
			{
				static $no_navbar; // Static cache.

				if(isset($no_navbar)) return $no_navbar;

				if(!empty($_REQUEST['_no_navbar'])) $no_navbar = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_navbar = TRUE;

				else $no_navbar = (is_singular() // Singulars only.
				                   && ($this->is_true($this->post_custom('no_navbar'))
				                       || $this->is_true($this->post_custom('super_clean'))));

				return ($no_navbar = apply_filters(__METHOD__, $no_navbar, get_defined_vars()));
			}

			public function no_panel()
			{
				static $no_panel; // Static cache.

				if(isset($no_panel)) return $no_panel;

				if(!empty($_REQUEST['_no_panel'])) $no_panel = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_panel = TRUE;

				else $no_panel = (is_singular() // Singulars only.
				                  && ($this->is_true($this->post_custom('no_panel'))
				                      || $this->is_true($this->post_custom('super_clean'))));

				return ($no_panel = apply_filters(__METHOD__, $no_panel, get_defined_vars()));
			}

			public function no_topper()
			{
				static $no_topper; // Static cache.

				if(isset($no_topper)) return $no_topper;

				if(!empty($_REQUEST['_no_topper'])) $no_topper = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_topper = TRUE;

				else if(!is_single()) $no_topper = TRUE;

				else $no_topper = (is_singular() // Singulars only.
				                   && ($this->is_true($this->post_custom('no_topper'))
				                       || $this->is_true($this->post_custom('super_clean'))));

				return ($no_topper = apply_filters(__METHOD__, $no_topper, get_defined_vars()));
			}

			public function no_topper_shortlink()
			{
				static $no_topper_shortlink; // Static cache.

				if(isset($no_topper_shortlink)) return $no_topper_shortlink;

				if(!empty($_REQUEST['_no_topper_shortlink'])) $no_topper_shortlink = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_topper_shortlink = TRUE;

				else if(!is_single()) $no_topper_shortlink = TRUE;

				else $no_topper_shortlink = (is_singular() // Singulars only.
				                             && ($this->is_true($this->post_custom('no_topper_shortlink'))
				                                 || $this->is_true($this->post_custom('super_clean'))));

				return ($no_topper_shortlink = apply_filters(__METHOD__, $no_topper_shortlink, get_defined_vars()));
			}

			public function no_topper_date()
			{
				static $no_topper_date; // Static cache.

				if(isset($no_topper_date)) return $no_topper_date;

				if(!empty($_REQUEST['_no_topper_date'])) $no_topper_date = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_topper_date = TRUE;

				else if(!is_single()) $no_topper_date = TRUE;

				else $no_topper_date = (is_singular() // Singulars only.
				                        && ($this->is_true($this->post_custom('no_topper_date'))
				                            || $this->is_true($this->post_custom('super_clean'))
				                            || get_post_type() === 'product'));

				return ($no_topper_date = apply_filters(__METHOD__, $no_topper_date, get_defined_vars()));
			}

			public function no_sidebar()
			{
				static $no_sidebar; // Static cache.

				if(isset($no_sidebar)) return $no_sidebar;

				if($this->is_fluid()) $no_sidebar = TRUE; // Conflicting.

				else if(!empty($_REQUEST['_no_sidebar'])) $no_sidebar = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_sidebar = TRUE;

				else if(!is_active_sidebar('primary-rs')) $no_sidebar = TRUE;

				else $no_sidebar = (is_singular() // Singulars only.
				                    && ($this->is_true($this->post_custom('no_sidebar'))
				                        || $this->is_true($this->post_custom('super_clean'))));

				return ($no_sidebar = apply_filters(__METHOD__, $no_sidebar, get_defined_vars()));
			}

			public function no_sharebar()
			{
				static $no_sharebar; // Static cache.

				if(isset($no_sharebar)) return $no_sharebar;

				if(!$this->options['sharebar_enable'])
					$no_sharebar = TRUE; // Disabled completely.

				else if($this->is_fluid()) $no_sharebar = TRUE; // Conflicting.

				else if(!empty($_REQUEST['_no_sharebar'])) $no_sharebar = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_sharebar = TRUE;

				else $no_sharebar = (is_singular() // Singulars only.
				                     && ($this->is_true($this->post_custom('no_sharebar'))
				                         || $this->is_true($this->post_custom('super_clean'))));

				return ($no_sharebar = apply_filters(__METHOD__, $no_sharebar, get_defined_vars()));
			}

			public function no_footbar()
			{
				static $no_footbar; // Static cache.

				if(isset($no_footbar)) return $no_footbar;

				if(!empty($_REQUEST['_no_footbar'])) $no_footbar = TRUE;

				else if(!empty($_REQUEST['_super_clean'])) $no_footbar = TRUE;

				else if(!is_active_sidebar('primary-fb')) $no_footbar = TRUE;

				else $no_footbar = (is_singular() // Singulars only.
				                    && ($this->is_true($this->post_custom('no_footbar'))
				                        || $this->is_true($this->post_custom('super_clean'))));

				return ($no_footbar = apply_filters(__METHOD__, $no_footbar, get_defined_vars()));
			}

			public function header()
			{
				$header = ''; // Initialize.

				if($this->options['site_custom_header_elements'])
					$header .= $this->shortcode_eval($this->options['site_custom_header_elements'])."\n";

				if(is_blog_page() && get_option('show_on_front') === 'page' && ($page = get_option('page_for_posts'))
				   && ($page = get_post($page)) && $page->post_content // Has static content for this page?
				) $header .= apply_filters('the_content', $page->post_content);

				else if(is_singular() // Allow for raw HTML in the header.
				        && ($custom_header_elements = $this->post_custom('header_elements'))
				) $header .= $this->shortcode_eval($custom_header_elements);

				else if(post_count_gt0() && is_front_page() && is_blog_page() && $this->options['site_custom_front_page_blog_header_elements'])
					$header .= $this->shortcode_eval($this->options['site_custom_front_page_blog_header_elements'])."\n";

				else if((post_count_gt0() && (is_blog_page() || is_search() || is_archive()))
				        || (is_singular() && $this->post_custom('header_title'))
				) $header .= '<div class="jumbotron box-shadow'.(is_front_page() && is_blog_page() ? ' text-center' : '').'">'.
				             ' <div class="container'.(($this->is_fluid()) ? '-fluid' : '').'">'.
				             '    <h2>'.$this->header_title().'</h2>'.
				             '    <p>'.$this->header_description().'</p>'.
				             ' </div>'.
				             '</div>';
				return apply_filters(__METHOD__, $header, get_defined_vars());
			}

			public function header_title_prefix()
			{
				if(is_blog_page()) // Blog; e.g. latest posts.
					$prefix = '<a href="'.esc_attr(get_feed_link()).'" target="_blank"><i class="fa fa-rss"></i></a>';

				else if(is_search()) // Search results.
					$prefix = '<i class="fa fa-search"></i>';

				else if(is_tag()) // Tag.
					$prefix = '<i class="fa fa-tag"></i> '.__('Tagged:', $this->text_domain);

				else if(is_category()) // Category.
					$prefix = '<i class="fa fa-folder-open"></i> '.__('Category:', $this->text_domain);

				else if(is_tax()) // Custom taxonomy.
					$prefix = '<i class="fa fa-folder-open"></i> '.get_taxonomy(get_query_var('taxonomy'))->labels->singular_name.':';

				else if(is_post_type_archive()) // Post Type archive.
					$prefix = '<i class="fa fa-folder-open"></i> '.__('Archive:', $this->text_domain);

				else if(is_year()) // By year.
					$prefix = '<i class="fa fa-folder-open"></i> '.__('Yearly Archive:', $this->text_domain);

				else if(is_month()) // A specific month.
					$prefix = '<i class="fa fa-folder-open"></i> '.__('Monthly Archive:', $this->text_domain);

				else if(is_day()) // A specific day.
					$prefix = '<i class="fa fa-folder-open"></i> '.__('Daily Archive:', $this->text_domain);

				else if(is_author()) // Author.
					$prefix = '<i class="fa fa-user"></i> '.__('Posts by:', $this->text_domain);

				else if(is_archive()) // Any other archive view.
					$prefix = '<i class="fa fa-archive"></i> '.__('Archive:', $this->text_domain);

				if(!empty($prefix)) // If we have a prefix, let's wrap it up.
					$prefix = '<span class="prefix">'.$prefix.'</span>&nbsp;&nbsp;';

				return apply_filters(__METHOD__, (!empty($prefix)) ? $prefix : '', get_defined_vars());
			}

			public function header_title()
			{
				if(is_singular() && ($custom_header_title = $this->post_custom('header_title')))
					$title = $this->shortcode_eval($custom_header_title);

				else if(is_tax()) // Cut this short; use only the term title.
					$title = $this->header_title_prefix().single_term_title('', FALSE);

				else $title = $this->header_title_prefix().$this->seo_title(FALSE);

				return apply_filters(__METHOD__, $title, get_defined_vars());
			}

			public function header_description()
			{
				if(is_singular() && ($custom_header_description = $this->post_custom('header_description')))
					$description = $this->shortcode_eval($custom_header_description);

				else $description = $this->seo_description(); // Default value.

				return apply_filters(__METHOD__, $description, get_defined_vars());
			}

			public function footer()
			{
				$footer = ''; // Initialize.

				if($this->options['site_custom_footer_elements'])
					$footer .= $this->shortcode_eval($this->options['site_custom_footer_elements'])."\n";

				if(is_singular() // Allow for raw HTML in the footer.
				   && ($custom_footer_elements = $this->post_custom('footer_elements'))
				) $footer .= $this->shortcode_eval($custom_footer_elements);

				return apply_filters(__METHOD__, $footer, get_defined_vars());
			}

			public function custom_wp_head_elements()
			{
				$elements = ''; // Initialize.

				if($this->options['site_custom_wp_head_elements'])
					$elements .= $this->shortcode_eval($this->options['site_custom_wp_head_elements'])."\n";

				if(is_singular() && ($custom_wp_head_elements = $this->post_custom('wp_head_elements')))
					$elements .= $this->shortcode_eval($custom_wp_head_elements)."\n"; // Custom elements; if available.

				echo apply_filters(__METHOD__, $elements, get_defined_vars());
			}

			public function custom_wp_footer_elements()
			{
				$elements = ''; // Initialize.

				if($this->options['site_custom_wp_footer_elements'])
					$elements .= $this->shortcode_eval($this->options['site_custom_wp_footer_elements'])."\n";

				if(is_singular() && ($custom_wp_footer_elements = $this->post_custom('wp_footer_elements')))
					$elements .= $this->shortcode_eval($custom_wp_footer_elements)."\n"; // Custom elements; if available.

				echo apply_filters(__METHOD__, $elements, get_defined_vars());
			}

			public function registration_url()
			{
				$url = $this->options['navbar_login_registration_url']; // By site owner.
				if(!$url) $url = wp_registration_url(); // Default location.

				return apply_filters(__METHOD__, $url, get_defined_vars());
			}

			public function redirect_to($logout = FALSE)
			{
				$to = ''; // Initialize redirection location.

				if($logout && !empty($_SERVER['HTTP_ORIGIN']))
					$to = (string)$_SERVER['HTTP_ORIGIN']; // AJAX origin.

				$to = (!empty($_REQUEST['redirect_to'])) ? (string)$_REQUEST['redirect_to'] : '';

				if(!$to) $to = ($logout) ? $this->options['navbar_logout_redirect_to']
					: $this->options['navbar_login_redirect_to'];

				if($to === '%%previous%%') $to = (string)$_SERVER['REQUEST_URI'];

				if(strpos($to, '/wp-load.php') !== FALSE) $to = home_url('/');

				return apply_filters(__METHOD__, $to, get_defined_vars());
			}

			public function title($hilite_search_terms = NULL)
			{
				$title = esc_html(get_the_title());

				if(is_attachment()) // Prepend title.
					$title = __('Attachment:', $this->text_domain).' '.$title;

				if(!isset($hilite_search_terms) && is_search())
					$hilite_search_terms = TRUE; // Auto-enable.

				if($hilite_search_terms && is_search())
					$title = $this->hilite_search_terms($title);

				return apply_filters(__METHOD__, $title, get_defined_vars());
			}

			public function content()
			{
				ob_start(); // Output buffer.
				the_content(); // Get the content :-)
				$content = ob_get_clean(); // Collect buffer.

				if($this->options['site_custom_content_footer_elements'])
					$content .= $this->shortcode_eval($this->options['site_custom_content_footer_elements'])."\n";

				if(is_singular() && ($custom_content_footer_elements = $this->post_custom('content_footer_elements')))
					$content .= $this->shortcode_eval($custom_content_footer_elements)."\n"; // Custom elements; if available.

				return apply_filters(__METHOD__, $content, get_defined_vars());
			}

			public function excerpt_($excerpt /* MD-compatible `wp_trim_excerpt()`. */)
			{
				if(!$excerpt && ($post = get_post()))
					$excerpt = $this->excerpt(NULL, NULL, FALSE);

				return apply_filters(__METHOD__, $excerpt, get_defined_vars());
			}

			public function excerpt($clip = NULL, $hilite_search_terms = NULL, $has_excerpt = NULL)
			{
				ob_start(); // Output buffer.

				if($has_excerpt !== FALSE && ($using_custom_excerpt = has_excerpt()))
					the_excerpt(); // Use custom excerpt in this case.

				else if(empty($GLOBALS['more']) && ($post = get_post()) && strpos($post->post_content, '<!--more'))
					the_content(''); // Get teaser; w/o more link.

				else // Build an excerpt from content automatically.
				{
					the_content(''); // Full content (enable clipping).
					if(!isset($clip)) $clip = TRUE; // Auto-enable.
				}
				$excerpt = ob_get_clean(); // Collect buffer.

				if(empty($using_custom_excerpt)) // Strip titles.
					$excerpt = $this->strip_titles($excerpt);

				if(!isset($clip) && is_search()) $clip = TRUE; // Auto-clip?

				if($clip) // If enabled, we clip; and we may also hilite search terms.
				{
					if(is_integer($clip)) // Clipping a specific number of chars?
						$clip_chars = $clip; // Use explicit value; ignore default value.
					else $clip_chars = (integer)$this->options['default_excerpt_clip_chars'];

					$excerpt = $this->clip($excerpt, $clip_chars, FALSE, TRUE);

					// Only highlight when clipping; else we risk HTML corruption.

					if(!isset($hilite_search_terms) && is_search())
						$hilite_search_terms = TRUE; // Auto-enable.

					if($hilite_search_terms && is_search())
						$excerpt = $this->hilite_search_terms($excerpt);
				}
				return apply_filters(__METHOD__, $excerpt, get_defined_vars());
			}

			public function get_summary($post, $clip = NULL)
			{
				if(!($post instanceof \WP_Post) && !($post = get_post($post)))
					return ''; // Not possible.

				if($post->post_excerpt) // Custom summary?
				{
					$using_custom_excerpt = TRUE;
					$summary              = $post->post_excerpt;
				}
				else if(strpos($post->post_content, '<!--more'))
					$summary = strstr($post->post_content, '<!--more', TRUE);

				else // Build summary from content automatically.
				{
					$summary = $post->post_content; // Full content clip.
					if(!isset($clip)) $clip = TRUE; // Auto-enable.
				}
				$summary = apply_filters(__NAMESPACE__.'_get_the_summary', $summary);

				if(empty($using_custom_excerpt))
					$summary = $this->strip_titles($summary);

				if($clip) // If enabled, we clip to a certain number of chars.
				{
					if(is_integer($clip)) // Clipping a specific number of chars?
						$clip_chars = $clip; // Use explicit value; ignore default value.
					else $clip_chars = (integer)$this->options['default_excerpt_clip_chars'];

					$summary = $this->clip($summary, $clip_chars, FALSE, TRUE);
				}
				return apply_filters(__METHOD__, $summary, get_defined_vars());
			}

			public function read_more()
			{
				if(is_search()) // Search results are handled differently here.
					$read_more = '<a href="'.esc_attr(get_permalink().'#!s='.urlencode(get_search_query())).'" target="_blank" class="btn btn-info">'.
					             $this->options['excerpt_read_more_label_s'].' <i class="fa fa-external-link"></i>'.
					             '</a>';
				else // Handle this normally; e.g. provide a read more link with the default label in use.
					$read_more = '<a href="'.esc_attr(get_permalink().'#more-'.get_the_ID()).'" class="btn btn-primary">'.
					             $this->options['excerpt_read_more_label'].' <i class="fa fa-arrow-circle-right"></i>'.
					             '</a>';
				return apply_filters(__METHOD__, $read_more, get_defined_vars());
			}

			public function format_icon_class()
			{
				$classes = array
				(
					'aside'   => 'fa-file-text',
					'quote'   => 'fa-quote-left',
					'audio'   => 'fa-music',
					'video'   => 'fa-film',
					'gallery' => 'fa-eye',
					'image'   => 'fa-picture-o',
					'link'    => 'fa-link',
					'chat'    => 'fa-comments-o',
					'status'  => 'fa-comment-o',
				);
				$type    = get_post_type();
				$format  = get_post_format();

				if(is_sticky()) $class = 'fa-star';

				else if($format && !empty($classes[$format]))
					$class = $classes[$format]; // Specific post format icon.

				else if($type === 'product') // WP Products compatibility.
				{
					if(has_term('wp-themes', 'product_category'))
						$class = 'fa-picture-o'; // Custom icon class.

					else if(has_term('wp-plugins', 'product_category'))
						$class = 'fa-puzzle-piece'; // Custom icon class.

					else $class = 'fa-barcode'; // Other products.
				}
				else $class = ($type === 'page') ? 'fa-bookmark' : 'fa-thumb-tack';

				return apply_filters(__METHOD__, $class, get_defined_vars());
			}

			public function fatcow_icon($args = array())
			{
				if(is_string($args)) $args = array('name' => $args);

				$defaults = array('name'  => '', 'size' => 32, 'id' => '',
				                  'title' => '', 'class' => 'fatcow-icon', 'style' => '', 'attr' => '');
				$args     = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args     = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				$src = '//cdnjs.cloudflare.com/ajax/libs/fatcow-icons/20130425/FatCow_Icons'.
				       urlencode($args['size']).'x'.urlencode($args['size']).'/'.urlencode($args['name']);

				$icon = '<img src="'.esc_attr($src).'"'. // URL to icon image.
				        ' id="'.esc_attr($args['id']).'" title="'.esc_attr($args['title']).'"'.
				        ' class="'.esc_attr($args['class']).'" style="'.esc_attr($args['style']).'"'.
				        ' width="'.esc_attr($args['size']).'" height="'.esc_attr($args['size']).'"'.
				        ' alt=""'.($args['attr'] ? ' '.trim($args['attr']) : '').' />';

				return apply_filters(__METHOD__, $icon, get_defined_vars());
			}

			public function avatar($args = array())
			{
				if(is_string($args)) $args = array('id_or_email' => $args);

				$defaults = array('id_or_email' => get_current_user_id(), 'size' => 48,
				                  'id'          => '', 'title' => '', 'class' => 'avatar', 'style' => '', 'attr' => '');
				$args     = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args     = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				$get_avatar = get_avatar($args['id_or_email'], $args['size']);

				if(preg_match('/\s+src\s*\=\s*(["\'])(?P<src>.+?)\\1/i', $get_avatar, $avatar_parts))
					$avatar = '<img src="'.esc_attr($avatar_parts['src']).'"'. // URL to avatar image.
					          ' id="'.esc_attr($args['id']).'" title="'.esc_attr($args['title']).'"'.
					          ' class="'.esc_attr($args['class']).'" style="'.esc_attr($args['style']).'"'.
					          ' width="'.esc_attr($args['size']).'" height="'.esc_attr($args['size']).'"'.
					          ' alt=""'.($args['attr'] ? ' '.trim($args['attr']) : '').' />';

				return apply_filters(__METHOD__, !empty($avatar) ? $avatar : '', get_defined_vars());
			}

			public function calendar_date($args = array())
			{
				$date     = ''; // Initialize.
				$defaults = array('class'              => '', 'link_up' => TRUE,
				                  'tooltip_date_class' => '', 'tooltip_class' => '', 'display_type' => 'date');
				$args     = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args     = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				if($args['class']) $args['class'] = ' '.$args['class']; // Whitespace.
				if($args['tooltip_class']) $args['tooltip_class'] = ' '.$args['tooltip_class'];
				if($args['tooltip_date_class']) $args['tooltip_date_class'] = ' '.$args['tooltip_date_class'];

				$date .= '<div class="calendar-date'.esc_attr($args['class']).'"'.
				         ' data-toggle="tooltip" data-placement="left" data-html="true"'.
				         ' title="'.esc_attr('<div class="calendar-date-tooltip'.esc_attr($args['tooltip_class']).'">'.
				                             '<span>'.__('Published', $this->text_domain).'</span><br />'.
				                             '<strong>'.get_the_date().'</strong><hr class="em-margin translucent dotted-border" />'.
				                             '<span>'.__('Last Modified', $this->text_domain).'</span><br />'.
				                             '<strong>'.get_the_modified_date().'</strong>'.
				                             '</div>').'">'."\n";

				$date .= '<div class="fa fa-stack">'."\n";
				$date .= '<i class="fa fa-calendar-o fa-stack-2x"></i>'."\n";

				$display_func = ($args['display_type'] === 'modified_date')
					? 'get_the_modified_date' : 'get_the_date';

				if($args['link_up'] && $args['display_type'] === 'date' && get_post_type() === 'post')
				{
					$date .= '<div class="date font-body'.esc_attr($args['tooltip_date_class']).'">'."\n";
					$date .= '<a class="day" href="'.esc_attr(get_day_link($display_func('Y'), $display_func('m'), get_the_date('d'))).'">'.esc_html($display_func('d')).'</a><br />'."\n";
					$date .= '<a class="month" href="'.esc_attr(get_month_link($display_func('Y'), $display_func('m'))).'">'.esc_html($display_func('M')).'</a>,'."\n";
					$date .= '<a class="year" href="'.esc_attr(get_year_link($display_func('Y'))).'">'.esc_html($display_func('Y')).'</a>'."\n";
					$date .= '</div>'."\n";
				}
				else // Dated archives may NOT include this date; even if `$args` wants them; we do NOT link-up in this case.
				{
					$date .= '<div class="date font-body'.esc_attr($args['tooltip_date_class']).'">'."\n";
					$date .= '<span class="day">'.esc_html($display_func('d')).'</span><br />'."\n";
					$date .= '<span class="month">'.esc_html($display_func('M')).'</span>,'."\n";
					$date .= '<span class="year">'.esc_html($display_func('Y')).'</span>'."\n";
					$date .= '</div>'."\n";
				}
				$date .= '</div>'."\n";
				$date .= '</div>'."\n";

				return apply_filters(__METHOD__, $date, get_defined_vars());
			}

			public function taxonomies($args = array())
			{
				$tax_term_links = array(); // Initialize.
				$defaults       = array('before'          => '', 'after' => '',
				                        'sep'             => '<span class="spacer"></span>',
				                        'category_prefix' => __('in: ', $this->text_domain), 'format_prefix' => __('format: ', $this->text_domain),
				                        'template'        => '<em>%1$s</em> %2$s');
				$args           = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args           = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				foreach(get_object_taxonomies($post = get_post()) as $_tax)
				{
					$_tax_obj = get_taxonomy($_tax);

					if(!$_tax_obj->public || !$_tax_obj->show_in_nav_menus)
						continue; // Do NOT display hidden taxonomies.

					if(($_tax_terms = get_object_term_cache($post->ID, $_tax)) === FALSE)
						$_tax_terms = wp_get_object_terms($post->ID, $_tax, $_tax_obj['args']);

					$_tax_term_links = array(); // Initialize/reset links.
					foreach($_tax_terms as $_tax_term) // Generate taxonomy/term links.
						$_tax_term_links[] = '<a href="'.esc_attr(get_term_link($_tax_term)).'" rel="nofollow">'.esc_html($_tax_term->name).'</a>';
					if(!$_tax_term_links) continue; // Nothing to do. There are no terms in this taxonomy.

					if($_tax === 'category') // Categories; w/ a folder icon.
						$_tax_icon_label_name = $args['category_prefix']. // Configurable prefix; e.g. `in `.
						                        '<i class="fa fa-folder-open" title="'.esc_attr($_tax_obj->labels->name).'"></i>';

					else if($_tax === 'post_tag') // Post tags; w/ tags icon.
						$_tax_icon_label_name = '<i class="fa fa-tags" title="'.esc_attr($_tax_obj->labels->name).'"></i>';

					else if($_tax === 'post_format') // Post format w/ format icon class.
						$_tax_icon_label_name = $args['format_prefix']. // Configurable prefix; e.g. `as `.
						                        '<i class="fa '.esc_attr($this->format_icon_class()).'" title="'.esc_attr($_tax_obj->labels->name).'"></i>';

					else if(strpos($_tax, 'tag') !== FALSE && !$_tax_obj->hierarchical) // Like a post tag (of some sort).
						$_tax_icon_label_name = '<i class="fa fa-tags" title="'.esc_attr($_tax_obj->labels->name).'"></i> '.esc_html($_tax_obj->labels->name).':';

					else $_tax_icon_label_name = // Anything else includes a label too.
						'<i class="fa fa-folder" title="'.esc_attr($_tax_obj->labels->name).'"></i> '.esc_html($_tax_obj->labels->name).':';

					$_tax_icon_label_name = apply_filters(__METHOD__.'__icon_label_name', $_tax_icon_label_name, get_defined_vars());

					$tax_term_links[$_tax] = sprintf($args['template'], $_tax_icon_label_name, implode(', ', $_tax_term_links));
				}
				unset($_tax, $_tax_obj, $_tax_terms, $_tax_term, $_tax_term_links, $_tax_icon_label_name); // Housekeeping.

				$taxonomies = ($tax_term_links) ? $args['before'].implode($args['sep'], $tax_term_links).$args['after'] : '';

				return apply_filters(__METHOD__, $taxonomies, get_defined_vars());
			}

			public function link_pages($args = array())
			{
				if(empty($GLOBALS['page'])
				   || empty($GLOBALS['multipage']) || empty($GLOBALS['numpages'])
				) return ''; // Nothing to do here.

				$defaults = array(
					'before'      => '<div class="link-pages">', 'after' => '</div>',
					'link_before' => '', 'link' => '%', 'link_after' => '');
				$args     = array_merge($defaults, $args); // Merge current args w/ defaults.
				$args     = apply_filters(__METHOD__.'__args', $args, get_defined_vars());

				$link_pages = $args['before'];

				$link_pages .= '<div class="col-md-4 font-170">';
				$link_pages .= '<span class="label label-primary">';
				$link_pages .= sprintf(__('Page %1$s of %2$s', $this->text_domain),
				                       $GLOBALS['page'], $GLOBALS['numpages']);
				$link_pages .= '</span>';
				$link_pages .= '</div>';

				$link_pages .= '<div class="col-md-8 text-right">';
				$link_pages .= '<ul class="pagination no-margin">';

				if($GLOBALS['page'] === 1)
					$link_pages .= '<li class="disabled"><a href="#">&laquo;</a></li>';
				else $link_pages .= '<li>'._wp_link_page($GLOBALS['page'] - 1).'&laquo;</a></li>';

				for($_page = 1; $_page <= $GLOBALS['numpages']; $_page++)
				{
					if($_page === $GLOBALS['page'])
						$link_pages .= '<li class="active">'._wp_link_page($_page);
					else $link_pages .= '<li>'._wp_link_page($_page);
					$link_pages .= $args['link_before'].str_replace('%', $_page, $args['link']).$args['link_after'];
					$link_pages .= '</a></li>';
				}
				unset($_page); // Just a little housekeeping.

				if($GLOBALS['page'] === $GLOBALS['numpages'])
					$link_pages .= '<li class="disabled"><a href="#">&raquo;</a></li>';
				else $link_pages .= '<li>'._wp_link_page($GLOBALS['page'] + 1).'&raquo;</a></li>';

				$link_pages .= '</ul>';
				$link_pages .= '</div>';

				$link_pages .= $args['after'];

				return apply_filters(__METHOD__, $link_pages, get_defined_vars());
			}

			public function cancel_comment_reply($anchor)
			{
				$anchor = preg_replace_callback('/\<a\s+[^>]*\>/i', function ($m)
				{
					return preg_replace('/\s+(?:title|class)\=([\'"]).*?\\1/i', '', $m[0]);
					// Strip existing title|class attributes; BEFORE adding new ones.
				}, (string)$anchor);
				$anchor = str_replace('<a', '<a title="'.esc_attr(__('Cancel Reply', $this->text_domain)).'"'.
				                            ' class="no-text-decor"', $anchor);

				return apply_filters(__METHOD__, $anchor, get_defined_vars());
			}

			public function allowed_tags()
			{
				$tags = // Basic HTML (allowed tags/attributes).

					'<p class="text-primary font-120">'.
					'<strong>'.__('Basic HTML Works Here', $this->text_domain).'</strong><br />'.
					'<span class="text-small-caps">'.__('allowed HTML tags/attributes', $this->text_domain).'</span>'.
					'</p><hr class="no-margin" />'.

					'<pre class="code no-b-margin">'.
					'<code class="pre language-html text-wrap">'.
					allowed_tags(). // List of allowed HTML tags/attributes.
					'</code></pre>'; // Syntax highlighting; if enabled.

				return apply_filters(__METHOD__, $tags, get_defined_vars());
			}

			public function md_syntax()
			{
				$syntax = // Markdown syntax (with examples).

					'<p class="text-primary font-120">'.
					'<strong>'.__('Markdown Works Here', $this->text_domain).'</strong><br />'.
					'<span class="text-small-caps">'.__('a few markdown examples', $this->text_domain).'</span>'.
					'</p><hr class="no-margin" />'.

					'<h5># '.__('Heading', $this->text_domain).'</h5>'.

					'<p>**<strong>'.__('Bold Text', $this->text_domain).'</strong>**'.
					'<span class="spacer"></span>*<em>'.__('Italic Text', $this->text_domain).'</em>*</p>'.

					'<p><tt>---</tt> '.__('for a Horizontal Line', $this->text_domain).'</p>'.
					'<hr class="no-margin" />'. // Horizontal line demo.

					'<blockquote class="inline-block em-t-margin em-b-margin em-padding l-padding">'.
					'<p><tt>&gt;</tt> '.__('to quote someone', $this->text_domain).'</p></blockquote>'.

					'<p><code>`'.__('backticks for inline code', $this->text_domain).'`</code></p>'.
					'<p><code>```</code> '.__('for a fenced code block...', $this->text_domain).'</p>'.

					'<pre class="code no-b-margin">'.
					'<code class="pre language-php">'.
					'```php'."\n".'&lt;?php echo \'code\'; ?&gt;'."\n".'```'.
					'</code></pre>'; // Syntax highlighting; if enabled.

				return apply_filters(__METHOD__, $syntax, get_defined_vars());
			}

			public function embedly_syntax()
			{
				$syntax = // How to use Embedly (URLs on a separate line).

					'<p class="text-primary font-120">'.
					'<strong>'.__('Embedly® Works Here', $this->text_domain).'</strong><br />'.
					'<span class="text-small-caps">'.__('automatic content embeds w/ URLs', $this->text_domain).'</span>'.
					'</p><hr class="no-margin" />'.

					'<p>'.__('Put a URL on it\'s own line. If the underlying site supports oEmbed; e.g. YouTube, Vimeo, Flickr, GitHub, and more; it is transformed automatically into an inline content snippet.', $this->text_domain).'</p>'.

					'<pre class="code no-b-margin font-80">'.
					'<code class="pre no-highlight">'.
					'https://www.youtube.com/watch?v=suoRzeq2Edk'."\n".
					'http://codepen.io/eliasyanni/pen/lxawf'."\n".
					'https://gist.github.com/JasWSInc/6960780'."\n".
					'</code></pre>'; // No highlighting.

				return apply_filters(__METHOD__, $syntax, get_defined_vars());
			}

			public function trending_posts($type = 'post', array $formats = array(), $max = 25, $thumb_width = 300, $thumb_height = 169)
			{
				if(!($type = (string)$type))
					$type = 'post'; // Default.

				if(!$formats) // Use defaults?
					$formats = array('standard');
				$formats_list = implode(',', $formats);

				if(($max = (integer)$max) < 1)
					$max = 25; // Default.

				if(($thumb_width = (integer)$thumb_width) < 1)
					$thumb_width = 300; // Default.

				if(($thumb_height = (integer)$thumb_height) < 1)
					$thumb_height = 169; // Default.

				static $static = array(); // Static cache.
				if(isset($static[$type][$formats_list][$max][$thumb_width][$thumb_height]))
					return $static[$type][$formats_list][$max][$thumb_width][$thumb_height];

				$static[$type][$formats_list][$max][$thumb_width][$thumb_height] = array(); // Initialize & create reference.
				$posts                                                           =& $static[$type][$formats_list][$max][$thumb_width][$thumb_height];

				$tp_cache_dir  = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/trending-posts';
				$tp_cache_file = $tp_cache_dir.'/'.sha1($type.$formats_list.$max.$thumb_width.$thumb_height);

				if(is_file($tp_cache_file) && filemtime($tp_cache_file) >= strtotime('-30 minutes'))
					return ($posts = (array)unserialize(file_get_contents($tp_cache_file)));

				@set_time_limit(300); // Give this routine a bit of time to complete.

				if(!is_dir($tp_cache_dir)) // Does the cache directory exist yet?
				{
					if(!mkdir($tp_cache_dir, 0775, TRUE) || !is_dir($tp_cache_dir))
						throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
						                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $tp_cache_dir));
					file_put_contents($tp_cache_dir.'/.htaccess', $this->htaccess_deny);
				}
				if(!empty($_SERVER['CDN_DIR']) && !empty($_SERVER['CDN_URL']) && extension_loaded('gd'))
				{
					$force_image_editor_gd_filter = function (){ return array('WP_Image_Editor_GD'); };
					// We force GD because Imagick refuses to work w/ CDN stream wrappers.
					add_filter('wp_image_editors', $force_image_editor_gd_filter);

					$tp_thumb_cache_dir_path = $_SERVER['CDN_DIR'].'/'.$this->options['cache_dir'].'/trending-post-thumbs';
					$tp_thumb_cache_dir_url  = set_url_scheme($_SERVER['CDN_URL'].'/'.$this->options['cache_dir'].'/trending-post-thumbs', 'http');
				}
				else // Use the `WP_CONTENT_DIR` as the base from which to store thumbs.
				{
					$tp_thumb_cache_dir_path = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/trending-post-thumbs';
					$tp_thumb_cache_dir_url  = set_url_scheme(content_url('/'.$this->options['cache_dir'].'/trending-post-thumbs'), 'http');
				}
				if(!is_dir($tp_thumb_cache_dir_path)) // If directory does not exist create it now.
				{
					if(!mkdir($tp_thumb_cache_dir_path, 0775, TRUE) || !is_dir($tp_thumb_cache_dir_path))
						throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
						                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $tp_thumb_cache_dir_path));
				}
				if($this->options['addthis_publisher']) // Only possible if the site owner has configured this value.
					if(!is_wp_error($_response = wp_remote_get('http://q.addthis.com/feeds/1.0/trending.json?pubid='.urlencode($this->options['addthis_publisher']).'&period=month')))
						if(is_array($_results = json_decode(wp_remote_retrieve_body($_response)))) foreach($_results as $_result)
						{
							if(!is_object($_result) || empty($_result->url) || !is_string($_result->url))
								continue; // Invalid result; for whatever reason.

							if(!preg_match('/^https?\:\/\/'.preg_quote($_SERVER['HTTP_HOST'], '/').'\//i', $_result->url))
								// AddThis includes some URLs which are actually nested into the oExchange API formation.
								// e.g. `http://api.addthis.com/oexchange/0.8/forward/email/offer?url=http%3A%2F%2Fexample.com%2Fthe...`
								if(($_query = parse_url($_result->url, PHP_URL_QUERY)))
								{
									wp_parse_str($_query, $_query_vars); // Parse query.
									if(!empty($_query_vars['url'])) $_result->url = $_query_vars['url'];
								}
							unset($_query, $_query_vars); // Housekeeping.

							if(!preg_match('/^https?\:\/\/'.preg_quote($_SERVER['HTTP_HOST'], '/').'\//i', $_result->url))
								continue; // Not from this site.

							if(!($_post_id = url_to_postid($_result->url)))
								continue; // Unable to determine post ID.

							if(!($_post = get_post($_post_id)) || $_post->post_status !== 'publish')
								continue; // Exclude these at all times.

							if($_post->post_type !== $type) // Must be of this type.
								continue; // Exclude these at all times; no exceptions.

							$_post_format = get_post_format($_post->ID);
							$_post_format = !$_post_format ? 'standard' : $_post_format;
							if(!in_array($_post_format, $formats, TRUE)) // Not in list of formats.
								continue; // Exclude these at all times; no exceptions.

							if(apply_filters(__METHOD__.'__exclude', FALSE, $_post, get_defined_vars()))
								continue; // Exclude any filtered by plugins.

							if(!($_post_og_image_urls = $this->open_graph_image_urls($_post->ID))
							   || !($_post_og_image = $_post_og_image_urls[0]) // First image.
							) continue; // Unable to locate any images.

							$posts[$_post->ID] = (object)array('ID'    => $_post->ID, 'url' => get_permalink($_post),
							                                   'title' => $_post->post_title, 'summary' => $this->get_summary($_post),
							                                   'image' => $_post_og_image, 'thumb' => $_post_og_image);

							if(count($posts) >= $max) break; // We can stop here.
						}
				unset($_response, $_results, $_result, $_post_id, $_post, $_post_format, $_post_og_image_urls, $_post_og_image); // Housekeeping.

				if(count($posts) < $max /* Iterate new posts; these backfill sites without a trend yet. */)
				{
					$_formats = $formats;
					foreach($_formats as &$_format)
						$_format = 'post-format-'.$_format;
					unset($_format); // Housekeeping.

					$_supported_formats  = array(); // Initialize.
					$__supported_formats = get_theme_support('post-formats');
					if(!empty($__supported_formats[0]) && is_array($__supported_formats[0]))
					{
						foreach($__supported_formats[0] as $_supported_format)
							$_supported_formats[] = 'post-format-'.$_supported_format;
						unset($_supported_format); // Housekeeping.
					}
					unset($__supported_formats); // Housekeeping.

					$_tax_query = array(
						array( // Default tax query.
						       'taxonomy' => 'post_format',
						       'terms'    => $_formats,
						       'operator' => 'IN', 'field' => 'slug',
						));
					if(in_array('post-format-standard', $_formats, TRUE))
						// Include posts without a format; i.e., standard.
						$_tax_query = array(
							'relation' => 'OR',
							array(
								'taxonomy' => 'post_format',
								'terms'    => $_supported_formats,
								'operator' => 'NOT IN', 'field' => 'slug',
							),
							array(
								'taxonomy' => 'post_format',
								'terms'    => array_diff($_formats, array('post-format-standard')),
								'operator' => 'IN', 'field' => 'slug',
							));
					$_wp_query = new \WP_Query(
						array(
							'post_type'      => $type,
							'tax_query'      => $_tax_query,
							'order'          => 'DESC', 'orderby' => 'date',
							'posts_per_page' => $max - count($posts) + 25,
						));
					if(is_array($_results = $_wp_query->get_posts())) foreach($_results as $_post)
					{
						if(apply_filters(__METHOD__.'__exclude', FALSE, $_post, get_defined_vars()))
							continue; // Exclude any filtered by plugins.

						$_post_format = get_post_format($_post->ID);
						$_post_format = !$_post_format ? 'standard' : $_post_format;
						if(!in_array($_post_format, $formats, TRUE)) // Not in list of formats.
							continue; // Exclude these at all times; no exceptions.

						if(!($_post_og_image_urls = $this->open_graph_image_urls($_post->ID))
						   || !($_post_og_image = $_post_og_image_urls[0]) // First image.
						) continue; // Unable to locate any images.

						$posts[$_post->ID] = (object)array('ID'    => $_post->ID, 'url' => get_permalink($_post),
						                                   'title' => $_post->post_title, 'summary' => $this->get_summary($_post),
						                                   'image' => $_post_og_image, 'thumb' => $_post_og_image);

						if(count($posts) >= $max) break; // We can stop here.
					}
				}
				unset($_formats, $_supported_formats, $_tax_query, $_wp_query, $_results, $_post, $_post_format, $_post_og_image_urls, $_post_og_image);

				foreach($posts as $_post /* Iterate posts; crop and resize images to produce thumbnails. */)
				{
					$_post_image_extension = $this->extension($_post->image); // Image extension.
					$_post_image_basename  = md5($_post->image.$thumb_width.$thumb_height.$this->version).'.'.$_post_image_extension;

					$_post_thumb_file = $tp_thumb_cache_dir_path.'/'.$_post_image_basename;
					$_post->thumb     = $tp_thumb_cache_dir_url.'/'.$_post_image_basename;

					if(!is_file($_post_thumb_file) || filemtime($_post_thumb_file) < strtotime('-24 hours'))
					{
						if(is_wp_error($_post_thumb_response = $_post_thumb_resource = wp_get_image_editor($_post->image)))
							throw new \exception($_post_thumb_response->get_error_message());

						$_post_thumb_resize        = new \stdClass;
						$_post_thumb_resize->dst_w = $thumb_width;
						$_post_thumb_resize->dst_h = $thumb_height;
						$_post_thumb_resize->src_w = round($thumb_width * 2);
						$_post_thumb_resize->src_h = round($thumb_height * 2);
						$_post_thumb_resize->size  = $_post_thumb_resource->get_size();

						if($_post_thumb_resize->src_w > $_post_thumb_resize->size['width'] || $_post_thumb_resize->src_h > $_post_thumb_resize->size['height'])
						{
							$_post_thumb_resize->src_w = $thumb_width; // Same size as the final thumbnail will be.
							$_post_thumb_resize->src_h = $thumb_height; // Same size as the final thumbnail.
						}
						$_post_thumb_resize->src_x = max(0, round(($_post_thumb_resize->size['width'] / 2) - ($_post_thumb_resize->src_w / 2)));
						$_post_thumb_resize->src_y = max(0, round(($_post_thumb_resize->size['height'] / 2) - ($_post_thumb_resize->src_h / 2)));

						if(is_wp_error($_post_thumb_response = $_post_thumb_resource->crop($_post_thumb_resize->src_x, $_post_thumb_resize->src_y, $_post_thumb_resize->src_w, $_post_thumb_resize->src_h, $_post_thumb_resize->dst_w, $_post_thumb_resize->dst_h)))
							throw new \exception($_post_thumb_response->get_error_message());

						if(is_wp_error($_post_thumb_response = $_post_thumb_resource->save($_post_thumb_file)))
							throw new \exception($_post_thumb_response->get_error_message());

						unset($_post_thumb_response, $_post_thumb_resource, $_post_thumb_resize); // Force destruction; save memory.
					}
				}
				unset($_post, $_post_image_extension, $_post_image_basename, $_post_thumb_file); // Housekeeping.

				if(!empty($force_image_editor_gd_filter)) // Cleanup this filter.
					remove_filter('wp_image_editors', $force_image_editor_gd_filter);

				$posts = array_slice($posts, 0, $max, TRUE); // Top X posts (from the two sources).

				$cache_file_tmp = $tp_cache_file.'.'.uniqid('', TRUE).'.tmp';
				if(file_put_contents($cache_file_tmp, serialize($posts)) !== FALSE)
					rename($cache_file_tmp, $tp_cache_file);

				return apply_filters(__METHOD__, $posts, get_defined_vars());
			}

			public function strip_titles($string)
			{
				if(!($string = (string)$string))
					return $string; // Empty.

				$string = trim(preg_replace('/\<(h[0-9]|header)(?:\s+[^>]*)?\>.*?\<\/\\1\>/is', '', $string));

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function clip($string, $max_length = 45, $strip_titles = TRUE, $force_ellipsis = FALSE)
			{
				if(!($string = (string)$string))
					return $string; // Empty.

				$max_length = ($max_length < 4) ? 4 : $max_length;

				if($strip_titles) $string = $this->strip_titles($string);

				$string = trim(preg_replace('/\s+/', ' ', strip_tags($string)));

				if(strlen($string) > $max_length)
					$string = (string)substr($string, 0, $max_length - 3).'...';

				else if($force_ellipsis && strlen($string) + 3 > $max_length)
					$string = (string)substr($string, 0, $max_length - 3).'...';

				else $string .= ($force_ellipsis) ? '...' : '';

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function mid_clip($string, $max_length = 45, $strip_titles = TRUE)
			{
				if(!($string = (string)$string))
					return $string; // Empty.

				$max_length = ($max_length < 4) ? 4 : $max_length;

				if($strip_titles) $string = $this->strip_titles($string);

				$string = trim(preg_replace('/\s+/', ' ', strip_tags($string)));

				if(strlen($string) <= $max_length)
					goto finale; // Nothing to do.

				$full_string     = $string;
				$half_max_length = floor($max_length / 2);

				$first_clip = $half_max_length - 3;
				$string     = ($first_clip >= 1) // Something?
					? substr($full_string, 0, $first_clip).'...'
					: '...'; // Ellipsis only.

				$second_clip = strlen($full_string) - ($max_length - strlen($string));
				$string .= ($second_clip >= 0 && $second_clip >= $first_clip)
					? substr($full_string, $second_clip) : ''; // Nothing more.

				finale: // Target point; all done.

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function hilite_search_terms($string)
			{
				if(!strlen($string = (string)$string))
					return $string; // Empty.

				static $search_terms_regex; // Static (process these ONE time only).

				if(!isset($search_terms_regex) && ($search_terms_regex = strtolower(get_search_query())))
				{
					$search_terms_regex = preg_split('/\s+/', $search_terms_regex, NULL, PREG_SPLIT_NO_EMPTY);
					$search_terms_regex = array_unique($search_terms_regex);

					foreach($search_terms_regex as &$_search_term)
						$_search_term = preg_quote($_search_term, '/');
					unset($_search_term); // Housekeeping.

					$search_terms_regex = '/'.implode('|', $search_terms_regex).'/i';
				}
				if(!$search_terms_regex) return $string; // Nothing to hilite.

				$string = preg_replace($search_terms_regex, '<mark>${0}</mark>', $string);

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function esc_js_sq($string, $times = 1)
			{
				if(!($string = (string)$string))
					return $string; // Empty.

				$string = str_replace(array("\r\n", "\r", '"'), array("\n", "\n", '%%!dq!%%'), $string);
				$string = str_replace(array('%%!dq!%%', "'"), array('"', "\\'"), trim(json_encode($string), '"'));
				$string = str_replace('\\', str_repeat('\\', abs($times) - 1).'\\', $string);

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function is_true($value)
			{
				if(!is_scalar($value)) return FALSE; // Obviously NOT true.
				return (filter_var($value, FILTER_VALIDATE_BOOLEAN)) ? TRUE : FALSE;
			}

			public function extension($file)
			{
				$file = (string)$file; // Force string value.
				return strtolower(ltrim((string)strrchr(basename($file), '.'), '.'));
			}

			public function xencrypt($string, $w_md5_cs = TRUE)
			{
				if(!strlen($string = (string)$string))
					return ($base64 = ''); // Nothing to do.

				for($key = wp_salt(), $string = '~xe|'.$string, $_i = 1, $e = ''; $_i <= strlen($string); $_i++)
				{
					$_char     = (string)substr($string, $_i - 1, 1);
					$_key_char = (string)substr($key, ($_i % strlen($key)) - 1, 1);
					$e .= chr(ord($_char) + ord($_key_char));
				}
				unset($_i, $_char, $_key_char);

				if(!strlen($e)) // This should not happen, but let's be sure.
					throw new \exception(__('String encryption failed (`$e` has no length).', $this->text_domain));

				$e = '~xe'.(($w_md5_cs) ? ':'.md5($e) : '').'|'.$e;

				return ($base64 = base64_encode($e)); // Encrypted string.
			}

			public function xdecrypt($base64)
			{
				if(!strlen($e = base64_decode((string)$base64)))
					return ($string = ''); // Nothing to do.

				if(!preg_match('/^~xe(?:\:(?P<md5>[a-zA-Z0-9]+))?\|(?P<e>.*)$/s', $e, $md5_e))
					return ($string = ''); // Invalid.

				if(!strlen($md5_e['e'])) return ($string = ''); // Invalid.

				if(!empty($md5_e['md5']) && $md5_e['md5'] !== md5($md5_e['e']))
					return ($string = ''); // Invalid.

				for($key = wp_salt(), $_i = 1, $string = ''; $_i <= strlen($md5_e['e']); $_i++)
				{
					$_char     = (string)substr($md5_e['e'], $_i - 1, 1);
					$_key_char = (string)substr($key, ($_i % strlen($key)) - 1, 1);
					$string .= chr(ord($_char) - ord($_key_char));
				}
				unset($_i, $_char, $_key_char); // Housekeeping.

				if(!strlen($string)) // This should not happen, but let's be sure.
					throw new \exception(__('String decryption failed (`$string` has no length).', $this->text_domain));

				if(!strlen($string = preg_replace('/^~xe\|/', '', $string, 1, $xe)) || !$xe)
					return ($string = ''); // Invalid.

				return $string; // Decrypted string.
			}

			public function kses_init() // Only when Markdown is enabled :-)
			{
				$this->kses_allowed_tags(); // Add allowable tags.
				$this->kses_remove_filters(); // Remove filters.

				if(!current_user_can('unfiltered_html'))
					$this->kses_add_filters(); // Current user.
			}

			function kses_allowed_tags()
			{
				$other_allowed_tags = array
				(
					's'       => array(),
					'u'       => array(),
					'big'     => array(),
					'small'   => array(),
					'tt'      => array(),
					'kbd'     => array(),
					'dfn'     => array(),
					'time'    => array(),
					'samp'    => array(),
					'var'     => array(),
					'sub'     => array(),
					'sup'     => array(),
					'h1'      => array(),
					'h2'      => array(),
					'h3'      => array(),
					'h4'      => array(),
					'h5'      => array(),
					'h6'      => array(),
					'p'       => array(),
					'br'      => array(),
					'ul'      => array(),
					'ol'      => array(),
					'li'      => array(),
					'table'   => array(),
					'tbody'   => array(),
					'thead'   => array(),
					'tfoot'   => array(),
					'tr'      => array(),
					'th'      => array(),
					'td'      => array(),
					'hr'      => array(),
					'center'  => array(),
					'address' => array(),
					'details' => array(),
					'pre'     => array(), 'code' => array(),
					'ruby'    => array(), 'rt' => array(), 'rp' => array(),
					'meter'   => array('value' => array(), 'max' => array()),
					'font'    => array('face' => array(), 'size' => array()),
					'img'     => array('src' => array(), 'alt' => array())
				);
				$tags               = &$GLOBALS['allowedtags']; // By reference.
				$tags               = array_merge((array)$tags, apply_filters(__METHOD__, $other_allowed_tags));
				ksort($tags, SORT_STRING);
			}

			function kses_add_filters()
			{
				add_filter('title_save_pre', 'wp_filter_kses');
				add_filter('content_save_pre', array($this, 'post_kses'));
				add_filter('excerpt_save_pre', array($this, 'post_kses'));
				add_filter('content_filtered_save_pre', array($this, 'post_kses'));
				add_filter('pre_comment_content', array($this, 'kses'));
			}

			function kses_remove_filters()
			{
				remove_filter('title_save_pre', 'wp_filter_kses');
				remove_filter('content_save_pre', array($this, 'post_kses'));
				remove_filter('excerpt_save_pre', array($this, 'post_kses'));
				remove_filter('content_filtered_save_pre', array($this, 'post_kses'));
				remove_filter('pre_comment_content', array($this, 'kses'));
			}

			public function kses($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(strpos($string, '<') === FALSE)
					goto finale; // Nothing to do.

				$spcsm           = // Markdown fences only.
					$this->spcsm_tokens($string, array('md_fences'), __FUNCTION__);
				$spcsm['string'] = wp_filter_kses($spcsm['string']);
				$string          = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function post_kses($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(strpos($string, '<') === FALSE)
					goto finale; // Nothing to do.

				$spcsm           = // Markdown fences only.
					$this->spcsm_tokens($string, array('md_fences'), __FUNCTION__);
				$spcsm['string'] = wp_filter_post_kses($spcsm['string']);
				$string          = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function spcsm_tokens($string, array $tokenize_only = array(), $marker = '')
			{
				$marker = str_replace('.', '', uniqid('', TRUE)).
				          ($marker ? sha1($marker) : '');

				if(!($string = trim((string)$string))) // Nothing to tokenize.
					return array('string' => $string, 'tokens' => array(), 'marker' => $marker);

				$spcsm = // Convert string to an array w/ token details.
					array('string' => $string, 'tokens' => array(), 'marker' => $marker);

				shortcodes: // Target point; `[shortcode][/shortcode]`.

				if($tokenize_only && !in_array('shortcodes', $tokenize_only, TRUE))
					goto pre; // Not tokenizing these.

				if(empty($GLOBALS['shortcode_tags']) || strpos($spcsm['string'], '[') === FALSE)
					goto pre; // No `[` shortcodes.

				$spcsm['string'] = preg_replace_callback('/'.get_shortcode_regex().'/s', function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Shortcodes replaced by tokens.

				pre: // Target point; HTML `<pre>` tags.

				if($tokenize_only && !in_array('pre', $tokenize_only, TRUE))
					goto code; // Not tokenizing these.

				if(stripos($spcsm['string'], '<pre') === FALSE)
					goto code; // Nothing to tokenize here.

				$pre = // HTML `<pre>` tags.
					'/(?P<tag_open_bracket>\<)'. // Opening `<` bracket.
					'(?P<tag_open_name>pre)'. // Tag name; e.g. a `pre` tag.
					'(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'. // Attributes & `>`.
					'(?P<tag_contents>.*?)'. // Tag contents (multiline possible).
					'(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</pre>` tag.

				$spcsm['string'] = preg_replace_callback($pre, function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Tags replaced by tokens.

				code: // Target point; HTML `<code>` tags.

				if($tokenize_only && !in_array('code', $tokenize_only, TRUE))
					goto samp; // Not tokenizing these.

				if(stripos($spcsm['string'], '<code') === FALSE)
					goto samp; // Nothing to tokenize here.

				$code = // HTML `<code>` tags.
					'/(?P<tag_open_bracket>\<)'. // Opening `<` bracket.
					'(?P<tag_open_name>code)'. // Tag name; e.g. a `code` tag.
					'(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'. // Attributes & `>`.
					'(?P<tag_contents>.*?)'. // Tag contents (multiline possible).
					'(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</code>` tag.

				$spcsm['string'] = preg_replace_callback($code, function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Tags replaced by tokens.

				samp: // Target point; HTML `<samp>` tags.

				if($tokenize_only && !in_array('samp', $tokenize_only, TRUE))
					goto md_fences; // Not tokenizing these.

				if(stripos($spcsm['string'], '<samp') === FALSE)
					goto md_fences; // Nothing to tokenize here.

				$samp = // HTML `<samp>` tags.
					'/(?P<tag_open_bracket>\<)'. // Opening `<` bracket.
					'(?P<tag_open_name>samp)'. // Tag name; e.g. a `samp` tag.
					'(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'. // Attributes & `>`.
					'(?P<tag_contents>.*?)'. // Tag contents (multiline possible).
					'(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</samp>` tag.

				$spcsm['string'] = preg_replace_callback($samp, function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Tags replaced by tokens.

				md_fences: // Target point; Markdown pre/code fences.

				if(!$this->options['md_enable_flavor']) goto md_links;
				if($tokenize_only && !in_array('md_fences', $tokenize_only, TRUE))
					goto md_links; // Not tokenizing these.

				if(strpos($spcsm['string'], '~') === FALSE && strpos($spcsm['string'], '`') === FALSE)
					goto md_links; // Nothing to tokenize here.

				$md_fences = // Markdown pre/code fences.
					'/(?P<fence_open>~{3,}|`{3,}|`)'. // Opening fence.
					'(?P<fence_contents>.*?)'. // Contents (multiline possible).
					'(?P<fence_close>\\1)/is'; // Closing fence; ~~~, ```, `.

				$spcsm['string'] = preg_replace_callback($md_fences, function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Fences replaced by tokens.

				md_links: // Target point; [Markdown](links).
				// This also tokenizes [Markdown]: <link> "definitions".
				// This routine includes considerations for images also.

				// NOTE: The tokenizer does NOT deal with links that reference definitions, as this is not necessary.
				//    So, while we DO tokenize <link> "definitions" themselves, the [actual][references] to
				//    these definitions do not need to be tokenized; i.e. it is not necessary here.

				if(!$this->options['md_enable_flavor']) goto finale;
				if($tokenize_only && !in_array('md_links', $tokenize_only, TRUE))
					goto finale; // Not tokenizing these.

				$spcsm['string'] = preg_replace_callback(array('/^[ ]*(?:\[[^\]]+\])+[ ]*\:[ ]*(?:\<[^>]+\>|\S+)(?:[ ]+.+)?$/m',
				                                               '/\!?\[(?:(?R)|[^\]]*)\]\([^)]+\)(?:\{[^}]*\})?/'), function ($m) use (&$spcsm)
				{
					$spcsm['tokens'][] = $m[0]; // Tokenize.
					return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%'; #

				}, $spcsm['string']); // Shortcodes replaced by tokens.

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $spcsm, get_defined_vars());
			}

			public function spcsm_restore(array $spcsm)
			{
				if(!isset($spcsm['string']))
					return ''; // Not possible.

				if(!($string = trim((string)$spcsm['string'])))
					goto finale; // Nothing to restore.

				$tokens = isset($spcsm['tokens']) ? (array)$spcsm['tokens'] : array();
				$marker = isset($spcsm['marker']) ? (string)$spcsm['marker'] : '';

				if(!$tokens || !$marker || strpos($string, '%#%') === FALSE)
					goto finale; // Nothing to restore in this case.

				foreach(array_reverse($tokens, TRUE) as $_token => $_value)
					$string = str_replace('%#%spcsm-'.$marker.'-'.$_token.'%#%', $_value, $string);
				// Must go in reverse order so nested tokens unfold properly.
				unset($_token, $_value); // Housekeeping.

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function format_typer($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(!is_single() || is_attachment())
					goto finale; // Nothing to do.

				if(strpos($string, '<') !== FALSE || strpos($string, '[') !== FALSE)
					goto finale; // Possible HTML, `[raw]` tag or `[` shortcode.

				switch($format = get_post_format()) // Auto-formatting; based on format type.
				{
					case 'quote': // Automatic `<blockquote>` tag wrapper.

						if((!$this->options['md_enable_flavor'] || strpos($string, '> ') === FALSE)
						   && stripos($string, '<blockquote') === FALSE
						) $string = '<blockquote>'.$string.'</blockquote>';

						break; // Break switch handler.

					case 'audio': // Automatic `<audio>` tag w/ `<source>`.

						if(preg_match('/^(?:[a-z0-9]+\:\/\/|\/)[^\s]+$/i', $string))
							$string = '<audio width="100%" controls>'."\n".
							          '<source src="'.esc_attr($string).'">'."\n".
							          '</audio>'; // Line breaks required by Markdown.

						break; // Break switch handler.

					case 'video': // Automatic `<audio>` tag w/ `<source>`.

						if(preg_match('/^(?:[a-z0-9]+\:\/\/|\/)[^\s]+$/i', $string))
							$string = '<video width="100%" controls>'."\n".
							          '<source src="'.esc_attr($string).'">'."\n".
							          '</video>'; // Line breaks required by Markdown.

						break; // Break switch handler.

					case 'image': // Automatic `<img>` tag.

						if(preg_match('/^(?:[a-z0-9]+\:\/\/|\/)[^\s]+$/i', $string))
							$string = '<img src="'.esc_attr($string).'" alt="'.esc_attr($this->title()).'" />';

						break; // Break switch handler.

					case 'link': // Automatic `<a>` link tag.

						if(preg_match('/^(?:[a-z0-9]+\:\/\/|\/)[^\s]+$/i', $string))
							$string = '<a href="'.esc_attr($string).'">'.(($title = $this->title()) ? $title : $string).'</a>';

						break; // Break switch handler.

					case 'chat': // Automatic `<pre>` tag.

						if((!$this->options['md_enable_flavor'] || (strpos($string, '~~~') === FALSE && strpos($string, '```') === FALSE))
						   && stripos($string, '<pre') === FALSE
						) $string = '<pre>'.$string.'</pre>';

						break; // Break switch handler.
				}
				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function oembed($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(strpos($string, '://') === FALSE)
					goto finale; // Nothing to do.

				$spcsm = $this->spcsm_tokens($string, array(), __FUNCTION__);
				if($GLOBALS['wp_embed'] instanceof \WP_Embed) // For IDEs.
					$spcsm['string'] = $GLOBALS['wp_embed']->autoembed($spcsm['string']);
				$string = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function clickable($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(!preg_match('/\:\/\/|(?:www|ftp)\.|@/i', $string))
					goto finale; // Nothing to do.

				$spcsm           = $this->spcsm_tokens($string, array(), __FUNCTION__);
				$spcsm['string'] = make_clickable($spcsm['string']);
				$string          = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function md_parse_cache($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(!$this->options['md_enable_flavor'])
					return $string; // Markdown not enabled here.

				$md_parse_cache = // Based on configured flavor.
					$this->options['md_enable_flavor'].'_parse_cache';

				$string = $this->{$md_parse_cache}($string);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function md_parse_cache_wp_readme_tabs($readme, $slug = '', $max_age = 3600, $default_active_tab = 'Description', $include_all_sections = FALSE)
			{
				if(!($readme = trim((string)$readme)))
					return $readme; // Empty.

				if(!$this->options['md_enable_flavor'])
					return $readme; // Markdown not enabled here.

				if(!empty($_REQUEST['tab']) && is_string($_REQUEST['tab']))
					$default_active_tab = stripslashes($_REQUEST['tab']);

				$md_cache_dir  = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';
				$md_cache_hash = sha1($readme.$default_active_tab.$this->options['md_enable_flavor'].$this->options['md_enable_line_breaks']);
				$cache_file    = $md_cache_dir.'/'.$md_cache_hash.'.wp-readme-tabs.md.html'; // Markdown cache file.

				check_cache: // Target point; first we check the cache.

				if(is_file($cache_file) && filemtime($cache_file) >= time() - $max_age)
					return ($tabs = (string)file_get_contents($cache_file));

				check_readme: // Target point; detect format type.

				if(preg_match('/^\/[^\s]+$/', $readme))
					goto fetch_local_readme_contents;

				if(preg_match('/^[a-z0-9]+\:\/\/[^\s]+$/i', $readme))
					goto fetch_remote_readme_contents;

				$readme_contents = $readme; // Readme as contents.
				goto parse_cache_readme_contents_into_tabs;

				fetch_local_readme_contents: // Target point; local file.

				$readme_contents = (string)file_get_contents($readme);
				goto parse_cache_readme_contents_into_tabs;

				fetch_remote_readme_contents: // Target point; fetch remote file.

				$readme_contents = ''; // Initialize.

				if(($_remote_readme_response = wp_remote_get($readme)))
					$readme_contents = wp_remote_retrieve_body($_remote_readme_response);

				if($readme_contents && stripos($readme, 'https://api.github.com/') === 0 && strpos($readme, '/contents/') !== FALSE)
					if(is_object($_remote_readme_object = json_decode($readme_contents)) && isset($_remote_readme_object->content))
						$readme_contents = base64_decode($_remote_readme_object->content);

				unset($_remote_readme_response, $_remote_readme_object); // Housekeeping.

				goto parse_cache_readme_contents_into_tabs;

				parse_cache_readme_contents_into_tabs: // Target point; parse.

				$tabs = ''; // Initialize tabs.
				if(!$readme_contents) goto finale; // Nothing.
				$readme_contents = $this->esc_raw($readme_contents);
				$readme_contents = // Convert WP sub-sections into `<h3>` tags.
					preg_replace('/^\=\s+(.*?)\s+\=$/m', '### ${1}', $readme_contents);

				$_this             = $this; // Self-reference.
				$specs_desc_leader = ''; // Initialize; parse from specs.

				$readme_section_names['Specifications']                   = __('Specifications', $this->text_domain);
				$readme_section_short_names['Specifications']             = __('Specs', $this->text_domain);
				$readme_section_names['Description']                      = __('Description', $this->text_domain);
				$readme_section_names['Features']                         = __('Features', $this->text_domain);
				$readme_section_names['Pro Features']                     = __('Pro Features', $this->text_domain);
				$readme_section_names['Screenshots']                      = __('Screenshots', $this->text_domain);
				$readme_section_names['Installation']                     = __('Installation', $this->text_domain);
				$readme_section_names['Pro Installation']                 = __('Pro Installation', $this->text_domain);
				$readme_section_names['Frequently Asked Questions']       = __('Frequently Asked Questions', $this->text_domain);
				$readme_section_short_names['Frequently Asked Questions'] = __('FAQs', $this->text_domain);
				$readme_section_names['Further Details']                  = __('Further Details', $this->text_domain);
				$readme_section_names['License']                          = __('License', $this->text_domain);
				$readme_section_names['Changelog']                        = __('Changelog', $this->text_domain);

				$readme_specifications = function ($specifications) use ($readme_section_names, $_this, &$specs_desc_leader)
				{
					$specifications = make_clickable($specifications); // Clickables.
					$lines          = array(); // Initiliaze the array of lines.

					foreach(preg_split("/[\r\n]+/", $specifications, NULL, PREG_SPLIT_NO_EMPTY) as $_line)
						if(count($_line_parts = explode(':', $_line, 2)) === 2)
							$lines[] = '<strong>'.$_line_parts[0].'</strong>: '.$_line_parts[1];
						else $specs_desc_leader = $_line; // Leader; short description.
					unset($_line); // Housekeeping.

					$icon           = '<i class="fa fa-chevron-circle-right"></i> ';
					$specifications = '<h3>'.esc_html($readme_section_names['Specifications']).'</h3>'.
					                  '<ul class="list-unstyled"><li>'.$icon.implode('</li><li>'.$icon, $lines).'</li></ul>';

					return $specifications; // Now a formatted list w/ a sub-section heading.
				};
				$readme_screenshots    = function ($screenshots) use ($_this, &$specs_desc_leader, $slug)
				{
					if(!$slug) return ''; // Not possible w/o a slug.
					if(!($screenshots = trim($screenshots))) return ''; // N/A.
					if(is_ssl()) return ''; // Can't load over SSL (not possible).

					if(!preg_match('/^(?:1|on|yes|true)$/i', (string)ini_get('allow_url_fopen')))
						return ''; // Can't check if remote files exist (not possible).

					if(preg_match('/^[0-9]+\.\s+https?\:\/\//im', $screenshots))
						$src_template = '#description-src#'; // Descriptions are sources.

					else if(@getimagesize($_src = 'http://s-plugins.wordpress.org/'.$slug.'/assets/screenshot-1.png'))
						$src_template = 'http://s-plugins.wordpress.org/'.$slug.'/assets/screenshot-%1$s.png';

					else if(@getimagesize($_src = 'http://s-plugins.wordpress.org/'.$slug.'/assets/screenshot-1.jpg'))
						$src_template = 'http://s-plugins.wordpress.org/'.$slug.'/assets/screenshot-%1$s.jpg';

					else if(@getimagesize($_src = 'http://s.wordpress.org/plugins/'.$slug.'/screenshot-1.png'))
						$src_template = 'http://s.wordpress.org/plugins/'.$slug.'/screenshot-%1$s.png';

					else if(@getimagesize($_src = 'http://s.wordpress.org/plugins/'.$slug.'/screenshot-1.jpg'))
						$src_template = 'http://s.wordpress.org/plugins/'.$slug.'/screenshot-%1$s.jpg';

					else return ''; // Unable to determine the underlying location of these screenshots.

					$imgs = array(); // Initiliaze the array of `<img />` tags.
					foreach(preg_split("/[\r\n]+/", $screenshots, NULL, PREG_SPLIT_NO_EMPTY) as $_key => $_description)
					{
						$_title       = sprintf(__('Screenshot %1$s', $_this->text_domain), $_key + 1);
						$_description = trim(preg_replace('/^[0-9]+\./', '', $_description)); // Possibly the source.
						$_src         = str_replace('#description-src#', $_description, sprintf($src_template, $_key + 1));

						$imgs[] = '<div class="text-center y-margin">'. // Center each image w/ margins.
						          ' <a href="'.esc_attr($_src).'" target="_blank" rel="external nofollow" title="'.esc_attr($_title).'">'.
						          ' <img src="'.esc_attr($_src).'" class="fancy-image" alt="'.esc_attr($_title).'" title="'.esc_attr($_description).'" /></a>'.
						          '</div>';
					}
					unset($_key, $_description, $_src); // Housekeeping.

					return $imgs ? '<div data-toggle="fancybox-gallery">'.implode("\n", $imgs).'</div>' : '';
				};
				$readme_parse_videos   = function ($string) use ($_this)
				{
					return preg_replace_callback('/^\<p\>\[youtube\s+https?\:\/\/www\.youtube\.com\/watch\?v\=(?P<video>\S+)\s*\/\]\<\/p\>$/im', function ($m)
					{
						return '<iframe src="//www.youtube.com/embed/'.esc_attr($m['video']).'" class="border-radius width-100 y-margin" style="height:400px; border:0;"></iframe>';
					}, $string);
				};
				$_readme_sections      = preg_split('/^(?:\={3}\s+(.*?)\s+\={3}|\={2}\s+(.*?)\s+\={2})$/m',
				                                    $readme_contents, NULL, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

				for($_readme_tabs = array(), $_i = 0; $_i < count($_readme_sections); $_i = $_i + 2)
				{
					if(!isset($_readme_sections[$_i + 1]))
						continue; // Not possible.

					if($_i === 0) // Specifications.
						$_readme_tabs[$readme_section_names['Specifications']]
							= $readme_specifications($_readme_sections[$_i + 1]);

					else switch($_readme_sections[$_i])
					{
						case $readme_section_names['Description']:
						case $readme_section_names['Features']:
						case $readme_section_names['Pro Features']:
						case $readme_section_names['Installation']:
						case $readme_section_names['Pro Installation']:
						case $readme_section_names['Further Details']:
						case $readme_section_names['Frequently Asked Questions']:
						case $readme_section_names['License']:
						case $readme_section_names['Changelog']: // All handled the same.
							$_readme_tabs[$_readme_sections[$_i]] = $_readme_sections[$_i + 1];
							break; // Break switch handler.

						case $readme_section_names['Screenshots']:
							if(($_readme_screenshots = $readme_screenshots($_readme_sections[$_i + 1])))
								$_readme_tabs[$_readme_sections[$_i]] = $_readme_screenshots;
							break; // Break switch handler.

						default: // Custom sections MUST end with `:`.
							if($include_all_sections || substr($_readme_sections[$_i], -1) === ':')
								$_readme_tabs[trim($_readme_sections[$_i], ':')] = $_readme_sections[$_i + 1];
							break; // Break switch handler.
					}
				}
				unset($_readme_sections, $_i, $_readme_screenshots); // Housekeeping.

				if(!$_readme_tabs) goto finale; // There are NO tabs in this case.

				$tabs = '<ul class="nav nav-tabs b-margin" data-toggle="tabdrop">'."\n";
				foreach($_readme_tabs as $_readme_tab_section_name => $_readme_tab_section_content)
				{
					$_is_lite_tab_section   = stripos($_readme_tab_section_name, 'Pro ') !== 0 && !empty($_readme_tabs['Pro '.$_readme_tab_section_name]);
					$_is_pro_tab_section    = stripos($_readme_tab_section_name, 'Pro ') === 0 && !empty($_readme_tabs[substr($_readme_tab_section_name, 4)]);
					$_lite_tab_section_name = $_is_lite_tab_section ? $_readme_tab_section_name : ($_is_pro_tab_section ? substr($_readme_tab_section_name, 4) : '');
					$_pro_tab_section_name  = $_is_pro_tab_section ? $_readme_tab_section_name : ($_is_lite_tab_section ? 'Pro '.$_readme_tab_section_name : '');

					if($_is_pro_tab_section) continue; // Already in the dropdown menu; as seen below.
					else if($_is_lite_tab_section) // Combine these into a one tab that behaves like a dropdown menu.
					{
						$tabs .= '<li class="dropdown'.((strcasecmp($_lite_tab_section_name, $default_active_tab) === 0 || strcasecmp($_pro_tab_section_name, $default_active_tab) === 0) ? ' active' : '').'">'.
						         '  <a href="#" class="dropdown-toggle" data-toggle="dropdown"'.(($_lite_tab_section_name === $readme_section_names['Features']) ? ' style="font-weight:bold;"' : '').'>'.
						         '     '.(($_lite_tab_section_name === $readme_section_names['Features']) ? '<i class="fa fa-shopping-cart"></i> ' : '').
						         '     '.esc_html($_lite_tab_section_name).' <span class="caret"></span></a>'."\n".
						         '     <ul class="dropdown-menu" role="menu">'."\n".
						         '        <li'.((strcasecmp($_lite_tab_section_name, $default_active_tab) === 0) ? ' class="active"' : '').'>'.
						         '           <a href="#'.esc_attr('tab-'.md5($_lite_tab_section_name)).'" tabindex="-1" data-toggle="tab">'.
						         '              '.__('Lite', $this->text_domain).' '. // Prefix the lite version with a `Lite` label to help clarify.
						         '              '.esc_html(!empty($readme_section_short_names[$_lite_tab_section_name]) ? $readme_section_short_names[$_lite_tab_section_name] : $_lite_tab_section_name).
						         '           </a>'.
						         '        </li>'."\n".
						         '        <li'.((strcasecmp($_pro_tab_section_name, $default_active_tab) === 0) ? ' class="active"' : '').'>'.
						         '           <a href="#'.esc_attr('tab-'.md5($_pro_tab_section_name)).'" tabindex="-1" data-toggle="tab">'.
						         '              '.esc_html(!empty($readme_section_short_names[$_pro_tab_section_name]) ? $readme_section_short_names[$_pro_tab_section_name] : $_pro_tab_section_name).
						         '           </a>'.
						         '        </li>'."\n".
						         '     </ul>'."\n".
						         '  </a>'."\n".
						         '</li>'."\n";
					}
					else $tabs .= '<li'.((strcasecmp($_readme_tab_section_name, $default_active_tab) === 0) ? ' class="active"' : '').'>'.
					              '  <a href="#'.esc_attr('tab-'.md5($_readme_tab_section_name)).'" data-toggle="tab"'.(($_readme_tab_section_name === $readme_section_names['Pro Features']) ? ' style="font-weight:bold;"' : '').'>'.
					              '    '.esc_html(!empty($readme_section_short_names[$_readme_tab_section_name]) ? $readme_section_short_names[$_readme_tab_section_name] : $_readme_tab_section_name).
					              '       '.(($_readme_tab_section_name === $readme_section_names['Pro Features']) ? ' <i class="fa fa-shopping-cart"></i>' : '').
					              '  </a>'.
					              '</li>'."\n";
				}
				$tabs .= '</ul>'."\n";

				$tabs .= '<div class="tab-content">'."\n";
				foreach($_readme_tabs as $_readme_tab_section_name => $_readme_tab_section_content) // Tabbed navigation panes.
					$tabs .= '<div id="'.esc_attr('tab-'.md5($_readme_tab_section_name)).'" class="tab-pane'.(in_array($_readme_tab_section_name, array($readme_section_names['Features'], $readme_section_names['Pro Features'], $readme_section_names['Changelog']), TRUE) ? ' y-margin-lis' : '').(strcasecmp($_readme_tab_section_name, $default_active_tab) === 0 ? ' active' : '').'">'."\n".
					         '  '.($_readme_tab_section_name === $readme_section_names['Specifications'] ? '<i class="fa fa-info-circle fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Description'] ? '<i class="fa fa-file-text-o fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Description'] && $specs_desc_leader ? '<h2 class="r-margin">'.$specs_desc_leader.'</h2>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Features'] ? '<i class="fa fa-binoculars fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Pro Features'] ? '<i class="fa fa-shopping-cart fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Installation'] ? '<i class="fa fa-magic fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Pro Installation'] ? '<i class="fa fa-magic fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Further Details'] ? '<i class="fa fa-file fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Frequently Asked Questions'] ? '<i class="fa fa-question-circle fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['License'] ? '<i class="fa fa-legal fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.($_readme_tab_section_name === $readme_section_names['Changelog'] ? '<i class="fa fa-wrench fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.(!in_array($_readme_tab_section_name, $readme_section_names, TRUE) ? '<i class="fa fa-thumbs-o-up fa-4x pull-right l-margin b-margin"></i>' : '').
					         '  '.$readme_parse_videos($this->md_parse_cache($_readme_tab_section_content))."\n".
					         '</div>'."\n";
				$tabs .= '</div>'."\n";

				unset($_this, $_readme_tabs, $_readme_tab_section_name, $_readme_tab_section_content); // Houskeeping.
				unset($_is_lite_tab_section, $_is_pro_tab_section, $_lite_tab_section_name, $_pro_tab_section_name); // Housekeeping.

				finale: // Target point; grand finale (cache & return).

				if(!is_dir($md_cache_dir)) // Does the cache directory exist yet?
				{
					if(!mkdir($md_cache_dir, 0775, TRUE) || !is_dir($md_cache_dir))
						throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
						                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $md_cache_dir));
					file_put_contents($md_cache_dir.'/.htaccess', $this->htaccess_deny);
				}
				$cache_file_tmp = $cache_file.'.'.uniqid('', TRUE).'.tmp';
				if(file_put_contents($cache_file_tmp, $tabs) !== FALSE)
					rename($cache_file_tmp, $cache_file);

				return apply_filters(__METHOD__, $tabs, get_defined_vars());
			}

			public function php_markdown_extra_parse_cache($string)
			{
				static $parser; // Parser instance.

				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if($this->options['md_enable_flavor'] !== 'php_markdown_extra')
					return $string; // Not this flavor.

				$md_cache_dir  = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';
				$md_cache_hash = sha1($string.$this->options['md_enable_flavor'].$this->options['md_enable_line_breaks']);
				$cache_file    = $md_cache_dir.'/'.$md_cache_hash.'.md.html'; // Markdown cache file.

				if(is_file($cache_file) && ($html = file_get_contents($cache_file)))
					return $html; // From cache; we already processed this.

				if(!isset($parser)) // A single instance of the Markdown class.
				{
					$parser                    = new \Michelf\MarkdownExtra();
					$parser->code_class_prefix = 'language-'; // e.g., `<code class="language-php">`.
				}
				if(($html = apply_filters(__METHOD__, $parser->transform($string), get_defined_vars())))
				{
					if(!is_dir($md_cache_dir)) // Does the cache directory exist yet?
					{
						if(!mkdir($md_cache_dir, 0775, TRUE) || !is_dir($md_cache_dir))
							throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
							                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $md_cache_dir));
						file_put_contents($md_cache_dir.'/.htaccess', $this->htaccess_deny);
					}
					$cache_file_tmp = $cache_file.'.'.uniqid('', TRUE).'.tmp';
					if(file_put_contents($cache_file_tmp, $html) && rename($cache_file_tmp, $cache_file))
						return $html; // From API call above.

					@unlink($cache_file_tmp); // Clean this up; if it exists.
					throw new \exception(sprintf(__('Unable to write: `%1$s`.', $this->text_domain), $cache_file));
				}
				throw new \exception(sprintf(__('Unable to parse PHP Markdown Extra into: `%1$s`.', $this->text_domain), $cache_file));
			}

			public function parsedown_extra_parse_cache($string)
			{
				static $parser; // Parser instance.

				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if($this->options['md_enable_flavor'] !== 'parsedown_extra')
					return $string; // Not this flavor.

				$md_cache_dir  = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';
				$md_cache_hash = sha1($string.$this->options['md_enable_flavor'].$this->options['md_enable_line_breaks']);
				$cache_file    = $md_cache_dir.'/'.$md_cache_hash.'.md.html'; // Markdown cache file.

				if(is_file($cache_file) && ($html = file_get_contents($cache_file)))
					return $html; // From cache; we already processed this.

				if(!isset($parser)) // A single instance of the Markdown class.
				{
					$parser = new \ParsedownExtra(); // Single instance of Parsedown Extra.
					$parser->setBreaksEnabled((boolean)$this->options['md_enable_line_breaks']);
				}
				if(($html = apply_filters(__METHOD__, $parser->text($string), get_defined_vars())))
				{
					if(!is_dir($md_cache_dir)) // Does the cache directory exist yet?
					{
						if(!mkdir($md_cache_dir, 0775, TRUE) || !is_dir($md_cache_dir))
							throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
							                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $md_cache_dir));
						file_put_contents($md_cache_dir.'/.htaccess', $this->htaccess_deny);
					}
					$cache_file_tmp = $cache_file.'.'.uniqid('', TRUE).'.tmp';
					if(file_put_contents($cache_file_tmp, $html) && rename($cache_file_tmp, $cache_file))
						return $html; // From API call above.

					@unlink($cache_file_tmp); // Clean this up; if it exists.
					throw new \exception(sprintf(__('Unable to write: `%1$s`.', $this->text_domain), $cache_file));
				}
				throw new \exception(sprintf(__('Unable to convert Parsedown Extra into: `%1$s`.', $this->text_domain), $cache_file));
			}

			public function custom_markdown_parse_cache($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if($this->options['md_enable_flavor'] !== 'custom_markdown')
					return $string; // Not this flavor.

				$md_cache_dir  = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';
				$md_cache_hash = sha1($string.$this->options['md_enable_flavor'].$this->options['md_enable_line_breaks']);
				$cache_file    = $md_cache_dir.'/'.$md_cache_hash.'.md.html'; // Markdown cache file.

				if(is_file($cache_file) && ($html = file_get_contents($cache_file)))
					return $html; // From cache; we already processed this.

				if(!$this->options['md_custom_parser'] || !is_callable($this->options['md_custom_parser']))
					throw new \exception(sprintf(__('Missing custom Markdown parser: `%1$s`.', $this->text_domain), $this->options['md_custom_parser']));

				if(($html = apply_filters(__METHOD__, $this->options['md_custom_parser']($string), get_defined_vars())))
				{
					if(!is_dir($md_cache_dir)) // Does the cache directory exist yet?
					{
						if(!mkdir($md_cache_dir, 0775, TRUE) || !is_dir($md_cache_dir))
							throw new \exception(__('Slight problem; unable to create a local cache directory.', $this->text_domain).
							                     ' '.sprintf(__('Please create this directory and make it writable: `%1$s`.', $this->text_domain), $md_cache_dir));
						file_put_contents($md_cache_dir.'/.htaccess', $this->htaccess_deny);
					}
					$cache_file_tmp = $cache_file.'.'.uniqid('', TRUE).'.tmp';
					if(file_put_contents($cache_file_tmp, $html) && rename($cache_file_tmp, $cache_file))
						return $html; // From API call above.

					@unlink($cache_file_tmp); // Clean this up; if it exists.
					throw new \exception(sprintf(__('Unable to write: `%1$s`.', $this->text_domain), $cache_file));
				}
				throw new \exception(sprintf(__('Custom API failure. Unable to parse Markdown into: `%1$s`.', $this->text_domain), $cache_file));
			}

			public function shortcodes($attr = '', $content = '', $tag = '')
			{
				static $shortcodes; // Static instance cache.

				if(!isset($shortcodes)) // Singleton class instance yet?
				{
					require_once dirname(__FILE__).'/shortcodes.php';
					$shortcodes = new shortcodes(); // New class instance.
				}
				$string = $shortcodes->$tag((array)$attr, $content, $tag); // Shortcode tag parser.

				return apply_filters(__METHOD__, $string, get_defined_vars()); // Give filters a chance too.
			}

			public function esc_shortcodes($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(empty($GLOBALS['shortcode_tags']))
					goto finale; // Nothing to do.

				if(strpos($string, '[') === FALSE)
					goto finale; // No `[` shortcodes.

				$spcsm           = // Tokenize; but leave shortcodes.
					$this->spcsm_tokens($string, array('pre', 'code', 'samp', 'md_fences'), __FUNCTION__);
				$spcsm['string'] = preg_replace_callback('/'.get_shortcode_regex().'/s', function ($m)
				{
					if($m[1] === '[' && $m[6] === ']')
						return $m[0]; // Escaped already.

					return '['.$m[0].']'; // Escape.

				}, $spcsm['string']);
				$string          = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function esc_raw($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(empty($GLOBALS['shortcode_tags']))
					goto finale; // Nothing to do.

				if(strpos($string, '[') === FALSE)
					goto finale; // No `[` shortcodes.

				static $shortcode_regex; // Cached statically.
				if(!isset($shortcode_tag_names, $shortcode_regex))
				{
					$shortcode_tag_names = array_map(function ($value)
					{
						preg_quote($value, '/');
					}, array_keys($GLOBALS['shortcode_tags']));
					$shortcode_regex     = '/\[\/?(?:'.implode('|', $shortcode_tag_names).')(?:\]|\s+[^\]]*\])/';
				}
				$string = preg_replace_callback($shortcode_regex, function ($m)
				{
					return str_replace(array('[', ']'), array('%#%{%#%', '%#%}%#%'), $m[0]); // Tokenize.

				}, $string); // Shortcodes now escaped w/ tokenized curly brackets.

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function unesc_raw($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(strpos($string, '%#%') === FALSE)
					goto finale; // No `%#%` tokens.

				$string = str_replace(array('%#%{%#%', '%#%}%#%'), array('[', ']'), $string);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function parse_shortcodes($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(strpos($string, '[') === FALSE)
					goto finale; // No shortcodes.

				$spcsm           = // Tokenize; but leave shortcodes.
					$this->spcsm_tokens($string, array('pre', 'code', 'samp', 'md_fences'), __FUNCTION__);
				$spcsm['string'] = do_shortcode(shortcode_unautop($spcsm['string']));
				$string          = $this->spcsm_restore($spcsm);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function pcs_format($string)
			{
				if(!($string = trim((string)$string)))
					return $string; // Empty.

				if(!preg_match('/\<(?:pre|code|samp)/i', $string))
					goto finale; // Nothing to format.

				strip_leading_whitespace: // Resolve `white-space:pre` issues.

				$pre_code_samp = // HTML `<pre|code|samp>` tags.
					'/(?P<tag_open>\<(?:pre|code|samp))'. // Tag open; e.g. `<pre|code|samp`.
					'(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'. // Attributes & `>`.
					'(?P<whitespace>\s+)/i'; // Possible leading whitespace.

				$string = preg_replace($pre_code_samp, '${1}${2}', $string);

				add_nested_pre_code_markers: // For greater CSS selector flexibility.
				// Handled via JS also; but doing this in code prevents a FOUC on-site.

				$pre_code = // Nested HTML `<pre><code>` tags.
					'/(?P<tag_open_pre>\<pre)'. // Tag open; e.g. `<pre`.
					'(?P<tag_open_pre_attrs_bracket>\>|\s+[^>]*\>)'. // Attributes & `>`.
					'(?P<whitespace>\s*)'. // Possible whitespace between these tags.
					'(?P<tag_open_code>\<code)'. // Nested tag open; e.g. `<code`.
					'(?P<tag_open_code_attrs_bracket>\>|\s+[^>]*\>)/i'; // Attributes & `>`.

				$string = preg_replace_callback($pre_code, function ($m)
				{
					$regex_class_attr = '/\sclass\s*\=\s*["\']/i'; // Define once.

					if(preg_match($regex_class_attr, $m['tag_open_pre_attrs_bracket']))
						$m['tag_open_pre_attrs_bracket'] = // Add class to those which exist already.
							preg_replace($regex_class_attr, '${0}code ', $m['tag_open_pre_attrs_bracket']);
					else $m['tag_open_pre_attrs_bracket'] = str_replace('>', ' class="code">', $m['tag_open_pre_attrs_bracket']);

					if(preg_match($regex_class_attr, $m['tag_open_code_attrs_bracket']))
						$m['tag_open_code_attrs_bracket'] = // Add class to those which exist already.
							preg_replace($regex_class_attr, '${0}pre ', $m['tag_open_code_attrs_bracket']);
					else $m['tag_open_code_attrs_bracket'] = str_replace('>', ' class="pre">', $m['tag_open_code_attrs_bracket']);

					return $m['tag_open_pre'].$m['tag_open_pre_attrs_bracket'].$m['tag_open_code'].$m['tag_open_code_attrs_bracket']; #

				}, $string); // Intentionally excluding any whitespace between tags.

				unparse_raw_more_tag_span: // Unparse `<!--more-->` tag.

				if(strpos($string, '&lt;span id="more-') === FALSE || !is_singular())
					goto finale; // Not necessary; skip to finale in this case.

				$string = str_replace('&lt;span id="more-'.get_the_ID().'"&gt;&lt;/span&gt;', '&lt;!--more--&gt;', $string);

				finale: // Target point; grand finale (return).

				return apply_filters(__METHOD__, $string, get_defined_vars());
			}

			public function md_cache_purge()
			{
				$counter = 0; // Initialize counter.

				if(!$this->options['md_enable_flavor'])
					return $counter; // Markdown not enabled here.

				$md_cache_dir     = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';
				$md_cache_max_age = strtotime('-'.$this->options['md_cache_max_age']);

				if(!is_dir($md_cache_dir) || !($opendir = opendir($md_cache_dir)))
					return $counter; // Nothing to do; or NOT possible.

				@set_time_limit(1800); // In case of HUGE sites w/ a very large directory.

				while(($_file = $_basename = readdir($opendir)) !== FALSE && ($_file = $md_cache_dir.'/'.$_file))
					if(is_file($_file) && substr($_file, -8) === '.md.html' && filemtime($_file) < $md_cache_max_age)
						if(!unlink($_file)) throw new \exception(sprintf(__('Unable to purge: `%1$s`.', $this->text_domain), $_file));
						else $counter++; // Increment counter for each file we purge.

				unset($_file, $_basename); // Just a little housekeeping.
				closedir($opendir); // Close directory.

				return apply_filters(__METHOD__, $counter, get_defined_vars());
			}

			public function md_cache_clear()
			{
				$counter = 0; // Initialize counter.

				if(!$this->options['md_enable_flavor'])
					return $counter; // Markdown not enabled here.

				$md_cache_dir = WP_CONTENT_DIR.'/'.$this->options['cache_dir'].'/markdown';

				if(!is_dir($md_cache_dir) || !($opendir = opendir($md_cache_dir)))
					return $counter; // Nothing to do; or NOT possible.

				@set_time_limit(1800); // In case of HUGE sites w/ a very large directory.

				while(($_file = $_basename = readdir($opendir)) !== FALSE && ($_file = $md_cache_dir.'/'.$_file))
					if(is_file($_file) && substr($_file, -8) === '.md.html') // Don't care about time here; clearing all files.
						if(!unlink($_file)) throw new \exception(sprintf(__('Unable to clear: `%1$s`.', $this->text_domain), $_file));
						else $counter++; // Increment counter for each file we clear.

				unset($_file, $_basename); // Just a little housekeeping.
				closedir($opendir); // Close directory.

				return apply_filters(__METHOD__, $counter, get_defined_vars());
			}

			public function check_update_sync_version()
			{
				if(!$this->options['update_sync_version_check'])
					return; // Functionality is disabled here.

				if(!current_user_can('update_themes')) return; // Nothing to do.

				if($this->options['last_update_sync_version_check'] >= strtotime('-1 hour'))
					return; // No reason to keep checking on this.

				$this->options['last_update_sync_version_check'] = time(); // Update; checking now.
				update_option(__NAMESPACE__.'_options', $this->options); // Save this option value now.

				$update_sync_url       = 'https://www.websharks-inc.com/products/update-sync.php';
				$update_sync_post_vars = array('data' => array('slug'    => str_replace('_', '-', __NAMESPACE__).'-pro',
				                                               'version' => 'latest-stable', 'version_check_only' => '1'));

				$update_sync_response = wp_remote_post($update_sync_url, array('body' => $update_sync_post_vars));
				$update_sync_response = json_decode(wp_remote_retrieve_body($update_sync_response), TRUE);

				if(empty($update_sync_response['version']) || version_compare($this->version, $update_sync_response['version'], '>='))
					return; // Current version is the latest stable version. Nothing more to do here.

				$update_sync_page = self_admin_url('/admin.php'); // Page that initiates an update.
				$update_sync_page = add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-update-sync')), $update_sync_page);

				$notices                                   = (is_array($notices = get_option(__NAMESPACE__.'_notices'))) ? $notices : array();
				$notices['persistent-update-sync-version'] = // This creates a persistent notice; e.g. it must be cleared away by the site owner.
					sprintf(__('<strong>%1$s:</strong> a new version is now available. Please <a href="%2$s">upgrade to v%3$s</a>.', $this->text_domain),
					        $this->name, $update_sync_page, $update_sync_response['version']);
				update_option(__NAMESPACE__.'_notices', $notices);
			}

			public function pre_site_transient_update_themes($transient)
			{
				if(!is_admin() || $GLOBALS['pagenow'] !== 'update.php')
					return $transient; // Nothing to do here.

				$_r = array_map('trim', stripslashes_deep($_REQUEST));

				if(empty($_r['action']) || $_r['action'] !== 'upgrade-theme')
					return $transient; // Nothing to do here.

				if(!current_user_can('update_themes')) return $transient; // Nothing to do here.

				if(empty($_r['_wpnonce']) || !wp_verify_nonce((string)$_r['_wpnonce'], 'upgrade-theme_'.str_replace('_', '-', __NAMESPACE__)))
					return $transient; // Nothing to do here.

				if(empty($_r[__NAMESPACE__.'__update_version']) || !($update_version = (string)$_r[__NAMESPACE__.'__update_version']))
					return $transient; // Nothing to do here.

				if(empty($_r[__NAMESPACE__.'__update_zip']) || !($update_zip = base64_decode((string)$_r[__NAMESPACE__.'__update_zip'])))
					return $transient; // Nothing to do here.

				if(!is_object($transient)) $transient = new \stdClass();

				$transient->last_checked                                   = time();
				$transient->checked[str_replace('_', '-', __NAMESPACE__)]  = $this->version;
				$transient->response[str_replace('_', '-', __NAMESPACE__)] = array(
					'url'         => add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-update-sync')),
					                               self_admin_url('/admin.php')),
					'new_version' => $update_version, 'package' => $update_zip);
				// TODO: test this with a child theme running against s2Clean.

				return $transient; // Modified now.
			}

			public function enqueue_admin_styles()
			{
				if(empty($_GET['page']) || strpos($_GET['page'], __NAMESPACE__) !== 0)
					return; // Nothing to do; NOT a theme page in the administrative area.

				$deps = array(__NAMESPACE__.'-fa', __NAMESPACE__.'-cm'); // Theme dependencies.

				wp_enqueue_style(__NAMESPACE__.'-fa', set_url_scheme('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'), array(), NULL, 'all');
				wp_enqueue_style(__NAMESPACE__.'-cm', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/codemirror.min.css'), array(), NULL, 'all');
				wp_enqueue_style(__NAMESPACE__.'-cm-theme', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/theme/twilight.css'), array(__NAMESPACE__.'-cm'), NULL, 'all');

				wp_enqueue_style(__NAMESPACE__, $this->url('/client-s/css/menu-pages.min.css'), $deps, $this->version, 'all');
			}

			public function enqueue_admin_scripts()
			{
				if(empty($_GET['page']) || strpos($_GET['page'], __NAMESPACE__) !== 0)
					return; // Nothing to do; NOT a theme page in the administrative area.

				$deps = array('jquery', __NAMESPACE__.'-cm'); // Theme dependencies.

				wp_enqueue_script(__NAMESPACE__.'-cm', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/codemirror.min.js'), array(), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-matchbrackets', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/addon/edit/matchbrackets.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-htmlmixed', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/htmlmixed/htmlmixed.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-xml', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/xml/xml.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-javascript', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/javascript/javascript.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-css', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/css/css.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-clike', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/clike/clike.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);
				wp_enqueue_script(__NAMESPACE__.'-cm-php', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/codemirror/4.7.0/mode/php/php.js'), array(__NAMESPACE__.'-cm'), NULL, TRUE);

				wp_enqueue_script(__NAMESPACE__, $this->url('/client-s/js/menu-pages.min.js'), $deps, $this->version, TRUE);
			}

			public function all_admin_notices()
			{
				if(($notices = (is_array($notices = get_option(__NAMESPACE__.'_notices'))) ? $notices : array()))
				{
					$notices = $updated_notices = array_unique($notices); // De-dupe.

					foreach(array_keys($updated_notices) as $_key) if(strpos($_key, 'persistent-') !== 0)
						unset($updated_notices[$_key]); // Leave persistent notices; ditch others.
					unset($_key); // Housekeeping after updating notices.

					update_option(__NAMESPACE__.'_notices', $updated_notices);
				}
				if(current_user_can('edit_theme_options')) foreach($notices as $_key => $_notice)
				{
					$_dismiss = ''; // Initialize empty string; e.g. reset value on each pass.
					if(strpos($_key, 'persistent-') === 0) // A dismissal link is needed in this case?
					{
						$_dismiss_css = 'display:inline-block; float:right; margin:0 0 0 15px; text-decoration:none; font-weight:bold;';
						$_dismiss     = add_query_arg(urlencode_deep(array('theme' => array('dismiss_notice' => array('key' => $_key)), '_wpnonce' => wp_create_nonce())));
						$_dismiss     = '<a style="'.esc_attr($_dismiss_css).'" href="'.esc_attr($_dismiss).'">'.__('dismiss &times;', $this->text_domain).'</a>';
					}
					echo apply_filters(__METHOD__.'__notice', '<div class="updated"><p>'.$_notice.$_dismiss.'</p></div>', get_defined_vars());
				}
				unset($_key, $_notice, $_dismiss_css, $_dismiss); // Housekeeping.
			}

			public function all_admin_errors()
			{
				if(($errors = (is_array($errors = get_option(__NAMESPACE__.'_errors'))) ? $errors : array()))
				{
					$errors = $updated_errors = array_unique($errors); // De-dupe.

					foreach(array_keys($updated_errors) as $_key) if(strpos($_key, 'persistent-') !== 0)
						unset($updated_errors[$_key]); // Leave persistent errors; ditch others.
					unset($_key); // Housekeeping after updating notices.

					update_option(__NAMESPACE__.'_errors', $updated_errors);
				}
				if(current_user_can('edit_theme_options')) foreach($errors as $_key => $_error)
				{
					$_dismiss = ''; // Initialize empty string; e.g. reset value on each pass.
					if(strpos($_key, 'persistent-') === 0) // A dismissal link is needed in this case?
					{
						$_dismiss_css = 'display:inline-block; float:right; margin:0 0 0 15px; text-decoration:none; font-weight:bold;';
						$_dismiss     = add_query_arg(urlencode_deep(array('theme' => array('dismiss_error' => array('key' => $_key)), '_wpnonce' => wp_create_nonce())));
						$_dismiss     = '<a style="'.esc_attr($_dismiss_css).'" href="'.esc_attr($_dismiss).'">'.__('dismiss &times;', $this->text_domain).'</a>';
					}
					echo apply_filters(__METHOD__.'__error', '<div class="error"><p>'.$_error.$_dismiss.'</p></div>', get_defined_vars());
				}
				unset($_key, $_error, $_dismiss_css, $_dismiss); // Housekeeping.
			}

			public function add_menu_pages()
			{
				add_menu_page($this->name, $this->name, // Product name (main menu).
				              'edit_theme_options', __NAMESPACE__, array($this, 'menu_page_options'),
				              $this->url('/client-s/images/menu-icon.png'));

				add_submenu_page(__NAMESPACE__, __('Theme Options', $this->text_domain), __('Theme Options', $this->text_domain),
				                 'edit_theme_options', __NAMESPACE__, array($this, 'menu_page_options'));

				add_submenu_page(__NAMESPACE__, __('Theme Updater', $this->text_domain), __('Theme Updater', $this->text_domain),
				                 'update_themes', __NAMESPACE__.'-update-sync', array($this, 'menu_page_update_sync'));
			}

			public function menu_page_options()
			{
				require_once dirname(__FILE__).'/menu-pages.php';
				$menu_pages = new menu_pages();
				$menu_pages->options();
			}

			public function menu_page_update_sync()
			{
				require_once dirname(__FILE__).'/menu-pages.php';
				$menu_pages = new menu_pages();
				$menu_pages->update_sync();
			}

			public function add_meta_boxes($post_type)
			{
				$excluded_post_types = array('link', 'comment', 'revision', 'attachment', 'nav_menu_item', 'snippet', 'redirect');
				$excluded_post_types = apply_filters(__METHOD__.'__excluded_types', $excluded_post_types, get_defined_vars());

				if(in_array($post_type, array_keys(get_post_types()), TRUE) && !in_array($post_type, $excluded_post_types, TRUE))
					add_meta_box(__NAMESPACE__.'--custom-fields', __('Custom Fields Supported by Theme', $this->text_domain),
					             array($this, 'custom_fields_meta_box'), (string)$post_type, 'normal', 'high');
			}

			public function custom_fields_meta_box()
			{
				require_once dirname(__FILE__).'/menu-pages.php';
				$menu_pages = new menu_pages();
				$menu_pages->custom_fields_meta_box();
			}

			public $htaccess_deny = "<IfModule authz_core_module>\n\tRequire all denied\n</IfModule>\n<IfModule !authz_core_module>\n\tdeny from all\n</IfModule>";

		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\theme'))
	{
		/**
		 * @return theme Class instance.
		 */
		function theme() // Easy reference in template files.
		{
			return $GLOBALS[__NAMESPACE__];
		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\is_home_page'))
	{
		/**
		 * @return boolean TRUE is is home page.
		 */
		function is_home_page() // For clarity.
		{
			return is_front_page() && !is_home();
		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\is_blog_page'))
	{
		/**
		 * @return boolean TRUE is is blog page.
		 */
		function is_blog_page() // For clarity.
		{
			return is_home();
		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\post_count_gt0'))
	{
		/**
		 * @return boolean TRUE if post count > `0`.
		 */
		function post_count_gt0()
		{
			return (boolean)$GLOBALS['wp_query']->post_count;
		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\is_super_sticky'))
	{
		/**
		 * @return boolean TRUE if super sticky.
		 */
		function is_super_sticky() // Like `post_class()`.
		{
			return is_sticky() && is_blog_page() && !is_paged();
		}
	}
	if(!function_exists('\\'.__NAMESPACE__.'\\wp_query'))
	{
		/**
		 * @return \WP_Query Class instance.
		 */
		function wp_query() // Easy access.
		{
			return $GLOBALS['wp_query'];
		}
	}

	do_action(__NAMESPACE__); // Child themes can use this hook to the extend the base class.

	if(!isset($GLOBALS[__NAMESPACE__])) // If NOT already defined by a child theme.
		$GLOBALS[__NAMESPACE__] = new theme(); // New theme instance.
}
namespace // Easy global reference.
{
	if(!function_exists('s2clean'))
	{
		/**
		 * @return \s2clean\theme Class instance.
		 */
		function s2clean() // Easy reference for plugins.
		{
			return $GLOBALS[__FUNCTION__];
		}
	}
}
