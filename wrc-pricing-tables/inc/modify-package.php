<?php
/**
 * Editing Pricing Table Packages
 * 
 * @package WRC Pricing Tables v2.5 - 28 May, 2025
 * @link https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function wrcpt_edit_pricing_packages() {
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
		// Display an error message or handle the case where permissions and nonce check failed
		wp_die(esc_html__('You do not have sufficient permissions to access this page, or the nonce verification failed.', 'wrc-pricing-tables'));
	} else {
		$fv = 1; $ac = 1; $cf = 1;
		$f_value = '';
		$f_tips = '';
		$pricing_table = isset($_POST['packtable']) ? sanitize_text_field(wp_unslash($_POST['packtable'])) : '';
		$pricing_table_name = ucwords(str_replace('_', ' ', $pricing_table));
		$package_feature = get_option($pricing_table.'_feature');
		$packageCombine = get_option($pricing_table.'_option');
		$package_lists = get_option($pricing_table);
		$packageOptions = explode(', ', $package_lists);
		$packageItem = get_option($packageOptions[0]);
		$featureNum = count($package_feature)/2;
		$template = isset($packageCombine['templ']) ? sanitize_text_field($packageCombine['templ']) : 'temp0';
		$checkValue = uniqid('yes'); ?>
		<input type="hidden" name="wrcpt_process" value="process" />
		<div id="tablecolumndiv">
			<div class="tablecolumnwrap">
				<h3><?php esc_html_e('Pricing Table Columns', 'wrc-pricing-tables'); ?></h3>
				<div id="addButtons"><a href="#" class="button button-large" id="addPackage"><?php esc_html_e('New Column', 'wrc-pricing-tables'); ?></a></div>
				<div class="accordion-expand-holder">
					<button type="button" class="expand"><?php esc_html_e('Expand all', 'wrc-pricing-tables'); ?></button>
					<button type="button" class="collapse"><?php esc_html_e('Collapse all', 'wrc-pricing-tables'); ?></button>
				</div>
				<div id="sortable_column">
				<?php
				if(!empty($package_lists)) {
					foreach($packageOptions as $option => $value) {
					$packageValue = get_option($value);
					if(isset($packageValue['pdisp'])) { $showpack = $packageValue['pdisp']; }
					else { $showpack = 'show'; } ?>
					<div id="wrcpt-<?php echo esc_attr($ac); ?>" class="package_details">
						<h4 id="pcolumn<?php echo esc_attr($ac); ?>"><?php esc_html_e('Pricing Column', 'wrc-pricing-tables'); ?> <?php echo esc_attr($ac); ?></h4>
						<?php if($packageValue['pdisp'] == 'show') { ?><span id="hidePack<?php echo esc_attr($ac); ?>" class="column_hide"><span class="dashicons dashicons-fullscreen-alt"></span><?php } else { ?><span id="showPack<?php echo esc_attr($ac); ?>" class="column_show"><span class="dashicons dashicons-fullscreen-exit-alt"></span><?php } ?><input type="hidden" name="hide_show[]" value="<?php echo esc_attr($packageValue['pdisp']); ?>" /></span>
						<span id="delPackage"><span class="dashicons dashicons-trash"></span></span>
						<div id="accordion<?php echo esc_attr($ac); ?>" class="column_container">
							<h3 class="ptitle"><?php esc_html_e('Pricing Column Details', 'wrc-pricing-tables'); ?></h3>
							<div class="element-input">
								<label class="input-check"><?php esc_html_e('Enlarge Column?', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('If you want to highlight the current column from some of the other columns, simply mark the checkbox.', 'wrc-pricing-tables'); ?>"></a></label>
								<input type="checkbox" class="tickbox" name="special_package[<?php echo esc_attr($ac-1); ?>]" id="special_package" value="yes"<?php if($packageValue['spack'] == 'yes') {?> checked="checked"<?php } ?> /><hr />
								<h4><?php esc_html_e('Package Name', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter your pricing package name here. You can also enter a short description of the package name. There are many users who choose a package based on the name, instead of features. So, a short description might help users to select the appropriate package.', 'wrc-pricing-tables'); ?>"></a></h4>
								<label class="input-title"><?php esc_html_e('Package Name', 'wrc-pricing-tables'); ?></label>
								<input type="text" name="package_type[]" class="medium" id="package_type" value="<?php echo esc_attr($packageValue['type']); ?>" />
								<textarea name="package_desc[]" class="medium" id="package_desc" cols="27" rows="2" placeholder="<?php esc_html_e('Enter Short Description', 'wrc-pricing-tables'); ?>"><?php echo esc_attr($packageValue['tdesc']); ?></textarea><hr />
								<h4><?php esc_html_e('Package Pricing', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter the price of the package, price currency, price plan (monthly or yearly) and a short description about pricing.', 'wrc-pricing-tables'); ?>"></a></h4>
								<label class="input-title"><?php esc_html_e('Package Price', 'wrc-pricing-tables'); ?></label>
								<input name="price_number[]" type="text" class="col_price" value="<?php echo esc_attr($packageValue['price']); ?>" />&nbsp;.&nbsp;<input name="price_fraction[]" type="number" class="col_price" value="<?php echo esc_attr($packageValue['cent']); ?>" min="0" max="99" placeholder="00" />
								<label class="input-title"><?php esc_html_e('Price Plan', 'wrc-pricing-tables'); ?></label>
								<input name="package_plan[]" id="package_plan" type="text" class="medium" value="<?php echo esc_attr($packageValue['plan']); ?>" />
								<label class="input-title"><?php esc_html_e('Price Unit', 'wrc-pricing-tables'); ?></label>
								<input name="price_unit[]" id="price_unit" type="text" class="medium" value="<?php echo esc_attr($packageValue['unit']); ?>" />
								<textarea name="price_desc[]" class="medium" id="price_desc" cols="27" rows="2" placeholder="<?php esc_html_e('Enter Short Description', 'wrc-pricing-tables'); ?>"><?php echo esc_attr($packageValue['pdesc']); ?></textarea><hr />
								<h4><?php esc_html_e('Package Features', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter feature values and feature ToolTips here. To show the tick icon mark the checkbox and to show cross icon unmark the checkbox. To display ToolTips, you should enable ToolTips in General settings under Pricing Table Settings.', 'wrc-pricing-tables'); ?>"></a></h4><?php
								if($package_feature) {
									for($i = 1; $i <= $featureNum; $i++) {
										if(isset($packageValue['fitem'.$fv])) {
											$f_value = $packageValue['fitem'.$fv];
											$f_tips = $packageValue['tip'.$fv];
										}
										if($package_feature['ftype'.$i] == 'text') { ?>
											<label class="input-title"><?php echo esc_html($package_feature['fitem'.$i]); ?></label><input type="text" class="medium" name="feature_value[]" id="feature_value" value="<?php echo esc_attr($f_value); ?>" placeholder="<?php esc_html_e('Feature Value', 'wrc-pricing-tables'); ?>" /><textarea name="tooltips[]" id="tooltips" class="medium" cols="27" rows="2" placeholder="<?php esc_html_e('Enter Tooltip', 'wrc-pricing-tables'); ?>"><?php echo esc_attr($f_tips); ?></textarea><hr /><?php
										} else { ?>
											<label class="input-check"><?php echo esc_html($package_feature['fitem'.$i]); ?></label><input type="checkbox" class="tickbox" name="feature_value[<?php echo esc_attr('ftype'.$cf); ?>]" id="feature_value" value="<?php echo esc_attr($checkValue); ?>"<?php if($f_value == 'tick' || $f_value == 'yes') {?> checked="checked"<?php } ?> /><textarea name="tooltips[]" id="tooltips" class="medium" cols="27" rows="2" placeholder="<?php esc_html_e('Enter Tooltip', 'wrc-pricing-tables'); ?>"><?php echo esc_attr($f_tips); ?></textarea><hr /><?php
											$cf++;
										} $fv++;
									}
								} else { echo '<label class="input-title">' . esc_html_e('There are no feature items!', 'wrc-pricing-tables') . '</label>'; } ?>
								<h4><?php esc_html_e('Package Button', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter your call to action text and call to action URL here. The URL is usually either a payment link or a page where users can create an account.', 'wrc-pricing-tables'); ?>"></a></h4>
								<div id="normal_button">
									<label class="input-title"><?php esc_html_e('Button Text', 'wrc-pricing-tables'); ?></label>
									<input type="text" name="button_text[]" class="medium" id="button_text" value="<?php echo esc_attr($packageValue['btext']); ?>" placeholder="<?php esc_html_e('e.g. Buy Now!', 'wrc-pricing-tables'); ?>" />
									<label class="input-title"><?php esc_html_e('Button Link', 'wrc-pricing-tables'); ?></label>
									<input type="text" name="button_link[]" class="medium" id="button_link" value="<?php echo esc_attr($packageValue['blink']); ?>" placeholder="<?php esc_html_e('e.g. http://example.com', 'wrc-pricing-tables'); ?>" />
								</div>
								<hr />
								<h4><?php esc_html_e('Package Ribbon', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter ribbon text to make current package more attractive to users, like \'best\', \'new\', \'hot\' etc.', 'wrc-pricing-tables'); ?>"></a></h4>
								<label class="input-title"><?php esc_html_e('Ribbon Text', 'wrc-pricing-tables'); ?></label>
								<input type="text" name="ribbon_text[]" class="medium" id="ribbon_text" value="<?php echo esc_attr($packageValue['rtext']); ?>" placeholder="<?php esc_html_e('e.g. Best', 'wrc-pricing-tables'); ?>" />
							</div>
							<h3 class="ptitle"><?php esc_html_e('Pricing Column Colors', 'wrc-pricing-tables'); ?></h3>
							<div class="element-input">
								<table>
									<!--Background Color -->
									<tr class="table-header">
										<td colspan="2"><?php esc_html_e('Background Colors', 'wrc-pricing-tables'); ?></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Table Background Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="title_bg[]" class="title_bg" id="title_bg" value="<?php echo esc_attr($packageValue['tbcolor']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Feature Background Color 1', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="feat_row1_color[]" class="feat_row1_color" id="feat_row1_color" value="<?php echo esc_attr($packageValue['fbrow1']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Feature Background Color 2', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="feat_row2_color[]" class="feat_row2_color" id="feat_row2_color" value="<?php echo esc_attr($packageValue['fbrow2']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Ribbon Background Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="ribbon_bg[]" class="ribbon_bg" id="ribbon_bg" value="<?php echo esc_attr($packageValue['rbcolor']); ?>" /></td>
									</tr>
									<!--Font Color -->
									<tr class="table-header">
										<td colspan="2"><?php esc_html_e('Font Colors', 'wrc-pricing-tables'); ?></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Title Font Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="title_color[]" class="title_color" id="title_color" value="<?php echo esc_attr($packageValue['tcolor']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Price Font Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="price_color_big[]" class="price_color_big" id="price_color_big" value="<?php echo esc_attr($packageValue['pcbig']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Feature Font Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="feature_text_color[]" class="feature_text_color" id="feature_text_color" value="<?php echo esc_attr($packageValue['ftcolor']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Ribbon Font Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="ribbon_text_color[]" class="ribbon_text_color" id="ribbon_text_color" value="<?php echo esc_attr($packageValue['rtcolor']); ?>" /></td>
									</tr>
									<!--Button Color -->
									<tr class="table-header">
										<td colspan="2"><?php esc_html_e('Button Colors', 'wrc-pricing-tables'); ?></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Button Font Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="button_text_color[]" class="button_text_color" id="button_text_color" value="<?php echo esc_attr($packageValue['btcolor']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Button Font Hover Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="button_text_hover[]" class="button_text_hover" id="button_text_hover" value="<?php echo esc_attr($packageValue['bthover']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Button Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="button_color[]" class="button_color" id="button_color" value="<?php echo esc_attr($packageValue['bcolor']); ?>" /></td>
									</tr>
									<tr class="table-input">
										<th><label class="input-title"><?php esc_html_e('Button Hover Color', 'wrc-pricing-tables'); ?></label></th>
										<td><input type="text" name="button_hover[]" class="button_hover" id="button_hover" value="<?php echo esc_attr($packageValue['bhover']); ?>" /></td>
									</tr>
								</table>
							</div>
							<input type="hidden" name="pricing_packages[]" value="<?php echo esc_attr($value); ?>" />
							<input type="hidden" name="package_id[]" value="<?php echo esc_attr($packageValue['pid']); ?>" />
							<input type="hidden" name="order_id[]" value="<?php echo esc_attr($packageValue['order']); ?>" />
						</div>	<!-- End of column_container -->
					</div>	<!-- End of package_details -->
				<?php
						$fv = 1; $ac++;
					}
				} else esc_html_e('No Packages yet!', 'wrc-pricing-tables'); ?>
				</div>	<!--sortable_column -->
			</div>	<!--tablecolumnwrap -->
		</div>	<!--tablecolumndiv -->
		<div class="wrcpt-clear"></div>
		<div id="settingcolumndiv">
			<div class="settingcolumnwrap">
				<h3><?php esc_html_e('Pricing Table Settings', 'wrc-pricing-tables'); ?></h3>
				<div id="accordion_advance" class="package_advance">
					<h3 class="ptitle"><?php esc_html_e('General Settings', 'wrc-pricing-tables'); ?></h3>
					<div class="advance-input">
						<label class="input-check"><?php esc_html_e('Enable Pricing Table', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="wrcpt_option" class="tickbox" id="wrcpt_option" value="yes" <?php if($packageCombine['enable'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
						<label id="modify-table" class="input-title"><?php esc_html_e('Modify Pricing Table Name', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="pricing_table_name" class="medium" id="pricing_table_name" value="<?php echo esc_attr($pricing_table_name); ?>" />
						<div class="break-line"></div>
						<label class="input-check"><?php esc_html_e('Enable Column Shadow', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="column_shadow" class="tickbox" id="column_shadow" value="yes" <?php if($packageCombine['colshad'] == 'yes') { ?> checked="checked" <?php } ?> /></label>
						<label class="input-check"><?php esc_html_e('Disable Shadow on Highlight', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="disable_shadow" class="tickbox" id="disable_shadow" value="yes" <?php if($packageCombine['dscol'] == 'yes') { ?> checked="checked" <?php } ?> /></label>
						<label class="input-check"><?php esc_html_e('Disable Title Gradient', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="title_gradient" class="tickbox" id="title_gradient" value="yes" <?php if($packageCombine['ttgrd'] == 'yes') { ?> checked="checked"<?php } ?>></label>
						<label class="input-check"><?php esc_html_e('Align Price Unit at Right', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="unit_right" class="tickbox" id="unit_right" value="yes" <?php if($packageCombine['purgt'] == 'yes') { ?> checked="checked" <?php } ?> /></label>
						<label class="input-check"><?php esc_html_e('Open Link in New Tab', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="new_tab" class="tickbox" id="new_tab" value="yes" <?php if($packageCombine['nltab'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
						<label class="input-check"><?php esc_html_e('Enable Feature Tooltips', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="enable_tooltip" class="tickbox" id="enable_tooltip" value="yes" <?php if($packageCombine['entips'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
						<div class="break-line"></div>
						<label class="input-check"><?php esc_html_e('Enable Package Ribbons', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="enable_ribbon" class="tickbox" id="enable_ribbon" value="yes" <?php if($packageCombine['enribs'] == 'yes') { ?> checked="checked" <?php } ?> /></label>
						<div class="break-line"></div>
						<h4><?php esc_html_e('Tick/Cross Icons', 'wrc-pricing-tables'); ?></h4>
						<div id="tickcross-items">
							<label class="input-radio" id="tick-choice"><?php esc_html_e('Choose Tick Icon to Show', 'wrc-pricing-tables'); ?>:</label>
							<div class="tickicon_choice">
								<label for="tickicon1"><input type="radio" name="tick_mark" value="tick-1" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-1') { ?> checked="checked"  <?php } ?> /><span id="tickicon1" class="tick-icons"></span></label>
								<label for="tickicon2"><input type="radio" name="tick_mark" value="tick-2" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-2') { ?> checked="checked"  <?php } ?> /><span id="tickicon2" class="tick-icons"></span></label>
								<label for="tickicon3"><input type="radio" name="tick_mark" value="tick-3" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-3') { ?> checked="checked"  <?php } ?> /><span id="tickicon3" class="tick-icons"></span></label>
								<label for="tickicon4"><input type="radio" name="tick_mark" value="tick-4" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-4') { ?> checked="checked"  <?php } ?> /><span id="tickicon4" class="tick-icons"></span></label>
								<label for="tickicon5"><input type="radio" name="tick_mark" value="tick-5" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-5') { ?> checked="checked"  <?php } ?> /><span id="tickicon5" class="tick-icons"></span></label>
							</div>
							<div class="tickicon_choice">
								<label for="tickicon6"><input type="radio" name="tick_mark" value="tick-6" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-6') { ?> checked="checked"  <?php } ?> /><span id="tickicon6" class="tick-icons"></span></label>
								<label for="tickicon7"><input type="radio" name="tick_mark" value="tick-7" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-7') { ?> checked="checked"  <?php } ?> /><span id="tickicon7" class="tick-icons"></span></label>
								<label for="tickicon8"><input type="radio" name="tick_mark" value="tick-8" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-8') { ?> checked="checked"  <?php } ?> /><span id="tickicon8" class="tick-icons"></span></label>
								<label for="tickicon9"><input type="radio" name="tick_mark" value="tick-9" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-9') { ?> checked="checked"  <?php } ?> /><span id="tickicon9" class="tick-icons"></span></label>
								<label for="tickicon10"><input type="radio" name="tick_mark" value="tick-10" class="hide_tick" <?php if($packageCombine['tick'] == 'tick-10') { ?> checked="checked"  <?php } ?> /><span id="tickicon10" class="tick-icons"></span></label>
							</div>
							<label class="input-radio" id="cross-choice"><?php esc_html_e('Choose Cross Icon to Show', 'wrc-pricing-tables'); ?>:</label>
							<div class="tickicon_choice">
								<label for="crossicon1"><input type="radio" name="cross_mark" value="cross-1" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-1') { ?> checked="checked"  <?php } ?> /><span id="crossicon1" class="cross-icons"></span></label>
								<label for="crossicon2"><input type="radio" name="cross_mark" value="cross-2" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-2') { ?> checked="checked"  <?php } ?> /><span id="crossicon2" class="cross-icons"></span></label>
								<label for="crossicon3"><input type="radio" name="cross_mark" value="cross-3" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-3') { ?> checked="checked"  <?php } ?> /><span id="crossicon3" class="cross-icons"></span></label>
								<label for="crossicon4"><input type="radio" name="cross_mark" value="cross-4" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-4') { ?> checked="checked"  <?php } ?> /><span id="crossicon4" class="cross-icons"></span></label>
								<label for="crossicon5"><input type="radio" name="cross_mark" value="cross-5" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-5') { ?> checked="checked"  <?php } ?> /><span id="crossicon5" class="cross-icons"></span></label>
							</div>
							<div class="tickicon_choice">
								<label for="crossicon6"><input type="radio" name="cross_mark" value="cross-6" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-6') { ?> checked="checked"<?php } ?> /><span id="crossicon6" class="cross-icons"></span></label>
								<label for="crossicon7"><input type="radio" name="cross_mark" value="cross-7" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-7') { ?> checked="checked"<?php } ?> /><span id="crossicon7" class="cross-icons"></span></label>
								<label for="crossicon8"><input type="radio" name="cross_mark" value="cross-8" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-8') { ?> checked="checked"<?php } ?> /><span id="crossicon8" class="cross-icons"></span></label>
								<label for="crossicon9"><input type="radio" name="cross_mark" value="cross-9" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-9') { ?> checked="checked"<?php } ?> /><span id="crossicon9" class="cross-icons"></span></label>
								<label for="crossicon10"><input type="radio" name="cross_mark" value="cross-10" class="hide_cross" <?php if($packageCombine['cross'] == 'cross-10') { ?> checked="checked"<?php } ?> /><span id="crossicon10" class="cross-icons"></span></label>
							</div>
						</div>
					</div>
					<h3 class="ptitle"><?php esc_html_e('Structural Settings', 'wrc-pricing-tables'); ?></h3>
					<div class="advance-input">
						<label class="input-title"><?php esc_html_e('Pricing Table Container Width', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('Enter the total width of your pricing table here.', 'wrc-pricing-tables'); ?>"></a></label>
						<input type="text" name="container_width" class="medium" id="container_width" value="<?php echo esc_attr($packageCombine['cwidth']); ?>" placeholder="e.g. 100%" />
						<label class="input-title"><?php esc_html_e('Number of Columns per Row', 'wrc-pricing-tables'); ?><a href="#" class="wrc_tooltip" rel="<?php esc_html_e('If your pricing table has a lot of columns, then you can split the columns according to the rows by entering the number of columns in the right TextBox.', 'wrc-pricing-tables'); ?>"></a></label>
						<input type="number" name="max_column" class="medium" id="max_column" value="<?php echo esc_attr($packageCombine['maxcol']); ?>" min="0" max="12" placeholder="e.g. 6" />
						<label class="input-title"><?php esc_html_e('Title Body Height', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="title_body" class="medium" id="title_body" value="<?php echo esc_attr($packageCombine['tbody']); ?>" placeholder="e.g. 48px" />
						<label class="input-title"><?php esc_html_e('Price Body Height', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="price_body" class="medium" id="price_body" value="<?php echo esc_attr($packageCombine['pbody']); ?>" placeholder="e.g. 120px" />
						<label class="input-title"><?php esc_html_e('Feature Label Body Height', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="feature_body" class="medium" id="feature_body" value="<?php echo esc_attr($packageCombine['ftbody']); ?>" placeholder="e.g. 42px" />
						<label class="input-title"><?php esc_html_e('Button Body Height', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="button_body" class="medium" id="button_body" value="<?php echo esc_attr($packageCombine['btbody']); ?>" placeholder="e.g. 40px" />
						<label class="input-title"><?php esc_html_e('Button Width', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="button_width" class="medium" id="button_width" value="<?php echo esc_attr($packageCombine['bwidth']); ?>" placeholder="e.g. 140px" />
						<label class="input-title"><?php esc_html_e('Button Height', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="button_height" class="medium" id="button_height" value="<?php echo esc_attr($packageCombine['bheight']); ?>" placeholder="e.g. 30px" />
						<label class="input-title"><?php esc_html_e('Tooltip Width', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="tooltip_width" class="medium" id="tooltip_width" value="<?php echo esc_attr($packageCombine['ttwidth']); ?>" placeholder="e.g. 130px" /><hr />
						<label class="input-title"><?php esc_html_e('Feature Label Font Direction', 'wrc-pricing-tables'); ?>:</label>
						<select name="feature_align" id="feature_align" class="font-dir">
							<?php if($packageCombine['ftdir'] == 'left') { ?>
							<option value="left" selected="selected"><?php esc_html_e('Left', 'wrc-pricing-tables'); ?></option>
							<option value="right"><?php esc_html_e('Right', 'wrc-pricing-tables'); ?></option>
							<option value="center"><?php esc_html_e('Center', 'wrc-pricing-tables'); ?></option>
							<?php } elseif($packageCombine['ftdir'] == 'right') { ?>
							<option value="left"><?php esc_html_e('Left', 'wrc-pricing-tables'); ?></option>
							<option value="right" selected="selected"><?php esc_html_e('Right', 'wrc-pricing-tables'); ?></option>
							<option value="center"><?php esc_html_e('Center', 'wrc-pricing-tables'); ?></option>
							<?php } else { ?>
							<option value="left"><?php esc_html_e('Left', 'wrc-pricing-tables'); ?></option>
							<option value="right"><?php esc_html_e('Right', 'wrc-pricing-tables'); ?></option>
							<option value="center" selected="selected"><?php esc_html_e('Center', 'wrc-pricing-tables'); ?></option>
							<?php } ?>
						</select>
						<hr />
						<label class="input-check"><?php esc_html_e('Disable Auto Column Width', 'wrc-pricing-tables'); ?>:<a href="#" class="wrc_tooltip" rel="<?php esc_html_e('If you want to setup caption column width manually simply mark the check box. It will allow you to enter the caption column width and the space between the columns.', 'wrc-pricing-tables'); ?>"></a>
						<input type="checkbox" name="auto_column" class="tickbox" id="auto_column" value="yes" <?php if($packageCombine['autocol'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
						<label class="input-title" id="margin_right"><?php esc_html_e('Space Between Columns', 'wrc-pricing-tables'); ?></label>
						<input type="number" step="any" name="column_space" class="medium" id="column_space" value="<?php echo esc_attr($packageCombine['colgap']); ?>" placeholder="e.g. 1" />
						<label class="input-check"><?php esc_html_e('Enable Caption Column', 'wrc-pricing-tables'); ?>:<a href="#" class="wrc_tooltip" rel="<?php esc_html_e('If you would like to display features name column separately on the left of the pricing table simply mark this checkbox.', 'wrc-pricing-tables'); ?>"></a>
						<input type="checkbox" name="feature_caption" class="tickbox" id="feature_caption" value="yes" <?php if($packageCombine['ftcap'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
						<label class="input-title" id="cap_col_width"><?php esc_html_e('Caption Column Width', 'wrc-pricing-tables'); ?></label>
						<input type="number" step="any" name="cap_column_width" class="medium" id="cap_column_width" value="<?php echo esc_attr($packageCombine['capwidth']); ?>" placeholder="e.g. 18 or 18.73" />
						<label class="input-check"><?php esc_html_e('Enlarge Column on Hover', 'wrc-pricing-tables'); ?>:
						<input type="checkbox" name="enlarge_column" class="tickbox" id="enlarge_column" value="yes" <?php if($packageCombine['encol'] == 'yes') { ?> checked="checked"  <?php } ?> /></label>
					</div>
					<h3 class="ptitle"><?php esc_html_e('Font Settings', 'wrc-pricing-tables'); ?></h3>
					<div class="advance-input">
						<label class="input-title"><?php esc_html_e('Pricing Table Name Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="caption_size" class="medium" id="caption_size" value="<?php echo esc_attr($packageCombine['ctsize']); ?>" placeholder="e.g. 36px" />
						<label class="input-title"><?php esc_html_e('Title Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="title_size" class="medium" id="title_size" value="<?php echo esc_attr($packageCombine['tsize']); ?>" placeholder="e.g. 24px" />
						<label class="input-title"><?php esc_html_e('Price Font Size (Big)', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="price_size_big" class="medium" id="price_size_big" value="<?php echo esc_attr($packageCombine['psbig']); ?>" placeholder="e.g. 60px" />
						<label class="input-title"><?php esc_html_e('Price Font Size (Small)', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="price_size_small" class="medium" id="price_size_small" value="<?php echo esc_attr($packageCombine['pssmall']); ?>" placeholder="e.g. 24px" />
						<label class="input-title"><?php esc_html_e('Feature Name Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="cap_feat_size" class="medium" id="cap_feat_size" value="<?php echo esc_attr($packageCombine['cftsize']); ?>" placeholder="e.g. 12px" />
						<label class="input-title"><?php esc_html_e('Feature Values Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="feature_text_size" class="medium" id="feature_text_size" value="<?php echo esc_attr($packageCombine['ftsize']); ?>" placeholder="e.g. 12px" />
						<label class="input-title"><?php esc_html_e('Button Text Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="button_text_size" class="medium" id="button_text_size" value="<?php echo esc_attr($packageCombine['btsize']); ?>" placeholder="e.g. 12px" />
						<label class="input-title"><?php esc_html_e('Ribbon Text Font Size', 'wrc-pricing-tables'); ?></label>
						<input type="text" name="ribbon_text_size" class="medium" id="ribbon_text_size" value="<?php echo esc_attr($packageCombine['rtsize']); ?>" placeholder="e.g. 12px" />
					</div>
					<h3 class="ptitle"><?php esc_html_e('Advanced Color Settings', 'wrc-pricing-tables'); ?></h3>
					<div class="advance-input">
						<table>
							<!--Shadow Color -->
							<tr class="table-header">
								<td colspan="2"><?php esc_html_e('Column Shadow Colors', 'wrc-pricing-tables'); ?></td>
							</tr>
							<tr class="table-input">
								<th><label class="input-title"><?php esc_html_e('Column Shadow Color', 'wrc-pricing-tables'); ?></label></th>
								<td><input type="text" name="col_shad_color" class="col_shad_color" id="col_shad_color" value="<?php echo esc_attr($packageCombine['cscolor']); ?>" /></td>
							</tr>
							<tr class="table-input">
								<th><label class="input-title"><?php esc_html_e('Column Shadow Highlight Color', 'wrc-pricing-tables'); ?></label></th>
								<td><input type="text" name="col_shad_hover_color" class="col_shad_hover_color" id="col_shad_hover_color" value="<?php echo esc_attr($packageCombine['cshcolor']); ?>" /></td>
							</tr>
						</table>
					</div>
				</div>	<!--package_advance -->
			</div>	<!--settingcolumnwrap -->
		</div>	<!--settingcolumndiv -->
		<div class="wrcpt-clear"></div>
		<input type="hidden" id="submitted" name="submitted" value="<?php if(isset($packageCombine['subform']) && $packageCombine['subform']) { echo esc_attr($packageCombine['subform']); } else { echo 'no'; } ?>" />
		<input type="hidden" name="pricing_table" value="<?php echo esc_attr($pricing_table); ?>" />
		<input type="hidden" name="checkbox_value" value="<?php echo esc_attr($checkValue); ?>" />
		<input type="hidden" name="template" value="<?php echo esc_attr($template); ?>" />
		<input type="hidden" name="action" value="wrcpt_update_pricing_package">
		<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
		<button type="submit" id="wrcpt_edit" class="button-primary"><?php esc_html_e('Update Package', 'wrc-pricing-tables'); ?></button><?php
	}
	wp_die();
}
add_action( 'wp_ajax_nopriv_wrcpt_edit_pricing_packages', 'wrcpt_edit_pricing_packages' );
add_action( 'wp_ajax_wrcpt_edit_pricing_packages', 'wrcpt_edit_pricing_packages' );
