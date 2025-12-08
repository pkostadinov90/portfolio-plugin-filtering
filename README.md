# Filtering

A WordPress plugin that registers the **Casino** custom post type and related taxonomies, with optional sample data seeding tools.
This plugin is designed to work with **ACF (Free)** and stores/loads ACF Local JSON from the plugin (when enabled via `ACF\Overrides`).

---

## Requirements

- WordPress 6.9+
- PHP 8.2+
- **Advanced Custom Fields (Free)**

Install ACF Free from WordPress.org:
https://wordpress.org/plugins/advanced-custom-fields/

---

## Installation

1. Copy this plugin folder into:

   `wp-content/plugins/filtering/`

2. Ensure dependencies are installed (if this plugin uses Composer in your setup):

   ```bash
   composer install
   ```

3. Activate the plugin in:

   **WP Admin → Plugins**

---

## What this plugin provides

- Custom Post Type:
  - `filtering_casino` (non-public; admin-manageable)

- Taxonomies (assigned to `filtering_casino`):
  - `filtering_type`
  - `filtering_game`
  - `filtering_banking`
  - `filtering_payouts`

- (Optional) Sample data tools:
  - Term seeding via an admin notice after activation (manual trigger)
  - Post seeding (if you enable it in your seeding flow)

---

## ACF Local JSON (plugin-based)

If `Filtering\ACF\Overrides` is initialized, ACF Local JSON will:

- **Save to**:
  - `filtering/acf-json/`

- **Load from**:
  - the plugin JSON directory (theme JSON paths are filtered out)

Create this folder if it doesn’t exist:

```
acf-json/
```

---

## Development notes

The plugin uses PSR-4 autoloading:

- Namespace prefix: `Filtering\`
- Directory: `lib/`

Example:

- `lib/Plugin.php` → `Filtering\Plugin`
- `lib/Storage/PostType.php` → `Filtering\Storage\PostType`

After changes to classes:

```bash
composer dump-autoload -o
```

---

## Assets (optional)

If this project includes the `/assets` build pipeline:

- See `assets/readme.md` for SCSS/JS build instructions.

---

## License

GPL-2.0-or-later

---

## Author

Plamen Kostadinov
