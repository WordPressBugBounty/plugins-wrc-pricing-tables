<?php
/*
 * WRC Pricing Tables 2.4.3 - 1-August-2024
 * @realwebcare - https://www.realwebcare.com/
 * Generating preview of the pricing table in WP admin panel
 * to get an idea about how the table will look at front-end
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function wrcpt_view_pricing_packages() {
    // Check if the user has the necessary capability (e.g., manage_options)
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
    }
	// Get the nonce from the AJAX request data
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

	// Verify the nonce
	if (!wp_verify_nonce($nonce, 'wrcpt_ajax_action_nonce')) {
		// Nonce verification failed, handle the error
		wp_send_json_error(array('message' => 'Nonce verification failed'));
		wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
	} else {
		$i = 1;
		$pricing_table = isset($_POST['packtable']) ? sanitize_text_field($_POST['packtable']) : '';
		$tableId = isset($_POST['tableid']) ? sanitize_text_field($_POST['tableid']) : '';
		$package_lists = get_option($pricing_table);
		$packageOptions = explode(', ', $package_lists);
		$packageCount = count($packageOptions); ?>
		<style type="text/css">
			*,*:after,*:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:0;margin:0}
		</style>
		<div id="tabledisplaydiv">
			<h3><span id="editPackages" class="button button-large" onclick="wrcpteditpackages(<?php echo $packageCount; ?>, '<?php echo esc_attr($pricing_table); ?>')"><?php esc_html_e('Edit Columns', 'wrc-pricing-tables'); ?></span></h3>
			<?php echo do_shortcode('[wrc-pricing-table id="'.esc_attr($tableId).'"]'); ?>
		</div><?php
		wp_die();
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_view_pricing_packages', 'wrcpt_view_pricing_packages' );
add_action( 'wp_ajax_wrcpt_view_pricing_packages', 'wrcpt_view_pricing_packages' );
?>