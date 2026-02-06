<?php
/**
 * This file is responsible for handling various actions related to the
 * initialization of the WRC Pricing Tables plugin. It performs the following tasks:
 *
 * 1. Adding action links to the plugin on the WordPress plugins page.
 * 3. Setting up the admin menu if the current user is an administrator.
 * 4. Initialization of various plugin functions via 'init-functions.php'.
 * 5. Enqueuing CSS and JS files using 'team-enqueue.php'.
 * 7. Adding a shortcode for the table using 'table-shortcode.php'.
 *
 * @param string $plugin The execution key for the WRC Pricing Tables plugin.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Init')) {
    class WRCPT_Init
    {
        private static $instance;

        public function __construct()
        {
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
         * Includes required files for the plugin's functionality.
         *
         * This method loads the necessary PHP files that provide core functionality for the plugin. 
         * By requiring these files, the plugin ensures that all actions, options, and shortcodes 
         * are properly initialized and available.
         *
         * @return void
         */
        private function required_files()
        {
            /* Pricing table various functions */
            require_once WRCPT_PLUGIN_PATH . 'action/init-functions.php';
            /* Shortcode */
            require_once WRCPT_PLUGIN_PATH . 'action/wrcpt-shortcode.php';
            /* Admin Menu */
            if (is_admin()) { require_once WRCPT_PLUGIN_PATH . 'inc/admin-menu.php'; }
            /* Enqueue CSS & JS */
            require_once WRCPT_PLUGIN_PATH . 'inc/wrcpt-enqueue.php';
            /* Preview a pricing table */
            require_once WRCPT_PLUGIN_PATH . 'inc/display-package.php';
            /* Pricing table admin Sidebar */
            require_once WRCPT_PLUGIN_PATH . 'inc/wrcpt-sidebar.php';
            /* Process the pricing table options */
            require_once WRCPT_PLUGIN_PATH . 'lib/process_table-option.php';
            /* Get the pricing table package's options */
            require_once WRCPT_PLUGIN_PATH . 'lib/process-package.php';
            /* Get the pricing table feature's options */
            require_once WRCPT_PLUGIN_PATH . 'lib/process-feature.php';
            /* Process the pricing table template options */
            require_once WRCPT_PLUGIN_PATH . 'template/process-template-option.php';
        }
    }
}

WRCPT_Init::get_instances();
