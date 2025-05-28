<?php
/**
 * Adding a new Pricing Table
 * Lists of all created pricing tables
 * 
 * @package WRC Pricing Tables v2.5 - 28 May, 2025
 * @link https://www.realwebcare.com/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
$package_table = get_option('packageTables');
$package_ids = get_option('packageIDs');
$flag = 0;
$templates = array(
	'temp1'		=> 'Template 1',	'temp2'		=> 'Template 2',	'temp3'		=> 'Template 3',
	'temp4'		=> 'Template 4',	'temp5'		=> 'Template 5',	'temp6'		=> 'Template 6',
	'temp7'		=> 'Template 7',	'temp8'		=> 'Template 8',	'temp9'		=> 'Template 9',
	'temp10'	=> 'Template 10',	'temp11'	=> 'Template 11',	'temp12'	=> 'Template 12',
	'temp13'	=> 'Template 13',	'temp14'	=> 'Template 14',	'temp15'	=> 'Template 15',
	'temp16'	=> 'Template 16',	'temp17'	=> 'Template 17',	'temp18'	=> 'Template 18',
	'temp19'	=> 'Template 19',	'temp20'	=> 'Template 20',	'temp21'	=> 'Template 21',
	'temp0'		=> 'Default'
); ?>
<div class="wrap">
	<div id="add_new_table" class="postbox-container">
		<h2 class="main-header"><?php esc_html_e('Pricing Tables', 'wrc-pricing-tables'); ?>
			<a href="?page=wrcpt-template" id="new_table"
				class="add-new-h2"><?php esc_html_e('Add Template', 'wrc-pricing-tables'); ?></a>
			<span id="wrcpt-loading-image"></span>
		</h2>
		<?php
		/* Display Pricing Table Lists*/
		if ($package_table) {
			$table_lists = explode(', ', $package_table);
			$active_lists = wrcpt_published_tables_count($table_lists);
			$inactive_lists = count($table_lists) - wrcpt_published_tables_count($table_lists);
			$id_lists = explode(', ', $package_ids);
			?>
			<div class="table_list">
				<ul class="subsubsub">
					<li class="all">All <span class="count"><?php echo count($table_lists); ?></span></li>
					<li class="publish">Active <span class="count"><?php echo esc_attr($active_lists); ?></span></li>
					<li class="unpublish">Inactive <span class="count"><?php echo esc_attr($inactive_lists); ?></span></li>
				</ul><br>
				<form id='wrcpt_edit_form' method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
					enctype="multipart/form-data">
					<input type="hidden" name="wrcpt_edit_process" value="editprocess" />
					<table id="wrcpt_list" class="form-table">
						<div id="form-messages">
							<button type="button" class="wrcpt_close">
								<span aria-hidden="true"><a><i
											class="dashicons dashicons-dismiss blackcross"></i></a></span>
							</button>
							<i class="start-icon dashicons dashicons-yes-alt"></i>
							<?php echo wp_kses_post(sprintf(__('<strong>Well done!</strong> You have successfully Updated Your Pricing Table Settings.', 'wrc-pricing-tables'))); ?>
						</div>
						<thead>
							<tr>
								<th style="width:5%"><?php esc_html_e('ID', 'wrc-pricing-tables'); ?></th>
								<th style="width:50%"><?php esc_html_e('Table Name', 'wrc-pricing-tables'); ?></th>
								<th style="width:20%"><?php esc_html_e('Shortcode', 'wrc-pricing-tables'); ?></th>
								<th style="width:15%"><?php esc_html_e('Template', 'wrc-pricing-tables'); ?></th>
								<th style="width:10%"><?php esc_html_e('Visible', 'wrc-pricing-tables'); ?></th>
							</tr>
						</thead><?php
						foreach ($table_lists as $key => $list) {
							$list = isset($list) ? sanitize_text_field($list) : '';
							$list_item = ucwords(str_replace('_', ' ', $list));
							$package_lists = get_option($list);
							$package_feature = get_option($list . '_feature');
							$packageCombine = get_option($list . '_option');
							$package_item = explode(', ', $package_lists);
							$packageCount = count($package_item);
							$packageID = $id_lists[$key];
							$tableId = $key + 1;
							$t_templ = 'temp0';
							if (isset($packageCombine['templ']) && $packageCombine['templ'] != '') {
								$t_templ = sanitize_text_field($packageCombine['templ']);
							}
							if ($package_feature) {
								if (get_option($list) && $packageCount > 0) {
									$flag = 1; ?>
									<tbody id="wrcpt_<?php echo esc_attr($list); ?>" class="table_todo">
										<tr <?php if ($tableId % 2 == 0) {
											echo 'class="alt"';
										} ?>>
											<td><?php echo esc_attr($tableId); ?></td>
											<td class="table_name" id="<?php echo esc_attr($list); ?>">
												<div><?php echo esc_html($list_item); ?></div>
												<span id="edit_package"
													onclick="wrcpteditpackages(<?php echo esc_attr($packageCount); ?>, '<?php echo esc_attr($list); ?>')"><?php esc_html_e('Edit Columns', 'wrc-pricing-tables'); ?></span>
												<span id="add_feature"
													onclick="wrcpteditfeature('<?php echo esc_attr($list); ?>')"><?php esc_html_e('Edit Features', 'wrc-pricing-tables'); ?></span>
												<span id="view_package"
													onclick="wrcptviewpack(<?php echo esc_attr($packageID); ?>, '<?php echo esc_attr($list); ?>')"><?php esc_html_e('Preview', 'wrc-pricing-tables'); ?></span>
												<span id="remTable"
													onclick="wrcptdeletetable('<?php echo esc_attr($list); ?>')"><?php esc_html_e('Delete', 'wrc-pricing-tables'); ?></span>
											</td>
											<td class="shortcode">
												<div class="tooltip">
													<input id="myInput-<?php echo esc_attr($tableId); ?>" type="text"
														name="wrc_shortcode" class="wrc_shortcode"
														value="<?php echo esc_html('[wrc-pricing-table id="' . $packageID . '"]'); ?>"
														onclick="myFunction(<?php echo esc_attr($tableId); ?>)" onmouseout="outFunc()">
													<span class="tooltiptext"
														id="myTooltip-<?php echo esc_attr($tableId); ?>"><?php esc_html_e('Click to Copy Shortcode!', 'wrc-pricing-tables'); ?></span>
												</div>
											</td>
											<td>
												<div class="temp_choice">
													<select name="wrcpt-template" class="wrcpt-template" id="wrcpt-template" onChange="wrcpttemplate('<?php echo esc_attr($list); ?>', this.options[this.selectedIndex].value)">
														<?php
														foreach ($templates as $value => $label) : ?>
															<option value="<?php echo esc_attr($value); ?>" <?php selected($t_templ, $value); ?>>
																<?php echo esc_html_e($label, 'wrc-pricing-tables'); ?>
															</option><?php
														endforeach;
														?>
													</select>
												</div>
											</td>
											<td class="wrc-status">
												<?php
												if (isset($packageCombine['enable']) && $packageCombine['enable'] == 'yes') { ?>
													<span class="status active"><?php esc_html_e('Active', 'wrc-pricing-tables'); ?></span><?php
												} else { ?>
													<span
														class="status inactive"><?php esc_html_e('Inactive', 'wrc-pricing-tables'); ?></span><?php
												}
												?>
											</td>
										</tr>
									</tbody><?php
								}
							} else {
								$flag = 0; ?>
								<tbody id="wrcpt_<?php echo esc_attr($list); ?>">
									<tr <?php if ($tableId % 2 == 0) {
										echo 'class="alt"';
									} ?>>
										<td><?php echo esc_attr($tableId); ?></td>
										<td class="table_name" id="<?php echo esc_attr($list); ?>">
											<div onclick="wrcpteditpackages('<?php echo esc_attr($list); ?>')">
												<?php echo esc_html($list_item); ?>
											</div>
											<span id="add_feature"
												onclick="wrcpteditfeature('<?php echo esc_attr($list); ?>')"><?php esc_html_e('Add Features', 'wrc-pricing-tables'); ?></span>
											<span id="remTable"
												onclick="wrcptdeletetable('<?php echo esc_attr($list); ?>')"><?php esc_html_e('Delete', 'wrc-pricing-tables'); ?></span>
										</td>
										<td class="wrcpt_notice">
											<span><?php esc_html_e('Mouseover on the table name in the left and clicked on <strong>Add Feature</strong> link. To get started you have to add some pricing features first. After that, you will be able to add pricing columns. After adding pricing columns you will get the <strong>SHORTCODE</strong> here.', 'wrc-pricing-tables'); ?></span>
										</td>
										<td><?php esc_html_e('Not Ready!', 'wrc-pricing-tables'); ?></td>
										<td><span
												class="status inactive"><?php esc_html_e('Inactive', 'wrc-pricing-tables'); ?></span>
										</td>
									</tr>
								</tbody><?php
							}
						} ?>
					</table>
					<?php if ($package_table && $flag == 1) { ?>
						<input type="button" id="reset-shortcode" name="reset_shortcode" class="button-primary"
							onclick="wrcptresetshortcode()"
							value="<?php esc_html_e('Regenerate Shortcode', 'wrc-pricing-tables'); ?>" />
					<?php } ?>
				</form>
				<?php if ($package_table && $flag == 1) { ?>
					<form method="post" action="">
						<input type="submit" id="wrcpt_optimize" name="wrcpt_optimize" class="button-secondary float-right"
							value="<?php esc_html_e('Optimize Pricing Tables', 'wrc-pricing-tables'); ?>">
					</form>
				<?php } ?>
				<?php
				if (isset($_POST['wrcpt_optimize'])) {
					$pcount = wrcpt_unuseful_package_options();
					if ($pcount > 0) {
						/* translators: %d: Number of unnecessary package options that were cleared */
						echo '<div id="message" class="updated"><p class="get_started">' . wp_kses_post(sprintf(__('Hurray! %d unnecessary package options have been cleared!', 'wrc-pricing-tables'), esc_attr($pcount))) . '</p></div>';
					} else {
						echo '<div id="message" class="updated"><p class="get_started">' . wp_kses_post(sprintf(__('Hurray! You don\'t have anymore unnecessary package options to clear!', 'wrc-pricing-tables'))) . '</p></div>';
					}
				}
				$upcount = wrcpt_count_unuseful_package_options();
				if ($upcount > 0 && !isset($pcount)) {
					/* translators: %d: Number of unnecessary package options that were created */
					echo '<div id="message" class="notice-warning notice">' . wp_kses_post(sprintf(__('<strong>Alert!</strong> %d unnecessary package options have been created unintentionally! Click on <strong>Optimize Pricing Tables</strong> button to clear.', 'wrc-pricing-tables'), esc_attr($upcount))) . '</div>';
				}
				?>
			</div>
			<?php
			/* If no pricing table available */
		} else {
			$flag = 0;
			?>
			<div class="table_list">
				<p class="get_started">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 
							%1$s: Plugin name (WRC Pricing Tables), 
							%2$s: URL to the Add Template page, 
							%3$s: URL to the Help page, 
							%4$s: URL to the WordPress support thread 
							*/
							__('
							Welcome to our plugin, %1$s! It looks like you haven\'t added any tables yet. Don\'t worry, we\'ve got you covered! Just click on the <a href="%2$s"><strong>Add Template</strong></a> button to get started. You\'ll find 15 ready-made templates to choose from. Simply select one and click on the <strong>Create Table</strong> button to instantly create your pricing table!<br /><br />If you have any questions or need further assistance beyond what\'s covered in the help <a href="%3$s"><strong>page</strong></a>, please don\'t hesitate to <a href="%4$s" target="_blank"><strong>contact us</strong></a> via the WordPress support thread. We\'re here to provide you with the support you need.', 'wrc-pricing-tables'),
							'<strong>WRC Pricing Tables</strong>',
							esc_url(admin_url("admin.php?page=wrcpt-template")),
							esc_url(admin_url("admin.php?page=wrcpt-help")),
							esc_url("https://wordpress.org/support/plugin/wrc-pricing-tables/")
						)
					);
					?>
				</p>
			</div>
			<?php
		} ?>
	</div><!-- End postbox-container -->
	<div id="wrcpt-modal" class="wrcpt-modal" style="display:none;">
		<div class="wrcpt-modal-content">
			<p><?php esc_html_e('The changes are being updated. Please wait...', 'wrc-pricing-tables'); ?></p>
			<img src="<?php echo esc_url(plugins_url('../images/ajax-loader.gif', __FILE__)); ?>" alt="Loading" />
		</div>
	</div>
	<?php if ($package_table && $flag == 1) { ?>
		<div id="wrcpt-narration" class="postbox-container code">
			<div id="wrcptusage-premium" class="wrcptusage-sidebar">
				<h3><?php esc_html_e('Code Usage Instruction', 'wrc-pricing-tables'); ?></h3>
				<div class="wrcpt">
					<p><?php esc_html_e('To display a pricing table shortcode in a WordPress post or page, you need to access the post or page editor in the WordPress dashboard. Here\'s how:', 'wrc-pricing-tables'); ?>
					</p>
					<ol>
						<li><?php esc_html_e('Go to Posts or Pages, depending on where you want to display the pricing table.', 'wrc-pricing-tables'); ?>
						</li>
						<li><?php esc_html_e('Either create a new post or page, or edit an existing one.', 'wrc-pricing-tables'); ?>
						</li>
						<!-- <li><?php //_e('Switch to the visual editor if it\'s not already active.', 'wrc-pricing-tables'); ?></li> -->
						<li><?php esc_html_e('Locate the spot in the post or page where you want to display the pricing table.', 'wrc-pricing-tables'); ?>
						</li>
						<li>
							<?php esc_html_e('Paste the following shortcode into the editor:', 'wrc-pricing-tables'); ?>
							<pre><code>[wrc-pricing-table</span> <span class="wrcpt-built_in">id</span>=<span class="wrcpt-string">"SHORTCODE_ID"</span>]</code></pre>
							<?php esc_html_e('Replace "SHORTCODE_ID" with the actual id of the pricing table that you want to display.', 'wrc-pricing-tables'); ?>
						</li>
						<li><?php esc_html_e('Save or publish the post or page.', 'wrc-pricing-tables'); ?></li>
					</ol>
					<p><?php esc_html_e('Once you\'ve saved or published the post or page, the pricing table shortcode will be processed and the pricing table will be displayed on the front end of your site.', 'wrc-pricing-tables'); ?>
					</p>
				</div>
			</div>
			<div id="wrcptusage-premium" class="wrcptusage-sidebar">
				<div class="wrcpt">
					<h3><?php esc_html_e('Optimize Pricing Tables', 'wrc-pricing-tables'); ?></h3>
					<p><strong><?php esc_html_e('Optimize Pricing Tables: ', 'wrc-pricing-tables'); ?></strong><?php esc_html_e('While customizing the pricing table, it\'s possible to unknowingly create unnecessary package options, which can increase the overall weight of the database. By simply clicking on this button, you can effortlessly remove any unnecessary options from the database immediately, without any hassle.', 'wrc-pricing-tables'); ?>
					</p>
					<p><?php echo wp_kses(__('Check out our YouTube video for a clear demonstration of how the Pricing Table plugin works. For more detailed information and additional functionality, visit our <a href="?page=wrcpt-help">help page</a>, where you can watch the video as well.', 'wrc-pricing-tables'), 'post'); ?>
					</p>
					<p class="likeit">
						<?php esc_html_e('Like it? Please leave us a rating.', 'wrc-pricing-tables'); ?> <a target="_blank"
							href="https://wordpress.org/support/plugin/wrc-pricing-tables/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a>
						<?php esc_html_e('We highly appreciate your support!', 'wrc-pricing-tables'); ?>
					</p>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php wrcpt_sidebar($flag); ?>
</div>