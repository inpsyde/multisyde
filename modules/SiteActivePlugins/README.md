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

## Related WordPress Core Ticket

This feature is inspired by [Trac ticket #53255](https://core.trac.wordpress.org/ticket/53255).