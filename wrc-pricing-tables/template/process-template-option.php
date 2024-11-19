<?php
/*
 * WRC Pricing Tables 2.4.4 - 20 November, 2024
 * @realwebcare - https://www.realwebcare.com/
 * Auto generating pricing table from scratch.
 * Changing table design with selected template instantly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function wrcpt_activate_template() {
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
		$template_number = isset($_POST['tempcount']) ? sanitize_text_field($_POST['tempcount']) : 1;
		$package_table = get_option('packageTables');
		$package_id = $id_count = 1;
		$table_lists = explode(', ', sanitize_text_field($package_table));
		$count_copy = count($table_lists) + $template_number;
		$pricing_table = 'pricing_template_' . $count_copy . rand(1,1000);
		$pricing_table = sanitize_text_field($pricing_table);
		$package_feature = $pricing_table.'_feature';
		$table_option = $pricing_table.'_option';
		$fn = 1;

		$template_features = array('Webspace', 'Monthly Bandwidth', 'Domain Name', 'Email Address', 'Online Support');
		$feature_type = array('text', 'text', 'text', 'text', 'check');
		$feature_values = array( 'fitem1' => array('5 GB', '15 GB', '30 GB', '50 GB'), 'fitem2' => array('50 GB', '150 GB', '300 GB', '500 GB'), 'fitem3' => array('25', '75', '150', '250'), 'fitem4' => array('50', '150', '300', '500'), 'fitem5' => array('cross', 'tick', 'tick', 'tick'), 'tip1' => array('', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', '', ''), 'tip2' => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', '', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic'), 'tip3' => array('', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', ''), 'tip4' => array('', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic'), 'tip5' => array('', '', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic') );

		if(!isset($package_table)) {
			add_option('packageTables', $pricing_table);
			add_option('packageIDs', $package_id);
			add_option('IDsCount', $id_count);
		} elseif(empty($package_table)){
			update_option('packageTables', $pricing_table);
			update_option('packageIDs', $package_id);
			update_option('IDsCount', $id_count);
		} else {
			if(in_array($pricing_table, $table_lists)) {
				$new_pricing_table = 'another_' . $pricing_table;
				$pricing_table_lists = $package_table . ', ' . $new_pricing_table;
				update_option('packageTables', $pricing_table_lists);
			} else {
				$pricing_table_lists = $package_table . ', ' . $pricing_table;
				update_option('packageTables', $pricing_table_lists);
			}
			$package_id = get_option('packageIDs');
			$id_count = get_option('IDsCount') + 1;
			$pricing_table_ids = $package_id . ', ' . $id_count;
			update_option('packageIDs', $pricing_table_ids);
			update_option('IDsCount', $id_count);
		}

		$package_options_check = array( 'cwidth' => '', 'maxcol' => '4', 'colgap' => '1', 'capwidth' => '18.73', 'ctsize' => '', 'cftsize' => '', 'tbody' => '', 'tsize' => '', 'pbody' => '', 'psbig' => '', 'pssmall' => '', 'ftbody' => '', 'ftsize' => '', 'btbody' => '', 'bwidth' => '', 'bheight' => '', 'btsize' => '', 'rtsize' => '', 'ttwidth' => '150px', 'ftdir' => 'left', 'enable' => 'yes', 'ftcap' => 'no', 'autocol' => 'yes', 'encol' => 'yes', 'colshad' => 'yes', 'dscol' => 'no', 'ttgrd' => 'no', 'purgt' => 'no', 'entips' => 'yes', 'enribs' => 'yes', 'nltab' => 'no', 'tick' => 'tick-2', 'cross' => 'cross-2' );
		$package_details_texts = array( 'spack' => array('no', 'no', 'no', 'no'), 'pdisp' => array('show', 'show', 'show', 'show'), 'type' => array('Starter', 'Professional', 'Business', 'Premier'), 'tdesc' => array('', '', '', ''), 'price' => array('9', '24', '39', '54'), 'cent' => array('', '', '', ''), 'unit' => array('$', '$', '$', '$'), 'plan' => array('month', 'month', 'month', 'month'), 'pdesc' => array('', '', '', ''), 'btext' => array('Sign Up', 'Sign Up', 'Sign Up', 'Sign Up'), 'blink' => array('#', '#', '#', '#'), 'rtext' => array('', '', 'NEW', '') );

		if($template_number == 1) {
			$package_options_color = array( 'templ' => 'temp1', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'bhover' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'rtcolor' => array('#aa4518', '#aa4518', '#aa4518', '#aa4518'), 'rbcolor' => array('#faec00', '#faec00', '#faec00', '#faec00') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 2) {
			$package_options_color = array( 'templ' => 'temp2', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#49ab81', '#419873', '#398564', '#317256'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#49ab81', '#419873', '#398564', '#317256'), 'bhover' => array('#49ab81', '#419873', '#398564', '#317256'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#93291c', '#93291c', '#93291c', '#93291c') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 3) {
			$package_options_color = array( 'templ' => 'temp3', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#6497b1', '#005b96', '#03396c', '#011f4b'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#03396c', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#174d80', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#ededed', '#333333'), 'btcolor' => array('#012345', '#012345', '#012345', '#012345'), 'bthover' => array('#012345', '#012345', '#012345', '#012345'), 'bcolor' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'bhover' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#a73d30', '#a73d30', '#a73d30', '#a73d30') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 4) {
			$package_options_color = array( 'templ' => 'temp4', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bhover' => array('#6239B9', '#6239B9', '#6239B9', '#6239B9'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 5) {
			$package_options_color = array( 'templ' => 'temp5', 'ttgrd' => 'yes', 'tick' => 'tick-7', 'cross' => 'cross-7', 'ftdir' => 'center', 'tbody' => '72px', 'pbody' => '200px', 'btbody' => '72px', 'bwidth' => '140px', 'bheight' => '40px', 'ttwidth' => '200px', 'psbig' => '80px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'spack' => array('no', 'no', 'yes', 'no'), 'tdesc' => array('Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet'), 'pdesc' => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'), 'tcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'tbcolor' => array('#1c1c1c', '#1c1c1c', '#1c1c1c', '#1c1c1c'), 'pcbig' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow1' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'fbrow2' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'bhover' => array('#c89212', '#ba681b', '#b56044', '#914c35'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#ecb331', '#ecb331', '#d17e2d', '#d17e2d') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 6) {
			$package_options_color = array( 'templ' => 'temp6', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#a1b901', '#01a0e1', '#ff5d02', '#ffb701'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 7) {
			$package_options_color = array( 'templ' => 'temp7', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#B5A166', '#8A6A35', '#804C32', '#B1402A'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'fbrow2' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'ftcolor' => array('#333333', '#333333', '#FAFAFA', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 8) {
			$package_options_color = array( 'templ' => 'temp8', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'tbcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'pcbig' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bcolor' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'bhover' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'rtcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'rbcolor' => array('#04BACE', '#ecf0e2', '#04BACE', '#04BACE') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 9) {
			$package_options_color = array( 'templ' => 'temp9', 'ftdir' => 'center', 'colgap' => '0', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'ttwidth' => '220px', 'ctsize' => '24px' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#ed662f', '#ea2e2d', '#BE292D', '#993124'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'ftcolor' => array('#000000', '#000000', '#000000', '#000000'), 'fbrow1' => array('#ffffff', '#ececec', '#ffffff', '#ececec'), 'fbrow2' => array('#ececec', '#ffffff', '#ececec', '#ffffff'), 'btcolor' => array('#000000', '#000000', '#000000', '#000000'), 'bthover' => array('#000000', '#000000', '#000000', '#000000'), 'bcolor' => array('#ececec', '#ececec', '#ececec', '#ececec'), 'bhover' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'rtcolor' => array('#993124', '#993124', '#993124', '#993124'), 'rbcolor' => array('#f7db53', '#f7db53', '#f7db53', '#f7db53') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 10) {
			$package_options_color = array( 'templ' => 'temp10', 'colgap' => '1', 'bwidth' => '120px', 'bheight' => '36px', 'ftdir' => 'center', 'ttwidth' => '220px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes' );
			$package_details_color = array( 'tcolor' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'tbcolor' => array('#ededed', '#ededed', '#222F3D', '#ededed'), 'pcbig' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'ftcolor' => array('#222F3D', '#222F3D', '#ffffff', '#222F3D'), 'fbrow1' => array('#ffffff', '#ffffff', '#34495E', '#ffffff'), 'fbrow2' => array('#EBEBEB', '#EBEBEB', '#304357', '#EBEBEB'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#46627F', '#46627F', '#46627F', '#46627F'), 'bhover' => array('#222F3D', '#222F3D', '#222F3D', '#222F3D'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 11) {
			$package_options_color = array( 'templ' => 'temp11', 'ftdir' => 'center', 'ttwidth' => '200px', 'colshad' => 'no', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'colgap' => '0', 'ctsize' => '24px' );
			$package_details_color = array( 'tcolor' => array('#9d9d9d', '#9d9d9d', '#9d9d9d', '#9d9d9d'), 'tbcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'pcbig' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'ftcolor' => array('#7d7d7d', '#7d7d7d', '#7d7d7d', '#7d7d7d'), 'fbrow1' => array('#e6e6e6', '#e2e2e2', '#e6e6e6', '#e2e2e2'), 'fbrow2' => array('#ffffff', '#f5f5f5', '#ffffff', '#f5f5f5'), 'btcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bthover' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bcolor' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'bhover' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 12) {
			$package_options_color = array( 'templ' => 'temp12', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes' );
			$package_details_color = array( 'tcolor' => array('#000000', '#000000', '#ffffff', '#000000'), 'tbcolor' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'pcbig' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'), 'ftcolor' => array('#868686', '#868686', '#fafafa', '#868686'), 'fbrow1' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'fbrow2' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'btcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#3e7de8', '#3e7de8', '#f2f2f2', '#3e7de8'), 'bhover' => array('#606060', '#606060', '#606060', '#606060'), 'rtcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'rbcolor' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 13) {
			/* 
			etclr, tbpad, ftpad, cftdir, tttext, fatclr, facclr, ttbg, ptabg, ptnbg, ctcolor, cftcolor, furlclr, furlhvr, ccolbg, prnbg, capbg1, capbg2, ccborder, markrib, pbrad, cbrad, enicon, enfbor, prgrd, dsgrd, encbor, fcatbg, enfatc, awetick, awecross, captbg, cttdir,

			pbclr, thover, phbig, pcsmall, phsmall, cutclr, pbcolor, fncolor, bbcolor, rthover, ftborder,
			*/
			$package_options_color = array( 'templ' => 'temp13', 'ftdir' => 'left', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes' );
			$package_details_color = array( 'tcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'tbcolor' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'pcbig' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'ftcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'fbrow1' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow2' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'bhover' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} elseif($template_number == 14) {
			$package_options_color = array( 'templ' => 'temp14', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes', 'tick' => 'tick-2', 'cross' => 'cross-2' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'tbcolor' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'ftcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'fbrow1' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'fbrow2' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'btcolor' => array('#292c3c', '#292c3c', '#ffffff', '#292c3c'), 'bthover' => array('#ffffff', '#ffffff', '#292c3c', '#ffffff'), 'bcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'), 'bhover' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		} else {
			$package_options_color = array( 'templ' => 'temp0', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details_color = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#44A3D5', '#44A3D5', '#44A3D5', '#44A3D5'), 'pcbig' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bhover' => array('#333333', '#333333', '#333333', '#333333'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#CB0000', '#CB0000', '#CB0000', '#CB0000') );
			$package_options = array_merge($package_options_check, $package_options_color);
			$package_details = array_merge($package_details_texts, $package_details_color);
		}
		/* Generating Package Features */
		foreach($template_features as $key => $feature) {
			if($feature) {
				$feature_name['fitem'.$fn] = sanitize_text_field( $feature );
				$feature_name['ftype'.$fn] = sanitize_text_field($feature_type[$key]);
				$fn++;
			} else {
				$feature_name['fitem'.$fn] = '';
				$feature_name['ftype'.$fn] = '';
				$fn++;
			}
		}
		add_option($package_feature, $feature_name);
		/* Generating Package Options */
		foreach($package_options as $key => $option) {
			$optionValue[$key] = sanitize_text_field( $option );
		}
		add_option($table_option, $optionValue);
		/* Generating Package Lists */
		for($pn = 0; $pn < 4; $pn++) {
			$package_lists = get_option($pricing_table);
			$optionName = wrcpt_update_pricing_table($pricing_table, $package_lists);
			$package_count = get_option('packageCount');
			$new_package_lists = get_option($pricing_table);
			$packageOptions = explode(', ', $new_package_lists);
			$list_count = count($packageOptions);
			foreach($package_details as $pkey => $value) {
				$packageOptions_text[$pkey] = sanitize_text_field( $value[$pn] );
			}
			foreach($feature_values as $fkey => $fvalue) {
				$featureValues_text[$fkey] = sanitize_text_field( $fvalue[$pn] );
			}
			$package_details_top = array( 'pid' => $package_count, 'order' => $list_count );
			$mergePackages = array_merge($package_details_top, $packageOptions_text, $featureValues_text);
			add_option($optionName, $mergePackages);
		}
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_activate_template', 'wrcpt_activate_template' );
add_action( 'wp_ajax_wrcpt_activate_template', 'wrcpt_activate_template' );

function wrcpt_setup_selected_template() {
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
		$template = isset($_POST['template']) ? sanitize_text_field($_POST['template']) : 'temp1';
		$table_name = isset($_POST['packtable']) ? sanitize_text_field($_POST['packtable']) : '';
		$option_name = $table_name.'_option';
		$table_option = get_option($option_name);
		$package_lists = get_option($table_name);
		$packageOptions = explode(', ', sanitize_text_field($package_lists));
		if($template == 'temp1') {
			$package_options = array( 'templ' => 'temp1', 'ftdir' => 'left', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'bhover' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'rtcolor' => array('#aa4518', '#aa4518', '#aa4518', '#aa4518'), 'rbcolor' => array('#faec00', '#faec00', '#faec00', '#faec00') );
		} elseif($template == 'temp2') {
			$package_options = array( 'templ' => 'temp2', 'ftdir' => 'left', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#49ab81', '#419873', '#398564', '#317256'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#49ab81', '#419873', '#398564', '#317256'), 'bhover' => array('#49ab81', '#419873', '#398564', '#317256'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#93291c', '#93291c', '#93291c', '#93291c') );
		} elseif($template == 'temp3') {
			$package_options = array( 'templ' => 'temp3', 'ftdir' => 'left', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#6497b1', '#005b96', '#03396c', '#011f4b'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#012345', '#012345', '#012345', '#012345'), 'bthover' => array('#012345', '#012345', '#012345', '#012345'), 'bcolor' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'bhover' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#a73d30', '#a73d30', '#a73d30', '#a73d30') );
		} elseif($template == 'temp4') {
			$package_options = array( 'templ' => 'temp4', 'ftdir' => 'center', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bhover' => array('#6239B9', '#6239B9', '#6239B9', '#6239B9'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162') );
		} elseif($template == 'temp5') {
			$package_options = array( 'templ' => 'temp5', 'ftdir' => 'center', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'pcbig' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'btcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bthover' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'bcolor' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'bhover' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100') );
		} elseif($template == 'temp6') {
			$package_options = array( 'templ' => 'temp6', 'ftdir' => 'center', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#a1b901', '#01a0e1', '#ff5d02', '#ffb701'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2') );
		} elseif($template == 'temp7') {
			$package_options = array( 'templ' => 'temp7', 'ftdir' => 'center', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#B5A166', '#8A6A35', '#804C32', '#B1402A'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2') );
		} elseif($template == 'temp8') {
			$package_options = array( 'templ' => 'temp8', 'ftdir' => 'center', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'tbcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'pcbig' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'btcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bcolor' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'bhover' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'rtcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'rbcolor' => array('#04BACE', '#ecf0e2', '#04BACE', '#04BACE') );
		} elseif($template == 'temp9') {		
			$package_options = array( 'templ' => 'temp9', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#ed662f', '#ea2e2d', '#BE292D', '#993124'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'ftcolor' => array('#000000', '#000000', '#000000', '#000000'), 'fbrow1' => array('#ffffff', '#ececec', '#ffffff', '#ececec'), 'fbrow2' => array('#ececec', '#ffffff', '#ececec', '#ffffff'), 'btcolor' => array('#000000', '#000000', '#000000', '#000000'), 'bthover' => array('#000000', '#000000', '#000000', '#000000'), 'bcolor' => array('#ececec', '#ececec', '#ececec', '#ececec'), 'bhover' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'rtcolor' => array('#993124', '#993124', '#993124', '#993124'), 'rbcolor' => array('#f7db53', '#f7db53', '#f7db53', '#f7db53') );
		} elseif($template == 'temp10') {		
			$package_options = array( 'templ' => 'temp10', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'tbcolor' => array('#ededed', '#ededed', '#222F3D', '#ededed'), 'pcbig' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'ftcolor' => array('#222F3D', '#222F3D', '#ffffff', '#222F3D'), 'fbrow1' => array('#ffffff', '#ffffff', '#34495E', '#ffffff'), 'fbrow2' => array('#EBEBEB', '#EBEBEB', '#304357', '#EBEBEB'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#46627F', '#46627F', '#46627F', '#46627F'), 'bhover' => array('#222F3D', '#222F3D', '#222F3D', '#222F3D'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100') );
		} elseif($template == 'temp11') {		
			$package_options = array( 'templ' => 'temp11', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#9d9d9d', '#9d9d9d', '#9d9d9d', '#9d9d9d'), 'tbcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'pcbig' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'ftcolor' => array('#7d7d7d', '#7d7d7d', '#7d7d7d', '#7d7d7d'), 'fbrow1' => array('#e6e6e6', '#e2e2e2', '#e6e6e6', '#e2e2e2'), 'fbrow2' => array('#ffffff', '#f5f5f5', '#ffffff', '#f5f5f5'), 'btcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bthover' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bcolor' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'bhover' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100') );
		} elseif($template == 'temp12') {		
			$package_options = array( 'templ' => 'temp12', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#000000', '#000000', '#ffffff', '#000000'), 'tbcolor' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'pcbig' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'), 'ftcolor' => array('#868686', '#868686', '#fafafa', '#868686'), 'fbrow1' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'fbrow2' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'btcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#3e7de8', '#3e7de8', '#f2f2f2', '#3e7de8'), 'bhover' => array('#606060', '#606060', '#606060', '#606060'), 'rtcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'rbcolor' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8') );
		} elseif($template == 'temp13') {		
			$package_options = array( 'templ' => 'temp13', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'tbcolor' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'pcbig' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'ftcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'fbrow1' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow2' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'bhover' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39') );
		} elseif($template == 'temp14') {		
			$package_options = array( 'templ' => 'temp14', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'tbcolor' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'ftcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'fbrow1' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'fbrow2' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'btcolor' => array('#292c3c', '#292c3c', '#ffffff', '#292c3c'), 'bthover' => array('#ffffff', '#ffffff', '#292c3c', '#ffffff'), 'bcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'), 'bhover' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052') );
		} else {
			$package_options = array( 'templ' => 'temp0', 'ftdir' => 'left', 'cscolor' => '#cccccc', 'cshcolor' => '#333333' );
			$package_details = array( 'tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#44A3D5', '#44A3D5', '#44A3D5', '#44A3D5'), 'pcbig' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bhover' => array('#333333', '#333333', '#333333', '#333333'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#CB0000', '#CB0000', '#CB0000', '#CB0000') );
		}
		foreach($packageOptions as $key => $option) {
			$package_value = get_option($option);
			foreach($package_details as $pkey => $value) {
				$packageValues_text[$pkey] = sanitize_text_field( $value[$key] );
			}
			$mergePackages = array_merge($package_value, $packageValues_text);
			update_option($option, $mergePackages);
		}
		$mergeOptions = array_merge($table_option, $package_options);
		update_option($option_name, $mergeOptions);
	}
}
add_action( 'wp_ajax_nopriv_wrcpt_setup_selected_template', 'wrcpt_setup_selected_template' );
add_action( 'wp_ajax_wrcpt_setup_selected_template', 'wrcpt_setup_selected_template' );