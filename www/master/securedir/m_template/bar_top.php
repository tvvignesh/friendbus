<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle toggle-menu menu-left" data-toggle="collapse" data-target="#ta-navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/profile.php" style="padding-top:4px;">
            	<img src="<?php echo "/master/securedir/m_images/logo_topbar.png";?>" height="47" width="45" style="padding-top:0px;position: relative;top: -9px;"> 
            	<div style="display: inline-block;">
            		Friendbus
            		<br>
            		<font size="1">The key to connect</font>
            	</div>
            </a>
            
            <button type="button" class="navbar-toggle navbar-righttoggle toggle-menu menu-right jPushMenuBtn menu-active" id="navbar_toggle_1" data-toggle="collapse" data-target="#ta-rmenu">
              <span class="sr-only">Toggle navigation</span>
              <span class="glyphicon glyphicon-asterisk"></span>
            </button>
            
            <button type="button" class="navbar-toggle navbar-righttoggle toggle-menu menu-right jPushMenuBtn menu-active" id="navbar_toggle_2" data-toggle="collapse" data-target="#ta-rmenu1">
              <span class="sr-only">Toggle navigation</span>
              <i class="fa fa-sitemap"></i>
            </button>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <!-- Push Menu Left -->
          <div class="collapse navbar-collapse cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="ta-navbar">
            <ul class="nav navbar-nav">
              <li class="active tbar_items"><a href="/profile.php" id="tbar_dashboard">
              <i class="fa fa-tachometer"></i>
              <span class="tbar_navtext">Dashboard</span>
              </a></li>
              <li class="tbar_items tbar_i_feeds"><a href="/social_feeds.php" id="tbar_feeds">
              <i class="fa fa-rss"></i>
              <span class="tbar_navtext">Feeds</span>
              </a></li>
            </ul>
            
            
            <form class="navbar-form navbar-left navbar-tasearch" role="search" style="min-width:250px;width:50%;">
				<div class="input-group margin-bottom-sm" style="width:100%;">
				<select class="form-control sbox_input" name="search_box_input" placeholder="Search for people,places,things"></select>
					<!-- <input type="text" name="search_box_input" class="sbox_input" placeholder="Search for people,places,things" class="form-control">-->
				</div>
            </form>
            
            <?php 
            	$userobj=new ta_userinfo();
            	if($userobj->checklogin())
            	{
            ?>
            
            
            
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                <span class="glyphicon glyphicon-user"></span>
                <span class="tbar_navtext">My Account</span>
                <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="/dash_settings.php" id="tbar_settings">Settings</a></li>
                  <li><a href="/dash_gallery.php">My Gallery</a></li>
                  <li class="divider"></li>
                  <?php echo '<li><a href="/logout.php">Logout</a></li>';?>
                </ul>
              </li>
<!--               <li><a href="#"><div id="ta_logo"></div></a></li> -->
            </ul>
            
            <?php 
            	}
            	else
            	{
            ?>
            	<ul class="nav navbar-nav navbar-right">
              		<li><a href="#" id="tbar_login" data-toggle="modal" data-target="#ta_loginbox">
			              <i class="fa fa-sign-in"></i>
			              <span class="tbar_navtext">Login</span>
              		</a></li>
            	</ul>
            <?php 
            	}
            ?>
            
          </div><!-- /.navbar-collapse -->
          
          
          
          
        </div><!-- /.container-fluid -->
      </nav>
      
      
      
<nav class="navbar navbar-default cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="ta-rmenu">
<ul class="nav navbar-nav navbar-right">
	<li class="active"><a href="/dash_contacts.php">People</a></li>
	<li><a href="/dash_conversations.php">Conversations</a></li>
	<li><a href="/dash_gallery.php">Gallery</a></li>
	<!-- <li><a href="/dash_events.php">Calendar & Events</a></li>-->
	<li><a href="/dash_groups.php">Groups</a></li>
	<!--<li><a href="/dash_lists.php">Lists</a></li>-->
	<!--<li><a href="#">Favorites</a></li>
	<li><a href="#">Product Alerts</a></li>
	<li><a href="#">Spam Box & Blacklists</a></li>
	<li><a href="#">Subscribers</a></li>
	<li><a href="#">Profile Hits</a></li>-->
	<li><a href="/dash_reputation.php">Reputation</a></li>
	<li><a href="#">Transactions</a></li>
</ul>
</nav>

<nav class="navbar navbar-default cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="ta-rmenu1">
<ul class="nav navbar-nav navbar-right">
	<li class="active"><a href="/dash_contacts.php#cbx-tabs-suggestions">Recommendations</a></li>
	<!--<li><a href="#">Calendar &amp; Organizer</a></li>-->
	<li><a href="/dash_contacts.php#cbx-tabs-invites">Requests &amp; Invites</a></li>
	<li><a href="/logs.php">Logs &amp; Statistics</a></li>
	<!-- <li><a href="#">Backup &amp; Transfer</a></li>-->
	<!-- <li><a href="#">APPS &amp; Cool Stuff</a></li>
	<li><a href="#">Developers</a></li>-->
	<li><a href="/help.php">Help &amp; Support</a></li>
	<li><a href="/privacy.php">Privacy,Security</a></li>
	<li><a href="/terms.php">T&amp;C</a></li>
	<li><a href="#">Feedbacks &amp; Suggestions</a></li>
</ul>
</nav>

 <div class="modal fade" id="ta_loginbox" tabindex="-1" role="dialog" aria-labelledby="ta_loginbox_label">
			  <div class="modal-dialog" role="document">
			    <form method="post" action="/index.php">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="ta_loginbox_label">Login</h4>
			      </div>
			      <div class="modal-body">
				        <b>User Name:</b> <input type="text" class="form-control" name="tauuname">
				        <b>Password:</b> <input type="password" class="form-control" name="taupass">
			        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Login</button>
			      </div>
			    </div>
			    </form>
			  </div>
			</div>