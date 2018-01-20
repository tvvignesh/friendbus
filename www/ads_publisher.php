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
	setcookie("returnpath",HOST_SERVER."/ads_publisher.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">
	<div class="panel panel-default">
		<div class="panel-heading">Publisher Dashboard</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3 col-md-5 col-sm-6">
					<h4>Your Websites</h4>
					<ul class="list-group ta-mylists">
						<li class="list-group-item active">www.xyz.com</li>
						<li class="list-group-item">www.abc.com</li>
						<li class="list-group-item">www.def.in</li>
					</ul>
				</div>
				<div class="col-lg-9 col-md-7 col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-left">
								<button class="btn btn-primary" title="Create a New Advertisement"><i class="fa fa-plus"></i> Add a New Website</button>
							</div>
							<div class="pull-right">
								<button class="btn btn-default" title="Manage duration of this Website"><i class="fa fa-clock-o"></i></button>
								<button class="btn btn-default" title="Manage AD types"><i class="fa fa-tags"></i></button>
								<button class="btn btn-default" title="Edit Website Details"><i class="fa fa-pencil"></i></button>
								<button class="btn btn-default" title="Remove ADS in this Website"><i class="fa fa-trash"></i></button>
								<button class="btn btn-default" title="Pause ADS in this Website"><i class="fa fa-pause"></i></button>
								<button class="btn btn-default" title="View Statistics"><i class="fa fa-bar-chart"></i></button>
								<div class="dropdown pull-right ta-lmargin">
									<button class="btn btn-default" title="More" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-caret-down"></i></button>
									<ul class="dropdown-menu">
										<li><a href="#"><i class="fa fa-list"></i> Add this to List</a></li>
										<li><a href="#"><i class="fa fa-download"></i> Download complete Report</a></li>
									</ul>
								</div>
							</div>
							<div style="clear:both;"></div>
						</div>
						<div class="panel-body">
							<ul class="list-group">
								<li class="list-group-item"><b>Website:</b> www.xyz.com</li>
								<li class="list-group-item"><b>Duration/Schedule: </b> <i class="fa fa-question-circle pull-right" title="This is the duration for which ADS should be shown on your website"></i>
								<span class="clear:both;"></span> 
									<ul class="list-group">
										<li class="list-group-item">1/1/2015 - 5/1/2015 [5:00 PM-6:00 PM]</li>
										<li class="list-group-item">10/1/2015 - 15/1/2015 [5:00 PM-6:00 PM]</li>
									</ul>
								</li>
								<li class="list-group-item"><b>Total Budget:</b> 1500 $</li>
								<li class="list-group-item"><b>Website Audience:</b> 
									<ul class="list-group">
										<li class="list-group-item">People with Age group of 18 and above</li>
										<li class="list-group-item">People who like Food</li>
									</ul>
								</li>
								<li class="list-group-item"><b>Current Status:</b> Active</li>
								<li class="list-group-item"><b>Current Reach:</b> 1800 AD Views</li>
								<li class="list-group-item"><b>Tags:</b> <span class="label label-success">Technology</span> <span class="label label-success">Business</span></li>
								<li class="list-group-item"><b>AD Type:</b> Plain Text AD</li>
								<li class="list-group-item"><b>Website Description:</b> Lorem Ipsum</li>
								<li class="list-group-item"><b>AD Sizes:</b> 
									<ul class="list-group">
										<li class="list-group-item">200x180 <a href="#" class="pull-right">Get Code</a></li>
										<li class="list-group-item">400x400 <a href="#" class="pull-right">Get Code</a></li>
									</ul>
								</li>
							</ul>
						</div>
						<div class="panel-footer">
							Created on 1/1/2015 @ 5:00 PM
						</div>
					</div>		
				</div>
			</div>
		</div>
		<div class="panel-footer">
			4 Websites in total, 2 active, 1 paused, 1 removed
		</div>
	</div>
</div>

 <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
function lists_init_temp()
{
	listenevent($(".ta-mylists li"),"click",function(){
		$(".ta-mylists li").removeClass("active");
		$(this).addClass("active");
	});

}

document.addEventListener("DOMContentLoaded",lists_init_temp);
</script>