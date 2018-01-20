<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
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
		    <h3 class="panel-title">Your Followers</h3>
		  </div>
		<div class="panel-body">
		  	
		
			<div class="row">
			  			  
<?php 
  	$res=$socialobj->follower_get($userobj->uid);
  	$totfol=count($res);
  	if($totfol==0)
  	{
  		echo "You have no Followers yet";
  	}
  	else
  	{
  		for($i=0;$i<$totfol;$i++)
  		{
  			$fid=$res[$i][changesqlquote(tbl_followdb::col_uid,"")];
  			$ftime=$res[$i][changesqlquote(tbl_followdb::col_crtime,"")];
  			$mutcount=count($socialobj->get_mutualfriends($userobj->uid,$fid));
  			
  			$finfo=$userobj->user_getinfo($fid);
  			$f_profpic=$finfo[changesqlquote(tbl_user_info::col_cprofpic2,"")];
  			$f_fname=$finfo[changesqlquote(tbl_user_info::col_ufname,"")];
  			$f_mname=$finfo[changesqlquote(tbl_user_info::col_umname,"")];
  			$f_lname=$finfo[changesqlquote(tbl_user_info::col_ulname,"")];
  			
  			
  			
  			echo $uiobj->userpop_toggle($userobj->uid,$fid).
  			'
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
		  <div class="media">
		  <div class="media-left">
		    <a href="#">
		      <img class="media-object" src="/'.$f_profpic.'" width="50" height="50">
		    </a>
		  </div>
		  <div class="media-body">
		    <h4 class="media-heading"><a href="/users.php?uid='.$fid.'" title="Started Following on '.$ftime.'">'.$f_fname.' '.$f_mname.' '.$f_lname.'</a></h4>
		    '.$mutcount.' Mutual Contacts
  			<br><button class="btn btn-default btn-sm" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$fid.'_a" data-diaplace="top">View</button>
		  </div>
		</div>
		  </div>
			';
  		}
  	}
  	
  ?>
		  
		</div>
	
	
	</div>						
			<div class="panel-footer"><?php echo $totfol;?> Follower(s) in total</div>
	  </div>
	</div>		  		 
</div>
