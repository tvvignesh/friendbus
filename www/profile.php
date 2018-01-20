<?php
ob_start();

require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$uiobj=new ta_uifriend();
$assetobj=new ta_assetloader();
$fileobj=new ta_fileoperations();
$dbobj=new ta_dboperations();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$colobj=new ta_collection();

if(!$userobj->checklogin())
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/profile.php",0,'/');
	$GLOBALS["returnpath"]="/profile.php";
	$uiobj->returnpath("/index.php");
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
}

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
  	echo '
		#prof_cont_enclose
		{
			background-image:url("'.$bpicurl.'");
		}
		';
  }
  ?>
  .panel-body
  {
 	word-wrap: break-word;
  }
</style>

<div>
<div id="template_content_body">

<?php
	require_once 'pop_profile.php';
	require_once 'pop_box.php';
?>



	<div class="mainhead_text">
		<?php echo $userobj->fname." ".$userobj->lname;?>
		<div class="pull-right">
		<?php echo '<a title="" data-placement="bottom" data-original-title="Upload" class="btn btn-default box-tog ta-lmargin" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_bgprofpic" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-upload"></i><span class="hidden-xs hidden-sm hidden-md"> Change Background</span></a>';?>
		</div>
	</div>
	<div class="mainhead_cont">
<ul class="nav nav-tabs responsive" id="galbox_cont_tabs">
	<li class="active"><a data-toggle="tab" href="#profbox_tab_about"><span class="glyphicon glyphicon-info-sign"></span> <span class="hidden-xs hidden-sm hidden-md">About <div class="panel panel-default ta_prof_badges">You</div></span></a></li>
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
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_fname" data-elemid="base"><i class="fa fa-tag"></i> <b>First Name:</b> <a href="#" id="ta_edit_fname"><?php echo $userobj->fname;?></a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_mname" data-elemid="base"><i class="fa fa-tag"></i> <b>Middle Name:</b> <a href="#" id="ta_edit_mname"><?php echo $userobj->mname;?></a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_lname" data-elemid="base"><i class="fa fa-tag"></i> <b>Last Name:</b> <a href="#" id="ta_edit_lname"><?php echo $userobj->lname;?></a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_dob" data-elemid="base"><i class="fa fa-birthday-cake"></i> <b>Birthday:</b> <a href="#" id="ta_edit_dob"><?php echo $userobj->dob;?></a></li>
			        	<li class="list-group-item"><i class="fa fa-hourglass"></i> <b>Age:</b> <?php echo $userobj->age['y'];?></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_relationship" data-elemid="base"><i class="fa fa-child"></i> <b>Relationship:</b> 
			        	<a href="#" id="ta_edit_relationshipstat">
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
			        	</a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_gender" data-elemid="base"><i class="fa fa-male"></i> <b>Gender:</b> 
			        	<a href="#" id="ta_edit_gender">
			        	<?php 
			        		if($userobj->gender=='m'){echo "Male";}else if($userobj->gender=='f'){echo "Female";}else if($userobj->gender=='o'){echo "Other";} else echo "NA";
			        	?>
			        	</a>
			        	</li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_aliases" data-elemid="base"><i class="fa fa-user-secret"></i> <b>Other Names:</b> 
			        	<a href="#" id="ta_edit_aliases">
			        	<br>
			        		<?php 
			        			echo nl2br($userobj->extras->aliases);
			        		?>
			        	</a></li>
			        </ul>
		        </div>
		        </div>
		        
		        <div class="panel panel-default">
		        	<div class="panel-body profile-contact-info">
				        <h4 class="prof_elem_name">
			        	<i class="fa fa-phone"></i> Contact Info 
			        	<span class="pull-right" style="font-size:13px;"><a href="/dash_contacts.php">View Book</a></span>
			        	<div style="clear: both;"></div>
			        </h4>
			        <ul class="list-group">
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_phone" data-elemid="base"><i class="fa fa-phone" data-toggle="popover" data-diatarget="#pop_prof_phone" data-diaplace="top"></i> <b>Phone:</b> <a href="#" id="ta_edit_contactphone"><?php echo $userobj->phone;?></a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_mobile" data-elemid="base"><i class="fa fa-mobile" data-toggle="popover" data-diatarget="#pop_prof_mobile" data-diaplace="top"></i> <b>Mobile:</b> <a href="#" id="ta_edit_contactmobile"><?php echo $userobj->mobno;?></a></li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_email" data-elemid="base"><i class="fa fa-envelope" data-toggle="popover" data-diatarget="#pop_prof_email" data-diaplace="top"></i> <b>E-Mail:</b> <a href="#" id="ta_edit_contactemail"><?php echo $userobj->email;?></a></li>
			        	
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_country" data-elemid="base"><i class="fa fa-map-marker"></i> <b>Country:</b> 
				        	<select class="form-control prof_country_input country_input" name="country_input">
				        	<option value="<?php echo $userobj->country;?>" selected="selected">
				        		<?php 
				        			$cres=$utilityobj->countryfromcode($userobj->country);
				        			$cname=$cres[0][changesqlquote(tbl_country::col_name,"")];
				        			echo $cname;
				        		?>
				        	</option>
				        	</select>
			        	</li>
			        	
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_state" data-elemid="base"><i class="fa fa-map-marker"></i> <b>State:</b> 
				        	<select class="form-control prof_state_input state_input" name="state_input">
				        	<option value="<?php echo $userobj->state;?>" selected="selected">
				        		<?php 
				        			$sres=$utilityobj->statefromid($userobj->state);
				        			$sname=$sres[0][changesqlquote(tbl_states::col_name,"")];
				        			echo $sname;
				        		?>
				        	</option>
				        	</select>
			        	</li>
			        	
			        	
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_address" data-elemid="base"><i class="fa fa-map-marker"></i> <b>Address:</b> <a href="#" id="ta_edit_contactaddress"><?php echo $userobj->compaddr;?></a></li>
			        	
			        	
			        	
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_pincode" data-elemid="base"><i class="fa fa-map-marker"></i> <b>Pincode:</b> <a href="#" id="ta_edit_pincode"><?php echo $userobj->pincode;?></a></li>
			        </ul>
			        
			        <div id="map"></div>
		        	</div>
    			</div>
    		
    		
    		
    		</div>
	  		
    		
    		
    		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 prof_elem">
    			
    			<div class="panel panel-default prof_elem" id="ta_ppign">
		        <div class="panel-body ta_profcog" data-elemtype="prof_pic" data-elemid="base">
		        	<!-- <div class="profimg-uploader" style="position:absolute;top:0px;left:15px;display:none;">
		        		<div class="btn-group">
		        				<button class="btn btn-default" data-toggle="modal" data-target="#prof_profpicupload"><i class="fa fa-upload"></i> Upload Profile Pic</button>
		        		</div>
		        	</div>-->
		        	
		        	<?php 
		        		$galid_default=$galobj->get_galid_special($userobj->uid);
		        		if($userobj->profpicurl=="")
		        		{
		        			echo 'No Picture Uploaded Yet';
		        		}
		        		else
		        		{
		        			echo '<img src="'.$utilityobj->pathtourl($userobj->profpicurl).'" style="width: 100%;max-height:700px;">';
		        		}
		        		
		        		echo '<br><br><button class="btn btn-default box-tog" data-toggle="modal"  data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-target="#prof_profpicupload" data-galid="'.$galid_default.'" data-uptype="-3"><i class="fa fa-upload"></i> Upload</button>';
		        	?>
		        	
		        	<!-- <button class="btn btn-primary box-tog pull-left ta-lmargin gbx_pic_addstuff" data-galid="'.$galid_init.'"><i class="fa fa-plus"></i> Upload Pictures</button>-->
		        	
		        	
		        </div>
		      </div>
		      
		      
		      <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-language"></i> Languages</h4>
			        <ul class="list-group">
			        	<li class="list-group-item">
			        		 <i class="fa fa-microphone"></i> <b>I know:</b>
			        		 
			        		 <select class="form-control prof_lang" multiple="multiple">
			        		 <?php 
			        		 $selres=Array();
			        		 
			        		 $lall=$utilityobj->languages_get_all();
			        		 for($j=0;$j<count($lall);$j++)
			        		 {
			        		 	$langid=$lall[$j][changesqlquote(tbl_languages::col_langcode,"")];
			        		 	$langname=$lall[$j][changesqlquote(tbl_languages::col_language,"")];
			        		 	
			        		 	$selres[$langid]='<option value="'.$langid.'">'.$langname.'</option>';
			        		 }
			        		 
		        		 	$colres=$colobj->get_collection_complete_info(tbl_collection_languages::tblname,tbl_collection_languages::col_collangid,$userobj->col_langid);
		        		 	for($i=0;$i<count($colres);$i++)
		        		 	{
		        		 		$langid=$colres[$i][changesqlquote(tbl_collection_languages::col_langid,"")];
		        		 		$langres=$utilityobj->language_get($langid);
		        		 		$langname=$langres[0][changesqlquote(tbl_languages::col_language,"")];
		        		 		$selres[$langid]='<option value="'.$langid.'" selected="selected">'.$langname.'</option>';
		        		 	}
			        		 	
		        		 	foreach ($selres as $langid=>$opt)
		        		 	{
		        		 		echo $opt;
		        		 	}
			        		 ?>
							</select>
			        		 
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
			        	<li class="list-group-item"><i class="fa fa-magnet"></i> <b>Religion:</b> <a href="#" id="ta_edit_religion"><?php echo $userobj->extras->religion;?></a></li>
			        	<li class="list-group-item"><i class="fa fa-magnet"></i> <b>Political View:</b> <a href="#" id="ta_edit_politics"><?php echo $userobj->extras->politicalview;?></a></li>
			        </ul>
			     </div>
			     </div>
		      
		      
		      		        <div class="panel panel-default">
		      	<div class="panel-body">
				       	<h4 class="prof_elem_name"><i class="fa fa-desktop"></i> Devices</h4>
				       	
				       	<ul class="list-group">
				       	
				       	<a href="/logs.php">View Logs</a> to know details about your devices
				       	
				       	<?php 
				       		for($i=0;$i<count($userobj->extras->devices);$i++)
				       		{
				       			$did=$userobj->extras->devices[$i]["did"];
				       			echo '<li class="list-group-item ta_profcog" data-elemtype="prof_devices" data-elemid="'.$did.'">';
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
				       			
				       			
							echo $devico.' <a href="#" class="ta_edit_deviceelem" data-elemid="'.$userobj->extras->devices[$i]["did"].'">'.$userobj->extras->devices[$i]["label"].'</a></li>';
				       		}
				       	
				       	?>
				        </ul>
				 </div>
				 </div>
		      
		      
		      
		      
		      <!-- <div class="panel panel-default">
		      	<div class="panel-body">
		      			<h4 class="prof_elem_name"><i class="fa fa-cloud"></i> Interests</h4>
		        		<ul class="list-group">
				        	<li class="list-group-item"><i class="fa fa-futbol-o"></i> Sports</li>
				        	<li class="list-group-item"><i class="fa fa-film"></i> Movies</li>
				        	<li class="list-group-item"><i class="fa fa-television"></i> TV Shows</li>
				        	<li class="list-group-item"><i class="fa fa-music"></i> Songs</li>
				        	<li class="list-group-item"><i class="fa fa-book"></i> Books</li>
				        	<li class="list-group-item"><i class="fa fa-cutlery"></i> Food & Beverages</li>
		        			<li class="list-group-item"><i class="fa fa-gamepad"></i> Games</li>
		        			<li class="list-group-item"><i class="fa fa-bars"></i> Apps</li>
				        	<li class="list-group-item"><i class="fa fa-heart"></i> Likes</li>
				        	<li class="list-group-item"><i class="fa fa-share"></i> Shares</li>
				        	<li class="list-group-item"><i class="fa fa-comments-o"></i> Reviews</li>
				        </ul>
				</div>
		       	</div>-->
		      
		      
		      
		        
		        
    		</div>
    		
    		
    		
    		<!-- COLUMN 3 -->
    		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 prof_elem">
    			<div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-briefcase" data-container="body"></i> Work
			        
			        <div class="btn-group pull-right">
			        		<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#prof_work_add"><i class="fa fa-plus"></i></button>
			        </div>
			        <div style="clear: both;"></div>
			        
			        </h4>
			        
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->work);$i++)
			        	{
			        		$wid=$userobj->extras->work[$i]["wid"];
			        		echo '<li class="list-group-item ta_profcog" data-elemtype="prof_work" data-elemid="'.$wid.'"><i class="fa fa-briefcase" data-toggle="popover" data-diatarget="#pop_prof_work_'.$userobj->extras->work[$i]["wid"].'" data-diaplace="top" data-rebinder="work_popover"></i> <a href="#" class="ta_edit_work" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["role"].'</a> at <a href="#" class="ta_edit_work_inst" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["instname"].'</a></li>';
			        		echo
			        		'
  							<div id="pop_prof_work_'.$userobj->extras->work[$i]["wid"].'" class="popover_html">
							   	<b>Start Time:</b> <a href="#" class="ta_edit_work_stime" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["stime"].'</a><br>
							   	<b>End Time:</b> <a href="#" class="ta_edit_work_etime" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["etime"].'</a><br>
								<b>Organization Website URL:</b> <a href="#" class="ta_edit_work_orgurl" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["insturl"].'</a><br>
								<b>Notes:</b> <a href="#" class="ta_edit_work_notes" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["notes"].'</a><br>
								<!--<b>Colleagues:</b> <a href="#" class="ta_edit_work_colleagues" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["listid"].'</a><br>-->
								<b>Located at:</b> <a href="#" class="ta_edit_work_location" data-elemid="'.$userobj->extras->work[$i]["wid"].'">'.$userobj->extras->work[$i]["locid"].'</a><br>
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
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_resume" data-elemid="base">
			        	<?php 
			        	echo '<i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->resume.'" data-galid="'.$galid_att.'">Resume</a>';
			        	echo '<a title="" data-placement="bottom" data-original-title="Attach Documents" class="btn btn-default btn-xs box-tog ta-lmargin" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_resume" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a>';
			        	?>
			        	</li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_coverletter" data-elemid="base">
			        	<?php 
			        	echo '<i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->coverletter.'" data-galid="'.$galid_att.'">Cover Letter</a>';
			        	echo '<a title="" data-placement="bottom" data-original-title="Attach Documents" class="btn btn-default btn-xs box-tog ta-lmargin" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_coverletter" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a>';
			        	?>
			        	</li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_biodata" data-elemid="base">
			        	<?php 
			        	echo '<i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->biodata.'" data-galid="'.$galid_att.'">Biodata</a>';
			        	echo '<a title="" data-placement="bottom" data-original-title="Attach Documents" class="btn btn-default btn-xs box-tog ta-lmargin" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_biodata" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a>';
			        	?>
			        	</li>
			        	<li class="list-group-item ta_profcog" data-elemtype="prof_recommendations" data-elemid="base">
			        	<?php 
			        	echo '<i class="fa fa-pencil-square-o"></i> <a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$userobj->extras->recommendations.'" data-galid="'.$galid_att.'">Recommendations</a>';
			        	echo '<a title="" data-placement="bottom" data-original-title="Attach Documents" class="btn btn-default btn-xs box-tog ta-lmargin" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_recommendations" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a>';
			        	?>
			        	</li>
			        </ul>
			     </div>
			     </div>
		        
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-graduation-cap"></i> Education
			        <div class="btn-group pull-right">
			        		<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#prof_edu_add"><i class="fa fa-plus"></i></button>
			        </div>
			        <div style="clear: both;"></div>
			        </h4>
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->education);$i++)
			        	{
			        		$eduid=$userobj->extras->education[$i]["eduid"];
			        		echo '<li class="list-group-item ta_profcog" data-elemtype="prof_education" data-elemid="'.$eduid.'"><i class="fa fa-graduation-cap" data-toggle="popover" data-diatarget="#pop_prof_edu_'.$userobj->extras->education[$i]["eduid"].'" data-diaplace="top" data-rebinder="education_popover"></i> <a href="#" class="ta_edit_educelem" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["degree"].'</a> at <a href="#" class="ta_edit_educeleminst" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["instname"].'</a></li>';
			        		echo
			        		'
  							<div id="pop_prof_edu_'.$userobj->extras->education[$i]["eduid"].'" class="popover_html">
							   	<b>Start Time:</b> <a href="#" class="ta_edit_edu_stime" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["stime"].'</a><br>
							   	<b>End Time:</b> <a href="#" class="ta_edit_edu_etime" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["etime"].'</a><br>
								<b>Notes:</b> <a href="#" class="ta_edit_edu_notes" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["notes"].'</a><br>
								<b>Institution Website URL:</b> <a href="#" class="ta_edit_edu_insturl" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["insturl"].'</a><br>
								<b>Located at:</b> <a href="#" class="ta_edit_edu_loc" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["locid"].'</a><br>
								<b>Batchmates:</b> <a href="#" class="ta_edit_edu_batchmate" data-elemid="'.$userobj->extras->education[$i]["eduid"].'">'.$userobj->extras->education[$i]["listid"].'</a><br>
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
			        <h4 class="prof_elem_name"><i class="fa fa-trophy"></i> Achievements
			        
			        <div class="btn-group pull-right">
			        		<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#prof_achievement_add"><i class="fa fa-plus"></i></button>
			        </div>
			        <div style="clear: both;"></div>
			        
			        </h4>
			        <?php
			        	for($i=0;$i<count($userobj->extras->achievements);$i++)
			        	{
			        		$aid=$userobj->extras->achievements[$i]["achievementid"];
			        		echo '<li class="list-group-item ta_profcog" data-elemtype="prof_achievements" data-elemid="'.$aid.'"><i class="fa fa-trophy" data-container="body" data-toggle="popover" data-placement="top" data-content="'.$userobj->extras->achievements[$i]["notes"].'<hr>On <b>'.$userobj->extras->achievements[$i]["achievetime"].'</b>"></i> <a href="#" class="ta_edit_achieveelem" data-elemid="'.$userobj->extras->achievements[$i]["achievementid"].'">'.$userobj->extras->achievements[$i]["label"].'</a></li>';
			        	}
			        ?>
			        </ul>
		        </div>
		        </div>
		        
		        
		        <div class="panel panel-default">
		    	<div class="panel-body">
			        <h4 class="prof_elem_name"><i class="fa fa-lightbulb-o"></i> Skills
			        
			        <div class="btn-group pull-right">
			        		<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#prof_skill_add"><i class="fa fa-plus"></i></button>
			        </div>
			        <div style="clear: both;"></div>
			        
			        </h4>
			        
			        <ul class="list-group">
			        <?php 
			        	for($i=0;$i<count($userobj->extras->skills);$i++)
			        	{
			        		$skillid=$userobj->extras->skills[$i]["skillid"];
			        		echo '<li class="list-group-item ta_profcog" data-elemtype="prof_skills" data-elemid="'.$skillid.'"><img src="'.$userobj->extras->skills[$i]["skillico"].'" width="20" height="20"> <a href="#" class="ta_edit_skillelem" data-elemid="'.$userobj->extras->skills[$i]["skillid"].'">'.$userobj->extras->skills[$i]["label"].'</a></li>';
			        	}
			        ?>
			        </ul>
			     </div>
			     </div>
		        
		        
		        
		        		            		<div class="panel panel-default">
		        	<div class="panel-body">
			    		<h4 class="prof_elem_name"><i class="fa fa-cloud"></i> Social &amp; Web Presence
			    		
			    		<div class="btn-group pull-right">
			        		<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#prof_social_add"><i class="fa fa-plus"></i></button>
			        	</div>
			        	<div style="clear: both;"></div>
			    		
			    		</h4>
			    		
				        <ul class="list-group">
				        
				        	<?php
				        		for($i=0;$i<count($userobj->extras->sociallinks);$i++)
				        		{
				        			$linkid=$userobj->extras->sociallinks[$i]["linkid"];
				        			echo '<li class="list-group-item ta_profcog" data-elemtype="prof_weburl" data-elemid="'.$linkid.'">';
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
										<b><a href="#" class="ta_edit_social_label" data-elemid="'.$userobj->extras->sociallinks[$i]["linkid"].'">'.$userobj->extras->sociallinks[$i]["label"].'</a>:</b>
										<a href="#" class="ta_edit_social" data-elemid="'.$userobj->extras->sociallinks[$i]["linkid"].'">'.$userobj->extras->sociallinks[$i]["url"].'</a>
										<a href="'.$userobj->extras->sociallinks[$i]["url"].'" title="Open this link in a new tab" target="_blank"><i class="fa fa-external-link"></i></a>
										</li>
									';
				        		}
			    			?>
				        
				        </ul>
    		</div>
    		</div>
		        
		        
		        
		        
		        
		            		<div class="panel panel-default">
			<div class="panel-body">
    		 <h4 class="prof_elem_name"><i class="fa fa-users"></i> Groups <span class="pull-right" style="font-size:13px;"><a href="/dash_groups.php">View</a></span></h4>
    		 
		        <div class="row">
		        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		        		<b>Administrating:</b>
		        		<ul class="list-group">
		        		<?php 
		        			$socialobj=new ta_socialoperations();
		        			
		        			for($i=0;$i<count($userobj->extras->groups);$i++)
		        			{
		        				if($userobj->extras->groups[$i]["memrole"]!="2"&&$userobj->extras->groups[$i]["memrole"]!="3")continue;
		        				$gpid=$userobj->extras->groups[$i]["gpid"];
		        				$group=$socialobj->groups_get($gpid);
		        				
		        				echo '<li class="list-group-item ta_profcog" data-elemtype="prof_groups" data-elemid="'.$gpid.'"><i class="fa fa-users" data-container="body" data-toggle="popover" data-placement="top" data-content="<b>Joined:</b> '.$userobj->extras->groups[$i]["jointime"].'<br><b>Added By:</b> '.
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
		        				if($userobj->extras->groups[$i]["memrole"]!="1")continue;
		        				$gpid=$userobj->extras->groups[$i]["gpid"];
		        				$group=$socialobj->groups_get($gpid);
		        				
		        				echo '<li class="list-group-item ta_profcog" data-elemtype="prof_groups" data-elemid="'.$gpid.'"><i class="fa fa-users" data-container="body" data-toggle="popover" data-placement="top" data-content="<b>Joined:</b> '.$userobj->extras->groups[$i]["jointime"].'<br><b>Added By:</b> '.
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
	

    
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 prof_elem">
    	<div class="panel panel-default">
    	<div class="panel-body ta_profcog" data-elemtype="prof_scribbles" data-elemid="base" id="ta-scribbler">
	        <h4 class="prof_elem_name"><i class="fa fa-pencil"></i> Scribbles</h4>
	        <a href="#" id="ta_edit_scribbles">
	        	<?php echo $userobj->extras->scribbles;?>
	        </a>
	     </div>
        </div>
    	</div>
  	</div>
  
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
//$assetobj->load_js_final();

require_once 'profile_js.php';
?>
<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq62FIbYq77WkjuosVG4o65UFX7fI0XBM&callback=initMap">
    </script>
    
<script type="text/javascript">
function send_file_prof(profkey,attvar)
{
	loadobj.ajax_call({
		url:"/request_process.php",
		  method:"POST",
		  data:{mkey:"prof_files",profkey:profkey,attachments:attvar},
		  cache:false,
		  success:function(data){
			  $(".alert-text").html("The file has been uploaded successfully");
			  $(".alert-dismissable").css("display","block");
			  $(".statusinput").html("");
			  $(".status-subject").val("");
			  $(".statusinput").css("background-color","white");
			  window.location.reload();
		  }
	});
}

function uplded_resume()
{
	var attvar=JSON.stringify(window.mediaidarr);
	send_file_prof("resume",attvar);
}

function uplded_coverletter()
{
	var attvar=JSON.stringify(window.mediaidarr);
	send_file_prof("coverletter",attvar);
}

function uplded_recommendations()
{
	var attvar=JSON.stringify(window.mediaidarr);
	send_file_prof("recommendations",attvar);
}

function uplded_biodata()
{
	var attvar=JSON.stringify(window.mediaidarr);
	send_file_prof("biodata",attvar);
}

function uplded_bgprofpic()
{
	var attvar=JSON.stringify(window.mediaidarr);
	send_file_prof("prof_bgpic",attvar);
}
</script>
