<div class="wrap">
<h2>CR-Post2PingFM - Configurations</h2>
<p>&raquo;&raquo;&raquo;&nbsp;&nbsp;For questions and feature requests, you can go to my <strong><a href="http://crwpplugins.userecho.com/">UserEcho</a></strong> forum&nbsp;&nbsp;&laquo;&laquo;&laquo;</p>

<form method="post" action="options.php">

<p>You can set various config to match your needs, here.</p>

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Ping.fm Application Key</th>
<td><input type="text" size="60" name="cl_post_pingfm_api_key" value="<?php echo get_option('cl_post_pingfm_api_key'); ?>" />
<p class="description">Get your Application Key <a href="http://www.ping.fm/key/">here</a>. After you have saved your configuration. You can test it <a href="admin.php?page=cr_post_2_pingfm_ping_test_form">here</a>, to see if your application key is working properly</p></td>
</tr>

<tr valign="top">
<th scope="row">Ping Template</th>
<td>
<input type="text" size="60" name="cl_post_pingfm_message_template_1" value="<?php echo get_option('cl_post_pingfm_message_template_1', 'I just post {title} on {url}'); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_2" value="<?php echo get_option('cl_post_pingfm_message_template_2', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_3" value="<?php echo get_option('cl_post_pingfm_message_template_3', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_4" value="<?php echo get_option('cl_post_pingfm_message_template_4', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_5" value="<?php echo get_option('cl_post_pingfm_message_template_5', 'New blog post {title} here: {url}'); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_6" value="<?php echo get_option('cl_post_pingfm_message_template_6', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_7" value="<?php echo get_option('cl_post_pingfm_message_template_7', 'Check out new post about {title} {url} here'); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_8" value="<?php echo get_option('cl_post_pingfm_message_template_8', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_9" value="<?php echo get_option('cl_post_pingfm_message_template_9', ''); ?>" /><br />
<input type="text" size="60" name="cl_post_pingfm_message_template_10" value="<?php echo get_option('cl_post_pingfm_message_template_10', ''); ?>" /><br />
<p class="description">Template Tags: <strong>{title}</strong> for <em>Post Title</em> and <strong>{url}</strong> for <em>Permalink URL</em> <br />
eg: <em>I just post {title} on {url}</em></p></td>
</tr>

<tr valign="top">
<th scope="row">Connect Method</th>
<td>Choose what connection method you want to use: <br />
    <input type="radio" name="cr_ping_connection_method" id="cr_ping_connection_method_curl" value="curl" checked="checked" <?php if(get_option('cr_ping_connection_method') == "curl") { echo 'checked="checked"'; }?> /><label for="cr_ping_connection_method_curl">CURL</label><br />
    <input type="radio" name="cr_ping_connection_method" id="cr_ping_connection_method_fsock" value="fsockopen" <?php if(get_option('cr_ping_connection_method') == "fsockopen") { echo 'checked="checked"'; }?> /><label for="cr_ping_connection_method_fsock">FSockOpen</label><br />
    <p class="description">You can test which method is supported by your server <a href="admin.php?page=cr_post_2_pingfm_ping_test_form">here</a>.</p>
</td>
</tr>

<tr valign="top">
<th scope="row">Template Mode</th>
<td>
    Choose what template you want to use: <br />
    <input type="radio" name="cr_post_pingfm_template_mode" value="global" <?php if(get_option('cr_post_pingfm_template_mode') == "global") { echo 'checked="checked"'; }?> />Global template.<br />
    <input type="radio" name="cr_post_pingfm_template_mode" value="category" <?php if(get_option('cr_post_pingfm_template_mode') == "category") { echo 'checked="checked"'; }?> />Per-category template.<br />
    <p class="description">Please note that you can bypass this configuration by setting ping template using <strong>each post's template when you create new post</strong>.</p>
</td>
</tr>

<tr valign="top">
<th scope="row">Ping mode</th>
<td>
    Allow submit new post to ping.fm: <br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="all" <?php if(get_option('cl_post_pingfm_ping_mode') == "all") { echo 'checked="checked"'; }?> />For all categories (<em>all</em>)<br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="allow" <?php if(get_option('cl_post_pingfm_ping_mode') == "allow") { echo 'checked="checked"'; }?> />For this categories (<em>allow</em>)<br />
    <input type="radio" name="cl_post_pingfm_ping_mode" value="deny" <?php if(get_option('cl_post_pingfm_ping_mode') == "deny") { echo 'checked="checked"'; }?> />For categories except this one (<em>deny</em>)<br />
    <p class="description">Category list (<em>category ID and slug are supported</em>)<input type="text" name="cl_post_pingfm_ping_mode_category" value="<?php echo get_option('cl_post_pingfm_ping_mode_category'); ?>" />(comma separated, eg: <em>1,23,random-caregory,10,rants</em>)<br />
    Category list is ignored if mode <em>all</em> is selected.</p>
</td>
</tr>

<tr valign="top">
<th scope="row">Publish mode</th>
<td>
    Allow submit to ping.fm on the following condition: <br />
    <input type="radio" name="cl_post_pingfm_publish_mode" value="once" <?php if(get_option('cl_post_pingfm_publish_mode') == "once") { echo 'checked="checked"'; }?> />Only submit for the <strong>first time</strong>(<em>once</em>)<br />
    <input type="radio" name="cl_post_pingfm_publish_mode" value="all" <?php if(get_option('cl_post_pingfm_publish_mode') == "all") { echo 'checked="checked"'; }?> />For everytime you push <strong>publish</strong> button(<em>all</em>)<br />
</td>
</tr>

<tr valign="top">
<th scope="row">Re-Publish template</th>
<td>
    This option only used if you choose <em>all</em> in <strong>publish mode</strong>.<br />
    <input type="radio" name="cl_post_pingfm_republish_template" value="above" <?php if(get_option('cl_post_pingfm_republish_template', 'above') == "above") { echo 'checked="checked"'; }?> />Use Ping Template above<br />
    <input type="radio" name="cl_post_pingfm_republish_template" value="this" <?php if(get_option('cl_post_pingfm_republish_template') == "this") { echo 'checked="checked"'; }?> />Use this template
    <input type="text" size="45" name="cl_post_pingfm_ping_republish_template_text" value="<?php echo get_option('cl_post_pingfm_ping_republish_template_text', 'republished {title} on {url}'); ?>" /><br />
    <p class="description">The above template tags, apply.</p>
</td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cl_post_pingfm_message_template,cl_post_pingfm_api_key,cl_post_pingfm_ping_mode_category,
cl_post_pingfm_ping_mode,cl_post_pingfm_publish_mode,cl_post_pingfm_ping_republish_template_text,cl_post_pingfm_republish_template,
cl_post_pingfm_message_template_1,cl_post_pingfm_message_template_2,cl_post_pingfm_message_template_3,cl_post_pingfm_message_template_4,cl_post_pingfm_message_template_5,
cl_post_pingfm_message_template_6,cl_post_pingfm_message_template_7,cl_post_pingfm_message_template_8,cl_post_pingfm_message_template_9,cl_post_pingfm_message_template_10,
cr_post_pingfm_template_mode, cr_ping_connection_method" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>