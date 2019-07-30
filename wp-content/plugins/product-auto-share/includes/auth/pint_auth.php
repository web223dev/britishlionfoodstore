<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $current_user;
	require_once(CED_PAS_PATH. '/Apis/pin_it.php');

	if(!isset($_GET['tab'])){
		$_GET['tab'] = "general";
	}
	/**
	 * save pinterest api keys
	 */
	if ($_GET['tab']=="pinterest" && isset($_POST['pinapp_id'])) {
		$app_id   	= 	sanitize_text_field($_POST['pinapp_id']);
		$app_sec  	=	sanitize_text_field($_POST['pinapp_sec']);
		$page_id  	=	sanitize_text_field($_POST['pin_board_id']);
		update_option('sharepro_pin_appid', $app_id);
		update_option('sharepro_pin_appsec', $app_sec);
		update_option('sharepro_pin_pageid', $page_id);
		delete_option("sharepro_pin_acctoken");
		delete_option("sharepro_pin_pageid");
	}


	/**
	 * saving pinterest token
	 */
	if( isset( $_GET['code'] ) && $_GET['tab'] == "pinterest" ) {
		$pint = new pin(get_option("sharepro_pin_appid"),get_option("sharepro_pin_appsec"));
		$results = $pint->get_token($_GET['code']);
		$dec_result = json_decode($results,true);
		if(isset($dec_result['access_token'])){
			$acc_token = $dec_result['access_token'];
		}
		if(isset($acc_token)){
			update_option( "sharepro_pin_acctoken", $acc_token );
			?>
			<div class="wsp_popup_notice" id="wsp_popup">
				<?php _e("Settings updated successfully.","product-auto-share")?> 
				&nbsp;&nbsp;&nbsp;
				<span id="wsp_popup_dismiss">
					<?php _e( "Dismiss", "product-auto-share" );?>
				</span>
			</div>
		<?php 
		}
	}
	
	/**
	 * saving boardid for pinterest
	 */
	if( isset( $_POST['pin_board'] ) && $_GET['tab'] == "pinterest" ) {
		if( $_POST[ 'pin_board' ] != '' ) {
			$pint = new pin( get_option( "sharepro_pin_appid" ), get_option( "sharepro_pin_appsec" ) );
			$pint->set_token( get_option( "sharepro_pin_acctoken" ) );
			$data = $pint->create_board( $_POST['pin_board'], $_POST['pin_board_desc'] );
			$deco_data = json_decode($data,true);
			update_option( 'sharepro_pin_pageid', $deco_data['data']['id'] );	
		} else {
			$boardid = sanitize_text_field( $_POST[ 'sharepro_pin_pageid' ] );
			update_option( 'sharepro_pin_pageid', $boardid );
		}
	}

	/**
	 * getting pinterest token url
	 */
	if( get_option( "sharepro_pin_acctoken" ) == "" && get_option( "sharepro_pin_appid" ) ) {
		//echo "djlfghfldjhfd";
		$pint 			= new pin( get_option( "sharepro_pin_appid" ), get_option( "sharepro_pin_appsec" ) );
		$redirect_uri 	= get_admin_url() . "admin.php?page=share-product-settings&tab=pinterest&section=gen";
		$scope_arr 		= array( "read_public", "write_public" );
		$auth_url 		= $pint->getin_url( $redirect_uri, $scope_arr );
		?>
	<!-- 	<a class="button button-primary pas-auth-btn" data-url="<?php echo $auth_url; ?>" data-adminurl="<?php echo get_admin_url(); ?>" >
			<?php _e( "AUTH FROM PINTEREST","product-auto-share" ) ?>
		</a> -->
		<a class="button button-primary pas-auth-btn" href=<?php echo $auth_url; ?>>
			<?php _e( "AUTH FROM PINTEREST","product-auto-share" ) ?>
		</a>
		<script type="application/javascript">
			// var url= jQuery('.pas-auth-btn').data('url');
			// var admiurl=jQuery('.pas-auth-btn').data('adminurl');
			// jQuery(document).ready(function(){
			// 	jQuery(document).on('click','.pas-auth-btn',function(){
			// 		var url= jQuery('.pas-auth-btn').data('url');
			// 		jQuery.ajax({
	  //                   url: url,
	  //                   crossDomain: true,
	  //                   dataType: 'jsonp',
	  //                   error: function(data){
	  //                   	window.location.replace(url);
	  //                   }
			// 		});
			// 	});				
			// });
			// function fun(response){
			// 	if(response.status!='failure'){
   //                window.location.replace(url);
   //          	}else if(response.status=='failure'){
   //          		alert('Something is Wrong!');
   //          		window.location.href = admiurl+"admin.php?page=share-product-settings&tab=pinterest&section=gen";
   //          	 	alert('Authuntication failed !');
   //          	}
			// }
			//fetch(auth_url)
		</script>
	<?php 
	}
