<?php
ob_start();

require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$userobj_mine=new ta_userinfo();
$uiobj=new ta_uifriend();
$assetobj=new ta_assetloader();
$fileobj=new ta_fileoperations();
$dbobj=new ta_dboperations();
$setobj=new ta_settings();
$logobj=new ta_logs();
$socialobj=new ta_socialoperations();
$blacklistobj=new ta_blacklists();
$msgobj=new ta_messageoperations();
$galobj=new ta_galleryoperations();
$colobj=new ta_collection();

if(isset($_GET["uid"]))
{
	$vuid=$_GET["uid"];//UID of the person who is being viewed
}
else
if(isset($_GET["uname"]))
{
	$vuname=$_GET["uname"];
	$vuid=$userobj->user_unametouid($vuname);
	if($vuid=="")die("OOPS! Some unexpected Error occured!");
}

if(!$userobj_mine->checklogin())
{
	$uid="";
}
else
{
	$userobj_mine->userinit();
	$uid=$userobj_mine->uid;
}

$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

if($vuid==$uid)
{
	die('<script type="text/javascript">window.location.assign("profile.php");</script>');
}

$userobj->user_initialize_data($vuid);

$themeobj->template_load_left();
$galid_att=$galobj->get_galid_special($userobj->uid,"16");
?>

<style type="text/css">
  #map { width: 100%;height:300px; }
  <?php
  $bpicid=$userobj->extras->bgprofpic;
  if($bpicid!="")
  {
  	$bpicurl=$utilityobj->pathtourl($galobj->geturl_media("",$bpicid,"3"));
  	echo '#prof_cont_enclose
		{
			background-image:url("'.$bpicurl.'");
		}';
  }
  ?>
</style>

<div>
<div id="template_content_body">

	<div class="mainhead_text"><?php echo $userobj->fname." ".$userobj->lname;?>
		
	
		<div class="btn-group pull-right">
		
		<?php 
			
		if($socialobj->friend_check_mutual($uid, $userobj->uid))
		{
			$ftid=$msgobj->get_threadid_byuser($userobj->uid,$uid);
			
			echo '<a href="/dash_conversations.php?thid='.$ftid.'" class="pull-left ta-rmargin"><button class="btn btn-default ta_s_sendmsg"><i class="fa fa-envelope"></i> <span class="hidden-xs hidden-sm hidden-md">Message</span></button></a>';
			
			echo '<a href="/dash_gallery.php?uid='.$userobj->uid.'" class="pull-left ta-rmargin"><button class="btn btn-default"><i class="fa fa-file"></i> <span class="hidden-xs hidden-sm hidden-md">View Gallery</span></button></a>';
		}
			$follabel='<i class="fa fa-rss"></i> <span class="hidden-xs hidden-sm hidden-md">Follow</span>';
			if($socialobj->follower_check($uid,$userobj->uid))
			{
				$follabel='<i class="fa fa-stop"></i> <span class="hidden-xs hidden-sm hidden-md">Unsubscribe</span>';
			}
			
			echo '<button class="btn btn-default ajax-btn" data-mkey="contacts_togfollow" data-fuid="'.$userobj->uid.'" data-eltarget=".usr_folstat_'.$userobj->uid.'"> <span class="usr_folstat_'.$userobj->uid.'">'.$follabel.'</span></button>';
			
			if($socialobj->friend_check_mutual($uid, $userobj->uid))
			{$fdisp='<i class="fa fa-times"></i> <span class="hidden-xs hidden-sm hidden-md">Remove Friend</span>';}
			else
				if($socialobj->friend_check($uid,$userobj->uid))
				{$fdisp='<i class="fa fa-times"></i> <span class="hidden-xs hidden-sm hidden-md">Cancel Request</span>';}
			else
				if($socialobj->friend_check($userobj->uid,$uid))
				{$fdisp='<i class="fa fa-check"></i> <span class="hidden-xs hidden-sm hidden-md">Accept Request</span>';}
			else
			{$fdisp='<i class="fa fa-user-plus"></i> Add Friend';}
			echo '<button class="btn btn-default ajax-btn" data-mkey="s_togfriend" data-fuid="'.$userobj->uid.'" data-eltarget=".s_fshipstat_'.$userobj->uid.'"><span class="s_fshipstat_'.$userobj->uid.'">'.$fdisp.'</span></button>';
		?>
		
		<div class="dropdown pull-right" style="margin-left:5px;">
				<button class="btn btn-default" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> <span class="hidden-xs hidden-sm hidden-md">Settings</span></button>
				
				<ul class="dropdown-menu" id="ta_profdpdwn">
				
				<?php 
					
					if($blacklistobj->block_check($uid,$userobj->uid,"1"))
					{
						$disp_block='<i class="fa fa-unlock"></i> Unblock';
					}
					else
					{
						$disp_block='<i class="fa fa-ban"></i> Block';
					}
				
					echo '<li><a href="#" class="s_blkfrnd_'.$userobj->uid.' ajax-btn" data-mkey="s_togblockfriend" data-fuid="'.$userobj->uid.'" data-eltarget=".s_blkfrnd_'.$userobj->uid.'" data-elpropstop="1">'.$disp_block.'</a></li>';
					
					echo '<li><a href="#" class="box-tog" data-mkey="s_reportfrnd" data-toggle="modal" data-fuid="'.$userobj->uid.'"><i class="fa fa-flag"></i> Report</a></li>';
				?>
   	  			</ul>
		</div>
		
		
		
		</div>
		<div style="clear: both;"></div>
	</div>
	<div class="mainhead_cont">
<ul class="nav nav-tabs responsive" id="galbox_cont_tabs">
	<li class="active"><a data-toggle="tab" href="#profbox_tab_about"><span class="glyphicon glyphicon-info-sign"></span> About</a></li>
  <!-- <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">More
    <span class="caret"></span></a>
    <ul class="dropdown-menu" id="ta_profdpdwn">
   	  <li><a href="#"><span class="glyphicon glyphicon-heart"></span> Interests <span class="badge">2</span></a></li>
      <li><a href="#"><i class="fa fa-gamepad"></i> Entertainment <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-star"></span> Favorites <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-stats"></span> Stats <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-eye-open"></span> Privacy <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-tags"></span> Mentions <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-globe"></span> Activities <span class="badge">2</span></a></li>
      <li><a href="#"><i class="fa fa-usd"></i> Transactions <span class="badge">2</span></a></li>
      <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings <span class="badge">2</span></a></li>
    </ul>
  </li>-->
</ul>

<div class="tab-content responsive">

<div id="profbox_tab_about" class="tab-pane fade in active">

<div id="prof_cont_enclose">

	
	
	
	  <div class="row">
	  		<!-- COLUMN 1 -->        
		    
		    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 prof_elem">
		    	<div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-info-circle"></i> <?php echo $userobj->fname." ".$userobj->lname;?></h4>
			        <ul class="list-group">
		        	<?php 
		        		if($setobj->checkvis($vuid,"prof_fname","base",$uid))echo '<li class="list-group-item"><i class="fa fa-tag"></i> <b>First Name:</b> '.$userobj->fname.'</li>';
		        		if($setobj->checkvis($vuid,"prof_mname","base",$uid))echo '<li class="list-group-item"><i class="fa fa-tag"></i> <b>Middle Name:</b> '.$userobj->mname.'</li>';
		        		if($setobj->checkvis($vuid,"prof_lname","base",$uid))echo '<li class="list-group-item"><i class="fa fa-tag"></i> <b>Last Name:</b> '.$userobj->lname.'</li>';
		        		if($setobj->checkvis($vuid,"prof_dob","base",$uid))
		        		{
		        			echo '<li class="list-group-item"><i class="fa fa-birthday-cake"></i> <b>Birthday:</b> '.$userobj->dob.'</li>';
		        			echo '<li class="list-group-item"><i class="fa fa-hourglass"></i> <b>Age:</b>'.$userobj->age['y'].'</li>';
		        		}
		        		if($setobj->checkvis($vuid,"prof_relationship","base",$uid))
		        		{
		        			?>
		        			
		        			<li class="list-group-item"><i class="fa fa-child"></i> <b>Relationship:</b> 
			        		<?php 
			        			$disptext="Single";
			        			switch($userobj->extras->relstat)
			        			{
			        				case "1":$disptext="Single";break;
			        				case "2":$disptext="Married";break;
			        				case "3":$disptext="In a Relationship";break;
			        				case "4":$disptext="Engaged";break;
			        				case "5":$disptext="It is Complicated";break;
			        				case "6":$disptext="Separated";break;
			        				case "7":$disptext="Divorced";break;
			        				case "8":$disptext="Widowed";break;
			        				default:$disptext="NA";break;
			        			}
			        			echo $disptext;
			        		?>
			        		</li>
		        			
		        			<?php 
		        		}
		        		
		        		if($setobj->checkvis($vuid,"prof_gender","base",$uid))
		        		{
		        			?>
		        			
		        			<li class="list-group-item"><i class="fa fa-male"></i> <b>Gender:</b> 
					        	<?php 
					        		if($userobj->gender=='m'){echo "Male";}else if($userobj->gender=='f'){echo "Female";}else if($userobj->gender=='o'){echo "Other";} else echo "NA";
					        	?>
				        	</li>
		        			
		        			<?php 
		        		}
		        		
		        		if($setobj->checkvis($vuid,"prof_aliases","base",$uid))echo '<li class="list-group-item"><i class="fa fa-user-secret"></i> <b>Other Names:</b><br>'.nl2br($userobj->extras->aliases).'</li>';
		        	?>
			        </ul>
		        </div>
		        </div>
		        
		        <div class="panel panel-default">
		        	<div class="panel-body profile-contact-info">
				        <h4 class="prof_elem_name">
			        	<i class="fa fa-phone"></i> Contact Info</h4>
			        <ul class="list-group">
			        <?php 
			        	if($setobj->checkvis($vuid,"prof_phone","base",$uid))echo '<li class="list-group-item"><i class="fa fa-phone" data-toggle="popover" data-diatarget="#pop_prof_phone" data-diaplace="top"></i> <b>Phone:</b> '.$userobj->phone.'</li>';
			        	if($setobj->checkvis($vuid,"prof_mobile","base",$uid))echo '<li class="list-group-item"><i class="fa fa-mobile" data-toggle="popover" data-diatarget="#pop_prof_mobile" data-diaplace="top"></i> <b>Mobile:</b> '.$userobj->mobno.'</li>';
			        	if($setobj->checkvis($vuid,"prof_email","base",$uid))echo '<li class="list-group-item"><i class="fa fa-envelope" data-toggle="popover" data-diatarget="#pop_prof_email" data-diaplace="top"></i> <b>E-Mail:</b> '.$userobj->email.'</li>';
			        	if($setobj->checkvis($vuid,"prof_country","base",$uid))
			        	{
			        		$cres=$utilityobj->countryfromcode($userobj->country);
			        		$cname=$cres[0][changesqlquote(tbl_country::col_name,"")];
			        		echo '<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Country:</b> '.$cname.'</li>';
			        	}
			        	if($setobj->checkvis($vuid,"prof_state","base",$uid))
			        	{
			        		$sres=$utilityobj->statefromid($userobj->state);
			        		$sname=$sres[0][changesqlquote(tbl_states::col_name,"")];
			        		echo '<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>State:</b> '.$sname.'</li>';
			        	}
			        	if($setobj->checkvis($vuid,"prof_address","base",$uid))echo '<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Address:</b> '.$userobj->compaddr.'</li>';
			        	if($setobj->checkvis($vuid,"prof_pincode","base",$uid))echo '<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Pincode:</b> '.$userobj->pincode.'</li>';
			        ?>
			        </ul>
			        
			        <div id="map"></div>
		        	</div>
    			</div>
    		
    		
    		
    		</div>
    		
    		
    		<!-- COLUMN 2 -->
    		
    		
    		<!--<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 prof_elem" id="ta_profpiccont">
		      
		       <div class="panel panel-default">
		      	<div class="panel-body">
		      			<h4 class="prof_elem_name"><i class="fa fa-cloud"></i> Interests</h4>
		        		<ul class="list-group">
				        	<li class="list-group-item"><i class="fa fa-futbol-o"></i> Sports</li>
				        	<li class="list-group-item"><i class="fa fa-film"></i> Movies</li>
				        	<li class="list-group-item"><i class="fa fa-television"></i> TV Shows</li>
				        	<li class="list-group-item"><i class="fa fa-music"></i> Songs</li>
				        	<li class="list-group-item"><i class="fa fa-book"></i> Books</li>
				        	<li class="list-group-item"><i class="fa fa-cutlery"></i> Food &amp; Beverages</li>
		        			<li class="list-group-item"><i class="fa fa-gamepad"></i> Games</li>
		        			<li class="list-group-item"><i class="fa fa-bars"></i> Apps</li>
				        	<li class="list-group-item"><i class="fa fa-heart"></i> Likes</li>
				        	<li class="list-group-item"><i class="fa fa-share"></i> Shares</li>
				        	<li class="list-group-item"><i class="fa fa-comments-o"></i> Reviews</li>
				        </ul>
				</div>
		       	</div>
		       	
		        </div>-->
    		
    		
    		
    		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 prof_elem">
    			
    			<div class="panel panel-default prof_elem" id="ta_ppign">
		        <div class="panel-body">
		        	
		        	<?php 
		        	if($setobj->checkvis($vuid,"prof_pic","base",$uid))
		        	{
		        		if($userobj->profpicurl==""||!($setobj->checkvis($vuid,"profpic","base",$uid)))
		        		{
		        			echo '<img src="/master/securedir/m_images/image-not-found.png" style="width: 100%;">';
		        		}
		        		else
		        		{
		        			echo '<img src="'.$utilityobj->pathtourl($userobj->profpicurl).'" style="width: 100%;">';
		        		}
		        	}
		        	?>
		        	
		        </div>
		      </div>
    			
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-language"></i> Languages</h4>
			        <ul class="list-group">
			        	<li class="list-group-item">
			        		 <i class="fa fa-microphone"></i> <b>Knows:</b> 
			        		 
			        		 
			        		 <?php 
			        		 $colres=$colobj->get_collection_complete_info(tbl_collection_languages::tblname,tbl_collection_languages::col_collangid,$userobj->col_langid);
			        		 $knows='';
		        		 	for($i=0;$i<count($colres);$i++)
		        		 	{
		        		 		if($i!=0){$knows.=",";}
		        		 		$langid=$colres[$i][changesqlquote(tbl_collection_languages::col_langid,"")];
		        		 		$langres=$utilityobj->language_get($langid);
		        		 		$langname=$langres[0][changesqlquote(tbl_languages::col_language,"")];
		        		 		$knows.=$langname;
		        		 	}
			        		 echo $knows;
			        		 ?>
			        		 
			        	</li>
			        	<!-- <li class="list-group-item"><i class="fa fa-pencil-square-o"></i> <b>Write:</b> English</li>
			        	<li class="list-group-item"><i class="fa fa-book"></i> <b>Read:</b> English,Tamil,Hindi</li>-->
			        </ul>
		        </div>
		        </div>
		        
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-magnet"></i> Beliefs</h4>
			        <ul class="list-group">
			        <?php 
			        	if($setobj->checkvis($vuid,"religion","base",$uid))echo '<li class="list-group-item"><i class="fa fa-magnet"></i> <b>Religion:</b> '.$userobj->extras->religion.'</li>';
			        	if($setobj->checkvis($vuid,"politicalview","base",$uid))echo '<li class="list-group-item"><i class="fa fa-magnet"></i> <b>Political View:</b> '.$userobj->extras->politicalview.'</li>';
			        ?>
			        </ul>
			    </div>
			    </div>
		        
		        
    		</div>
    		
    		<!-- COLUMN 3 -->
    		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 prof_elem">
    			<div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-briefcase" data-container="body"></i> Work</h4>
			        
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->work);$i++)
			        	{
			        		if(!($setobj->checkvis($vuid,"prof_work",$userobj->extras->work[$i]["wid"],$uid)))continue;
			        		
			        		echo '<li class="list-group-item"><i class="fa fa-briefcase" data-toggle="popover" data-diatarget="#pop_prof_work_'.$userobj->extras->work[$i]["wid"].'" data-diaplace="top" data-rebinder="work_popover"></i> '.$userobj->extras->work[$i]["role"].' at '.$userobj->extras->work[$i]["instname"].'</li>';
			        		echo
			        		'
  							<div id="pop_prof_work_'.$userobj->extras->work[$i]["wid"].'" class="popover_html">
							   	<b>Start Time:</b> '.$userobj->extras->work[$i]["stime"].'<br>
							   	<b>End Time:</b> '.$userobj->extras->work[$i]["etime"].'<br>
								<b>Min. Salary:</b> '.$userobj->extras->work[$i]["salarymin"].'<br>
								<b>Max. Salary:</b> '.$userobj->extras->work[$i]["salarymax"].'<br>
								<b>Organization Website URL:</b> <a href="'.$userobj->extras->work[$i]["insturl"].'" target="_blank">'.$userobj->extras->work[$i]["insturl"].'</a><br>
								<b>Notes:</b> '.$userobj->extras->work[$i]["notes"].'<br>
								<b>Colleagues:</b> '.$userobj->extras->work[$i]["listid"].'<br>
								<b>Located at:</b> '.$userobj->extras->work[$i]["locid"].'<br>
							</div>
				  		';
			        	}
			        ?>
			        </ul>
			     </div>
			     </div>
			     
			    
			    <!-- <div class="panel panel-default">
		    	<div class="panel-body">
					<h4 class="prof_elem_name"><i class="fa fa-map-marker" data-container="body" data-toggle="popover" data-placement="top" data-content="test"></i> Places</h4>
			        <ul class="list-group">
			        	<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Born at:</b> <a href="#" class="ta_edit_places">Kumbakonam, Chennai</a></li>
			        	<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Staying at:</b> <a href="#" class="ta_edit_places">Nanganallur, Chennai</a></li>
			        	<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Work at:</b> <a href="#" class="ta_edit_places">Egmore, Chennai</a></li>
			        	<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Schooling at:</b> <a href="#" class="ta_edit_places">Nanganallur, Chennai</a></li>
			        	<li class="list-group-item"><i class="fa fa-map-marker"></i> <b>Visited:</b> <a href="#" class="ta_edit_places">Mysore, Andhra, Karnataka</a></li>
			        </ul>
		        </div>
		        </div>-->
		        
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-building"></i> Career Profile</h4>
			        <ul class="list-group">
			        <?php 
			        	if($setobj->checkvis($vuid,"prof_resume","base",$uid)&&($userobj->extras->resume!=""))echo '<li class="list-group-item"><i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->resume.'" data-galid="'.$galid_att.'">Resume</a></li>';
			        	if($setobj->checkvis($vuid,"prof_coverletter","base",$uid)&&($userobj->extras->coverletter!=""))echo '<li class="list-group-item"><i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->coverletter.'" data-galid="'.$galid_att.'">Cover Letter</a></li>';
			        	if($setobj->checkvis($vuid,"prof_biodata","base",$uid)&&($userobj->extras->biodata!=""))echo '<li class="list-group-item"><i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->biodata.'" data-galid="'.$galid_att.'">Biodata</a></li>';
			        	if($setobj->checkvis($vuid,"prof_recommendations","base",$uid)&&($userobj->extras->recommendations!=""))echo '<li class="list-group-item"><i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->recommendations.'" data-galid="'.$galid_att.'">Recommendations</a></li>';
			        	
			        	if($userobj->extras->resume==""&&$userobj->extras->coverletter==""&&$userobj->extras->biodata==""&&$userobj->extras->recommendations=="")
			        	{
			        		echo 'Nothing here to show.';
			        	}
			        ?>
			        </ul>
			     </div>
			     </div>
			     
			     
			     <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-graduation-cap"></i> Education </h4>
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->education);$i++)
			        	{
			        		if(!($setobj->checkvis($vuid,"prof_education",$userobj->extras->education[$i]["eduid"],$uid)))continue;
			        		
			        		echo '<li class="list-group-item ta_profcog" data-elemtype="prof_education"><i class="fa fa-graduation-cap" data-toggle="popover" data-diatarget="#pop_prof_edu_'.$userobj->extras->education[$i]["eduid"].'" data-diaplace="top" data-rebinder="education_popover"></i> <span class="ta_edit_educelem" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["degree"].'</span> at <span class="ta_edit_educeleminst" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["instname"].'</span></li>';
			        		echo
			        		'
  							<div id="pop_prof_edu_'.$userobj->extras->education[$i]["eduid"].'" class="popover_html">
							   	<b>Start Time:</b> '.$userobj->extras->education[$i]["stime"].'<br>
							   	<b>End Time:</b> '.$userobj->extras->education[$i]["etime"].'<br>
								<b>Notes:</b> '.$userobj->extras->education[$i]["notes"].'<br>
								<b>Institution Website URL:</b> <a href="'.$userobj->extras->education[$i]["insturl"].'" target="_blank">'.$userobj->extras->education[$i]["insturl"].'</a><br>
								<b>Located at:</b> '.$userobj->extras->education[$i]["locid"].'<br>
								<b>Batchmates:</b> '.$userobj->extras->education[$i]["listid"].'<br>
							</div>
				  		';
			        		
			        	}
			        ?>
			        </ul>
			     </div>
			     </div>
			     
			    <div class="panel panel-default">
		    	<div class="panel-body">
		    		<ul class="list-group">
			        <h4 class="prof_elem_name"><i class="fa fa-trophy"></i> Achievements</h4>
			        <?php
			        	for($i=0;$i<count($userobj->extras->achievements);$i++)
			        	{
			        		if(!($setobj->checkvis($vuid,"prof_achievements",$userobj->extras->achievements[$i]["achievementid"],$uid)))continue;
			        		echo '<li class="list-group-item"><i class="fa fa-trophy" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$userobj->extras->achievements[$i]["notes"].'<hr>On <b>'.$userobj->extras->achievements[$i]["achievetime"].'</b>"></i> '.$userobj->extras->achievements[$i]["label"].'</li>';
			        	}
			        ?>
			        </ul>
		        </div>
		        </div>
		        
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-lightbulb-o"></i> Skills</h4>
			        
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->skills);$i++)
			        	{
			        		if(!($setobj->checkvis($vuid,"prof_skills",$userobj->extras->skills[$i]["skillid"],$uid)))continue;
			        		echo '<li class="list-group-item"><img src="'.$userobj->extras->skills[$i]["skillico"].'" width="20" height="20"> '.$userobj->extras->skills[$i]["label"].'</li>';
			        	}
			        ?>
			        </ul>
			     </div>
			     </div>
		        
		        <!-- <div class="panel panel-default">
		      	<div class="panel-body">
				       	<h4 class="prof_elem_name"><i class="fa fa-desktop"></i> Devices</h4>
				       	
				       	<ul class="list-group">
				       	
				       	
				       	<?php 
				       		for($i=0;$i<count($userobj->extras->devices);$i++)
				       		{
				       			if(!($setobj->checkvis($vuid,"prof_devices",$userobj->extras->devices[$i]["did"],$uid)))continue;
				       			
				       			echo '<li class="list-group-item">';
								$devico="";
				       			switch($userobj->extras->devices[$i]["devicetype"])
				       			{
				       				case "1":$devico='<i class="fa fa-desktop"></i>';break;
				       				case "2":$devico='<i class="fa fa-mobile"></i>';break;
				       				case "3":$devico='<i class="fa fa-tablet"></i>';break;
				       				case "4":$devico='<i class="fa fa-laptop"></i>';break;
				       				case "5":$devico='<i class="fa fa-clock-o"></i>';break;
				       				case "6":$devico='<i class="fa fa-desktop"></i>';break;
				       				default:$devico='<i class="fa fa-desktop"></i>';break;
				       			}
				       			
				       			
							echo $devico.' '.$userobj->extras->devices[$i]["label"].'</li>';
				       		}
				       	
				       	?>
				        </ul>
				 </div>
				 </div>-->
		        
		        
		        
		        <div class="panel panel-default">
		        	<div class="panel-body">
			    		<h4 class="prof_elem_name"><i class="fa fa-cloud"></i> Social &amp; Web Presence</h4>
			    		
				        <ul class="list-group">
				        
				        	<?php
				        		for($i=0;$i<count($userobj->extras->sociallinks);$i++)
				        		{
				        			
				        			if(!($setobj->checkvis($vuid,"prof_weburl",$userobj->extras->sociallinks[$i]["linkid"],$uid)))continue;
				        			
				        				echo '<li class="list-group-item">';
				        				if($userobj->extras->sociallinks[$i]["favico"]!="")
				        				{
				        					echo '<img src="'.$userobj->extras->sociallinks[$i]["favico"].'" width="20" height="20">';
				        				}
				        				else
				        				{
				        					$favico=$utilityobj->favicon_get($userobj->extras->sociallinks[$i]["url"]);
				        					if($favico!="")
				        					{
				        						echo '<img src="'.$favico.'" width="20" height="20">';
				        					}
				        					else
				        					{
				        						echo '<i class="fa fa-link"></i>';
				        					}
				        				}
				        				 
				        				echo
				        				'
										<b>'.$userobj->extras->sociallinks[$i]["label"].':</b> <a href="'.$userobj->extras->sociallinks[$i]["url"].'" title="Open this link in a new tab" target="_blank">'.$userobj->extras->sociallinks[$i]["url"].'</a></li>
										';
				        		}
			    			?>
				        
				        </ul>
    		</div>
    		</div>
		        
		        
		        
		        
		        
		        
		        
		        <div class="panel panel-default">
			<div class="panel-body">
    		 <h4 class="prof_elem_name"><i class="fa fa-users"></i> Groups</h4>
		        <div class="row">
		        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		        		<b>Administrating:</b>
		        		<ul class="list-group">
		        		<?php
		        			
		        			for($i=0;$i<count($userobj->extras->groups);$i++)
		        			{
		        				$gpid=$userobj->extras->groups[$i]["gpid"];
		        				if(!$setobj->checkvis($vuid,"prof_groups",$gpid,$uid))continue;
		        				if($userobj->extras->groups[$i]["memrole"]!="2"&&$userobj->extras->groups[$i]["memrole"]!="3")continue;
		        				if(!($setobj->checkvis($vuid,"prof_groups",$gpid,$uid)))continue;
		        				
		        				$group=$socialobj->groups_get($userobj->extras->groups[$i]["gpid"]);
		        				
		        				echo '<li class="list-group-item"><i class="fa fa-users" data-container="body" data-toggle="popover" data-placement="top" data-content="<b>Joined:</b> '.$userobj->extras->groups[$i]["jointime"].'<br><b>Added By:</b> '.
		        			$userobj->user_getinfo($userobj->extras->groups[$i]["addby"])["ufname"]	.'<br>"></i> '.$group[0]["gpname"].'</li>';
		        			}
		        		?>
				        </ul>
		        	</div>
		        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		        		<b>Member of:</b>
				        <ul class="list-group">
				        
				        <?php 
		        			
		        			for($i=0;$i<count($userobj->extras->groups);$i++)
		        			{
		        				$gpid=$userobj->extras->groups[$i]["gpid"];
		        				if(!$setobj->checkvis($vuid,"prof_groups",$gpid,$uid))continue;
		        				if($userobj->extras->groups[$i]["memrole"]!="1")continue;
		        				if(!($setobj->checkvis($vuid,"prof_groups",$userobj->extras->groups[$i]["gpid"],$uid)))continue;
		        				
		        				$group=$socialobj->groups_get($gpid);
		        				
		        				echo '<li class="list-group-item"><i class="fa fa-users" data-container="body" data-toggle="popover" data-placement="top" data-content="<b>Joined:</b> '.$userobj->extras->groups[$i]["jointime"].'<br><b>Added By:</b> '.
		        			$userobj->user_getinfo($userobj->extras->groups[$i]["addby"])["ufname"]	.'<br>"></i> '.$group[0]["gpname"].'</li>';
		        			}
		        		?>
				        
				        </ul>
		        	</div>
		        </div>
    		</div>
    		</div>
		        
		        
		        
    		</div>
	  </div>
	

    <?php 
    	if($setobj->checkvis($vuid,"prof_scribbles","base",$uid))
    	{
    ?>
		    <div class="row">
		    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 prof_elem">
		    	<div class="panel panel-default">
		    	<div class="panel-body" id="ta-scribbler">
			        <h4 class="prof_elem_name"><i class="fa fa-pencil"></i> Scribbles</h4>
			        	<?php echo $userobj->extras->scribbles;?>
			     </div>
		        </div>
		    	</div>
		  	</div>
  	<?php 
    	}
  	?>
  
</div>

</div>
</div>


</div>
</div>
</div>

<?php
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();

require_once 'profile_view_js.php';
?>
<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq62FIbYq77WkjuosVG4o65UFX7fI0XBM&callback=initMap">
    </script>