<?php
/**
 * WRC Pricing Tables – Admin Sidebar
 *
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WRCPT_Admin_Sidebar' ) ) {

    class WRCPT_Admin_Sidebar {

        /**
         * Singleton instance
         *
         * @var WRCPT_Admin_Sidebar|null
         */
        private static $instance = null;

        /**
         * Get singleton instance
         *
         * @return WRCPT_Admin_Sidebar
         */
        public static function get_instances() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Private constructor to enforce singleton
         */
        private function __construct() {
            // Intentionally empty
        }

        /**
         * Render the full sidebar
         *
         * @param bool   $with_pro_box      Whether to show the "Ultimate Features" box (default: true)
		 * @param string $extra_class       Optional extra CSS class for the container
         * @param bool   $featured_plugins  Whether to show the "Featured Plugins" box (default: false)
         */
        public function render_guide_sidebar( $with_pro_box = true, $featured_plugins = false, $plugin_info = true, $extra_class = '' ) {
            $extra_class = sanitize_html_class( $extra_class );
            ?>
            <div id="wrcpt-sidebar" class="postbox-container <?php echo esc_attr( $extra_class ); ?>">
                <?php
                if ( $with_pro_box ) :
                    $this->render_pro_features_box();
                endif;
                if ( $featured_plugins ) :
                    $this->render_plugin_info_box();
                endif;
                if ( $plugin_info ) :
                    $this->render_guide_info_box();
                endif; ?>
            </div>
            <?php
        }

		/**
		 * Pro / Ultimate Features Box
         * 
		 * @return void
		 */
        protected function render_pro_features_box() {
            $pro_image_url = WRCPT_PLUGIN_URL . 'assets/images/template-pro.png';
            ?>
            <div id="wrcptusage-features" class="wrcptusage-sidebar">
                <div class="wrcptusage-feature-header">
                    <img src="<?php echo esc_url( $pro_image_url ); ?>" alt="<?php esc_attr_e( 'WRC Pricing Tables Ultimate', 'wrc-pricing-tables' ); ?>">
                </div>
                <div class="wrcptusage-feature-body">
                    <h3><?php esc_html_e( 'Ultimate Features', 'wrc-pricing-tables' ); ?></h3>

                    <a href="<?php echo esc_url( 'https://sandbox.realwebcare.com/sandbox-demo-creator-wrc-pricing-tables/' ); ?>"
                       class="wrcpt-sanbox-button"
                       target="_blank" rel="noopener">
                        <?php esc_html_e( 'Test Drive Pro', 'wrc-pricing-tables' ); ?>
                    </a>

                    <div class="wrcpt">
                        <?php esc_html_e( 'Ultimate version has been developed to present Pricing Tables more proficiently. Some of the most notable features are:', 'wrc-pricing-tables' ); ?>
                    </div>

                    <ul class="wrcptusage-list">
                        <li><?php esc_html_e( '60+ templates to create pricing table instantly.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Feature Categorization.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Set image as background image.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Set price section at the bottom.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Set button at the top.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Display prices in a circle.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Set up to 4 custom pricing toggles effortlessly.', 'wrc-pricing-tables' ); ?></li>
                        <li><?php esc_html_e( 'Enable HTML code in feature area.', 'wrc-pricing-tables' ); ?></li>
                    </ul>

                    <a href="<?php echo esc_url( 'https://www.realwebcare.com/demo/?product_id=wrc-pricing-tables-ultimate' ); ?>"
                       target="_blank" rel="noopener">
                        <?php esc_html_e( 'View Demo', 'wrc-pricing-tables' ); ?>
                    </a>
                </div>
            </div>
            <?php
        }

		/**
		 * Featured Plugins Info box
         * 
		 * @return void
		 */
        protected function render_plugin_info_box() {
            ?>
            <div id="wrcptusage-info" class="wrcptusage-sidebar">
                <h3><?php esc_html_e( 'Our Featured Plugins', 'wrc-pricing-tables' ); ?></h3>
                <ul class="wrcptusage-list">
                    <li>
                        <a href="https://www.realwebcare.com/item/wordpress-responsive-pricing-table-plugin/"
                           target="_blank" rel="noopener">
                            <?php esc_html_e( 'WRC Pricing Tables - Ultimate', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/t4b-news-ticker/"
                           target="_blank" rel="noopener">
                            <?php esc_html_e( 'T4B News Ticker', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/rwc-team-members/"
                           target="_blank" rel="noopener">
                            <?php esc_html_e( 'RWC Team Members', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
        }

		/**
		 * Plugin Info box used only on the Guide page
         * 
		 * @return void
		 */
        protected function render_guide_info_box() {
            $review_url = 'https://wordpress.org/support/plugin/wrc-pricing-tables/reviews/?filter=5/#new-post';
            ?>
            <div id="wrcptusage-info" class="wrcptusage-sidebar">
                <h3><?php esc_html_e( 'Plugin Info', 'wrc-pricing-tables' ); ?></h3>
                <ul class="wrcptusage-list">
                    <li>
                        <?php esc_html_e( 'Version: ', 'wrc-pricing-tables' ); ?>
                        <a href="https://wordpress.org/plugins/wrc-pricing-tables/changelog/" target="_blank" rel="noopener">
                            <?php echo esc_html( '2.6' ); ?>
                        </a>
                    </li>
                    <li><?php esc_html_e( 'Requires: WordPress 5.2', 'wrc-pricing-tables' ); ?></li>
                    <li><?php esc_html_e( 'First release: 6 May, 2015', 'wrc-pricing-tables' ); ?></li>
                    <li><?php esc_html_e( 'Last Update: 9 December, 2025', 'wrc-pricing-tables' ); ?></li>
                    <li>
                        <?php esc_html_e( 'Developed by: ', 'wrc-pricing-tables' ); ?>
                        <a href="https://www.realwebcare.com" target="_blank" rel="noopener">
                            <?php esc_html_e( 'Realwebcare', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                    <li>
                        <?php esc_html_e( 'Need Help? ', 'wrc-pricing-tables' ); ?>
                        <a href="https://wordpress.org/support/plugin/wrc-pricing-tables/" target="_blank" rel="noopener">
                            <?php esc_html_e( 'Support', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                    <li>
                        <?php esc_html_e( 'Benefited by WRC Pricing Tables? Please leave us a ', 'wrc-pricing-tables' ); ?>
                        <a href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="noopener">
                            &#9733;&#9733;&#9733;&#9733;&#9733;
                        </a>
                        <?php esc_html_e( ' rating. We highly appreciate your support!', 'wrc-pricing-tables' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'Published under:', 'wrc-pricing-tables' ); ?><br>
                        <a href="http://www.gnu.org/licenses/gpl.txt" target="_blank" rel="noopener">
                            <?php esc_html_e( 'GNU General Public License', 'wrc-pricing-tables' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
        }
    }
}
