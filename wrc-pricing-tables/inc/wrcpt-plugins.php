<?php
/**
 * Class WRCPT_Featured_Plugins
 *
 * This class handles the display of featured plugins from Realwebcare in the WordPress admin area.
 * It is responsible for fetching plugin data, checking installation status, and rendering a user-friendly
 * interface for administrators to explore and install recommended plugins.
 *
 * @package WRC Pricing Tables v2.7.1 - 11 June, 2026
 * @link https://www.realwebcare.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WRCPT_Featured_Plugins')) {
    class WRCPT_Featured_Plugins
    {

        private static $instance;
        private $plugin_checker;

        public function __construct()
        {
            // Load necessary required files.
            $this->required_files();

            // Access the Plugin Checker
            $this->plugin_checker = WRCPT_Plugin_Checker::get_instances();

            $this->wrcpt_rwc_featured_plugins();
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
            /* Check Plugin Data */
            require WRCPT_PLUGIN_PATH . 'lib/plugin-data-checker.php';
        }

        /**
         * Displays a list of featured plugins from Realwebcare in the WordPress admin area.
         *
         * Key functionalities:
         * - Fetches plugin data from the WordPress.org repository or cached data.
         * - Checks plugin installation and activation status.
         * - Displays plugin details, ratings, and compatibility information.
         * - Provides buttons for installation, activation, and viewing demos or pro versions.
         *
         * @return void Outputs the HTML content directly to the page.
         */
        public function wrcpt_rwc_featured_plugins()
        {
            if (is_admin()) {
                // You may comment this out IF you're sure the function exists.
                require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

                $plugin_names = array(
                    'Lemon Squeezy for EDD (Coming Soon)',
                    'T4B News Ticker',
                    'WRC Pricing Tables',
                    'RWC Team Members',
                    'Responsive Portfolio Image Gallery',
                    'Awesome Responsive Photo Gallery',
                );
                $plugin_last_updates = array('2026-04-22');
                $plugin_active_users = array('No Active Installations');
                $pluign_custom_rating = array(0.0);
                $pluign_custom_rating_number = array(0);
                $plugin_custom_compatibility = array('6.9.4');
                $plugin_slugs = array(
                    '',
                    't4b-news-ticker',
                    'wrc-pricing-tables',
                    'rwc-team-members',
                    'responsive-portfolio-image-gallery',
                    'awesome-responsive-photo-gallery',
                );
                $pro_plugin_paths = array(
                    'rwc-lemon-squeezy-edd/rwc-lemon-squeezy-edd.php',
                    't4b-news-ticker-pro/news-ticker.php',
                    'wrc-pricing-tables-ultimate/wrc-pricing-tables.php',
                    'rwc-team-members-pro/rwc-team-members.php',
                    'responsive-portfolio-image-gallery-pro/responsive-gallery.php',
                    '',
                );
                $short_details = array(
                    esc_html__('Seamlessly connect Lemon Squeezy with Easy Digital Downloads (EDD). Accept secure global payments, manage subscriptions, and automate tax compliance with this premium integration.', 'wrc-pricing-tables'),
                    esc_html__('T4B News Ticker is a flexible and user-friendly news ticker plugin for WordPress, designed to create horizontal news tickers with 4 unique animations.', 'wrc-pricing-tables'),
                    esc_html__('Responsive CSS3 pricing tables design to present features and prices of different products. Display pricing tables or comparison table by shortcode.', 'wrc-pricing-tables'),
                    esc_html__('Showcase your team\'s talent and expertise with ease. Grids, slider, pop-up and filters - all in one shortcode. Get started today!', 'wrc-pricing-tables'),
                    esc_html__('Build powerful, lightweight, filterable, responsive portfolio gallery or image gallery on different posts or pages by SHORTCODE.', 'wrc-pricing-tables'),
                    esc_html__('A jQuery lightbox plugin for responsive, touch-enabled photo galleries with smooth fullscreen CSS3 transitions with dynamic Gallery ID functionality.', 'wrc-pricing-tables'),
                );
                $plugin_icons = array(
                    esc_url('https://www.realwebcare.com/plugin/lemon-squeezy-edd/images/lemon-squeezy-edd-256x256.png'),
                    esc_url('https://ps.w.org/t4b-news-ticker/assets/icon-256x256.gif?rev=2092997'),
                    esc_url('https://ps.w.org/wrc-pricing-tables/assets/icon-256x256.gif?rev=2969356'),
                    esc_url('https://ps.w.org/rwc-team-members/assets/icon-256x256.gif?rev=3126712'),
                    esc_url('https://ps.w.org/responsive-portfolio-image-gallery/assets/icon-128x128.jpg?rev=2686002'),
                    esc_url('https://ps.w.org/awesome-responsive-photo-gallery/assets/icon-256x256.png?rev=3237105'),
                );
                $plugin_demos = array(
                    esc_url(''),
                    esc_url('https://youtu.be/CX72IvU51SY'),
                    esc_url('https://www.realwebcare.com/demo/wordpress-responsive-css3-pricing-table-free/'),
                    esc_url('https://www.realwebcare.com/demo/rwc-team-members-wordpress-plugin-free/'),
                    esc_url('https://www.realwebcare.com/demo/responsive-portfolio-image-gallery-free/'),
                    esc_url('https://www.realwebcare.com/demo/awesome-responsive-photo-gallery-free/'),
                );
                $pro_details = array(
                    esc_url(''),
                    esc_url('https://www.realwebcare.com/item/wp-news-ticker-t4b-news-ticker-pro-dynamic-scrolling-plugin/'),
                    esc_url('https://www.realwebcare.com/item/wordpress-responsive-pricing-table-plugin/'),
                    esc_url('https://www.realwebcare.com/item/rwc-team-members-pro-wp-plugin/'),
                    esc_url('https://www.realwebcare.com/item/responsive-portfolio-image-gallery-pro/'),
                    esc_url(''),
                );
                $pro_demos = array(
                    esc_url(''),
                    esc_url('https://www.realwebcare.com/demo/?product_id=t4b-news-ticker-pro'),
                    esc_url('https://www.realwebcare.com/demo/?product_id=wrc-pricing-tables-ultimate'),
                    esc_url('https://www.realwebcare.com/demo/?product_id=rwc-team-members-pro'),
                    esc_url('https://www.realwebcare.com/demo/?product_id=responsive-portfolio-gallery'),
                    esc_url(''),
                );

                echo '<div class="wrap">';
                echo '<h2>' . esc_html__('Our Featured Plugins for Your WordPress Site', 'wrc-pricing-tables') . '</h2><hr>';
                echo '<div id="plugin-list" class="wp-list-table widefat plugin-install">';

                foreach ($plugin_slugs as $key => $slug) {
                    $plugin_data = $this->plugin_checker->wrcpt_fetch_plugin_data_with_cache($slug);

                    // Displaying data
                    if (!empty($plugin_data) && !is_wp_error($plugin_data)) {
                        // Check if the plugin is installed
                        $is_installed = $this->plugin_checker->wrcpt_check_plugin_status($slug);

                        // Define variables for plugin data
                        $plugin_compatibility = array();
                        $plugin_name = !empty($plugin_data['name']) ? esc_html($plugin_data['name']) : esc_html($plugin_names[$key]);
                        $plugin_icon = isset($plugin_data['icon']) && !empty($plugin_data['icon']) ? esc_url($plugin_data['icon']) : $plugin_icons[$key];
                        $plugin_demo = $plugin_demos[$key];
                        $pro_detail = $pro_details[$key];
                        $pro_demo = $pro_demos[$key];
                        $plugin_description = isset($plugin_data['short_description']) && !empty($plugin_data['short_description']) ? esc_html($plugin_data['short_description']) : $short_details[$key];
                        $plugin_author_url = isset($plugin_data['author_profile']) ? esc_url($plugin_data['author_profile']) : '';
                        $plugin_author = isset($plugin_data['contributors']['realwebcare']['display_name']) ? esc_html($plugin_data['contributors']['realwebcare']['display_name']) : __('Realwebcare', 'wrc-pricing-tables');
                        $plugin_rating = isset($plugin_data['rating']) ? number_format(($plugin_data['rating'] / 20), 1) : number_format($pluign_custom_rating[$key], 1);
                        $plugin_num_ratings = isset($plugin_data['num_ratings']) ? intval($plugin_data['num_ratings']) : intval($pluign_custom_rating_number[$key]);
                        $plugin_last_updated = isset($plugin_data['last_updated']) ? esc_html(human_time_diff(strtotime($plugin_data['last_updated']), current_time('U')) . ' ago') : esc_html(human_time_diff(strtotime($plugin_last_updates[$key]), current_time('U')) . ' ago');
                        $plugin_active_installations = isset($plugin_data['active_installs']) ? esc_html($plugin_data['active_installs'] . '+ Active Installations') : esc_html($plugin_active_users[$key]);

                        // Compatibility Check
                        $tested_version = !empty($plugin_data['tested']) ? $plugin_data['tested'] : $plugin_custom_compatibility[$key];
                        $plugin_compatibility = $this->plugin_checker->wrcpt_plugin_compatibility_check($tested_version);

                        // Install/Activate/Active Button
                        $activation_button = $this->plugin_checker->wrcpt_pluign_installation_checker($is_installed, $pro_plugin_paths[$key], $slug, $plugin_name);

                        // Star Rating Calculation
                        $star_rating = $this->plugin_checker->wrcpt_star_rating_calculation($plugin_rating);

                        // Details Link Logic
                        $is_repo_plugin = !empty($slug) && !is_wp_error($plugin_data);
                        $details_url = $is_repo_plugin ? wp_nonce_url(admin_url("plugin-install.php?tab=plugin-information&plugin=$slug&TB_iframe=true&width=772&height=619"), 'plugin-information_' . $slug) : $pro_detail;
                        $details_class = $is_repo_plugin ? 'thickbox open-plugin-details-modal' : '';
                        $details_target = $is_repo_plugin ? '_self' : '_blank';

                        // Plugin Card Output
                        printf(
                            '<div class="plugin-card wrcpt-plugin-card plugin-card-%1$s">
                                <div class="plugin-card-top">
                                    <div class="name column-name">
                                        <h3>
                                            <a href="%2$s" class="%3$s" target="%4$s">%5$s
                                                <img src="%6$s" class="plugin-icon" alt="">
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="action-links">
                                        <ul class="plugin-action-buttons">
                                            <li>%7$s</li>
                                            <li><a href="%8$s" class="button button-secondary button-small" target="_blank">%9$s</a></li>
                                            <li><a href="%2$s" class="%3$s" target="%4$s">%10$s</a></li>
                                            <hr><br>
                                            <li><a href="%11$s" target="_blank">%12$s</a></li>
                                            <li><a href="%13$s" class="button button-secondary button-small" target="_blank">%14$s</a></li>
                                        </ul>
                                    </div>
                                    <div class="desc column-description">
                                        <p>%15$s</p>
                                        <p class="authors">' . esc_html__('By', 'wrc-pricing-tables') . ' <a href="%16$s">%17$s</a></p>
                                    </div>
                                </div>
                                <div class="plugin-card-bottom">
                                    <div class="vers column-rating">
                                        <div class="star-rating">%18$s</div>
                                        <span class="num-ratings" aria-hidden="true">(%19$d)</span>
                                    </div>
                                    <div class="column-updated">
                                        <strong>' . esc_html__('Last Updated:', 'wrc-pricing-tables') . '</strong> %20$s
                                    </div>
                                    <div class="column-downloaded">
                                        %21$s
                                    </div>
                                    <div class="column-compatibility">
                                        <span class="compatibility-%22$s">%23$s</span>
                                    </div>
                                </div>
                            </div>',
                            esc_attr($slug),
                            esc_url($details_url),
                            esc_attr($details_class),
                            esc_attr($details_target),
                            esc_html($plugin_name),
                            esc_url($plugin_icon),
                            wp_kses_post($activation_button),
                            esc_url($plugin_demo),
                            esc_html__('View Demo', 'wrc-pricing-tables'),
                            esc_html__('More Details', 'wrc-pricing-tables'),
                            !empty($pro_detail) ? esc_url($pro_detail) : '#',
                            !empty($pro_detail) ? esc_html__('Pro Details', 'wrc-pricing-tables') : 'Coming Soon',
                            !empty($pro_demo) ? esc_url($pro_demo) : '#',
                            !empty($pro_demo) ? esc_html__('Pro Demo', 'wrc-pricing-tables') : 'Coming Soon',
                            esc_html($plugin_description),
                            esc_url($plugin_author_url),
                            esc_html($plugin_author),
                            wp_kses_post($star_rating),
                            absint($plugin_num_ratings),
                            esc_html($plugin_last_updated),
                            esc_html($plugin_active_installations),
                            esc_attr($plugin_compatibility['class']),
                            esc_html($plugin_compatibility['text'])
                        );
                    }
                }

                echo '</div>'; // End plugin list
                echo '</div>'; // End wrap
            }
        }
    }
}

WRCPT_Featured_Plugins::get_instances();