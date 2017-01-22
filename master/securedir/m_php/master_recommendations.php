<?php
/**
 *
 * CONTAINS RECOMMENDATIONS OF USERS
 * @author T.V.VIGNESH
 *
 */
class ta_recommendations
{
	public function get_user_friend_rec($uid,$start="0",$tot=10) 
	{
		$socialobj=new ta_socialoperations();
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
		$uobj=new ta_userinfo();
		
		$uobj->user_initialize_data($uid);
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$uid' LIMIT 0,1", tbl_user_extras::dbname);
		$col_uid=$res[0][changesqlquote(tbl_user_extras::col_recommendations,"")];
		
		$recarr=Array();
		
		$fres=$socialobj->getfriends($uid,"0","10");
		for($i=0;$i<count($fres);$i++)
		{
			$fid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			$ffres=$socialobj->getfriends($fid,"","");
			for($j=0;$j<count($ffres);$j++)
			{
				$ffid=$ffres[$j][changesqlquote(tbl_frienddb::col_tuid,"")];
				if($ffid==$uid)continue;
				if($socialobj->friend_check($uid,$ffid))continue;
				if($col_uid!="")
				{
					if($colobj->check_collection_item(tbl_collection_users::tblname,tbl_collection_users::col_col_uid,$col_uid,tbl_collection_users::col_uid,$ffid))
					{
						continue;
					}
				}
				
				$mut=count($socialobj->get_mutualfriends($uid,$ffid));
				if($mut==0)continue;
				$recarr[$ffid]=$mut;
			}
		}
		$c=0;
		if((count($fres)==0)||count($recarr)<$tot)
		{
			$needed=$tot-count($recarr);
			$impres=$dbobj->dbquery("SELECT * FROM ".tbl_user_import::tblname." WHERE ".tbl_user_import::col_uid."='$uid' AND ".tbl_user_import::col_prodflag."='1'", tbl_user_import::dbname);
			for($k=0;$k<count($impres);$k++)
			{
				$emailaddr=$impres[$k][changesqlquote(tbl_user_import::col_email,"")];
				$usrres=$socialobj->emailtouser($emailaddr);
				if(count($usrres)==0)continue;
				$ffid=$usrres[0][changesqlquote(tbl_user_info::col_usrid,"")];
				if($socialobj->friend_check_mutual($uid,$ffid))continue;
				$mut=count($socialobj->get_mutualfriends($uid,$ffid));
				$recarr[$ffid]=$mut;
				$c++;
				if(count($recarr)>=$tot)break;
			}
		}
		
		if($needed!=0)
		{
			$impres1=$dbobj->dbquery("SELECT * FROM ".tbl_user_import::tblname." WHERE ".tbl_user_import::col_email."='$uobj->email' AND ".tbl_user_import::col_prodflag."='1'", tbl_user_import::dbname);
			for($k=0;$k<count($impres1);$k++)
			{
				$ffid=$impres1[$k][changesqlquote(tbl_user_import::col_uid,"")];
				if($socialobj->friend_check_mutual($uid,$ffid))continue;
				$mut=count($socialobj->get_mutualfriends($uid,$ffid));
				$recarr[$ffid]=$mut;
				$c++;
				if(count($recarr)>=$tot)break;
			}
		}
		
		arsort($recarr);
		return $recarr;
	}
	
	
	public function get_user_group_rec($uid,$start="0",$tot="10")
	{
		$socialobj=new ta_socialoperations();
		$userobj=new ta_userinfo();
		$dbobj=new ta_dboperations();
		$colobj=new ta_collection();
	
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_extras::tblname." WHERE ".tbl_user_extras::col_uid."='$uid' LIMIT 0,1", tbl_user_extras::dbname);
		$col_gpid=$res[0][changesqlquote(tbl_user_extras::col_rec_groups,"")];
	
		$recarr=Array();
	
		$fres=$socialobj->getfriends($uid,"0","10");
		for($i=0;$i<count($fres);$i++)
		{
			$fid=$fres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
			
			$memres=$socialobj->getgroups($fid);
			for($j=0;$j<count($memres);$j++)
			{
				$gpid=$memres[$j][changesqlquote(tbl_members_attached::col_gpid,"")];
				if($socialobj->group_user_check($gpid,$uid))continue;
				
				if($col_gpid!="")
				{
					if($colobj->check_collection_item(tbl_collection_groups::tblname,tbl_collection_groups::col_col_gpid,$col_gpid,tbl_collection_groups::col_gpid,$gpid))
					{
						continue;
					}
				}
				
				if(isset($recarr[$gpid]))
				{
					$recarr[$gpid]++;
				}
				else
				{
					$recarr[$gpid]=1;
				}
			}
			
		}
		
		if(count($recarr)<$tot)
		{
			$gprem=$socialobj->get_groups_all();
			for($i=0;$i<count($gprem);$i++)
			{
				$gpid=$gprem[$i][changesqlquote(tbl_groups_info::col_gpid,"")];
				if($socialobj->group_user_check($gpid,$uid))continue;
				if(isset($recarr[$gpid]))
				{
					$recarr[$gpid]++;
				}
				else
				{
					$recarr[$gpid]=1;
				}
				if(count($recarr)==$tot)break;
			}
		}
	
		arsort($recarr);
		return $recarr;
	}
}
?>