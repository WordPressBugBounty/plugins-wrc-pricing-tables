<?php
/**
 * IMPORTANT: Plugin Activation Defaults
 *
 * This file is crucial for setting up default options in the database
 * when the plugin is activated. It ensures that input and textarea
 * fields won't display any error or warning messages. Modifying
 * this file without proper understanding may lead to unexpected
 * behavior of the plugin. Make sure to proceed with caution.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Activation')) {
    class WRCPT_Activation
    {
        private static $instance;

        public function __construct()
        {
            add_action('wrcpt_plugin_activation_options', array($this, 'wrcpt_plugin_activation_values'));

            register_activation_hook(WRCPT_AUF, array($this, 'wrcpt_plugin_activate'));
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
         * Initializes and updates plugin activation values, including pricing table
         * features, options, and column details for all stored package tables.
         * 
         * @return void
         */
        public function wrcpt_plugin_activation_values()
        {
            $package_table = get_option('packageTables', 'default_value');
            $package_feature = $package_combine = $package_columns = $packageOptions = array();

            if ($package_table !== 'default_value') {
                // The option exists
                $pricing_tables = explode(', ', $package_table);
                $fn = 0;
                foreach ($pricing_tables as $pricing_table) {
                    $pricing_table = sanitize_text_field($pricing_table);
                    if ($pricing_table) {
                        $package_feature = get_option($pricing_table . '_feature', 'feature_not_exists');
                        $package_combine = get_option($pricing_table . '_option');
                        $package_columns = get_option($pricing_table, null);
                        if ($package_columns !== null) {
                            // The option exists
                            $packageOptions = explode(', ', $package_columns);
                        }
                    }
                    if ($package_feature !== 'feature_not_exists') {
                        // The feature exists
                        $tfeat = count($package_feature) / 2;
                    } else {
                        $tfeat = 0;
                    }
                    $feature_name = array();
                    for ($fn = 1; $fn <= $tfeat; $fn++) {
                        $feature_name['fitem' . $fn] = '';
                        $feature_name['fdesc' . $fn] = '';
                        $feature_name['ftype' . $fn] = '';
                    }
                    $fn = 0;
                    $pbcolor = $bbcolor = $fncolor = '';
                    $final_features = array_merge($feature_name, $package_feature);

                    //Updating all the features
                    update_option($pricing_table . '_feature', $final_features);

                    $common_options = array("templ" => '', "cwidth" => '', "maxcol" => '', "colgap" => '', "capwidth" => '', "ctsize" => '', "cftsize" => '', "tbody" => '', "tsize" => '', "pbody" => '', "psbig" => '', "pssmall" => '', "ftbody" => '', "ftsize" => '', "btbody" => '', "bwidth" => '', "bheight" => '', "btsize" => '', "rtsize" => '', "ttwidth" => '', "cscolor" => '', "cshcolor" => '', "ftdir" => '', "enable" => '', "ftcap" => '', "autocol" => '', "encol" => '', "colshad" => '', "dscol" => '', "ttgrd" => '', "purgt" => '', "entips" => '', "enribs" => '', "tick" => '', "cross" => '', "nltab" => '', "subform" => '');

                    if (is_array($package_combine)) {
                        $final_options = array_merge($common_options, $package_combine);
                    } else {
                        $final_options = $common_options; // Set a default value
                    }

                    //Updating all the common options
                    update_option($pricing_table . '_option', $final_options);

                    foreach ($packageOptions as $option) {
                        $package_values = get_option($option);

                        $package_column_details = array("pdisp" => '', "type" => '', "tdesc" => '', "tcolor" => '', "tbcolor" => '', "fbrow1" => '', "fbrow2" => '', "ftcolor" => '', "price" => '', "pcbig" => '', "cent" => '', "unit" => '', "plan" => '', "pdesc" => '', "btext" => '', "blink" => '', "btcolor" => '', "bthover" => '', "bcolor" => '', "bhover" => '', "rtext" => '', "rtcolor" => '', "rbcolor" => '');

                        for ($fn = 1; $fn <= $tfeat; $fn++) {
                            $feature_values['fitem' . $fn] = '';
                            $feature_values['tip' . $fn] = '';
                        }
                        $column_details = array_merge($package_column_details, $feature_values);
                        $final_column_details = array_merge($column_details, $package_values);
                        //Updating all the columns options
                        update_option($option, $final_column_details);
                    }
                }
            }
        }


        /**
         * Registering plugin activation hooks
         * @return void
         */
        public function wrcpt_plugin_activate()
        {
            do_action('wrcpt_plugin_activation_options');
        }
    }
}

WRCPT_Activation::get_instances();
