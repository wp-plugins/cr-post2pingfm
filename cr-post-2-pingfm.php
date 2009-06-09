<?php
/*
Plugin Name: CR Post to Ping.fm
Plugin URI: http://bayu.freelancer.web.id/2009/03/31/wordpress-plugin-cr-post2pingfm/
Description: This plugin will submit your new post to ping.fm account.
Version: 0.5
Author: Arief Bayu Purwanto
Author URI: http://bayu.freelancer.web.id/

 */

define('API_KEY', '41121eb3a56f921bc2957b2458d65bad');

add_action('publish_post', 'cr_post_2_pingfm_submit_to_ping_fm');
add_action('admin_menu', 'cr_post_2_pingfm_submit_config_admin');

function cr_post_2_pingfm_submit_to_ping_fm($postId)
{
    $continue = false;
    $categories = array();
    $postMode = get_option('cl_post_pingfm_publish_mode');

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
            update_option('cr_post_2_pingfm_submit_post_submitted_'.$postId, true);
            submitPingFM($postId);
        }
    }
}


function cr_post_2_pingfm_submit_config_admin()
{
    add_options_page('CR Post2Pingfm', 'CR Post2Pingfm', 8, __FILE__, 'cr_post_2_pingfm_submit_config_form');
}

function cr_post_2_pingfm_submit_config_form() {
?><div class="wrap">
<h2>CR Post2Pingfm</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Ping.fm Application Key</th>
<td><input type="text" name="cl_post_pingfm_api_key" value="<?php echo get_option('cl_post_pingfm_api_key'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Ping Template</th>
<td><input type="text" name="cl_post_pingfm_message_template" value="<?php echo get_option('cl_post_pingfm_message_template', 'I just post {title} on {url}'); ?>" /><br />
Template Tags: <strong>{title}</strong> for <em>Post Title</em> and <strong>{url}</strong> for <em>Permalink URL</em> <br />
eg: <em>I just post {title} on {url}</em></td>
</tr>

<tr valign="top">
<th scope="row">Ping mode</th>
<td>
    Allow submit new post to ping.fm: <br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="all" <?php if(get_option('cl_post_pingfm_ping_mode') == "all") { echo 'checked="checked"'; }?> />For all categories (<em>all</em>)<br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="allow" <?php if(get_option('cl_post_pingfm_ping_mode') == "allow") { echo 'checked="checked"'; }?> />For this categories (<em>allow</em>)<br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="deny" <?php if(get_option('cl_post_pingfm_ping_mode') == "deny") { echo 'checked="checked"'; }?> />For categories except this one (<em>deny</em>)<br />
    Category list (<em>category ID and slug is supported</em>)<input type="text" name="cl_post_pingfm_ping_mode_category" value="<?php echo get_option('cl_post_pingfm_ping_mode_category'); ?>" />(comma separated, eg: <em>1,23,random-caregory,10,rants</em>)<br />
    Category list is ignored if mode <em>all</em> is selected.
</td>
</tr>

<tr valign="top">
<th score="row">Publish Mode</th>
<td>
    Allow submit to ping.fm on the following condition: <br />
    <input type="radio" name="cl_post_pingfm_publish_mode" value="once" <?php if(get_option('cl_post_pingfm_publish_mode') == "once") { echo 'checked="checked"'; }?> />Only submit for the first time (<em>once</em>)<br />
    <input type="radio" name="cl_post_pingfm_publish_mode" value="all" <?php if(get_option('cl_post_pingfm_publish_mode') == "all") { echo 'checked="checked"'; }?> />For every <strong>publish</strong>(<em>all</em>)<br />
</td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cl_post_pingfm_message_template,cl_post_pingfm_api_key,cl_post_pingfm_ping_mode_category,cl_post_pingfm_ping_mode,cl_post_pingfm_publish_mode" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div><?php
}

function submitPingFM($postId)
{
    $post = get_post($postId);
    include_once('PHPingFM.php');
    $my_API_key = get_option('cl_post_pingfm_api_key');
    $ping_template = get_option('cl_post_pingfm_message_template');
    $pfm = new PHPingFM(API_KEY, $my_API_key, false);
    $arrTemplate = array(
        '{title}' => $post->post_title,
        '{url}' => get_permalink($postId),
    );

    foreach($arrTemplate as $template => $template_data)
    {
        $ping_template = str_replace($template, $template_data, $ping_template);
    }
    $result = $pfm->post("status", $ping_template);
}

function isCategoriesAllowedToPing($postId, $categories)
{
    $pingMode = get_option('cl_post_pingfm_ping_mode');
    $pingCats = get_option('cl_post_pingfm_ping_mode_category');
    $pingCats = array_map("trim", explode(",", $pingCats));
    if(!is_array($pingCats)) $pingCats = array();

    if("all" == $pingMode) return true;
    
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
}



