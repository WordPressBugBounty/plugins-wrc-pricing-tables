<?php
/**
 * Pricing Table Shortcode Functions
 *
 * This file contains functions to define and handle the [wrc-pricing-table] shortcode
 * for displaying pricing table information on the front-end of the website.
 * The shortcode allows users to customize how the pricing tables are displayed
 * and provides a seamless way to integrate the pricing table's information into posts or pages.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Shortcode')) {
    class WRCPT_Shortcode
    {
        private static $instance;

        public function __construct()
        {
            // Add the pricing table shortcode
            add_shortcode('wrc-pricing-table', array($this, 'wrcpt_pricing_table_shortcode'));
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
         * Renders the pricing table shortcode output based on the provided table ID,
         * retrieving all necessary options, features, and package configurations.
         * 
         * @param mixed $atts       Shortcode attributes.
         * @param mixed $content    Optional content between shortcode tags.
         * @return bool|string
         */
        public function wrcpt_pricing_table_shortcode($atts, $content = null)
        {
            $atts = shortcode_atts(array(
                'id' => 1
            ), $atts, 'wrc-pricing-table');

            // Sanitize and validate $id here
            $id = absint($atts['id']);

            $f_value = $f_tips = '';
            $total_feature = $flag = 0;

            $pricing_table_lists = get_option('packageTables');
            $pricing_id_lists = get_option('packageIDs');
            $pricing_table_lists = explode(', ', $pricing_table_lists);
            $pricing_id_lists = explode(', ', $pricing_id_lists);

            // Sanitize and validate $pricing_table_lists and $pricing_id_lists

            $key = array_search($id, $pricing_id_lists);
            if ($key !== false) {
                $flag = 1;
            }

            $pricing_table = isset($pricing_table_lists[$key]) ? $pricing_table_lists[$key] : '';
            $tableID = $pricing_table ? strtolower($pricing_table) . '-' . $id : '';

            // Sanitize and validate $pricing_table and $tableID

            $package_feature = get_option($pricing_table . '_feature');
            $packageCombine = get_option($pricing_table . '_option');

            // Sanitize and validate $package_feature and $packageCombine

            if ($package_feature) {
                $total_feature = count($package_feature) / 2;
            }

            $package_lists = get_option($pricing_table);
            $packageOptions = explode(', ', $package_lists);
            $package_count = count($packageOptions);

            // Sanitize and validate $package_lists and $packageOptions

            require_once(WRCPT_PLUGIN_PATH . 'lib/process-shortcode.php');

            ob_start();

            echo wp_kses_post(
                WRCPT_Process_Shortcode::get_instances()->wrcpt_process_shortcode(
                    $pricing_table,
                    $tableID,
                    $package_feature,
                    $packageCombine,
                    $total_feature,
                    $package_lists,
                    $packageOptions,
                    $package_count,
                    $flag
                )
            );

            return ob_get_clean();
        }
    }
}

WRCPT_Shortcode::get_instances();