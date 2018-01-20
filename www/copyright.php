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
	setcookie("returnpath",HOST_SERVER."/copyright.php",0,'/');
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
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Copyright Policy 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					We do beleive in a free and open internet. So, we permit the use of any of the code we have written on the client side (Javascript/HTML) to be reused free of cost after providing appropriate credit to us or the respective authors.
					<br><br>
					But, by code, we do not include the content enlisted. The content is posted by users and may be shared within Friendbus (if the user has allowed it) for free but copying and posting any content outside the website requires the permission of the respective user who published and owns the content.
					<br><br>
					If any copyright violation is made, Tech Ahoy or the respective user has the right to ask the concerned party to take the content down or file legal claims.
					<br><br>
					We do beleive in free and open internet! We just want to make sure it is moral and legal as well.
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