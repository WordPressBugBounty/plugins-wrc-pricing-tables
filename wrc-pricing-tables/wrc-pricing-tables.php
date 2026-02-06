<?php
/*
 * Plugin Name:       WRC Pricing Tables – Responsive CSS3 Pricing Tables
 * Plugin URI:        http://wordpress.org/plugins/wrc-pricing-tables/
 * Description:       Responsive pricing table plugin developed to display pricing table in a lot more professional way on different posts or pages by SHORTCODE.
 * Version:           2.6
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Realwebcare
 * Author URI:        https://www.realwebcare.com/
 * Text Domain:       wrc-pricing-tables
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * WRC Pricing Tables Plugin
 *
 * Main plugin file that initializes and manages the "WRC Pricing Tables" plugin.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 */

if (!class_exists('WRCPT_Index')) {
    class WRCPT_Index
    {
        private static $instance;

        private function __construct()
        {
            // Define plugin-specific constants.
            $this->define_constants();

            // Load necessary required files.
            $this->required_files();
        }

        /**
         * Public static method to retrieve the singleton instance.
         */
        public static function get_instances()
        {
            if (self::$instance) {
                return self::$instance;
            }

            self::$instance = new self();

            return self::$instance;
        }

        /**
         * Defines essential plugin constants.
         *
         * This method sets up constants that are used throughout the plugin for easy access 
         * to important paths and URLs, ensuring a consistent and maintainable structure.
         *
         * Constants defined:
         * - `WRCPT_PLUGIN_PATH`: Absolute path to the plugin directory.
         * - `WRCPT_PLUGIN_URL`: URL to the plugin directory.
         * - `WRCPT_AUF`: Absolute path to the main plugin file.
         *
         * @return void
         */
        private function define_constants()
        {
            define('WRCPT_PLUGIN_PATH', plugin_dir_path(__FILE__));
            define('WRCPT_PLUGIN_URL', plugin_dir_url(__FILE__));
            define('WRCPT_AUF', __FILE__);
        }

        /**
         * Includes initialized files for the plugin.
         *
         * @return void
         */
        private function required_files()
        {
            // Initialize the plugin
            require_once WRCPT_PLUGIN_PATH . 'inc/init-table.php';
            /* Activation */
            require_once WRCPT_PLUGIN_PATH . 'action/activate-plugin.php';
            /* On Delete */
            require_once WRCPT_PLUGIN_PATH . 'action/uninstall-plugin.php';
        }
    }
}

WRCPT_Index::get_instances();
