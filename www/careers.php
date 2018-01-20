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
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Careers 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					I will fill this section up when I get time. Till then, let me make things short.
					<br><br>
					<b>Technical Roles:</b><br>
<pre style="white-space: pre-wrap;word-break: normal;">

<b>Web Developer</b>
Skills required:
HTML,CSS,Javascript with JQuery,PHP,MySQL (knowing Bootstrap will be an advantage) and most importantly a love for programming
Experience:
Should have worked on some projects with these technologies (may be anything) for a period of atleast 1 year.

<b>Moderator</b>
Skills required:
Good knowldge of English Language, ability to decide which is right and which is wrong, quick keyboard and mouse reflexes
Experience:
Not required



<b>Salary:</b>
Will be decided only after selection
<b>Perks &amp; Benefits:</b>
Depends on the role the candidate is selected for

<b>How to approach?</b>
Send a mail to tvvignesh@techahoy.in with your resume and all details and we will get back to you. (We are building our careers portal. So, currently this is the only way) 
</pre>
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