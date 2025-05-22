# Multisite Improvements

This plugin provides various improvements for WordPress multisite installations.

## Available Features

| Title                                                                     | Description                                                                                                                                              | Trac Tickets                                           |
|---------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------------------|
| [Site Active Plugins](./SiteActivePlugins/README.md)                      | Displays which plugins are active on each site in the network. Adds a “Sites deactivate” link to the Network Admin Plugins page with a modal that lists subsites using the plugin. Supports selective bulk deactivation across subsites. | [#53255](https://core.trac.wordpress.org/ticket/53255) |
| [Introduce `get_site_by()` function for multisite](./GetSiteBy/README.md) | Provides a utility function to retrieve a site object from the multisite network using a specific field such as ID, slug, domain, path, or full URL. This makes it easier to locate subsites without relying on raw SQL or manual loops. | [#40180](https://core.trac.wordpress.org/ticket/40180) |

_Made with ❤️ by [Syde](https://syde.com)._