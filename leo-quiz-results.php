<?php

require_once(__DIR__ . '/vendor/autoload.php');

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/gr33k01
 * @since             1.0.0
 * @package           Leo_Quiz_Results
 *
 * @wordpress-plugin
 * Plugin Name:       Leo Quiz Results
 * Plugin URI:        https://github.com/gr33k01/Leo-Quiz-Results
 * Description:       Custom admin view that shows quiz results by department.
 * Version:           1.0.0
 * Author:            Nate Hobi
 * Author URI:        https://github.com/gr33k01
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       leo-quiz-results
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-leo-quiz-results-activator.php
 */
function activate_leo_quiz_results() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leo-quiz-results-activator.php';
	Leo_Quiz_Results_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-leo-quiz-results-deactivator.php
 */
function deactivate_leo_quiz_results() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leo-quiz-results-deactivator.php';
	Leo_Quiz_Results_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_leo_quiz_results' );
register_deactivation_hook( __FILE__, 'deactivate_leo_quiz_results' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-leo-quiz-results.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_leo_quiz_results() {

	$plugin = new Leo_Quiz_Results();
	$plugin->run();

}

run_leo_quiz_results();
