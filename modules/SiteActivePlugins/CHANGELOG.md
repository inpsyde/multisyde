# Changelog

All notable changes to this project will be documented in this file.

## Version  1.0.1

Improved performance safeguards in populate_active_plugins(): the method now retrieves all site IDs and skips execution entirely if the number of sites exceeds the configured limit (site_active_plugins_max_sites, default: 100). This prevents partial or misleading results in large networks and avoids unnecessary overhead.
