# EasySuite Changelog

Shortcode-based plugin to display the changelog section from any WordPress.org plugin’s readme.txt file.

## Features

- Fetches `readme.txt` directly from the WordPress.org SVN repository  
- Parses and displays the `== Changelog ==` section as HTML  
- Supports version selection (`trunk` or any tagged version)  
- Caches remote fetches using WordPress transients  
- Customizable heading level  

## Usage

Use the `[changelog]` shortcode in any post, page, or widget.

### Shortcode Attributes

| Attribute | Default       | Description                                     |
|-----------|---------------|-------------------------------------------------|
| `slug`    | `easycommerce`| Plugin slug from WordPress.org                 |
| `version` | `trunk`       | Version to fetch (`trunk` or a specific tag)   |
| `cache`   | `86400`       | Cache duration in seconds (default: 1 day)     |
| `heading` | `h4`          | HTML heading tag for version titles            |

### Example

```
[changelog slug="easycommerce" version="0.9.6" heading="h3"]
```

## Developer Notes

- Uses `wp_remote_get()` to fetch the `readme.txt`
- Parses only the `== Changelog ==` section
- Version blocks should follow `= version =` pattern (e.g., `= 0.9.7-beta – 2025.03.19 =`)

## Installation

1. Upload the plugin to your WordPress site
2. Activate it
3. Use the `[changelog]` shortcode wherever needed

## License

GPLv2 or later
