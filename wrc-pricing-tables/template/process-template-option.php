<?php
/**
 * Class WRCPT_Template_Options
 * 
 * Handles AJAX actions for activating and setting up pricing table templates with default options.
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Template_Options')) {
	class WRCPT_Template_Options
	{
		private static $instance;

		/**
		 * Constructor: Registers AJAX actions for template activation and setup.
		 */
		public function __construct()
		{
			add_action( 'wp_ajax_nopriv_wrcpt_activate_template', array( $this, 'wrcpt_activate_template' ) );
			add_action( 'wp_ajax_wrcpt_activate_template', array( $this, 'wrcpt_activate_template' ) );

			add_action( 'wp_ajax_nopriv_wrcpt_setup_selected_template', array( $this, 'wrcpt_setup_selected_template' ) );
			add_action( 'wp_ajax_wrcpt_setup_selected_template', array( $this, 'wrcpt_setup_selected_template' ) );
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
		 * Updates or creates a pricing table entry and returns the new option name.
		 * 
		 * @param mixed $pricing_table	Table slug/name.
		 * @param mixed $package_lists	Comma-separated list of package option keys.
		 */
		public function wrcpt_update_pricing_table($pricing_table, $package_lists) {
			// Check if the user has the necessary capability (e.g., manage_options)
			if (!current_user_can('manage_options')) {
				// If the user does not have the required capability, terminate and display an error message.
				wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wrc-pricing-tables'));
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

		/**
		 * Activates a selected template by creating a new pricing table with default features and columns.
		 * 
		 * @return void
		 */
		public function wrcpt_activate_template()
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
				$template_number = isset($_POST['tempcount']) ? sanitize_text_field(wp_unslash($_POST['tempcount'])) : 1;
				$package_table = get_option('packageTables');
				$package_id = $id_count = 1;
				$table_lists = explode(', ', sanitize_text_field($package_table));
				$count_copy = count($table_lists) + $template_number;
				$pricing_table = 'pricing_template_' . $count_copy . wp_rand(1, 1000);
				$pricing_table = sanitize_text_field($pricing_table);
				$package_feature = $pricing_table . '_feature';
				$table_option = $pricing_table . '_option';
				$fn = 1;
		
				$template_features = array('Webspace', 'Monthly Bandwidth', 'Domain Name', 'Email Address', 'Online Support');
				$feature_type = array('text', 'text', 'text', 'text', 'check');
				$feature_values = array('fitem1' => array('5 GB', '15 GB', '30 GB', '50 GB'), 'fitem2' => array('50 GB', '150 GB', '300 GB', '500 GB'), 'fitem3' => array('25', '75', '150', '250'), 'fitem4' => array('50', '150', '300', '500'), 'fitem5' => array('cross', 'tick', 'tick', 'tick'), 'tip1' => array('', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', '', ''), 'tip2' => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', '', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic'), 'tip3' => array('', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', ''), 'tip4' => array('', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic'), 'tip5' => array('', '', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin condimentum elit et ipsum tempus, at ultricies odio effic'));
		
				if (!isset($package_table)) {
					add_option('packageTables', $pricing_table);
					add_option('packageIDs', $package_id);
					add_option('IDsCount', $id_count);
				} elseif (empty($package_table)) {
					update_option('packageTables', $pricing_table);
					update_option('packageIDs', $package_id);
					update_option('IDsCount', $id_count);
				} else {
					if (in_array($pricing_table, $table_lists)) {
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
		
				$package_options_check = array('cwidth' => '', 'maxcol' => '4', 'colgap' => '1', 'capwidth' => '18.73', 'ctsize' => '', 'cftsize' => '', 'tbody' => '', 'tsize' => '', 'pbody' => '', 'psbig' => '', 'pssmall' => '', 'ftbody' => '', 'ftsize' => '', 'btbody' => '', 'bwidth' => '', 'bheight' => '', 'btsize' => '', 'rtsize' => '', 'ttwidth' => '150px', 'ftdir' => 'left', 'enable' => 'yes', 'ftcap' => 'no', 'autocol' => 'yes', 'encol' => 'yes', 'colshad' => 'yes', 'dscol' => 'no', 'ttgrd' => 'no', 'purgt' => 'no', 'entips' => 'yes', 'enribs' => 'yes', 'nltab' => 'no', 'tick' => 'tick-2', 'cross' => 'cross-2');
				$package_details_texts = array('spack' => array('no', 'no', 'no', 'no'), 'pdisp' => array('show', 'show', 'show', 'show'), 'type' => array('Starter', 'Professional', 'Business', 'Premier'), 'tdesc' => array('', '', '', ''), 'price' => array('9', '24', '39', '54'), 'cent' => array('', '', '', ''), 'unit' => array('$', '$', '$', '$'), 'plan' => array('month', 'month', 'month', 'month'), 'pdesc' => array('', '', '', ''), 'btext' => array('Sign Up', 'Sign Up', 'Sign Up', 'Sign Up'), 'blink' => array('#', '#', '#', '#'), 'rtext' => array('', '', 'NEW', ''));
		
				if ($template_number == 1) {
					$package_options_color = array('templ' => 'temp1', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'bhover' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'rtcolor' => array('#aa4518', '#aa4518', '#aa4518', '#aa4518'), 'rbcolor' => array('#faec00', '#faec00', '#faec00', '#faec00'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 2) {
					$package_options_color = array('templ' => 'temp2', 'ftdir' => 'left', 'ttwidth' => '200px', 'bwidth' => '130px', 'bheight' => '40px', 'cscolor' => '#e8f5e9', 'cshcolor' => '#c8e6c9');
					$package_details_color = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#2E7D32','#1B5E20','#388E3C','#43A047'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#E8F5E9','#E8F5E9','#E8F5E9','#E8F5E9'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#1B5E20','#1B5E20','#1B5E20','#1B5E20'),'bcolor'=>array('#388E3C','#388E3C','#388E3C','#388E3C'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF6F00','#FF6F00','#FF6F00','#FF6F00'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 3) {
					$package_options_color = array('templ' => 'temp3', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#6497b1', '#005b96', '#03396c', '#011f4b'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#03396c', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#174d80', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#ededed', '#333333'), 'btcolor' => array('#012345', '#012345', '#012345', '#012345'), 'bthover' => array('#012345', '#012345', '#012345', '#012345'), 'bcolor' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'bhover' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#a73d30', '#a73d30', '#a73d30', '#a73d30'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 4) {
					$package_options_color = array('templ' => 'temp4', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bhover' => array('#6239B9', '#6239B9', '#6239B9', '#6239B9'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 5) {
					$package_options_color = array('templ' => 'temp5', 'ttgrd' => 'yes', 'tick' => 'tick-7', 'cross' => 'cross-7', 'ftdir' => 'center', 'tbody' => '72px', 'pbody' => '200px', 'btbody' => '72px', 'bwidth' => '140px', 'bheight' => '40px', 'ttwidth' => '200px', 'psbig' => '80px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('spack' => array('no', 'no', 'yes', 'no'), 'tdesc' => array('Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet'), 'pdesc' => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'), 'tcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'tbcolor' => array('#1c1c1c', '#1c1c1c', '#1c1c1c', '#1c1c1c'), 'pcbig' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow1' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'fbrow2' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'bhover' => array('#c89212', '#ba681b', '#b56044', '#914c35'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#ecb331', '#ecb331', '#d17e2d', '#d17e2d'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 6) {
					$package_options_color = array('templ' => 'temp6', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#a1b901', '#01a0e1', '#ff5d02', '#ffb701'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 7) {
					$package_options_color = array('templ' => 'temp7', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#B5A166', '#8A6A35', '#804C32', '#B1402A'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'fbrow2' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'ftcolor' => array('#333333', '#333333', '#FAFAFA', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 8) {
					$package_options_color = array('templ' => 'temp8', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details_color = array('tcolor' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'tbcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'pcbig' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bcolor' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'bhover' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'rtcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'rbcolor' => array('#04BACE', '#ecf0e2', '#04BACE', '#04BACE'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 9) {
					$package_options_color = array('templ' => 'temp9', 'ftdir' => 'center', 'colgap' => '0', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'ttwidth' => '220px', 'ctsize' => '24px');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#ed662f', '#ea2e2d', '#BE292D', '#993124'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'ftcolor' => array('#000000', '#000000', '#000000', '#000000'), 'fbrow1' => array('#ffffff', '#ececec', '#ffffff', '#ececec'), 'fbrow2' => array('#ececec', '#ffffff', '#ececec', '#ffffff'), 'btcolor' => array('#000000', '#000000', '#000000', '#000000'), 'bthover' => array('#000000', '#000000', '#000000', '#000000'), 'bcolor' => array('#ececec', '#ececec', '#ececec', '#ececec'), 'bhover' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'rtcolor' => array('#993124', '#993124', '#993124', '#993124'), 'rbcolor' => array('#f7db53', '#f7db53', '#f7db53', '#f7db53'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 10) {
					$package_options_color = array('templ' => 'temp10', 'colgap' => '1', 'bwidth' => '120px', 'bheight' => '36px', 'ftdir' => 'center', 'ttwidth' => '220px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details_color = array('tcolor' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'tbcolor' => array('#ededed', '#ededed', '#222F3D', '#ededed'), 'pcbig' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'ftcolor' => array('#222F3D', '#222F3D', '#ffffff', '#222F3D'), 'fbrow1' => array('#ffffff', '#ffffff', '#34495E', '#ffffff'), 'fbrow2' => array('#EBEBEB', '#EBEBEB', '#304357', '#EBEBEB'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#46627F', '#46627F', '#46627F', '#46627F'), 'bhover' => array('#222F3D', '#222F3D', '#222F3D', '#222F3D'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 11) {
					$package_options_color = array('templ' => 'temp11', 'ftdir' => 'center', 'ttwidth' => '200px', 'colshad' => 'no', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'colgap' => '0', 'ctsize' => '24px');
					$package_details_color = array('tcolor' => array('#9d9d9d', '#9d9d9d', '#9d9d9d', '#9d9d9d'), 'tbcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'pcbig' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'ftcolor' => array('#7d7d7d', '#7d7d7d', '#7d7d7d', '#7d7d7d'), 'fbrow1' => array('#e6e6e6', '#e2e2e2', '#e6e6e6', '#e2e2e2'), 'fbrow2' => array('#ffffff', '#f5f5f5', '#ffffff', '#f5f5f5'), 'btcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bthover' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bcolor' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'bhover' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 12) {
					$package_options_color = array('templ' => 'temp12', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details_color = array('tcolor' => array('#000000', '#000000', '#ffffff', '#000000'), 'tbcolor' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'pcbig' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'), 'ftcolor' => array('#868686', '#868686', '#fafafa', '#868686'), 'fbrow1' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'fbrow2' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'btcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#3e7de8', '#3e7de8', '#f2f2f2', '#3e7de8'), 'bhover' => array('#606060', '#606060', '#606060', '#606060'), 'rtcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'rbcolor' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 13) {
					$package_options_color = array('templ' => 'temp13', 'ftdir' => 'left', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details_color = array('tcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'tbcolor' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'pcbig' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'ftcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'fbrow1' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow2' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'bhover' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 14) {
					$package_options_color = array('templ' => 'temp14', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes', 'tick' => 'tick-2', 'cross' => 'cross-2');
					$package_details_color = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'tbcolor' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'ftcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'fbrow1' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'fbrow2' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'btcolor' => array('#292c3c', '#292c3c', '#ffffff', '#292c3c'), 'bthover' => array('#ffffff', '#ffffff', '#292c3c', '#ffffff'), 'bcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'), 'bhover' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 15) {
					$package_options_color = array('templ' => 'temp15', 'cscolor' => '#333333', 'cshcolor' => '#eeeeee');
					$package_details_color = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#24292e','#2f363d','#3b434a','#444c54'),'pcbig'=>array('#ffd33d','#ffd33d','#ffd33d','#ffd33d'),'fbrow1'=>array('#2c3136','#2c3136','#2c3136','#2c3136'),'fbrow2'=>array('#3b4046','#3b4046','#3b4046','#3b4046'),'ftcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#333333','#333333','#333333','#333333'),'bcolor'=>array('#28a745','#218838','#1e7e34','#1c7430'),'bhover'=>array('#218838','#1e7e34','#1c7430','#19692c'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#e36209','#e36209','#e36209','#e36209'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 16) {
					$package_options_color = array('templ' => 'temp16', 'cscolor' => '#004d40', 'cshcolor' => '#b2dfdb');
					$package_details_color = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#00695c','#00796b','#00897b','#009688'),'pcbig'=>array('#b2dfdb','#b2dfdb','#b2dfdb','#b2dfdb'),'fbrow1'=>array('#004d40','#004d40','#004d40','#004d40'),'fbrow2'=>array('#00695c','#00695c','#00695c','#00695c'),'ftcolor'=>array('#e0f2f1','#e0f2f1','#e0f2f1','#e0f2f1'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bcolor'=>array('#00796b','#00897b','#009688','#4db6ac'),'bhover'=>array('#004d40','#00695c','#00796b','#00897b'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#004d40','#004d40','#004d40','#004d40'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 17) {
					$package_options_color = array('templ' => 'temp17', 'cscolor' => '#3f51b5', 'cshcolor' => '#e8eaf6');
					$package_details_color = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#303f9f','#3949ab','#5c6bc0','#7986cb'),'pcbig'=>array('#c5cae9','#c5cae9','#c5cae9','#c5cae9'),'fbrow1'=>array('#e8eaf6','#e8eaf6','#e8eaf6','#e8eaf6'),'fbrow2'=>array('#c5cae9','#c5cae9','#c5cae9','#c5cae9'),'ftcolor'=>array('#1a237e','#1a237e','#1a237e','#1a237e'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#e8eaf6','#e8eaf6','#e8eaf6','#e8eaf6'),'bcolor'=>array('#303f9f','#3949ab','#5c6bc0','#7986cb'),'bhover'=>array('#1a237e','#283593','#3f51b5','#5c6bc0'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#1a237e','#1a237e','#1a237e','#1a237e'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 18) {
					$package_options_color = array('templ' => 'temp18', 'cscolor' => '#263238', 'cshcolor' => '#cfd8dc');
					$package_details_color = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#37474f','#455a64','#546e7a','#607d8b'),'pcbig'=>array('#b0bec5','#b0bec5','#b0bec5','#b0bec5'),'fbrow1'=>array('#eceff1','#eceff1','#eceff1','#eceff1'),'fbrow2'=>array('#cfd8dc','#cfd8dc','#cfd8dc','#cfd8dc'),'ftcolor'=>array('#263238','#263238','#263238','#263238'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#cfd8dc','#cfd8dc','#cfd8dc','#cfd8dc'),'bcolor'=>array('#37474f','#455a64','#546e7a','#607d8b'),'bhover'=>array('#263238','#37474f','#455a64','#546e7a'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#263238','#263238','#263238','#263238'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 19) {
					$package_options_color = array('templ' => 'temp19', 'cscolor' => '#9c27b0', 'cshcolor' => '#f3e5f5');
					$package_details_color = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#7b1fa2','#8e24aa','#9c27b0','#ab47bc'),'pcbig'=>array('#e1bee7','#e1bee7','#e1bee7','#e1bee7'),'fbrow1'=>array('#f3e5f5','#f3e5f5','#f3e5f5','#f3e5f5'),'fbrow2'=>array('#e1bee7','#e1bee7','#e1bee7','#e1bee7'),'ftcolor'=>array('#4a148c','#4a148c','#4a148c','#4a148c'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#f3e5f5','#f3e5f5','#f3e5f5','#f3e5f5'),'bcolor'=>array('#7b1fa2','#8e24aa','#9c27b0','#ab47bc'),'bhover'=>array('#6a1b9a','#7b1fa2','#8e24aa','#9c27b0'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#4a148c','#4a148c','#4a148c','#4a148c'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 20) {
					$package_options_color = array('templ' => 'temp20', 'ftdir' => 'center', 'ttwidth' => '220px', 'bwidth' => '140px', 'bheight' => '42px', 'colgap' => '1', 'cscolor' => '#fff3e0', 'cshcolor' => '#ffe0b2');
					$package_details_color = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#E65100','#EF6C00','#F57C00','#FB8C00'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#FFF3E0','#FFF3E0','#FFF3E0','#FFF3E0'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#E65100','#E65100','#E65100','#E65100'),'bcolor'=>array('#EF6C00','#EF6C00','#EF6C00','#EF6C00'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#5C6BC0','#5C6BC0','#5C6BC0','#5C6BC0'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} elseif ($template_number == 21) {
					$package_options_color = array( 'templ' => 'temp21', 'ftdir' => 'center', 'ttwidth' => '240px', 'bwidth' => '140px', 'bheight' => '44px', 'colgap' => '3', 'cscolor' => '#f5f5f5', 'cshcolor' => '#e0e0e0' );
					$package_details_color = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'fbrow2'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'ftcolor'=>array('#333333','#333333','#333333','#333333'),'btcolor'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'bthover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bhover'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF6D00','#FF6D00','#FF6D00','#FF6D00'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				} else {
					$package_options_color = array( 'templ' => 'temp0', 'ftdir' => 'left', 'ttwidth' => '200px', 'bwidth' => '135px', 'bheight' => '42px', 'cscolor' => '#e3f2fd', 'cshcolor' => '#bbdefb' );
					$package_details_color = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#1565C0','#0D47A1','#1976D2','#1E88E5'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#E3F2FD','#E3F2FD','#E3F2FD','#E3F2FD'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#0D47A1','#0D47A1','#0D47A1','#0D47A1'),'bcolor'=>array('#1976D2','#1976D2','#1976D2','#1976D2'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF5722','#FF5722','#FF5722','#FF5722'));
					$package_options = array_merge($package_options_check, $package_options_color);
					$package_details = array_merge($package_details_texts, $package_details_color);
				}
				/* Generating Package Features */
				foreach ($template_features as $key => $feature) {
					if ($feature) {
						$feature_name['fitem' . $fn] = sanitize_text_field($feature);
						$feature_name['ftype' . $fn] = sanitize_text_field($feature_type[$key]);
						$fn++;
					} else {
						$feature_name['fitem' . $fn] = '';
						$feature_name['ftype' . $fn] = '';
						$fn++;
					}
				}
				add_option($package_feature, $feature_name);
				/* Generating Package Options */
				foreach ($package_options as $key => $option) {
					$optionValue[$key] = sanitize_text_field($option);
				}
				add_option($table_option, $optionValue);
				/* Generating Package Lists */
				for ($pn = 0; $pn < 4; $pn++) {
					$package_lists = get_option($pricing_table);
					$optionName = $this->wrcpt_update_pricing_table($pricing_table, $package_lists);
					$package_count = get_option('packageCount');
					$new_package_lists = get_option($pricing_table);
					$packageOptions = explode(', ', $new_package_lists);
					$list_count = count($packageOptions);
					foreach ($package_details as $pkey => $value) {
						$packageOptions_text[$pkey] = sanitize_text_field($value[$pn]);
					}
					foreach ($feature_values as $fkey => $fvalue) {
						$featureValues_text[$fkey] = sanitize_text_field($fvalue[$pn]);
					}
					$package_details_top = array('pid' => $package_count, 'order' => $list_count);
					$mergePackages = array_merge($package_details_top, $packageOptions_text, $featureValues_text);
					add_option($optionName, $mergePackages);
				}
			}
		}

		/**
		 * Sets up an existing pricing table with settings from a selected template.
		 * 
		 * @return void
		 */
		public function wrcpt_setup_selected_template()
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
				$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])) : 'temp1';
				$table_name = isset($_POST['packtable']) ? sanitize_text_field(wp_unslash($_POST['packtable'])) : '';
				$option_name = $table_name . '_option';
				$table_option = get_option($option_name);
				$package_lists = get_option($table_name);
				$packageOptions = explode(', ', sanitize_text_field($package_lists));
				if ($template == 'temp1') {
					$package_options = array('templ' => 'temp1', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'bhover' => array('#64abcb', '#ecb000', '#9db74b', '#988fbb'), 'rtcolor' => array('#aa4518', '#aa4518', '#aa4518', '#aa4518'), 'rbcolor' => array('#faec00', '#faec00', '#faec00', '#faec00'));
				} elseif ($template == 'temp2') {
					$package_options = array('templ' => 'temp2', 'ftdir' => 'left', 'ttwidth' => '200px', 'bwidth' => '130px', 'bheight' => '40px', 'cscolor' => '#e8f5e9', 'cshcolor' => '#c8e6c9');
					$package_details = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#2E7D32','#1B5E20','#388E3C','#43A047'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#E8F5E9','#E8F5E9','#E8F5E9','#E8F5E9'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#1B5E20','#1B5E20','#1B5E20','#1B5E20'),'bcolor'=>array('#388E3C','#388E3C','#388E3C','#388E3C'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF6F00','#FF6F00','#FF6F00','#FF6F00'));
				} elseif ($template == 'temp3') {
					$package_options = array('templ' => 'temp3', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#6497b1', '#005b96', '#03396c', '#011f4b'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#03396c', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#174d80', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#ededed', '#333333'), 'btcolor' => array('#012345', '#012345', '#012345', '#012345'), 'bthover' => array('#012345', '#012345', '#012345', '#012345'), 'bcolor' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'bhover' => array('#b3cde0', '#b3cde0', '#b3cde0', '#b3cde0'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#a73d30', '#a73d30', '#a73d30', '#a73d30'));
				} elseif ($template == 'temp4') {
					$package_options = array('templ' => 'temp4', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'), 'bthover' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'bhover' => array('#6239B9', '#6239B9', '#6239B9', '#6239B9'), 'rtcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'rbcolor' => array('#FFCD00', '#FF8F45', '#9332CB', '#CC2162'));
				} elseif ($template == 'temp5') {
					$package_options = array('templ' => 'temp5', 'ttgrd' => 'yes', 'tick' => 'tick-7', 'cross' => 'cross-7', 'ftdir' => 'center', 'tbody' => '72px', 'pbody' => '200px', 'btbody' => '72px', 'bwidth' => '140px', 'bheight' => '40px', 'ttwidth' => '200px', 'psbig' => '80px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('spack' => array('no', 'no', 'yes', 'no'), 'tdesc' => array('Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet'), 'pdesc' => array('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'), 'tcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'tbcolor' => array('#1c1c1c', '#1c1c1c', '#1c1c1c', '#1c1c1c'), 'pcbig' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow1' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'fbrow2' => array('#f5f5f5', '#f5f5f5', '#f5f5f5', '#f5f5f5'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#ecb331', '#dd822b', '#c0765d', '#b15e43'), 'bhover' => array('#c89212', '#ba681b', '#b56044', '#914c35'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#ecb331', '#ecb331', '#d17e2d', '#d17e2d'));
				} elseif ($template == 'temp6') {
					$package_options = array('templ' => 'temp6', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#a1b901', '#01a0e1', '#ff5d02', '#ffb701'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'));
				} elseif ($template == 'temp7') {
					$package_options = array('templ' => 'temp7', 'ttgrd' => 'yes', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#B5A166', '#8A6A35', '#804C32', '#B1402A'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'fbrow2' => array('#FAFAFA', '#FAFAFA', '#804C32', '#FAFAFA'), 'ftcolor' => array('#333333', '#333333', '#FAFAFA', '#333333'), 'btcolor' => array('#333333', '#333333', '#333333', '#333333'), 'bthover' => array('#333333', '#333333', '#333333', '#333333'), 'bcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'bhover' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'), 'rtcolor' => array('#333333', '#333333', '#333333', '#333333'), 'rbcolor' => array('#ecf0e2', '#ecf0e2', '#ecf0e2', '#ecf0e2'));
				} elseif ($template == 'temp8') {
					$package_options = array('templ' => 'temp8', 'cscolor' => '#cccccc', 'cshcolor' => '#333333');
					$package_details = array('tcolor' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'tbcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'pcbig' => array('#3C4857', '#ffffff', '#3C4857', '#3C4857'), 'fbrow1' => array('#FAFAFA', '#FAFAFA', '#FAFAFA', '#FAFAFA'), 'fbrow2' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'ftcolor' => array('#333333', '#333333', '#333333', '#333333'), 'btcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'bcolor' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'bhover' => array('#04BACE', '#ffffff', '#04BACE', '#04BACE'), 'rtcolor' => array('#ffffff', '#04BACE', '#ffffff', '#ffffff'), 'rbcolor' => array('#04BACE', '#ecf0e2', '#04BACE', '#04BACE'));
				} elseif ($template == 'temp9') {
					$package_options = array('templ' => 'temp9', 'ftdir' => 'center', 'colgap' => '0', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'ttwidth' => '220px', 'ctsize' => '24px');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'tbcolor' => array('#ed662f', '#ea2e2d', '#BE292D', '#993124'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'), 'ftcolor' => array('#000000', '#000000', '#000000', '#000000'), 'fbrow1' => array('#ffffff', '#ececec', '#ffffff', '#ececec'), 'fbrow2' => array('#ececec', '#ffffff', '#ececec', '#ffffff'), 'btcolor' => array('#000000', '#000000', '#000000', '#000000'), 'bthover' => array('#000000', '#000000', '#000000', '#000000'), 'bcolor' => array('#ececec', '#ececec', '#ececec', '#ececec'), 'bhover' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'rtcolor' => array('#993124', '#993124', '#993124', '#993124'), 'rbcolor' => array('#f7db53', '#f7db53', '#f7db53', '#f7db53'));
				} elseif ($template == 'temp10') {
					$package_options = array('templ' => 'temp10', 'colgap' => '1', 'bwidth' => '120px', 'bheight' => '36px', 'ftdir' => 'center', 'ttwidth' => '220px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details = array('tcolor' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'tbcolor' => array('#ededed', '#ededed', '#222F3D', '#ededed'), 'pcbig' => array('#34495E', '#34495E', '#ffffff', '#34495E'), 'ftcolor' => array('#222F3D', '#222F3D', '#ffffff', '#222F3D'), 'fbrow1' => array('#ffffff', '#ffffff', '#34495E', '#ffffff'), 'fbrow2' => array('#EBEBEB', '#EBEBEB', '#304357', '#EBEBEB'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#46627F', '#46627F', '#46627F', '#46627F'), 'bhover' => array('#222F3D', '#222F3D', '#222F3D', '#222F3D'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100'));
				} elseif ($template == 'temp11') {
					$package_options = array('templ' => 'temp11', 'ftdir' => 'center', 'ttwidth' => '200px', 'colshad' => 'no', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ftcap' => 'yes', 'colgap' => '0', 'ctsize' => '24px');
					$package_details = array('tcolor' => array('#9d9d9d', '#9d9d9d', '#9d9d9d', '#9d9d9d'), 'tbcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'pcbig' => array('#5EC4CD', '#5EC4CD', '#5EC4CD', '#5EC4CD'), 'ftcolor' => array('#7d7d7d', '#7d7d7d', '#7d7d7d', '#7d7d7d'), 'fbrow1' => array('#e6e6e6', '#e2e2e2', '#e6e6e6', '#e2e2e2'), 'fbrow2' => array('#ffffff', '#f5f5f5', '#ffffff', '#f5f5f5'), 'btcolor' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bthover' => array('#2C2C2C', '#2C2C2C', '#2C2C2C', '#2C2C2C'), 'bcolor' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc'), 'bhover' => array('#eeeeee', '#eeeeee', '#eeeeee', '#eeeeee'), 'rtcolor' => array('#ff0000', '#ff0000', '#ff0000', '#ff0000'), 'rbcolor' => array('#ffc100', '#ffc100', '#ffc100', '#ffc100'));
				} elseif ($template == 'temp12') {
					$package_options = array('templ' => 'temp12', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details = array('tcolor' => array('#000000', '#000000', '#ffffff', '#000000'), 'tbcolor' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'pcbig' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'), 'ftcolor' => array('#868686', '#868686', '#fafafa', '#868686'), 'fbrow1' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'fbrow2' => array('#ffffff', '#ffffff', '#3e7de8', '#ffffff'), 'btcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#3e7de8', '#3e7de8', '#f2f2f2', '#3e7de8'), 'bhover' => array('#606060', '#606060', '#606060', '#606060'), 'rtcolor' => array('#ffffff', '#ffffff', '#000000', '#ffffff'), 'rbcolor' => array('#3e7de8', '#3e7de8', '#ffffff', '#3e7de8'));
				} elseif ($template == 'temp13') {
					$package_options = array('templ' => 'temp13', 'ftdir' => 'left', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes');
					$package_details = array('tcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'tbcolor' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'pcbig' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'ftcolor' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'fbrow1' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'fbrow2' => array('#f9f9f9', '#f9f9f9', '#f9f9f9', '#f9f9f9'), 'btcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bthover' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'bcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'), 'bhover' => array('#52586b', '#52586b', '#52586b', '#52586b'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FF5277', '#527BFF', '#FFB052', '#4FCA39'));
				} elseif ($template == 'temp14') {
					$package_options = array('templ' => 'temp14', 'ftdir' => 'center', 'ttwidth' => '250px', 'cscolor' => '#cccccc', 'cshcolor' => '#333333', 'ttgrd' => 'yes', 'tick' => 'tick-2', 'cross' => 'cross-2');
					$package_details = array('tcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'tbcolor' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'pcbig' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'ftcolor' => array('#FFFFFF', '#FFFFFF', '#292c3c', '#FFFFFF'), 'fbrow1' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'fbrow2' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'btcolor' => array('#292c3c', '#292c3c', '#ffffff', '#292c3c'), 'bthover' => array('#ffffff', '#ffffff', '#292c3c', '#ffffff'), 'bcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'), 'bhover' => array('#292c3c', '#292c3c', '#FFB052', '#292c3c'), 'rtcolor' => array('#ffffff', '#ffffff', '#ffffff', '#ffffff'), 'rbcolor' => array('#FFB052', '#FFB052', '#292c3c', '#FFB052'));
				} elseif ($template == 'temp15') {
					$package_options = array('templ' => 'temp15', 'cscolor' => '#333333', 'cshcolor' => '#eeeeee');
					$package_details = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#24292e','#2f363d','#3b434a','#444c54'),'pcbig'=>array('#ffd33d','#ffd33d','#ffd33d','#ffd33d'),'fbrow1'=>array('#2c3136','#2c3136','#2c3136','#2c3136'),'fbrow2'=>array('#3b4046','#3b4046','#3b4046','#3b4046'),'ftcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#333333','#333333','#333333','#333333'),'bcolor'=>array('#28a745','#218838','#1e7e34','#1c7430'),'bhover'=>array('#218838','#1e7e34','#1c7430','#19692c'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#e36209','#e36209','#e36209','#e36209'));
				} elseif ($template == 'temp16') {
					$package_options = array('templ' => 'temp16', 'cscolor' => '#004d40', 'cshcolor' => '#b2dfdb');
					$package_details = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#00695c','#00796b','#00897b','#009688'),'pcbig'=>array('#b2dfdb','#b2dfdb','#b2dfdb','#b2dfdb'),'fbrow1'=>array('#004d40','#004d40','#004d40','#004d40'),'fbrow2'=>array('#00695c','#00695c','#00695c','#00695c'),'ftcolor'=>array('#e0f2f1','#e0f2f1','#e0f2f1','#e0f2f1'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bcolor'=>array('#00796b','#00897b','#009688','#4db6ac'),'bhover'=>array('#004d40','#00695c','#00796b','#00897b'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#004d40','#004d40','#004d40','#004d40'));
				} elseif ($template == 'temp17') {
					$package_options = array('templ' => 'temp17', 'cscolor' => '#3f51b5', 'cshcolor' => '#e8eaf6');
					$package_details = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#303f9f','#3949ab','#5c6bc0','#7986cb'),'pcbig'=>array('#c5cae9','#c5cae9','#c5cae9','#c5cae9'),'fbrow1'=>array('#e8eaf6','#e8eaf6','#e8eaf6','#e8eaf6'),'fbrow2'=>array('#c5cae9','#c5cae9','#c5cae9','#c5cae9'),'ftcolor'=>array('#1a237e','#1a237e','#1a237e','#1a237e'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#e8eaf6','#e8eaf6','#e8eaf6','#e8eaf6'),'bcolor'=>array('#303f9f','#3949ab','#5c6bc0','#7986cb'),'bhover'=>array('#1a237e','#283593','#3f51b5','#5c6bc0'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#1a237e','#1a237e','#1a237e','#1a237e'));
				} elseif ($template == 'temp18') {
					$package_options = array('templ' => 'temp18', 'cscolor' => '#263238', 'cshcolor' => '#cfd8dc');
					$package_details = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#37474f','#455a64','#546e7a','#607d8b'),'pcbig'=>array('#b0bec5','#b0bec5','#b0bec5','#b0bec5'),'fbrow1'=>array('#eceff1','#eceff1','#eceff1','#eceff1'),'fbrow2'=>array('#cfd8dc','#cfd8dc','#cfd8dc','#cfd8dc'),'ftcolor'=>array('#263238','#263238','#263238','#263238'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#cfd8dc','#cfd8dc','#cfd8dc','#cfd8dc'),'bcolor'=>array('#37474f','#455a64','#546e7a','#607d8b'),'bhover'=>array('#263238','#37474f','#455a64','#546e7a'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#263238','#263238','#263238','#263238'));
				} elseif ($template == 'temp19') {
					$package_options = array('templ' => 'temp19', 'cscolor' => '#9c27b0', 'cshcolor' => '#f3e5f5');
					$package_details = array('tcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'tbcolor'=>array('#7b1fa2','#8e24aa','#9c27b0','#ab47bc'),'pcbig'=>array('#e1bee7','#e1bee7','#e1bee7','#e1bee7'),'fbrow1'=>array('#f3e5f5','#f3e5f5','#f3e5f5','#f3e5f5'),'fbrow2'=>array('#e1bee7','#e1bee7','#e1bee7','#e1bee7'),'ftcolor'=>array('#4a148c','#4a148c','#4a148c','#4a148c'),'btcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'bthover'=>array('#f3e5f5','#f3e5f5','#f3e5f5','#f3e5f5'),'bcolor'=>array('#7b1fa2','#8e24aa','#9c27b0','#ab47bc'),'bhover'=>array('#6a1b9a','#7b1fa2','#8e24aa','#9c27b0'),'rtcolor'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'rbcolor'=>array('#4a148c','#4a148c','#4a148c','#4a148c'));
				} elseif ($template == 'temp20') {
					$package_options = array('templ' => 'temp20', 'ftdir' => 'center', 'ttwidth' => '220px', 'bwidth' => '140px', 'bheight' => '42px', 'colgap' => '1', 'cscolor' => '#fff3e0', 'cshcolor' => '#ffe0b2');
					$package_details = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#E65100','#EF6C00','#F57C00','#FB8C00'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#FFF3E0','#FFF3E0','#FFF3E0','#FFF3E0'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#E65100','#E65100','#E65100','#E65100'),'bcolor'=>array('#EF6C00','#EF6C00','#EF6C00','#EF6C00'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#5C6BC0','#5C6BC0','#5C6BC0','#5C6BC0'));
				} elseif ($template == 'temp21') {
					$package_options = array( 'templ' => 'temp21', 'ftdir' => 'center', 'ttwidth' => '240px', 'bwidth' => '140px', 'bheight' => '44px', 'colgap' => '3', 'cscolor' => '#f5f5f5', 'cshcolor' => '#e0e0e0' );
					$package_details = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'fbrow2'=>array('#ffffff','#ffffff','#ffffff','#ffffff'),'ftcolor'=>array('#333333','#333333','#333333','#333333'),'btcolor'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'bthover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bhover'=>array('#4285F4','#EA4335','#FBBC05','#34A853'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF6D00','#FF6D00','#FF6D00','#FF6D00'));
				} else {
					$package_options = array( 'templ' => 'temp0', 'ftdir' => 'left', 'ttwidth' => '200px', 'bwidth' => '135px', 'bheight' => '42px', 'cscolor' => '#e3f2fd', 'cshcolor' => '#bbdefb' );
					$package_details = array('tcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'tbcolor'=>array('#1565C0','#0D47A1','#1976D2','#1E88E5'),'pcbig'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'fbrow1'=>array('#E3F2FD','#E3F2FD','#E3F2FD','#E3F2FD'),'fbrow2'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'ftcolor'=>array('#212121','#212121','#212121','#212121'),'btcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'bthover'=>array('#0D47A1','#0D47A1','#0D47A1','#0D47A1'),'bcolor'=>array('#1976D2','#1976D2','#1976D2','#1976D2'),'bhover'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rtcolor'=>array('#FFFFFF','#FFFFFF','#FFFFFF','#FFFFFF'),'rbcolor'=>array('#FF5722','#FF5722','#FF5722','#FF5722'));
				}
				foreach ($packageOptions as $key => $option) {
					$package_value = get_option($option);
					foreach ($package_details as $pkey => $value) {
						$packageValues_text[$pkey] = sanitize_text_field($value[$key]);
					}
					$mergePackages = array_merge($package_value, $packageValues_text);
					update_option($option, $mergePackages);
				}
				$mergeOptions = array_merge($table_option, $package_options);
				update_option($option_name, $mergeOptions);
			}
		}
	}
}

WRCPT_Template_Options::get_instances();
