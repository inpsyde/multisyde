# Last User Login

This module adds a **"Last Login"** column to the **Network Admin > Users** screen in WordPress Multisite. It records and displays the last time each user logged into the network, helping administrators better understand user activity.

![Last User Login](https://github.com/inpsyde/multisyde/blob/main/.wordpress-org/screenshot-4.png?raw=true)
## Features

- Adds a **"Last Login"** column to the Network Users list.
- Automatically stores the login timestamp when users authenticate.
- Makes the column sortable by login date.
- Dates are displayed using the site's configured date format.
- Timezone-aware display with ISO 8601 tooltips.
- Shows "Never" for users who have not yet logged in.

## How It Works

- Whenever a user logs in, the timestamp is saved in UTC in user meta (`last_logged_in`).
- A new column (`Last Login`) appears in the Network Admin > Users table.
- The column displays the date formatted according to the site's `links_updated_date_format` setting, with the full timestamp shown in a tooltip.
- The column is also sortable, and users without login data are treated appropriately.

## Technical Details

- **Column Key:** `last-logged-in`
- **Meta Key:** `last_logged_in`
- **Date Format:** Stored as `Y-m-d H:i:s` (UTC), displayed using WordPress date format settings.
- **Permissions:** Only visible to users with `list_users` capability.
- **Scope:** Available only in **Network Admin** for multisite installations.

## Use Cases

- Identify inactive users across the network.
- Improve network management by sorting users by activity.
- Audit logins for security or engagement tracking.

## Author

Originally developed by [Daniel Huesken](https://profiles.wordpress.org/danielhuesken/)
