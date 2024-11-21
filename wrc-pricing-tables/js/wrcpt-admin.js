/*!
 * WRC Pricing Tables 2.4.4 - 20 November, 2024
 * by @realwebcare - https://www.realwebcare.com
 */
jQuery(document).ready(function() {
	jQuery(".table_name span").css("display","none");
	jQuery(".table_name").mouseover(function(){
		var linkid = jQuery(this).attr("id");
		jQuery("td#" + linkid + " span").css("display","inline-block");
	});
	jQuery(".table_name").mouseout(function(){
		var linkid = jQuery(this).attr("id");
		jQuery("td#" + linkid + " span").css("display","none");
	});
});

/* copy shorcode on click */
function myFunction(id) {
	"use strict";
	var copyText = document.getElementById("myInput-" + id);
	
	if (copyText !== null) {
	  copyText.select();
	  document.execCommand("copy");
  
	  var tooltip = document.getElementById("myTooltip-" + id);
	  
	  if (tooltip !== null) {
		tooltip.innerHTML = "Copied!";
	  }
	}
}

function outFunc() {
	"use strict";
	var id;
	var tooltip = document.getElementById("myTooltip-" + id);
	if (tooltip !== null) {
		tooltip.innerHTML = "Click to Copy Shortcode!";
	}
}

function wrcptdeletetable(ptable) {
	var answer = confirm ("Are you sure you want to delete?");
	if (answer) {
		jQuery.ajax({
			type: 'POST',
			url: wrcptajax.ajaxurl,
			data: {
				action: 'wrcpt_delete_pricing_table',
				packtable: ptable,
				nonce: wrcptajax.nonce
			},
			beforeSend: function(){
				// Show image container
				jQuery("#wrcpt-loading-image").css("display","inline-block");
			},
			success:function(data, textStatus, XMLHttpRequest){
				var linkid = '#wrcpt_' + ptable;
				jQuery(linkid).remove();
				jQuery(linkid).append(data);
			},
			complete:function(){
				// Hide image container
				jQuery("#wrcpt-loading-image").hide();
			},
			error: function(MLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	}
}
function wrcpteditpackages(pcount, ptable) {
	jQuery.ajax({
		type: 'POST',
		url: wrcptajax.ajaxurl,
		data: {
			action: 'wrcpt_edit_pricing_packages',
			packtable: ptable,
			nonce: wrcptajax.nonce
		},
		beforeSend: function(){
			// Show image container
			jQuery("#wrcpt-loading-image").css("display","inline-block");
		},
		success:function(data, textStatus, XMLHttpRequest){
			var linkid = '#wrcpt_list';

			jQuery(linkid).html('');
			jQuery(".table_list .subsubsub").hide();
			jQuery("#new_table").hide();
			jQuery('#reset-shortcode').hide();
			jQuery('#wrcpt-narration').hide();
			jQuery('#wrcpt-sidebar').hide();
			jQuery('#wrcpt_optimize').hide();
			jQuery(linkid).append(data);
			jQuery(".expand").hide();
			jQuery(".collapse").click(function() {
				jQuery(".column_container").accordion({
					collapsible: true,
					active:false
				});
				jQuery(".collapse").hide();
				jQuery(".expand").show();
			});
			
			jQuery(".expand").click(function() {
				jQuery(".column_container").accordion({
					collapsible: false,
					active:true
				});
				jQuery(".expand").hide();
				jQuery(".collapse").show();
			});
			jQuery("#auto_column").click(function() {
				if(jQuery("#auto_column").is(":checked")){
					jQuery("label#margin_right").slideDown("slow");
					jQuery("input#column_space").slideDown("slow");
					if(jQuery("#feature_caption").is(":checked")) {
						jQuery("label#cap_col_width").slideDown("slow");
						jQuery("input#cap_column_width").slideDown("slow");
					}
				} else {
					jQuery("label#margin_right").slideUp("slow");
					jQuery("input#column_space").slideUp("slow");
					jQuery("label#cap_col_width").slideUp("slow");
					jQuery("input#cap_column_width").slideUp("slow");
				}
			});
			if(jQuery("#auto_column").is(":checked")){
				jQuery("label#margin_right").css("display","block");
				jQuery("input#column_space").css("display","block");
			}
			jQuery("#feature_caption").click(function() {
				if(jQuery("#feature_caption").is(":checked") && jQuery("#auto_column").is(":checked")){
					jQuery("label#cap_col_width").slideDown("slow");
					jQuery("input#cap_column_width").slideDown("slow");
				} else {
					jQuery("label#cap_col_width").slideUp("slow");
					jQuery("input#cap_column_width").slideUp("slow");
				}
			});
			if(jQuery("#feature_caption").is(":checked") && jQuery("#auto_column").is(":checked")){
				jQuery("label#cap_col_width").css("display","block");
				jQuery("input#cap_column_width").css("display","block");
			}
			if(pcount == 1) {
				jQuery('#wrcpt-1 #delPackage').attr('id', 'delDisable');
				jQuery('#wrcpt-1 #hidePack').attr('class', 'inactive');
			}
			jQuery(".table_list").css("width","100%");
			jQuery("#add_new_table h2").text("Edit Pricing Table");
			jQuery(".postbox-container").css("width","100%");
			jQuery(function() {
				jQuery('#sortable_column').sortable({
					cancel: ".column_container"
				});

				// Bind touchstart event to input elements
				jQuery('#sortable_column input, #sortable_column textarea').on('touchstart', function(e) {
					e.preventDefault();
					jQuery(this).focus();
					var val = jQuery(this).val();
					jQuery(this).val('').val(val); // Move cursor to end of input field
				});
			});
			jQuery(".package_details").css("cursor","move");
			jQuery('#accordion_advance').accordion({
				collapsible: true,
				heightStyle: "content"
			});
			for(i = 1; i <= pcount; i++) {
				jQuery('#accordion' + i).accordion({
					collapsible: true,
					heightStyle: "content"
				});
				if(jQuery('#showPack' + i + ' input').val() == 'hide') {
					jQuery('#accordion' + i).hide();
				}
			}
			jQuery('.title_color').wpColorPicker();
			jQuery('.title_bg').wpColorPicker();
			jQuery('.feat_row1_color').wpColorPicker();
			jQuery('.feat_row2_color').wpColorPicker();
			jQuery('.feature_text_color').wpColorPicker();
			jQuery('.price_color_big').wpColorPicker();
			jQuery('.button_text_color').wpColorPicker();
			jQuery('.button_text_hover').wpColorPicker();
			jQuery('.button_color').wpColorPicker();
			jQuery('.button_hover').wpColorPicker();
			jQuery('.ribbon_text_color').wpColorPicker();
			jQuery('.ribbon_bg').wpColorPicker();
			jQuery('.col_shad_color').wpColorPicker();
			jQuery('.col_shad_hover_color').wpColorPicker();
			jQuery('#addPackage').click(function () {
				var num = jQuery('.package_details').length,
				newNum  = new Number(num + 1),
				newElem = jQuery('#wrcpt-' + num).clone().attr('id', 'wrcpt-' + newNum).fadeIn('slow');
				newElem.find('#pcolumn' + num).attr('id', 'pcolumn' + newNum);
				newElem.find('#showPack' + num).attr('id', 'showPack' + newNum);
				newElem.find('#hidePack' + num).attr('id', 'hidePack' + newNum);
				newElem.find('#accordion' + num).attr('id', 'accordion' + newNum);
				jQuery('#wrcpt-' + num).after(newElem);
				jQuery('.ptitle').focus();
				jQuery('#accordion' + newNum).accordion({
					collapsible: true,
					heightStyle: "content"
				});
				jQuery('#pcolumn' + newNum).text('Pricing Column ' + newNum);
				jQuery('#accordion' + newNum + ' .wp-color-result').remove();
				jQuery('.title_color').wpColorPicker();
				jQuery('.title_bg').wpColorPicker();
				jQuery('.feat_row1_color').wpColorPicker();
				jQuery('.feat_row2_color').wpColorPicker();
				jQuery('.feature_text_color').wpColorPicker();
				jQuery('.price_color_big').wpColorPicker();
				jQuery('.button_text_color').wpColorPicker();
				jQuery('.button_text_hover').wpColorPicker();
				jQuery('.button_color').wpColorPicker();
				jQuery('.button_hover').wpColorPicker();
				jQuery('.ribbon_text_color').wpColorPicker();
				jQuery('.ribbon_bg').wpColorPicker();
				jQuery('#wrcpt-1 #delDisable').attr('id', 'delPackage');
				jQuery('#wrcpt-' + newNum + ' #delDisable').attr('id', 'delPackage');
				jQuery('.inactive').attr('class', 'column_hide');
			});
			jQuery('body').on('click', '#delPackage', function() {
				var num = jQuery('.package_details').length;
				var answer = confirm ("Are you sure you wish to remove this package? This cannot be undone!");
				if (answer) {
					jQuery(this).parents('.package_details').slideUp('slow', function () {
						jQuery(this).remove();
						if (num -1 === 1) {
							jQuery('#delPackage').attr('id', 'delDisable');
							jQuery('.column_hide').attr('class', 'column_hide inactive');
							jQuery(".package_details").css("cursor","auto");
							jQuery("#sortable_column").sortable({ disabled: true });
						}
						jQuery('#addPackage').attr('disabled', false).prop('value', "New Column");
						var j = 1;
						for(var i=1; i<=num; i++) {
							if(jQuery('#wrcpt-' + i).length !== 0) {
								jQuery('#wrcpt-' + i).attr('id', 'wrcpt-' + j);
								jQuery("#pcolumn"+ i).text("Pricing Column " + j);
								jQuery("#pcolumn"+ i).attr("id", "pcolumn"+ j);
								jQuery("#hidePack"+ i).attr("id", "hidePack"+ j);
								jQuery("#showPack"+ i).attr("id", "showPack"+ j);
								jQuery('#accordion' + i).attr('id', 'accordion' + j);
								j++;
							}
						}
					});
				}
			});
			jQuery('body').on('click', '.column_hide', function() {
				var num = jQuery('.package_details').length;
				if(num > 1) {
					jQuery(this).parents('.package_details').find('.column_container').fadeOut("slow");
					jQuery(this).prop('class', 'column_show');
					jQuery(this).children('.dashicons-fullscreen-alt').prop('class', 'dashicons dashicons-fullscreen-exit-alt');
					jQuery(this).children('input').prop('value', 'hide');
				}
			});
			jQuery('body').on('click', '.column_show', function() {
				jQuery(this).parents('.package_details').find('.column_container').fadeIn("slow");
				jQuery(this).prop('class', 'column_hide');
				jQuery(this).children('.dashicons-fullscreen-exit-alt').prop('class', 'dashicons dashicons-fullscreen-alt');
				jQuery(this).children('input').prop('value', 'show');
			});

			// Edit pricing column, column info and column settings
			jQuery('#wrcpt_edit').on('click', function() {
				wrcptsettableoptions();
			});
		},
		complete:function(){
			// Hide image container
			jQuery("#wrcpt-loading-image").hide();
		},
		error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	});
	// Customizing to add 18 color palettes
	if (typeof jQuery.wp !== 'undefined' && typeof jQuery.wp.wpColorPicker !== 'undefined') {
		jQuery.wp.wpColorPicker.prototype.options = {
			width: 255,
			hide: true,
			border: false,
			palettes: ['#ededed', '#ecf0f1',  '#c8d6e5', '#7f8c8d', '#34495e', '#22313f', '#2ecc71', '#48b56a', '#0abde3', '#1f8dd6', '#2574a9', '#1f3a93', '#5f27cd', '#fad232','#ff9f43', '#ed6789', '#ff6b6b', '#ee5253'],    
		};
	}
}
// save table options
function wrcptsettableoptions() {
	// let submitted = false;
	var submitted = jQuery('#submitted').val();
	// Get the form.
	const form = jQuery('#wrcpt_edit_form');
	const formMessages = jQuery('#form-messages');

	// Bind the click event of the submit button
	form.on('submit', function(event) {
		// Prevent the form from submitting normally
		event.preventDefault();

		// Get the form data
		const formData = jQuery(this).serialize();

		// Show the modal with the "updating table" message
		jQuery('#wrcpt-modal').fadeIn().find('.wrcpt-modal-content').html(`
			<p>${wrcptajax.updating_table}</p>
			<img src="${wrcptajax.loading_image}" alt="Loading" />
		`);
		// Submit the form via AJAX
		jQuery.ajax({
			type: 'POST',
			url: jQuery(this).attr('action'),
			data: formData,
			success: function(response) {
                // Hold the "updating table" message for 3 seconds
                setTimeout(function() {
                    // Update the modal with the success message
                    jQuery('#wrcpt-modal .wrcpt-modal-content').html(`
                        <p>${wrcptajax.update_success}</p>
                    `);

                    // After 2 seconds, hide the modal and proceed
                    setTimeout(function() {
                        jQuery('#wrcpt-modal').fadeOut('slow', function() {
							// Make sure that the formMessages div has the 'success' class.
							formMessages.addClass('success').css('display', 'block');
							// Clear the form and retrieve it again.
							form.hide().fadeIn(1000);
							jQuery('html, body').animate({ scrollTop: 0 }, 0);

							jQuery('body').on('click', '.wrcpt_close', function() {
								formMessages.fadeOut('slow');
							});

							if (submitted === 'no') {
								window.location.reload();
							}
                        });
                    }, 2000); // Hold the success message for 2 seconds
                }, 3000); // Hold the "updating table" message for 3 seconds
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error('An error occurred:', textStatus, '-', errorThrown);

                // Update the modal with an error message
                jQuery('#wrcpt-modal .wrcpt-modal-content').html(`
                    <p>${wrcptajax.update_error}</p>
                `);

                // Hide the modal after 3 seconds
                setTimeout(function() {
                    jQuery('#wrcpt-modal').fadeOut();
                }, 3000);
			}
		});
	});
}
function wrcpteditfeature(ptable) {
	jQuery.ajax({
		type: 'POST',
		url: wrcptajax.ajaxurl,
		data: {
			action: 'wrcpt_process_package_features',
			packtable: ptable,
			nonce: wrcptajax.nonce
		},
		beforeSend: function(){
			// Show image container
			jQuery("#wrcpt-loading-image").css("display","inline-block");
		},
		success:function(data, textStatus, XMLHttpRequest){
			var linkid = '#wrcpt_list';
			jQuery(linkid).html('');
			jQuery(".table_list .subsubsub").hide();
			jQuery("#new_table").hide();
			jQuery('#reset-shortcode').hide();
			jQuery('#wrcpt-narration').hide();
			jQuery('#wrcpt-sidebar').hide();
			jQuery('#wrcpt_optimize').hide();
			jQuery('#add_new_table').css('width', '50%');
			jQuery(linkid).append(data);
			jQuery("#add_new_table h2").text("Edit Pricing Column Features");

			var featureName = jQuery('#feature_edititem');

			jQuery('body').on('click', '#editfeature', function() {
				jQuery('<tr class="featurebody"><td><input type="text" name="feature_name[]" placeholder="Enter Feature Name" size="20" required /></td><td><select name="feature_type[]" id="feature_type"><option value="text" selected="selected">Text</option><option value="check">Checkbox</option></select></td><td><span id="remFeature"></span></td></tr>').appendTo(featureName);
				return false;
			});
			jQuery('body').on('click', '#remFeature', function() {
				jQuery(this).parents('tr.featurebody').remove();
				return false;
			});
			jQuery('body').on('click', '#remFeature', function() {
				var num = jQuery('#feature_edititem tr.featurebody').length;
				// alert(num);
				if (num === 1)
					jQuery('#remFeature').attr('id', 'remDisable');
				jQuery(this).parents('tr.featurebody').remove();
				return false;
			});

			jQuery(function() {					  
				jQuery('#feature_edititem tbody').sortable({
					helper: function(e, ui) {
						ui.children().each(function() {
							jQuery(this).width(jQuery(this).width());
						});
						return ui;
					},
					cancel: 'input, select'
				});
				// Bind touchstart event to input elements
				jQuery('.featurebody input').on('touchstart', function(e) {
					e.preventDefault();
					jQuery(this).focus();
					var val = jQuery(this).val();
					jQuery(this).val('').val(val); // Move cursor to end of input field
				});
			});

			// Add pricing features
			jQuery('#wrcpt_addfeature').on('click', function() {
				wrcptsettableoptions();
			});

			// Edit pricing features
			jQuery('#wrcpt_upfeature').on('click', function() {
				wrcptsettableoptions();
			});
		},
		complete:function(){
			// Hide image container
			jQuery("#wrcpt-loading-image").hide();
		},
		error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	});
}
function wrcptviewpack(tabid, ptable) {
	jQuery.ajax({
		type: 'POST',
		url: wrcptajax.ajaxurl,
		data: {
			action: 'wrcpt_view_pricing_packages',
			packtable: ptable,
			tableid: tabid,
			nonce: wrcptajax.nonce
		},
		beforeSend: function(){
			// Show image container
			jQuery("#wrcpt-loading-image").css("display","inline-block");
		},
		success:function(data, textStatus, XMLHttpRequest){
			var linkid = '#wrcpt_list';
			var replace_name = ptable.replace("_", " ");
			var pricing_table_name = replace_name.replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function(m){ return m.toUpperCase() });
			jQuery(linkid).html('');
			jQuery(".table_list .subsubsub").hide();
			jQuery("#new_table").hide();
			jQuery('#wrcpt-sidebar').hide();
			jQuery('#reset-shortcode').hide();
			jQuery('#wrcpt_optimize').hide();
			jQuery(linkid).append(data);
			jQuery("#add_new_table h2.main-header").text("Preview of " + pricing_table_name);
			jQuery(".table_list").css("width","100%");
			jQuery(".postbox-container").css("width","100%");
		},
		complete:function(){
			// Hide image container
			jQuery("#wrcpt-loading-image").hide();
		},
		error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	});
}
function wrcptresetshortcode(pcount, ptable) {
	jQuery.ajax({
		type: 'POST',
		url: wrcptajax.ajaxurl,
		data: {
			action: 'wrcpt_regenerate_shortcode',
			nonce: wrcptajax.nonce
		},
		beforeSend: function(){
			// Show image container
			jQuery("#wrcpt-loading-image").css("display","inline-block");
		},
		success:function(data, textStatus, XMLHttpRequest){
			alert('Successfully Regenerated Shortcode ID!');
			window.location.reload();
		},
		complete:function(){
			// Hide image container
			jQuery("#wrcpt-loading-image").hide();
		},
		error: function(MLHttpRequest, textStatus, errorThrown){
			alert(errorThrown);
		}
	});
}
function wrcpttemplate(ptable, temp) {
	var answer = confirm ("Are you sure you want to setup this template?");
	if (answer) {
		// Show the modal with the "switching" message
		jQuery('#wrcpt-modal').fadeIn().find('.wrcpt-modal-content').html(`
			<p>${wrcptajax.switch_template}</p>
			<img src="${wrcptajax.loading_image}" alt="Loading" />
		`);
		jQuery.ajax({
			type: 'POST',
			url: wrcptajax.ajaxurl,
			data: {
				action: 'wrcpt_setup_selected_template',
				packtable: ptable,
				template: temp,
				nonce: wrcptajax.nonce
			},
			success: function(data, textStatus, XMLHttpRequest) {
				// Hold the "switch_template" for 3 seconds
				setTimeout(function() {
					// After 5 seconds, update the modal with the success message
					jQuery('#wrcpt-modal .wrcpt-modal-content').html(`<p>${wrcptajax.success_switch}</p>`);
					
					// Reload the page after another 2 seconds (optional delay for success message visibility)
					setTimeout(function() {
						window.location.reload();
					}, 2000); // 2 seconds delay
				}, 3000); // Hold for 3 seconds
			},
			error: function(MLHttpRequest, textStatus, errorThrown) {
				// Update the modal with the error message
				jQuery('#wrcpt-modal .wrcpt-modal-content').html(`<p>${wrcptajax.error_message}</p>`);
				setTimeout(function() {
					jQuery('#wrcpt-modal').fadeOut();
				}, 3000); // Hide the modal after 3 seconds
			}
		});
	}
}
function wrcptactivatetemp(tcount) {
    // Show the modal with the "creating" message
    jQuery('#wrcpt-modal').fadeIn().find('.wrcpt-modal-content').html(`
        <p>${wrcptajax.creating_message}</p>
        <img src="${wrcptajax.loading_image}" alt="Loading" />
    `);

	jQuery.ajax({
		type: 'POST',
		url: wrcptajax.ajaxurl,
		data: {
			action: 'wrcpt_activate_template',
			tempcount: tcount,
			nonce: wrcptajax.nonce
		},
        success: function(data, textStatus, XMLHttpRequest) {
            // Hold the "creating_message" for 3 seconds
            setTimeout(function() {
                // After 5 seconds, update the modal with the success message
                jQuery('#wrcpt-modal .wrcpt-modal-content').html(`<p>${wrcptajax.success_message}</p>`);
                
                // Reload the page after another 2 seconds (optional delay for success message visibility)
                setTimeout(function() {
                    window.location.reload();
                }, 2000); // 2 seconds delay
            }, 3000); // Hold for 3 seconds
        },
        error: function(MLHttpRequest, textStatus, errorThrown) {
            // Update the modal with the error message
            jQuery('#wrcpt-modal .wrcpt-modal-content').html(`<p>${wrcptajax.error_message}</p>`);
            setTimeout(function() {
                jQuery('#wrcpt-modal').fadeOut();
            }, 3000); // Hide the modal after 3 seconds
        }
	});
}