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
	setcookie("returnpath",HOST_SERVER."/logs.php",0,'/');
	echo '<div id="template_content_body">Please <a href="/index.php">Login</a> to view Logs</div>';
	$assetobj=new ta_assetloader();
	$assetobj->load_css_theme_default();
	$assetobj->load_css_product_login();
	$themeobj->template_load_left();
	return "";
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
					User Logs (Under construction)
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					
					<ul class="list-group">
					<?php 
						$actres=$userobj->activity_get_user($userobj->uid);
						for($i=0;$i<count($actres);$i++)
						{
							$actid=$actres[$i][changesqlquote(tbl_user_activity::col_activityid,"")];
							$actdesc=$actres[$i][changesqlquote(tbl_user_activity::col_activitydesc,"")];
							$acttime=$actres[$i][changesqlquote(tbl_user_activity::col_activitytime,"")];
							$instid=$actres[$i][changesqlquote(tbl_user_activity::col_instanceid,"")];
							$ipaddr=$actres[$i][changesqlquote(tbl_user_activity::col_ipaddr,"")];
							$platform=$actres[$i][changesqlquote(tbl_user_activity::col_platforminfo,"")];
							
							echo '<li class="list-group-item">'.$actdesc.' ['.$acttime.'] ['.$platform.'] ['.$ipaddr.']</li>';
						}
					?>
					</ul>
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