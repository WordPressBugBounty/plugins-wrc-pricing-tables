<?php
/**
 * Class WRCPT_Process_Template
 * 
 * Renders the "Pricing Table Templates" admin screen with template previews and activation buttons.
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('WRCPT_Process_Template')) {
	class WRCPT_Process_Template
	{
		private static $instance;

		/**
		 * Constructor: Initializes the template processing.
		 */
		public function __construct()
		{
			$this->wrcpt_process_template();
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
		 * Outputs the HTML for the template selection page,
		 * including previews and activation modal.
		 * 
		 * @return void
		 */
		public function wrcpt_process_template() {
			?>
			<div class="wrap">
				<div class="postbox-container wrc-global" style="width:100%">
					<h2 class="main-header"><?php esc_html_e('Pricing Table Templates', 'wrc-pricing-tables'); ?></h2>
					<hr>
					<div class="template-container">
					<?php for($tp = 1; $tp <= 22; $tp++) { ?>
						<div class="template-items">
							<div class="template-img">
								<img src="<?php printf(esc_url(WRCPT_PLUGIN_URL . 'assets/images/template-%s.png'), esc_html( $tp ));
								?>" alt="<?php esc_html_e('Template Preview', 'wrc-pricing-tables'); ?>">
							</div>
							<h2 class="template-name"><?php if($tp == 22) { ?><?php esc_html_e('Default ', 'wrc-pricing-tables'); ?><?php } ?><?php esc_html_e('Template', 'wrc-pricing-tables'); ?><?php if($tp != 22) { echo esc_attr(' '.$tp); } ?></h2>
							<div class="template-actions">
								<span class="button button-secondary activate" onclick="wrcptactivatetemp(<?php echo esc_attr($tp); ?>)"><?php esc_html_e('Create Table', 'wrc-pricing-tables'); ?></span>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
				<div id="wrcpt-confirm-modal" class="wrcpt-modal shrink-out" style="display:none;">
					<div class="wrcpt-modal-content">
						<p><?php esc_html_e('Are you sure you want this?', 'wrc-pricing-tables'); ?></p>
						<button id="wrcpt-confirm-yes" class="wrcpt-btn-confirm"><?php esc_html_e('Yes', 'wrc-pricing-tables'); ?></button>
						<button id="wrcpt-confirm-no" class="wrcpt-btn-cancel"><?php esc_html_e('No', 'wrc-pricing-tables'); ?></button>
					</div>
				</div>
				<div id="wrcpt-modal" class="wrcpt-modal" style="display:none;">
					<div class="wrcpt-modal-content">
						<p><?php esc_html_e('Table is being created. Please wait...', 'wrc-pricing-tables'); ?></p>
						<img src="<?php echo esc_url(plugins_url('../images/ajax-loader.gif', __FILE__)); ?>" alt="Loading" />
					</div>
				</div>
			</div>
			<?php
		}
	}
}

WRCPT_Process_Template::get_instances();