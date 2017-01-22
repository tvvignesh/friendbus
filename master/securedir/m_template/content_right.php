<?php 
	$socialobj=new ta_socialoperations();
	$userobj=new ta_userinfo();
	if(!$userobj->checklogin())
	{
		echo 'Please <a href="/index.php">Login</a>';return;
	}
	$userobj->userinit();
	$n_tot_unread=$socialobj->notifications_getcount($userobj->uid);
?>

<div id="template_content_right">
<ul class="nav nav-tabs responsive-tabs" id="rbar_tabs">
	<li class="active"><a href="#rbx_chats"><i class="fa fa-comment"></i></a></li>
	
	<li>
		<a href="#rbx_notify"><i class="fa fa-bell"></i>
			<span class="badge badge-notify cnt-notif"><?php echo $n_tot_unread;?></span>
		</a>
	</li>
	
	<!--<li><a href="#rbx_calendar"><i class="fa fa-calendar"></i></a></li>-->
	<li><a href="#rbx_recommendations"><i class="fa fa-thumbs-up"></i></a></li>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    <span class="caret"></span></a>
    <ul class="dropdown-menu dropdown-menu-right">
    	<!--<li><a href="#">Sticky Notes</a></li>-->
    	<li><a href="/logs.php">Logs & Statistics</a></li>
    	<!--<li><a href="#">Backup & Transfer</a></li>
      	<li><a href="#">APPS & Widgets</a></li>
      	<li><a href="#">Developers</a></li>-->
      	<li><a href="/dash_feedback.php">Feedbacks,Feature Requests &amp; Bug reports</a></li>
    </ul>
  </li>
</ul>

<div class="tab-content">
	<div id="rbx_chats" class="tab-pane active" data-taburl="/master/securedir/m_template/rbar_chats.php">
		<?php require_once MASTER_TEMPLATE.'/rbar_chats.php';?>
	</div>
	<div id="rbx_notify" class="tab-pane" data-taburl="/master/securedir/m_template/rbar_notify.php">
	</div>
    <!--<div id="rbx_calendar" class="tab-pane" data-taburl="/master/securedir/m_template/rbar_calendar.php">
    </div>-->
    <div id="rbx_recommendations" class="tab-pane" data-taburl="/master/securedir/m_template/rbar_recommendations.php">
    </div>
    <div id="rbx_music" class="tab-pane" data-taburl="/master/securedir/m_template/rbar_music.php">
    </div>
</div>

 </div>
 
<div class="chatbox_container" style="position:fixed;bottom:0px;right:0px;z-index:10000;">
<?php
require_once ROOT_SECURE.'/chatbox.php';
?>
</div>