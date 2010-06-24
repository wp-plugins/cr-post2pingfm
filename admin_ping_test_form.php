<div class="wrap">
<h2>CR Post2Pingfm - Test Connection</h2>
<p>&raquo;&raquo;&raquo;&nbsp;&nbsp;For questions and feature requests, you can go to my <strong><a href="http://crwpplugins.userecho.com/">UserEcho</a></strong> forum&nbsp;&nbsp;&laquo;&laquo;&laquo;</p>

<form method="post" action="#">

<p>This page will help you choose which connection method is supported by your server.</p>
<table class="form-table">

<tr valign="top">
<th scope="row">Ping Message</th>
<td><input type="text" size="70" maxlength="140" name="cr_ping_message" id="cr_ping_message" /></td>
</tr>

<tr valign="top">
<th scope="row">Connect Method</th>
<td>Choose what connection method you want to test: <br />
    <input type="hidden" name="cr_ping_connection_method_selected" id="cr_ping_connection_method_selected" value="curl" />
    <input type="radio" onclick="javascript:set_connect_method(this)" name="cr_ping_connection_method" id="cr_ping_connection_method_curl" value="curl" checked="checked" /><label for="cr_ping_connection_method_curl">CURL</label><br />
    <input type="radio" onclick="javascript:set_connect_method(this)" name="cr_ping_connection_method" id="cr_ping_connection_method_fsock" value="fsockopen" /><label for="cr_ping_connection_method_fsock">FSockOpen</label><br />
    <p class="description">Don't worry about what happened behind the scene. You only need to choose between these two supported connection method which my plugin has to connect to Ping.FM. Test each one and use that in config if you see your post submitted to Ping.FM.</p>
</td>
</tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td><div id="pingresult"></div></td>
</tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td><p class="submit">
<input type="button" class="button-primary" onclick="javascript:cr_post2pingfm_submit_testing();" value="<?php _e('Test!') ?>" />
</p>
</td>
</tr>

</table>

</form>
</div>