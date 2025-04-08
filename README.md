# Multisite Improvements

Proof of concept for a Canonical Plugin with improvements for WordPress Multisite

## How to start

The easiest way to start is to use the [WordPress Environment](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) package.
```
$ git clone git@github.com:inpsyde/multisite-improvements.git
$ cd multisite-improvements
$ composer install
$ npm -g i @wordpress/env
$ wp-env start
```

The local environment will be available at http://localhost:8888 (Username: _admin_, Password: _password_).

The database credentials are: user _root_, password _password_.