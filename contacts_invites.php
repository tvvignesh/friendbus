<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_contacts.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}
?>	

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">		  		 
  		 
  		 
  		 <div class="panel panel-default">
		  <div class="panel-heading">
		    <h3 class="panel-title">Friend Requests Received</h3>
		  </div>
		<div class="panel-body">
		  	
		  	
		  	<div class="row">
			  
			  
			  <?php 
			  	$res=$socialobj->get_friend_requests($userobj->uid);
			  	$totreq=count($res);
			  	if($totreq==0)
			  	{
			  		echo "You have no pending Friend Requests";
			  	}
			  	else
			  	{
			  		for($i=0;$i<$totreq;$i++)
			  		{
			  			$fid=$res[$i][changesqlquote(tbl_frienddb::col_fuid,"")];
			  			$ftime=$res[$i][changesqlquote(tbl_frienddb::col_ftime,"")];
			  			$fmsg=$res[$i][changesqlquote(tbl_frienddb::col_fmsg,"")];
			  			
			  			$finfo=$userobj->user_getinfo($fid);
			  			$f_profpic=$finfo[changesqlquote(tbl_user_info::col_cprofpic2,"")];
			  			$f_fname=$finfo[changesqlquote(tbl_user_info::col_ufname,"")];
			  			$f_mname=$finfo[changesqlquote(tbl_user_info::col_umname,"")];
			  			$f_lname=$finfo[changesqlquote(tbl_user_info::col_ulname,"")];
			  			
			  			echo
			  			'
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd cbx-frcont-'.$fid.'">
			  <div class="media">
			  <div class="media-left">
			    <a href="#">
			      <img class="media-object" src="/'.$f_profpic.'" title="'.$fmsg.'" width="50" height="50">
			    </a>
			  </div>
			  <div class="media-body">
			    <h4 class="media-heading"><a href="/users.php?uid='.$fid.'">'.$f_fname.' '.$f_mname.' '.$f_lname.'</a></h4>
			    Sent a friend Request on '.$ftime.'
			    <br>
			    
			    <div class="btn-group cbx_requests">
				    <button class="btn btn-default ajax-btn" data-mkey="s_togfriend" data-fuid="'.$fid.'" data-suchide=".cbx-frcont-'.$fid.'" data-eltarget=".s_fshipstat_'.$fid.'"><i class="fa fa-check-circle"></i> Accept</button>
				    <button class="btn btn-default ajax-btn" data-mkey="s_removefriend" data-fuid="'.$fid.'" data-suchide=".cbx-frcont-'.$fid.'" data-eltarget=".s_fshipstat_'.$fid.'"><i class="fa fa-times-circle"></i> Decline</button>
			    </div>
			  	</div>
				</div>
			  	</div>
						';
			  		}
			  	}
			  	
			  ?>	
			  
			</div>
		  	
		  	
		</div>						
				<div class="panel-footer"><?php echo $totreq;?> Request(s) in total</div>
		  </div>
		  
		  
		  
		  
		  <div class="panel panel-default">
		  <div class="panel-heading">
		    <h3 class="panel-title">Friend Requests Sent (Not Accepted Yet)</h3>
		  </div>
		<div class="panel-body">
		  	
		  	
		  	<div class="row">
		  
		  
		  <?php 
			  	$res=$socialobj->get_friend_sentrequest($userobj->uid);
			  	$totreq_s=count($res);
			  	if($totreq_s==0)
			  	{
			  		echo "You have no pending Unaccepted Friend Requests";
			  	}
			  	else
			  	{
			  		for($i=0;$i<$totreq_s;$i++)
			  		{
			  			$fid=$res[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			  			$ftime=$res[$i][changesqlquote(tbl_frienddb::col_ftime,"")];
			  			$fmsg=$res[$i][changesqlquote(tbl_frienddb::col_fmsg,"")];
			  			
			  			$finfo=$userobj->user_getinfo($fid);
			  			$f_profpic=$finfo[changesqlquote(tbl_user_info::col_cprofpic2,"")];
			  			$f_fname=$finfo[changesqlquote(tbl_user_info::col_ufname,"")];
			  			$f_mname=$finfo[changesqlquote(tbl_user_info::col_umname,"")];
			  			$f_lname=$finfo[changesqlquote(tbl_user_info::col_ulname,"")];
			  			
			  			echo
			  			'
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd cbx-frcont1-'.$fid.'">
			  <div class="media">
			  <div class="media-left">
			    <a href="#">
			      <img class="media-object" src="/'.$f_profpic.'" title="'.$fmsg.'" width="50" height="50">
			    </a>
			  </div>
			  <div class="media-body">
			    <h4 class="media-heading">'.$f_fname.' '.$f_mname.' '.$f_lname.'</h4>
			    Sent a friend Request on '.$ftime.'
			    <br>
			    
			    <div class="btn-group cbx_requests">
				    <!--<button class="btn btn-default ajax-btn" data-mkey="s_togfriend" data-fuid="'.$fid.'" data-suchide=".cbx-frcont1-'.$fid.'" data-eltarget=".s_fshipstat_'.$fid.'"><i class="fa fa-check-circle"></i> Resend</button>-->
				    <button class="btn btn-default ajax-btn" data-mkey="s_removefriend" data-fuid="'.$fid.'" data-suchide=".cbx-frcont1-'.$fid.'" data-eltarget=".s_fshipstat_'.$fid.'"><i class="fa fa-times-circle"></i> Cancel Request</button>
			    </div>
			  	</div>
				</div>
			  	</div>
						';
			  		}
			  	}
			  	
			  ?>	
			  
			</div>
		  	
		  	
		</div>						
				<div class="panel-footer"><?php echo $totreq_s;?> Request(s) in total</div>
		  </div>
		  
		  
		  
		  
		</div>		  		 
			</div>
