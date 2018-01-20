<?php
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$themeobj=new ta_template();
$galleryobj=new ta_galleryoperations();
$blacklistobj=new ta_blacklists();
$logobj=new ta_logs();
$utilityobj=new ta_utilitymaster();

if($userobj->checklogin())
{
	$userobj->userinit();
	$profpic=$utilityobj->pathtourl($userobj->getprofpic($userobj->uid,"1"));
}
else
{
	$profpic="/master/securedir/m_images/image-not-found.png";
}

?>

 <ul class="lo_menu">

      <li title="More"><a href="#" class="lo_menu-button home"></a></li>
      
      <li title="People"><a href="/dash_contacts.php"><i class="fa fa-user"></i></a></li>
      <li title="Conversations"><a href="/dash_conversations.php"><i class="fa fa-comments"></i></a></li>
      <li title="Gallery"><a href="/dash_gallery.php" class="archive"><i class="fa fa-file"></i></a></li>
      <!--<li title="Calendar & Events"><a href="/social_events.php" class="contact"><i class="fa fa-calendar"></i></a></li>-->
      <li title="Groups"><a href="/dash_groups.php" class="archive"><i class="fa fa-users"></i></a></li>
      <!-- <li title="Lists"><a href="/dash_lists.php" class="archive"><i class="fa fa-list"></i></a></li>-->
      <li title="Reputation"><a href="/dash_reputation.php"><i class="fa fa-star"></i></a></li>
      <!--<li title="Transactions"><a href="#" class="archive"><i class="fa fa-money"></i></a></li>-->
  </ul>
    
    <ul class="lo_menu-bar">
    <?php 
    if(!$userobj->checklogin())
    {
    	echo '<li><a href="/index.php">Login</a></li>';
    }
    else
    {
    	?>
    	
    	<a href="/profile.php" style="text-decoration:none;">
    <?php 
    	echo '<img src="'.$profpic.'" id="lbar_profpic" border="0" width="100%" data-original-title="" title="">';
    ?>
    </a>
    	<li><a href="/profile.php" class="lo_menu-button"><?php echo ucfirst($userobj->fname);?></a></li>
    	<li><a href="/social_feeds.php#feed_wall" class="lo_menu-button">My Posts</a></li>
        <!--<li><a href="#">Start the Tour</a></li>
        <li><a href="#">In Touch</a></li>
        <li><a href="#">Entertain Me</a></li>
        <li><a href="#">Time Saver</a></li>
        <li><a href="#">Contact Book</a></li>
        <li><a href="#">My Devices</a></li>
        <li><a href="#">My APPS</a></li>
        <li><a href="#">Switch Language</a></li>-->
    	
    	<?php 
    }
    ?>
    </ul>













