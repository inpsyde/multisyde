=== MultiSyde ===

Contributors: syde, realloc
Tags: multisite, network admin, enhancements, usability, admin tools
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that explores practical, focused solutions and enhancements for WordPress Multisite.

== Description ==

MultiSyde enhances the WordPress Multisite experience by addressing common usability challenges and streamlining network administration tasks. Developed by [Syde](https://profiles.wordpress.org/syde/) as both a practical tool and proof-of-concept for potential WordPress core improvements, with a focus on Multisite. 

The plugin targets WordPress Multisite network administrators, developers, and organizations managing multiple WordPress sites. It focuses on targeted, high-impact enhancements rather than attempting to solve all Multisite challenges at once.

MultiSyde follows a modular architecture where each feature addresses specific pain points identified in [WordPress Trac tickets](https://core.trac.wordpress.org/tickets/active/) and community feedback. The plugin is actively developed and welcomes community contributions via the [GitHub repository](https://github.com/inpsyde/multisyde/ "MultiSyde GitHub Repository"). See the FAQ for more details on how to contribute.

== Features ==

**Enhanced Site Retrieval** - New `get_site_by()` utility function for easier site lookups by ID, slug, domain, path, or URL (addresses Trac [#40180](https://core.trac.wordpress.org/ticket/40180/ "WordPress Trac Ticket #40180"))

**Last User Login Tracking** - Adds "Last Login" column to Network Admin Users screen with automatic timestamp recording (addresses MultiSyde GitHub issue [#11](https://github.com/inpsyde/multisyde/issues/11/ "MultiSyde GitHub Issue #11"))

**Site Active Plugins** - See which plugins are active on each site, with bulk deactivation across subsites (addresses Trac [#53255](https://core.trac.wordpress.org/ticket/53255/ "WordPress Trac Ticket #53255"))

**Site Active Theme** - Displays the active theme for each site in the Network Admin dashboard, making it easier to manage themes across the network (addresses Trac [#56458](https://core.trac.wordpress.org/ticket/56458/ "WordPress Trac Ticket #56458"))

The feature set continues to expand based on community contributions and real-world needs.

== Installation ==

**Important:** This plugin requires a WordPress Multisite installation and must be network activated.

= Automatic Installation =
1. Go to Network Admin → Plugins → Add New
2. Search for "MultiSyde"
3. Click "Install Now" then "Network Activate"

= Manual Installation =
1. Download the plugin ZIP file
2. Upload to `/wp-content/plugins/` and extract
3. Go to Network Admin → Plugins
4. Network Activate "MultiSyde"

= Development Installation =
1. Clone the repository: `git clone https://github.com/inpsyde/multisyde/ /wp-content/plugins/multisyde`
2. Install dependencies: `composer install`
3. Network activate the plugin

**Requirements:** WordPress 6.8+, PHP 7.4+, Multisite installation

== Frequently Asked Questions ==

= What is the purpose of this plugin? =

MultiSyde explores and demonstrates practical improvements to the WordPress Multisite experience. It provides focused solutions that enhance usability, transparency, and network management efficiency for administrators and developers.

= What is WordPress Multisite? =

WordPress Multisite is a core feature that allows you to run multiple WordPress sites from a single WordPress installation. It's ideal for organizations managing multiple websites, educational institutions, or businesses with separate sites for different departments or locations. MultiSyde specifically enhances the network administration experience for Multisite installations. The [Before You Create A Network](https://developer.wordpress.org/advanced-administration/multisite/prepare-network/ "Before You Create A Network") document outlines some requirements to consider before you begin [creating a multisite network](https://developer.wordpress.org/advanced-administration/multisite/create-network/ "Create A Network").

= How does it improve my WordPress Multisite experience? =

The plugin provides targeted enhancements to solve common challenges, including:
- Better visibility into which plugins are active across your network sites
- Easier site lookup and management with the new `get_site_by()` function
- User login tracking to monitor network activity
- Streamlined bulk operations for plugin management

Each feature is designed to solve specific challenges that Multisite administrators commonly encounter.

= Is this plugin ready for production use? =

MultiSyde is functional but also serves as a proof-of-concept and exploration tool. It's designed to demonstrate potential improvements and gather community feedback. Use in production environments should be carefully evaluated based on your specific needs and risk tolerance.

= How can I contribute to the project? =

We welcome contributions! You can:
- Report bugs and issues on [GitHub Issues](https://github.com/inpsyde/multisyde/issues/ "MultiSyde GitHub Issue Tracker")
- Suggest new features or improvements
- Submit pull requests with code improvements
- Improve documentation
- Test the plugin in different environments

Visit our [GitHub repository](https://github.com/inpsyde/multisyde/ "MultiSyde GitHub Repository") for detailed contribution guidelines and development setup instructions in [CONTRIBUTING.md](https://github.com/inpsyde/multisyde/blob/main/CONTRIBUTING.md "MultiSyde GitHub CONTRIBUTING.md").

= How do I report bugs or request features? =

Please visit our [GitHub repository](https://github.com/inpsyde/multisyde/ "MultiSyde GitHub Repository") to:
- Report bugs using our [issue tracker](https://github.com/inpsyde/multisyde/issues/ "MultiSyde GitHub Issue Tracker")
- Request new features
- View existing issues and discussions
- Access detailed documentation

= Where can I find documentation for each feature? =

Comprehensive documentation for each module is available in the plugin and the [GitHub repository](https://github.com/inpsyde/multisyde/ "MultiSyde GitHub Repository"). Each feature includes detailed documentation explaining its purpose, functionality, and customization options.

= Can I customize or extend the plugin? =

Yes! MultiSyde uses a modular architecture that makes customization straightforward. Developers can:
- Modify existing modules
- Create new enhancement modules
- Integrate with existing Multisite workflows
- Extend functionality through hooks and filters

See our [contribution guidelines](https://github.com/inpsyde/multisyde/blob/main/CONTRIBUTING.md "MultiSyde GitHub CONTRIBUTING.md") and [feature description](https://github.com/inpsyde/multisyde/blob/main/modules/README.md "MultiSyde GitHub Feature README.md") for detailed guidance on customization and extension.

= What's the development philosophy behind MultiSyde? =

MultiSyde follows these key principles:
- Focused enhancements that solve real-world pain points
- Self-contained modules that can be independently evaluated
- Practical improvements that demonstrate potential for WordPress core integration
- Community-driven development with open contribution guidelines

= How does MultiSyde relate to WordPress core development? =

Many features in MultiSyde address specific WordPress Trac tickets, serving as working examples of potential core improvements. The plugin acts as a testing ground for enhancements that could eventually be proposed for WordPress core inclusion.

== Screenshots ==

1. MultiSyde overview page in Network Admin dashboard
2. Enhanced user management interface with Last Login column
3. Site Active Plugins management with bulk deactivation modal
4. Advanced site retrieval using get_site_by() function
5. Site Active Theme feature in site management dashboard

== Upgrade Notice ==

Update for the latest enhancements and improvements. MultiSyde evolves continuously based on community feedback.

== Changelog ==

= 1.1.1 =
* MultiSyde is now network-wide activatable now.

= 1.1.0 =
* Enhancement: Added [Site Active Theme](https://github.com/inpsyde/multisyde/blob/main/modules/SiteActiveTheme/README.md) in site management dashboard.

= 1.0.1 =
* Enhancement: [Site Active Plugins](https://github.com/inpsyde/multisyde/blob/main/modules/SiteActivePlugins/README.md) - Added a filter to allow other plugins to modify the list of active plugins on a site.

= 1.0.0 =
* Initial release.
* Added Site Active Plugins feature with bulk deactivation.
* Added Enhanced Site Retrieval with `get_site_by()` function.
* Added Last User Login tracking in Network Admin.
* Established a modular architecture for extensibility.
