<?php
namespace s2clean // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	class menu_pages // Theme options.
	{
		/**
		 * @var theme Theme instance.
		 */
		protected $theme; // Set by constructor.

		public function __construct()
		{
			$this->theme = theme();
		}

		public function options()
		{
			echo '<form id="theme-menu-page" class="theme-menu-page" method="post" enctype="multipart/form-data"'.
			     ' action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce())), self_admin_url('/admin.php'))).'">'."\n";

			echo '<div class="theme-menu-page-heading">'."\n";

			echo '   <button type="submit">'.__('Save', $this->theme->text_domain).' <i class="fa fa-save"></i></button>'."\n";

			echo '   <button type="button" class="theme-menu-page-restore-defaults"'. // Restores default options.
			     '      data-confirmation="'.esc_attr(__('Restore default theme options? You will lose all of your current settings! Are you absolutely sure about this?', $this->theme->text_domain)).'"'.
			     '      data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), 'theme' => array('restore_default_options' => '1'))), self_admin_url('/admin.php'))).'">'.
			     '      '.__('Restore', $this->theme->text_domain).' <i class="fa fa-ambulance"></i></button>'."\n";

			echo '   <div class="theme-menu-page-panel-togglers" title="'.esc_attr(__('All Panels', $this->theme->text_domain)).'">'."\n";
			echo '      <button type="button" class="theme-menu-page-panels-open"><i class="fa fa-chevron-down"></i></button>'."\n";
			echo '      <button type="button" class="theme-menu-page-panels-close"><i class="fa fa-chevron-up"></i></button>'."\n";
			echo '   </div>'."\n";

			echo '   <div class="theme-menu-page-upsells">'."\n";
			if(current_user_can('update_themes')) echo '<a href="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-update-sync')), self_admin_url('/admin.php'))).'"><i class="fa fa-magic"></i> '.__('Theme Updater', $this->theme->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://www.websharks-inc.com/r/'.str_replace('_', '-', __NAMESPACE__).'-theme-subscribe/').'" target="_blank"><i class="fa fa-envelope"></i> '.sprintf(__('%1$s Newsletter (Subscribe)', $this->theme->text_domain), $this->theme->name).'</a>'."\n";
			echo '   </div>'."\n";

			echo '   <img src="'.$this->theme->url('/client-s/images/options.png').'" alt="'.esc_attr(__('Theme Options', $this->theme->text_domain)).'" />'."\n";

			echo '</div>'."\n";

			if(!empty($_REQUEST[__NAMESPACE__.'__updated'])) // Options updated successfully?
			{
				echo '<div class="theme-menu-page-notice notice">'."\n";
				echo '   <i class="fa fa-thumbs-up"></i> '.__('Options updated successfully.', $this->theme->text_domain)."\n";
				echo '</div>'."\n";
			}
			if(!empty($_REQUEST[__NAMESPACE__.'__restored'])) // Restored default options?
			{
				echo '<div class="theme-menu-page-notice notice">'."\n";
				echo '   <i class="fa fa-thumbs-up"></i> '.__('Default options successfully restored.', $this->theme->text_domain)."\n";
				echo '</div>'."\n";
			}
			echo '<div class="theme-menu-page-body">'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-shield"></i> '.__('Deactivation Safeguards', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Uninstall on Deactivation; or Safeguard Options?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('<strong>Tip:</strong> By default, if you deactivate this theme in WordPress; nothing is lost. However, if you want to uninstall it completely you should set this to <code>Yes</code> and <strong>THEN</strong> deactivate it in WordPress. This way options are erased, CRON jobs are terminated, etc. It erases itself from existence completely.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][uninstall_on_deactivation]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="0"'.selected($this->theme->options['uninstall_on_deactivation'], '0', FALSE).'>'.__('No, if I deactivate this theme please safeguard my options (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['uninstall_on_deactivation'], '1', FALSE).'>'.__('Yes, uninstall (completely erase) this theme on deactivation.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-info-circle"></i> '.__('Custom Fields', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/cf-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Fine-Tuning Theme Options on a per Post/Page Basis...', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('From your Post/Page editing station in WordPress, please review the meta box (as seen in this screenshot to the right). This section is titled "<strong>Custom Fields Supported by Theme</strong>".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('In your Post/Page editing station you will find further details and documentation on several Custom Fields that allow for granular control at the Post/Page level.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('For instance, you can choose to disable the Navbar, Sidebar, Footbar (one of these; or all of these) on specific Posts/Pages, and there\'s more too!', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> All Custom Fields for a Post/Page are optional. The defaults work just fine 99% of the time. Granular control is needed only when you deem necessary.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('No-Cache Headers', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/error-key-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Prevent Browsers from Caching Dynamic PHP Content?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This does NOT prevent you from using plugins like Quick Cache or W3 Total Cache. It also does NOT impact things like images, JavaScript or CSS files. It simply reduces confusion on sites that have members logging in and out. If a user logs in, you want to be sure they aren\'nt still seeing the cached version of a page they visited before they logged-in; and vice-versa. When a user logs out, you want to be sure they aren\'t still seeing the cached version of a page they visited while they were logged-in. See also: <a href="http://codex.wordpress.org/Function_Reference/nocache_headers" target="_blank">nocache_headers()</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][nocache_headers_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['nocache_headers_enable'], '1', FALSE).'>'.__('Yes, enable no-cache headers (recommended for dynamic membership sites).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['nocache_headers_enable'], '0', FALSE).'>'.__('No, use WordPress default behavior.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p><em>'.__('If your site is NOT going to provide membership, you can safely disable this feature.', $this->theme->text_domain).'</em></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Cache Directory', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/base-dir-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Base Directory for Theme-Related Cache Files', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This theme generates an internal cache for a few things. The theme\'s cache is designed to prevent any repetitive processing; i.e. to keep your site blazing fast at all times. For instance, Markdown processing is cached intelligently. Also, if you choose to use any shortcode that pulls trending content, this theme will cache trending content queries intelligently. Those are just a couple of examples.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('The default value for this field is normally just fine; i.e. it is usually NOT necessary to customize this any further.', $this->theme->text_domain).'</p>'."\n";
			echo '      <table style="width:100%; table-layout:auto;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">'.esc_html(WP_CONTENT_DIR).'/</td><td><input type="text" name="theme[save_options][cache_dir]" value="'.esc_attr($this->theme->options['cache_dir']).'" autocomplete="off" /></td></tr></table>'."\n";
			echo '      <p class="info">'.__('<strong>Note:</strong> It is not necessary to clear theme-related cache files. The cache is smart enough to keep itself up-to-date at all times automatically.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Feed Links', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/rss-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Enable Automatic Feed Links in <code>&lt;head&gt;</code>?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Unless you\'re running plugins that seriously alter WordPress XML/RSS feeds, it is best to leave this enabled. See also: <a href="http://codex.wordpress.org/Automatic_Feed_Links" target="_blank">Automatic Feed Links</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][automatic_feed_links_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['automatic_feed_links_enable'], '1', FALSE).'>'.__('Yes, enable automatic feed links (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['automatic_feed_links_enable'], '0', FALSE).'>'.__('No, do not enable.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('FeedBurner, or another Custom Feed Link?', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('You may want to use FeedBurner to power your feeds and make them available for everyone to subscribe to. FeedBurner is sometimes preferred for serving feeds as it has detailed statistics and display options such as sharing buttons. See: <a href="http://feedburner.com/" target="_blank">FeedBurner™</a>. If you signup with FeedBurner; or if you have another Custom Feed Link you\'d like to use, please enter the URL below (e.g. the custom URL for your primary feed). If you leave this blank a default WordPress feed is already provided automatically :-)', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][custom_feed_link]" value="'.esc_attr($this->theme->options['custom_feed_link']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('JavaScript', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Load JavaScript Files in the Footer?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('It\'s usually a good idea to load JavaScript files in the footer to reduce the number of HTTP requests required to load other more important resources; this way your site can be displayed quickly. See also: <a href="http://stackoverflow.com/questions/5329807/benefits-of-loading-js-at-the-bottom-as-opposed-to-the-top-of-the-document" target="_blank">this discussion at StackOverflow</a>. Note: this only impacts JavaScript files loaded by your theme; it will not impact the way JavaScript files are loaded by any plugins that you activate.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][scripts_in_footer]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['scripts_in_footer'], '1', FALSE).'>'.__('Yes, load theme-related JavaScript files in footer (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['scripts_in_footer'], '0', FALSE).'>'.__('No, load theme-related JavaScript files in the &lt;head&gt;.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Favicon', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://en.wikipedia.org/wiki/Favicon" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/fav-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Favicon Image URL (see: <a href="http://en.wikipedia.org/wiki/Favicon" target="_blank">Favicons</a>)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('The <a href="'.esc_attr($this->theme->default_options['favicon_url']).'" target="_blank">default favicon image</a> works for most sites. However, if you\'d like to customize this try <a href="http://www.convertico.com/" target="_blank">ConvertICO.com</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][favicon_url]" value="'.esc_attr($this->theme->options['favicon_url']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <p class="info"><strong>Tip:</strong> It is best to create your favicon at a larger size (i.e. larger than the most common 16x16 pixel usage). This way, if a device bookmarks your site, and it happens to need a larger size; your favicon will still look great! Try creating your favicon as a 128x128 PNG image with alpha transparency. Then, convert it to the ICO format.</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Custom Code', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom <code>wp_head</code> Elements (raw HTML in the document <code>&lt;head&gt;</code>)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If you integrate with other 3rd-party services that require code in the <code>&lt;head&gt;</code> of your site, this is where you paste it in :-) This is also a good place to add any custom CSS; e.g. <code>&lt;style type=&quot;text/css&quot;&gt;</code> tags. The code you enter here is applied globally to the entire site. If you have other custom elements that you\'d like to apply only on specific Posts/Pages, please consider using the Custom Field <code>wp_head_elements</code> in a given Post/Page. For further details, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_wp_head_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_wp_head_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom Header Elements (raw HTML at the top of every page on the site)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This is a good place to implement a custom logo, perhaps banner graphics (anything that should run across the top of the site; on every page). The code you enter here is applied globally to the entire site. If you have other custom elements that you\'d like to apply only on specific Posts/Pages, please consider using the Custom Field <code>header_elements</code> in a given Post/Page. For further details, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_header_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_header_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom Front Page Blog Header Elements (raw HTML displayed on the front page; when/if it\'s your blog)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If, and only if, you are using the WordPress default Reading Settings (i.e. your front page is a list of your most recent posts); this allows you to customize the front page blog header. This will be output at the top of your blog; after the navigation bar, and after any other custom header elements from the previous section. Or, if you leave this empty, a default blog header will be used instead.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_front_page_blog_header_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_front_page_blog_header_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom Content Footer Elements (raw HTML at the bottom of all singular content)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This is a good place to implement sharing buttons; or perhaps banner graphics (anything that should be presented after, but within, the content body). The code you enter here is applied globally to all singular content on the site. If you have other custom elements that you\'d like to apply only on specific Posts/Pages, please consider using the Custom Field <code>content_footer_elements</code> in a given Post/Page. For further details, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_content_footer_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_content_footer_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom Footer Elements (raw HTML after the content of every page on the site)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This is a good place to implement links to your most popular posts; or perhaps banner graphics (anything that should be presented after the content body is closed). The code you enter here is applied globally to the entire site. If you have other custom elements that you\'d like to apply only on specific Posts/Pages, please consider using the Custom Field <code>footer_elements</code> in a given Post/Page. For further details, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_footer_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_footer_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/custom-code-128.png')).'" class="float-right" style="width:64px;" />'."\n";
			echo '      <h3>'.__('Custom <code>wp_footer</code> Elements (raw HTML before closing <code>&lt;/body&gt;</code> tag)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If you integrate with other 3rd-party services that require code at the bottom of your site (before the closing <code>&lt;/body&gt;</code> tag), this is where you paste it in :-) This is also a good place to implement <strong>Google Analytics</strong> and/or other <code>&lt;script type=&quot;text/javascript&quot;&gt;</code> tags. The code you enter here is applied globally to the entire site. If you have other custom elements that you\'d like to apply only on specific Posts/Pages, please consider using the Custom Field <code>wp_footer_elements</code> in a given Post/Page. For further details, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme".', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="application/x-httpd-php"><textarea name="theme[save_options][site_custom_wp_footer_elements]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['site_custom_wp_footer_elements']).'</textarea></p>'."\n";
			echo '      <p><em>'.__('This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!', $this->theme->text_domain).'</em></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('SEO Settings', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Enable Automatic SEO Functionality?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This enables automatic meta tags for <code>robots</code>, <code>keywords</code>, and a <code>description</code>; making your site easier for search engines to index (and to help you avoid duplicate content warnings from search engine spiders). It also gives you the ability to customize things further (see below).', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('In addition, a few Custom Fields are enabled for individual Posts/Pages so you can tweak things further when/if necessary. To learn more about fine-tuning for SEO, please see the meta panel in your Post/Page editing station, titled: "Custom Fields Supported by Theme". There you\'ll have control over <code>seo_title</code>, <code>seo_keywords</code>, <code>seo_description</code>, and <code>seo_robots</code> on a per Post/Page basis.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][seo_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['seo_enable'], '1', FALSE).'>'.__('Yes, enable theme SEO functionality (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['seo_enable'], '0', FALSE).'>'.__('No, I prefer to use a separate plugin for this.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('SEO: Site Name', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Keep this short n\' sweet; ex: <code>Acme Corp.</code>', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_name]" value="'.esc_attr($this->theme->options['site_seo_name']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('SEO: Title Separator Char', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Separator; ex: <code>Blog | Category | Acme Corp.</code>', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_title_sep]" value="'.esc_attr($this->theme->options['site_seo_title_sep']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('SEO: Front Page Site Title', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Around 75 chars max; ex: <code>Acme Corp. | Tips on Growing Flowers, How to Grow Flowers</code>', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_title]" value="'.esc_attr($this->theme->options['site_seo_title']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('SEO: Front Page Keywords', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Comma-delimited; ex: <code>flowers, growing, acme</code>', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_keywords]" value="'.esc_attr($this->theme->options['site_seo_keywords']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('SEO: Front Page Description', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Approx 100 chars max; ex: <code>We grow flowers and discuss flower growing tips.</code>', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_description]" value="'.esc_attr($this->theme->options['site_seo_description']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('SEO: Front Page and Default Robots Meta Tag Value', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('See <a href="http://www.robotstxt.org/meta.html" target="_blank">www.robotstxt.org</a>; ex: <code>index,follow</code> (recommended)', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][site_seo_robots]" value="'.esc_attr($this->theme->options['site_seo_robots']).'" autocomplete="off" /></p>'."\n";
			echo '         <p>'.__('Tip: no matter what you configure here, all archive views (e.g. your blog index, search results, tags, date archives, etc); are always forced to: <code>noindex</code>; preventing duplicate content warnings from search engine crawlers. Also, please note that what you configure here is ignored completely if your blog is NOT even public yet; e.g. if your <strong><em>WordPress -› Reading Settings</em></strong> have search engine visiblity <strong>off</strong>.', $this->theme->text_domain).'</p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('OpenGraph', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <a href="http://ogp.me/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/og-128.png')).'" class="float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable OpenGraph Meta Tags?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('It is important to implement OpenGraph meta tags on every page of your site. This is how Facebook, Google+ and countless other services find an image thumbnail and details for content that you publish. You want these networks to have something to display for each Post/Page when your content is shared and/or cross-referenced on networks such as these. Therefore, unless you have another plugin that you would like to use for this, there is no reason to disable it here.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][open_graph_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['open_graph_enable'], '1', FALSE).'>'.__('Yes, automatically generate OpenGraph meta tags (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['open_graph_enable'], '0', FALSE).'>'.__('No, I have a separate plugin that will handle this.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Front Page and Default Image Thumbnail (Required)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('If you publish content w/o defining a Featured Image for a specific Post/Page, and there are no images in the content either; this will be used as a fallback. Ordinarily this is simply your logo image; or a screenshot of your home page. Recommended size: 512 x 512 pixels (.jpg or .png).', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][default_open_graph_img_url]" value="'.esc_attr($this->theme->options['default_open_graph_img_url']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Bootstrap', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://getbootstrap.com/css/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/html5-bs-icons.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Bootstrap Responsive (e.g. Mobile-Friendly) Theme Selection', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This theme is built on top of the <a href="http://getbootstrap.com/" target="_blank">Bootstrap CSS framework</a> (mobile friendly). It uses the default Bootstrap theme (recommended). However, if you have another Bootstrap theme you prefer to use, please supply it\'s URL below. See also: <a href="http://bootswatch.com/" target="_blank">BootSwatch.com</a>. Any theme you find at BootSwatch.com can be loaded via the BootStrap CDN. See: <a href="http://www.bootstrapcdn.com/#bootswatch_tab" target="_blank">BootStrap CDN (BootSwatch)</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][bootstrap_theme_url]" value="'.esc_attr($this->theme->options['bootstrap_theme_url']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <p>'.__('It\'s also possible to build your own Bootstrap theme. See: <a href="http://getbootstrap.com/customize/" target="_blank">http://getbootstrap.com/customize/</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> Since this theme is built on Bootstrap, it\'s mobile-friendly. This theme also makes a valiant effort to style content in Posts/Pages that you create, so they remain mobile-friendly too (automatically). However, if you intend to publish content that looks <strong>really great</strong> on mobile devices, we recommend that you review the documentation regarding Bootstrap\'s Responsive Utility Classes and try to incorporate these into the content that you publish when necessary. See: <a href="http://getbootstrap.com/css/#responsive-utilities" target="_blank">Responsive Utility Classes</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> It\'s also a good idea to review <a href="http://getbootstrap.com/components/" target="_blank">all CSS framework classes</a> made available in Bootstrap. They are all mobile-friendly, and they can help you style content easily when you want something more than just text &amp; images. This theme also incorporates <a href="http://getbootstrap.com/javascript/" target="_blank">Bootstrap JavaScript</a> and <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">FontAwesome icons</a> that you can take advantage of, if you like.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Background', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" style="width:48px;" />'."\n";
			echo '      <h3>'.__('Background CSS (Modify Color/Image &amp; More)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If you would like to customize the background used in this theme, please modify the CSS below. Need help? See: <a href="http://www.w3schools.com/cssref/css3_pr_background.asp" target="_blank">CSS Background Properties</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <table style="width:100%; table-layout:auto;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;"><code>body { background:</code></td><td><input type="text" name="theme[save_options][background_css]" value="'.esc_attr($this->theme->options['background_css']).'" autocomplete="off" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;"><code>; }</code></td></tr></table>'."\n";
			echo '      <p>'.__('If you leave this field blank there will be NO custom background; only the Bootstrap framework/theme.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Web Fonts', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://www.google.com/fonts" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/webf-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Optional Web Fonts; such as <a href="http://www.google.com/fonts" target="_blank">Google Fonts</a>', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('If you\'d like to modify the default web fonts used in this theme, please adjust the URL &amp; CSS below.', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('URL (or Script Tag) that Loads @font-face Declaration(s)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p style="margin-bottom:0;">'.__('Google Fonts Example (CSS): <code>//fonts.googleapis.com/css?family=Cherry+Swash|Flavors</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p style="margin-top:0;">'.__('Adobe Edge Fonts Example (JS): <code>&lt;script src="//use.edgefonts.net/abril-fatface;advent-pro.js"&gt;&lt;/script&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][fonts_location]" value="'.esc_attr($this->theme->options['fonts_location']).'" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('CSS to Specify which Elements use which Font Families</h3>', $this->theme->text_domain)."\n";
			echo '      <p>'.__('Example: <code>h1, .h1 { font-family: \'Cherry Swash\'; } h2, .h2 { font-family: \'Flavors\'; }</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p data-cm-mode="text/css"><textarea name="theme[save_options][fonts_css]" rows="10" spellcheck="false">'.format_to_edit($this->theme->options['fonts_css']).'</textarea></p>'."\n";
			echo '      <p>'.__('If you leave these fields empty, no custom web fonts will be loaded up.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Navbar', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/nb-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Fixed Top Navigation Bar w/ Dynamic Menu Items', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('The Navbar runs across the top of the site in a fixed position; e.g. it remains in the same place as you scroll the page. To customize your Navbar, please see: <strong><em>Dashboard -› Appearance -› Menus</em></strong>. There you can build custom menus with WordPress and add them to your Navbar.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('This theme also adds several Bootstrap-friendly options to WordPress under <strong><em>Dashboard -› Appearance -› Menus</em></strong>. This makes it easy to build menus in WordPress that are 100% compatible with mobile-friendly styles provided by Bootstrap. You can even add things like menu dividers and icon-only navigation items.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> If you\'re looking for a way to display menu items only for certain types of users; or only when some other conditions apply; please take a look under <strong><em>Dashboard -› Appearance -› Menus</em></strong>. This theme adds a "Conditional Logic" field for advanced site owners and developers.', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Navbar Class (Coloration/Style)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('A <code>default</code> navbar is generally a light color with dark text. An <code>inverse</code> navbar is generally a dark color with light text. That\'s the case with the default Bootstrap CSS theme. However, the navbar class that you choose is impacted by the Bootstrap theme that you\'ve selected. A <code>default</code> navbar is suggested for most sites.', $this->theme->text_domain).'</p>'."\n";
			echo '      <table style="width:100%; table-layout:auto;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;"><code>class=&quot;navbar-</code></td><td><input type="text" name="theme[save_options][navbar_class]" value="'.esc_attr($this->theme->options['navbar_class']).'" autocomplete="off" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;"><code>&quot;</code></td></tr></table>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Navbar Brand; Logo Image (30px Tall)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <table style="width:100%; table-layout:auto;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">Width: <input type="text" name="theme[save_options][navbar_brand_img_width]" value="'.esc_attr($this->theme->options['navbar_brand_img_width']).'" style="width:60px;" autocomplete="off" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;">URL:&nbsp;</td><td><input type="text" name="theme[save_options][navbar_brand_img_url]" value="'.esc_attr($this->theme->options['navbar_brand_img_url']).'" /></td></tr></table>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Enable Navbar Search Box?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p><select name="theme[save_options][navbar_search_box_enable]" class="no-if-enabled" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['navbar_search_box_enable'], '1', FALSE).'>'.__('Yes, enable search box in navigation bar.', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['navbar_search_box_enable'], '0', FALSE).'>'.__('No, I prefer not to use this.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Enable Navbar Login Box?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p><select name="theme[save_options][navbar_login_box_enable]" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['navbar_login_box_enable'], '1', FALSE).'>'.__('Yes, enable login box in navigation bar.', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['navbar_login_box_enable'], '0', FALSE).'>'.__('No, I prefer not to use this.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Navbar Login Box; Redirect URL (After a User Logs In)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('This can be <code>%%previous%%</code> (for the page they were previously viewing); or a URL of your choosing.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][navbar_login_redirect_to]" value="'.esc_attr($this->theme->options['navbar_login_redirect_to']).'" autocomplete="off" /></p>'."\n";
			echo '         <h3>'.__('Navbar Login Box; Redirect URL (After Logout)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('This can be <code>%%previous%%</code> (for the page they were previously viewing); or a URL of your choosing.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][navbar_logout_redirect_to]" value="'.esc_attr($this->theme->options['navbar_logout_redirect_to']).'" autocomplete="off" /></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Navbar Login Box; Redirect Always HTTP?', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('This is helpful if you are running WordPress with <code>FORCE_SSL_LOGIN</code> or <code>FORCE_SSL_ADMIN</code> enabled (see <a href="http://codex.wordpress.org/Administration_Over_SSL" target="_blank">Administration Over SSL</a>). When users log in/out of your site there is a chance they\'ll end up viewing your site in the SSL (HTTPS) mode by accident (on the front-end of your site). This is usually not desirable, as SSL tends to be slower. Enabling this will prevent that from happening; so that you may continue to run WordPress Administrative areas over SSL without negatively impacting users of your site.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><select name="theme[save_options][navbar_loginout_redirect_always_http]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->theme->options['navbar_loginout_redirect_always_http'], '1', FALSE).'>'.__('Yes, login/logout redirections always occur over HTTP.', $this->theme->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->theme->options['navbar_loginout_redirect_always_http'], '0', FALSE).'>'.__('No, I would rather not modify this behavior.', $this->theme->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Navbar Login Box; Registration URL (if Allowed)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('If you allow users to register on your site (for free); a registration link will appear in the Login Box automatically. If you prefer, you can specify a different URL. Perhaps a URL that provides further details about registration (before they actually register); or maybe you have a custom registration form of your own. If so, please enter that URL here.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p>'.__('Note: you can enable free registration in your WordPress Settings under <strong><em>Dashboard -› Settings -› General</em></strong>. Or, with the <a href="http://wordpress.org/plugins/s2member/" target="_blank">s2Member</a> plugin installed, Open Registration can be enabled from: <strong><em>Dasboard -› s2Member -› General Options -› Open Registration</em></strong>. Other plugins may offer similar functionality.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][navbar_login_registration_url]" value="'.esc_attr($this->theme->options['navbar_login_registration_url']).'" autocomplete="off" /></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Navbar Login/Registration Box; via AJAX?', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('If enabled, a simplified login/registration system is implemented by the Navbar Login Box. For logins, the underlying authentication will take place via JavaScript (AJAX). Upon logging into the site, the Navbar Login Box will simply close; and the visitor will remain on the same page. A login redirection URL is irrelevant in this case, since the entire point of this method is to keep a user on the same page they were visiting before having logged into the site. This is useful in cases where a user could potentially lose data they are working on by logging into the site (i.e. by being redirected elsewhere after login). With an AJAX login there is no redirection. For instance, logging a user into the site via AJAX might allow the user to continue filling out a form they were already working on (without much interruption). If you enable this, a Quick Registration system is also integrated for new users (assuming you have Open Registration enabled in WordPress). If registration is allowed, it will also take place via AJAX.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p>'.__('<strong>Advanced tip for developers:</strong> upon logging in successfully (via AJAX), a jQuery event is fired. Custom plugins may choose to integrate with this in more advanced ways; e.g. <code>$(\'#login-box\').on(\'loggedIn\', function(event, user){});</code> ... where <code>user</code> is an object containing details about the current user.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><select name="theme[save_options][navbar_login_registration_via_ajax]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->theme->options['navbar_login_registration_via_ajax'], '1', FALSE).'>'.__('Yes, perform login/registration via AJAX to keep users on the same page.', $this->theme->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->theme->options['navbar_login_registration_via_ajax'], '0', FALSE).'>'.__('No, I prefer to use the default Navbar Login Box (i.e. no AJAX).', $this->theme->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Markdown', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/md-128.png')).'" class="float-right" style="width:128px;" /></a>'."\n";
			echo '      <h3>'.__('Enable Markdown Content Processing?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Recommended for advanced users. This allows you to publish content in <a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Markdown syntax</a>. Or, with pure HTML, because Markdown allows for pure HTML to be incorporated also. Markdown processing implemented by this theme is also 100% compatible with <a href="http://codex.wordpress.org/Shortcode_API" target="_blank">WordPress Shortcodes</a>, and even with plugins like <a href="http://wordpress.org/plugins/ezphp/" target="_blank">ezPHP</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][md_enable_flavor]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="0"'.selected($this->theme->options['md_enable_flavor'], '0', FALSE).'>'.__('No, I prefer WordPress content filters only (default WP behavior)', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="parsedown_extra"'.selected($this->theme->options['md_enable_flavor'], 'parsedown_extra', FALSE).'>'.__('Yes, enable Markdown using Parsedown Extra (newer/faster)', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="php_markdown_extra"'.selected($this->theme->options['md_enable_flavor'], 'php_markdown_extra', FALSE).'>'.__('Yes, enable Markdown using PHP Markdown Extra', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info" style="display:block;">'.__('<strong>Tip:</strong> If Markdown processing is enabled, the default WordPress content filters; things like <code>wptexturize</code>, <code>convert_smilies</code>, <code>convert_chars</code>, <code>wp_autop</code> (Markdown handles this in it\'s own way), and <code>capital_P_dangit</code>; are all disabled in favor of Markdown. For advanced users this is usually a breath of fresh air! However, it\'s important to consider this before you enable Markdown. Ideally, you would enable Markdown on a site that is new—so you can publish your content in Markdown from the start (or with a mix of Markdown and pure HTML). That being said, it never hurts to <em>try</em> Markdown processing, just to see how you like it. If you decide later that you prefer WordPress content filters, you can always switch back; i.e., this feature does not modify the underlying content that you publish; only the final display of that content on-site. <em>Note: enabling Markdown also makes it possible for visitors to leave comments on your site in the Markdown syntax if they want to.</em>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info" style="display:block;">'.__('<strong>Tip:</strong> If you enable Markdown, it is suggested that you disable the WordPress Visual Editor under your WP profile options. This is not required, but if you\'re publishing in Markdown (or with a mix of Markdown and pure HTML) there is very little reason to depend on the WP Visual Editor. To disable the WP Visual Editor for your profile, please see: <code>Dashboard ⥱ Users ⥱ Your Profile</code>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Markdown Line Break Style', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p><select name="theme[save_options][md_enable_line_breaks]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="0"'.selected($this->theme->options['md_enable_line_breaks'], '0', FALSE).'>'.__('No, I prefer to use the default/strict Markdown line-break behavior', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['md_enable_line_breaks'], '1', FALSE).'>'.__('Yes, enable hard line breaks (GitHub style); works with Parsedown Extra only', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Markdown Cache Expiration', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Cached HTML files generated from Markdown syntax will never be allowed to grow older than this expiration date. The value that you specify here should be compatible with PHP\'s <code>strtotime()</code> function. Examples: <code>30 days</code>, <code>2 hours</code>, <code>1 year</code>. See also: <a href="http://php.net/manual/en/function.strtotime.php" target="_blank">strtotime()</a>. Cache files exceeding this expiration date are automatically purged through a WP Cron job that runs once daily.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][md_cache_max_age]" value="'.esc_attr($this->theme->options['md_cache_max_age']).'" autocomplete="off" /></p>'."\n";
			echo '         <p>'.__('Content (and/or excerpts) filtered through Markdown are cached intelligently to prevent repeated Markdown parsing on every page view. Cached content/excerpts will be refreshed automatically whenever you update (or modify) the original content; e.g., when you edit a Post/Page. Or, if your content changes dynamically. For instance, if your content contains PHP tags which produce dynamic content. Or, if a shortcode produces something different each time it\'s processed. The Markdown cache is extremely intelligent all on it\'s own; i.e., there is no need to clear or purge the Markdown cache manually. Set a reasonable expiration time and you\'re good-to-go. You can also run other plugins like ZenCache or W3 Total Cache along with Markdown processing—that\'s perfectly OK :-)', $this->theme->text_domain).'</p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Markdown Syntax URL (Documentation)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('When Markdown is enabled, it is also possible for comments to contain Markdown; allowing your viewers to post comments using Markdown. The URL that you specify here should lead visitors to the documentation for Markdown, where they can learn more about the Markdown syntax—in case it\'s new to them.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][md_syntax_url]" value="'.esc_attr($this->theme->options['md_syntax_url']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Excerpts', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Default Excerpt Characters (when Clipped)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When you publish a Post/Page and you don\'t supply a custom Excerpt; and you don\'t use the <a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank" style="text-decoration:none;"><code>&lt;!--more--&gt;</code></a> tag either, a default Excerpt will be generated automatically by clipping a text version of the full content. This setting controls the number of characters you\'d like to clip in this scenario.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][default_excerpt_clip_chars]" value="'.esc_attr($this->theme->options['default_excerpt_clip_chars']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Excerpt "Read More" Label (to View the Full Content)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('In any archive view; e.g. the blog index, category listings, tag archives, dated archives, etc; only the Excerpt is displayed, and a link to read the full content is available for those who would like to continue reading. This setting controls the label that you\'d like to display next to the Excerpt.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][excerpt_read_more_label]" value="'.esc_attr($this->theme->options['excerpt_read_more_label']).'" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Excerpt "Read More" Label in Search Results (to View the Full Content)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When search results are displayed; only the Excerpt is shown, along with a link to read the full content. This setting controls the label that you\'d like to display next to the Excerpt in search result listings.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][excerpt_read_more_label_s]" value="'.esc_attr($this->theme->options['excerpt_read_more_label_s']).'" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Shortlinks', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/sl-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Enable the Display of a Shortlink at the Top of Each Post?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This will display a Shortlink (with click-to-copy functionality) at the top of each Post (only for Posts, not Pages). This can be handy for visitors who wish to share a Post with others.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][shortlinks_display_enable]" autocomplete="off" class="max-width no-if-enabled">'."\n";
			echo '            <option value="0"'.selected($this->theme->options['shortlinks_display_enable'], '0', FALSE).'>'.__('No, I prefer not to display Shortlinks.', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['shortlinks_display_enable'], '1', FALSE).'>'.__('Yes, display a Shortlink at the top of each Post.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <a href="https://bitly.com/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/bitly-128.png')).'" class="float-right" style="width:128px; clear:right;" /></a>'."\n";
			echo '      <h3>'.__('Enable the use of <a href="https://bitly.com/" target="_blank">Bitly™</a> for all Shortlinks?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('By default, WordPress builds its own Shortlinks; e.g. <code>'.esc_html(home_url('?p=123')).'</code>. However, you might prefer Bitly for this (recommended), since Bitly URLs are even shorter. Plus, you will also have the option (with Bitly) to use a custom short domain of your own. A custom short domain is optional of course, but highly recommended. See <a href="http://support.bitly.com/knowledgebase/articles/76741-how-do-i-set-up-a-custom-short-domain" target="_blank">this article</a> for further details.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][bitly_shortlinks_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="0"'.selected($this->theme->options['bitly_shortlinks_enable'], '0', FALSE).'>'.__('No, I prefer NOT to use Bitly for Shortlinks.', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['bitly_shortlinks_enable'], '1', FALSE).'>'.__('Yes, use Bitly for all Shortlinks generated by WordPress.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Generic Access Token (for Bitly)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Please generate a Generic Access Token <a href="https://bitly.com/a/oauth_apps" target="_blank">here (at Bitly)</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][bitly_access_token]" value="'.esc_attr($this->theme->options['bitly_access_token']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-info-circle"></i> '.__('Sidebar', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <h3>'.__('Floating Peek-a-Boo Sidebar', $this->theme->text_domain).'</h3>'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/sb-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <p>'.__('The Sidebar in this theme is dynamic (it allows for widget configurations). A floating peek-a-boo Sidebar is displayed if, and only if, you\'ve added widgets to it. To customize your Sidebar, please see: <strong><em>Dashboard -› Appearance -› Widgets -› Sidebar</em></strong>. Need help? Please see <a href="http://codex.wordpress.org/WordPress_Widgets" target="_blank">WordPress Widgets</a> for further details.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('In this theme the Sidebar floats in a fixed position on the right side; i.e. it remains in the same location as you scroll the page. In addition, the Sidebar in this theme (if you choose to enable it by adding widgets); plays peek-a-boo with your visitors. In order to open the Sidebar a visitor must toggle it open by clicking the tab on the right side of the screen.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> If you\'re looking for a way to display widgets only for certain types of users; or only when some other conditions apply; please activate this handy plugin: <a href="http://wordpress.org/plugins/widget-logic/" target="_blank">Widget Logic</a> (compatible).', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Markdown:</strong> If you enabled Markdown processing, you can also use Markdown (and even WordPress Shortcodes) in text widgets; along with pure HTML of course (if you like). For PHP tags, get <a href="http://wordpress.org/plugins/ezphp/" target="_blank">ezPHP</a> (compatible).', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Sharebar', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/shb-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Enable the Floating Sharebar?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This improves usability and increases exposure on social networking sites like Facebook, Twitter and Google+. The Sharebar floats on the left side of your site in a fixed position; e.g. it remains in the same location as you scroll the page. The Sharebar makes it easy for visitors to share the current page they are viewing on your site with their friends or associates via social networking sites.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][sharebar_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['sharebar_enable'], '1', FALSE).'>'.__('Yes, enable the Sharebar (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['sharebar_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Sharebar Featured Services', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('A comma-delimited list of service codes to include; in the order you desire. Any combination of: <code>facebook</code>, <code>twitter</code>, <code>google_plus</code>, <code>linkedin</code>, <code>pinterest</code>, <code>amazon</code>, <code>wordpress</code>, <code>tumblr</code>, <code>email</code>, <code>more</code>. It is suggested that you always include the <code>more</code> code, as this will open a new window with a long list of all possible services (in case a user would prefer to share another way).', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][sharebar_services]" value="'.esc_attr($this->theme->options['sharebar_services']).'" autocomplete="off" /></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Optional AddThis™ Publisher (aka: Profile) ID', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('The Sharebar integrates with the AddThis™ service. Although not required, if you <a href="https://www.addthis.com/register" target="_blank">register free @ AddThis.com</a> you will get a Publisher (aka: Profile) ID, along with access to statistics about the number of people sharing your content across the web. Entering your ID here will automatically enable this tracking for you.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][addthis_publisher]" value="'.esc_attr($this->theme->options['addthis_publisher']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('TabOverride', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://wjbryant.github.io/taboverride/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/to-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable the <a href="https://github.com/wjbryant/jquery.taboverride" target="_blank">TabOverride</a> (v4.0) JavaScript Library?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This enables the tab key (for indentation of code samples and other text) in various areas of your theme where user input is accepted in a <code>&lt;textarea&gt;</code> form field. For instance, the comment message field will allow a tab key if you enable this (i.e. an actual TAB inside the field). You can also enable this yourself in any <code>&lt;textarea&gt;</code> form fields that you create in a Post/Page. Just add the following HTML data attribute to enable tabs: <code>&lt;textarea data-toggle=&quot;taboverride&quot;&gt;&lt;/textarea&gt;</code>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][taboverride_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value=""'.selected($this->theme->options['taboverride_enable'], '', FALSE).'>'.__('Yes, lazy-load TabOverride; i.e. load only when needed on a given Post/Page (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['taboverride_enable'], '1', FALSE).'>'.__('Yes, enable the TabOverride on every page of the site (always-on).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['taboverride_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> You can also force this library to load for a given Post/Page by adding a comment line anywhere in the content body: <code>&lt;!--taboverride--&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('TabOverride Tab Size (in Spaces)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('You can set the tab size (in spaces, perhaps <code>4</code>); or you can set this to a value of <code>0</code>; which simply uses a TAB char (recommended).', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][taboverride_size]" value="'.esc_attr($this->theme->options['taboverride_size']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Highlight.js', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="https://highlightjs.org/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/hljs-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable the <a href="https://github.com/isagalaev/highlight.js" target="_blank">Highlight.js</a> (v8.4) JavaScript Library?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This enables automatic syntax highlighting for any code samples that you publish (or that a visitor\'s comments may include). This is applied to all instances of <code>&lt;pre&gt;&ltcode&gt;...&lt;/code&gt;&lt;/pre&gt;</code> tags across the site; i.e. where a <code>&lt;code&gt;</code> tag is nested inside of a <code>&lt;pre&gt;</code> tag.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('While not required, you can be specific about the language of the code. Example: <code>&lt;pre&gt;&lt;code class=&quot;php&quot;&gt;</code>. Or, if you\'re using Markdown syntax: <code>```php ... ```</code>. This will tell the Highlight.js library <a href="http://highlightjs.readthedocs.org/en/latest/css-classes-reference.html" target="_blank">what language</a> that you\'ve used in a particular code sample.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('If you have <code>&lt;pre&gt;&lt;code&gt;</code> tags that you would prefer to exclude from syntax highlighting, you can add the <code>no-highlight</code> class. Example: <code>&lt;pre&gt;&lt;code class=&quot;no-highlight&quot;&gt;</code>. Or, with Markdown syntax you might have: <code>```no-highlight ... ```</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][highlight_js_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value=""'.selected($this->theme->options['highlight_js_enable'], '', FALSE).'>'.__('Yes, lazy-load Highlight.js; i.e. load only when needed on a given Post/Page (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['highlight_js_enable'], '1', FALSE).'>'.__('Yes, enable Highlight.js on every page of the site (always-on).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['highlight_js_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> You can also force this library to load for a given Post/Page by adding a comment line anywhere in the content body: <code>&lt;!--highlight--&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Highlight.js Theme (Coloration)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('If you\'d like to choose a different Highlight.js theme, you can choose from those listed here for version 8.2. See: <a href="http://cdnjs.com/libraries/highlight.js/" target="_blank">Highlight.js @ CDNjs</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '         <table style="width:100%; table-layout:auto; margin-bottom:0;"><tr><td style="width:1px; font-weight:bold; white-space:nowrap;">//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/styles/</td><td><input type="text" name="theme[save_options][highlight_js_theme]" value="'.esc_attr($this->theme->options['highlight_js_theme']).'" autocomplete="off" /></td><td style="width:1px; font-weight:bold; white-space:nowrap;">.min.css</td></tr></table>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Embedly', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://embed.ly/" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/eb-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable the <a href="https://github.com/embedly/embedly-jquery" target="_blank">Embed.ly</a> (v3.1.1) JavaScript Library?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This enables automatic content embeds for popular online services such as YouTube, Vimeo, CodePen, jsFiddle, and countless others. With Embedly enabled, simply place a URL on it\'s own line (all by itself) and watch the magic happen. It\'s never been easier to post YouTube videos, code samples, or pull a summary from a website automatically by simply posting a URL on it\'s own line.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][embedly_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value=""'.selected($this->theme->options['embedly_enable'], '', FALSE).'>'.__('Yes, lazy-load Embedly; i.e. load only when needed on a given Post/Page (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['embedly_enable'], '1', FALSE).'>'.__('Yes, enable Embedly on every page of the site (always-on).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['embedly_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> You can also force this library to load for a given Post/Page by adding a comment line anywhere in the content body: <code>&lt;!--embedly--&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Embedly Key (Required)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Please register at <a href="https://app.embed.ly/signup" target="_blank">Embed.ly</a> to get your free API key.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][embedly_key]" value="'.esc_attr($this->theme->options['embedly_key']).'" autocomplete="off" /></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Embedly Syntax URL (Documentation)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('When Embedly is enabled, it is also possible for comments to contain URLs on their own line; allowing your viewers to post comments w/ the help of Embedly. The URL that you specify here should lead visitors to the documentation for Embedly; where they can learn more about Embedly (in case it\'s new to them).', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><input type="text" name="theme[save_options][embedly_syntax_url]" value="'.esc_attr($this->theme->options['embedly_syntax_url']).'" autocomplete="off" /></p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('FancyBox', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <a href="http://fancyapps.com/fancybox/#examples" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/fbox-screenshot.png')).'" class="bordered float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable the <a href="http://fancyapps.com/fancybox/#examples" target="_blank">fancyBox</a> (v2.1.5) JavaScript Library?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('fancyBox is a tool that offers an elegant way to add zooming functionality for single images, entire image galleries, html content; and even audio/video. If you enable fancyBox, any <a href="http://codex.wordpress.org/Gallery_Shortcode" target="_blank" style="text-decoration:none;"><code>[gallery]</code></a> shortcodes that you post in WordPress will take advantage of fancyBox automatically. fancyBox is also enabled automatically for single attachments.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('Note: if you are using the <a href="http://codex.wordpress.org/Gallery_Shortcode" target="_blank" style="text-decoration:none;"><code>[gallery]</code></a> shortcode and fancyBox is not working, please be sure each of the image thumbnails you selected are linked up w/ their image source (e.g. the full size image). Do NOT link images w/ the WordPress attachment page for each image if you want fancyBox enabled. In order for fancyBox to work effectively, each image in your gallery should be linked to it\'s original source file residing in your WordPress Media Library; e.g. <code>&lt;a href=&quot;.../full-size.jpg&quot;&gt;&lt;img src=&quot;.../thumbnail.jpg&quot; /&gt;&lt;/a&gt;</code>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('You can also enable fancyBox yourself by adding this HTML data attribute to an anchor tag that leads to an image: <code>&lt;a href=&quot;.../image.gif|jpg|png&quot; data-toggle=&quot;fancybox&quot;&gt;</code>. To create your own image gallery that takes advantage of fancyBox (without the <a href="http://codex.wordpress.org/Gallery_Shortcode" target="_blank" style="text-decoration:none;"><code>[gallery]</code></a> shortcode, you can wrap a series of anchor tags that lead to images inside a div wrapper. Example: <code>&lt;div data-toggle=&quot;fancybox-gallery&quot;&gt;&lt;a href=&quot;.../full-size.jpg&quot;&gt;&lt;img src=&quot;.../thumbnail.jpg&quot; /&gt;&lt;/a&gt;, and another...&lt;/div&gt;</code>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][fancybox_enable]" autocomplete="off">'."\n";
			echo '            <option value=""'.selected($this->theme->options['fancybox_enable'], '', FALSE).'>'.__('Yes, lazy-load fancyBox; i.e. load only when needed on a given Post/Page (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['fancybox_enable'], '1', FALSE).'>'.__('Yes, enable fancyBox on every page of the site (always-on).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['fancybox_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> You can also force this library to load for a given Post/Page by adding a comment line anywhere in the content body: <code>&lt;!--fancybox--&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Contact Form', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/email-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Default "From" Address for Contact Form Submissions', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When using the <code>[contact_form /]</code> shortcode, if you don\'t specify <code>[contact_form from="contact-form@'.esc_attr(str_ireplace('www.', '', $_SERVER['HTTP_HOST'])).'" /]</code>, what email address should be used as a default? Contact form submissions should always come "From" the site itself (e.g. you might whitelist something like: <code>contact-form@'.esc_html(str_ireplace('www.', '', $_SERVER['HTTP_HOST'])).'</code>). <em>NOTE: This is a default location only, you can always override this when using the <code>[contact_form /]</code> shortcode.</em> <em>NOTE: This only impacts the "From" email header. The Reply-To header in the email, is always set dynamically to that of the person who actually submitted the contact form on your site.</em>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][contact_form_from]" value="'.esc_attr($this->theme->options['contact_form_from']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Default "To" Address for Contact Form Submissions', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When using the <code>[contact_form /]</code> shortcode, if you don\'t specify <code>[contact_form to="me@'.esc_attr(str_ireplace('www.', '', $_SERVER['HTTP_HOST'])).'" /]</code>, what email address should be used as a default? This is where contact form submissions will be sent — so that you (or someone else in charge of your site) can review them. <em>NOTE: This is a default location only, you can always override this when using the <code>[contact_form /]</code> shortcode.</em>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][contact_form_to]" value="'.esc_attr($this->theme->options['contact_form_to']).'" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Default Subject Line for Contact Form Submissions', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When using the <code>[contact_form /]</code> shortcode, if you don\'t specify <code>[contact_form subject="Contact Form Submission" /]</code>, what subject line should be used as a default? <em>NOTE: This is simply a default subject line, you can always override this when using the <code>[contact_form /]</code> shortcode.</em>', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][contact_form_subject]" value="'.esc_attr($this->theme->options['contact_form_subject']).'" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <a href="http://www.google.com/recaptcha" target="_blank"><img src="'.esc_attr($this->theme->url('/client-s/images/use-recaptcha-208.png')).'" class="float-right" /></a>'."\n";
			echo '      <h3>'.__('Enable the <a href="http://www.google.com/recaptcha/learnmore" target="_blank">reCAPTCHA</a> JavaScript Library?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('Required, if you use the <code>[contact_form /]</code> shortcode. This should be set to lazy-load, or always-on (either are fine).', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][recaptcha_enable]" autocomplete="off">'."\n";
			echo '            <option value=""'.selected($this->theme->options['recaptcha_enable'], '', FALSE).'>'.__('Yes, lazy-load reCAPTCHA; i.e. load only when needed on a given Post/Page (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="1"'.selected($this->theme->options['recaptcha_enable'], '1', FALSE).'>'.__('Yes, enable reCAPTCHA on every page of the site (always-on).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['recaptcha_enable'], '0', FALSE).'>'.__('No, I prefer not to use this feature.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> You can also force this library to load for a given Post/Page by adding a comment line anywhere in the content body: <code>&lt;!--recaptcha--&gt;</code>', $this->theme->text_domain).'</p>'."\n";
			echo '      <h3>'.__('reCAPTCHA Public Key (<a href="http://www.google.com/recaptcha" target="_blank">Register</a>)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This is a security mechanism that prevents spam. You can learn more about reCAPTCHA <a href="http://www.google.com/recaptcha/learnmore" target="_blank">here</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][recaptcha_public_key]" value="'.esc_attr($this->theme->options['recaptcha_public_key']).'" autocomplete="off" /></p>'."\n";
			echo '      <h3>'.__('reCAPTCHA Private Key', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p><input type="text" name="theme[save_options][recaptcha_private_key]" value="'.esc_attr($this->theme->options['recaptcha_private_key']).'" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Comments', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/coms-screenshot.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Comment Avatar Size', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This controls both the width &amp; height of user avatar images that appear in the list of comments for a Post/Page. Max size: <code>512</code>. A size less than (or equal to) <code>64</code>px is suggested here.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][comment_avatar_size]" value="'.esc_attr($this->theme->options['comment_avatar_size']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Display Pings in Comments List?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('<a href="http://codex.wordpress.org/Introduction_to_Blogging#Pingbacks" target="_blank">Pingbacks &amp; Trackbacks</a> are slightly different comment types (they are NOT a written message from an end-user). Pingbacks &amp; Trackbacks represent a website that references your Post/Page on another blog. Would you like Pingbacks/Trackbacks displayed publicly in the list of comments?', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][pings_display_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['pings_display_enable'], '1', FALSE).'>'.__('Yes, enable the display of Pings (recommended).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['pings_display_enable'], '0', FALSE).'>'.__('No, I prefer these not be shown publicly.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Footbar', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/fb-layouts.png')).'" class="bordered float-right" />'."\n";
			echo '      <h3>'.__('Footbar Widget Columns', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('The Footbar is dynamic (it allows widgets). The Footbar is displayed at the bottom of your site; if, and only if, you\'ve added widgets to it. To customize your Footbar, please see: <strong><em>Dashboard -› Appearance -› Widgets -› Footbar</em></strong>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p>'.__('The widgets that you configure (if you want to display the Footbar at all); must conform to one of three layout models. Please choose the one that you prefer here. Note: the mobile version of your site will always display one widget in each row; no matter what you select here.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><select name="theme[save_options][footbar_col_size]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="4"'.selected($this->theme->options['footbar_col_size'], '4', FALSE).'>'.__('3 widgets in each row (each widget is 33.3% wide).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="6"'.selected($this->theme->options['footbar_col_size'], '6', FALSE).'>'.__('2 widgets in each row (each widget is 50% wide).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="12"'.selected($this->theme->options['footbar_col_size'], '12', FALSE).'>'.__('1 widget in each row (each widget is 100% wide).', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <p><em>'.__('Need help? Please see: <a href="http://codex.wordpress.org/WordPress_Widgets" target="_blank">WordPress Widgets</a> for further details.', $this->theme->text_domain).'</em></p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> If you\'re looking for a way to display widgets only for certain types of users; or only when some other conditions apply; please activate <a href="http://wordpress.org/plugins/widget-logic/" target="_blank">Widget Logic</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Tip:</strong> If you want the Footbar to simply contain a single row with just a copyright notice and a few links, please use <code>1</code> row and add a single text widget to your Footbar w/ the HTML you desire. The Footbar will only grow as tall as the content in your widget(s).', $this->theme->text_domain).'</p>'."\n";
			echo '      <p class="info">'.__('<strong>Markdown:</strong> If you enabled Markdown processing, you can also use Markdown (and even WordPress Shortcodes) in text widgets; along with pure HTML of course (if you like).', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Error Pages', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Image Displayed on 404 Error', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This error occurs whenever a visitor attempts to visit a page that does NOT exist on your site. Many sites like to display a bit of humor when this error occurs, just to keep things light :-) However, you can always change this to something you like better. See current image here: <a href="'.esc_attr(home_url('/a-slug-which-does-NOT-exist-on-this-site/')).'" target="_blank">example 404 error</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][404_img_url]" value="'.esc_attr($this->theme->options['404_img_url']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Image Displayed on 204 Error (No Search Results)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This error occurs whenever a visitor searches for something that produces absolutely no results. Many sites like to display a bit of humor when this error occurs, just to keep things light :-) However, you can always change this to something you like better. See current image here: <a href="'.esc_attr(get_search_link('a-keyword-that-is-not-found-on-this-site')).'" target="_blank">example 204 error — no results</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][204_no_results_img_url]" value="'.esc_attr($this->theme->options['204_no_results_img_url']).'" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Image Displayed on 204 Error (Archive Empty)', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('This error occurs only if you link to an empty archive (your theme will not do this on it\'s own). For instance, if you link to a Category or Tag archive that contains absolutely nothing, this error will be displayed. Many sites like to display a bit of humor when this error occurs, just to keep things light :-) However, you can always change this to something you like better. See <a href="'.esc_attr($this->theme->options['204_archive_empty_img_url']).'" target="_blank">current image</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][204_archive_empty_img_url]" value="'.esc_attr($this->theme->options['204_archive_empty_img_url']).'" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('X-Frame-Options', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('X-Frame-Options Header', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('The X-Frame-Options HTTP response header can be used to indicate whether or not a browser should be allowed to render your site in an IFRAME. Sites can use this to avoid clickjacking attacks, by ensuring their content is not embedded into other sites. The most popular values for this option are (empty; i.e. unspecified) or <code>SAMEORIGIN</code>. For further details, please see <a href="https://developer.mozilla.org/en-US/docs/HTTP/X-Frame-Options" target="_blank">MDN (X-Frame-Options)</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '      <p><input type="text" name="theme[save_options][x_frame_options_header]" value="'.esc_attr($this->theme->options['x_frame_options_header']).'" class="max-width" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Content Security Policy', $this->theme->text_domain).''."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/lock-hazard-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Enable a Content Security Policy?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('A <a href="https://developer.mozilla.org/en-US/docs/Security/CSP/Introducing_Content_Security_Policy" target="_blank">Content Security Policy (CSP)</a> is an <strong>optional</strong> added layer of security that helps to detect and mitigate certain types of attacks.', $this->theme->text_domain).'</p>'."\n";
			echo '      '.((!$this->theme->options['csp_enable']) ? '<p class="warning">'.__('<strong>Warning:</strong> A default same-origin policy is adequate for most sites. Please enable this only if you know exactly what you\'re doing; otherwise you might break plugins you run (which may depend on 3rd-party resources not covered by your policy). Most sites will not require a Content Security Policy, as you can simply use the default same-origin policy already implemented by modern web browsers. If you\'d like to learn more about CSP, please take a look at: <a href="https://developer.mozilla.org/en-US/docs/Security/CSP/Introducing_Content_Security_Policy" target="_blank">Content Security Policy | MDN</a>.', $this->theme->text_domain).'</p>'."\n" : '');
			echo '      <p><select name="theme[save_options][csp_enable]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['csp_enable'], '1', FALSE).'>'.__('Yes, enable a Content Security Policy (I know what I\'m doing).', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['csp_enable'], '0', FALSE).'>'.__('No, use a default same-origin policy; e.g. use default browser behavior.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <div class="theme-menu-page-panel-if-enabled">'."\n";
			echo '         <h3>'.__('Content Security Policy in Report-Only Mode?', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('To ease deployment, CSP can be deployed in "report-only" mode. The policy is not enforced, but any violations are reported in your browser\'s developer console. To clarify, if you enable Report-Only mode, a report-only header is used to test your current policy without actually deploying it; e.g. it is NOT yet live.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><select name="theme[save_options][csp_report_only]" autocomplete="off">'."\n";
			echo '               <option value="1"'.selected($this->theme->options['csp_report_only'], '1', FALSE).'>'.__('Yes, put my Content Security Policy into Report-Only mode.', $this->theme->text_domain).'</option>'."\n";
			echo '               <option value="0"'.selected($this->theme->options['csp_report_only'], '0', FALSE).'>'.__('No, I want my Content Security Policy live; e.g. enforce all restrictions.', $this->theme->text_domain).'</option>'."\n";
			echo '            </select></p>'."\n";
			echo '         <hr />'."\n";
			echo '         <h3>'.__('Content Security Policy (Trusted Resources; i.e. <code>%%trusted_resources%%</code>)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('Optional; you can simplify your actual Content Security Policy (below); by providing a list here of all trusted resources. This way you can use the special replacement code <code>%%trusted_resources%%</code> in your policy; just to save time and make things a little easier to modify in the future.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><textarea name="theme[save_options][csp_trusted_resources]" rows="5" spellcheck="false">'.format_to_edit($this->theme->options['csp_trusted_resources']).'</textarea></p>'."\n";
			echo '         <h3>'.__('Content Security Policy (Required, if Enabled)', $this->theme->text_domain).'</h3>'."\n";
			echo '         <p>'.__('This is where you state what your Content Security Policy is. The default CSP (default for your theme) considers all resources that your theme may use at any given time. If you have other plugins installed which may depend on other 3rd-party services, please be sure to modify the default policy as necessary. See also: <a href="https://developer.mozilla.org/en-US/docs/Security/CSP/CSP_policy_directives" target="_blank">CSP Policy Directives</a>.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p><textarea name="theme[save_options][csp]" rows="5" spellcheck="false">'.format_to_edit($this->theme->options['csp']).'</textarea></p>'."\n";
			echo '         <p>'.__('Note: <code>\'unsafe-inline\'</code> and <code>\'unsafe-eval\'</code> are required for compatibility with WordPress itself, and with many plugins powered by WordPress. This theme requires <code>\'unsafe-inline\'</code> for styles; due to the way WordPress implements custom inline styles for backgrounds/fonts. This may change in the future, but for now you will need to include <code>\'unsafe-inline\'</code> for styles; at the very least.', $this->theme->text_domain).'</p>'."\n";
			echo '         <p class="info">'.__('<strong>Tip:</strong> If you\'ve enabled Embedly for automatic content embeds, it\'s important to allow embedded content such as audio, video &amp; iframe tags from sources that you intend to use (or that visitors may want to include in comments on your site). Using Embedly together with a Content Security Policy can be somewhat tricky, particulary if you allow comments that might take advantage of Embedly. The default Content Security Policy that comes with this theme considers the most popular Embedly resources (such as YouTube, Vimeo, Flickr, CodePen, jsFiddle), but you may want to allow others.', $this->theme->text_domain).'</p>'."\n";
			echo '      </div>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-gears"></i> '.__('Import/Export Options', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/up-folder-128.png')).'" class="float-right" />'."\n";
			echo '      <h3>'.__('Import Options from Another Theme Installation?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('Upload your <code>%1$s-options.json</code> file and click "Save All Changes" below. The options provided by your import file will override any that exist currently.', $this->theme->text_domain), __NAMESPACE__).'</p>'."\n";
			echo '      <p><input type="file" name="'.esc_attr(__NAMESPACE__).'[import_options]" class="max-width" autocomplete="off" /></p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('Export Existing Options from this Theme Installation?', $this->theme->text_domain).'</h3>'."\n";
			echo '      <button type="button" class="theme-menu-page-export-options" style="float:right; margin: 0 0 0 25px;"'. // Exports existing theme options from this installation.
			     '         data-action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__, '_wpnonce' => wp_create_nonce(), 'theme' => array('export_options' => '1'))), self_admin_url('/admin.php'))).'">'.
			     '         '.sprintf(__('%1$s-options.json', $this->theme->text_domain), __NAMESPACE__).' <i class="fa fa-arrow-circle-o-down"></i></button>'."\n";
			echo '      <p>'.__('Download your existing options and import them all into another theme installation; saves time on future installs.', $this->theme->text_domain).'</p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-save">'."\n";
			echo '   <input type="hidden" name="theme[save_options][crons_setup]" value="'.esc_attr($this->theme->options['crons_setup']).'" autocomplete="off" />'."\n";
			echo '   <input type="hidden" name="theme[save_options][update_sync_username]" value="'.esc_attr($this->theme->options['update_sync_username']).'" autocomplete="off" />'."\n";
			echo '   <input type="hidden" name="theme[save_options][update_sync_password]" value="'.esc_attr($this->theme->options['update_sync_password']).'" autocomplete="off" />'."\n";
			echo '   <input type="hidden" name="theme[save_options][update_sync_version_check]" value="'.esc_attr($this->theme->options['update_sync_version_check']).'" autocomplete="off" />'."\n";
			echo '   <input type="hidden" name="theme[save_options][last_update_sync_version_check]" value="'.esc_attr($this->theme->options['last_update_sync_version_check']).'" autocomplete="off" />'."\n";
			echo '   <button type="submit">'.__('Save All Changes', $this->theme->text_domain).' <i class="fa fa-save"></i></button>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
			echo '</form>';
		}

		public function update_sync()
		{
			echo '<form id="theme-menu-page" class="theme-menu-page" method="post" enctype="multipart/form-data"'.
			     ' action="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'-update-sync', '_wpnonce' => wp_create_nonce())), self_admin_url('/admin.php'))).'">'."\n";

			echo '<div class="theme-menu-page-heading">'."\n";

			echo '   <button type="submit">'.__('Update Now', $this->theme->text_domain).' <i class="fa fa-magic"></i></button>'."\n";

			echo '   <div class="theme-menu-page-panel-togglers" title="'.esc_attr(__('All Panels', $this->theme->text_domain)).'">'."\n";
			echo '      <button type="button" class="theme-menu-page-panels-open"><i class="fa fa-chevron-down"></i></button>'."\n";
			echo '      <button type="button" class="theme-menu-page-panels-close"><i class="fa fa-chevron-up"></i></button>'."\n";
			echo '   </div>'."\n";

			echo '   <div class="theme-menu-page-upsells">'."\n";
			if(current_user_can('edit_theme_options')) echo '<a href="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__)), self_admin_url('/admin.php'))).'"><i class="fa fa-gears"></i> '.__('Theme Options', $this->theme->text_domain).'</a>'."\n";
			echo '      <a href="'.esc_attr('http://www.websharks-inc.com/r/'.str_replace('_', '-', __NAMESPACE__).'-theme-subscribe/').'" target="_blank"><i class="fa fa-envelope"></i> '.sprintf(__('%1$s Newsletter (Subscribe)', $this->theme->text_domain), $this->theme->name).'</a>'."\n";
			echo '   </div>'."\n";

			echo '   <img src="'.$this->theme->url('/client-s/images/updater.png').'" alt="'.esc_attr(__('Theme Updater', $this->theme->text_domain)).'" />'."\n";

			echo '</div>'."\n";

			if(!empty($_REQUEST[__NAMESPACE__.'__error'])) // Error?
			{
				echo '<div class="theme-menu-page-error error">'."\n";
				echo '   <i class="fa fa-thumbs-down"></i> '.esc_html(stripslashes((string)$_REQUEST[__NAMESPACE__.'__error']))."\n";
				echo '</div>'."\n";
			}
			echo '<div class="theme-menu-page-body">'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <div class="theme-menu-page-panel-heading open">'."\n";
			echo '      <i class="fa fa-sign-in"></i> '.__('Update Credentials', $this->theme->text_domain)."\n";
			echo '   </div>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix open">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/key-128.png')).'" class="float-right" style="width:80px;" />'."\n";
			echo '      <h3>'.__('WebSharks™ Authentication', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.sprintf(__('From this page you can update to the latest version of the %2$s theme for WordPress. The %2$s theme is a premium product available for purchase @ <a href="http://www.websharks-inc.com/product/%1$s/" target="_blank">websharks-inc.com</a>. In order to connect with our update servers, we ask that you supply your account login details for <a href="http://www.websharks-inc.com/product/%1$s/" target="_blank">websharks-inc.com</a>. This will authenticate your copy of the %2$s theme; providing you with access to the latest version. You only need to enter these credentials once. s2Clean will save them in your WordPress database; making future upgrades even easier. <i class="fa fa-smile-o"></i>', $this->theme->text_domain), __NAMESPACE__, $this->theme->name).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <h3>'.__('WebSharks™ Username:', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p><input type="text" name="theme[update_sync][username]" value="'.esc_attr($this->theme->options['update_sync_username']).'" autocomplete="off" /></p>'."\n";
			echo '      <h3>'.__('Product License Key (or your WebSharks™ password):', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p><input type="password" name="theme[update_sync][password]" value="'.esc_attr($this->theme->options['update_sync_password']).'" autocomplete="off" /></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-panel">'."\n";

			echo '   <a class="theme-menu-page-panel-heading">'."\n";
			echo '      <i class="fa fa-bullhorn"></i> '.__('Update Notifier', $this->theme->text_domain)."\n";
			echo '   </a>'."\n";

			echo '   <div class="theme-menu-page-panel-body clearfix">'."\n";
			echo '      <img src="'.esc_attr($this->theme->url('/client-s/images/wrench-128.png')).'" class="float-right" style="width:80px;" />'."\n";
			echo '      <h3>'.__('WebSharks™ Update Notifier', $this->theme->text_domain).'</h3>'."\n";
			echo '      <p>'.__('When a new version of this theme becomes available, WebSharks™ can display a notification in your WordPress Dashboard prompting you to return to this page and perform an upgrade. Would you like this functionality enabled or disabled?', $this->theme->text_domain).'</p>'."\n";
			echo '      <hr />'."\n";
			echo '      <p><select name="theme[update_sync][version_check]" class="max-width" autocomplete="off">'."\n";
			echo '            <option value="1"'.selected($this->theme->options['update_sync_version_check'], '1', FALSE).'>'.__('Yes, display a notification in my WordPress Dashboard when a new version is available.', $this->theme->text_domain).'</option>'."\n";
			echo '            <option value="0"'.selected($this->theme->options['update_sync_version_check'], '0', FALSE).'>'.__('No, do not display any theme update notifications in my WordPress Dashboard.', $this->theme->text_domain).'</option>'."\n";
			echo '         </select></p>'."\n";
			echo '   </div>'."\n";

			echo '</div>'."\n";

			echo '<div class="theme-menu-page-save">'."\n";
			echo '   <button type="submit">'.__('Update Now', $this->theme->text_domain).' <i class="fa fa-magic"></i></button>'."\n";
			echo '</div>'."\n";

			echo '</div>'."\n";
			echo '</form>';
		}

		public function custom_fields_meta_box()
		{
			echo __('NOTE: all of these have default values that work just fine :-)', $this->theme->text_domain)."\n";
			echo '<div style="float:right; margin: 0 0 0 20px;">'.sprintf(__('Need help? See: <a href="%1$s" target="_blank">Custom Fields</a> in WordPress.', $this->theme->text_domain), 'http://codex.wordpress.org/Custom_Fields#Usage').'</div>'."\n";
			echo '<div style="margin:10px 0 10px 0; padding:0; height:1px; line-height:1px; background:#CCCCCC;"></div>'."\n";

			echo '<table width="100%">'."\n"; // Table of supported custom fields.
			echo '<tr><th style="text-align:left;">'.__('Custom Field Name', $this->theme->text_domain).'</th>'.
			     '<th style="text-align:left;">'.__('Custom Field Value', $this->theme->text_domain).'</th></tr>'."\n";

			foreach(array('hr_1'                    => '', // Simply a divider; this is handled below.

			              'seo_title'               => __('Title tag for the Post/Page; e.g. <code>My Title</code>', $this->theme->text_domain),
			              'seo_keywords'            => __('Meta keywords for the Post/Page; e.g. <code>my, key, words</code>', $this->theme->text_domain),
			              'seo_description'         => __('Meta description for the Post/Page; e.g. <code>My description.</code>', $this->theme->text_domain),
			              'seo_robots'              => __('Robot meta tag specs for the Post/Page; e.g. <code>noindex,nofollow</code>', $this->theme->text_domain),

			              'hr_2'                    => '', // Simply a divider; this is handled below.

			              'no_navbar'               => __('Navbar to be excluded from the Post/Page; any true value: <code>1|on|yes|true</code>', $this->theme->text_domain),
			              'no_panel'                => __('Panel (matte) to be excluded from the Post/Page; any true value: <code>1|on|yes|true</code><br /><em>This is the primary content panel w/ a background; behind your content.</em>', $this->theme->text_domain),
			              'no_topper'               => __('Topper (title/meta/date) to be excluded from a Post only; any true value: <code>1|on|yes|true</code><br /><em>A Topper is applicable only on Posts; it\'s never shown on Pages.</em>', $this->theme->text_domain),
			              'no_topper_shortlink'     => __('Topper (shortlink) to be excluded from a Post only; any true value: <code>1|on|yes|true</code><br /><em>Applicable only if you\'ve enabled the display of Shortlinks in your Theme Options.</em>', $this->theme->text_domain),
			              'no_topper_date'          => __('Topper (calendar date) to be excluded from a Post only; any true value: <code>1|on|yes|true</code><br /><em>Applicable only on Posts; a calendar date is never shown on Pages.</em>', $this->theme->text_domain),
			              'no_sidebar'              => __('Sidebar to be excluded from the Post/Page; any true value: <code>1|on|yes|true</code><br /><em>The Sidebar is only displayed if you have widgets configured for it; else it\'s disabled anyway.</em>', $this->theme->text_domain),
			              'no_sharebar'             => __('Sharebar to be excluded from the Post/Page; any true value: <code>1|on|yes|true</code><br /><em>This is applicable only if you have the Sharebar enabled in your Theme Options.</em>', theme()->text_domain),
			              'no_footbar'              => __('Footbar to be excluded from the Post/Page; any true value: <code>1|on|yes|true</code><br /><em>The Footbar is only displayed if you have widgets configured for it; else it\'s disabled anyway.</em>', $this->theme->text_domain),
			              'fluid'                   => __('Puts a Post/Page into Fluid (widescreen) mode; use any true value: <code>1|on|yes|true</code><br /><em>This also forces <code>no_sidebar</code> &amp; <code>no_sharebar</code> to avoid conflicts.</em>', $this->theme->text_domain),
			              'super_clean'             => __('Sets all <code>no_*</code> flags for the Post/Page; any true value: <code>1|on|yes|true</code>', $this->theme->text_domain),

			              'hr_3'                    => '', // Simply a divider; this is handled below.

			              'header_title'            => __('Header title content for the Post/Page; e.g. <code>&lt;span&gt;My Title&lt;/span&gt;</code><br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),
			              'header_description'      => __('Header description content for the Post/Page; e.g. <code>&lt;span&gt;My description.&lt;/span&gt;</code><br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),
			              'header_elements'         => __('Header content elements (more control). If supplied, this overrides <code>header_title</code>/<code>header_description</code>.<br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),

			              'hr_4'                    => '', // Simply a divider; this is handled below.

			              'content_footer_elements' => __('Content Footer elements; e.g. any additional HTML elements you\'d like to appear after, but within, the Post/Page content body.<br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),
			              'footer_elements'         => __('Footer elements; e.g. any additional HTML elements you\'d like to appear after the Post/Page content body is closed.<br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),

			              'hr_5'                    => '', // Simply a divider; this is handled below.

			              'wp_head_elements'        => __('Raw HTML for the <code>&lt;head&gt;</code> section of the Post/Page; e.g. <code>&lt;script&gt;</code> or <code>&lt;style&gt;</code> tags; perhaps a background color/image.<br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),
			              'wp_footer_elements'      => __('Raw HTML for the <code>wp_footer</code> section of the Post/Page; e.g. any additional <code>&lt;script&gt;</code> or <code>&lt;style&gt;</code> tags.<br /><em>This field also supports PHP code execution; i.e. <code>&lt;?php ?&gt;</code> tags; and shortcodes work too!</em>', $this->theme->text_domain),
			        ) as $_name => $_description) echo (strpos($_name, 'hr_') === 0)
				? '<tr><td colspan="2" style="padding:0;">'.
				  '<hr style="margin:10px 0 10px 0; padding:0; border:0; height:1px; line-height:1px; background:#CCCCCC;" /></td></tr>'
				: '<tr><td><code>'.$_name.'</code></td><td style="padding:5px 0 5px 0;">'.$_description.'</td></tr>'."\n";

			echo '</table>'; // Close table.
		}
	}
}