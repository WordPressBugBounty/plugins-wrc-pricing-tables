<?php
/**
 * Initial Plugin Setup Functions
 *
 * This file contains functions that play a crucial role in the initial setup
 * of the "WRC Pricing Tables" plugin. These functions handle tasks such as
 * text domain setup for translations, adding action links to the plugin settings,
 * and various other essential tasks needed when the plugin is live at the front-end.
 * It's important to understand the role of each function before making any modifications,
 * as they collectively ensure a smooth and error-free activation process.
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Init_Functions')) {
    class WRCPT_Init_Functions
    {
        private static $instance;

        public function __construct()
        {
            /* Internationalization */
            add_action('init', array($this, 'wrcpt_textdomain'));

            /**
             * Hook into the plugin action links to add custom links
             * This filter allows us to modify the action links displayed on the Plugins page for our plugin
             * We're adding our custom function 'wrcpt_plugin_actions' to be called when this filter is triggered
             */
            add_filter('plugin_action_links_' . plugin_basename(WRCPT_AUF), array($this, 'wrcpt_plugin_actions'));

            add_action('admin_init', array($this, 'wrcpt_check_installation_date'));
            add_action('admin_init', array($this, 'wrcpt_spare_me'), 5);

            /* Add meta viewport in head section */
            add_action('wp_head', array($this, 'wrcpt_add_view_port'));
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
         * Internationalization
         * @return void
         */
        public function wrcpt_textdomain()
        {
            $locale = apply_filters('plugin_locale', get_locale(), 'wrc-pricing-tables');
            load_textdomain('wrc-pricing-tables', WRCPT_PLUGIN_PATH . 'wrc-pricing-tables/languages/wrc-pricing-tables-' . $locale . '.mo');
        }

        /**
         * Add plugin action links
         * @param mixed $links
         */
        public function wrcpt_plugin_actions($links)
        {
            $create_table_url = esc_url(menu_page_url('wrcpt-template', false));
            $create_table_url = wp_nonce_url($create_table_url, 'wrcpt_create_table_action');

            $help_table_url = esc_url(menu_page_url('wrcpt-help', false));
            $help_table_url = wp_nonce_url($help_table_url, 'wrcpt_help_table_action');

            $support_url = esc_url("https://wordpress.org/support/plugin/wrc-pricing-tables");

            $links[] = '<a href="' . $create_table_url . '">' . esc_html__('Create Table', 'wrc-pricing-tables') . '</a>';
            $links[] = '<a href="' . $help_table_url . '">' . esc_html__('Help', 'wrc-pricing-tables') . '</a>';
            $links[] = '<a href="' . $support_url . '" target="_blank">' . esc_html__('Support', 'wrc-pricing-tables') . '</a>';
            return $links;
        }

        /**
         * Check date on admin initiation and add to admin notice if it was over 7 days ago.
         * @return null
         */
        public function wrcpt_check_installation_date()
        {
            // Retrieve the 'spare_me' option
            $spare_me = get_option('wrcpt_spare_me', false);

            // Proceed only if 'spare_me' is not set
            if (!$spare_me) {
                // Retrieve the 'i10n_date' option and set a default value if not found
                $i10n_date = get_option('wrcpt_activation_time', false);

                // Calculate the past date for the threshold (7 days in seconds: 7 * 24 * 60 * 60)
                $past_date = strtotime('-7 days');

                // Validate the 'i10n_date' and compare it with the threshold
                if ($i10n_date && is_numeric($i10n_date) && $i10n_date < $past_date) {
                    // If the condition is met, display the admin notice
                    add_action('admin_notices', array($this, 'wrcpt_display_admin_notice'));
                } else {
                    // Otherwise, store the current timestamp as the activation time
                    $current_time = time(); // Current timestamp
                    update_option('wrcpt_activation_time', $current_time);
                }
            }
        }

        /**
         * Display Admin Notice, asking for a review
         **/
        public function wrcpt_display_admin_notice()
        {
            // WordPress global variable 
            global $pagenow;

            // Validate current admin page and query parameters
            if (is_admin() && $pagenow === 'admin.php' && isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === 'wrcpt-lists') {

                // Generate URLs with proper escaping
                $dont_disturb = esc_url_raw(admin_url('admin.php?page=wrcpt-lists&spare_me=1'));
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
                    '<div id="wrcpt-review" class="notice notice-success is-dismissible">
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

                /* printf(
                    __('<div id="wrcpt-review" class="notice notice-success is-dismissible"><p>It\'s been 7 days since your last update or installation of the latest version of <b>%s</b>! We hope you\'ve had a positive experience so far.</p><p>Your feedback is important to us and can help us improve. If you find our team members plugin valuable, could you please take a moment to share your thoughts by leaving a quick review?</p><div class="wrcpt-review-btn"><a href="%s" class="button button-primary" target="_blank">Leave a Review</a><a href="%s" class="wrcpt-grid-review-done button button-secondary">Already Left a Review</a></div></div>'),
                    $plugin_info['Name'],
                    esc_url($reviewurl),
                    esc_url($dont_disturb)
                ); */
            }
        }

        /**
         * Remove the notice for the user if review already done or if the user does not want to
         **/
        public function wrcpt_spare_me()
        {
            // Check if 'review_nt' parameter is set and not empty
            if (isset($_GET['spare_me']) && !empty($_GET['spare_me'])) {
                // Verify the nonce
                if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'wrcpt_disturb_action')) {
                    wp_die(esc_html__('Nonce verification failed. Please try again.', 'wrc-pricing-tables'));
                }

                // Sanitize the input value to ensure it is safe to use
                $spare_me = sanitize_text_field(wp_unslash($_GET['spare_me']));

                // Validate the value to check if it is the expected value
                if ($spare_me === '1') {
                    // Add the 'wrcpt_spare_me' option with a boolean value
                    add_option('wrcpt_spare_me', true);
                }
            }
        }

        /**
         * Add meta viewport in head section
         * A <meta> viewport element gives the browser instructions on how to control the page's dimensions and scaling.
         */
        public function wrcpt_add_view_port()
        {
            echo '<meta name="viewport" content="' . esc_attr('width=device-width, initial-scale=1, maximum-scale=1') . '">';
        }

        /**
         * Adjust brightness of a colour
         * not the best way to do it but works well enough here
         * @param mixed $hex
         * @param mixed $steps
         * @return string
         */
        public function wrcpt_adjustBrightness($hex, $steps)
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

		/**
		 * Find how many tables are plublished
		 * @param mixed $table_lists
		 */
		public function wrcpt_published_tables_count($table_lists) {
			// Check if the user has the necessary capability (e.g., manage_options)
			if (!current_user_can('manage_options')) {
				// If the user does not have the required capability, terminate and display an error message.
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
			} else {
				$count = 0;
				foreach($table_lists as $key => $list) {
					$packageCombine = get_option($list.'_option');
					if(isset($packageCombine['enable']) && $packageCombine['enable'] == 'yes') {
						$count++;
					}
				}
				return $count;
			}
		}

		/**
		 * Find unuseful package options and delete them
		 */
		public function wrcpt_remove_unuseful_package_options() {
			// Check if the user has the necessary capability (e.g., manage_options)
			if (!current_user_can('manage_options')) {
				// If the user does not have the required capability, terminate and display an error message.
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
			} else {
				$package_table = get_option('packageTables');
				$table_lists = explode(', ', $package_table);
				$temp = ''; $pcount = 0;

                foreach($table_lists as $table) :
					$table_options = get_option($table);
					$table_options_list = $temp . $table_options;
					$temp = $table_options_list.', ';
				endforeach;

                $total_table_options = explode(', ', $table_options_list);

                /* counting packageOptions1-100 to check
				* if any unuseful package exist or not */
				for($i = 1; $i <= 500; $i++) {
					$package_option = 'packageOptions'.$i;
					if(get_option($package_option) == true && !in_array($package_option, $total_table_options)) {
						delete_option($package_option);
						$pcount++; // Increment the count for each deleted package
					}
				}
				return $pcount; // Return the count after the loop completes
			}
		}
		
		/**
		 * Count unuseful package options and show number
		 */
		public function wrcpt_count_unuseful_package_options() {
			// Check if the user has the necessary capability (e.g., manage_options)
			if (!current_user_can('manage_options')) {
				// If the user does not have the required capability, terminate and display an error message.
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
			} else {
				$package_table = get_option('packageTables');
				$table_lists = explode(', ', $package_table);
				$temp = ''; $upcount = 0;

                foreach($table_lists as $table) :
					$table_options = get_option($table);
					$table_options_list = $temp . $table_options;
					$temp = $table_options_list.', ';
				endforeach;

                $total_table_options = explode(', ', $table_options_list);

                /* counting packageOptions1-100 to check
				* if any unuseful package exist or not */
				for($i = 1; $i <= 500; $i++) {
					$package_option = 'packageOptions'.$i;
					if(get_option($package_option) == true && !in_array($package_option, $total_table_options)) {
						$upcount++; // Increment the count for each deleted package
					}
				}
				return $upcount; // Return the count after the loop completes
			}
		}
    }
}

WRCPT_Init_Functions::get_instances();
