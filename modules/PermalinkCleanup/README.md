# Permalink Cleanup Module

A WordPress multisite feature that automatically removes the `/blog` prefix from the main site's permalink structure.

## Overview

In WordPress multisite installations, the main site typically has `/blog` prepended to all post permalinks to distinguish posts from pages. This module automatically strips that prefix, allowing cleaner URLs on the main site while leaving subsites untouched.


## Installation

This module is part of the MultiSyde package and is loaded automatically.


## How It Works

The module hooks into two WordPress filters:

1. `sanitize_option_permalink_structure` - Cleans the permalink structure when it's saved
2. `option_permalink_structure` - Cleans the permalink structure when it's retrieved

## Disabling the Feature

If you need to disable the permalink cleanup feature, add this code to your theme's `functions.php` or a custom plugin **after** the feature has been initialized:
```php
add_action('init', function() {
    remove_filter(
        'sanitize_option_permalink_structure',
        ['Syde\MultiSyde\Modules\PermalinkCleanup\Feature', 'remove_blog_prefix']
    );
    
    remove_filter(
        'option_permalink_structure',
        ['Syde\MultiSyde\Modules\PermalinkCleanup\Feature', 'remove_blog_prefix']
    );
}, 20); // Priority 20 to ensure it runs after the feature is initialized
```
