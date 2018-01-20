<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$uobj=new ta_userinfo();
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

$recobj=new ta_recommendations();
$recres=$recobj->get_user_friend_rec($userobj->uid);
?>  		
  		
  		<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">		  		 
  		 <div class="panel panel-default">
		  <div class="panel-heading">
		    <h3 class="panel-title">Contact Suggestions</h3>
		  </div>
		<div class="panel-body">
		  	
		
				<div class="row">
			  
			  
  <?php 
		  $c=0;
		  foreach($recres as $key=>$val)
		  {
		  	$c++;
		  	if($c>10)break;
		  	$profpic=$utilityobj->pathtourl($uobj->getprofpic($key));
		  	$fullname=$uobj->user_getfullname($key);
		  	echo
		  	'
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
			  <div class="media">
			  <div class="media-left">
			    <a href="/users.php?uid='.$key.'">
			      <img class="media-object" src="'.$profpic.'" alt="'.$fullname.'" width="100" height="100">
			    </a>
			  </div>
			  <div class="media-body">
			    <h4 class="media-heading">'.$fullname.'</h4>
			    '.$val.' Mutual Contacts
			  </div>
			</div>
			  </div>
		';
		  }
		  
		  if($c==0)
		  {
		  	echo 'We have no suggestions yet. You will start seeing suggestions when you add atleast 1 friend on your own.';
		  }
		  
?>  
			</div>
			
			
		<div align="center"><a class="btn btn-primary" style="color:white;" href="/dash_import.php">Import People</a></div>
		</div>						
				<div class="panel-footer">We are working to bring you even better recommendations feature</div>
		  </div>
		</div>		  		 
	</div>