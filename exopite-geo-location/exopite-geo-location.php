<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://joe.szalai.org
 * @since             1.0.0
 * @package           Exopite_Geo_Location
 *
 * @wordpress-plugin
 * Plugin Name:       Exopite Geo Location
 * Plugin URI:        https://joe.szalai.org/exopite/exopite-geo-location
 * Description:       Display IP Lookup form and enetered IP geo location with [exopite-geo-location] shortcode.
 * Version:           20180415
 * Author:            Joe Szalai
 * Author URI:        https://joe.szalai.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       exopite-geo-location
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
define( 'EXOPITE_GEO_LOCATION_VERSION', '20180330' );
define( 'EXOPITE_GEO_LOCATION_PLUGIN_NAME', 'exopite-geo-location' );
define( 'EXOPITE_GEO_LOCATION_PATH', plugin_dir_path( __FILE__ ) );
define( 'EXOPITE_GEO_LOCATION_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-exopite-geo-location-activator.php
 */
function activate_exopite_geo_location() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exopite-geo-location-activator.php';
	Exopite_Geo_Location_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-exopite-geo-location-deactivator.php
 */
function deactivate_exopite_geo_location() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exopite-geo-location-deactivator.php';
	Exopite_Geo_Location_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_exopite_geo_location' );
register_deactivation_hook( __FILE__, 'deactivate_exopite_geo_location' );

/*
 * Update
 */
if ( is_admin() ) {

    /**
     * A custom update checker for WordPress plugins.
     *
     * Useful if you don't want to host your project
     * in the official WP repository, but would still like it to support automatic updates.
     * Despite the name, it also works with themes.
     *
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {

        require_once join( DIRECTORY_SEPARATOR, array( EXOPITE_GEO_LOCATION_PATH, 'vendor', 'plugin-update-checker', 'plugin-update-checker.php' ) );

    }

    $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://update.szalai.org/?action=get_metadata&slug=' . EXOPITE_GEO_LOCATION_PLUGIN_NAME, //Metadata URL.
        __FILE__, //Full path to the main plugin file.
        EXOPITE_GEO_LOCATION_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
    );

}
// End Update

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-exopite-geo-location.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_exopite_geo_location() {

	$plugin = new Exopite_Geo_Location();
	$plugin->run();

}
run_exopite_geo_location();
