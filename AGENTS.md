# AGENTS.md

This file provides structured guidance for AI coding assistants and agents
working with the **MultiSyde** WordPress plugin.

## Project Overview
MultiSyde is a WordPress Multisite plugin providing improvements to the
Network Admin experience. It introduces modules like:

- **SiteActivePlugins**: Shows per-site active plugins in Network Admin.
- **SiteActiveTheme**: Displays the active theme for each site in Network Admin.
- **SiteEnabledThemes**: (planned) Highlights themes enabled per site in Network Admin.

## Code Layout
- `src/` – Main plugin bootstrap and PHP entry files
- `modules/` – Features (PHP, JS, CSS, assets, build, tests)
    - `modules/<FeatureName>/Feature.php` – Main feature entry class implementing `LoadableFeature`
    - `modules/<FeatureName>/assets/` – Feature-specific JavaScript/TypeScript and CSS
    - `modules/<FeatureName>/build/` – Compiled build artifacts
    - `modules/<FeatureName>/tests/` – Unit and integration tests for the feature
- `tests/` – Unit and integration tests for the plugin bootstrap and shared code
- `CHANGELOG.md` – Project changelog
- `README.md` – General project description
- `CONTRIBUTING.md` – Contribution guidelines

## Conventions
- PHP follows **WordPress Coding Standards** (`phpcs` config included).
- Namespaces: `Syde\MultiSyde\Modules\<Feature>`
- Each feature implements `LoadableFeature` interface with an `init()` method.
- Prefer strict typing (`declare(strict_types=1)`).
- Keep PHP, JS, CSS, assets, build artifacts, and tests for a feature together inside its `modules/<FeatureName>/`.

## Adding a New Feature
1. Create a new directory under `modules/<FeatureName>/`.
2. Add a `Feature.php` class implementing `LoadableFeature`.
3. Register hooks in `init()`.
4. If UI is needed:
    - Add JS/TS and CSS in `modules/<FeatureName>/assets/`
    - Built artifacts should be placed in `modules/<FeatureName>/build/`
    - Register/enqueue scripts in the feature class
5. Write feature-specific tests in `modules/<FeatureName>/tests/`.
6. If necessary, add bootstrap-level tests in `/tests`.

## Tests and CI
- PHPStan runs at **Level 9**.
- PHPCS enforces WordPress coding standards.
- PHPUnit tests required for new features.
- Tests are located in:
    - `/tests` for plugin bootstrap and shared code
    - `modules/<FeatureName>/tests/` for feature-level tests
- Commands for testing and builds are defined in `composer.json` and `package.json`.
- GitHub Actions run CI on each PR.

## Things Agents Should Not Do
- Do not edit files in `/vendor/` (dependencies).
- Do not change build artifacts in `/modules/<FeatureName>/build/`.
- Do not modify `CHANGELOG.md` manually outside of release workflow.
- Do not alter the plugin header in `multisyde.php`.

## References
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Multisite Documentation](https://wordpress.org/documentation/article/create-a-network/)

---

## Machine-Readable Summary

```yaml
project: MultiSyde
type: wordpress-plugin
layout:
  src: bootstrap and entry files
  modules/<FeatureName>/:
    - Feature.php: feature entry class
    - assets/: feature JS, TS, CSS
    - build/: compiled artifacts
    - tests/: feature tests
  tests/: plugin bootstrap and shared tests
files:
  - README.md
  - CHANGELOG.md
  - CONTRIBUTING.md
ci:
  phpstan: level 9
  phpcs: wordpress-coding-standards
  phpunit: required
  commands: composer.json, package.json
rules:
  - no edits in vendor/
  - no edits in modules/<FeatureName>/build/
  - changelog only via release workflow
  - do not change plugin header in multisyde.php
```
