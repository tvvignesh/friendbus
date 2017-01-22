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
	setcookie("returnpath",HOST_SERVER."/help.php",0,'/');
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
					Latest questions <button class="btn btn-primary btn-sm pull-right box-tog" data-mkey="box_question_ask" data-appid="00000"><i class="fa fa-question"></i> Ask a Question</button>
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					
					<?php 
					$start="0";
					$tot="10";
					$hres=$socialobj->get_help_threads($start,$tot,"00000");
					
					for($i=0;$i<count($hres);$i++)
					{
						$tid=$hres[$i][changesqlquote(tbl_support_help::col_threadid,"")];
						$tres=$msgobj->getthreadoutline($tid);
						$subject=$tres[0][changesqlquote(tbl_message_outline::col_subject,"")];
						echo '
							<div class="panel panel-default">
							<div class="panel-body">
							<a href="/post_display.php?tid='.$tid.'">'.$subject.'</a>
							</div>
							</div>
						';
					}
					?>
					
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