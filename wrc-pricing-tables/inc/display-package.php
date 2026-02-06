<?php
/**
 * Generating preview of the pricing table in WP admin panel
 * to get an idea about how the table will look at front-end
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Preview')) {
	class WRCPT_Preview
	{
		private static $instance;

		public function __construct()
		{
			add_action('wp_ajax_nopriv_wrcpt_view_pricing_packages', array($this, 'wrcpt_view_pricing_packages'));
			add_action('wp_ajax_wrcpt_view_pricing_packages', array($this, 'wrcpt_view_pricing_packages'));
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
		 * Handles the AJAX request to display a pricing table's package columns.
		 * Verifies user capabilities and nonce security before processing the request.
		 * Retrieves the requested pricing table packages, renders the shortcode output,
		 * and returns the generated HTML inside the AJAX response.
		 * 
		 * @return void
		 */
		public function wrcpt_view_pricing_packages()
		{
			// Check if the user has the necessary capability (e.g., manage_options)
			if (!current_user_can('manage_options')) {
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
			}
			// Get the nonce from the AJAX request data
			$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

			// Verify the nonce
			if (!wp_verify_nonce($nonce, 'wrcpt_ajax_action_nonce')) {
				// Nonce verification failed, handle the error
				wp_send_json_error(array('message' => 'Nonce verification failed'));
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
			} else {
				$pricing_table = isset($_POST['packtable']) ? sanitize_text_field(wp_unslash($_POST['packtable'])) : '';
				$tableId = isset($_POST['tableid']) ? sanitize_text_field(wp_unslash($_POST['tableid'])) : '';
				$package_lists = get_option($pricing_table);
				$packageOptions = explode(', ', $package_lists);
				$packageCount = intval(count($packageOptions)); ?>

				<div id="tabledisplaydiv">
					<h3><span id="editPackages" class="button button-large" onclick="wrcpteditpackages(<?php echo esc_attr($packageCount); ?>, '<?php echo esc_attr($pricing_table); ?>')"><?php esc_html_e('Edit Columns', 'wrc-pricing-tables'); ?></span>
					</h3>
					<?php echo do_shortcode('[wrc-pricing-table id="' . esc_attr($tableId) . '"]'); ?>
				</div>
				<?php
				wp_die();
			}
		}
	}
}

WRCPT_Preview::get_instances();
