# 🔍 Get Site by Field (`get_site_by()`)

This feature introduces a utility function `get_site_by()` to the multisite environment. It allows developers to retrieve a `WP_Site` object based on a specific field such as:

- `id` (site ID)
- `slug` (relative site path or subdomain slug)
- `url` (full site URL)
- `domain` (only in subdomain installs)
- `path` (only in subdirectory installs)

The function simplifies locating a site without relying on raw SQL queries or manual `get_sites()` loops. It supports optional filtering by network ID and handles normalization (e.g., `www.` domain stripping, URL parsing).

## Example Usage

```php
$site = get_site_by( 'slug', 'store' );
```
Returns the corresponding `WP_Site` object or `null` if not found.

## Background

This function is a patch proposed for WordPress core: [Trac #40180](https://core.trac.wordpress.org/ticket/40180).  
It is included here as a forward-compatible enhancement.