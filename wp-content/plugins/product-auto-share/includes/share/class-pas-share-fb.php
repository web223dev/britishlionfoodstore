<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require (CED_PAS_PATH.'/Apis/Facebook/autoload.php');
class ced_pas_fbshare{
	
	/**
	  * This function is for sharing on Facebook.
	  * @name ced_pas_shareonfb($link,$prodid)
	  * @param1 product link 
	  * @param2 productid
	  * @author CedCommerce <plugins@cedcommerce.com>
	  * @link http://cedcommerce.com/
	  */
	function ced_pas_shareonfb($link,$prodid){
		//print_r($link);die();
		$app_id= get_option( 'sharepro_appid' );
		$app_secret= get_option( 'sharepro_appsec' );
		if($app_id == ""){
			return false;
		}
		$access_token= get_option('sharepro_acctoken');
		$page_access_token = get_option('ced_pas_fbpage_token');
		$shareOnprofile = get_option('ced_pas_FBShare_on_prof');
		$shareonpage  = get_option('ced_pas_FBShare_on_page');
		$page = get_option('sharepro_pageid');
		$msg = get_the_title($prodid);
		//$msg .= "\n".get_option('ced_pas_custom_msg');
		$msg .= "\n".get_post_meta($prodid,'ced_pas_custom_msg',true);
		$onpost= '/'.$page.'/feed';	
		$fb = new Facebook\Facebook([
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'default_graph_version' => 'v2.5',
		]);
		$linkData = [
				'link' => $link,
				'message' => $msg,
		];
		//print_r($linkData);die;
		try {
			if(get_post_meta($prodid,"fbpage_postid",true) == ""){
				if($shareonpage == "FBShare_on_page"){
					$pageRequest = $fb->post($onpost,$linkData ,$page_access_token);					
					$pagebody=$pageRequest->getDecodedBody();
					update_post_meta($prodid,'fbpage_postid',$pagebody['id']);
					if(get_option("Fb_error") != ""){
						delete_option("Fb_error");
					}
					
				}
			}
			// if(get_post_meta($prodid,"fb_postid",true) == ""){
				if($shareOnprofile == "FBShare_on_prof"){
					$response = $fb->post('/me/feed',$linkData ,$access_token);
					$body=$response->getDecodedBody();
					update_post_meta($prodid,'fb_postid',$body['id']);
					if(get_option("Fb_error") != ""){
						delete_option("Fb_error");
					}				
				}
			// }
			return true ;
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			update_option("Fb_error",$e->getMessage());	
			return false;
			
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			update_option("Fb_error",$e->getMessage());
			return false;
		}
	}	
	
	/**
	 * This function is for getting Facebook accounts.
	 * @name ced_pas_get_facebook_accounts()
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function ced_pas_get_facebook_accounts(){
		$app_id= get_option( 'sharepro_appid' );
		$app_secret= get_option( 'sharepro_appsec' );
		if($app_id == ""){
			return false;
		}
		$access_token= get_option('sharepro_acctoken');	
		$fb = new Facebook\Facebook([
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'default_graph_version' => 'v2.5',
		]);
		$response = $fb->get('/me?fields=accounts,name,email',$access_token);
		$graphObject = $response->getDecodedBody();
		$pages       = $graphObject['accounts'];
		foreach ($pages as $page){
			if(is_array($page)){
				foreach ($page as $page1){
					//print_r($page1['name']);
					$pic = ced_graph_url.$page1['id']."/picture?type=square";					
					echo "<img src=".$pic.">";
					echo "<br>";
				}
			}
		}
		
	}
	
}