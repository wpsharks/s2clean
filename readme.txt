=== s2Clean Theme ===

Stable tag: 160122
Requires at least: 4.4
Tested up to: 4.4
Text Domain: s2clean

License: Please see http://websharks-inc.com/product/s2clean/ for details.
License URI: http://websharks-inc.com/product/s2clean/

Contributors: WebSharks, JasWSInc, raamdev
Donate link: http://websharks-inc.com/r/wp-theme-plugin-donation/
Tags: flexible-width, custom-header, custom-menu, featured-images, microformats, post-formats, translation-ready, bootstrap

A powerful (responsive) WordPress® theme; built on Bootstrap.

== Description ==

s2Clean is an extremely flexible theme built for WordPress. It also utilizes the popular [Bootstrap CSS/JavaScript frameworks](http://getbootstrap.com/) designed by Twitter (a fully Responsive mobile-first design architecture). s2Clean transforms all of WordPress into a Bootstrap-compatible CMS, including support for all WordPress components; such as custom Post Types, Post Type Archives, custom Taxonomies, Post Formats, Menus, Widgets, Pages, Posts, the `<!--more-->` tag, custom Excerpts, Custom Fields, search results and more.

== Installation ==

= s2Clean is Very Easy to Install =

1. Upload the `/s2clean` folder to your `/wp-content/themes/` directory.
2. Activate s2Clean through the **Appearance -› Themes** menu in WordPress®.
3. Visit your **Theme Options** panel in the WordPress Dashboard to configure.

== Changelog ==

= v160122 =

* Updating to PSR-4 codebase layout.
* Moving s2Clean Pro into `websharks/s2clean` repo.
* Updating for PHP v7 compat.

= v150402 =

* Many subtle stylesheet enhancements.

= v150318 =

* Many subtle stylesheet enhancements.
* Improving Markdown parser and additional new configurable options.
* Adding support for Parsedown extra as a Markdown option.

* Enhancing shortcode: `[trending_posts /]`.

= v140805 =

* Enhancing shortcode: `[trending_posts /]`.

= v140804 =

* Bringing back the Sharebar with a new/improved implementation.
* Adding a new shortcode: `[trending_posts /]`.

= v140728 =

* Enhancing the default set of Google Web Fonts that are configured to run with s2Clean.
* Enhancing s2Clean's comment templates and functionality. Better styling, better consistency.
* Adding additional CSS rules to enhance the presentation of Shareaholic features when running together with s2Clean.
* Ditching the built-in Sharebar and integration with AddThis in favor of allowing site owners to choose the service they prefer.

= v131224 =

* Adding an automatic updater for the s2Clean theme (a premium product available @ www.websharks-inc.com). Updates for this theme can now be performed automatically through your WP Dashboard. In addition, update notices are now displayed automatically whenever a new version of the s2Clean theme is available for download. Please see: `Dashboard ⥱ s2Clean ⥱ Theme Updater` in the latest release.
* Bug fix. Additional options made available by s2Clean when adding navigation menu items were not showing up until after a navigation menu had been saved at least once. These additional options now show up as soon as you add a new menu item. Fixed in this release.
* Threaded comment pagination was not working properly when s2Clean was also configured to display pings in the list of comments.
* Bug fix. Hashed anchor links were not working properly on sites that do NOT use fancy permalinks.
* Bug fix. Custom feed link should NOT override comment feed link.
* Improved PHP v5.3 Dashboard notice on sites that are running older versions of PHP.
* Adding `comment_form` action hook to resolve issues in pagination and comment posting together with other plugins like Akismet.
* Login links for comments now use `#login-box` for the best user experience.
* Login links that open a modal `#login-box` now auto-focus the username input field.
* Bug fix. Contact form not always working properly across various offsite domains. Fixed in this release.
* Adding support for GitHub API calls against readme files parsed by the `[wp_readme_tabs /]` shortcode.
* Improving WordPress conformity throughout all areas of the s2Clean codebase.

= v131121 =

* New shortcode: `[wp_readme_tabs /]`.
* New shortcode: `[contact_form /]`.
* Bug fix in Markdown processing routines (when/if Markdown was enabled).
* Several minor improvements in layout design and CSS classes for optimization on mobile devices.
* Several minor improvements in HTML role attributes; for accessibility purposes.

= v131031 =

* Initial release.
