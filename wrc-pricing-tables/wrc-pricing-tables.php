<?php
/*
 * Plugin Name:       WRC Pricing Tables – Responsive CSS3 Pricing Tables
 * Plugin URI:        http://wordpress.org/plugins/wrc-pricing-tables/
 * Description:       Responsive pricing table plugin developed to display pricing table in a lot more professional way on different posts or pages by SHORTCODE.
 * Version:           2.5
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
 * @package WRC Pricing Tables v2.5 - 28 May, 2025
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
define('WRCPT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WRCPT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WRCPT_AUF', __FILE__);

/**
 * Internationalization
 */
if (!function_exists('wrcpt_textdomain')) {
    function wrcpt_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'wrc-pricing-tables');
        load_textdomain('wrc-pricing-tables', WRCPT_PLUGIN_PATH . 'wrc-pricing-tables/languages/wrc-pricing-tables-' . $locale . '.mo');
    }
}
add_action('init', 'wrcpt_textdomain');

/**
 * Add plugin action links
 */
if (!function_exists('wrcpt_plugin_actions')) {
    function wrcpt_plugin_actions($links)
    {
        $create_table_url = esc_url(menu_page_url('wrcpt-template', false));
        $create_table_url = wp_nonce_url($create_table_url, 'wrcpt_create_table_action');

        $support_url = esc_url("https://wordpress.org/support/plugin/wrc-pricing-tables");

        $links[] = '<a href="' . $create_table_url . '">' . esc_html__('Create Table', 'wrc-pricing-tables') . '</a>';
        $links[] = '<a href="' . $support_url . '" target="_blank">' . esc_html__('Support', 'wrc-pricing-tables') . '</a>';
        return $links;
    }
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wrcpt_plugin_actions');

/* Admin Menu */
if (is_admin()) {
    include(WRCPT_PLUGIN_PATH . 'inc/admin-menu.php');
}

/**
 * Admin enqueue styles and scripts
 */
if (!function_exists('wrcpt_enqueue_scripts_admin')) {
    function wrcpt_enqueue_scripts_admin()
    {
        wp_enqueue_script('wrcptjs', WRCPT_PLUGIN_URL . 'assets/js/wrcpt-admin.js', array('jquery'), '2.5', ['in_footer' => true]);
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
            'success_switch' => __('Template Switched Successfully! Redirecting...', 'wrc-pricing-tables'),
            'updating_table' => __('Update is being processed. Please wait...', 'wrc-pricing-tables'),
            'update_success' => __('Update completed successfully!', 'wrc-pricing-tables'),
            'error_message' => __('An error occurred. Please try again.', 'wrc-pricing-tables'),
            'loading_image' => WRCPT_PLUGIN_URL . 'assets/images/ajax-loader.gif',
            'main_menu_url' => admin_url('admin.php?page=wrcpt-menu'), // Add the main menu URL,
        ));
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('wrcptfront', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-front.css', [], '2.5');
        wp_enqueue_style('wrcptadmin', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-admin.css', [], '2.5');
        wp_enqueue_style('jquery-ui-style', WRCPT_PLUGIN_URL . 'assets/css/jquery-accordion.css', [], '1.10.4');
    }
}
add_action('admin_enqueue_scripts', 'wrcpt_enqueue_scripts_admin');

/**
 * Enqueue styles and scripts at fromt-end
 */
if (!function_exists('wrcpt_pricing_table_enqueue')) {
    function wrcpt_pricing_table_enqueue()
    {
        wp_enqueue_style('wrcptfront', WRCPT_PLUGIN_URL . 'assets/css/wrcpt-front.css', array(), '2.5');
        wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Roboto:wght@400;700&display=swap', array(), '2.5');
    }
}
add_action('wp_enqueue_scripts', 'wrcpt_pricing_table_enqueue');

/**
 * Get the current time and set it as an option when the plugin is activated.
 * @return null
 */
if (!function_exists('wrcpt_set_activation_time')) {
    function wrcpt_set_activation_time()
    {
        $get_activation_time = strtotime("now");
        add_option('wrcpt_activation_time', $get_activation_time);
    }
}
register_activation_hook(__FILE__, 'wrcpt_set_activation_time');

/**
 * Check date on admin initiation and add to admin notice if it was over 7 days ago.
 * @return null
 */
if (!function_exists('wrcpt_check_installation_date')) {
    function wrcpt_check_installation_date()
    {
        // Retrieve the 'spare_me' option
        $spare_me = get_option('wrcpt_spare_me', false);

        // Proceed only if 'spare_me' is not set
        if (!$spare_me) {
            // Retrieve the 'i10n_date' option and set a default value if not found
            $i10n_date = get_option('wrcpt_activation_time', false);

            // Calculate the past date for the threshold (7 days in seconds: 7 * 24 * 60 * 60)
            $past_date = strtotime('-7 minutes');

            // Validate the 'i10n_date' and compare it with the threshold
            if ($i10n_date && is_numeric($i10n_date) && $i10n_date < $past_date) {
                // If the condition is met, display the admin notice
                add_action('admin_notices', 'wrcpt_display_admin_notice');
            } else {
                // Otherwise, store the current timestamp as the activation time
                $current_time = time(); // Current timestamp
                update_option('wrcpt_activation_time', $current_time);
            }
        }
    }
}
add_action('admin_init', 'wrcpt_check_installation_date');

/**
 * Display Admin Notice, asking for a review
 **/
if (!function_exists('wrcpt_display_admin_notice')) {
    function wrcpt_display_admin_notice()
    {
        // WordPress global variable 
        global $pagenow;

        if (is_admin() && $pagenow === 'admin.php' && isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'wrcpt-menu') {

            // Generate URLs with proper escaping
            $dont_disturb = esc_url_raw(admin_url('admin.php?page=wrcpt-menu&wrcpt_spare_me=1'));
            $dont_disturb = wp_nonce_url($dont_disturb, 'wrcpt_disturb_action');

            // Retrieve plugin data securely
            $plugin_info = get_plugin_data(WRCPT_AUF, true, true);

            // Validate and sanitize plugin data
            $plugin_name = esc_html__('WRC Pricing Tables', 'wrc-pricing-tables');
            $text_domain = !empty($plugin_info['TextDomain']) ? sanitize_title($plugin_info['TextDomain']) : 'wrc-pricing-tables';

            // Construct the review URL securely
            $review_url = 'https://wordpress.org/support/plugin/' . $text_domain . '/reviews/';
            $review_url = wp_nonce_url($review_url, 'wrcpt_review_action');

            // Output the notice with proper escaping
            printf(
                '<div id="wrcpt-review" class="wrcpt-notice wrcpt-notice-success wrcpt-is-dismissible">
                    <span class="wrcpt-close-icon">&times;</span>
                    <p>%1$s</p>
                    <p>%2$s</p>
                    <div class="wrcpt-review-btn">
                        <a href="%3$s" class="button button-primary" target="_blank">%4$s</a>
                        <a href="%5$s" class="wrcpt-grid-review-done button button-secondary">%6$s</a>
                    </div>
                </div>',
                esc_html__('It\'s been 7 days since your last update or installation of the latest version of ', 'wrc-pricing-tables') . '<b>' . esc_html($plugin_name) . '</b>' . esc_html__('! We hope you\'ve had a positive experience so far.', 'wrc-pricing-tables'),
                esc_html__('Your feedback is important to us and can help us improve. If you find our ', 'wrc-pricing-tables') . '<b>' . esc_html($plugin_name) . '</b>' . esc_html__(' plugin valuable, could you please take a moment to share your thoughts by leaving a quick review?', 'wrc-pricing-tables'),
                esc_url($review_url),
                esc_html__('Leave a Review', 'wrc-pricing-tables'),
                esc_url($dont_disturb),
                esc_html__('Already Left a Review', 'wrc-pricing-tables')
            );
        }
    }
}

/**
 * remove the notice for the user if review already done or if the user does not want to
 **/
if (!function_exists('wrcpt_spare_me')) {
    function wrcpt_spare_me()
    {
        if (isset($_GET['wrcpt_spare_me']) && !empty($_GET['wrcpt_spare_me'])) {
            $spare_me = isset($_GET['wrcpt_spare_me']) ? sanitize_text_field(wp_unslash($_GET['wrcpt_spare_me'])) : '';
            if ($spare_me == 1) {
                add_option('wrcpt_spare_me', true);
            }
        }
    }
}
add_action('admin_init', 'wrcpt_spare_me', 5);

/**
 * Add meta viewport in head section
 * A <meta> viewport element gives the browser instructions on how to control the page's dimensions and scaling.
 */
if (!function_exists('wrcpt_add_view_port')) {
    function wrcpt_add_view_port()
    {
        echo '<meta name="viewport" content="' . esc_attr('width=device-width, initial-scale=1, maximum-scale=1') . '">';
    }
}
add_action('wp_head', 'wrcpt_add_view_port');


/**
 * adjust brightness of a colour
 * not the best way to do it but works well enough here
 * @param mixed $hex
 * @param mixed $steps
 * @return string
 */
if (!function_exists('wrcpt_adjustBrightness')) {
    function wrcpt_adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', esc_attr($hex));

        // Convert shorthand color code to full-length format
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        if (preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            // Split into three parts: R, G and B
            $color_parts = str_split($hex, 2);
            $return = '#';

            foreach ($color_parts as $color) {
                // Convert to decimal
                $color = hexdec($color);
                // Adjust color
                $color = max(0, min(255, $color + $steps));
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
    function wrcpt_pricing_table_shortcode($atts, $content = null)
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
            wrcpt_shortcode(
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
add_shortcode('wrc-pricing-table', 'wrcpt_pricing_table_shortcode');
