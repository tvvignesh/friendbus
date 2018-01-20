<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$uiobj=new ta_uifriend();
$socialobj=new ta_socialoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/dash_lists.php",0,'/');
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

$vuid=$userobj->uid;
//TODO ABILITY TO ADD SUBLISTS
?>

<div id="template_content_body">
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-list"></i> Lists</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3 col-md-5 col-sm-6">
				<button class="btn btn-default box-tog" data-toggle="modal" data-mkey="box_lists_new"> <i class="fa fa-plus-circle"></i> New List</button>
					<h4>My Lists</h4>
					<ul class="list-group ta-mylists">
					
						<?php 
							$resarr=json_decode($uiobj->disp_lists($vuid,"0","15"),true);
							echo $resarr["res"];
							$initlid=$resarr["initlid"]
						?>
					
						<li class="list-group-item active">Movies I like</li>
						<li class="list-group-item">My Favorite Songs</li>
						<li class="list-group-item">Top 10 Indian Actors</li>
						<li class="list-group-item">Top 10 Indian Actress</li>
					</ul>
				</div>
				<div class="col-lg-9 col-md-7 col-sm-6">
				<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-left">
								<i class="fa fa-list"></i>
							</div>
							<div class="pull-right">
								<button class="btn btn-default" title="Add an item to this list"><i class="fa fa-plus"></i></button>
								<button class="btn btn-default" title="Share this list"><i class="fa fa-share"></i></button>
								<button class="btn btn-default" title="Remove this list"><i class="fa fa-trash"></i></button>
								<div class="dropdown pull-right ta-lmargin">
									<button class="btn btn-default" title="More" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-caret-down"></i></button>
									<ul class="dropdown-menu">
										<li><a href="#"><i class="fa fa-arrows"></i> Move this List</a></li>
										<li><a href="#"><i class="fa fa-download"></i> Download this List</a></li>
									</ul>
								</div>
							</div>
							<div style="clear:both;"></div>
						</div>
							<ol class="breadcrumb">
							  <li><a href="#">Movies I like</a></li>
							  <li><a href="#">English</a></li>
							  <li class="active">2015</li>
							</ol>
						<div class="panel-body">
		
							<div class="ta-list-accordion listcontainer">
					<?php 
						$rescontarr=json_decode($uiobj->disp_list_container($vuid,$listid,"0","15"),true);
						echo $rescontarr["res"];
						$start=$rescontarr["st"];
					?>
							</div>
								<div class="panel-footer">
									List footer
								</div>
						</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			List footer
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
	listenevent_future(".ta-mylists li",$("body"),"click",function(){
		$(".ta-mylists li").removeClass("active");
		$(this).addClass("active");
		var listid=$(this).attr("data-listid");
		$(".listcontainer").attr("data-listid",listid);
	});

	$( ".ta-list-accordion" ).accordion({
		heightStyle: "content",
		collapsible: true
	});
}

document.addEventListener("DOMContentLoaded",lists_init_temp);
</script>