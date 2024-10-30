<?php

/**
 * @link              https://anotherread.com
 * @package           Another_Read
 *
 * @wordpress-plugin
 * Plugin Name:       Another Read
 * Plugin URI:        https://anotherread.com
 * Description:       Display data from the another read site
 * Version:           1.0.0
 * Author:            Line Industries
 * Author URI:        https://line.industries
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       anotherread
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Current plugin version.
 */
define( 'ANOTHER_READ_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function another_read_activate()
{
	require_once plugin_dir_path(__FILE__) . 'core/another-read-activator.php';
	Another_Read_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function another_read_deactivate()
{
	require_once plugin_dir_path(__FILE__) . 'core/another-read-deactivator.php';
	Another_Read_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'another_read_activate');
register_deactivation_hook(__FILE__, 'another_read_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'core/another-read-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function another_read_run()
{
	$plugin = new Another_Read();
	$plugin->run();
}
another_read_run();

if(! wp_next_scheduled('getActivityPosts')){
    wp_schedule_event(time(), 'daily', 'getActivityPosts');
}

?>