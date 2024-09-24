<?php
/*
 * Plugin Name:       WordPress Responsive CSS3 Pricing Tables
 * Plugin URI:        http://wordpress.org/plugins/wrc-pricing-tables/
 * Description:       Responsive pricing table plugin developed to display pricing table in a lot more professional way on different posts or pages by SHORTCODE.
 * Version:           2.4.3
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Realwebcare
 * Author URI:        https://www.realwebcare.com/
 * Text Domain:       wrc-pricing-tables
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Main plugin file that initializes and manages the "WRC Pricing Tables" plugin.
 * @package WRC Pricing Tables 2.4.3 - 1-August-2024
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
define('WRCPT_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

/**
 * Internationalization
 */
if (!function_exists('wrcpt_textdomain')) {
    function wrcpt_textdomain() {
        $locale = apply_filters( 'plugin_locale', get_locale(), 'wrc-pricing-tables' );
        load_textdomain( 'wrc-pricing-tables', trailingslashit( WP_PLUGIN_DIR ) . 'wrc-pricing-tables/languages/wrc-pricing-tables-' . $locale . '.mo' );
        load_plugin_textdomain( 'wrc-pricing-tables', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}
add_action( 'init', 'wrcpt_textdomain' );

/**
 * Add plugin action links
 */
if (!function_exists('wrcpt_plugin_actions')) {
    function wrcpt_plugin_actions( $links ) {
        $create_table_url = esc_url(menu_page_url('wrcpt-template', false));
        $create_table_url = wp_nonce_url($create_table_url, 'wrcpt_create_table_action');

        $support_url = esc_url("https://wordpress.org/support/plugin/wrc-pricing-tables");

        $links[] = '<a href="'. $create_table_url .'">'. esc_html__('Create Table', 'wrc-pricing-tables') .'</a>';
        $links[] = '<a href="'. $support_url .'" target="_blank">'. esc_html__('Support', 'wrc-pricing-tables') .'</a>';
        return $links;
    }
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wrcpt_plugin_actions' );

/* Admin Menu */
if(is_admin()) { include ( WRCPT_PLUGIN_PATH . 'inc/admin-menu.php' ); }

/**
 * Admin enqueue styles and scripts
 */
if (!function_exists('wrcpt_enqueue_scripts_admin')) {
    function wrcpt_enqueue_scripts_admin() {
        wp_register_script('wrcptjs', plugins_url( 'js/wrcpt-admin.js', __FILE__ ), array('jquery'), '2.4.3');
        wp_enqueue_script('wrcptjs');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('wp-color-picker');
        $nonce = wp_create_nonce('wrcpt_ajax_action_nonce');
        wp_localize_script('wrcptjs', 'wrcptajax', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => $nonce,
        ));
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('wrcptfront', plugins_url( 'css/wrcpt-front.css', __FILE__ ), '', '2.4.3');
        wp_enqueue_style('wrcptadmin', plugins_url( 'css/wrcpt-admin.css', __FILE__ ), '', '2.4.3');
        wp_enqueue_style('jquery-ui-style', plugins_url( 'css/jquery-accordion.css', __FILE__ ), '', '1.10.4');
    }
}
add_action('admin_enqueue_scripts', 'wrcpt_enqueue_scripts_admin');

/**
 * Enqueue styles and scripts at fromt-end
 */
if (!function_exists('wrcpt_pricing_table_enqueue')) {
    function wrcpt_pricing_table_enqueue() {
        wp_register_style('wrcptfront', plugins_url( 'css/wrcpt-front.css', __FILE__ ), array(), '2.4.3' );
        wp_register_style('googleFonts', '//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Roboto:wght@400;700&display=swap', array(), null);
        wp_enqueue_style( 'wrcptfront');
        wp_enqueue_style( 'googleFonts');
    }
}
add_action('wp_enqueue_scripts', 'wrcpt_pricing_table_enqueue');

/**
* Get the current time and set it as an option when the plugin is activated.
* @return null
*/
if (!function_exists('wrcpt_set_activation_time')) {
    function wrcpt_set_activation_time(){
        $get_activation_time = strtotime("now");
        add_option('wrcpt_activation_time', $get_activation_time );
    }
}
register_activation_hook( __FILE__, 'wrcpt_set_activation_time' );

/**
* Check date on admin initiation and add to admin notice if it was over 7 days ago.
* @return null
*/
if (!function_exists('wrcpt_check_installation_date')) {
    function wrcpt_check_installation_date() {
        $spare_me = "";
        $spare_me = get_option('wrcpt_spare_me');
    
        if (!$spare_me) {
            $install_date = get_option( 'wrcpt_activation_time', 'default_value' );
            $past_date = strtotime( '-7 days' );

            if ($install_date !== 'default_value' && $install_date < $past_date) {
                add_action( 'admin_notices', 'wrcpt_display_admin_notice' );
            } else {
                $get_activation_time = strtotime("now");
                add_option('wrcpt_activation_time', $get_activation_time );
            }
        }
    }
}
add_action( 'admin_init', 'wrcpt_check_installation_date' );

/**
* Display Admin Notice, asking for a review
**/
if (!function_exists('wrcpt_display_admin_notice')) {
    function wrcpt_display_admin_notice() {
        // WordPress global variable 
        global $pagenow;
        if (is_admin() && $pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wrcpt-menu') {
            $dont_disturb = esc_url(admin_url('admin.php?page=wrcpt-menu&spare_me=1'));
            $plugin_info = get_plugin_data(__FILE__, true, true);
            $reviewurl = esc_url('https://wordpress.org/support/plugin/' . sanitize_title($plugin_info['TextDomain']) . '/reviews/');

            printf(
                __('<div id="wrcpt-review" class="notice notice-success is-dismissible"><p>It\'s been 7 days since your last update or installation of the latest version of <b>%s</b>! We hope you\'ve had a positive experience so far.</p><p>Your feedback is important to us and can help us improve. If you find our pricing table plugin valuable, could you please take a moment to share your thoughts by leaving a quick review?</p><div class="wrcpt-review-btn"><a href="%s" class="button button-primary" target="_blank">Leave a Review</a><a href="%s" class="wrcpt-grid-review-done button button-secondary">Already Left a Review</a></div></div>'),
                $plugin_info['Name'],
                $reviewurl,
                $dont_disturb
            );
        }
    }
}

/**
* remove the notice for the user if review already done or if the user does not want to
**/
if (!function_exists('wrcpt_spare_me')) {
    function wrcpt_spare_me() {    
        if( isset( $_GET['spare_me'] ) && !empty( $_GET['spare_me'] ) ) {
            $spare_me = $_GET['spare_me'];
            if( $spare_me == 1 ) {
                add_option( 'wrcpt_spare_me' , TRUE );
            }
        }
    }
}
add_action( 'admin_init', 'wrcpt_spare_me', 5 );

/**
 * Add meta viewport in head section
 * A <meta> viewport element gives the browser instructions on how to control the page's dimensions and scaling.
 */
if (!function_exists('wrcpt_add_view_port')) {
    function wrcpt_add_view_port() {
		echo '<meta name="viewport" content="' . esc_attr('width=device-width, initial-scale=1, maximum-scale=1') . '">';
    }
}
add_action('wp_head', 'wrcpt_add_view_port');

/**
 * adjust brightness of a colour
 * not the best way to do it but works well enough here
 *
 * @param type $hex
 * @param type $steps
 * @return type
 */
if (!function_exists('wrcpt_adjustBrightness')) {
    function wrcpt_adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', esc_attr($hex));

        // Convert shorthand color code to full-length format
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        if (preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            // Split into three parts: R, G and B
            $color_parts = str_split($hex, 2);
            $return = '#';

            foreach ($color_parts as $color) {
                // Convert to decimal
                $color   = hexdec($color);
                // Adjust color
                $color   = max(0, min(255, $color + $steps));
                // Make two char hex code
                $return .= str_pad(dechex(intval($color)), 2, '0', STR_PAD_LEFT);
            }
            return $return;
        } else {
            return "Invalid input: '$hex'. Please provide a valid six-digit hexadecimal color code.";
        }
    }
}

/**
 * Pricing Table Shortcode
 */
if (!function_exists('wrcpt_pricing_table_shortcode')) {
    function wrcpt_pricing_table_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts( array(
            'id' => 1
        ), $atts, 'wrc-pricing-table' );

        // Sanitize and validate $id here
        $id = absint( $atts['id'] );

        $f_value = $f_tips = '';
        $total_feature = $flag = 0;

        $pricing_table_lists = get_option('packageTables');
        $pricing_id_lists = get_option('packageIDs');
        $pricing_table_lists = explode(', ', $pricing_table_lists);
        $pricing_id_lists = explode(', ', $pricing_id_lists);

        // Sanitize and validate $pricing_table_lists and $pricing_id_lists

        $key = array_search($id, $pricing_id_lists);
        if( $key !== false ) {
            $flag = 1;
        }

        $pricing_table = isset( $pricing_table_lists[ $key ] ) ? $pricing_table_lists[ $key ] : '';
        $tableID = $pricing_table ? strtolower($pricing_table) . '-' .$id : '';

        // Sanitize and validate $pricing_table and $tableID

        $package_feature = get_option( $pricing_table.'_feature' );
        $packageCombine = get_option( $pricing_table.'_option' );

        // Sanitize and validate $package_feature and $packageCombine

        if( $package_feature ) {
            $total_feature = count( $package_feature ) / 2;
        }

        $package_lists = get_option( $pricing_table );
        $packageOptions = explode( ', ', $package_lists );
        $package_count = count( $packageOptions );

        // Sanitize and validate $package_lists and $packageOptions

        require_once( WRCPT_PLUGIN_PATH . 'lib/process-shortcode.php' );

        ob_start();
        echo wrcpt_shortcode(
            $pricing_table,
            $tableID,
            $package_feature,
            $packageCombine,
            $total_feature,
            $package_lists,
            $packageOptions,
            $package_count,
            $flag
        );
        
        return ob_get_clean();
    }
}
add_shortcode('wrc-pricing-table', 'wrcpt_pricing_table_shortcode');