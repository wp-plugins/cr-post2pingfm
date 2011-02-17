<?php
/*
Plugin Name: CR Post to Ping.fm
Plugin URI: http://bayu.freelancer.web.id/oss/crpost2pingfm-plugin-to-submit-updates-to-ping-fm/
Description: This plugin will submit your new post to ping.fm account.
Version: 1.0.1
Author: Arief Bayu Purwanto
Author URI: http://bayu.freelancer.web.id/
*/

define('API_KEY', 'faec1ae02db39b8da9fd4a528e6b2006');

add_action('publish_post', 'cr_post_2_pingfm_submit_to_ping_fm');
add_action('admin_menu', 'cr_post_2_pingfm_submit_config_admin');

add_action('edit_category_form_fields', 'cr_post_2_pingfm_edit_category_form_fields');
add_action('edit_category', 'cr_post_2_pingfm_edit_category_save_action');

add_action('init', 'cr_post_2_pingfm_init');
function cr_post_2_pingfm_init(){
    cr_post_2_pingfm_admin_warnings();
}

function cr_post_2_pingfm_admin_warnings(){
	if ( !get_option('cl_post_pingfm_api_key') && !isset($_POST['submit']) ) {
		function cr_post_2_pingfm_warning() {
			echo "
			<div id='crpost2pingfm-warning' class='updated fade'><p><strong>[CR]Post2PingFM is almost ready</strong> You must <a href='plugins.php?page=cr_post_2_pingfm_submit_config_form'>enter your Ping.FM API Key</a> for it to work.</p></div>
			";
		}
		add_action('admin_notices', 'cr_post_2_pingfm_warning');
		return;
	}    
}
add_action('wp_ajax_cr_post_2_pingfm_ajax_test', 'cr_post_2_pingfm_ajax_test_handler');
function cr_post_2_pingfm_ajax_test_handler(){
    $method  = isset($_POST['method']) ? trim($_POST['method']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    if(empty($message)){
        echo "Message empty!";
    }
    
    $user_key = trim(get_option('cl_post_pingfm_api_key'));
    if('curl' == $method){
        echo "Processing with CURL\n";
        require_once('curl_submitter.php');
        echo "cr_post2pingfm_do_submit_ping(".API_KEY.", $user_key, $message, true);\n";
        $message = cr_post2pingfm_do_submit_ping(API_KEY, $user_key, $message, true);
    } else if('fsockopen' == $method){
        echo "Processing with FSockOpen\n";
        require_once('fsockopen_submitter.php');
        echo "cr_post2pingfm_do_submit_ping(".API_KEY.", $user_key, $message, true);\n";
        $message = cr_post2pingfm_do_submit_ping(API_KEY, $user_key, $message, true);
    }
    
    echo "<pre>".htmlentities($message)."</pre>";
    exit;    
}

add_action('admin_head', 'cr_post_2_pingfm_js_admin');
function cr_post_2_pingfm_js_admin(){
?>
<script type="text/javascript">
function cr_post2pingfm_submit_testing(){
    var data = {
        action: 'cr_post_2_pingfm_ajax_test',
        method: jQuery('#cr_ping_connection_method_selected')[0].value,
        message: jQuery('#cr_ping_message')[0].value
    };
    jQuery.ajax({
        type: 'post',
        data: data,
        url: ajaxurl,
        success: function(data) {
            document.getElementById('pingresult').innerHTML = data;
            //jQuery('#pingresult')[0].html(data);
            //alert('Load was performed.');
        }
    });
}

function set_connect_method(obj){
    jQuery("#cr_ping_connection_method_selected")[0].value = obj.value;
}
</script><?php
}

add_action('save_post', 'cr_post_2_pingfm_save_postdata');
function cr_post_2_pingfm_save_postdata( $post_id ) {
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times

	if ( !wp_verify_nonce( $_POST['_cr_post_2_pingfm_custom_message_nonce'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ))
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ))
			return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data

	$cr_p2pcm = $_POST['cr_post_2_pingfm_custom_message'];
	if(!add_post_meta($post_id, "_cr_post_2_pingfm_custom_message", $cr_p2pcm, true))
		update_post_meta($post_id, "_cr_post_2_pingfm_custom_message", $cr_p2pcm);

	$cr_p2pdptp = $_POST['_cr_post_2_pingfm_dont_ping_this_post'];
	if(!add_post_meta($post_id, "_cr_post_2_pingfm_dont_ping_this_post", $cr_p2pdptp, true))
		update_post_meta($post_id, "_cr_post_2_pingfm_dont_ping_this_post", $cr_p2pdptp);

		$cr_p2pcm_send_on_update = $_POST['cr_post_2_pingfm_custom_message_send_on_update'];
	if(!add_post_meta($post_id, "_cr_post_2_pingfm_custom_message_send_on_update", $cr_p2pcm_send_on_update, true))
		update_post_meta($post_id, "_cr_post_2_pingfm_custom_message_send_on_update", $cr_p2pcm_send_on_update);

	return $post_id;
}

function cr_post_2_pingfm_submit_to_ping_fm($postId)
{
    $continue = false;
    $categories = array();
    $postMode = get_option('cl_post_pingfm_publish_mode', 'once');
    $republish = false;

    if(!wp_is_post_revision($postId))
    {
        $this_post_submitted = get_option('cr_post_2_pingfm_submit_post_submitted_'.$postId, false);
        if(!$this_post_submitted)
        {
            $continue = true;
        }
        else
        {
            if("all" == $postMode)
            {
                $continue = true;
                $republish = true;
            }
        }

        if($continue)
        {
            $theCats = get_the_category($postId);
            foreach($theCats as $cats)
            {
                $categories[] = $cats->cat_ID;
                $categories[] = $cats->category_nicename;
            }
            $continue = isCategoriesAllowedToPing($postId, $categories);
        }
        
        if($continue)
        {
            $ping_template_mode = get_option('cr_post_pingfm_template_mode', 'global');
            update_option('cr_post_2_pingfm_submit_post_submitted_'.$postId, true);
            submitPingFM($postId, $republish, $ping_template_mode);
        }
    }
}


function cr_post_2_pingfm_submit_config_admin()
{
	//add_options_page('CR Post2Pingfm', 'CR Post2Pingfm', 8, __FILE__, 'cr_post_2_pingfm_submit_config_form');
    add_menu_page( 'Post2Pingfm', 'Post2PingFM', 8, 'cr_post_2_pingfm_submit_config_form');
    add_submenu_page( 'cr_post_2_pingfm_submit_config_form', 'Post2Pingfm', 'Configurations', 8, 'cr_post_2_pingfm_submit_config_form', 'cr_post_2_pingfm_submit_config_form');
    add_submenu_page( 'cr_post_2_pingfm_submit_config_form', 'Post2Pingfm', 'Test Connection', 8, 'cr_post_2_pingfm_ping_test_form', 'cr_post_2_pingfm_ping_test_form');
	add_meta_box( 'cr_post_2_pingfm_custom_message_box', 'PingFM Message', 
		'cr_post_2_pingfm_custom_message_box', 'post', 'side' );
	add_meta_box( 'cr_post_2_pingfm_custom_message_box', 'PingFM Message', 
		'cr_post_2_pingfm_custom_message_box', 'page', 'side' );
}

function cr_post_2_pingfm_custom_message_box() {

	$post_id = mysql_escape_string($_GET['post']);

	// The actual fields for data entry
	$custom_message = get_post_meta( $post_id, '_cr_post_2_pingfm_custom_message', true);
	$dont_ping_this_post = get_post_meta( $post_id, '_cr_post_2_pingfm_dont_ping_this_post', true);
	$send_on_update = get_post_meta( $post_id, '_cr_post_2_pingfm_custom_message_send_on_update', true);
	
	echo '<input type="hidden" name="_cr_post_2_pingfm_custom_message_nonce" id="_cr_post_2_pingfm_custom_message_nonce" value="' . 
	 wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	echo '<label for="cr_post_2_pingfm_custom_message">Custom Message:</label><br />';
	echo '<input type="text"'
		.' name="cr_post_2_pingfm_custom_message"'
		.' value="' . $custom_message . '" />';
	echo '<p><u><strong>Custom message</strong></u> is very useful if you
want to set special update message to Ping.FM. If this field is empty,
we will use setting provided on CR Post2PingFm setting page.<br />
You can still use Template Tags: <strong>{title}</strong> for <em>Post Title</em>
and <strong>{url}</strong> for <em>Permalink URL</em> <br />
eg: <em>I just post {title} on {url}</em><br /></p>';
	echo '<input type="checkbox"'
		.' name="cr_post_2_pingfm_dont_ping_this_post"'
		.' value="1"' . ($dont_ping_this_post ? '' : ' checke="checked"') . ' />';
	echo '<label for="cr_post_2_pingfm_dont_ping_this_post">don\'t ping this post.</label><br />';
/*	echo '<label for="cr_post_2_pingfm_custom_message_send_on_update">Send on update:</label><br />';
  echo '<input type="radio"'
  		.' name="cr_post_2_pingfm_custom_message_send_on_update"'
  		. ($send_on_update == 'yes' ? ' checked="checked"' : '')
		.' value="default" /> Yes, send please.<br />';
  echo '<input type="radio"'
  		.' name="cr_post_2_pingfm_custom_message_send_on_update"'
  		. ($send_on_update == '' ? ' checked="checked"' : '')
		.' value="overide" /> No, thanks.<br />';
	echo '<p><u><strong>Send on update</strong></u> is used on updating post. By default, it is set to <strong>NO</strong>.</p>';*/
}

function cr_post_2_pingfm_submit_config_form() {
    include_once('admin_form_menu.php');
}

function cr_post_2_pingfm_ping_test_form(){
    include_once('admin_ping_test_form.php');
}
function submitPingFM($postId, $republish = false, $ping_template_mode = 'global')
{
	$post = get_post($postId);
	$my_API_key = get_option('cl_post_pingfm_api_key');
	$ping_template = get_post_meta( $postId, '_cr_post_2_pingfm_custom_message', true);
	if(trim($ping_template) == ''){
		$ping_template = getPingTemplate($post, $ping_template_mode);
	}
	$cl_post_pingfm_republish_template = get_option('cl_post_pingfm_republish_template');

	if($republish)
	{
		if("this" == $cl_post_pingfm_republish_template)
		{
			$ping_template = get_option('cl_post_pingfm_ping_republish_template_text');
		}
	}


	$arrTemplate = array(
		'{title}' => $post->post_title,
		'{url}' => get_permalink($postId),
	);

	foreach($arrTemplate as $template => $template_data)
	{
		$ping_template = str_replace($template, $template_data, $ping_template);
	}

	//$result = $pfm->post("status", $ping_template);
    $cr_ping_connection_method = get_option('cr_ping_connection_method');
    if('curl' == $cr_ping_connection_method){
        require_once('curl_submitter.php');
        //echo "cr_post2pingfm_do_submit_ping(".API_KEY.", $my_API_key, $ping_template, false);\n";
        $message = cr_post2pingfm_do_submit_ping(API_KEY, $my_API_key, $ping_template, false);
    }else if('fsockopen' == $cr_ping_connection_method){
        require_once('fsockopen_submitter.php');
        //echo "cr_post2pingfm_do_submit_ping(".API_KEY.", $my_API_key, $ping_template, false);\n";
        $message = cr_post2pingfm_do_submit_ping(API_KEY, $my_API_key, $ping_template, false);
    }
    
    
}

function isCategoriesAllowedToPing($postId, $categories)
{
    $pingMode = get_option('cl_post_pingfm_ping_mode');
    $pingCats = get_option('cl_post_pingfm_ping_mode_category');
    $pingCats = array_map("trim", explode(",", $pingCats));
    if(!is_array($pingCats)) $pingCats = array();

    if("allow" == $pingMode)
    {
        foreach($categories as $cats)
        {
            if(in_array($cats, $pingCats))
            {
                return true;
            }
        }
    }
    else if("deny" == $pingMode)
    {
        foreach($categories as $cats)
        {
            if(in_array($cats, $pingCats))
            {
                return false;
            }
        }
        return true;
    }
    else
    {
        return true;
    }
}

function getPingTemplate($post, $ping_template_mode){
	$template = $templateX = array();
	
	for($i = 1; $i <=10; $i++){
		$x = trim(get_option('cl_post_pingfm_message_template_' . $i, ''));
		if($x){
			$template[] = $x;
		}
	}
	
    $postcats = wp_get_object_terms($post->ID, 'category');
    //print_r($postcats);
    foreach($postcats as $cat){
    	for($i = 1; $i <=5; $i++){
    		$x = trim(get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_' . $i, ''));
    		if($x !== ''){
    			$templateX[] = $x;
    		}
    	}
     }
     
     if(!empty($templateX) && ('category' == $ping_template_mode)){
        $template = $templateX;        
     }
	
	//print_r($template);
	
	return $template[ rand(0, count($template) - 1 ) ];
}

function cr_post_2_pingfm_edit_category_form_fields($cat){
?>
        <tr class="form-field">
			<th scope="row" valign="top"><label for="ping_template_1">Ping.FM template</label></th>
			<td scope="row" valign="top"><table><tr>
<td><?php //echo $cat->term_id; ?>
<input type="text" size="45" name="cl_post_pingfm_message_template_1" value="<?php echo get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_1', '') ?>" /><br />
<input type="text" size="45" name="cl_post_pingfm_message_template_2" value="<?php echo get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_2', '') ?>" /><br />
<input type="text" size="45" name="cl_post_pingfm_message_template_3" value="<?php echo get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_3', '') ?>" /><br />
<input type="text" size="45" name="cl_post_pingfm_message_template_4" value="<?php echo get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_4', '') ?>" /><br />
<input type="text" size="45" name="cl_post_pingfm_message_template_5" value="<?php echo get_option('cr_post_2_pingfm_category_' . $cat->term_id . '_5', '') ?>" /><br />
<p class="description">Template Tags: <strong>{title}</strong> for <em>Post Title</em> and <strong>{url}</strong> for <em>Permalink URL</em> <br />
eg: <em>I just post {title} on {url}</em></p></td>
</tr>
</table></td>
		</tr>
<?php
}

function cr_post_2_pingfm_edit_category_save_action($cat_id){
    update_option('cr_post_2_pingfm_category_' . $cat_id . '_1', $_POST['cl_post_pingfm_message_template_1']);
    update_option('cr_post_2_pingfm_category_' . $cat_id . '_2', $_POST['cl_post_pingfm_message_template_2']);
    update_option('cr_post_2_pingfm_category_' . $cat_id . '_3', $_POST['cl_post_pingfm_message_template_3']);
    update_option('cr_post_2_pingfm_category_' . $cat_id . '_4', $_POST['cl_post_pingfm_message_template_4']);
    update_option('cr_post_2_pingfm_category_' . $cat_id . '_5', $_POST['cl_post_pingfm_message_template_5']);
}

