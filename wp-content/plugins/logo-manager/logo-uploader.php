<?php 
/*
Plugin Name: Logo Manager
Plugin URI: http://quick-plugins.com/logo-manager/
Description: Goto Appearance > Change Logo to manage your logos.
Author: fisicx
Version: 2.4
Author URI: http://quick-plugins.com
*/

add_action('admin_menu', 'lm_menu_link');
add_filter('plugin_action_links', 'lm_action_links', 10, 2 );
add_action('admin_enqueue_scripts', 'lm_settings_scripts');

function lm_settings_scripts() {
	wp_enqueue_media();
	wp_enqueue_script('lm-media-script',plugins_url('logo-manager-media.js', __FILE__ ), array( 'jquery' ), false, true );
	}
function lm_deactivate(){
	delete_option("lm_option");
	}
register_deactivation_hook(__FILE__, "lm_deactivate");

function lm_display_logo(){
	$lm_option = lm_get_stored_options();
	if ($lm_option['responsive'] == 'checked')$responsive = 'style="max-width: 100%;height:auto"';
	if ($lm_option['link'] == 'checked') echo '<a href="'.home_url().'"><img  ' .$responsive . ' src="'.$lm_option['url'].'" alt="'.$lm_option['alt'].'" title="'.$lm_option['title'].'" /></a>';
	else echo '<img ' .$responsive . ' src="'.$lm_option['url'].'" alt="'.$lm_option['alt'].'" title="'.$lm_option['title'].'" />';
	}
function lm_menu_link(){
	add_submenu_page("themes.php", "Change Logo", "Change Logo", 8, "lm_display_logo", "lm_options_page");
	}
function lm_action_links($links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) )
		{
		$lm_links = '<a href="'.get_admin_url().'themes.php?page=lm_display_logo">'.__('Settings').'</a>';
		array_unshift( $links, $lm_links );
		}
	return $links;
	}
function lm_admin_notice($message) {
	if (!empty($message)) echo '<div class="updated"><p>'.$message.'</p></div>';
	}
function lm_options_page() {
	if (isset($_POST['submit']))
		{
		$option = array('alt','title','link','responsive','alternative','selection');
		foreach ($option as $item) $lm_option[$item] = $_POST[$item];
        if ($lm_option['selection'] <> $lm_option['url']) 
            $lm_option['url'] = $lm_option['selection'];
        else {$lm_option['alternative'] = $_POST['newlogo'].','.$lm_option['alternative'];
        $lm_option['url'] = $_POST['newlogo'];
        $lm_option['alternative'] = rtrim($lm_option['alternative'], ',');}
		if ($_POST['lm_clear']) $lm_option['alternative'] = '';
		update_option('lm_option',$lm_option);
        lm_admin_notice("Logo settings updated.");
		}
	if( isset( $_POST['Reset'])) {
		delete_option('lm_option');
		lm_admin_notice("The logo settings have been reset.");
		}
    $lm_option = lm_get_stored_options();
    $$lm_option['link'] ='checked';
	$$lm_option['responsive'] ='checked';
	$content ='<div class="wrap">
	   <h2>Logo Manager</h2>
	   <p><span style="color:red">Warning:</span> If your theme already has a logo editor then this plugin may not work without a lot of code hacking.</p>
	   <p>Once you have uploaded your logo scroll down to the bottom of the page for instructions on how to use the plugin on your site.</p>
	   <p><img src="'.$lm_option['url'] . '"></p>
	   <form enctype="multipart/form-data" action="" method="POST" action="">
        <table>
	   <tr>
       <td width="20%">Logo URL</td><td><input id="upload_logo" type="text" size="100%" name="newlogo" value="'.$lm_option['url'] . '" /><br>
       <input id="upload_logo_button" class="button" type="button" value="Upload New Logo" /></td>
       </tr>
	   <tr>
       <td width="20%">Alt text (optional):</td><td><input name="alt" type="text" size="100%" value="'.$lm_option['alt'] . '"/></td>
       </tr>
	   <tr>
       <td width="20%">Image Title: (optional):</td><td> <input name="title" type="text" size="100%" value="' .$lm_option['title'] . '"/></td>
       </tr>
	   <tr>
       <td width="20%"></td><td><input name="link" type="checkbox" ' .$lm_option['link'] . ' value="checked"/> Logo links to your homepage?<br>
	   <input name="responsive" type="checkbox" ' .$lm_option['responsive'] . ' value="checked"/> Responsive logo (resizes to fit the screen)</td>
       </tr>
        </table>';
    if (strpos($lm_option['alternative'],',' ) ) {
	   $content .='<h3>Current Logos</h3><p>Select one of the logos below to use it in your theme</p><p>';
	   $arr = explode(",",$lm_option['alternative']);
	   foreach ($arr as $item) {
           $checked = '';
           if ($lm_option['url'] == $item) $checked = 'checked';
           if (!empty($item ))$content .='<input type="radio" name="selection" value="' .$item . '" ' .$checked . ' />&nbsp;&nbsp;<img style="max-width:40%; height:auto;" src="'.$item.'" /><br>';
            }
        $content .='</p><p><input name="lm_clear" type="checkbox" value="checked"/> Clear all alternate logos (only the selected logo will remain)</p>';
        }
	$content .='<input type="hidden" name="alternative" value="' . $lm_option['alternative'] . '" />
	   <p>
       <input type="submit" name="submit" class="button-primary" value="Update Logo" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the logo settings?\' );"/>
       </p>
	   </form>
	   <h2>Using the plugin</h2>
	   <p>You are going to have to tweak some of the code in your theme. There is no way round this as every theme is slightly different. But fear not, it&#146;s a simple fix.</p>
	   <p>Open the header.php file using the <a href="/wp-admin/theme-editor.php?file=header.php">appearance editor</a>.  Find the line of code that looks something like this:</p>
	   <p><code>&lt;a href="&lt;?php bloginfo(\'url\'); ?&gt;"&gt;&lt;img src="&lt;?php bloginfo(\'stylesheet_directory\'); ?&gt;/images/logo.gif"&gt;&lt;/a&gt;</code></p>
	   <p>Delete and replace it with this: <code>&lt;?php lm_display_logo(); ?&gt;</code>.</p>
	   <p>If you want to leave the old code in place that&#146;s fine, it just means you will have two logos.</p>
	   <p>Click &#146;Update file&#146; and you are ready to roll.</p>
	   </div>';
	echo $content;
	}
function lm_get_stored_options () {
	$lm_option = get_option('lm_option');
	if(!is_array($lm_option)) $lm_option = array();
	$option_default = lm_get_defaults();
	$lm_option = array_merge($option_default, $lm_option);
	return $lm_option;
	}
function lm_get_defaults () {
	$lm_option = array();
	$lm_option['url'] = plugins_url( 'logo.gif' , __FILE__);
	$lm_option['alt'] = get_option('blogname');
	$lm_option['title'] = get_option('blogname').' homepage';
	$lm_option['link'] = 'checked';
	$lm_option['responsive'] = 'checked';
	$lm_option['alternative'] = '';
	return $lm_option;
	}