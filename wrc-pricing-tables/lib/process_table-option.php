<?php
/*
 * WRC Pricing Tables 2.4.4 - 20 November, 2024
 * @realwebcare - https://www.realwebcare.com/
 * Pricing table functions to create and update tables
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

/**
 * Check user capabilities and nonce for security in AJAX requests.
 *
 * This function is designed to be used at the beginning of AJAX handler functions
 * to ensure that the current user has the necessary capabilities and a valid nonce.
 * If the checks fail, it terminates the script and sends a JSON error response.
 *
 * @since 1.0.0
 */
if (!function_exists('wrcpt_check_permissions_and_nonce')) {
    function wrcpt_check_permissions_and_nonce() {
        // Check if the user has the necessary capability (e.g., manage_options)
        if ( ! current_user_can( 'manage_options' ) ) {
			// If the user does not have the required capability, return false
			return false;
        }

        // Create or verify nonce
        $nonce_action = 'wrcpt_ajax_action_nonce';
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

        if (empty($nonce) || !wp_verify_nonce($nonce, $nonce_action)) {
			// If nonce is missing or verification fails, return false
            return false;
        }
		// Nonce verification passed, return true
        return true;
    }
}

if (!function_exists('wrcpt_add_package_features')) {
	function wrcpt_add_package_features() {
        // Check permissions and nonce
        if (wrcpt_check_permissions_and_nonce()) {
			$fn = 1;

			$package_feature = isset($_POST['package_feature']) ? sanitize_key($_POST['package_feature']) : '';
			$feature_type = isset($_POST['feature_type']) ? array_map('sanitize_text_field', $_POST['feature_type']) : array();

			if($package_feature) {
				if (isset($_POST['feature_name']) && is_array($_POST['feature_name'])) {
					$feature_name = array();
					foreach($_POST['feature_name'] as $key => $feature) {
						if($feature) {
							$feature_name['fitem' . $fn] = sanitize_text_field($feature);
							$feature_name['ftype' . $fn] = isset($feature_type[$key]) ? $feature_type[$key] : '';
							$fn++;
						} else {
							$feature_name['fitem'.$fn] = '';
							$feature_name['ftype'.$fn] = '';
							$fn++;
						}
					}
					add_option($package_feature, $feature_name);
				}
			}
		} else {
			// Nonce verification failed, handle the error
            wp_send_json_error(array('message' => 'Nonce verification failed'));
            // Display an error message or handle the case where permissions and nonce check failed
            wp_die(__('You do not have sufficient permissions to access this page, or the nonce verification failed.', 'wrc-pricing-tables'));
        }
		wp_die();
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_add_package_features', 'wrcpt_add_package_features' );
add_action( 'wp_ajax_wrcpt_add_package_features', 'wrcpt_add_package_features' );


if (!function_exists('wrcpt_update_package_features')) {
	function wrcpt_update_package_features() {
        // Check permissions and nonce
        if(wrcpt_check_permissions_and_nonce()) {
			$fn = 1; $count_item = 0;
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$feature_type = isset($_POST['feature_type']) ? array_map('sanitize_text_field', $_POST['feature_type']) : array();
			$package_feature = isset($_POST['package_feature']) ? sanitize_key($_POST['package_feature']) : '';

			$pricing_table = isset($_POST['pricing_table']) ? sanitize_text_field($_POST['pricing_table']) : '';

			$feature_lists = get_option($package_feature, 'default_value');

			if ( $feature_lists !== 'default_value' ) {
				$featureNum = count($feature_lists)/2;
			} else {
				$featureNum = 0;
			}

			$package_lists = get_option($pricing_table);
			$packageOptions = explode(', ', $package_lists);

			if(isset($_POST['feature_name'])) { $count_item = count($_POST['feature_name']); }

			if($count_item > 0) {
				$feature_value = isset($_POST['feature_value']) ? array_map('sanitize_text_field', $_POST['feature_value']) : array();
				$tooltips = isset($_POST['tooltips']) ? array_map('sanitize_text_field', $_POST['tooltips']) : array();

				$sn = 0; $fd = 1;
				$feature_name = array();

				foreach($_POST['feature_name'] as $key => $feature) {
					if($feature) {
						$feature_name['fitem' . $fn] = sanitize_text_field($feature);
						$feature_name['ftype' . $fn] = sanitize_text_field($feature_type[$fn - 1]);
						$fn++;
					}
					foreach($packageOptions as $item => $option) {
						$packageItem = get_option($option);
						if(array_key_exists($key, $feature_lists)) {
							$packageItem['fitem'.$fd] = isset($feature_value[$sn]) ? sanitize_text_field($feature_value[$sn]) : '';
							$packageItem['tip'.$fd] = isset($tooltips[$sn]) ? sanitize_text_field($tooltips[$sn]) : '';
							update_option($option, $packageItem);
							$sn++;
						} else {
							$packageItem['fitem' . $fd] = '';
							$packageItem['tip' . $fd] = '';
							update_option($option, $packageItem);
						}
					}
					$fd++;
				}
				update_option($package_feature, $feature_name);
			} else {
				$feature_name = array('');
				delete_option($package_feature);
			}
			foreach($packageOptions as $key => $option) {
				$packageItem = get_option($option);
				for($i = 1; $i <= $featureNum; $i++) {
					if($i > $count_item) {
						$feature_key = 'fitem'.$i;
						$tip_key = 'tip'.$i;
						unset($packageItem[$feature_key]);
						unset($packageItem[$tip_key]);
						update_option($option, $packageItem);
					}
				}
				$i = 1;
			}
		} else {
			// Nonce verification failed, handle the error
            wp_send_json_error(array('message' => 'Nonce verification failed'));
            // Display an error message or handle the case where permissions and nonce check failed
            wp_die(__('You do not have sufficient permissions to access this page, or the nonce verification failed.', 'wrc-pricing-tables'));
        }
		wp_die();
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_update_package_features', 'wrcpt_update_package_features' );
add_action( 'wp_ajax_wrcpt_update_package_features', 'wrcpt_update_package_features' );

if (!function_exists('wrcpt_edit_pricing_table')) {
	function wrcpt_edit_pricing_table($edited_table, $pricing_table) {
        // Check if the user has the necessary capability (e.g., manage_options)
        if (!current_user_can('manage_options')) {
            // If the user does not have the required capability, terminate and display an error message.
            wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
        } else {
			if($pricing_table && $pricing_table != $edited_table) {
				$package_table = get_option('packageTables');
				$table_item = explode(', ', $package_table);
				$new_package_table = array();

				foreach($table_item as $key => $value) {
					if($value === $edited_table) {
						if(in_array($pricing_table, $table_item)) {
							$pricing_table = 'another_' . sanitize_text_field($pricing_table);
							$new_package_table[$key] = $pricing_table;
						} else {
							$new_package_table[$key] = sanitize_text_field($pricing_table);
						}
					} else {
						$new_package_table[$key] = $value;
					}
				}

				$new_package_table = implode(', ', $new_package_table);
				update_option('packageTables', $new_package_table);

				$edited_table_value = get_option($edited_table);
				if($edited_table_value) {
					delete_option($edited_table);
					add_option($pricing_table, $edited_table_value);
				}

				$edited_feature_value = get_option($edited_table.'_feature');
				if($edited_feature_value) {
					delete_option($edited_table.'_feature');
					add_option($pricing_table.'_feature', $edited_feature_value);
				}

				$edited_option_value = get_option($edited_table.'_option');
				if($edited_option_value) {
					delete_option($edited_table.'_option');
					add_option($pricing_table . '_option', $edited_option_value);
				}
				return $pricing_table;
			} else {
				return $edited_table;
			}
		}
	}
}

if (!function_exists('wrcpt_delete_pricing_table')) {
	function wrcpt_delete_pricing_table() {
        // Check permissions and nonce
        if(wrcpt_check_permissions_and_nonce()) {
			// Validate and sanitize the input
			$pricing_table = isset( $_POST['packtable'] ) ? sanitize_text_field( $_POST['packtable'] ) : '';

			// Check if the selected table is not empty
			if ( ! empty( $pricing_table ) ) {
				$package_table_lists = get_option('packageTables');
				$package_id_lists = get_option('packageIDs');

				$package_lists = get_option($pricing_table);
				if(isset($package_lists)) {
					if($package_lists) {
						$table_packages = explode(', ', $package_lists);
						foreach($table_packages as $package) {
							delete_option($package);
						}
					}
					delete_option($pricing_table);
				}

				$package_feature_lists = get_option($pricing_table.'_feature');
				$package_option_lists = get_option($pricing_table.'_option');

				if(isset($package_feature_lists)) {
					delete_option($pricing_table.'_feature');
				}
				if(isset($package_option_lists)) {
					delete_option($pricing_table.'_option');
				}

				$package_table_lists = explode(', ', $package_table_lists);
				$package_id_lists = explode(', ', $package_id_lists);

				$key = array_search($pricing_table, $package_table_lists);

				if ($key !== false) {
					$package_table_diff = array_diff($package_table_lists, array($pricing_table));
					$new_package_table_lists = implode(', ', $package_table_diff);
					unset($package_id_lists[$key]);
					$new_package_id_lists = implode(', ', $package_id_lists);
					update_option('packageTables', $new_package_table_lists);
					update_option('packageIDs', $new_package_id_lists);
				} else {
					delete_option('packageTables');
					delete_option('packageCount');
					delete_option('packageIDs');
					delete_option('IDsCount');
				}
			}
			// Send response and terminate
			wp_send_json_success();
		} else {
			// Nonce verification failed, handle the error
            wp_send_json_error(array('message' => 'Nonce verification failed'));
            // Display an error message or handle the case where permissions and nonce check failed
            wp_die(__('You do not have sufficient permissions to access this page, or the nonce verification failed.', 'wrc-pricing-tables'));
        }
		wp_die();
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_delete_pricing_table', 'wrcpt_delete_pricing_table' );
add_action( 'wp_ajax_wrcpt_delete_pricing_table', 'wrcpt_delete_pricing_table' );

if (!function_exists('wrcpt_update_pricing_table')) {
	function wrcpt_update_pricing_table($pricing_table, $package_lists) {
        // Check if the user has the necessary capability (e.g., manage_options)
        if (!current_user_can('manage_options')) {
            // If the user does not have the required capability, terminate and display an error message.
            wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
        } else {
			$package_count = get_option('packageCount');	//4

			if(!isset($package_count)) {
				$package_count = 1;
				add_option('packageCount', $package_count);
			} elseif($package_count == 0) {
				$package_count = 1;
				update_option('packageCount', $package_count);
			} else {
				$package_count = $package_count + 1;
				update_option('packageCount', $package_count);	//8
			}

			$optionName = 'packageOptions' . $package_count;
			if(!isset($package_lists)) {
				$package_lists = $optionName;
				add_option($pricing_table, $package_lists);
			} elseif(empty($package_lists)){
				$package_lists = $optionName;
				update_option($pricing_table, $package_lists);
			} else {
				$package_lists = $package_lists . ', ' . $optionName;
				update_option($pricing_table, $package_lists);
			}
			return $optionName;
		}
	}
}

if (!function_exists('wrcpt_delete_pricing_packages')) {
	function wrcpt_delete_pricing_packages($pricing_table, $new_lists) {
        // Check if the user has the necessary capability (e.g., manage_options)
        if (!current_user_can('manage_options')) {
            // If the user does not have the required capability, terminate and display an error message.
            wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
        } else {
			$pricing_table = sanitize_text_field($pricing_table);

			$old_package_lists = get_option($pricing_table);
			$packageOptions = explode(', ', $old_package_lists);

			// Make sure each item in $new_lists is also sanitized.
			$new_lists = array_map('sanitize_text_field', $new_lists);

			$package_diff = array_diff($packageOptions, $new_lists);
			foreach($package_diff as $delpack) {
				delete_option($delpack);
			}
		}
	}
}

if (!function_exists('wrcpt_update_pricing_package')) {
	function wrcpt_update_pricing_package() {
        // Check permissions and nonce
        if(wrcpt_check_permissions_and_nonce()) {
			// Ensure this is a POST request
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				wp_die();
			}

			$fc = 0; $tc = 0; $temp_id = 0; $cf = 1;

			// Sanitize and validate user inputs
			$pricing_table = isset($_POST['pricing_table']) && $_POST['pricing_table'] ? sanitize_text_field($_POST['pricing_table']) : '';
			$pricing_table_name = isset($_POST['pricing_table_name']) && $_POST['pricing_table_name'] ? trim(preg_replace('/[^A-Za-z0-9-\w_]+/', '_', sanitize_text_field( $_POST['pricing_table_name'] ))) : $pricing_table;

			$pricing_table = wrcpt_edit_pricing_table($pricing_table, $pricing_table_name);

			$package_text_values = array(
				'templ' => 'template', 'cwidth' => 'container_width', 'maxcol' => 'max_column', 'colgap' => 'column_space', 'capwidth' => 'cap_column_width', 'ctsize' => 'caption_size', 'cftsize' => 'cap_feat_size', 'tbody' => 'title_body', 'tsize' => 'title_size', 'pbody' => 'price_body', 'psbig' => 'price_size_big', 'pssmall' => 'price_size_small', 'ftbody' => 'feature_body', 'ftsize' => 'feature_text_size', 'btbody' => 'button_body', 'bwidth' => 'button_width', 'bheight' => 'button_height', 'btsize' => 'button_text_size', 'rtsize' => 'ribbon_text_size', 'ttwidth' => 'tooltip_width', 'ptntext' => 'ptn_font_color', 'ptatext' => 'pta_font_color', 'ptsptext' => 'ptsp_font_color', 'cscolor' => 'col_shad_color', 'cshcolor' => 'col_shad_hover_color', 'ftdir' => 'feature_align'
			);

			foreach($package_text_values as $key => $value) {
				if( isset( $_POST[$value] ) ) {
					$optionValue_text[$key] = sanitize_text_field( $_POST[$value] );
				}
			}

			// Define the icons for tick and cross
			$tick_icons = [
				'tick-1', 'tick-2', 'tick-3', 'tick-4', 'tick-5', 'tick-6', 'tick-7', 'tick-8', 'tick-9', 'tick-10'
			];
			$cross_icons = [
				'cross-1', 'cross-2', 'cross-3', 'cross-4', 'cross-5', 'cross-6', 'cross-7', 'cross-8', 'cross-9', 'cross-10'
			];

			$wrcpt_option = isset($_POST['wrcpt_option']) && $_POST['wrcpt_option'] === 'yes' ? 'yes' : 'no';
			$feature_caption = isset($_POST['feature_caption']) && $_POST['feature_caption'] === 'yes' ? 'yes' : 'no';
			$auto_column = isset($_POST['auto_column']) && $_POST['auto_column'] === 'yes' ? 'yes' : 'no';
			$enlarge_column = isset($_POST['enlarge_column']) && $_POST['enlarge_column'] === 'yes' ? 'yes' : 'no';
			$column_shadow = isset($_POST['column_shadow']) && $_POST['column_shadow'] === 'yes' ? 'yes' : 'no';
			$disable_shadow = isset($_POST['disable_shadow']) && $_POST['disable_shadow'] === 'yes' ? 'yes' : 'no';
			$title_gradient = isset($_POST['title_gradient']) && $_POST['title_gradient'] === 'yes' ? 'yes' : 'no';
			$unit_right = isset($_POST['unit_right']) && $_POST['unit_right'] === 'yes' ? 'yes' : 'no';
			$enable_tooltip = isset($_POST['enable_tooltip']) && $_POST['enable_tooltip'] === 'yes' ? 'yes' : 'no';
			$enable_ribbon = isset($_POST['enable_ribbon']) && $_POST['enable_ribbon'] === 'yes' ? 'yes' : 'no';
			$new_tab = isset($_POST['new_tab']) && $_POST['new_tab'] === 'yes' ? 'yes' : 'no';

			$tick_mark = isset($_POST['tick_mark']) && in_array($_POST['tick_mark'], $tick_icons) ? sanitize_text_field($_POST['tick_mark']) : 'tick-1';
			$cross_mark = isset($_POST['cross_mark']) && in_array($_POST['cross_mark'], $cross_icons) ? sanitize_text_field($_POST['cross_mark']) : 'cross-1';

			$submitted = (isset($_POST['submitted']) && $_POST['submitted'] == 'yes') ? sanitize_text_field($_POST['submitted']) : 'yes';

			$table_option = $pricing_table.'_option';

			$optionValue_check = array(
				'enable' => $wrcpt_option, 'ftcap' => $feature_caption, 'autocol' => $auto_column, 'encol' => $enlarge_column, 'colshad' => $column_shadow, 'dscol' => $disable_shadow, 'ttgrd' => $title_gradient, 'purgt' => $unit_right, 'entips' => $enable_tooltip, 'enribs' => $enable_ribbon, 'tick' => $tick_mark, 'cross' => $cross_mark, 'nltab' => $new_tab, 'subform' => $submitted
			);

			$optionValue = array_merge($optionValue_text, $optionValue_check);

			update_option($table_option, $optionValue);

			if (isset($_POST['pricing_packages'])) {
				$sanitized_pricing_packages = array_map('sanitize_text_field', $_POST['pricing_packages']);
				wrcpt_delete_pricing_packages($pricing_table, $sanitized_pricing_packages);
			} else {
				wrcpt_delete_pricing_packages($pricing_table, ''); // Provide a default value if $_POST['pricing_packages'] is not set
			}

			$package_id = isset($_POST['package_id']) ? array_map('sanitize_text_field', $_POST['package_id']) : array();
			$order_id = isset($_POST['order_id']) ? array_map('sanitize_text_field', $_POST['order_id']) : array();
			$special_package = isset($_POST['special_package']) ? array_map('sanitize_text_field', $_POST['special_package']) : array();
			$package_type = isset($_POST['package_type']) ? array_map('sanitize_text_field', $_POST['package_type']) : array();

			$package_text_options = array(
				'pdisp' => 'hide_show', 'type' => 'package_type', 'tdesc' => 'package_desc', 'tcolor' => 'title_color', 'tbcolor' => 'title_bg', 'fbrow1' => 'feat_row1_color', 'fbrow2' => 'feat_row2_color', 'ftcolor' => 'feature_text_color', 'price' => 'price_number', 'pcbig' => 'price_color_big', 'cent' => 'price_fraction', 'unit' => 'price_unit', 'plan' => 'package_plan', 'pdesc' => 'price_desc', 'btext' => 'button_text', 'blink' =>'button_link', 'bicon' =>'button_icon', 'btcolor' => 'button_text_color', 'bthover' => 'button_text_hover', 'bcolor' => 'button_color', 'bhover' => 'button_hover', 'rtext' => 'ribbon_text', 'rtcolor' => 'ribbon_text_color', 'rbcolor' => 'ribbon_bg'
			);

			$package_features = isset($_POST['feature_value']) ? array_map('sanitize_text_field', $_POST['feature_value']) : array();
			$package_tooltips = isset($_POST['tooltips']) ? array_map('sanitize_text_field', $_POST['tooltips']) : array();
			$checkbox_value = isset($_POST['checkbox_value']) ? sanitize_text_field($_POST['checkbox_value']) : '';

			$table_feature = $pricing_table.'_feature';

			$feature_items = get_option($table_feature);

			if(isset($_POST['pricing_packages'])) {
				foreach($_POST['pricing_packages'] as $key => $package) {
					$vl =1;  $fk = 1; $packVal = array();
					$poptionName = 'packageOptions' . sanitize_text_field($package_id[$key]);

					if(get_option($poptionName) !== null && !empty(get_option($poptionName))) {
						$packVal = get_option($poptionName);
					}

					if($packVal['type'] == $package_type[$key]) {
						$optionName = 'packageOptions' . sanitize_text_field($package_id[$key]);
						$order_id[$key] = $key+1;
					} else {
						delete_option( $poptionName );
						$package_lists = get_option($pricing_table);
						$optionName = wrcpt_update_pricing_table($pricing_table, $package_lists);
						$package_id[$key] = get_option('packageCount');
						$order_id[$key] = $key+1;
					}
					$package_order[] = $optionName;

					foreach($feature_items as $fkey => $item) {
						if($fkey == 'fitem'.$fk) {
							if($feature_items['ftype'.$fk] == 'text') {
								if($package_features[$fc]) {
									$feature_value[$fkey] = sanitize_text_field(wp_unslash($package_features[$fc]));
								}
								else { $feature_value[$fkey] = ''; }
							} elseif($feature_items['ftype'.$fk] == 'check') {
								if(array_key_exists('ftype'.$cf, $package_features)) {
									$feature_value[$fkey] = 'tick';
									$cf++; $fc--;
								}
								else {
									$feature_value[$fkey] = 'cross';
									$cf++; $fc--;
								}
							} else {
								if($package_features[$fc]) {
									$feature_value[$fkey] = sanitize_text_field(wp_unslash($package_features[$fc]));
									$fc++;
								}
								else { $feature_value[$fkey] = ''; $fc++; }
								if(array_key_exists('ftype'.$cf, $package_features)) {
									$feature_value[$fkey.'c'] = 'tick';
									$cf++; $fc--;
								}
								else {
									$feature_value[$fkey.'c'] = 'cross';
									$cf++; $fc--;
								}
							}
							if($package_tooltips) {
								if($package_tooltips[$tc]) {
									$tooltips['tip' . $vl] = sanitize_text_field(wp_unslash($package_tooltips[$tc]));
									$vl++;
								} else {
									$tooltips['tip'.$vl] = '';
									$vl++;
								}
							}
							$fc++; $tc++;
						} else { 
							if($fkey == 'ftype'.$fk) {
								$fk++;
							}
						}
					}
					if(!isset($special_package[$key])) {
						$special_package[$key] = 'no';
					}

					foreach($package_text_options as $pkey => $value) {
						if( isset( $_POST[$value] ) ) {
							$packValue = isset($_POST[$value]) ? array_map('wp_unslash', $_POST[$value]) : array();
							if($pkey == 'blink') {
								$packageOptions_text[$pkey] = esc_url_raw( $packValue[$key] );
							} else {
								$packageOptions_text[$pkey] = sanitize_text_field($packValue[$key]);
							}
						}
					}

					$packageOptions_extra = array( 'pid' => $package_id[$key], 'order' => $order_id[$key], 'spack' => $special_package[$key] );

					$mergePackages = array_merge($packageOptions_extra, $packageOptions_text, $feature_value, $tooltips);
					update_option($optionName, $mergePackages);
				}
			}
			$table_lists = implode(', ', $package_order);
			update_option($pricing_table, $table_lists);
		} else {
			// Nonce verification failed, handle the error
            wp_send_json_error(array('message' => 'Nonce verification failed'));
            // Display an error message or handle the case where permissions and nonce check failed
            wp_die(__('You do not have sufficient permissions to access this page, or the nonce verification failed.', 'wrc-pricing-tables'));
        }
		wp_die();
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_update_pricing_package', 'wrcpt_update_pricing_package' );
add_action( 'wp_ajax_wrcpt_update_pricing_package', 'wrcpt_update_pricing_package' );

if (!function_exists('wrcpt_regenerate_shortcode')) {
	function wrcpt_regenerate_shortcode() {
		// Check if the current user has the necessary capability
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
	
		// Get the nonce from the AJAX request data
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
	
		// Verify the nonce
		if (!wp_verify_nonce($nonce, 'wrcpt_ajax_action_nonce')) {
			wp_die('You do not have sufficient permissions to access this page.');
			// Nonce verification failed, handle the error
			wp_send_json_error(array('message' => 'Nonce verification failed'));
		}
		$package_table = get_option('packageTables');
		delete_option('packageIDs');
		delete_option('IDsCount');
	
		$table_lists = explode(', ', $package_table);
		$pack_id = 1;
		$temp = '';
		$id_count = count($table_lists);
		foreach($table_lists as $key => $list) {
			if($id_count > 1) {
				$pricing_table_ids = $temp . $pack_id;
				$temp = $pricing_table_ids . ', ';
				$pack_id++;
			} else {
				$pricing_table_ids = $pack_id;
			}
		}
		add_option('packageIDs', $pricing_table_ids);
		add_option('IDsCount', $id_count);
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_regenerate_shortcode', 'wrcpt_regenerate_shortcode' );
add_action( 'wp_ajax_wrcpt_regenerate_shortcode', 'wrcpt_regenerate_shortcode' );

/* Find how many tables are plublished */
if (!function_exists('wrcpt_published_tables_count')) {
	function wrcpt_published_tables_count($table_lists) {
		// Check if the user has the necessary capability (e.g., manage_options)
		if (!current_user_can('manage_options')) {
			// If the user does not have the required capability, terminate and display an error message.
			wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
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
}

/* Find unuseful package options and delete them */
if (!function_exists('wrcpt_unuseful_package_options')) {
	function wrcpt_unuseful_package_options() {
		// Check if the user has the necessary capability (e.g., manage_options)
		if (!current_user_can('manage_options')) {
			// If the user does not have the required capability, terminate and display an error message.
			wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
		} else {
			$package_table = get_option('packageTables');
			$table_lists = explode(', ', $package_table);
			$temp = ''; $pcount = 0;
			foreach($table_lists as $key => $table) :
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
}

/* Count unuseful package options and show number */
if (!function_exists('wrcpt_count_unuseful_package_options')) {
	function wrcpt_count_unuseful_package_options() {
		// Check if the user has the necessary capability (e.g., manage_options)
		if (!current_user_can('manage_options')) {
			// If the user does not have the required capability, terminate and display an error message.
			wp_die(__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
		} else {
			$package_table = get_option('packageTables');
			$table_lists = explode(', ', $package_table);
			$temp = ''; $upcount = 0;
			foreach($table_lists as $key => $table) :
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