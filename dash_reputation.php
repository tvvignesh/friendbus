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
	setcookie("returnpath",HOST_SERVER."/dash_reputation.php",0,'/');
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
	<div class="panel panel-default">
		<div class="panel-heading">Reputation points</div>
		<div class="panel-body">
			<ul class="list-group">
				<li class="list-group-item">You have: <span class="pull-right label label-success"><?php echo $userobj->getpoints($userobj->uid);?> Points</span></li>
			</ul>
		</div>
		<div class="panel-footer">
			Tip: You can gain more points by inviting people,adding more friends, creating/joining groups, publishing posts, etc. 
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">Point Log</div>
		<div class="panel-body">
			<ul class="list-group">
			
				<?php 
					$pres=$userobj->getpointlog($userobj->uid,"0","20");
					for($i=0;$i<count($pres);$i++)
					{
						$pts=$pres[$i][changesqlquote(tbl_user_reppoints::col_points,"")];
						$reason=$pres[$i][changesqlquote(tbl_user_reppoints::col_reason,"")];
						$atime=$pres[$i][changesqlquote(tbl_user_reppoints::col_addtime,"")];
						
						if($pts>0)
						{
							$pstatus="earnt";
						}
						else
						{
							$pstatus="lost";
						}
						
						$pts=abs($pts);
						echo '<li class="list-group-item">You '.$pstatus.' '.$pts.' points (Reason: '.$reason.') ['.$atime.']</li>';
					}
					if(count($pres)==0)
					{
						echo 'Looks like there is nothing to show here..';
					}
				?>
			</ul>
			
		</div>
		<div class="panel-footer">
			Tip: You can gain more points by adding more friends, creating groups, publishing post, etc. 
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

	$( ".ta-list-accordion" ).accordion({
		heightStyle: "content",
		collapsible: true
	});
}

document.addEventListener("DOMContentLoaded",lists_init_temp);
</script>