<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();
$socialobj=new ta_socialoperations();
$msgobj=new ta_messageoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/privacy.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">
	<div class="row">
		<!-- <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
			<ul class="list-group">
				<li class="list-group-item">Technology</li>
				<li class="list-group-item">Technology</li>
			</ul>		
		</div>-->
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Privacy Policy 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					Our privacy policy is simple. 
					<br><br>
					<b>ONLY YOU</b> control what, where and when you want to share your data. And by data we mean every bit of information you have added to this site.
					<br><br>
					<b>Exception:</b> If the Government finds a person guilty of a crime and has enough evidence to back it up, we will share whatever information that the respective Country's government asks
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>