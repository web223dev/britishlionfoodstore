<?php
/* add fb like add this share to end of every post */
function sfsi_plus_social_buttons_below($content)
{
	global $post;
	$sfsi_section6=  unserialize(get_option('sfsi_plus_section6_options',false));
		 
	//new options that are added on the third questions
	//so in this function we are replacing all the past options 
	//that were saved under option6 by new settings saved under option8 
	$sfsi_section8=  unserialize(get_option('sfsi_plus_section8_options',false));
	$sfsi_plus_show_item_onposts = $sfsi_section8['sfsi_plus_show_item_onposts'];
	//new options that are added on the third questions
	
	//checking for standard icons
	if(!isset($sfsi_section8['sfsi_plus_rectsub']))
	{
		$sfsi_section8['sfsi_plus_rectsub'] = 'no';
	}
	if(!isset($sfsi_section8['sfsi_plus_rectfb']))
	{
		$sfsi_section8['sfsi_plus_rectfb'] = 'yes';
	}
	if(!isset($sfsi_section8['sfsi_plus_recttwtr']))
	{
		$sfsi_section8['sfsi_plus_recttwtr'] = 'no';
	}
	if(!isset($sfsi_section8['sfsi_plus_rectpinit']))
	{
		$sfsi_section8['sfsi_plus_rectpinit'] = 'no';
	}
	if(!isset($sfsi_section8['sfsi_plus_rectfbshare']))
	{
		$sfsi_section8['sfsi_plus_rectfbshare'] = 'no';
	}
	//checking for standard icons
		
	/* check if option activated in admin or not */ 
	//if($sfsi_section6['sfsi_plus_show_Onposts']=="yes")
	//removing following condition for now
	/*if($sfsi_section8['sfsi_plus_show_Onposts']=="yes")
	{*/
	$permalink = get_permalink($post->ID);
	$title = get_the_title();
	$sfsiLikeWith="45px;";
    
    /* check for counter display */
	//if($sfsi_section6['sfsi_plus_icons_DisplayCounts']=="yes")
		
	if($sfsi_section8['sfsi_plus_icons_DisplayCounts']=="yes")
	{
		$show_count=1;
		$sfsiLikeWith="75px;";
	}   
	else
	{
		$show_count=0;
	} 
	$common_options_check =	(($sfsi_section8['sfsi_plus_show_via_widget'] == "yes") || ($sfsi_section8['sfsi_plus_float_on_page'] == "yes") && (isset($sfsi_section8['sfsi_plus_float_page_position'])) || ($sfsi_section8['sfsi_plus_place_item_manually'] == "yes") || ($sfsi_section8['sfsi_plus_place_item_gutenberg'] == "yes"));
        
	//$txt=(isset($sfsi_section6['sfsi_plus_textBefor_icons']))? $sfsi_section6['sfsi_plus_textBefor_icons'] : "Share this Post with :" ;
	$txt=(isset($sfsi_section8['sfsi_plus_textBefor_icons']))? $sfsi_section8['sfsi_plus_textBefor_icons'] : "Please follow and like us:" ;
	//$float= $sfsi_section6['sfsi_plus_icons_alignment'];
	$float= $sfsi_section8['sfsi_plus_icons_alignment'];
	if($sfsi_section8['sfsi_plus_rectsub'] == 'yes' || $sfsi_section8['sfsi_plus_rectfb'] == 'yes' || $sfsi_section8['sfsi_plus_recttwtr'] == 'yes' || $sfsi_section8['sfsi_plus_rectpinit'] == 'yes' || $sfsi_section8['sfsi_plus_rectfbshare'] == 'yes')
	{
		$icons="<div class='sfsi_plus_Sicons ".$float."' style='float:".$float."'><div style='display: inline-block;margin-bottom: 0; margin-left: 0; margin-right: 8px; margin-top: 0; vertical-align: middle;width: auto;'><span>".$txt."</span></div>";
	}
	if($sfsi_section8['sfsi_plus_rectsub'] == 'yes')
	{
		if($show_count){$sfsiLikeWithsub = "93px";}else{$sfsiLikeWithsub = "64px";}
		if(!isset($sfsiLikeWithsub)){$sfsiLikeWithsub = $sfsiLikeWith;}
		$icons.="<div class='sf_subscrbe' style='display: inline-block;vertical-align: middle;width: auto;'>".sfsi_plus_Subscribelike($permalink,$show_count)."</div>";
	}
	if($sfsi_section8['sfsi_plus_rectfb'] == 'yes' || $common_options_check)
	{
		$icons.="<div class='sf_fb' style='display: inline-block;vertical-align: middle;width: auto;'>".sfsi_plus_FBlike($permalink,$show_count)."</div>";
	}
	
	if($sfsi_section8['sfsi_plus_rectfbshare'] == 'yes' || $common_options_check )
	{
		$icons.="<div class='sf_fb' style='display: inline-block;vertical-align: middle;width: auto;'>".sfsi_plus_FBshare($permalink,$show_count)."</div>";
	}

	if($sfsi_section8['sfsi_plus_recttwtr'] == 'yes')
	{
		if($show_count){$sfsiLikeWithtwtr = "77px";}else{$sfsiLikeWithtwtr = "56px";}
		if(!isset($sfsiLikeWithtwtr)){$sfsiLikeWithtwtr = $sfsiLikeWith;}
		$icons.="<div class='sf_twiter' style='display: inline-block;vertical-align: middle;width: auto;'>".sfsi_plus_twitterlike($permalink,$show_count)."</div>";
	}
	if($sfsi_section8['sfsi_plus_rectpinit'] == 'yes')
	{
		if($show_count){$sfsiLikeWithpinit = "100px";}else{$sfsiLikeWithpinit = "auto";}
	 	$icons.="<div class='sf_pinit' style='display: inline-block;text-align:left;vertical-align: middle;width: ".$sfsiLikeWithpinit.";'>".sfsi_plus_pinitpinterest($permalink,$show_count)."</div>";
	}
	$icons.="</div>";
    
	if(!is_feed() && !is_home() && !is_page())
	{
		$content =   $content .$icons;
	}
	//}   
	return $content;
}

/*subscribe like*/
function sfsi_plus_Subscribelike($permalink, $show_count)
{
	global $socialObj;
	$socialObj = new sfsi_plus_SocialHelper();
	
	$sfsi_plus_section2_options=  unserialize(get_option('sfsi_plus_section2_options',false));
	$sfsi_plus_section4_options = unserialize(get_option('sfsi_plus_section4_options',false));
	$sfsi_plus_section8_options=  unserialize(get_option('sfsi_plus_section8_options',false));
	$option5 =  unserialize(get_option('sfsi_plus_section5_options',false));
	
	$post_icons = $option5['sfsi_plus_follow_icons_language'];
	$visit_icon1 = SFSI_PLUS_DOCROOT.'/images/visit_icons/Follow/icon_'.$post_icons.'.png';
	$visit_iconsUrl = SFSI_PLUS_PLUGURL."images/";
		   
	if(file_exists($visit_icon1))
	{
		$visit_icon = $visit_iconsUrl."visit_icons/Follow/icon_".$post_icons.".png";
	}
	else
	{
		$visit_icon = $visit_iconsUrl."follow_subscribe.png";
	}
	
	$url = (isset($sfsi_plus_section2_options['sfsi_plus_email_url'])) ? $sfsi_plus_section2_options['sfsi_plus_email_url'] : 'javascript:void(0);';
	
	if($sfsi_plus_section4_options['sfsi_plus_email_countsFrom']=="manual")
	{    
		$counts=$socialObj->format_num($sfsi_plus_section4_options['sfsi_plus_email_manualCounts']);
	}
	else
	{
		$counts= $socialObj->SFSI_getFeedSubscriber(sanitize_text_field(get_option('sfsi_plus_feed_id',false)));           
	}
	
	if($sfsi_plus_section8_options['sfsi_plus_icons_DisplayCounts']=="yes")
	{
	 	$icon = '<a href="'.$url.'" target="_blank"><img src="'.$visit_icon.'" /></a>
		<span class="bot_no">'.$counts.'</span>';
	}
	else
	{
	 	$icon = '<a href="'.$url.'" target="_blank"><img src="'.$visit_icon.'" /></a>';
	}
	return $icon;
}
/*subscribe like*/

/*twitter like*/
function sfsi_plus_twitterlike($permalink, $show_count)
{
	global $socialObj;
	$socialObj = new sfsi_plus_SocialHelper();
	$twitter_text = '';
	$sfsi_plus_section5_options = unserialize(get_option('sfsi_plus_section5_options',false));
	$icons_language = $sfsi_plus_section5_options['sfsi_plus_icons_language'];
	if(!empty($permalink))
	{
		$postid = url_to_postid( $permalink );
	}
	if(!empty($postid))
	{
		$twitter_text = get_the_title($postid);
	}
	return $socialObj->sfsi_twitterSharewithcount($permalink,$twitter_text, $show_count, $icons_language);
}
/*twitter like*/

/* create fb like button */
function sfsi_plus_FBlike($permalink,$show_count)
{
    $send = 'false';
	$width = 180;

	$fb_like_html = '';

	$fb_like_html .= '<div class="fb-like" data-href="'.$permalink.'" data-action="like" data-size="small" data-show-faces="false" data-share="false"';
	
	if(1 == $show_count)
	{ 
		$fb_like_html .= 'data-layout="button_count"';
	}
	else
	{
		$fb_like_html .= 'data-layout="button"';
	}

	$fb_like_html .= ' ></div>';
	
	return $fb_like_html;
}

function sfsi_plus_FBshare($permalink,$show_count){

    $send 	 = 'false';
	$width   = 180;

	$fb_share_html = '';

	$fb_share_html .= '<div class="fb-share-button" data-action="share" data-href="'.$permalink.'" data-size="small" data-mobile-iframe="true"';

	if(1 == $show_count)
	{ 
		$fb_share_html .= 'data-layout="button_count"';
	}
	else
	{
		$fb_share_html .= 'data-layout="button"';
	}

	$fb_share_html .= '><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$permalink.';src=sdkpreparse" class="fb-xfbml-parse-ignore"></a></div>';
	
	return $fb_share_html;

}
/* create pinit button */

function sfsi_plus_pinitpinterest($permalink,$show_count)
{
	$pinit_html = '<a href="https://www.pinterest.com/pin/create/button/?url=&media=&description=" data-pin-do="buttonPin" data-pin-save="true"';

	if($show_count)
	{
		$pinit_html .= 'data-pin-count="beside"';
	}
	else
	{
		$pinit_html .= 'data-pin-count="none"';
	}

	$pinit_html .= '></a>';
	
	return $pinit_html;
}
	
/* add all external javascript to wp_footer */        
function sfsi_plus_footer_script()
{
	$sfsi_section1=  unserialize(get_option('sfsi_plus_section1_options',false));
	$sfsi_section6=  unserialize(get_option('sfsi_plus_section6_options',false));
	$sfsi_section8=  unserialize(get_option('sfsi_plus_section8_options',false));
	$sfsi_section2=  unserialize(get_option('sfsi_plus_section2_options',false));


	$sfsi_plus_section5_options = unserialize(get_option('sfsi_plus_section5_options',false));
	
	if(
		isset($sfsi_plus_section5_options['sfsi_plus_icons_language']) &&
		!empty($sfsi_plus_section5_options['sfsi_plus_icons_language'])
	)
	{
		$icons_language = $sfsi_plus_section5_options['sfsi_plus_icons_language'];
	}
	else
	{
		$icons_language = "en_US";
	}
	
	if(!isset($sfsi_section8['sfsi_plus_rectsub']))
	{
		$sfsi_section8['sfsi_plus_rectsub'] = 'no';
	}
	if(!isset($sfsi_section8['sfsi_plus_rectfb']))
	{
		$sfsi_section8['sfsi_plus_rectfb'] = 'yes';
	}
	if(!isset($sfsi_section8['sfsi_plus_recttwtr']))
	{
		$sfsi_section8['sfsi_plus_recttwtr'] = 'no';
	}
	if(!isset($sfsi_section8['sfsi_plus_rectpinit']))
	{
		$sfsi_section8['sfsi_plus_rectpinit'] = 'no';
	}

	if(!isset($sfsi_section8['sfsi_plus_rectfbshare']))
	{
		$sfsi_section8['sfsi_plus_rectfbshare'] = 'no';
	}
	$common_options_check =	(($sfsi_section8['sfsi_plus_show_via_widget'] == "yes") || ($sfsi_section8['sfsi_plus_float_on_page'] == "yes") && (isset($sfsi_section8['sfsi_plus_float_page_position'])) || ($sfsi_section8['sfsi_plus_place_item_manually'] == "yes") || ($sfsi_section8['sfsi_plus_place_item_gutenberg'] == "yes"));
	if($sfsi_section1['sfsi_plus_facebook_display']=="yes"){
		if( ($sfsi_section8['sfsi_plus_rectfb'] == "yes" && $sfsi_section8['sfsi_plus_show_item_onposts'] == "yes" && $sfsi_section8['sfsi_plus_display_button_type'] == "standard_buttons" ) || ($sfsi_section8['sfsi_plus_rectfbshare'] == "yes" && $sfsi_section8['sfsi_plus_show_item_onposts'] == "yes" && $sfsi_section8['sfsi_plus_display_button_type'] == "standard_buttons") || $common_options_check)
		{?>
			<!--facebook like and share js -->                   
			<div id="fb-root"></div>
			
			<script>
			(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/<?php echo $icons_language;?>/sdk.js#xfbml=1&version=v2.5";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
		<?php
		}
	}
	if( $sfsi_section1['sfsi_plus_youtube_display']=="yes") { 
		if( $sfsi_section2['sfsi_plus_youtube_page']=="yes" || $sfsi_section2['sfsi_plus_youtube_follow']=="yes") { 
		?>
		
		<!-- youtube share -->
		<script type="text/javascript">
			(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/platform.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
		</script>
		<?php
		}
	}
	if($sfsi_section1['sfsi_plus_linkedin_display']=="yes")
	{ 
		if($sfsi_section2['sfsi_plus_linkedin_follow'] == "yes" || $sfsi_section2['sfsi_plus_linkedin_recommendBusines'] == "yes" || $sfsi_section2['sfsi_plus_linkedin_SharePage'] == "yes" ){
			if($icons_language == 'ar_AR')
			{
				$icons_language = 'ar_AE';
			}
			if($common_options_check)
			 {
	
			?>	
				<!-- linkedIn share and  follow js -->
				<script src="//platform.linkedin.com/in.js">lang: <?php echo $icons_language;?></script>
				<?php
			 }
		}
		
	}
	if($sfsi_section1['sfsi_plus_pinterest_display']=="yes" && ($sfsi_section2['sfsi_plus_pinterest_pingBlog'] == "yes")){
		if( ($sfsi_section8['sfsi_plus_rectpinit'] == "yes" && $sfsi_section8['sfsi_plus_show_item_onposts'] == "yes" && $sfsi_section8['sfsi_plus_display_button_type'] == "standard_buttons") || $common_options_check) {?>
			<!--pinit js -->
			
			<script src="//assets.pinterest.com/js/pinit.js"> 
		 </script>
		<?php
		}
	}
	
	if($sfsi_section1['sfsi_plus_twitter_display']=="yes"){
		if( ($sfsi_section8['sfsi_plus_recttwtr'] == "yes"  && $sfsi_section8['sfsi_plus_show_item_onposts'] == "yes" && $sfsi_section8['sfsi_plus_display_button_type'] == "standard_buttons") || $common_options_check) {?>
			<!-- twitter JS End -->
			<script>
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>	
		<?php
		}
	}
	
	/* activate footer credit link */
	if(get_option('sfsi_plus_footer_sec')=="yes")
	{
	    if(!is_admin())
	    {
            //$footer_link='<div class="sfsiplus_footerLnk" style="margin: 0 auto;z-index:1000; absolute; text-align: center;">Social media & sharing icons powered by  <a href="https://wordpress.org/plugins/ultimate-social-media-icons/" target="_new">UltimatelySocial</a> ';
			$footer_link='<div class="sfsiplus_footerLnk" style="margin: 0 auto;z-index:1000; absolute; text-align: center;"><a href="https://www.ultimatelysocial.com/?utm_source=usmplus_settings_page&utm_campaign=credit_link_to_homepage&utm_medium=banner" target="_new">Social media & sharing icons </a> powered by UltimatelySocial';
	    	$footer_link.="</div>";
	    	echo $footer_link;
	    }
	}    
        
}
/* filter the content of post */
//commenting following code as we are going to extend this functionality 
//add_filter('the_content', 'sfsi_plus_social_buttons_below');

/* update footer for frontend and admin both */ 
if(!is_admin())
{
	global $post;
	add_action( 'wp_footer', 'sfsi_plus_footer_script' );	
	if(!function_exists('sfsi_plus_check_PopUp')){
		include('sfsi_frontpopUp.php');
	}
	add_action('wp_footer','sfsi_plus_check_PopUp');
	add_action('wp_footer','sfsi_plus_frontFloter');	 	     
}		 				    
if(is_admin())
{
	add_action('in_admin_footer', 'sfsi_plus_footer_script');	
}
/* ping to vendor site on updation of new post */


//<---------------------* Responsive icons *----------------->
function sfsi_plus_social_responsive_buttons($content,$option8,$server_side=false)
{
	
	if( (isset($option8["sfsi_plus_show_item_onposts"])&& $option8["sfsi_plus_show_item_onposts"]=="yes" && isset($option8["sfsi_plus_display_button_type"]) && $option8["sfsi_plus_display_button_type"]=="responsive_button") || $server_side ):
    $option2 = unserialize(get_option('sfsi_plus_section2_options',false));
    $option4 = unserialize(get_option('sfsi_plus_section4_options',false));
	$icons= "";
	$sfsi_plus_responsive_icons = (isset($option8["sfsi_plus_responsive_icons"])?$option8["sfsi_plus_responsive_icons"]:null);
	
	if(is_null($sfsi_plus_responsive_icons) ){
		return ""; // dont return anything if options not set;
	}
	$icon_width_type = $sfsi_plus_responsive_icons["settings"]["icon_width_type"];
	if($option4['sfsi_plus_display_counts']=='yes' &&isset($sfsi_plus_responsive_icons["settings"]['show_count'])&&$sfsi_plus_responsive_icons["settings"]['show_count']=="yes"):
       	
       	$counter_class="sfsi_plus_responsive_with_counter_icons";
       	$couter_display="inline-block";
       	
   	else:
       $counter_class="sfsi_plus_responsive_without_counter_icons";
       $couter_display="none";
    endif;
    $counts = sfsi_plus_getCounts(true);
   	$count = 0;
   	if(isset($counts['email_count'])){
   		$count = (int)($counts['email_count'])+$count;
   	}
   	if(isset($counts['fb_count'])){
   		$count = (int)($counts['fb_count'])+$count;
   	}
   	if(isset($counts['twitter_count'])){
   		$count = (int)($counts['twitter_count'])+$count;
   	}else{
   		$count = 0;
   	}
	$icons .= "<div class='sfsi_plus_responsive_icons' style='display:inline-block; ".($icon_width_type=="Fully Responsive"?"width:100%;display:flex; ":'width:100%')."' data-icon-width-type='".$icon_width_type."' data-icon-width-size='".$sfsi_plus_responsive_icons["settings"]['icon_width_size']."' data-edge-type='".$sfsi_plus_responsive_icons["settings"]['edge_type']."' data-edge-radius='".$sfsi_plus_responsive_icons["settings"]['edge_radius']."'  >";
	$sfsi_plus_anchor_div_style="";
	if($sfsi_plus_responsive_icons["settings"]["edge_type"]==="Round"){
		$sfsi_plus_anchor_div_style.=" border-radius:";
		if($sfsi_plus_responsive_icons["settings"]["edge_radius"]!==""){
			$sfsi_plus_anchor_div_style.=$sfsi_plus_responsive_icons["settings"]["edge_radius"].'px; ';
		}else{
			$sfsi_plus_anchor_div_style.='0px; ';
		}
	}
	
	ob_start();?>
			<div class="sfsi_plus_responsive_icons_count sfsi_plus_<?php echo ($icon_width_type=="Fully responsive"?'responsive':'fixed'); ?>_count_container sfsi_plus_<?php echo strtolower($sfsi_plus_responsive_icons['settings']['icon_size']); ?>_button"  style='display:<?php echo $couter_display; ?>;text-align:center; background-color:<?php echo $sfsi_plus_responsive_icons['settings']['counter_bg_color'] ;?>;color:<?php echo $sfsi_plus_responsive_icons['settings']['counter_color'] ;?>; <?php echo $sfsi_plus_anchor_div_style; ?>;' >
				<h3 style="color:<?php echo $sfsi_plus_responsive_icons['settings']['counter_color'] ;?>; " ><?php echo $count; ?></h3>
				<h6 style="color:<?php echo $sfsi_plus_responsive_icons['settings']['counter_color'] ;?>;" ><?php echo $sfsi_plus_responsive_icons['settings']["share_count_text"]; ?></h6>
			</div>
	<?php
		$icons.= ob_get_contents();
		ob_end_clean();
	$icons .= "\t<div class='sfsi_plus_icons_container ".$counter_class." sfsi_plus_".strtolower($sfsi_plus_responsive_icons['settings']['icon_size'])."_button_container sfsi_plus_icons_container_box_".($icon_width_type!=="Fixed icon width"?"fully":'fixed')."_container ' style='".($icon_width_type!=="Fixed icon width"?"width:100%;display:flex; ":'width:auto')."; text-align:center;' >";
	$socialObj = new sfsi_plus_SocialHelper();
	//styles
	$sfsi_plus_anchor_style="";
	if($sfsi_plus_responsive_icons["settings"]["text_align"]=="Centered"){
		$sfsi_plus_anchor_style.='text-align:center;';
	}
	if($sfsi_plus_responsive_icons["settings"]["margin"]!==""){
		$sfsi_plus_anchor_style.='margin-left:'.$sfsi_plus_responsive_icons["settings"]["margin"]."px; ";
		// $sfsi_plus_anchor_style.='margin-bottom:'.$sfsi_plus_responsive_icons["settings"]["margin"]."px; ";
	}
	//styles
	 
	if($sfsi_plus_responsive_icons['settings']['icon_width_type']==="Fixed icon width") 
	{
		$sfsi_plus_anchor_div_style.='width:'.$sfsi_plus_responsive_icons['settings']['icon_width_size'].'px;';
	}
	else{
		$sfsi_plus_anchor_style.=" flex-basis:100%;";
		$sfsi_plus_anchor_div_style.=" width:100%;";
	}
	// var_dump($sfsi_plus_anchor_style,$sfsi_plus_anchor_div_style);
	foreach($sfsi_plus_responsive_icons['default_icons'] as $icon=>$icon_config){
		// var_dump($icon_config);
		// $current_url =  $socialObj->sfsi_get_custom_share_link(strtolower($icon));
		switch($icon){
			case "facebook":$share_url="https://www.facebook.com/sharer/sharer.php?u=".get_permalink(); break;
           	case "Twitter":$twitter_text = $share_url = "https://twitter.com/intent/tweet?text=".get_the_title()."&url=".get_permalink();break;
			case "Follow":
				if(isset($option2['sfsi_plus_email_icons_functions']) && $option2['sfsi_plus_email_icons_functions'] == 'sf')
				{
					$share_url = (isset($option2['sfsi_plus_email_url']))
						? $option2['sfsi_plus_email_url']
						: 'javascript:';	
				}
				elseif(isset($option2['sfsi_plus_email_icons_functions']) && $option2['sfsi_plus_email_icons_functions'] == 'contact')
				{
					$share_url = (isset($option2['sfsi_plus_email_icons_contact']) && !empty($option2['sfsi_plus_email_icons_contact']))
						? "mailto:".$option2['sfsi_plus_email_icons_contact']
						: 'javascript:';	
				}
				elseif(isset($option2['sfsi_plus_email_icons_functions']) && $option2['sfsi_plus_email_icons_functions'] == 'page')
				{
					$share_url = (isset($option2['sfsi_plus_email_icons_pageurl']) && !empty($option2['sfsi_plus_email_icons_pageurl']))
						? $option2['sfsi_plus_email_icons_pageurl']
						: 'javascript:';	
				}
				elseif(isset($option2['sfsi_plus_email_icons_functions']) && $option2['sfsi_plus_email_icons_functions'] == 'share_email')
				{
					$subject = stripslashes($option2['sfsi_plus_email_icons_subject_line']);
					$subject = str_replace('${title}', $socialObj->sfsi_get_the_title(), $subject);
					$subject = str_replace('"', '', str_replace("'", '', $subject));
					$subject = html_entity_decode(strip_tags($subject), ENT_QUOTES,'UTF-8');
					$subject = str_replace("%26%238230%3B", "...", $subject);				
					$subject = rawurlencode($subject);

					$body = stripslashes($option2['sfsi_plus_email_icons_email_content']);
					$body = str_replace('${title}', $socialObj->sfsi_get_the_title(), $body);	
					$body = str_replace('${link}',  trailingslashit($socialObj->sfsi_get_custom_share_link('email')), $body);
					$body = str_replace('"', '', str_replace("'", '', $body));
					$body = html_entity_decode(strip_tags($body), ENT_QUOTES,'UTF-8');
					$body = str_replace("%26%238230%3B", "...", $body);
					$body = rawurlencode($body);				
					$share_url = "mailto:?subject=$subject&body=$body";
				}
				else
				{
					$share_url = (isset($option2['sfsi_plus_email_url']))
						? $option2['sfsi_plus_email_url']
						: 'javascript:';	
				}
				break;
           
		}
		$icons.="\t\t"."<a ".sfsi_plus_checkNewWindow()." href='".($icon_config['url']==""?$share_url:$icon_config['url'])."' style='".($icon_config['active']=='yes'?($sfsi_plus_responsive_icons['settings']['icon_width_type']==="Fixed icon width"?'display:inline-flex':'display:block'):'display:none').";".$sfsi_plus_anchor_style."' class=".($sfsi_plus_responsive_icons['settings']['icon_width_type']==="Fixed icon width"?'sfsi_plus_responsive_fixed_width':'sfsi_plus_responsive_fluid')." >"."\n";
		$icons.="\t\t\t<div class='sfsi_plus_responsive_icon_item_container sfsi_plus_responsive_icon_".strtolower($icon)."_container sfsi_plus_".strtolower($sfsi_plus_responsive_icons['settings']['icon_size'])."_button ".($sfsi_plus_responsive_icons['settings']['style']=="Gradient"?'sfsi_plus_responsive_icon_gradient':'').( " sfsi_plus_".(strtolower($sfsi_plus_responsive_icons['settings']['text_align'])=="centered"?'centered':'left-align')."_icon")."' style='".$sfsi_plus_anchor_div_style." ' >"."\n";
		$icons.="\t\t\t\t<img style='max-height: 25px;display:unset;margin:0' src='".SFSI_PLUS_PLUGURL."images/responsive-icon/".$icon.('Follow'===$icon?'.png':'.svg')."'>"."\n";
		$icons.="\t\t\t\t<span style='color:#fff' >".($icon_config["text"])."</span>"."\n";
		$icons.="\t\t\t</div>"."\n";
		$icons.="\t\t</a>"."\n\n";
	}
	$icons.="</div></div><!--end responsive_icons-->";
	return $icons;
	endif;
}
?>
