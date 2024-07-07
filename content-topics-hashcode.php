<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://hashcodeab.se
 * @since             1.0.0
 * @package           Content_Topics_Hashcode
 *
 * @wordpress-plugin
 * Plugin Name:       Content Topics
 * Plugin URI:        https://hashcodeab.se
 * Description:       This plugin creates dynamic content topics and adds content filtering function by content type
 * Version:           1.0.0
 * Author:            Dhanuka Gunarathna
 * Author URI:        https://hashcodeab.se/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       content-topics-hashcode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONTENT_TOPICS_HASHCODE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-content-topics-hashcode-activator.php
 */
function activate_content_topics_hashcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-topics-hashcode-activator.php';
	Content_Topics_Hashcode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-content-topics-hashcode-deactivator.php
 */
function deactivate_content_topics_hashcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-topics-hashcode-deactivator.php';
	Content_Topics_Hashcode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_content_topics_hashcode' );
register_deactivation_hook( __FILE__, 'deactivate_content_topics_hashcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-content-topics-hashcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_content_topics_hashcode() {

	$plugin = new Content_Topics_Hashcode();
	$plugin->run();

}
run_content_topics_hashcode();
