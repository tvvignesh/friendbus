<?php 
$result='';
$blacklistobj=new ta_blacklists();
$uiobj=new ta_uifriend();
$utilityobj=new ta_utilitymaster();

if($elem!="all"&&$elem!="")
{
	$lid_chk=$elem;
	$li_info=$socialobj->get_listinfo($lid_chk);
	if($li_info==EMPTY_RESULT)
	{
		echo "Sorry! No such list exists!";
		return;
	}
	$lname=$li_info[changesqlquote(tbl_listinfo::col_listname,"")];
	$li_pic=$li_info[changesqlquote(tbl_listinfo::col_listpic,"")];
	$li_desc=$li_info[changesqlquote(tbl_listinfo::col_listdesc,"")];
	$li_uid=$li_info[changesqlquote(tbl_listinfo::col_listuid,"")];
	
	$title_cnt=$lname;
}
else
{
	$lid_chk="";
	$title_cnt="All Contacts";
}

$res=$socialobj->getfriends($userobj->uid,"","",$lid_chk);
if(count($res)==0)
{
	if($lid_chk=="")
	{echo "You have no friends currently";}
	else
	{echo "You have no friends in this list currently";}
	
	return;
}

$result.='<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">'.$title_cnt.'</h3></div><div class="panel-body"><div class="row">';

$contacts_total=$socialobj->get_no_friends($userobj->uid,$lid_chk);

for($i=0;$i<count($res);$i++)
{
	$uid_friend=$res[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
	$flvl_friend=$res[$i][changesqlquote(tbl_frienddb::col_flevel,"")];
	$nick_friend=$res[$i][changesqlquote(tbl_frienddb::col_nickname,"")];
	
	$fres=$userobj->user_getinfo($uid_friend);
	$fname_friend=$fres["ufname"];
	$mname_friend=$fres["umname"];
	$lname_friend=$fres["ulname"];
	
	$profpic_friend=$fres["cprofpic2"];
	
	$displabel='';
	$listidarr=Array();
	
	$res3=$socialobj->get_mutualfriends($userobj->uid,$uid_friend);
	$mutcount=count($res3);
	
	$disp_nick="";
	if($nick_friend!=""){$disp_nick='(<span class="fr_fn_'.$uid_friend.'">'.$nick_friend.'</span>)';}
	
	$res1=$socialobj->get_belonginglist_friend($uid_friend,$userobj->uid);
	$listcount=count($res1);
	
	for($m=0;$m<$listcount;$m++)
	{
		$listidarr[$m]=$res1[$m][changesqlquote(tbl_listsdb::col_listid)];
	}
		
		$displabel='';
		for($j=0;$j<$listcount;$j++)
		{
			$listid=$res1[$j][changesqlquote(tbl_listsdb::col_listid,"")];
			$res2=$socialobj->get_listinfo($listid);
			$listlabel=$res2[changesqlquote(tbl_listinfo::col_listname,"")];
			$listdesc=$res2[changesqlquote(tbl_listinfo::col_listdesc,"")];
			
			$displabel.='<span class="label label-success contacts_lists" data-elemid="'.$listid.'" title="'.$listdesc.'">'.$listlabel.'</span> ';
		}

	$result.=$uiobj->userpop_toggle($userobj->uid,$uid_friend).'
								
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
			<div class="pull-left">
				<img class="media-object" src="'.$utilityobj->pathtourl($userobj->getprofpic($uid_friend)).'" width="50" height="50">
				<br><button class="btn btn-default btn-sm" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$uid_friend.'_a" data-diaplace="top">View</button>
			</div>
			<div class="pull-left cbx-tabs-contfr_rbx">
				<b><a href="/users.php?uid='.$uid_friend.'">'.$fname_friend.' '.$mname_friend.' '.$lname_friend.'</a> '.$disp_nick.'</b>
				<br>'.$mutcount.' Mutual Contacts
				<br>Friendship: <span class="d_flvl_'.$uid_friend.'">'.$socialobj->friend_leveltotext($flvl_friend).'</span>
				<div class="usr_flbadge_'.$uid_friend.'">'.$displabel.'</div> 
			</div>
			<div style="clear:both;"></div>
		</div>
			  ';
					

}
		
$result.='
</div>

		</div>
		<div class="panel-footer">'.$contacts_total.' Contact(s) in total</div>
		</div>
	';

echo $result;
?>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	  rebind_all();
});
</script>