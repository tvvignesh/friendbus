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
	setcookie("returnpath",HOST_SERVER."/dash_search.php",0,'/');
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
			<h4>Filter search</h4>
			<ul class="list-group">
				<li class="list-group-item">
					<label>Search Term:</label> 
					<input type="text" placeholder="Your Search" value="T.V.Abhinav Viswanath" class="form-control">
				</li>
				<li class="list-group-item">
					<label>Search Type:</label>
					<select class="form-control ta-stype" multiple>
						<option>Any</option>
						<option>People</option>
						<option>Groups</option>
						<option>Places</option>
						<option>Events</option>
						<option>Apps & Widgets</option>
						<option>Posts & Content</option>
						<option>Files</option>
						<option>Others</option>
					</select>
				</li>
				<li class="list-group-item">
					<label>Search Time:</label>
					<select class="form-control ta-stiming">
						<option>Anytime</option>
						<option>Past hour</option>
						<option>Past 24 hours</option>
						<option>Past week</option>
						<option>Past month</option>
						<option>Past year</option>
					</select>
				</li>
				<li class="list-group-item">
					<label>Location Filter:</label>
					<input type="text" class="form-control" placeholder="Enter the location name">
				</li>
				<li class="list-group-item">
					<input type="checkbox">
					<label>Exact Match</label>
				</li>
				<li class="list-group-item">
					<label>Regular Expressions:</label>
					<input type="text" class="form-control" placeholder="Enter the Regular Expression">
				</li>
			</ul>
		</div>
		<div class="col-lg-9 col-md-8 col-sm-7">
				<div class="panel panel-default">
				<div class="panel-heading"><i class="fa fa-search"></i> Search results for "<b>T.V.Abhinav Viswanath</b>"</div>
				<div class="panel-body">
					
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="pull-left">
								<a href="#">T.V.Abhinav Viswanath</a>
								<p>Studying in SRM University</p>
								<p>10 Mutual Friends</p>
							</div>
							<div class="dropdown pull-right">
								<button class="btn btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
								<ul class="dropdown-menu">
									<li><a href="#">Search within this result</a></li>
									<li><a href="#">View Similar</a></li>
									<li><a href="#">Mark as Useful</a></li>
									<li><a href="#">Flag Result</a></li>
									<li><a href="#">Public Notes</a></li>
									<li><a href="#">View Cached</a></li>
								</ul>
							</div>
							<div style="clear: both;"></div>
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

	utilityobj.multiselect($('.ta-stype'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true,
		buttonText: function(options, select) {
	        if (options.length === 0) {
	            return 'No filter selected ...';
	        }
	        else
	        {
	        	return options.length+' filter(s) selected!';
	        }
	    }
		});

	utilityobj.multiselect($('.ta-stiming'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true
	});

	setTimeout(function(){
			utilityobj.checkbox($('input[type="checkbox"]'), {
				checkedClass: 'glyphicon glyphicon-ok'
			});
		}, 300);
	
}

document.addEventListener("DOMContentLoaded",search_init_temp);
</script>