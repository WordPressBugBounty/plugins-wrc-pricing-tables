<?php
/*
 * WRC Pricing Tables 2.4.4 - 20 November, 2024
 * @realwebcare - https://www.realwebcare.com/
 * Plugin Guideline
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
 ?>
<div class="wrap">
	<div class="postbox-container wrcpt-guide" style="width:65%;">
	<h2 class="main-header"><?php esc_html_e('Pricing Table Guide', 'wrc-pricing-tables'); ?></h2>
		<div class="wrcusage-maincontent">
			<hr>
			<div id="poststuff">
				<div class="postbox">
					<h3><?php esc_html_e('Welcome to WRC Pricing Tables', 'wrc-pricing-tables'); ?></h3>
					<div class="inside">
						<p><?php esc_html_e('We recommend you watch this 7 minutes getting started video, and then try to create your first pricing table using various pricing table options.', 'wrc-pricing-tables'); ?></p>
						<div class="getting-started_video">
                        	<iframe width="620" height="350" src="https://www.youtube-nocookie.com/embed/--th9eLIAH4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div><!-- End poststuff -->
			<div id="poststuff">
				<div class="postbox">
					<h3><?php esc_html_e('Quick Start', 'wrc-pricing-tables'); ?>:</h3>
					<div class="inside">
						<p><?php echo wp_kses(__('From WordPress admin panel, navigate to <strong>Pricing Tables &#8811; Templates</strong>. Choice your table from 15 ready-made templates and click on <strong>Create Table</strong> button on mouse over. You will get a ready-made pricing table instantly! Navigate to All Pricing Tables and start configuring table content!', 'wrc-pricing-tables'), 'post'); ?></p>
					</div>
				</div>
			</div><!-- End poststuff -->
			<div id="poststuff">
				<div class="postbox">
					<h3><?php esc_html_e('Edit Features', 'wrc-pricing-tables'); ?>:</h3>
					<div class="inside">
						<p><?php echo wp_kses(__('You can customize the features of your pricing table as per your preference. To modify the features, go to the <strong>Edit Features</strong> section and input the values of the features in the corresponding feature section. For your convenience, the column features are divided into three parts as follows:', 'wrc-pricing-tables'), 'post'); ?></p>
						<ol>
							<li><?php echo wp_kses(__('<strong>Features: </strong>Enter the name of the feature here.', 'wrc-pricing-tables'), 'post'); ?></li>
							<li><?php echo wp_kses(__('<strong>Feature Type: </strong>Choose the feature type from the dropdown list. For displaying text, select <strong>Text</strong>. For using tick/cross icons, select <strong>Checkbox</strong>.', 'wrc-pricing-tables'), 'post'); ?></li>
						</ol>
                    </div>
                </div>
				<div class="postbox">
					<h3><?php esc_html_e('Edit Columns', 'wrc-pricing-tables'); ?>:</h3>
					<div class="inside">
						<p><?php echo wp_kses(__('Mouse over your recently created pricing table and click on the <strong>Edit Columns</strong> link. You can add as many columns to the pricing table as you like. Each column has two sections:', 'wrc-pricing-tables'), 'post'); ?></p>
						<ol>
                        	<li><p><strong><?php esc_html_e('Pricing Column Details:', 'wrc-pricing-tables'); ?></strong></p>
                                <ul>
                                    <li><?php echo wp_kses(__('<strong>Enlarge Column: </strong>Check this box to make the package stand out by making its column slightly larger than the others.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Package Name: </strong>Enter the name of the package and optionally a short description in the textarea.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Package Price: </strong>Please enter the package prices in the provided field. You can specify the package plan along with the price unit, such as using the dollar sign ($) or pounds sign (£). Additionally, you have the option to provide a short description in the accompanying textarea.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Package Features: </strong>Please enter the package features in the designated field. The feature input field will be configured based on the feature type you specified in the Edit Features section. Each feature will have a tooltip box where you can provide a short summary of the feature. This summary will be displayed as a tooltip in the pricing table.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Package Button: </strong>Please enter the desired button text and the corresponding URL in the provided fields.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Package Ribbon: </strong>Enter a catchy ribbon text in the given field to make the package more appealing and distinctive.', 'wrc-pricing-tables'), 'post'); ?></li>
                                </ul>
                            </li>
                        	<li><p><strong><?php esc_html_e('Pricing Column Colors:', 'wrc-pricing-tables'); ?></strong></p>
                                <ul>
                                    <li><?php echo wp_kses(__('<strong>Background Colors: </strong>Set the background color for each part of the pricing table here.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Font Colors: </strong>Set the font color for each part of the pricing table here.', 'wrc-pricing-tables'), 'post'); ?></li>
                                    <li><?php echo wp_kses(__('<strong>Button Colors: </strong>Set the button background and font color for the pricing table here.', 'wrc-pricing-tables'), 'post'); ?></li>
                                </ul>
                            </li>
							<li><p><strong><?php esc_html_e('Hide Or Delete Columns:', 'wrc-pricing-tables'); ?> </strong></p>
								<p><?php echo wp_kses(__('Each column has a <strong>Trash</strong> icon on the top right to delete the column. There is also a <strong>Toggle</strong> icon next to each trash icon to disable/hide a column instead of deleting it. You can reactivate the column again by clicking on the toggle icon.', 'wrc-pricing-tables'), 'post'); ?></p>
							</li>
                        </ol>
                    </div>
                </div>
				<div class="postbox">
					<h3><?php esc_html_e('Pricing Table Settings', 'wrc-pricing-tables'); ?>:</h3>
					<div class="inside">
						<p><?php echo wp_kses(__('At the bottom you will get <strong>Pricing Table Settings</strong>, where you can customize your table efficiently. There are four sections available:', 'wrc-pricing-tables'), 'post'); ?></p>
						<ol>
							<li><?php echo wp_kses(__('<strong>General Settings: </strong>To display the pricing table in your blog posts / pages enable the <strong>Enable Pricing Table</strong> checkbox by marking it. Some other options of the pricing table need to be enable for displaying the options.', 'wrc-pricing-tables'), 'post'); ?></li>
							<li><?php echo wp_kses(__('<strong>Structural Settings: </strong>In this section you have to set up the structure of the pricing table, like width, height and margin. You can set the container width that indicate the total width of pricing table. Leaving blank will set the container width at 100% by default. You can also set the number of columns in each row, maximum of six columns is recommended. By disabling the auto column width you can set space between each column, and if the caption column is enabled, then you can also set the caption column width.', 'wrc-pricing-tables'), 'post'); ?></li>
							<li><?php echo wp_kses(__('<strong>Font Settings: </strong>In this section you can assign font sizes of the pricing table.', 'wrc-pricing-tables'), 'post'); ?></li>
							<li><?php echo wp_kses(__('<strong>Advanced Color Settings: </strong>Column Shadow Color and Shadow Highlighted Color can be set here.', 'wrc-pricing-tables'), 'post'); ?></li>
						</ol>
					</div>
				</div>
				<div class="postbox">
					<h3><?php esc_html_e('SHORTCODE Placement', 'wrc-pricing-tables'); ?>:</h3>
					<div class="inside">
                        <p><?php echo wp_kses(__('Each pricing table will have a <strong>unique shortcode</strong> with a different <strong>pricing table ID</strong>, which will be generated automatically. Simply paste the shortcode into your desired post or page to display the pricing table, then publish it. Visit the published post/page to witness the magic! :)', 'wrc-pricing-tables'), 'post'); ?></p>
					</div>
				</div>
			</div><!-- End poststuff -->
			<hr>
			<div class="borderTop">
				<div class="last">
					<p class="prepend-top append-1"><?php echo wp_kses(__('Thank you for choosing our plugin! We highly value your feedback and are committed to providing you with the best support experience possible. If you have any questions or require assistance beyond the scope of this help guide, our dedicated team is eagerly awaiting your contact through the <a href="https://wordpress.org/support/plugin/wrc-pricing-tables" target="_blank">WordPress Support Threads</a>. Your satisfaction is our top priority, and we are dedicated to going above and beyond to assist you. If you have found our plugin to be valuable, we would be absolutely thrilled if you could take a moment to leave an extraordinary review, rating it with <a target="_blank" href="https://wordpress.org/support/plugin/wrc-pricing-tables/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a>. Your support means the world to us!', 'wrc-pricing-tables'), 'post'); ?></p>
				</div>
			</div><!-- end borderTop -->

		</div><!-- End wrcusage-maincontent -->
	</div><!-- End postbox-container -->
	<?php wrcpt_sidebar_guide(); ?>
</div><!-- End wrap -->