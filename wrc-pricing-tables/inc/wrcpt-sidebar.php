<?php
/*
 * WRC Pricing Tables 2.4.3 - 1-August-2024
 * @realwebcare - https://www.realwebcare.com/
 * Plugin info in sidebar
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function wrcpt_sidebar($class='') {
    if($class != '') {
        $class = ' '.$class;
    } ?>
	<div id="wrcpt-sidebar" class="postbox-container<?php echo $class; ?>">
		<div id="wrcptusage-features" class="wrcptusage-sidebar">
			<div class="wrcptusage-feature-header">
				<img src="<?php echo plugins_url('../images/template-pro.png', __FILE__) ?>" alt="Rover">
			</div>
			<div class="wrcptusage-feature-body">
				<h3><?php esc_html_e('WRC Pricing Tables Ultimate Features', 'wrc-pricing-tables'); ?></h3>
				<div class="wrcpt"><?php esc_html_e('Ultimate version has been developed to present Pricing Tables more proficiently. Some of the most notable features are:', 'wrc-pricing-tables'); ?></div>
				<ul class="wrcptusage-list">
					<li><?php esc_html_e('60+ templates to create pricing table instantly.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Feature Categorization.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set image as background image.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set price section at the bottom.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set button at the top.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Display prices in a circle.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set up to 4 custom pricing toggles effortlessly.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Enable HTML code in feature area.', 'wrc-pricing-tables'); ?></li>
				</ul>
				<a href="https://www.realwebcare.com/demo/?product_id=wrc-pricing-tables-ultimate" target="_blank"><?php esc_html_e('View Demo', 'wrc-pricing-tables'); ?></a>
			</div>
		</div>
		<div id="wrcptusage-info" class="wrcptusage-sidebar">
			<h3><?php esc_html_e('Featured Plugins', 'wrc-pricing-tables'); ?></h3>
			<ul class="wrcptusage-list">
				<li><a href="https://www.realwebcare.com/item/clean-css3-wordpress-responsive-pricing-table/" target="_blank"><?php esc_html_e('WRC Pricing Tables - Standard', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://www.realwebcare.com/item/wordpress-responsive-pricing-table-plugin/" target="_blank"><?php esc_html_e('WRC Pricing Tables - Ultimate', 'wrc-pricing-tables'); ?></a></li><hr>
				<li><a href="https://wordpress.org/plugins/t4b-news-ticker/" target="_blank"><?php esc_html_e('T4B News Ticker', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://wordpress.org/plugins/rwc-team-members/" target="_blank"><?php esc_html_e('RWC Team Members', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://wordpress.org/plugins/responsive-portfolio-image-gallery/" target="_blank"><?php esc_html_e('Portfolio Gallery - Photo Gallery and Image Gallery', 'wrc-pricing-tables'); ?></a></li><hr>
				<li><?php esc_html_e('Developed by: ', 'wrc-pricing-tables'); ?><a href="https://www.realwebcare.com" target="_blank"><?php esc_html_e('Realwebcare', 'wrc-pricing-tables'); ?></a></li>
				<li><?php esc_html_e('Need Help? ', 'wrc-pricing-tables'); ?><a href="https://wordpress.org/support/plugin/wrc-pricing-tables/" target="_blank"><?php esc_html_e('Support', 'wrc-pricing-tables'); ?></a></li>
				<li><?php esc_html_e('Published under:', 'wrc-pricing-tables'); ?><br>
				<a href="http://www.gnu.org/licenses/gpl.txt" target="_blank"><?php esc_html_e('GNU General Public License', 'wrc-pricing-tables'); ?></a>
				</li>
			</ul>
		</div>
	</div><?php
}
 function wrcpt_sidebar_guide() { ?>
	<div id="wrcpt-sidebar" class="postbox-container">
		<div id="wrcptusage-features" class="wrcptusage-sidebar">
			<div class="wrcptusage-feature-header">
				<img src="<?php echo plugins_url('../images/template-pro.png', __FILE__) ?>" alt="Rover">
			</div>
			<div class="wrcptusage-feature-body">
				<h3><?php esc_html_e('Premium Features', 'wrc-pricing-tables'); ?></h3>
				<div class="ccwrpt"><?php esc_html_e('The premium version has been developed to present Pricing Tables more proficiently. Some notable features are:', 'wrc-pricing-tables'); ?></div>
				<ul class="wrcptusage-list">
					<li><?php esc_html_e('25 to 60+ templates to create pricing table instantly.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Import/Export (Backup) pricing tables.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Make a copy of a table instantly.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Hide empty features.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Hide any parts of the pricing table.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set price section at the bottom.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Set button at the top.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('PayPal button integration.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Image and video support.', 'wrc-pricing-tables'); ?></li>
					<li><?php esc_html_e('Pricing toggle support.', 'wrc-pricing-tables'); ?></li>
				</ul>
				<a href="https://www.realwebcare.com/demo/?product_id=wrc-pricing-tables-ultimate" target="_blank"><?php esc_html_e('View Demo', 'wrc-pricing-tables'); ?></a>
			</div>
		</div>
		<div id="wrcptusage-info" class="wrcptusage-sidebar">
			<h3><?php esc_html_e('Featured Plugins', 'wrc-pricing-tables'); ?></h3>
			<ul class="wrcptusage-list">
				<li><a href="https://www.realwebcare.com/item/clean-css3-wordpress-responsive-pricing-table/" target="_blank"><?php esc_html_e('WRC Pricing Tables - Standard', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://www.realwebcare.com/item/wordpress-responsive-pricing-table-plugin/" target="_blank"><?php esc_html_e('WRC Pricing Tables - Ultimate', 'wrc-pricing-tables'); ?></a></li><hr>
				<li><a href="https://wordpress.org/plugins/t4b-news-ticker/" target="_blank"><?php esc_html_e('T4B News Ticker', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://wordpress.org/plugins/rwc-team-members/" target="_blank"><?php esc_html_e('RWC Team Members', 'wrc-pricing-tables'); ?></a></li>
				<li><a href="https://wordpress.org/plugins/responsive-portfolio-image-gallery/" target="_blank"><?php esc_html_e('Portfolio Gallery - Photo Gallery and Image Gallery', 'wrc-pricing-tables'); ?></a></li>
			</ul>
		</div>
		<div id="wrcptusage-info" class="wrcptusage-sidebar">
			<h3><?php esc_html_e('Plugin Info', 'wrc-pricing-tables'); ?></h3>
			<ul class="wrcptusage-list">
				<li><?php esc_html_e('Version: ', 'wrc-pricing-tables'); ?><a href="https://wordpress.org/plugins/wrc-pricing-tables/changelog/" target="_blank"><?php esc_html_e('2.4.3', 'wrc-pricing-tables'); ?></a></li>
				<li><?php esc_html_e('Requires: Wordpress 5.2', 'wrc-pricing-tables'); ?></li>
				<li><?php esc_html_e('First release: 6 May, 2015', 'wrc-pricing-tables'); ?></li>
				<li><?php esc_html_e('Last Update: 1 August, 2024', 'wrc-pricing-tables'); ?></li>
				<li><?php esc_html_e('Developed by: ', 'wrc-pricing-tables'); ?><a href="https://www.realwebcare.com" target="_blank"><?php esc_html_e('Realwebcare', 'wrc-pricing-tables'); ?></a></li>
				<li><?php esc_html_e('Need Help? ', 'wrc-pricing-tables'); ?><a href="https://wordpress.org/support/plugin/wrc-pricing-tables/" target="_blank"><?php esc_html_e('Support', 'wrc-pricing-tables'); ?></a></li>
				<li><?php esc_html_e('Benefited by WRC Pricing Tables? Please leave us a ', 'wrc-pricing-tables'); ?><a target="_blank" href="https://wordpress.org/support/plugin/wrc-pricing-tables/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a><?php esc_html_e(' rating. We highly appreciate your support!', 'wrc-pricing-tables'); ?></li>
				<li><?php esc_html_e('Published under:', 'wrc-pricing-tables'); ?><br>
				<a href="http://www.gnu.org/licenses/gpl.txt" target="_blank"><?php esc_html_e('GNU General Public License', 'wrc-pricing-tables'); ?></a>
				</li>
			</ul>
		</div>
	</div>
<?php } ?>