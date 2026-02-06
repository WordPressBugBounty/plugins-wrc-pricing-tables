<?php
/**
 * Plugin Guideline - Admin Guide Page
 * 
 * @package WRC Pricing Tables v2.6 - 9 December, 2025
 * @link https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WRCPT_Guide')) {
    class WRCPT_Guide
    {
        private static $instance;

		/**
         * Constructor – private to enforce singleton
         */
        public function __construct()
        {
			$this->render_guide_page();
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
         * Main render method – called from admin menu callback
         */
        public function render_guide_page() {
            ?>
            <div class="wrap">
                <?php $this->render_main_content(); ?>
                <?php 
                /* Sidebar */
				WRCPT_Admin_Sidebar::get_instances()->render_guide_sidebar(true, true, true);
                ?>
            </div>
            <?php
        }

		/**
         * Main content wrapper
         */
        protected function render_main_content() {
            ?>
            <div class="postbox-container wrcpt-guide" style="width:65%;">
                <h2 class="main-header"><?php esc_html_e( 'Pricing Table Guide', 'wrc-pricing-tables' ); ?></h2>

                <div class="wrcusage-maincontent">

                    <?php $this->render_welcome_section(); ?>
                    <?php $this->render_documentation_section(); ?>
                    <?php $this->render_code_usage_section(); ?>
                    <?php $this->render_footer_thanks(); ?>

                </div><!-- .wrcusage-maincontent -->
            </div><!-- .postbox-container -->
            <?php
        }

		/**
		 * Renders the welcome section in the plugin admin page, including
         * the getting started video and instructions for new users.
         * 
		 * @return void
		 */
		protected function render_welcome_section() {
            ?>
            <div id="poststuff">
                <div class="postbox">
                    <h3><?php esc_html_e( 'Welcome to WRC Pricing Tables', 'wrc-pricing-tables' ); ?></h3>
                    <div class="inside">
                        <p><?php esc_html_e( 'We recommend you watch this 7 minutes getting started video, and then try to create your first pricing table using various pricing table options.', 'wrc-pricing-tables' ); ?></p>
                        <div class="getting-started_video">
                            <iframe width="620" height="350" src="https://www.youtube-nocookie.com/embed/--th9eLIAH4" 
                                    title="<?php esc_attr_e( 'WRC Pricing Tables Getting Started Video', 'wrc-pricing-tables' ); ?>"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

		/**
		 * Clickable call-to-action that opens the plugin documentation in a new tab
         * 
		 * @return void
		 */
		protected function render_documentation_section() {
            ?>
            <div id="poststuff">
                <div class="postbox">
                    <h3><?php esc_html_e( 'Documentation', 'wrc-pricing-tables' ); ?>:</h3>
                    <div class="inside">
                        <p style="margin:0 0 6px 0; font-weight:600;"><?php esc_html_e( 'Need help setting up your pricing table?', 'wrc-pricing-tables' ); ?></p>
                        <p>
                            <?php
                            echo wp_kses(
                                __( 'Read the step-by-step documentation to learn how to create and customize pricing tables with <strong>WRC Pricing Tables</strong>.', 'wrc-pricing-tables' ),
                                array( 'strong' => array() )
                            );
                            ?>
                        </p>
                        <a href="https://www.realwebcare.com/plugin/wrc-pricing-tables-free/" target="_blank" rel="noopener noreferrer" class="wrcpt_docs"><?php esc_html_e( 'View Documentation', 'wrc-pricing-tables' ); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }

		/**
		 * Renders an editable section on the plugin admin page with a title,
         * content paragraph, optional ordered list items, and a closing paragraph.
         * 
		 * @param mixed $title      The section title.
		 * @param mixed $content    The main content of the section.
		 * @param mixed $closing    The closing paragraph of the section.
         * @param mixed $list_items Optional array of list items to display as an ordered list.
         * 
		 * @return void
		 */
		protected function render_edit_section( $title, $content, $closing, $list_items = array() ) {
            ?>
            <div class="postbox">
                <h3><?php echo esc_html( $title ); ?></h3>
                <div class="inside">
                    <p><?php echo wp_kses( $content, array( 'strong' => array() ) ); ?></p>

                    <?php if ( ! empty( $list_items ) ) : ?>
                        <ol>
                            <?php foreach ( $list_items as $item ) : ?>
                                <li><?php echo wp_kses( $item, array( 'strong' => array(), 'pre' => array(), 'code' => array(), 'span' => array( 'class' => array() ) ) ); ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>

                    <p><?php echo wp_kses( $closing, array( 'strong' => array() ) ); ?></p>
                </div>
            </div>
            <?php
        }

		/**
		 * Renders the "Code Usage Instruction" section in the plugin admin page,
         * providing step-by-step guidance on how to insert a pricing table shortcode
         * into a WordPress post or page.
         * 
		 * @return void
		 */
		protected function render_code_usage_section() {
            $title   = __( 'Code Usage Instruction', 'wrc-pricing-tables' );
            $content = __( 'To display a pricing table shortcode in a WordPress post or page, you need to access the post or page editor in the WordPress dashboard. Here\'s how:', 'wrc-pricing-tables' );

            $items = array(
                __( 'Go to Posts or Pages, depending on where you want to display the pricing table.', 'wrc-pricing-tables' ),
                __( 'Either create a new post or page, or edit an existing one.', 'wrc-pricing-tables' ),
                __( 'Locate the spot in the post or page where you want to display the pricing table.', 'wrc-pricing-tables' ),
                __( 'Paste the following shortcode into the editor:', 'wrc-pricing-tables' ),
                __( '<pre><code>[wrc-pricing-table</span> <span class="wrcpt-built_in">id</span>=<span class="wrcpt-string">"SHORTCODE_ID"</span>]</code></pre>', 'wrc-pricing-tables' ),
                __( 'Replace <strong>SHORTCODE_ID</strong> with the actual id of the pricing table that you want to display.', 'wrc-pricing-tables' ),
                __( 'Save or publish the post or page.', 'wrc-pricing-tables' ),
            );

            $closing = __( 'Once you\'ve saved or published the post or page, the pricing table shortcode will be processed and the pricing table will be displayed on the front end of your site.', 'wrc-pricing-tables' );

            $this->render_edit_section( $title, $content, $closing, $items );
        }

		/**
		 * Renders the footer "Thank You" section in the plugin admin page,
         * encouraging users to provide feedback, contact support if needed,
         * and leave a review for the plugin on WordPress.org.
         * 
		 * @return void
		 */
		protected function render_footer_thanks() {
            ?>
            <hr>
            <div class="borderTop">
                <div class="last">
                    <p class="prepend-top append-1">
                        <?php
                        echo wp_kses(
                            __( 'Thank you for choosing our plugin! We highly value your feedback and are committed to providing you with the best support experience possible. If you have any questions or require assistance beyond the scope of this help guide, our dedicated team is eagerly awaiting your contact through the <a href="https://wordpress.org/support/plugin/wrc-pricing-tables" target="_blank" rel="noopener">WordPress Support Threads</a>. Your satisfaction is our top priority, and we are dedicated to going above and beyond to assist you. If you have found our plugin to be valuable, we would be absolutely thrilled if you could take a moment to leave an extraordinary review, rating it with <a target="_blank" rel="noopener" href="https://wordpress.org/support/plugin/wrc-pricing-tables/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a>. Your support means the world to us!', 'wrc-pricing-tables' ),
                            array(
                                'a' => array(
                                    'href'   => array(),
                                    'target' => array(),
                                    'rel'    => array(),
                                ),
                                'strong' => array(),
                            )
                        );
                        ?>
                    </p>
                </div>
            </div>
            <?php
        }
	}
}

WRCPT_Guide::get_instances();
