<?php
/**
 * Class WRCPT_Process_Shortcode
 * 
 * Handles rendering of the [wrc-pricing-table] shortcode output.
 * Generates the complete HTML markup, inline CSS, and responsive layout
 * for a pricing table based on stored options and settings.
 * 
 * In the tablet view, the pricing table will be divided into two columns.
 * In the mobile view, each column of the price table will be displayed below each other.
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Process_Shortcode')) {
    class WRCPT_Process_Shortcode
    {
        private static $instance;
		private $get_functions;

        public function __construct()
        {
			// Access the Functions
			$this->get_functions = WRCPT_Init_Functions::get_instances();
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
		 * Renders the complete pricing table shortcode output.
		 * 
		 * @param mixed $p_table	Table slug/name.
		 * @param mixed $tableID	Unique table ID used in CSS selectors.
		 * @param mixed $p_feature	Feature list (name => type).
		 * @param mixed $p_combine	Table-wide settings (width, columns, shadows, etc.).
		 * @param mixed $tot_feat	Total number of features.
		 * @param mixed $p_lists	List of active column option keys.
		 * @param mixed $p_options	Column option values.
		 * @param mixed $p_count	Number of columns.
		 * @param mixed $flag		Flag indicating whether any column is active.
		 * 
		 * @return void
		 */
		public function wrcpt_process_shortcode( $p_table, $tableID, $p_feature, $p_combine, $tot_feat, $p_lists, $p_options, $p_count, $flag ) {
			$i = 1; $j = 0;

			/**
			 * Preparing structural settings of the different parts of the table.
			 */
			// Define default configuration
			$defaults = [
				'cwidth'   => 100,      // Container width (%)
				'maxcol'   => 4,        // Maximum columns
				'tbody'    => 62,       // Title box height (px)
				'pbody'    => 120,      // Price box height (px)
				'btbody'   => 60,       // Button box height (px)
				'bwidth'   => 120,      // Button width (for padding calculation)
				'capwidth' => 18.73,    // Caption column width (%)
				'colgap'   => 0,        // Column gap (px)
				'colshad'  => 'yes',    // Column shadow: yes/no
				'cscolor'  => '#cccccc' // Shadow color
			];

			// Merge saved options with defaults (saved values override defaults)
			$config = array_merge($defaults, array_filter((array)$p_combine, fn($value) => $value !== '' && $value !== null));

			// Extract values with type casting and sanitization
			$container_width     = (int)    $config['cwidth'];
			$maximum_column      = (int)    $config['maxcol'];
			$title_box_height    = (int)    $config['tbody'];
			$price_box_height    = (int)    $config['pbody'];
			$button_box_height   = (int)    $config['btbody'];
			$caption_width       = (float)  $config['capwidth'];
			$margin              = (int)    $config['colgap'];
			$column_shadow       = sanitize_text_field($config['colshad']);
			$shadow_color        = sanitize_hex_color($config['cscolor'] ?? '#cccccc');

			// Special case: Button padding based on button width
			$button_min_width    = 120;
			$button_padding      = ((int)$config['bwidth'] >= $button_min_width)
				? (int)$config['bwidth'] * 0.0833
				: 10;
		
			/* Calculating responsive width of the pricing table for both tablet and mobile view.
			 * Here, we are calculating responsive width both for caption column and package column.
			 */
			if($p_combine) {
				if($p_combine['autocol'] == 'no') {
					if($p_count > $maximum_column) {
						$width = ($container_width)/$maximum_column . '%';
						$cap_width = ($container_width)/($maximum_column+1) . '%';
					} else {
						$width = ($container_width)/$p_count . '%';
						$cap_width = ($container_width)/($p_count+1) . '%';
					}
				} else {
					if($p_combine['ftcap'] == "yes") {
						$single_column_width = ((100 - $caption_width) - ($margin * (12-1)))/12;
					} else {
						$single_column_width = (100 - ($margin * (12-1)))/12;
					}
					$width = ($single_column_width * (12 / $maximum_column)) + ($margin * ((12 / $maximum_column) - 1)) . '%';
					$cap_width = $caption_width . '%';
				}
				$tab_width = (((100 - ($margin * (12-1)))/12) * (12 / 2)) + ($margin * ((12 / 2) - 1)) . '%';
				$mob_width = ((100/12 * (12 / 1))) . '%';
			}
		
			/* Start making the shortcode */
			if(!empty($p_lists) && $p_combine['enable'] == 'yes' && $flag == 1) { ?>
				<div id="<?php echo esc_attr($tableID); ?>" class="wrcpt_content wrcpt_container">
					<div class="wrc_pricing_table wrcpt_row">
<style type="text/css">
/* Responsive View */
@media only screen and (max-width: 480px) {div#<?php echo esc_attr($tableID); ?> div.package_details {width: <?php echo esc_attr($mob_width); ?>;margin-top: 40px !important}}
@media only screen and (min-width: 481px) and (max-width: 1023px) {div#<?php echo esc_attr($tableID); ?> div.package_details {width: <?php echo esc_attr($tab_width); ?>}<?php if($p_combine['ftcap'] == "yes") { ?>div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+1) span.text_tooltip:hover:after,div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+1) span.icon_tooltip:hover:after{left:auto!important;right:-30px!important}<?php } else { ?>div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+2) span.text_tooltip:hover:after,div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+2) span.icon_tooltip:hover:after{left:auto!important;right:-30px!important}<?php } ?>}
<?php if($p_combine['ftcap'] != "yes") { ?>
<?php if($p_combine['autocol'] == 'yes') { ?>
@media only screen and (min-width: 481px) and (max-width: 1023px) {div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+1) {margin-right:<?php echo esc_attr($margin); ?>%}}
<?php } ?>
@media screen and (min-width: 1024px) {div#<?php echo esc_attr($tableID); ?> div.package_details {<?php if($p_combine['autocol'] == 'yes') { ?>margin-right:<?php echo esc_attr($margin); ?>%;<?php } ?>width: <?php echo esc_attr($width); ?>}div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(<?php echo esc_attr($maximum_column); ?>n+<?php echo esc_attr($maximum_column); ?>) {margin-right: 0}}
<?php } else { ?>
<?php if($p_combine['autocol'] == 'yes') { ?>
@media only screen and (min-width : 481px) and (max-width: 1023px) {div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(2n+2) {margin-right:<?php echo esc_attr($margin); ?>%}}
<?php } ?>
@media screen and (min-width: 1024px) {
div#<?php echo esc_attr($tableID); ?> div.package_caption {width: <?php echo esc_attr($cap_width); ?>}
<?php if($p_combine['ctsize']) { ?>div.wrcpt_content h3.caption {font-size: <?php echo esc_attr($p_combine['ctsize']); ?>}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_details {<?php if($p_combine['autocol'] == 'yes') { ?>width: <?php echo esc_attr($width); ?>;margin-right:<?php echo esc_attr($margin); ?>%;<?php } else { ?>width: <?php echo esc_attr($cap_width); ?>;<?php } ?>}
div#<?php echo esc_attr($tableID); ?> div.package_details:nth-of-type(<?php echo esc_attr($maximum_column + 1); ?>n+<?php echo esc_attr($maximum_column + 1); ?>) {margin-right: 0}
<?php if($p_combine['tbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_caption li.pricing_table_title {height: <?php echo esc_attr($p_combine['tbody']); ?>;}<?php } ?>
<?php if($p_combine['pbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_caption li.pricing_table_plan {height: <?php echo esc_attr($p_combine['pbody']); ?>;line-height: <?php echo esc_attr($p_combine['pbody']); ?>;}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_2 span.caption, div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_3 span.caption, div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_2 span.cap_tooltip, div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_3 span.cap_tooltip, div#<?php echo esc_attr($tableID); ?> div.package_details li.feature_style_1 span {<?php if($p_combine['cftsize']) { ?>font-size: <?php echo esc_attr($p_combine['cftsize']); ?>;<?php } ?>}
<?php if($p_combine['ftbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_2, div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_3 {height: <?php echo esc_attr($p_combine['ftbody']); ?>;line-height: <?php echo esc_attr($p_combine['ftbody']); ?>;}<?php } ?>
}
<?php } ?>
/* End of responsive view */
<?php if($p_combine['cwidth']) { ?>div#<?php echo esc_attr($tableID); ?> {width:<?php echo esc_attr($container_width); ?>%}<?php } ?>
<?php if($column_shadow == 'yes') { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details {-moz-box-shadow: 0px 0px 3px <?php echo esc_attr($shadow_color); ?>;-webkit-box-shadow: 0px 0px 3px <?php echo esc_attr($shadow_color); ?>;filter: progid:DXImageTransform.Microsoft.Shadow(Strength=0, Direction=0, Color='<?php echo esc_attr($shadow_color); ?>');-ms-filter: progid:DXImageTransform.Microsoft.Shadow(Strength=0, Direction=0, Color='<?php echo esc_attr($shadow_color); ?>');box-shadow: 0px 0px 3px <?php echo esc_attr($shadow_color); ?>}
div#<?php echo esc_attr($tableID); ?> div.package_details:hover {-moz-box-shadow: 0px 0px 3px <?php echo esc_attr($p_combine['cshcolor']); ?>;-webkit-box-shadow: 0px 0px 3px <?php echo esc_attr($p_combine['cshcolor']); ?>;filter: progid:DXImageTransform.Microsoft.Shadow(Strength=0, Direction=0, Color='<?php echo esc_attr($p_combine['cshcolor']); ?>');-ms-filter: progid:DXImageTransform.Microsoft.Shadow(Strength=0, Direction=0, Color='<?php echo esc_attr($p_combine['cshcolor']); ?>');box-shadow: 0px 0px 3px <?php echo esc_attr($p_combine['cshcolor']); ?>}
<?php } ?>
<?php if($p_combine['encol'] != "yes") { ?>div#<?php echo esc_attr($tableID); ?> div.package_details:hover {-moz-transform: none;-webkit-transform: none;-o-transform: none;-ms-transform: none;transform: none}<?php } ?>
<?php if($p_combine['dscol'] == "yes") { ?>div#<?php echo esc_attr($tableID); ?> div.package_details:hover {box-shadow: none;}<?php } ?>
<?php if($p_combine['ttwidth']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_caption span.cap_tooltip:hover:after, div#<?php echo esc_attr($tableID); ?> div.package_details span.text_tooltip:hover:after, div#<?php echo esc_attr($tableID); ?> div.package_details span.icon_tooltip:hover:after {width: <?php echo esc_attr($p_combine['ttwidth']); ?>}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_2, div#<?php echo esc_attr($tableID); ?> div.package_caption li.feature_style_3 {<?php if($p_combine['entips'] == 'yes') { ?>padding-right: 25px;<?php } ?>}
<?php /* Tick Icon */ ?>
<?php if($p_combine['tick'] == "tick-10") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -340px -41px}
<?php } elseif($p_combine['tick'] == "tick-9") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -305px -41px}
<?php } elseif($p_combine['tick'] == "tick-8") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -270px -41px}
<?php } elseif($p_combine['tick'] == "tick-7") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -235px -42px}
<?php } elseif($p_combine['tick'] == "tick-6") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -200px -40px}
<?php } elseif($p_combine['tick'] == "tick-5") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -129px -40px}
<?php } elseif($p_combine['tick'] == "tick-4") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -165px -42px}
<?php } elseif($p_combine['tick'] == "tick-3") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -95px -40px}
<?php } elseif($p_combine['tick'] == "tick-2") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -60px -41px}
<?php } else { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_yes:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -24px -41px}
<?php } ?>
<?php /* Cross Icon */ ?>
<?php if($p_combine['cross'] == "cross-10") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -340px -91px}
<?php } elseif($p_combine['cross'] == "cross-9") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -305px -91px}
<?php } elseif($p_combine['cross'] == "cross-8") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -270px -91px}
<?php } elseif($p_combine['cross'] == "cross-7") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -234px -91px}
<?php } elseif($p_combine['cross'] == "cross-6") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -200px -91px}
<?php } elseif($p_combine['cross'] == "cross-5") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -165px -91px}
<?php } elseif($p_combine['cross'] == "cross-4") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -129px -91px}
<?php } elseif($p_combine['cross'] == "cross-3") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -95px -91px}
<?php } elseif($p_combine['cross'] == "cross-2") { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -59px -90px}
<?php } else { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details span.feature_no:before {background: url(<?php echo esc_url(WRCPT_PLUGIN_URL . 'assets/images/wrcpt-buttons.png'); ?>) no-repeat -24px -91px}
<?php } ?>
</style><?php
						foreach($p_options as $key => $value) {
							$packageType = get_option($value);
							if(isset($packageType['pdisp'])) { $prdisp = sanitize_text_field($packageType['pdisp']); }
							else { $prdisp = 'show'; }
							if(($j < 1 || $j % $maximum_column == 0) && $j <= $p_count) {
								$i = 1;
								if($p_combine['ftcap'] == "yes" && $prdisp == "show") { ?>
									<div class="package_caption">
										<ul>
											<li class="pricing_table_title"></li>
											<li class="pricing_table_plan">
												<h3 class="caption"><?php echo esc_html(ucwords(str_replace('_', ' ', sanitize_text_field($p_table)))); ?></h3>
											</li><?php
											for($tf = 1; $tf <= $tot_feat; $tf++) {
												if($i % 2 == 0) {
													if($i == $tot_feat) { ?>
														<li class="feature_style_2 bottom_left"><div class="caption_lists"><span class="caption"><?php echo esc_attr($p_feature['fitem'.$tf]); ?></span></div></li><?php
													} else { ?>
														<li class="feature_style_2"><div class="caption_lists"><span class="caption"><?php echo esc_attr($p_feature['fitem'.$tf]); ?></span></div></li><?php
													}
												} else {
													if($i == 1) { ?>
														<li class="feature_style_3 top_left"><div class="caption_lists"><span class="caption"><?php echo esc_attr($p_feature['fitem'.$tf]); ?></span></div></li><?php
													} elseif($i == $tot_feat) { ?>
														<li class="feature_style_3 bottom_left"><div class="caption_lists"><span class="caption"><?php echo esc_attr($p_feature['fitem'.$tf]); ?></span></div></li><?php
													} else { ?>
														<li class="feature_style_3"><div class="caption_lists"><span class="caption"><?php echo esc_attr($p_feature['fitem'.$tf]); ?></span></div></li><?php
													}
												} $i++;
											} ?>
										</ul>
									</div><?php
								}
							}
							if($p_combine['ttgrd'] == 'yes') {
							$tlight = $this->get_functions->wrcpt_adjustBrightness($packageType['tbcolor'], 0); }
							else {
							$tlight = $this->get_functions->wrcpt_adjustBrightness($packageType['tbcolor'], 50); }
							$p_color = $packageType['tbcolor'];
							$pdark = $this->get_functions->wrcpt_adjustBrightness($packageType['tbcolor'], 20);
							$blight = $this->get_functions->wrcpt_adjustBrightness($packageType['bcolor'], 50);
							// $bdark = $this->get_functions->wrcpt_adjustBrightness($packageType['bhover'], 20);
							$bhlight = $this->get_functions->wrcpt_adjustBrightness($packageType['bhover'], 50);
							$rlight = $this->get_functions->wrcpt_adjustBrightness($packageType['rbcolor'], 80);
							$i = 1;
							$pid = absint( $packageType['pid'] ); ?>
<style type="text/css">
<?php if($p_combine['tsize'] || $packageType['tcolor']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details h3.txcolor-<?php echo esc_attr($pid); ?> {<?php if($p_combine['tsize']) { ?>font-size: <?php echo esc_attr($p_combine['tsize']); ?>;<?php } ?><?php if($packageType['tcolor']) { ?>color: <?php echo esc_attr($packageType['tcolor']); ?>;<?php } ?>}
<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.color-<?php echo esc_attr($pid); ?> {background: -moz-linear-gradient(<?php echo esc_attr($tlight); ?>, <?php echo esc_attr($packageType['tbcolor']); ?>);background: -webkit-gradient(linear, center top, center bottom, from(<?php echo esc_attr($tlight); ?>), to(<?php echo esc_attr($packageType['tbcolor']); ?>));background: -webkit-linear-gradient(<?php echo esc_attr($tlight); ?>, <?php echo esc_attr($packageType['tbcolor']); ?>);background: -o-linear-gradient(<?php echo esc_attr($tlight); ?>, <?php echo esc_attr($packageType['tbcolor']); ?>);background: -ms-linear-gradient(<?php echo esc_attr($tlight); ?>, <?php echo esc_attr($packageType['tbcolor']); ?>);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo esc_attr($tlight); ?>', endColorstr='<?php echo esc_attr($packageType['tbcolor']); ?>',GradientType=1);-ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo esc_attr($tlight); ?>', endColorstr='<?php echo esc_attr($packageType['tbcolor']); ?>',GradientType=1);background: linear-gradient(<?php echo esc_attr($tlight); ?>, <?php echo esc_attr($packageType['tbcolor']); ?>);<?php if($p_combine['tbody']) { ?>height: <?php echo esc_attr($p_combine['tbody']); ?>;line-height: <?php echo esc_attr($p_combine['tbody']); ?>;<?php } ?>}
<?php if($p_combine['psbig'] || $packageType['pcbig']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details h2.txcolor-<?php echo esc_attr($pid); ?> {<?php if($p_combine['psbig']) { ?>font-size: <?php echo esc_attr($p_combine['psbig']); ?>;<?php } ?><?php if($packageType['pcbig']) { ?>color: <?php echo esc_attr($packageType['pcbig']); ?>;<?php } ?>}div#<?php echo esc_attr($tableID); ?> div.package_details h2.txcolor-<?php echo esc_attr($pid); ?> span.price {<?php if($p_combine['psbig']) { ?>font-size: <?php echo esc_attr($p_combine['psbig']); ?>;<?php } ?><?php if($packageType['pcbig']) { ?>color: <?php echo esc_attr($packageType['pcbig']); ?>;<?php } ?>}
<?php } ?>
<?php if($p_combine['pssmall'] || $packageType['pcbig']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details h2.txcolor-<?php echo esc_attr($pid); ?> span.unit, div#<?php echo esc_attr($tableID); ?> div.package_details h2.txcolor-<?php echo esc_attr($pid); ?> span.cent {<?php if($p_combine['pssmall']) { ?>font-size: <?php echo esc_attr($p_combine['pssmall']); ?>;<?php } ?><?php if($packageType['pcbig']) { ?>color: <?php echo esc_attr($packageType['pcbig']); ?>;<?php } ?>}
<?php } ?>
<?php if($p_combine['purgt'] == 'yes') { ?>div#<?php echo esc_attr($tableID); ?> div.package_details h2.txcolor-<?php echo esc_attr($pid); ?> span.unit{position: absolute;right: 20px}<?php } ?>
<?php if($p_combine['pbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_details li.plan-<?php echo esc_attr($pid); ?> {height: <?php echo esc_attr($p_combine['pbody']); ?>;line-height: <?php echo esc_attr($p_combine['pbody']); ?>}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.plan-<?php echo esc_attr($pid); ?>, div#<?php echo esc_attr($tableID); ?> div.package_details li.bbcolor-<?php echo esc_attr($pid); ?> {background: -moz-linear-gradient(<?php echo esc_attr($pdark); ?>, <?php echo esc_attr($p_color); ?>);background: -webkit-gradient(linear, center top, center bottom, from(<?php echo esc_attr($pdark); ?>), to(<?php echo esc_attr($p_color); ?>));background: -webkit-linear-gradient(<?php echo esc_attr($pdark); ?>, <?php echo esc_attr($p_color); ?>);background: -o-linear-gradient(<?php echo esc_attr($pdark); ?>, <?php echo esc_attr($p_color); ?>);background: -ms-linear-gradient(<?php echo esc_attr($pdark); ?>, <?php echo esc_attr($p_color); ?>);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($pdark); ?>', endColorstr='<?php echo esc_attr($p_color); ?>',GradientType=1 );-ms-filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($pdark); ?>', endColorstr='<?php echo esc_attr($p_color); ?>',GradientType=1 );background: linear-gradient(<?php echo esc_attr($pdark); ?>, <?php echo esc_attr($p_color); ?>);}
<?php if($p_combine['btbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_details li.bbcolor-<?php echo esc_attr($pid); ?> {height:<?php echo esc_attr($button_box_height); ?>;line-height: 100%}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.button-<?php echo esc_attr($pid); ?> .action_button {background: -moz-linear-gradient(<?php echo esc_attr($blight); ?>, <?php echo esc_attr($packageType['bcolor']); ?>);background: -webkit-gradient(linear, center top, center bottom, from(<?php echo esc_attr($blight); ?>), to(<?php echo esc_attr($packageType['bcolor']); ?>));background: -webkit-linear-gradient(<?php echo esc_attr($blight); ?>, <?php echo esc_attr($packageType['bcolor']); ?>);background: -o-linear-gradient(<?php echo esc_attr($blight); ?>, <?php echo esc_attr($packageType['bcolor']); ?>);background: -ms-linear-gradient(<?php echo esc_attr($blight); ?>, <?php echo esc_attr($packageType['bcolor']); ?>);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($blight); ?>', endColorstr='<?php echo esc_attr($packageType['bcolor']); ?>',GradientType=1 );-ms-filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($blight); ?>', endColorstr='<?php echo esc_attr($packageType['bcolor']); ?>',GradientType=1 );background: linear-gradient(<?php echo esc_attr($blight); ?>, <?php echo esc_attr($packageType['bcolor']); ?>);border:1px solid <?php echo esc_attr($packageType['bcolor']); ?>;<?php if($p_combine['btsize']) { ?>font-size: <?php echo esc_attr($p_combine['btsize']); ?>;<?php } ?><?php if($packageType['btcolor']) { ?>color: <?php echo esc_attr($packageType['btcolor']); ?>;<?php } ?><?php if($p_combine['bheight']) { ?>height:<?php echo esc_attr($p_combine['bheight']); ?>;line-height:<?php echo esc_attr($p_combine['bheight']); ?>;<?php } ?><?php if($p_combine['bwidth']) { ?>width:<?php echo esc_attr($p_combine['bwidth']); ?>;padding:0 <?php echo esc_attr(round($button_padding)); ?>px<?php } ?>}
div#<?php echo esc_attr($tableID); ?> div.package_details li.button-<?php echo esc_attr($pid); ?> .action_button:hover {background: -moz-linear-gradient(<?php echo esc_attr($bhlight); ?>, <?php echo esc_attr($packageType['bhover']); ?>);background: -webkit-gradient(linear, center top, center bottom, from(<?php echo esc_attr($bhlight); ?>), to(<?php echo esc_attr($packageType['bhover']); ?>));background: -webkit-linear-gradient(<?php echo esc_attr($bhlight); ?>, <?php echo esc_attr($packageType['bhover']); ?>);background: -o-linear-gradient(<?php echo esc_attr($bhlight); ?>, <?php echo esc_attr($packageType['bhover']); ?>);background: -ms-linear-gradient(<?php echo esc_attr($bhlight); ?>, <?php echo esc_attr($packageType['bhover']); ?>);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($bhlight); ?>', endColorstr='<?php echo esc_attr($packageType['bhover']); ?>',GradientType=1 );-ms-filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($bhlight); ?>', endColorstr='<?php echo esc_attr($packageType['bhover']); ?>',GradientType=1 );background: linear-gradient(<?php echo esc_attr($bhlight); ?>, <?php echo esc_attr($packageType['bhover']); ?>);border:1px solid <?php echo esc_attr($packageType['bhover']); ?>;color: <?php echo esc_attr($packageType['bthover']); ?>;}
div#<?php echo esc_attr($tableID); ?> div.package_details li.button-<?php echo esc_attr($pid); ?> .action_button span {<?php if($packageType['btcolor']) { ?>color: <?php echo esc_attr($packageType['btcolor']); ?>;<?php } ?>}

div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?>, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> a.wrc_tooltip, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> div.feat_cap, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.text_tooltip, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.icon_tooltip, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.fa-icon, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.hide-fa-icon {<?php if($p_combine['ftsize']) { ?>font-size: <?php echo esc_attr($p_combine['ftsize']); ?>;<?php } ?>}
div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.feat_value, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span.media_screen {<?php if($p_combine['cftsize']) { ?>font-size: <?php echo esc_attr($p_combine['cftsize']); ?>;<?php } ?>}
<?php if($p_combine['ftbody']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> {height: <?php echo esc_attr($p_combine['ftbody']); ?>;line-height: <?php echo esc_attr($p_combine['ftbody']); ?>}<?php } ?>
<?php if($p_combine['ftcap'] == "yes") { ?>div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> {padding:0 5px 0 20px}<?php }?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> {text-align:<?php echo esc_attr($p_combine['ftdir']); ?>}
<?php /* Feature Font Color */ ?>
<?php if($packageType['ftcolor']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?>, div#<?php echo esc_attr($tableID); ?> div.package_details li.ftcolor-<?php echo esc_attr($pid); ?> span {color: <?php echo esc_attr($packageType['ftcolor']); ?>}
<?php } ?>
<?php /* Feature Background Color even */ ?>
<?php if($packageType['fbrow2']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.rowcolor-<?php echo esc_attr($pid); ?> {background-color: <?php echo esc_attr($packageType['fbrow2']); ?>;}
<?php } ?>
<?php /* Feature Background Color odd */ ?>
<?php if($packageType['fbrow1']) { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li.altrowcolor-<?php echo esc_attr($pid); ?> {background-color: <?php echo esc_attr($packageType['fbrow1']); ?>;}
<?php } ?>
<?php if($p_combine['enribs'] == 'yes') { ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li .ribbon_color-<?php echo esc_attr($pid); ?>{top:<?php echo esc_attr($title_box_height + 1); ?>px}
<?php if($p_combine['rtsize']) { ?>div#<?php echo esc_attr($tableID); ?> div.package_details li .ribbon_color-<?php echo esc_attr($pid); ?> {font-size: <?php echo esc_attr($p_combine['rtsize']); ?>}<?php } ?>
div#<?php echo esc_attr($tableID); ?> div.package_details li .ribbon_color-<?php echo esc_attr($pid); ?> a {background: <?php echo esc_attr($packageType['rbcolor']); ?>;background: -moz-linear-gradient(left, <?php echo esc_attr($rlight); ?>, <?php echo esc_attr($packageType['rbcolor']); ?>);background: -webkit-linear-gradient(left, <?php echo esc_attr($rlight); ?>, <?php echo esc_attr($packageType['rbcolor']); ?>);background: -o-linear-gradient(left, <?php echo esc_attr($rlight); ?>, <?php echo esc_attr($packageType['rbcolor']); ?>);background: -ms-linear-gradient(left, <?php echo esc_attr($rlight); ?>, <?php echo esc_attr($packageType['rbcolor']); ?>);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr($rlight); ?>', endColorstr='<?php echo esc_attr($packageType['rbcolor']); ?>',GradientType=1 );-ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo esc_attr($rlight); ?>', endColorstr='<?php echo esc_attr($packageType['rbcolor']); ?>',GradientType=1);background: linear-gradient(to right, <?php echo esc_attr($rlight); ?>, <?php echo esc_attr($packageType['rbcolor']); ?>);color: <?php echo esc_attr($packageType['rtcolor']); ?>}
div#<?php echo esc_attr($tableID); ?> div.package_details li .ribbon_color-<?php echo esc_attr($pid); ?> a:before {border-color: transparent <?php echo esc_attr($rlight); ?> transparent transparent}
div#<?php echo esc_attr($tableID); ?> div.package_details li .ribbon_color-<?php echo esc_attr($pid); ?> a:after {border-top: 8px solid rgba(0,0,0,.5)}
<?php } ?>
<?php if($packageType['spack'] == 'yes') { ?>
	div#<?php echo esc_attr($tableID); ?> div.special-package li.plan-<?php echo esc_attr($pid); ?> {height: <?php echo esc_attr($price_box_height+20); ?>px !important;line-height: <?php echo esc_attr($price_box_height+20); ?>px !important}
	div#<?php echo esc_attr($tableID); ?> div.special-package li.pricing_table_button {height:<?php echo esc_attr($button_box_height+10); ?>px !important;line-height:<?php echo esc_attr($button_box_height-3); ?>px !important;padding-top: 5px}
	div#<?php echo esc_attr($tableID); ?> div.special-package {z-index: 100;margin-top: 0;}
	div#<?php echo esc_attr($tableID); ?> div.special-package li.pricing_table_title {height: <?php echo esc_attr($title_box_height+20); ?>px !important;line-height: <?php echo esc_attr($title_box_height+20); ?>px !important}
	<?php if($p_combine['btbody'] == '') { ?>
	div#<?php echo esc_attr($tableID); ?> div.special-package li.pricing_table_button {height:80px !important;line-height:80px !important}
	<?php } else { ?>
	div#<?php echo esc_attr($tableID); ?> div.special-package li.pricing_table_button {height:<?php echo esc_attr($button_box_height+20); ?>px !important;line-height:<?php echo esc_attr($button_box_height+20); ?>px !important}
	<?php } ?>
	<?php if($p_combine['enribs'] == 'yes') { ?>
		div#<?php echo esc_attr($tableID); ?> div.special-package li .wrc-ribbon {top:<?php echo esc_attr($title_box_height+21); ?>px}
	<?php } ?>
<?php } ?>
</style>
							<?php if($prdisp == 'show') { ?>
							<div class="package_details<?php echo ' package-'.esc_attr($pid); ?><?php if(isset($packageType['spack']) && $packageType['spack'] == 'yes') { echo ' special-package'; } ?>">
								<ul>
									<?php if($p_combine['enribs'] == "yes" && esc_attr($packageType['rtext']) != '') { ?><li><div class="wrc-ribbon ribbon_color-<?php echo esc_attr($pid); ?>"><a href="#" id="wrc-ribbon"><?php echo esc_attr($packageType['rtext']); ?></a></div></li><?php } ?>
									<li class="pricing_table_title color-<?php echo esc_attr($pid); ?> title_top_radius">
										<h3 class="package_type txcolor-<?php echo esc_attr($pid); ?>"><?php echo esc_html($packageType['type']); ?><span class="package_desc"><?php echo esc_html($packageType['tdesc']); ?></span></h3>
									</li>
									<li class="pricing_table_plan plan-<?php echo esc_attr($pid); ?> top_price">
										<h2 class="package_plan txcolor-<?php echo esc_attr($pid); ?>">
											<span class="unit"><?php echo esc_attr($packageType['unit']); ?></span><span class="price"><?php echo esc_attr($packageType['price']); ?></span><span class="cent"><?php if(esc_attr($packageType['cent'])) echo '.'.esc_attr($packageType['cent']); ?></span><span class="plan"><?php echo esc_attr($packageType['plan']); ?></span>
											<span class="price_desc"><?php echo esc_attr($packageType['pdesc']); ?></span>
										</h2>
									</li><?php
									if($p_feature) {
										for($tf = 1; $tf <= $tot_feat; $tf++) {
											if(isset($packageType['fitem'.$tf])) {
												if($p_feature['ftype'.$tf] != 'textcheck') {
													$f_value = sanitize_text_field($packageType['fitem'.$tf]);
												} else {
													$f_value = sanitize_text_field($packageType['fitem'.$tf]);
													$tc_value = sanitize_text_field($packageType['fitem'.$tf.'c']);
												}
												$f_tips = sanitize_text_field($packageType['tip'.$tf]);
											}
											if($packageType['fbrow1'] || $packageType['fbrow2']) {
												if ($i % 2 == 0) { $row_color = 'rowcolor-'.esc_attr($pid); } else { $row_color = 'altrowcolor-'.esc_attr($pid); }
											} else {
												if ($i % 2 == 0) { $row_color = 'rowcolor'; } else { $row_color = 'altrowcolor'; }
											} ?>
											<li class="feature_style_1 ftcolor-<?php echo esc_attr($pid); ?> <?php echo esc_attr($row_color); ?><?php if($tf == 1) { echo ' top-feature'; } ?><?php if($tf == $tot_feat) { echo ' last-feature'; } ?>">
												<div class="feature_lists"><?php
													if($f_value == 'tick' || $f_value == 'cross') {
														if($p_combine['ftcap'] == "yes") {
															$ttip = 'icon';
															if($f_value == 'tick') { ?><span class="feature_yes"></span><?php
															} else { ?><span class="feature_no"></span><?php } ?>
															<span class="media_screen"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														} else {
															$ttip = 'text';
															if($f_value == 'tick') { ?><span class="feature_yes"></span><?php
															} else { ?><span class="feature_no"></span><?php } ?>
															<span class="feat_value"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														}
													} elseif($f_value == '') {
														if($p_combine['ftcap'] == "yes") {
															$ttip = 'icon'; ?>
															<span class="media_screen<?php if($f_value == '' && $tc_value != 'tick') { ?> not-available<?php } ?>"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														} else {
															$ttip = 'text'; //echo $fa_icon; ?>
															<span class="feat_value<?php if($f_value == '' && $tc_value != 'tick') { ?> not-available<?php } ?>"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														}
													} else {
														$ttip = 'text';
														if($p_combine['ftcap'] == "yes") { ?>
															<div class="feat_cap"><?php echo esc_attr($f_value); ?></div>
															<span class="media_screen"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														} else { ?>
															<div class="feat_cap"><?php echo esc_attr($f_value); ?></div>
															<span class="feat_value"><?php echo esc_attr($p_feature['fitem'.$i]); ?></span><?php
														}
													}
													if($p_combine['entips'] == "yes" && $f_tips != '') { ?><span class="<?php echo esc_attr($ttip); ?>_tooltip" rel="<?php echo wp_kses_post($f_tips); ?>"></span><?php } ?>
												</div>
											</li><?php
											$i++;
										} $j++;
									} ?>
									<?php
									if (isset($packageType['btext']) && $packageType['btext'] === '') {
										$button_text = ' btn-empty';
									} else { $button_text = ''; } ?>
									<li class="pricing_table_button bbcolor-<?php echo esc_attr($pid); ?> button-<?php echo esc_attr($pid); ?><?php echo esc_attr($button_text); ?>">
										<?php if (!empty($packageType['btext'])): ?>
										<div class="button_code">
											<a href="<?php echo esc_url($packageType['blink']); ?>" class="action_button"<?php if($p_combine['nltab'] == 'yes') { ?> target="_blank"<?php } ?>><?php echo esc_attr($packageType['btext']); ?></a>
										</div>
										<?php endif; ?>
									</li>
								</ul>
							</div> <!-- End of package_details -->
							<?php } ?>
						<?php } ?> <!-- End of ForEach -->
					</div>
				</div>
				<div class="wrc_clear"></div>
			<?php } else { ?>
				<style type="text/css">
					p.wrcpt_notice {background-color: #FFF;padding: 15px 20px;font-size: 24px;line-height: 24px;border-left: 4px solid #7ad03a;-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);box-shadow: 2px 2px 5px 2px rgba(0,0,0,.1);display: inline-block}
				</style><?php
				if($p_table == '' || $flag == 0) { ?>
					<p class="wrcpt_notice"><?php echo esc_html__('You didn\'t add any pricing tables yet!', 'wrc-pricing-tables') ?></p><?php
				} elseif(empty($p_lists)) { ?>
					<p class="wrcpt_notice"><?php echo esc_html__('You didn\'t add any pricing column yet!', 'wrc-pricing-tables') ?></p><?php
				} else { ?>
					<p class="wrcpt_notice"><?php echo wp_kses_post(sprintf(__('Please <strong>Enable</strong> pricing table to display pricing table columns!', 'wrc-pricing-tables'))); ?></p><?php
				}
			}
		}
	}
}
