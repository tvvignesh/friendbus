<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_ads.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">
<div class="panel panel-default">
	<div class="panel-heading">Announcements & Updates</div>
	<div class="panel-body">

<div class="panel panel-default">
	<div class="panel-body">
		<h4><b>Welcome</b></h4>
<pre>
Hello world. Friendbus has just launched. Now what?

Its all about <b>YOU</b>! Lets all join hands to make this even more bigger.
</pre>
	</div>
</div>
		 
	</div>
	<div class="panel-footer">
	We make all important announcements here.
	</div>
</div>
</div>

 <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>