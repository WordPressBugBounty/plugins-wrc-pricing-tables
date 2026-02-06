<?php
/**
 * Class WRCPT_Deactivation
 *
 * This class handles the uninstallation process of the WRC Pricing Tables plugin.
 * It is responsible for cleaning up plugin options and data from the database
 * when the plugin is uninstalled. The class ensures that data removal is performed
 * only if explicitly allowed by the user in the plugin settings.
 *
 * Note: This class is used exclusively during plugin uninstallation.
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Deactivation')) {
    class WRCPT_Deactivation
    {
        private static $instance;

        public function __construct()
        {
            add_action('wrcpt_plugin_deactivation_check', array($this, 'wrcpt_plugin_deactivate_options'));

            // register_uninstall_hook(WRCPT_AUF, array($this, 'wrcpt_plugin_deactivate'));
            register_uninstall_hook( WRCPT_AUF, array( __CLASS__, 'wrcpt_plugin_deactivate' ) );
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
         * Removes all stored plugin-related options upon deactivation, including
         * pricing tables, features, options, and associated package data.
         * 
         * @return void
         */
        public function wrcpt_plugin_deactivate_options()
        {
            $package_table = get_option('packageTables');

            if (isset($package_table) && $package_table != '') {
                $pricing_tables = explode(', ', $package_table);

                foreach ($pricing_tables as $list) {
                    $package_lists = get_option($list);

                    if (isset($package_lists)) {
                        if ($package_lists) {
                            $table_packages = explode(', ', $package_lists);
                            foreach ($table_packages as $package) {
                                delete_option($package);
                            }
                        }
                        delete_option($list);
                    }

                    $package_features = get_option($list . '_feature');
                    $package_options = get_option($list . '_option');

                    if (isset($package_features)) {
                        delete_option($list . '_feature');
                    }
                    if (isset($package_options)) {
                        delete_option($list . '_option');
                    }
                }

                delete_option('packageTables');
                delete_option('packageCount');
                delete_option('packageIDs');
                delete_option('IDsCount');
                delete_option('external_updates-wrc-pricing-tables');
            }
        }

        /**
         * Unregistering plugin deactivation hooks
         * @return void
         */
        function wrcpt_plugin_deactivate()
        {
            do_action('wrcpt_plugin_deactivation_check');
        }

    }
}

WRCPT_Deactivation::get_instances();
