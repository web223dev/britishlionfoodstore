<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	require_once(CED_PAS_PATH. '/Apis/Linkedin.php');
	if(!isset($_GET['tab'])){
		$_GET['tab'] = "general";
	}
	if ($_GET['tab']=="linkedin" && isset($_POST['linkedin_app_id'])) {
			$linkedin_settings = array(
				"linkedin_apiid"  => sanitize_text_field($_POST['linkedin_app_id']),
				"linkedin_appsec" => sanitize_text_field($_POST['linkedin_app_sec'])
			);	
			update_option('linked_settings',$linkedin_settings);
			delete_option("linked_token");
	}
	
	/**
	 * getting linkedin access token
	 */
	if ($_GET['tab'] == "linkedin" && isset($_GET['code'])) {
		$config = array(
				'api_key' => get_option("linked_settings")['linkedin_apiid'],
				'api_secret' => get_option("linked_settings")['linkedin_appsec'],
				'callback_url' => get_admin_url()."admin.php?page=share-product-settings&tab=linkedin"
		);
		
		$linked = new LinkedIn($config);
		$access_token=$linked->getAccessToken($_GET['code']);
		if(isset($access_token)){
			update_option("linked_token",$access_token);
			?><div class="wsp_popup_notice" id="wsp_popup">
							Settings updated successfully. &nbsp;&nbsp;&nbsp;<span
							id="wsp_popup_dismiss">Dismiss</span>
							</div><?php 
			}
			
		}

	/**
	 * Generating linkedin auth login url
	 */	
	if(get_option("linked_token") == ''  &&  get_option("linked_settings")["linkedin_apiid"] !=""){
		
		$config = array(
				'api_key' => get_option("linked_settings")['linkedin_apiid'],
				'api_secret' => get_option("linked_settings")['linkedin_appsec'],
				'callback_url' => get_admin_url()."admin.php?page=share-product-settings&tab=linkedin"
		);
	
		$linked = new LinkedIn($config);
		$scope = array(
				LinkedIn::SCOPE_BASIC_PROFILE,
				LinkedIn::SCOPE_EMAIL_ADDRESS,
				LinkedIn	::SCOPE_WRITE_SHARE,
		);
		$login_url=$linked->getLoginUrl($scope);
		?>
				<a class="button button-primary pas-auth-btn" href=<?php echo $login_url; ?>><?php _e("AUTH FROM LINKEDIN","product-auto-share")?></a>
				<?php 
	}
