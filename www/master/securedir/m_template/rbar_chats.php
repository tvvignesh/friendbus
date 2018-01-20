<?php
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

	$userobj=new ta_userinfo();
	$uobj=new ta_userinfo();
	$socialobj=new ta_socialoperations();
	$utilityobj=new ta_utilitymaster();
	$msgobj=new ta_messageoperations();
	
	if($userobj->checklogin())
	{
		$userobj->userinit();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		
	}
	else
	{
		echo 'Please <a href="/index.php">Login</a> to have conversations.';
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		return;
	}
	$fres=$socialobj->getfriends($userobj->uid);
	$totcap=20;
	$c=0;
	echo '<span class="hidden-xs hidden-sm">Your online Friends <br><ul class="list-group">';
	for($i=0;$i<count($fres);$i++)
	{
		$tuid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
		$uobj->user_initialize_data($tuid);
		$ftid=$msgobj->get_threadid_byuser($tuid,$userobj->uid);
		if($uobj->loginstat!="1")continue;
		$c++;
		echo '
				<li class="list-group-item rcbx_item" data-fuid="'.$tuid.'" data-threadid="'.$ftid.'">
					<img src="'.$utilityobj->pathtourl($uobj->getprofpic($tuid)).'" width="30" height="30" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$uobj->uid.'_a" data-diaplace="left" data-diatrigger="click"> 
							<a class="rcbx_item_cbopen">'.ucfirst($uobj->fname).' '.$uobj->mname.' '.$uobj->lname.'</a>
					<a href="/users.php?uid='.$tuid.'"><button class="btn btn-default pull-right"><i class="fa fa-eye"></i></button></a>
				</li>';
	}
	echo '</ul>
			<br><br>
			<b>Offline Friends</b><br><ul class="list-group">';
	for($i=0;$i<count($fres);$i++)
	{
		$tuid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
		$uobj->user_initialize_data($tuid);
		$ftid=$msgobj->get_threadid_byuser($tuid,$userobj->uid);
		if($uobj->loginstat=="1")continue;
		$c++;
		echo '
				<li class="list-group-item rcbx_item" data-fuid="'.$tuid.'" data-threadid="'.$ftid.'">
					<img src="'.$utilityobj->pathtourl($uobj->getprofpic($tuid)).'" width="30" height="30" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$uobj->uid.'_a" data-diaplace="left" data-diatrigger="click">
							<a class="rcbx_item_cbopen">'.ucfirst($uobj->fname).' '.$uobj->mname.' '.$uobj->lname.'</a>
					<a href="/users.php?uid='.$tuid.'"><button class="btn btn-default pull-right"><i class="fa fa-eye"></i></button></a>
				</li>';
		if($c>=$totcap)break;
	}
			echo '</ul></span>';
	
	if(count($fres)<=3)
	{
		echo '<div align="center"><a class="btn btn-primary" href="/dash_import.php" title="You can import contacts to get more friend recommendations.">Import Contacts</a></div>';
	}
	
?>