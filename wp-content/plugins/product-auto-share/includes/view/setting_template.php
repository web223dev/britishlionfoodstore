<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * This file is for setting panel html. This is included file if license is  validated.
 */
	global $current_user;
	require_once( CED_PAS_PATH.'includes/auth/fb_auth.php' );
	require_once( CED_PAS_PATH.'includes/auth/pint_auth.php' );
	require_once( CED_PAS_PATH.'includes/auth/linkedin_auth.php' );
	require_once( CED_PAS_PATH.'Apis/pin_it.php' );	
	
	if(!isset($_GET['tab'])){
		$_GET['tab'] = "general";
	}
	if (isset($_POST['twitter_app_id']) && $_GET['tab']=='twitter'){
		$app_id    		= 		sanitize_text_field($_POST['twitter_app_id']);
		$app_sec   		=		sanitize_text_field($_POST['twitter_app_sec']);
		$app_token   	=		sanitize_text_field($_POST['twitter_access']);
		$app_token_sec	= 		sanitize_text_field($_POST['twitter_access_sec']);
		$twitter_settings = array(
				"twitter_app_id" =>$app_id,
				"twitter_app_sec" =>$app_sec,
				"twitter_access" => $app_token,
				"twitter_access_sec" =>$app_token_sec
		);
		update_option("wsp_twitter_settings", $twitter_settings);	
	}
	if ( isset( $_POST[ 'tumblr_app_id' ] ) && $_GET[ 'tab' ] == 'tumblr' ) {
		$app_id1    		= sanitize_text_field($_POST['tumblr_app_id']);
		$app_sec1   		= sanitize_text_field($_POST['tumblr_app_sec']);
		$app_token1   		= sanitize_text_field($_POST['tumblr_access']);
		$app_token_sec1		= sanitize_text_field($_POST['tumblr_access_sec']);
		$blogUrl        	= sanitize_text_field($_POST["tumblr_blog_url"]);
		$tumblr_settings = array(
			"tumblr_app_id" 	=> $app_id1,
			"tumblr_app_sec" 	=> $app_sec1,
			"tumblr_access" 	=> $app_token1,
			"tumblr_access_sec" => $app_token_sec1,
			"tumblr_blog_url"  	=> $blogUrl
		);
		update_option( "wsp_tumblr_settings", $tumblr_settings );
	}

	if(isset($_GET["ced_pas_close"]) && $_GET["ced_pas_close"])
	{
		unset($_GET["ced_pas_close"]);
		if(!session_id())
			session_start();
		$_SESSION["product_auto_share_hide_email"]=true;
		wp_redirect(admin_url('admin.php').'?page=share-product-settings');
		exit();
	}
	/**
	 * Save general settings 
	 * 
	 */
	if( isset( $_POST['submit'] ) && ( $_GET[ 'tab' ] == "general" || $_GET['tab'] == "" ) ) {
		$fb			= isset( $_POST[ 'fb' ] ) ? sanitize_text_field( $_POST[ 'fb' ] ) : '';
		$tweet 		= isset( $_POST[ 'tweet' ] ) ? sanitize_text_field( $_POST[ 'tweet' ] ) : '';
		$pint 		= isset( $_POST[ 'pint' ] ) ? sanitize_text_field( $_POST[ 'pint' ] ) : '';
		$fb_box 	= isset( $_POST[ 'fb_combox' ] ) ? sanitize_text_field( $_POST[ 'fb_combox' ] ) : '';
		$link_in    = isset( $_POST[ 'LinkedIn' ] ) ? sanitize_text_field( $_POST[ 'LinkedIn' ] ) : '';
		$fb_reviews = isset( $_POST[ 'fb_reviews' ] ) ? sanitize_text_field( $_POST[ 'fb_reviews' ] ) : '';
		$mail       = isset( $_POST[ 'mail' ] ) ? sanitize_text_field( $_POST[ 'mail' ] ) : '';
		$tumblr     = isset( $_POST[ 'tumblr' ] ) ? sanitize_text_field( $_POST[ 'tumblr' ] ) : '';
		$gen_settings = array(
			"fb" 			=> $fb,
			"tweet" 		=> $tweet,
			"pint" 			=> $pint,
			"fb_combox" 	=> $fb_box,
			"fb_reviews" 	=> $fb_reviews,
			"LinkedIn"		=> $link_in,
			"mail"			=> $mail,
			"tumblr" 		=> $tumblr
		);
		update_option( "wsp_gen_settings", $gen_settings );
	}
		?>
		<div class="pa_main_wrapper">
		<div class="pas_content">
			<h1><?php _e('Product Auto Share Settings','product-auto-share'); ?></h1>
			<?php if(isset($_POST['submit'])){?>
				<div class="updated settings-error" id="setting-error-settings_updated"> 
				<p><strong><?php _e("Settings saved.",'product-auto-share')?></strong></p></div>
			<?php }?>
			<form action="" method="post">
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<a href	= 	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=general" class="nav-tab <?php if($_GET['tab']=='general' || !isset($_GET['tab'])){?> nav-tab-active<?php }?>"><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/settings.png'?>"></a>
					<a href	=	"<?php get_admin_url() ?>admin.php?page=share-product-settings&tab=facebook" class="nav-tab <?php if($_GET['tab']=='facebook' ){?>nav-tab-active<?php }?> "><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/facebook.png'?>"></a>
					<a href	=	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=twitter" class="nav-tab <?php if($_GET['tab']=='twitter' ){?>nav-tab-active<?php }?> "><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/twitter.png'?>"></a>	
					<a href	=	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=pinterest&section=gen" class="nav-tab <?php if($_GET['tab']=='pinterest' ){?>nav-tab-active<?php }?>"><img  width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/pint.png'?>"></a>	
					<a href	=	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=linkedin" class="nav-tab <?php if($_GET['tab']=='linkedin' ){?>nav-tab-active<?php }?>"><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/linkedin.png'?>"></a>	
					 <a href	=	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=mail" class="nav-tab <?php if($_GET['tab']=='mail' ){?>nav-tab-active<?php }?>"><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/mail.png'?>"></a>
					 <a href	=	"<?php get_admin_url()?>admin.php?page=share-product-settings&tab=tumblr" class="nav-tab <?php if($_GET['tab']=='tumblr' ){?>nav-tab-active<?php }?>"><img width ="40px" alt="" src="<?php echo plugins_url().'/product-auto-share/assets/images/tumblr.png'?>"></a>
				</h2>
				
				<?php 
				if($_GET['tab']=='general' || !isset($_GET['tab'])){
					?>
					<table class="form-table">
						<tr>
							<th><?php _e("Share on","product-auto-share")?></th>
							<td><input <?php if(get_option("wsp_gen_settings")['fb'] =="facebook"){?> checked="checked"<?php }?> type="checkbox" name="fb" value="facebook"><?php _e('Facebook','product-auto-share'); ?>
								<div><input <?php if(get_option("wsp_gen_settings")['tweet'] =="twiter"){?> checked="checked"<?php }?> type="checkbox" name="tweet" value="twiter"><?php _e('Twitter','product-auto-share'); ?></div>
								<div><input <?php if(get_option("wsp_gen_settings")['pint'] =="pinterest"){?> checked="checked"<?php }?>type="checkbox" name="pint" value="pinterest"><?php _e('Pinterest','product-auto-share'); ?></div>
								<div><input <?php if(get_option("wsp_gen_settings")['LinkedIn'] =="LinkedIn"){?> checked="checked"<?php }?>type="checkbox" name="LinkedIn" value="LinkedIn"><?php _e('LinkedIn','product-auto-share'); ?></div>
								<div><input <?php if(get_option("wsp_gen_settings")['mail'] =="mail"){?> checked="checked"<?php }?>type="checkbox" name="mail" value="mail"><?php _e('Mail','product-auto-share'); ?></div>
								<div><input <?php if(get_option("wsp_gen_settings")['tumblr'] =="tumblr"){?> checked="checked"<?php }?>type="checkbox" name="tumblr" value="tumblr"><?php _e('Tumblr','product-auto-share'); ?></div>
							</td>
						</tr>
						<tr>
							<th><?php _e("Show facebook commentbox","product-auto-share")?></th>
							<td><input <?php if(get_option("wsp_gen_settings")['fb_combox'] =="on"){?> checked="checked"<?php }?> type="radio" name="fb_combox" value="on"><?php _e('Yes','product-auto-share'); ?> 
								<input <?php if(get_option("wsp_gen_settings")['fb_combox'] =="off"){?> checked="checked"<?php }?>  type="radio" name="fb_combox" value="off"><?php _e('No','product-auto-share'); ?> 
							</td>
						</tr>	
						<tr>
							<th><?php _e("Show facebook reviews","product-auto-share")?></th>
							<td><input <?php if(get_option("wsp_gen_settings")['fb_reviews'] =="on"){?> checked="checked"<?php }?> type="radio" name="fb_reviews" value="on"><?php _e('Yes','product-auto-share'); ?> 
								<input <?php if(get_option("wsp_gen_settings")['fb_reviews'] =="off"){?> checked="checked"<?php }?>  type="radio" name="fb_reviews" value="off"><?php _e('No','product-auto-share'); ?> 
							</td>
						</tr>								
					</table>
					<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
					<?php }?>
				<?php 
				if($_GET['tab']=='twitter'){
					?>
					<table class="form-table">
					<tr>
						<th scope="row"><label for="appid"><?php _e("Consumer Key (API Key)","product-auto-share"); ?></label></th>
						<td><input class="regular-text" name="twitter_app_id" type="text" value="<?php echo  get_option('wsp_twitter_settings')['twitter_app_id'];?>" required>
							<p> <?php _e("Don't Have Twitter app","product-auto-share"); ?> <a href="https://apps.twitter.com/"><?php _e('Click here.','product-auto-share'); ?></a></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="appsec"><?php _e("Consumer Secret (API Secret)","product-auto-share")?> </label></th>
						<td><input class="regular-text" name="twitter_app_sec" type="password" value="<?php echo  get_option('wsp_twitter_settings')['twitter_app_sec'];?>" required></td>
					</tr>
					<tr>
						<th scope="row"><label for="pageid"><?php _e("Access Token","product-auto-share") ?></label> </th>
						<td><input class="regular-text" name="twitter_access" type="text" value="<?php echo  get_option('wsp_twitter_settings')['twitter_access'];?>" required></td>
					</tr>
					<tr>
						<th scope="row"><label for=""><?php _e("Access Token Secret","product-auto-share") ?></label> </th>
						<td><input class="regular-text" name="twitter_access_sec" type="password" value="<?php echo  get_option('wsp_twitter_settings')['twitter_access_sec'];?>" required></td>
					</tr>
					
				</table>
				<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
					<?php 
				}
				?>
					<?php 
				if($_GET['tab']=='facebook'){
					?>
					<table class="form-table">
						<tr>
						<th>
							<label><?php _e("Set this  as webauth url to facebook app","product-auto-share")?></label>				
						</th>
						<td>
						<?php 
								$redirect_uri = site_url();
								echo "<pre>".$redirect_uri."</pre>";
								?>									
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="appid"><?php _e("App Id","product-auto-share") ?></label></th>
						<td><input class="regular-text" name="app_id" type="text" value="<?php echo  get_option('sharepro_appid');?>" required>
							<p class="description"> <?php _e("Don't have Facebook App","product-auto-share"); ?> <a href="https://developers.facebook.com/apps/"><?php _e("Click here.","product-auto-share"); ?></a></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="appsec"><?php _e("App Secrect","product-auto-share") ?></label></th>
						<td><input class="regular-text" name="app_sec" type="password" value="<?php echo  get_option('sharepro_appsec');?>" required>
							
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="pageid"><?php _e("Page Id","product-auto-share") ?></label> </th>
						<td><input class="regular-text" name="page_id" type="text" value="<?php echo  get_option('ced_pas_fb_pageid');?>" required></td>
					</tr>	
					<tr>
						<th scope="row"><label for="ced_pas_FBShare_on_prof"><?php _e("Share on Personal Profile","product-auto-share") ?></label> </th>
						<td><input <?php if(get_option("ced_pas_FBShare_on_prof") =="FBShare_on_prof"){?> checked="checked"<?php }?> type="checkbox" name="ced_pas_FBShare_on_prof" value="FBShare_on_prof"></td>
					</tr>	
					<tr>
						<th scope="row"><label for="ced_pas_FBShare_on_page"><?php _e("Share on Page ","product-auto-share") ?></label> </th>
						<td><input <?php if(get_option("ced_pas_FBShare_on_page") =="FBShare_on_page"){?> checked="checked"<?php }?>type="checkbox" name="ced_pas_FBShare_on_page" value="FBShare_on_page"></td>
					</tr>								
				</table>
				<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
					<?php 
					}
				?>
				
							<?php 
				if($_GET['tab']=='pinterest'){
					
					?>
					<ul class="subsubsub">
						<li>
							<a class="" href="<?php get_admin_url()?>admin.php?page=share-product-settings&tab=pinterest&section=gen"><?php _e('General','product-auto-share'); ?></a> | </li><li><a class="" href="<?php get_admin_url()?>admin.php?page=share-product-settings&tab=pinterest&amp;section=pin_board"><?php _e('Board Settings','product-auto-share'); ?></a>
						</li>
					</ul>
					<?php 
					if( isset( $_GET['section'] ) and ( $_GET['section']=='gen' || $_GET['section']=='') ) {?>
						<table class="form-table">
							<tr>
								<th>
									<label><?php _e("Set this RedirectUrl to pinterest app","product-auto-share")?></label>				
				
								</th>
								<td>
									<?php 
									$redirect_uri = get_admin_url()."admin.php?page=share-product-settings&tab=pinterest&section=gen";
									echo '<pre>'.htmlentities($redirect_uri).'</pre>';
									?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="appid"><?php _e("App Id","product-auto-share")?></label></th>
								<td><input class="regular-text" name="pinapp_id" type="text" value="<?php echo  get_option('sharepro_pin_appid');?>" required>
									<p> <?php _e("Don't Have Pinterest app","product-auto-share"); ?> <a href="https://developers.pinterest.com/apps/"><?php _e('Click here.','product-auto-share'); ?></a></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="appsec"><?php _e("App Secrect","product-auto-share")?></label></th>
								<td><input class="regular-text" name="pinapp_sec" type="password" value="<?php echo  get_option('sharepro_pin_appsec');?>" required></td>
							</tr>
							<tr>
								<th scope="row"><label for="boardid"><?php _e("Board Id ","product-auto-share"); ?></label> </th>
								<td><input class="regular-text" name="pin_board_id" type="text" value="<?php echo  get_option('sharepro_pin_pageid');?>"></td>
							</tr>					
						</table>
						<p class="submit">
							<input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit">
						</p>
					
					<?php } if(get_option('sharepro_pin_appid') != "" && isset( $_GET['section'] ) and $_GET['section']=="pin_board"){	?>
					<?php if(get_option("sharepro_pin_acctoken") != ""){?>
						<table class="form-table">
						<tr>
						<?php 	$pint = new pin(get_option("sharepro_pin_appid"),get_option("sharepro_pin_appsec"));
								$pint->set_token(get_option("sharepro_pin_acctoken"));
								if (get_option('sharepro_pin_pageid')) {
									$bdata=$pint->getboarddesc(get_option('sharepro_pin_pageid'));
				
									$bdata = json_decode($bdata,true);
									?>
									<th><?php _e("Current Board","product-auto-share") ?></th>
									<td><?php echo $bdata['data']['name'];?></td>
									
									<?php 
								}
								else {
				
									?>
									
									<td><?php _e("Please select a board to create pin.","product-auto-share")?></td><?php 
								}
								
								?>
							
						</tr>
						<tr><th><h3><?php _e("Change Board","product-auto-share")?></h3></th></tr>
						<tr>
						
						<th scope="row"><?php _e("Select Board From Existing","product-auto-share")?></th>
							<?php 
								$pint = new pin(get_option("sharepro_pin_appid"),get_option("sharepro_pin_appsec"));
								$pint->set_token(get_option("sharepro_pin_acctoken"));
								$boards = json_decode($pint->getmyboards(),true);
							?><td><select name="sharepro_pin_pageid">
														 <?php foreach ($boards['data'] as $key=>$val){
													  	?><option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
													 <?php  } ?>
													 </select></td>
						</tr>
						<tr>
							<td>
								<h3>OR</h3>
							</td>
						</tr>
						<tr>
							<th><?php _e("Create New Board","product-auto-share")?></th>
								<tr>
									<td><?php _e("Board Name","product-auto-share")?></td>
									<td><input class="regular-text" name="pin_board" type="text" ></td>
								</tr>
								<tr>
									<td><?php _e('Board Details','product-auto-share'); ?></td>
									<td><input class="regular-text" name="pin_board_desc" type="text"></td>
								</tr>
						
						</table>
						<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
							<?php 
						}
						else{
							?>
							 <table class="form-table">
							 <tr>
							 <td><strong><?php _e("Please authorize from pinterest first.","product-auto-share")?></strong></td>
							 </tr>
							 </table>
							<?php 
						}
					}
					
				}?>
				<?php 
				if($_GET['tab']=='linkedin'){
					?>
					<table class="form-table">
					<tr>
						<th>
							<label><?php _e("Set this RedirectUrl to linkedin app","product-auto-share")?></label>				
						</th>
						<td>
						<?php 
								$redirect_uri = get_admin_url()."admin.php?page=share-product-settings&tab=linkedin";
								echo "<pre>".$redirect_uri."</pre>";
								?>									
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="appid"><?php _e("API Key","product-auto-share")?></label></th>
						<td><input class="regular-text" name="linkedin_app_id" type="text" value="<?php echo  get_option("linked_settings")['linkedin_apiid'];?>" required>
						<p> <?php _e("Don't Have LinkedIn app","product-auto-share"); ?> <a href="https://www.linkedin.com/developer/apps"><?php _e('Click here.','product-auto-share'); ?></a></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="appsec"><?php _e("API Secret","product-auto-share")?></label></th>
						<td><input class="regular-text" name="linkedin_app_sec" type="password" value="<?php echo  get_option("linked_settings")['linkedin_appsec'];?>" required></td>
					</tr>					
				</table>
				<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
					<?php 
				}
				?>
				<?php 
				if($_GET['tab']=='mail'){
					if (in_array('wp-advanced-newsletter/advancednewsletter.php', apply_filters('active_plugins', get_option('active_plugins'))))
					{						
				 	 	$url = admin_url().'admin.php?page=subscriber_list';
				 	 	echo "<a href=".$url.">"."__('Newsletter Subscribers','product-auto-share')"."</a>";
					 	//wp_localize_script('task', 'redirect', array('newsletter'=>$url));
					}else{
						echo "<div border = 1 class=update-nag>Please activate <a href=https://wordpress.org/plugins/wp-advanced-newsletter/ target=_blank>WP Advanced Newsletter</a> plugin for better features by using this free plugin you can enable sharing on your newsletter subscribers.</div><br>";
					}
                    echo "<div class=ced_pas_fanatsy>";
                    echo "<h2>".__("By default the sharing is on for all users which have role customer and are subscribed by WP Advanced Newsletter(if activated).","product-auto-share")."</h2>";
                    echo "<h2>"."__('Some amazing features are coming soon...','product-auto-share')"."</h2>";
                    echo "</div>";
				}
				?>	
				<?php 
				if($_GET['tab']=='tumblr'){
					?>
										<table class="form-table">
										<tr>
											<th scope="row"><label for="appid"><?php _e("Consumer Key (API Key)","product-auto-share")?></label></th>
											<td><input class="regular-text" name="tumblr_app_id" type="text" value="<?php echo  get_option('wsp_tumblr_settings')['tumblr_app_id'];?>" required>
												<p> <?php _e("Don't Have Tumblr app","product-auto-share"); ?> <a href="https://www.tumblr.com/oauth/apps"><?php _e("Click here.","product-auto-share"); ?></a></p>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="appsec"><?php _e("Consumer Secret (API Secret)","product-auto-share")?> </label></th>
											<td><input class="regular-text" name="tumblr_app_sec" type="password" value="<?php echo  get_option('wsp_tumblr_settings')['tumblr_app_sec'];?>" required></td>
										</tr>
										<tr>
											<th scope="row"><label for="pageid"><?php _e("Access Token","product-auto-share") ?></label> </th>
											<td><input class="regular-text" name="tumblr_access" type="text" value="<?php echo  get_option('wsp_tumblr_settings')['tumblr_access'];?>" required></td>
										</tr>
										<tr>
											<th scope="row"><label for=""><?php _e("Access Token Secret","product-auto-share") ?></label> </th>
											<td><input class="regular-text" name="tumblr_access_sec" type="password" value="<?php echo  get_option('wsp_tumblr_settings')['tumblr_access_sec'];?>" required></td>
										</tr>
										<tr>
											<th scope="row"><label for=""><?php _e("Tumblr Blog Url","product-auto-share") ?></label> </th>
											<td><input class="regular-text" name="tumblr_blog_url" type="text" value="<?php echo  get_option('wsp_tumblr_settings')['tumblr_blog_url'];?>" required>
											<p> <?php _e("Tumblr Blog Must Be Like","product-auto-share"); ?> <a><?php _e("blogname.tumblr.com","product-auto-share"); ?></a></p>
											</td>
										</tr>
										
									</table>
									<p class="submit"><input type="submit" value="<?php _e('Save Changes','product-auto-share'); ?>" class="button button-primary" id="submit" name="submit"></p>
										<?php 
				}
				?>
			</form>
		</div>
		<?php
		if(!session_id())
			session_start();
		if(!isset($_SESSION["product_auto_share_hide_email"])):
			$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$urlvars = parse_url($actual_link);
			$url_params = $urlvars["query"];
		?>
		<div class="product_autoshare_img_email_image">
			<div class="product_autoshare_img_main_wrapper">
				<div class="ced_pas_cross_image">
					<a class="button-primary ced_pas_cross_image" href="?<?php echo $url_params?>&ced_pas_close=true"></a>
				</div>
				<input type="text" value="" class="product_autoshare_img_email_field" placeholder="<?php _e("enter your e-mail address","")?>"/> 
				<a id="product_autoshare_img_send_email" href=""><?php _e("Know More","")?></a>
				<p></p>
				<div class="hide"  id="product_autoshare_loader">	
					<img id="product-autoshare-loading-image" src="<?php echo plugins_url().'/product-auto-share/assets/images/ajax-loader.gif'?>" >
				</div>
				<div class="product_autoshare_banner">
				<a target="_blank" href="https://cedcommerce.com/offers#woocommerce-offers"><img src="<?php echo plugins_url().'/product-auto-share/assets/images/ebay.jpg'?>"></a>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>