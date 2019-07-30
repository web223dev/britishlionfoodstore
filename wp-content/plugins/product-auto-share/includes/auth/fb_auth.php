<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $current_user;
	require (CED_PAS_PATH.'/Apis/Facebook/autoload.php');
	
	/**
	  * This is for saving facebook api keys.
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	if (isset($_POST['app_id'])){
		$app_id    = 	sanitize_text_field($_POST['app_id']);
		$app_sec   =		sanitize_text_field($_POST['app_sec']);
		$page_id   =		sanitize_text_field($_POST['page_id']);
		$shareOnprof='';
		$shareonPage='';
		if(isset($_POST['ced_pas_FBShare_on_prof'])){
			$shareOnprof = 		sanitize_text_field($_POST['ced_pas_FBShare_on_prof']);
		}
		if(isset($_POST['ced_pas_FBShare_on_page'])){
			$shareonPage = 		sanitize_text_field($_POST['ced_pas_FBShare_on_page']);
		}
		update_option('sharepro_appid', $app_id);
		update_option('ced_pas_FBShare_on_page', $shareonPage);
		update_option('ced_pas_FBShare_on_prof', $shareOnprof);
		update_option('sharepro_appsec', $app_sec);
		update_option('ced_pas_fb_pageid', $page_id);
		delete_option("sharepro_acctoken");
	}
	
	/**
	  * This is for saving saving access token.
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	if( isset($_REQUEST['code'])) {
			$app_id=get_option( 'sharepro_appid' );
			$app_secret=get_option( 'sharepro_appsec' );
			$code = sanitize_text_field($_REQUEST["code"]);
			$pageId = get_option("ced_pas_fb_pageid");
			$my_url   =  get_admin_url()."admin.php?page=share-product-settings";
			$token_url = "https://graph.facebook.com/v2.5/oauth/access_token?". "client_id=" . $app_id . "&redirect_uri=" . $my_url. "&client_secret=" . $app_secret . "&code=" . $code;
			$params = null;$access_token="";
			$response = wp_remote_get($token_url);
				if(isset($response['body']))
				{
					$params=json_decode($response['body'],true);	
					if(isset($params['access_token']))
						$access_token = $params['access_token'];
				}
				if($access_token!="")
				{
					update_option('sharepro_acctoken',$access_token);
					$page_login_url = ced_graph_url."/".$pageId."?fields=access_token&access_token=".$access_token;
					$pageResponse = wp_remote_get($page_login_url);
					
					if(isset($pageResponse['body']))
					{
						$pageDataBody=json_decode($pageResponse['body'],true);
						if(isset($pageDataBody['access_token']))
							$pageaccess_token = $pageDataBody['access_token'];
					}
					if($pageaccess_token!=""){
						update_option('ced_pas_fbpage_token',$pageaccess_token);
						?><div class="wsp_popup_notice" id="wsp_popup">
											Settings updated successfully. &nbsp;&nbsp;&nbsp;<span
											id="wsp_popup_dismiss">Dismiss</span>
											</div><?php 
					}
					
				}
	}
	
	/**
	  * This is for getting fb_auth url.
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */	
	if (get_option('sharepro_acctoken') == '' && get_option( 'sharepro_appid' ) !=''){
			$app_id=get_option( 'sharepro_appid' );
			$app_secret=get_option( 'sharepro_appsec' );
			$state = substr(md5(rand()), 0, 7);
			$_SESSION['state'] = $state;
			$my_url   =  get_admin_url()."admin.php?page=share-product-settings&tab=facebook";
			$loginUrl = "https://www.facebook.com/v2.5/dialog/oauth?client_id=". $app_id . "&redirect_uri=" . $my_url . "&state=".$state."&scope=email,public_profile,publish_pages,user_posts,publish_actions,manage_pages,read_insights";
			$permissions = array("email","public_profile","publish_pages","user_posts","publish_actions","manage_pages","read_insights");
			?><a class="button button-primary pas-auth-btn" href="<?php echo $loginUrl?>"><?php _e("AUTH FROM FACEBOOK","product-auto-share") ?></a><?php
		
	}