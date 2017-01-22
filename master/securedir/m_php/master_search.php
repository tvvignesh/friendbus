<?php
/**
 *
* CONTAINS ALL SEARCH RELATED FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_search
{
	public function search_tbar($query,$uid,$start_f=0,$tot_f=5)
	{
		$dbobj=new ta_dboperations();
		$socialobj=new ta_socialoperations();
		$searchobj=new ta_search();
		$logobj=new ta_logs();
		
		$fres=Array();
		$GLOBALS["incuid"]=Array();
		
		$query=strtolower($query);
		if(isset($GLOBALS["search_inituid"]))unset($GLOBALS["search_inituid"]);
		$GLOBALS["srec"]=0;
		$fres["people"]=$searchobj->search_people_recursive($query,$uid,$start_f,$tot_f);
		//$fres["people"]=Array();
		
		$c_myc=count($fres["people"]);
		$needed=$tot_f-$c_myc;
		if($needed>0&&$query!="")
		{
			$people_public=$searchobj->search_people_public($query,$uid,0,$needed);
			for($j=0;$j<count($people_public);$j++)
			{
				$fruid=$people_public[$j][changesqlquote(tbl_user_info::col_usrid,"")];
				if(isset($GLOBALS["incuid"]))
				{
					if(!in_array($fruid,$GLOBALS["incuid"]))
					{
						array_push($fres["people"],$people_public[$j]);
						array_push($GLOBALS["incuid"],$fruid);
					}
				}
				else
				{
					array_push($fres["people"],$people_public[$j]);
				}
				
			}
		}
		
		$groups_public=$searchobj->search_groups($query, $uid);
		$fres["groups"]=$groups_public;
		
		//$fres["groups"][0]=$fres["people"][0];
		
		//$logobj->store_templogs("SEARCH RESULTS:".print_r($fres,true));
		return $fres;
	}
	
	public function search_people_recursive($query,$uid,$start=0,$tot=5,&$retarr=Array(),$reclevel_cur=0,$reclevel_tot=20)
	{
		$GLOBALS["srec"]++;
		
		if($GLOBALS["srec"]>$reclevel_tot)return $retarr;
		
		$dbobj=new ta_dboperations();
		$searchobj=new ta_search();
		$socialobj=new ta_socialoperations();
		$logobj=new ta_logs();
		
		if(!isset($GLOBALS["search_inituid"])){$GLOBALS["search_inituid"]=$uid;}
		
		if($query=="")
		{
			$mres=$socialobj->getfriends($uid,0,5);
			for($i=0;$i<count($mres);$i++)
			{
				$fruid=$mres[$i][changesqlquote(tbl_frienddb::col_tuid,"")];
				$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid."='$fruid' LIMIT 0,1";
				$mures=$dbobj->dbquery($sql, tbl_user_info::dbname);
				
				if(isset($GLOBALS["incuid"]))
				{
					if(!in_array($fruid,$GLOBALS["incuid"]))
					{
						array_push($retarr,$mures[0]);
						array_push($GLOBALS["incuid"],$fruid);
					}
				}
				else
				{
					array_push($retarr,$mures[0]);
				}
				
			}
			return $retarr;
		}
		
		$curcnt=count($retarr);
		$needed=$tot-$curcnt;
		
		$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid." IN (SELECT ".tbl_frienddb::col_tuid." FROM ".tbl_frienddb::dbname.".".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid') AND (lower(".tbl_user_info::col_ufname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_ulname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_umname.") LIKE '".$query."%') LIMIT $start,$needed";
		$res=$dbobj->dbquery($sql, tbl_user_info::dbname);		
		
		for($i=0;$i<count($res);$i++)
		{
			$fruid=$res[$i][changesqlquote(tbl_user_info::col_usrid,"")];
			
			if(isset($GLOBALS["incuid"]))
			{
				if(!in_array($fruid,$GLOBALS["incuid"]))
				{
					array_push($retarr,$res[$i]);
					array_push($GLOBALS["incuid"],$fruid);
				}
			}
			else
			{
				array_push($GLOBALS["incuid"],$fruid);
				array_push($retarr,$res[$i]);
			}
		}
		
		if(count($retarr)==$tot)
		{
			return $retarr;
		}
		else
		{
			$fres=$socialobj->getfriends($uid);
			if(count($fres)==0&&$uid==$GLOBALS["search_inituid"])
			{
				return $retarr;
			}
			for($j=0;$j<count($fres);$j++)
			{
				$tuid=$fres[$j][changesqlquote(tbl_frienddb::col_tuid,"")];
				if($tuid==$GLOBALS["search_inituid"])
				{
					if(count($fres)==1)
					{
						break;
					}
					else
					{
						continue;
					}
				}
				$reclevel_cur++;
				if($reclevel_cur>=$reclevel_tot){return $retarr;}
				$searchobj->search_people_recursive($query,$tuid,$start,$tot,$retarr);
				if(count($retarr)==$tot)
				{
					return $retarr;
				}
			}
			if($uid==$GLOBALS["search_inituid"])
			{
				return $retarr;
			}
		}
	}
	
	public function search_people_public($query,$uid,$start=0,$tot=5)
	{
		$dbobj=new ta_dboperations();
		$searchobj=new ta_search();
		$socialobj=new ta_socialoperations();

		/*SELECT u.*
		FROM user_prefs u
		JOIN categories cat ON u.category_id = cat.category_id
		WHERE p.user_id = 10 
		ORDER BY cat.name*/
		
		$uidarr=Array();
		$fres=$socialobj->getfriends($uid);
		for($j=0;$j<count($fres);$j++)
		$uidarr[$j]=$fres[$j][changesqlquote(tbl_frienddb::col_tuid,"")];
		
		$str = implode(',', $uidarr);
		$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE (lower(".tbl_user_info::col_ufname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_umname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_ulname.") LIKE '".$query."%') AND (".tbl_user_info::col_usrid." NOT IN ('$str')) ORDER BY ".tbl_user_info::col_usignuptime." DESC LIMIT $start,$tot";
		$res=$dbobj->dbquery($sql,tbl_user_info::dbname);
		return $res;
	}
	
	public function search_groups($query,$uid,$start=0,$tot=5)
	{
		$dbobj=new ta_dboperations();
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_groups_info::tblname." WHERE (lower(".tbl_groups_info::col_gpname.") LIKE '".$query."%') ORDER BY ".tbl_groups_info::col_gpname." ASC LIMIT $start,$tot", tbl_groups_info::dbname);
		return $res;
	}
}