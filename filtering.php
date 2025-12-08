<?php
/**
 * Plugin Name: Filtering
 * Description: Plugin that filters and provides best choice
 * Version: 0.1.0
 * Author: Plamen Kostadinov
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: filter
 * Domain Path: /languages
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define plugin constants.
 */
define( 'FILTERING_DIR', __DIR__ );
define( 'FILTERING_URL', plugin_dir_url( __FILE__ ) );

require __DIR__ . '/vendor/autoload.php';

use Filtering\Plugin;

/**
 * Register activation and deactivation hooks.
 */
register_activation_hook( __FILE__,  array( Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( Plugin::class, 'deactivate' ) );

/**
 * Initialize the singleton instance early.
 *
 * This ensures a single shared instance is used everywhere.
 *
 * @var Plugin $filtering_plugin
 */
$filtering_plugin = Plugin::get_instance();

/**
 * Bootstrap the plugin after all plugins are loaded.
 */
add_action( 'plugins_loaded', array( $filtering_plugin, 'init' ) );
