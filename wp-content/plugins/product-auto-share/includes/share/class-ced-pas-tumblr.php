<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require (CED_PAS_PATH.'/Apis/tumblr/tumblrPHP.php');
class ced_pas_tumblr {
	/**
	 * This function is for sharing on tumblr.
	 * @name ced_pas_tumblrshare($link,$prodid)
	 * @param1 product link
	 * @param2 productid
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function ced_pas_tumblrshare($link,$prodid){
		$app_id= get_option('wsp_tumblr_settings')['tumblr_app_id'];
		if($app_id == ""){
			return false;
		}
	    $app_id = get_option('wsp_tumblr_settings')['tumblr_app_id'];
	    $app_secret	= get_option('wsp_tumblr_settings')['tumblr_app_sec'];
		$accessToken    =  get_option('wsp_tumblr_settings')['tumblr_access'];
		$accessTokenSecret	= get_option('wsp_tumblr_settings')['tumblr_access_sec'];
		$imgurl = wp_get_attachment_url( get_post_thumbnail_id($prodid) );		
		$msg = get_the_title($prodid);
		$blogUrl = get_option('wsp_tumblr_settings')['tumblr_blog_url'];
		// $tms= get_option('ced_pas_custom_msg');
		$tms=get_post_meta($prodid,'ced_pas_custom_msg',true);
		$params = array(
				'type'=>"link",
				'url'=> $link,
				'title'=>$msg,
				'description'=>$tms,
				'thumbnail'=> $imgurl
		);
		try {
            $tumblr = new Tumblr( $app_id,$app_secret, $accessToken,$accessTokenSecret);
			$response = $tumblr->oauth_post("/blog/".$blogUrl."/post",$params);		
			if($response->meta->status == 201){
				$postId = $response->response->id;			
				update_post_meta($prodid,'ced_pas_tumblr',$postId);
				if(get_option("ced_pas_tumblr_error") != ""){
					delete_option("ced_pas_tumblr_error");
				}
				return true ;
			}else{		
				$errormsg = "(".$response->response->errors[0].")";
				update_option("ced_pas_tumblr_error",$response->meta->msg.$errormsg);
				return false;
			}
		} catch (Exception $e) {			
			update_option("ced_pas_tumblr_error",$e->getMessage());
			return false;
		}
		
	}
}
