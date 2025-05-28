<?php
/**
 * Processing package features for Pricing Table
 * 
 * @package WRC Pricing Tables v2.5 - 28 May, 2025
 * @link https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function wrcpt_process_package_features() {
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
    }

	$i = 1; $fp = 1; $package_feature = array();
	$pricing_table = isset($_POST['packtable']) ? sanitize_text_field(wp_unslash($_POST['packtable'])) : '';

	$package_feature = get_option($pricing_table.'_feature', 'default_value');
	if ( $package_feature !== 'default_value' ) {
		$featureNum = count($package_feature)/2;
	} else {
		$featureNum = 0;
	}

	if($featureNum == 1 && $featureNum > 0) {
		$rmv_id = 'remDisable';
	} else {
		$rmv_id = 'remFeature';
	}

	$package_lists = get_option($pricing_table);
	$packageOptions = explode(', ', $package_lists); ?>

	<input type="hidden" name="process_feature" value="feature" />
	<div id="tablenamediv">
	<?php if ( $package_feature !== 'default_value' ) { ?>
		<div id="pricingfeaturediv">
			<div class="pricingfeaturewrap">
				<h3><?php esc_html_e('Pricing Package Features', 'wrc-pricing-tables'); ?></h3>
				<table id="feature_edititem" cellspacing="0">
					<thead>
						<tr class="featheader">
							<th><?php esc_html_e('Features ', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter your pricing table features in the text box. A feature is a distinctive characteristic of a good or service that sets it apart from similar items. Means of providing benefits to customers.', 'wrc-pricing-tables'); ?>"></a></th>
							<th><?php esc_html_e('Type', 'wrc-pricing-tables'); ?></th>
							<th><?php esc_html_e('Actions', 'wrc-pricing-tables'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i = 1; $i <= $featureNum; $i++) { ?>
						<tr class="featurebody">
							<td>
								<input type="text" name="feature_name[<?php echo esc_attr('fitem'.$i); ?>]" value="<?php echo esc_attr($package_feature['fitem'.$i]); ?>" placeholder="<?php esc_html_e('Enter Feature Name', 'wrc-pricing-tables'); ?>" size="20" required /><?php
								foreach($packageOptions as $option => $value) {
									$packageItem = get_option($value); ?>
									<input type="hidden" name="feature_value[]" value="<?php echo esc_attr($packageItem['fitem'.$fp]); ?>" />
									<input type="hidden" name="tooltips[]" value="<?php echo esc_attr($packageItem['tip'.$fp]); ?>" /><?php
								} $fp++; ?>
							</td>
                            <td>
								<select name="feature_type[]" id="feature_type">
									<?php if($package_feature['ftype'.$i] == 'text') { ?>
									<option value="text" selected="selected"><?php esc_html_e('Text', 'wrc-pricing-tables'); ?></option>
									<option value="check"><?php esc_html_e('Checkbox', 'wrc-pricing-tables'); ?></option>
									<?php } elseif($package_feature['ftype'.$i] == 'check') { ?>
									<option value="text"><?php esc_html_e('Text', 'wrc-pricing-tables'); ?></option>
									<option value="check" selected="selected"><?php esc_html_e('Checkbox', 'wrc-pricing-tables'); ?></option>
									<?php } else { ?>
									<option value="text" selected="selected"><?php esc_html_e('Text', 'wrc-pricing-tables'); ?></option>
									<option value="check"><?php esc_html_e('Checkbox', 'wrc-pricing-tables'); ?></option>
									<?php } ?>
								</select>
							</td>
							<td><span id="<?php echo esc_attr($rmv_id); ?>"></span></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<input type="button" id="editfeature" class="button-primary" value="<?php esc_html_e('Add New', 'wrc-pricing-tables'); ?>" />
			</div>
		</div>
		<input type="hidden" name="pricing_table" value="<?php echo esc_attr($pricing_table); ?>" />
		<input type="hidden" name="package_feature" value="<?php echo esc_attr($pricing_table.'_feature'); ?>" />
		<input type="hidden" name="action" value="wrcpt_update_package_features">
		<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
  		<button type="submit" id="wrcpt_upfeature" class="button-primary"><?php esc_html_e('Update Feature', 'wrc-pricing-tables'); ?></button>
	<?php } else { ?>
		<div id="pricingfeaturediv">
			<div class="pricingfeaturewrap">
				<h3><?php esc_html_e('Pricing Package Features', 'wrc-pricing-tables'); ?></h3>
				<table id="feature_edititem" cellspacing="0">
					<tr class="featheader">
						<th><?php esc_html_e('Features', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter your pricing table features in the text box. A feature is a distinctive characteristic of a good or service that sets it apart from similar items. Means of providing benefits to customers.', 'wrc-pricing-tables'); ?>"></a></th>
						<th><?php esc_html_e('Type', 'wrc-pricing-tables'); ?></th>
						<th><?php esc_html_e('Actions', 'wrc-pricing-tables'); ?></th>
					</tr>
					<tr class="featurebody">
						<td><input type="text" name="feature_name[]" value="" placeholder="<?php esc_html_e('Enter Feature Name', 'wrc-pricing-tables'); ?>" size="20" required /></td>
						<td>
							<select name="feature_type[]" id="feature_type">
								<option value="text" selected="selected"><?php esc_html_e('Text', 'wrc-pricing-tables'); ?></option>
								<option value="check"><?php esc_html_e('Checkbox', 'wrc-pricing-tables'); ?></option>
							</select>
						</td>
						<td><span id="remFeature"></span></td>
					</tr>
				</table>
				<input type="button" id="editfeature" class="button-primary" value="<?php esc_html_e('Add New', 'wrc-pricing-tables'); ?>" />
			</div>
		</div>
		<input type="hidden" name="package_feature" value="<?php echo esc_attr($pricing_table.'_feature'); ?>" />
		<input type="hidden" name="action" value="wrcpt_add_package_features">
  		<button type="submit" id="wrcpt_addfeature" class="button-primary"><?php esc_html_e('Add Feature', 'wrc-pricing-tables'); ?></button>
	<?php } ?>
	</div>
	<div class="wrcpt-clear"></div>
	<div class="table_list">
		<p class="feature_notice"><?php esc_html_e('Reorder features by dragging with the mouse', 'wrc-pricing-tables'); ?></p>
	</div>
<?php
	wp_die();
}
add_action( 'wp_ajax_nopriv_wrcpt_process_package_features', 'wrcpt_process_package_features' );
add_action( 'wp_ajax_wrcpt_process_package_features', 'wrcpt_process_package_features' );
