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
	setcookie("returnpath",HOST_SERVER."/dash_apps.php",0,'/');
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
//TODO ABILITY TO ADD SUBLISTS
?>

<div id="template_content_body">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-5">
			<h4>Categories</h4>
			<ul class="list-group">
				<li class="list-group-item active">Productivity</li>
				<li class="list-group-item">Games</li>
				<li class="list-group-item">Business</li>
				<li class="list-group-item">Health & Fitness</li>
				<li class="list-group-item">Communication</li>
				<li class="list-group-item">Music & Audio</li>
				<li class="list-group-item">Video & Media</li>
				<li class="list-group-item">News & Magazines</li>
				<li class="list-group-item">Travel</li>
				<li class="list-group-item">Lifestyle</li>
				<li class="list-group-item">Finance</li>
				<li class="list-group-item">Weather</li>
			</ul>
		</div>
		<div class="col-lg-9 col-md-8 col-sm-7">
				<div class="panel panel-default">
				<div class="panel-heading"><i class="fa fa-briefcase"></i> Apps under "<b>Productivity</b>"</div>
				<div class="panel-body">					
					<div class="panel panel-default">
						<div class="panel-body">
							<div>
								<div class="row">
									<div class="col-lg-3">
										<img src="https://i.ytimg.com/vi/NYkdEYksoy4/mqdefault.jpg" style="width: 100%;">
									</div>
									<div class="col-lg-9">
										<a href="#">Reminder & Alerts</a> [Tech Ahoy Systems]
										<p><input id="ta_apprate" type="number" value="3"></p>
										<p>
										<span class="label label-success">Free</span> 
										100 Users, Ranking: 10, Age Group: 13+
										<span style="clear:both;"></span>
										</p>
										<p>
											<span class="label label-primary">Productivity</span>
											<span class="label label-primary">Business</span>
										</p>
										<div>
											
											<div class="dropdown pull-right">
												<button class="btn btn-default" data-toggle="dropdown"><i class="fa fa-caret-down"></i> More</button>
												<ul class="dropdown-menu">
													<li><a href="#">Share</a></li>
													<li><a href="#">Get App Link</a></li>
													<li><a href="#">Add to Lists</a></li>
													<li><a href="#">Flag App</a></li>
													<li><a href="#">Public Notes</a></li>
													<li><a href="#">View Changelog</a></li>
												</ul>
											</div>
										
											<button class="btn btn-default"><i class="fa fa-plus"></i> Add this App</button>
											<button class="btn btn-default"><i class="fa fa-info-circle"></i> More Info</button>
											<button class="btn btn-default"><i class="fa fa-comment"></i> View Reviews</button>
											
											<div style="clear: both;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					

					
				</div>
				<div class="panel-footer">
					Showing 200 of 1000 Results
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

<script type="text/javascript">
function search_init_temp()
{
	var utilityobj=new JS_UTILITY(); 

	utilityobj.rating($("#ta_apprate"),{size:'xs',readonly:true,size:"xs",showCaption:false,showClear:false});

	setTimeout(function(){
			utilityobj.checkbox($('input[type="checkbox"]'), {
				checkedClass: 'glyphicon glyphicon-ok'
			});
		}, 300);
	
}

document.addEventListener("DOMContentLoaded",search_init_temp);
</script>