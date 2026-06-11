<?php
/**
 * Class WRCPT_Plugin_Checker
 *
 * Key functionalities:
 * - Fetches plugin data from the WordPress.org API with transient caching for improved performance.
 * - Checks plugin installation and activation status.
 * - Verifies plugin compatibility with the current WordPress version.
 * - Generates installation, activation, or status buttons for plugins.
 * - Creates HTML markup for star ratings based on plugin ratings.
 * - Normalizes plugin slugs to ensure accurate file path resolution.
 *
 * @package WRC Pricing Tables v2.7.1 - 11 June, 2026
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WRCPT_Plugin_Checker')) {
    class WRCPT_Plugin_Checker
    {

        private static $instance;

        public function __construct()
        {
        }

        public static function get_instances()
        {
            if (self::$instance) {
                return self::$instance;
            }

            self::$instance = new self();

            return self::$instance;
        }

        /**
         * Check if pluign main file is not named as slug
         * 
         * The function normalizes slugs for specific plugins.
         * This ensures the main file names are correctly inferred for those plugins.
         */
        public function wrcpt_check_plugin_slug($slug)
        {
            if ($slug === 't4b-news-ticker') {
                $plugin_file = 'news-ticker';
            } elseif ($slug === 'responsive-portfolio-image-gallery') {
                $plugin_file = 'responsive-gallery';
            } elseif ($slug === 'awesome-responsive-photo-gallery') {
                $plugin_file = 'awesome-gallery';
            } else {
                $plugin_file = $slug;
            }
            return $plugin_file;
        }

        /**
         * Checks the status of a plugin.
         *
         * This function determines whether a plugin is installed, active, or not installed.
         *
         * @param string $slug The plugin slug (directory name and main file without ".php").
         *                     Example: 'plugin-directory/plugin-file'.
         * @return int Returns 2 if the plugin is installed and active,
         *             1 if the plugin is installed but not active,
         *             0 if the plugin is not installed.
         */
        public function wrcpt_check_plugin_status($slug)
        {
            $plugin_file = $this->wrcpt_check_plugin_slug($slug);

            if (!function_exists('is_plugin_active')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            // Check if plugin is installed
            if (file_exists(WP_PLUGIN_DIR . '/' . $slug . '/' . $plugin_file . '.php')) {
                // Plugin is installed, now check if it is active
                if (is_plugin_active($slug . '/' . $plugin_file . '.php')) {
                    // Plugin is installed and active
                    return 2;
                } else {
                    // Plugin is installed but not active
                    return 1;
                }
            } else {
                // Plugin is not installed
                return 0;
            }
        }

        /**
         * Fetch plugin data with transient caching.
         *
         * This function retrieves plugin data from the WordPress.org API and caches the response 
         * using WordPress transients to reduce API requests and improve performance.
         *
         * @param string $slug The slug of the plugin to fetch data for.
         * @return array|WP_Error The plugin data as an associative array, or a WP_Error object on failure.
         */
        public function wrcpt_fetch_plugin_data_with_cache($slug)
        {
            if (empty($slug)) {
                return array('name' => 'Coming Soon'); // Dummy data to ensure it renders
            }

            $transient_key = 'wrcpt_plugin_data_' . sanitize_key($slug);
            $cached_data = get_transient($transient_key);

            if ($cached_data) {
                // Return cached data if available
                return $cached_data;
            }

            // Prepare API arguments
            $args = array(
                'slug' => sanitize_text_field($slug),
                'fields' => array(
                    'sections' => false,
                    'reviews' => false,
                    'versions' => false,
                    'tags' => false,
                    'screenshots' => false,
                ),
            );

            // Build API URL
            $api_url = add_query_arg(
                array(
                    'action' => 'plugin_information',
                    'request' => $args,
                ),
                esc_url('http://api.wordpress.org/plugins/info/1.2/')
            );

            // Fetch data from API
            $response = wp_remote_get($api_url);

            if (is_wp_error($response)) {
                // Handle error
                return new WP_Error('api_fetch_error', __('Failed to fetch plugin data.', 'wrc-pricing-tables'));
            }

            $plugin_data = json_decode(wp_remote_retrieve_body($response), true);

            if (!empty($plugin_data)) {
                // Cache the API response for 15 minutes (900 seconds)
                set_transient($transient_key, $plugin_data, 900);
            }

            return $plugin_data;
        }

        /**
         * Check plugin compatibility with the current WordPress version.
         *
         * This function determines the compatibility of a plugin with the current WordPress version
         * based on provided version data. It returns a message and a CSS class indicating compatibility.
         *
         * @param string|null $data The tested version of WordPress for the plugin, or null if unavailable.
         * @return array An associative array containing the compatibility text and CSS class.
         */
        public function wrcpt_plugin_compatibility_check($data)
        {
            $plugin_compatibility = array();

            $compatible_text = isset($data) ? (
                version_compare(get_bloginfo('version'), $data, '<=') ?
                __('Compatible with your version of WordPress', 'wrc-pricing-tables') :
                __('Untested with your version of WordPress', 'wrc-pricing-tables')
            ) : __('Compatibility information not available', 'wrc-pricing-tables');

            if ($compatible_text === 'Compatible with your version of WordPress') {
                $compatible_class = 'compatible';
            } elseif ($compatible_text === 'Untested with your version of WordPress') {
                $compatible_class = 'untested';
            } else {
                $compatible_class = 'unavailable';
            }

            $plugin_compatibility = array('text' => $compatible_text, 'class' => $compatible_class);

            return $plugin_compatibility;
        }

        /**
         * Generate plugin installation or activation button based on the plugin's installation state.
         *
         * This function checks the installation status of a plugin and generates the appropriate button
         * for installing, activating, or indicating the plugin is already active. It ensures that 
         * necessary actions are performed securely with nonce verification.
         *
         * @param int $install The installation state (0 = not installed, 1 = installed but not active).
         * @param string $pro_plugin_paths The relative path to the plugin's directory or file.
         * @param string $slug The slug of the plugin.
         * @param string $name The name of the plugin.
         * @return string HTML markup for the installation or activation button.
         */
        public function wrcpt_pluign_installation_checker($install, $pro_plugin_paths, $slug, $name)
        {
            if (empty($slug)) {
                $activation_button = '<button type="button" class="button button-disabled" disabled="disabled">' . esc_html__('Coming Soon', 'wrc-pricing-tables') . '</button>';
                return $activation_button;
            }

            $pro_installed = !empty($pro_plugin_paths) && file_exists(WP_PLUGIN_DIR . '/' . $pro_plugin_paths);
            
            if (!function_exists('is_plugin_active')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $pro_active = $pro_installed && is_plugin_active($pro_plugin_paths);

            if ($install === 2 || $pro_active) {
                $activation_button = '<button type="button" class="button button-disabled" disabled="disabled">' . esc_html__('Active', 'wrc-pricing-tables') . '</button>';
            } elseif ($install === 1) {
                $plugin_file = $this->wrcpt_check_plugin_slug($slug);
                $plugin_activate_url = wp_nonce_url(self_admin_url('plugins.php?action=activate&amp;plugin=' . $slug . '/' . $plugin_file . '.php'), 'activate-plugin_' . $slug . '/' . $plugin_file . '.php');
                $activation_button = '<a href="' . esc_url($plugin_activate_url) . '" data-name="' . esc_attr($name) . '" data-slug="' . esc_attr($slug) . '" class="button button-primary activate-now" aria-label="' . esc_attr($name) . '" role="button">' . esc_html__('Activate', 'wrc-pricing-tables') . '</a>';
            } elseif ($pro_installed && !$pro_active) {
                $plugin_activate_url = wp_nonce_url(self_admin_url('plugins.php?action=activate&amp;plugin=' . $pro_plugin_paths), 'activate-plugin_' . $pro_plugin_paths);
                $activation_button = '<a href="' . esc_url($plugin_activate_url) . '" data-name="' . esc_attr($name) . '" data-slug="' . esc_attr($slug) . '" class="button button-primary activate-now" aria-label="' . esc_attr($name) . '" role="button">' . esc_html__('Activate', 'wrc-pricing-tables') . '</a>';
            } else {
                $plugin_install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&amp;plugin=' . $slug), 'install-plugin_' . $slug);
                $activation_button = '<a class="install-now button" data-slug="' . esc_attr($slug) . '" href="' . esc_url($plugin_install_url) . '" aria-label="Install ' . esc_attr($name) . ' now" data-name="' . esc_attr($name) . '" role="button">' . esc_html__('Install Now', 'wrc-pricing-tables') . '</a>';
            }

            return $activation_button;
        }

        /**
         * Generate HTML for star rating display based on a given rating.
         *
         * This function calculates the number of full, half, and empty stars based on the provided rating 
         * and generates the corresponding HTML markup for a visual star rating representation.
         *
         * @param float $rating The rating value (e.g., 4.5).
         * @return string HTML markup for the star rating, including full, half, and empty stars.
         */
        public function wrcpt_star_rating_calculation($rating)
        {
            $full_stars = floor($rating); // Calculate full stars
            $half_star = ($rating - $full_stars >= 0.5) ? 1 : 0; // Check if half star is needed
            $empty_stars = 5 - ($full_stars + $half_star); // Calculate empty stars
            $star_rating_html = str_repeat('<div class="star star-full" aria-hidden="true"></div>', $full_stars);
            if ($half_star) {
                $star_rating_html .= '<div class="star star-half" aria-hidden="true"></div>';
            }
            $star_rating_html .= str_repeat('<div class="star star-empty" aria-hidden="true"></div>', $empty_stars);

            return $star_rating_html;
        }
    }
}