# MultiSyde

A WordPress plugin that explores potential improvements for WordPress Multisite.

Developed as part of Syde’s initiative to improve the Multisite experience by addressing usability, transparency, and network administration challenges.

## Vision and Purpose

Multisyde is a proof-of-concept plugin created by Syde to explore and demonstrate improvements to the WordPress Multisite experience.

Multisite is a powerful but often overlooked feature of WordPress. Our goal is to bring more visibility, usability, and flexibility to network administrators and developers working with Multisite networks - through small, focused improvements that solve real-world pain points.

This plugin exists to:
* Identify and prototype practical improvements for WordPress Multisite
* Spark conversations around long-standing issues and missing features
* Contribute ideas and feedback that may eventually inform core development
* Provide value to Network Admins and developers through improved workflows

We believe that even small changes - clearer UI, better network tools, developer helpers - can make a big impact in complex Multisite setups. This plugin is our way of testing those ideas out in the open.

## Features

Each [feature](./modules/README.md) is self-contained and documented in its respective directory under `modules/`, where you can find more details, including usage instructions and implementation notes.

## How to start

The easiest way to start is to use the [WordPress Environment](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) package.
```bash
git clone git@github.com:inpsyde/multisyde.git
cd multisyde
composer install
npm -g i @wordpress/env
wp-env start
```

The local environment will be available at http://localhost:8888 (Username: _admin_, Password: _password_).

The database credentials are: user _root_, password _password_.

## Contributing

If you want to [contribute to the plugin](./CONTRIBUTING.md), please fork the repository on GitHub and submit a pull request with your changes. We welcome contributions from the community to improve the plugin and its features.

## License

This plugin is licensed under the GPLv2 or later license. See the [LICENSE](LICENSE) file for more details.
