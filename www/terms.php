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
					Terms &amp; Conditions 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					We beleive in a world with open internet providing all the required information and knowledge for all. 
					<br><br>
					But we do consider that moderation is needed for every user and every content being posted in the site since some content may be offensive,illegal,abusive,copyrighted or may hurt the sentiments of some users.
					<br><br>
					To ensure that, we follow these strategies:
					<br><br>
					1) If a considerable number of users report a content or a user, we may BAN/DELETE the content and/or the user depending upon the level of issue.
					<br><br>
					2) All the trademarks,Logos,code,design and content are owned by respective owners. We do not claim ownership for any libraries we have used nor the content every user posts.
					<br><br>
					3) If deletion of a content is required from the site, appropriate reason needs to be specified with evidence (if any) and ample time (minimum of 2 weeks since notification) needs to be given to analyze the content. 
					The content will only be removed if proper justifications are given by the concerned party.
					<br><br>
					4) We always consider users our top priority. So, we will take any action or make any changes or additions to make sure almost every user's need is satisfied
					<br><br>
					5) There is not much possibility that we will make any changes to the Terms, Privacy, Cookie Policy, etc. but if we do, every user will be notified in the website.
					<br><br>
					6) All the content every user posts is owned by themselves though the services are owned and provided by us.
					<br><br>
					7) If any transaction(s) were to happen in this website, proper invoices will be generated and only the respective user is responsible for any transaction that occurs and we do not do anything without the user's consent.
					<br><br>
					8) We work hard to keep our services up and running 24/7/365 but if any failure occurs in our system or if we are posed with any security threat/ malfunctions, we may take the website down till the problem is fixed.
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