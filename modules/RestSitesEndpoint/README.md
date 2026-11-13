# 🌐 REST API Endpoint for Sites (`wp/v2/sites`)

This feature adds the `wp/v2/sites` REST API endpoint to multisite networks:

- `GET /wp/v2/sites` – list sites (filters, search, pagination, ordering)
- `POST /wp/v2/sites` – create a site
- `GET|PUT|PATCH /wp/v2/sites/<id>` – read or update a site, including site meta (`blogmeta`)
- `DELETE /wp/v2/sites/<id>` – archive/delete a site (`force=true` deletes it permanently)

Access requires the `manage_sites` capability (deleting additionally requires `delete_sites`).

The endpoint is the foundation for upcoming MultiSyde modules built with DataViews and DataForm.

## Background

This is a patch proposed for WordPress core: [Trac #40365](https://core.trac.wordpress.org/ticket/40365), expected to land in WordPress 7.3.
The classes in `patches/` are verbatim copies of the core branch and are only loaded when core does not provide `WP_REST_Sites_Controller` yet. Once core ships it, this module becomes a no-op.

The core PHPUnit tests are copied verbatim to `tests/core/` and run against the WordPress test library shipped with wp-env:

```sh
npm run test-php-core
```

To sync with a `wordpress-develop` checkout:

```sh
cp ../wordpress-develop/src/wp-includes/rest-api/endpoints/class-wp-rest-sites-controller.php \
   ../wordpress-develop/src/wp-includes/rest-api/fields/class-wp-rest-site-meta-fields.php \
   modules/RestSitesEndpoint/patches/
cp ../wordpress-develop/tests/phpunit/tests/rest-api/wpRestSitesController.php \
   ../wordpress-develop/tests/phpunit/tests/rest-api/wpRestSiteMetaFields.php \
   modules/RestSitesEndpoint/tests/core/
```
