<?php
/*
 * WRC Pricing Tables 2.4.3 - 1-August-2024
 * @realwebcare - https://www.realwebcare.com/
 * Adding menu for Pricing Table in WP admin
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if(!class_exists('WRCPT_Admin_Menu')) {
    class WRCPT_Admin_Menu {

        public function __construct() {
            add_action('admin_menu', array($this, 'register_menu'));
        }

        public function register_menu() {
            add_menu_page(
                'WRC Pricing Table',
                __('Pricing Tables', 'wrc-pricing-tables'),
                'manage_options',
                'wrcpt-menu',
                array($this, 'plugin_menu'),
                plugins_url('../images/icon.png', __FILE__)
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

        public function plugin_menu() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }
            require_once WRCPT_PLUGIN_PATH . 'inc/process-table.php';
        }

        public function template_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }
            require_once WRCPT_PLUGIN_PATH . 'template/process-template.php';
        }

        public function guide_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
            }
            require_once WRCPT_PLUGIN_PATH . 'inc/wrcpt-guide.php';
        }
    }
}

new WRCPT_Admin_Menu();

require_once WRCPT_PLUGIN_PATH . 'inc/modify-package.php';
require_once WRCPT_PLUGIN_PATH . 'inc/process-feature.php';
require_once WRCPT_PLUGIN_PATH . 'inc/display-package.php';
require_once WRCPT_PLUGIN_PATH . 'inc/wrcpt-sidebar.php';
require_once WRCPT_PLUGIN_PATH . 'lib/process_table-option.php';
require_once WRCPT_PLUGIN_PATH . 'template/process-template-option.php';

/* Calling a function to add a new pricing table details. */
if (isset($_POST['wrcpt_edit_process']) && $_POST['wrcpt_edit_process'] == "editprocess") {
    /* Optimizing the database by deleting unnecessary package options. */
    if (isset($_POST['wrcpt_optimize'])) {
        wrcpt_unuseful_package_options();
    }
}
?>