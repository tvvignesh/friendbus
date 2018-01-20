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

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Following</h3>
  </div>
<div class="panel-body">
  	

	<div class="row">

<?php 
$res=$socialobj->following_get($userobj->uid);
$foltotal=count($res);
if($foltotal==0)
{
	echo 'You are not Following any person yet!';
}
for($i=0;$i<$foltotal;$i++)
{
	$fid=$res[$i][changesqlquote(tbl_followdb::col_fuid,"")];
	$ftime=$res[$i][changesqlquote(tbl_followdb::col_crtime,"")];
	$mutcount=count($socialobj->get_mutualfriends($userobj->uid,$fid));
		
	$finfo=$userobj->user_getinfo($fid);
	$f_profpic=$finfo[changesqlquote(tbl_user_info::col_cprofpic2,"")];
	$f_fname=$finfo[changesqlquote(tbl_user_info::col_ufname,"")];
	$f_mname=$finfo[changesqlquote(tbl_user_info::col_umname,"")];
	$f_lname=$finfo[changesqlquote(tbl_user_info::col_ulname,"")];
	
	
	echo $uiobj->userpop_toggle($userobj->uid,$fid).'
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
			  <div class="media">
			  <div class="media-left">
			    <a href="#">
			      <img class="media-object" src="/'.$f_profpic.'" width="50" height="50">
			    </a>
			  </div>
			  <div class="media-body">
			    <h4 class="media-heading">'.$f_fname.' '.$f_mname.' '.$f_lname.'</h4>
			    Following since '.$ftime.'
				<br><button class="btn btn-default btn-sm" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$fid.'_a" data-diaplace="top">View</button>
			    <!--<br>20 of your contacts follow this person-->
			  </div>
			</div>
			  </div>
	';
}

?>
			  
			</div>
		
		
		</div>						
				<div class="panel-footer">Following <?php echo $foltotal;?> Person(s) in total</div>
		  </div>
