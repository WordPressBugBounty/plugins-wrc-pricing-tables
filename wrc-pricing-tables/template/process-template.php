<?php
/*
 * WRC Pricing Tables 2.4.3 - 1-August-2024
 * @realwebcare - https://www.realwebcare.com/
 * Processing pricing table templates.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>
<div class="wrap">
	<div class="postbox-container wrc-global" style="width:100%">
		<h2 class="main-header"><?php esc_html_e('Pricing Table Templates', 'wrc-pricing-tables'); ?></h2>
		<hr>
		<div class="template-container"><?php
			for($tp = 1; $tp <= 15; $tp++) { ?>
				<div class="template-items">
					<div class="template-img">
						<img src="<?php printf(esc_url(plugins_url('../images/template-%s.png', __FILE__ ), 'wrc-pricing-tables' ), esc_html( $tp ));
						?>" alt="<?php esc_html_e('Template Preview', 'wrc-pricing-tables'); ?>">
					</div>
					<h2 class="template-name"><?php if($tp == 15) { ?><?php esc_html_e('Default ', 'wrc-pricing-tables'); ?><?php } ?><?php esc_html_e('Template', 'wrc-pricing-tables'); ?><?php if($tp != 15) { echo esc_attr(' '.$tp); } ?></h2>
					<div class="template-actions">
						<span class="button button-secondary activate" onclick="wrcptactivatetemp(<?php echo esc_attr($tp); ?>)"><?php esc_html_e('Create Table', 'wrc-pricing-tables'); ?></span>
					</div>
				</div><?php
			} ?>
		</div>
	</div>
</div>