# Contributing to Multisyde

Thanks for your interest in contributing to Multisyde!

This plugin explores improvements for the WordPress Multisite experience, especially around usability, visibility, and admin workflows. Whether you're here to fix a bug, suggest an enhancement, or build something new — you're welcome!

## Table of Contents

- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Code Standards](#code-standards)
- [Creating a Pull Request](#creating-a-pull-request)
- [Feature Structure](#feature-structure)
- [Reporting Issues](#reporting-issues)
- [Code of Conduct](#code-of-conduct)

---

## Getting Started

Before contributing, it's a good idea to familiarize yourself with:

- How WordPress Multisite works.
- The purpose of each feature module in the plugin.

You can explore the plugin’s features in the `modules/` directory or by visiting the [Feature Overview](./README.md#available-features).

---

## Development Setup

We recommend using the [WordPress Environment](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) for local development.

### Quick Start

```bash
git clone git@github.com:syde-xyz/multisyde.git
cd multisyde
composer install
npm install -g @wordpress/env
wp-env start
```

Your environment will be available at [http://localhost:8888](http://localhost:8888)  
(Default credentials: **Username:** `admin`, **Password:** `password`)

**Database credentials:**

- **User:** `root`
- **Password:** `password`

---

## Code Standards

- **PHP:** Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- **JavaScript (if applicable):** Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- Use `phpcs` and `PHPStan` (level 8) to validate your code.

```bash
composer run lint
composer run phpstan
```

## Creating a Pull Request

1. Fork the repository on GitHub.
2. Create a new branch:
    ```bash
    git checkout -b feature/my-contribution
    ```
3. Make your changes and commit them with meaningful messages.
4. Run code quality tools before submitting:
    ```bash
    composer run lint
    composer run phpstan
   ```
5. Push your branch and open a pull request against the main branch.

Please include in your PR:
- A clear description of what your change does
- Links to related issues or Trac tickets (if any)
- Screenshots or screencasts for UI-related features
- Notes on testing or impacts to existing functionality

## Feature Structure

Each feature is self-contained under the modules/ directory and typically includes:
- A Feature class implementing LoadableFeature and optionally PresentableFeature
- Hooks for the Network Admin interface, if applicable
- A dedicated README.md with feature description and related Trac links

You can use GetSiteBy and SiteActivePlugins as reference implementations.

## Reporting Issues

If you run into bugs or want to suggest an improvement:
- Open a GitHub issue
- Clearly describe what’s happening and what you expected
- Include any error messages, reproduction steps, or environment details

## Code of Conduct

We follow the WordPress Community Code of Conduct.
Be respectful and inclusive in all your interactions.

---

Thanks for helping improve Multisyde! ❤️
— The Syde Team