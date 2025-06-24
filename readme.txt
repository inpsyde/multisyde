=== MultiSyde ===

Contributors: syde, realloc
Tags: multisite, network admin, enhancements, usability, admin tools
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that explores potential enhancements for WordPress Multisite.

== Description ==

A WordPress plugin that explores potential enhancements for WordPress Multisite.

Developed as part of Syde’s initiative to improve the Multisite experience by addressing usability, transparency, and network administration challenges.

== Installation ==

To install the plugin on a WordPress Multisite network:

Option 1: Install via Network Admin
* Go to Network Admin → Plugins → Add New (/wp-admin/network/plugin-install.php).
* Search for the plugin (if published) or click Upload Plugin to upload the ZIP file.
* Once uploaded, click Install Now, then Network Activate.

Option 2: Manual Installation
* Download or clone the plugin into your /wp-content/plugins/ directory: /wp-content/plugins/multisyde
* Go to Network Admin → Plugins (/wp-admin/network/plugins.php).
* Locate MultiSyde in the list and click "Network Activate".

This plugin is intended for WordPress Multisites and must be activated network-wide.

== Contributing ==

If you want to contribute to the plugin, please fork the [repository on GitHub](https://github.com/inpsyde/multisyde/ "MultiSyde Repository") and submit a pull request with your changes. We welcome contributions from the community to improve the plugin and its features.

== Changelog ==

= 1.0.1 =
* [Site Active Plugins](https://github.com/inpsyde/multisyde/blob/main/modules/SiteActivePlugins/README.md): Added a filter to allow other plugins to modify the list of active plugins on a site.
