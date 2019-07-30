
jQuery(document).ready(function($) {
		
		(function(d, s, id) {
	 		var js, fjs = d.getElementsByTagName(s)[0];
	     	if (d.getElementById(id)) return;
		 	js = d.createElement(s); js.id = id;
		 	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId="+id.id;
		  	fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		
		jQuery('#wsp_popup_notice').animate({
			opacity : 'show',
			height : 'show'
		}, 500);

		jQuery('#wsp_popup_dismiss').click(function() {
			jQuery('#wsp_popup').animate({
				opacity : 'hide',
				height : 'hide'
			}, 500);

		});
		jQuery('#check').animate({
			opacity : 'show',
			height : 'show'
		}, 500);

		jQuery('#dismis').click(function() {
			jQuery('#check').animate({
				opacity : 'hide',
				height : 'hide'
			}, 500);

		});
		jQuery('#product_autoshare_img_send_email').click(function(e) {
			e.preventDefault();
			jQuery(".product_autoshare_img_email_image p").html("");
			jQuery(".product_autoshare_img_email_image p").removeClass("ced_pas_email_image_error");
			jQuery(".product_autoshare_img_email_image p").removeClass("ced_pas_email_image_success");

			var email = jQuery('.product_autoshare_img_email_field').val();
			jQuery("#product_autoshare_loader").removeClass("hide");
			jQuery("#product_autoshare_loader").addClass("dislay");
			//alert(ajax_url);
			$.ajax({
		        type:'POST',
		        url :ajax_url,
		        data:{action:'product_autoshare_send_mail',flag:true,emailid:email},
		        success:function(data)
		        {
					var new_data = JSON.parse(data);
					jQuery("#product_autoshare_loader").removeClass("dislay");
					jQuery("#product_autoshare_loader").addClass("hide");
					if(new_data['status']==true)
			        {
						jQuery(".product_autoshare_img_email_image p").addClass("ced_pas_email_image_success");
						jQuery(".product_autoshare_img_email_image p").html(new_data['msg']);
			        }
			        else
			        {
						jQuery(".product_autoshare_img_email_image p").addClass("ced_pas_email_image_error");
						jQuery(".product_autoshare_img_email_image p").html(new_data['msg']);
			        }
		        }
	    	});
		});
		if(typeof replace != 'undefined'){
			window.location.replace(replace.url);
		}
		
		if(typeof redirect != 'undefined'){
			window.location.replace(redirect.newsletter);
		}
		if (window.location.href.indexOf("&ced_pas=true") > -1) {
					//  jQuery('#menu-posts-product')
				    var selectedclassList = document.getElementById('menu-posts-product').className;	    
				    var unselectedclass  = document.getElementById('toplevel_page_share-product-settings').className;
				    var selectedchildClass = jQuery('#menu-posts-product').children().first()[0].className;
				    var unselectedchildclass = jQuery('#toplevel_page_share-product-settings').children().first()[0].className
				    jQuery('#menu-posts-product').removeClass();
				    jQuery('#menu-posts-product>ul>li').removeClass('current');
				    jQuery('#toplevel_page_share-product-settings').removeClass();
				   // jQuery('#toplevel_page_share-product-settings').children().first()[0].removeClass();
				    jQuery('#menu-posts-product>a').removeClass();
				    jQuery('#menu-posts-product').addClass(unselectedclass);		   
				    jQuery('#toplevel_page_share-product-settings').addClass(selectedclassList);
				    jQuery('#menu-posts-product>a').addClass(unselectedchildclass);
				    jQuery('#toplevel_page_share-product-settings>a').addClass(selectedchildClass);
				    jQuery('#toplevel_page_share-product-settings>ul').children().eq(2).addClass('current');
				   
				    //Appending bulk actions
		    		//jQuery('<option>').val('fb_share').text('Share to Facebook').appendTo("select[name='action']");
			        jQuery('<option>').val('fb_share').text('Share to Facebook').appendTo("select[name='action2']");
			        //jQuery('<option>').val('tweet').text('Share to Twitter').appendTo("select[name='action']");
			        jQuery('<option>').val('tweet').text('Share to Twitter').appendTo("select[name='action2']");
			        
			        //Removing some bulk actions			        
			        jQuery("#bulk-action-selector-top option[value='trash']").remove();
			        jQuery("#bulk-action-selector-top option[value='edit']").remove();
			        jQuery("#bulk-action-selector-bottom option[value='trash']").remove();
			        jQuery("#bulk-action-selector-bottom option[value='edit']").remove();
			        //changing the cutom urls
			    	jQuery(".all>a").attr('href', function() {
						return this.href + '&ced_pas=true';
					});
					jQuery(".publish>a").attr('href', function() {
						return this.href + '&ced_pas=true';
					});		
					jQuery(".trash>a").attr('href', function() {
						return this.href + '&ced_pas=true';
					});       
		}
  // adding custom messages
		jQuery('#ced_pasmsg').click(function() {
				var text =jQuery('#ced_pas_text').val();
				var post_id = jQuery(this).data('post_id');
				var meta_id = jQuery(this).data('meta_id');
				if(typeof task != 'undefined'){												
					jQuery.ajax({
			            type: "POST",
			            url: task.ajaxurl,
			            data: { 
			            	post_id: post_id,
			            	nonce:task.nonce,
			                text:text,
			                action: 'ced_pas_save_text' 
			            },
			            success: function(){
			                jQuery('#ced_saveResult').html("<div id='ced_savemesage' class='ced_success'>");
			                jQuery('#ced_saveResult').append("<p>Message Saved Successfully</p></div>").show();
			                jQuery('#meta-'+meta_id+'-value').val(text);
			             }, 
			             timeout: 5000
			   
			        }); 
					setTimeout("jQuery('#ced_saveResult').hide('slow');", 5000);
				      return false; 
				}
		});
		
		
		

	});

	