<?php
/**
 * Class WRCPT_Enqueue
 *
 * Pricing Table Admin CSS & JS Enqueue
 * Pricing Table Front End CSS & JS Enqueue
 *
 * @param string $plugin The execution key for the WRC Pricing Tables plugin.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Enqueue')) {
    class WRCPT_Enqueue
    {
        private static $instance;
        private $wrcpt_options;

        public function __construct()
        {
            // Hook to enqueue styles for the admin.
            add_action('admin_enqueue_scripts', array($this, 'wrcpt_enqueue_scripts_admin'));

            // Hook to enqueue styles for the plugin.
            add_action('wp_enqueue_scripts', array($this, 'wrcpt_pricing_table_enqueue'));
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
         * Enqueue admin js and css files
         * 
         * @return void
         */
        public function wrcpt_enqueue_scripts_admin()
        {
            wp_enqueue_script('wrcptjs', WRCPT_PLUGIN_URL . 'assets/js/wrcpt-admin.js', array('jquery'), '2.6', ['in_footer' => true]);
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('wp-color-picker');

            $nonce = wp_create_nonce('wrcpt_ajax_action_nonce');

            wp_localize_script('wrcptjs', 'wrcptajax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => $nonce,
                'creating_message' => __('Table is being created. Please wait...', 'wrc-pricing-tables'),
                'success_message' => __('Successfully Created! Redirecting...', 'wrc-pricing-tables'),
                'switch_template' => __('Template is being switched. Please wait...', 'wrc-pricing-tables'),
                'success_switch' => __('Template Switched Successfully! Reloading...', 'wrc-pricing-tables'),
                'columns_message' => __('Columns are being loaded. Please wait...', 'wrc-pricing-tables'),
                'columns_success' => __('Columns loaded successfully! Redirecting...', 'wrc-pricing-tables'),
                'features_message' => __('Features are being loaded. Please wait...', 'wrc-pricing-tables'),
                'features_success' => __('Features loaded successfully! Redirecting...', 'wrc-pricing-tables'),
                'preview_message' => __('Preview are being loaded. Please wait...', 'wrc-pricing-tables'),
                'preview_success' => __('Preview loaded successfully! Redirecting...', 'wrc-pricing-tables'),
                'updating_table' => __('Update is being processed. Please wait...', 'wrc-pricing-tables'),
                'update_success' => __('Update completed successfully!', 'wrc-pricing-tables'),
                'deleting_message' => __('Deleting the table. Please wait...', 'wrc-pricing-tables'),
                'deleting_success' => __('Table deleted successfully! Reloading...', 'wrc-pricing-tables'),
                'regen_message' => __('Regenerating all the shortcodes. Please wait...', 'wrc-pricing-tables'),
                'regen_success' => __('Shortcodes regenerated successfully! Reloading...', 'wrc-pricing-tables'),
                'opt_message' => __('Optimizing your pricing tables. Please wait...', 'wrc-pricing-tables'),
                'opt_success' => __('All pricing tables optimized successfully! Reloading...', 'wrc-pricing-tables'),
                'error_message' => __('An error occurred. Please try again.', 'wrc-pricing-tables'),
                'loading_image' => WRCPT_PLUGIN_URL . 'assets/images/ajax-loader.gif',
                'main_menu_url' => admin_url('admin.php?page=wrcpt-menu'), // Add the main menu URL,
            ));

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('wrcptfront', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-front.css', [], '2.6');
            wp_enqueue_style('wrcptadmin', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-admin.css', [], '2.6');
            wp_enqueue_style('jquery-ui-style', WRCPT_PLUGIN_URL . 'assets/css/jquery-accordion.css', [], '1.10.4');
        }

        /**
         * Enqueue front js and css files
         * 
         * @return void
         */
        public function wrcpt_pricing_table_enqueue()
        {
            wp_enqueue_style('wrcptfront', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-front.css', array(), '2.6');
            wp_enqueue_style('wrcptFonts', '//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Roboto:wght@400;700&display=swap', array(), '2.6');
        }
    }
}

WRCPT_Enqueue::get_instances();