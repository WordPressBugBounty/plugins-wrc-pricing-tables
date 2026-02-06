<?php
/**
 * Class WRCPT_Admin_Menu
 * 
 * Adding a top-level menu page and a submenu page for pricing table plugin.
 *
 * @uses  add_menu_page()	 - Adding a top-level menu page for pricing table plugin.
 * These functions takes a capability which will be used to determine whether
 * or not a page is included in the menu.
 *
 * @uses  add_submenu_page() - Adding a submenu page for pricing table plugin.
 * The functions which is hooked in to handle the output of the page must check
 * that the user has the required capability as well.
 *
 * Including other pages to make the plugin workable.
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if(!class_exists('WRCPT_Admin_Menu')) {
    class WRCPT_Admin_Menu {
        private static $instance;

        public function __construct() {
            add_action('admin_menu', array($this, 'register_menu'));
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
         * Registers the main admin menu and submenu pages for the plugin,
         * including Pricing Tables, Templates, and Help sections.
         * 
         * @return void
         */
        public function register_menu() {
            add_menu_page(
                'WRC Pricing Table',
                __('Pricing Tables', 'wrc-pricing-tables'),
                'manage_options',
                'wrcpt-menu',
                array($this, 'plugin_menu'),
                WRCPT_PLUGIN_URL . 'assets/images/icon.png',
                66
            );

            add_submenu_page(
                'wrcpt-menu',
                __('WRCPT Lists', 'wrc-pricing-tables'),
                __('All Pricing Tables', 'wrc-pricing-tables'),
                'manage_options',
                'wrcpt-menu',
                array($this, 'plugin_menu')
            );

            add_submenu_page(
                'wrcpt-menu',
                'WRCPT Template',
                __('Templates', 'wrc-pricing-tables'),
                'manage_options',
                'wrcpt-template',
                array($this, 'template_page')
            );

            add_submenu_page(
                'wrcpt-menu',
                'WRCPT Help',
                __('Help', 'wrc-pricing-tables'),
                'manage_options',
                'wrcpt-help',
                array($this, 'guide_page')
            );
        }

        /**
         * Loads the main Pricing Tables admin page after verifying user capabilities.
         * 
         * @return void
         */
        public function plugin_menu() {
            if (!current_user_can('manage_options')) {
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }   
            require_once WRCPT_PLUGIN_PATH . 'lib/process-table.php';
        }

        /**
         * Loads the Templates creation page after verifying user capabilities.
         * 
         * @return void
         */
        public function template_page() {
            if (!current_user_can('manage_options')) {
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }
            require_once WRCPT_PLUGIN_PATH . 'template/process-template.php';
        }

        /**
         * Loads the Help/Guide page after verifying user capabilities.
         * 
         * @return void
         */
        public function guide_page() {
            if (!current_user_can('manage_options')) {
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }
            require_once WRCPT_PLUGIN_PATH . 'inc/wrcpt-guide.php';
        }
    }
}

WRCPT_Admin_Menu::get_instances();
