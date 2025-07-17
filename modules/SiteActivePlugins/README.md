# 🔧 Site Active Plugins (Plugin Usage Overview)

This feature enhances the **Network Admin Plugins** screen by showing where each plugin is actively used across the network's sites.

It adds a new **"Sites deactivate"** link next to plugins that are active on individual subsites (not network-activated). Clicking this link opens a **ThickBox modal** that:

- Lists all subsites where the plugin is currently active
- Provides checkboxes to **select multiple sites**
- Allows the plugin to be **bulk-deactivated** from the selected subsites directly from the modal

## ✨ Highlights

- Visual cue (background color) for plugins active on subsites
- Helps identify plugins that are no longer needed on specific sites
- Useful for network maintenance, cleanup, or plugin audits
- Admin notice confirms how many sites the plugin was deactivated from

## Example Workflow

1. Go to **Network Admin > Plugins**
2. Locate a plugin active on subsites
3. Click the **"Sites deactivate"** link
4. Select sites from the modal and submit
5. Receive a confirmation notice in the dashboard

## 🔌 Customization

### Filter: `site_active_plugins_max_sites`

You can customize the maximum number of sites to scan for site-level plugin usage using the `site_active_plugins_max_sites` filter. The default value is 100. If the number of sites in the network exceeds this limit, the feature will skip data collection to avoid performance issues.

#### Example:

```php
add_filter( 'site_active_plugins_max_sites', function ( int $max ): int {
    return 200; // Allow scanning up to 200 sites
} );
```

This filter is useful if your network is midsize and you still want to benefit from the feature, or if you want to lower the limit for performance reasons.

## Related WordPress Core Ticket

This feature is inspired by [Trac ticket #53255](https://core.trac.wordpress.org/ticket/53255).

## Screenshots

![Site Active Plugins 1](https://github.com/inpsyde/multisyde/blob/main/.wordpress-org/screenshot-2.png?raw=true)
![Site Active Plugins 2](https://github.com/inpsyde/multisyde/blob/main/.wordpress-org/screenshot-3.png?raw=true)
