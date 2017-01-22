<?php 
header('Content-Type: application/json');
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$logobj=new ta_logs();
$utilityobj=new ta_utilitymaster();
$dbobj=new ta_dboperations();

if(isset($_POST["key"]))
$key=$_POST["key"];
else
if(isset($_GET["key"]))
$key=$_GET["key"];
else
return;
switch($key)
{
	case "religions":
		$ret=Array();
		$r=$utilityobj->religion_get_all();
		for($i=0;$i<count($r);$i++)
		{
			$value=$r[$i]["religid"];
			$text=$r[$i]["label"];
			$ret[$i]["value"]=$value;
			$ret[$i]["text"]=$text;
			
			//$arrytest = array(array('a'=>1, 'b'=>2),array('c'=>3),array('d'=>4));
		}
		//$data = array( 'returnval' =>3, 'message' =>"OOPS! You must be logged in to edit this field!" );
		die(json_encode($ret));
	break;
	case "skills":
		$query=$_POST["query"];
		$query=strtolower($query);
		$res=$dbobj->dbquery("SELECT ".tbl_skills::col_label." FROM ".tbl_skills::tblname." WHERE lower(".tbl_skills::col_label.") LIKE '".$query."%'",tbl_skills::dbname);
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
			$retobj["results"]=Array();
			$retobj=json_encode($retobj);
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret[$i]=$res[$i][changesqlquote(tbl_skills::col_label,"")];
			}
			$retobj=json_encode($newret);
		}
		echo $retobj;
	break;
	case "friends":
		$query=$_POST["query"];
		$query=strtolower($query);
		$userobj->userinit();
		$uid=$userobj->uid;

		//TODO OPTIMIZE THIS QUERY
		$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid." IN (SELECT ".tbl_frienddb::col_tuid." FROM ".tbl_frienddb::dbname.".".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid') AND (lower(".tbl_user_info::col_ufname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_ulname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_umname.") LIKE '".$query."%')";
		$res=$dbobj->dbquery($sql, tbl_user_info::dbname);		
		
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
			$retobj["results"]=Array();
			$retobj=json_encode($retobj);
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret["results"][$i]["text"]=$res[$i][changesqlquote(tbl_user_info::col_ufname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_umname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_ulname,"")];
				$newret["results"][$i]["photo"]=$utilityobj->pathtourl($res[$i][changesqlquote(tbl_user_info::col_profpicurl,"")]);
				$newret["results"][$i]["id"]=$res[$i][changesqlquote(tbl_user_info::col_usrid,"")];
			}
			$newret["totcount"]=count($res);
			$retobj=json_encode($newret);
		}
		echo $retobj;
	break;
	case "friends_notingp":
		$query=$_POST["query"];
		$gpid=$_POST["gpid"];
		$query=strtolower($query);
		$userobj->userinit();
		$uid=$userobj->uid;
		
		//TODO OPTIMIZE THIS QUERY
		$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid." IN (SELECT ".tbl_frienddb::col_tuid." FROM ".tbl_frienddb::dbname.".".tbl_frienddb::tblname." WHERE (".tbl_frienddb::col_fuid."='$uid' AND ".tbl_frienddb::col_tuid." NOT IN (SELECT ".tbl_members_attached::col_uid." FROM ".tbl_members_attached::dbname.".".tbl_members_attached::tblname." WHERE ".tbl_members_attached::col_gpid."='$gpid'))) AND (lower(".tbl_user_info::col_ufname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_ulname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_umname.") LIKE '".$query."%')";
		$res=$dbobj->dbquery($sql, tbl_user_info::dbname);
		
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
			$retobj["results"]=Array();
			$retobj=json_encode($retobj);
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret["results"][$i]["text"]=$res[$i][changesqlquote(tbl_user_info::col_ufname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_umname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_ulname,"")];
				$newret["results"][$i]["photo"]=$utilityobj->pathtourl($res[$i][changesqlquote(tbl_user_info::col_profpicurl,"")]);
				$newret["results"][$i]["id"]=$res[$i][changesqlquote(tbl_user_info::col_usrid,"")];
			}
			$newret["totcount"]=count($res);
			$retobj=json_encode($newret);
		}
		echo $retobj;
		break;
	case "post_tags":
		$query=$_POST["query"];
		$query=strtolower($query);
		$userobj->userinit();
		$uid=$userobj->uid;
		
		$sql="SELECT * FROM ".tbl_tags_post::tblname." WHERE (lower(".tbl_tags_post::col_tagname.") LIKE '".$query."%') AND ((".tbl_tags_post::col_usrid."='') OR (".tbl_tags_post::col_usrid."='$uid'))";
		$res=$dbobj->dbquery($sql, tbl_tags_post::dbname);		
		
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
			$retobj["results"]=Array();
			$retobj=json_encode($retobj);
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret["results"][$i]["text"]=$res[$i][changesqlquote(tbl_tags_post::col_tagname,"")];
				$newret["results"][$i]["photo"]=$utilityobj->pathtourl($res[$i][changesqlquote(tbl_tags_post::col_tagpic,"")]);
				$newret["results"][$i]["id"]=$res[$i][changesqlquote(tbl_tags_post::col_tagid,"")];
			}
			$newret["totcount"]=count($res);
			$retobj=json_encode($newret);
		}
		echo $retobj;
		break;
	case "search":
		if(isset($_POST["query"]))
		{
			$query=$_POST["query"];
		}
		else
		{
			$query="";
		}
		$query=strtolower($query);
		$userobj->userinit();
		$uid=$userobj->uid;
		
		$searchobj=new ta_search();
		$galobj=new ta_galleryoperations();
		
		$galid_att=$galobj->get_galid_special($userobj->uid,"16");
		
		$fres=$searchobj->search_tbar($query,$uid,"0","5");
		
		$j=0;
			$newret=Array();
		
			//PEOPLE
			$newret["results"][$j]["id"]=1;
			$newret["results"][$j]["text"]="People";
			$newret["results"][$j]["photo"]="";
			$newret["results"][$j]["children"]=Array();
			
			for($i=0;$i<count($fres["people"]);$i++)
			{
				$newret["results"][$j]["children"][$i]["text"]="<span class='ta-myusers'>".ucfirst($fres["people"][$i][changesqlquote(tbl_user_info::col_ufname,"")])." ".$fres["people"][$i][changesqlquote(tbl_user_info::col_umname,"")]." ".$fres["people"][$i][changesqlquote(tbl_user_info::col_ulname,"")]."</span>";
				$newret["results"][$j]["children"][$i]["photo"]=$utilityobj->pathtourl($fres["people"][$i][changesqlquote(tbl_user_info::col_cprofpic2,"")]);
				$newret["results"][$j]["children"][$i]["id"]=$fres["people"][$i][changesqlquote(tbl_user_info::col_usrid,"")];
				$newret["results"][$j]["children"][$i]["link"]="/users.php?uid=".$fres["people"][$i][changesqlquote(tbl_user_info::col_usrid,"")];
			}
			$newret["results"][$j]["totcount"]=count($fres["people"]);
			
			//GROUPS
			$j++;
			$newret["results"][$j]["id"]=2;
			$newret["results"][$j]["text"]="Groups";
			$newret["results"][$j]["photo"]="";
			$newret["results"][$j]["children"]=Array();
			
			for($i=0;$i<count($fres["groups"]);$i++)
			{
				$newret["results"][$j]["children"][$i]["text"]="<span class='ta-sgroups'>".ucfirst($fres["groups"][$i][changesqlquote(tbl_groups_info::col_gpname,"")])."</span>";
				$gppic_medid=$fres["groups"][$i][changesqlquote(tbl_groups_info::col_gppic,"")];
				$newret["results"][$j]["children"][$i]["photo"]=$gppic=$utilityobj->pathtourl($galobj->geturl_media($galid_att, $gppic_medid,"3"));
				$newret["results"][$j]["children"][$i]["id"]=$fres["groups"][$i][changesqlquote(tbl_groups_info::col_gpid,"")];
				$newret["results"][$j]["children"][$i]["link"]="/social_groups.php?gpid=".$fres["groups"][$i][changesqlquote(tbl_groups_info::col_gpid,"")];
			}
			
			$retobj=json_encode($newret);
		
		/*$sql="SELECT * FROM ".tbl_tags_post::tblname." WHERE (lower(".tbl_tags_post::col_tagname.") LIKE '".$query."%') AND ((".tbl_tags_post::col_usrid."='') OR (".tbl_tags_post::col_usrid."='$uid'))";
		$res=$dbobj->dbquery($sql, tbl_tags_post::dbname);
		
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret["results"][$i]["text"]=$res[$i][changesqlquote(tbl_tags_post::col_tagname,"")];
				$newret["results"][$i]["photo"]=$utilityobj->pathtourl($res[$i][changesqlquote(tbl_tags_post::col_tagpic,"")]);
				$newret["results"][$i]["id"]=$res[$i][changesqlquote(tbl_tags_post::col_tagid,"")];
			}
			$newret["totcount"]=count($res);
			$retobj=json_encode($newret);
		}*/
		echo $retobj;
		break;
	case "tagfriend":
		$query=$_GET["term"];
		$query=strtolower($query);
		$userobj->userinit();
		$uid=$userobj->uid;
		
		//TODO OPTIMIZE THIS QUERY
		$sql="SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_usrid." IN (SELECT ".tbl_frienddb::col_tuid." FROM ".tbl_frienddb::dbname.".".tbl_frienddb::tblname." WHERE ".tbl_frienddb::col_fuid."='$uid') AND (lower(".tbl_user_info::col_ufname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_ulname.") LIKE '".$query."%' OR lower(".tbl_user_info::col_umname.") LIKE '".$query."%')";
		$res=$dbobj->dbquery($sql, tbl_user_info::dbname);
		
		if($res==EMPTY_RESULT)
		{
			$retobj=Array();
			$retobj=json_encode($retobj);
		}
		else
		{
			$newret=Array();
			for($i=0;$i<count($res);$i++)
			{
				$newret[$i]["value"]=$res[$i][changesqlquote(tbl_user_info::col_ufname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_umname,"")]." ".$res[$i][changesqlquote(tbl_user_info::col_ulname,"")];
				$newret[$i]["uid"]=$res[$i][changesqlquote(tbl_user_info::col_usrid,"")];
				$newret[$i]["image"]=$utilityobj->pathtourl($res[$i][changesqlquote(tbl_user_info::col_cprofpic2,"")]);
			}
			$retobj=json_encode($newret);
		}
		
		echo $retobj;
		break;
	case "galpic_info":
			$galobj=new ta_galleryoperations();
			$fobj=new ta_fileoperations();
			$utilityobj=new ta_utilitymaster();
			
			$galid=$_POST["galid"];
			$mediaid=$_POST["mediaid"];
			
			$galires=$galobj->get_gallery_info($galid);
			$galname=$galires[0][changesqlquote(tbl_galinfo::col_galname,"")];
			
			$mires=$galobj->media_get_info($mediaid);
			$meddesc=$mires[0][changesqlquote(tbl_galdb::col_mediadesc,"")];
			$medtime=$mires[0][changesqlquote(tbl_galdb::col_mediatime,"")];
			$medurl=$mires[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
			$medtitle=$mires[0][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$meduid=$mires[0][changesqlquote(tbl_galdb::col_mediauid,"")];
		
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimetype=finfo_file($finfo,$medurl);
			
			list($width,$height) = getimagesize($medurl);
			
			$newret=Array();
			$newret["medtitle"]=$medtitle;
			$newret["meddesc"]=$meddesc;
			$newret["medtime"]=$medtime;
			$newret["medsize"]=$fobj->getfilesize($medurl);
			$newret["medwidth"]=$width;
			$newret["medheight"]=$height;
			$newret["medmime"]=$mimetype;
			$newret["meduname"]=$userobj->user_getfullname($meduid);
			$newret["medurl"]=$utilityobj->pathtourl($galobj->geturl_media($galid,$mediaid,"3"));
			
			$retobj=json_encode($newret);
			echo $retobj;
		break;
	case "countries":
			$dbobj=new ta_dboperations();
			$newret=Array();
			
			if(isset($_POST["query"])&&$_POST["query"]!="")
			{
				$query=$_POST["query"];
			}
			else
			{
				$query="";
			}
			
			$sql="SELECT a.".tbl_countries::col_id.",a.".tbl_countries::col_sortname.",b.".tbl_country::col_flag_32.",b.".tbl_country::col_flag_128.",b.".tbl_country::col_name." FROM ".tbl_countries::tblname." AS a,".tbl_country::tblname." AS b WHERE a.".tbl_countries::col_sortname."=b.".tbl_country::col_code2l." AND b.".tbl_country::col_name." LIKE '".$query."%'";
			
			$res=$dbobj->dbquery($sql,tbl_countries::dbname);
			
			for($i=0;$i<count($res);$i++)
			{
				$cid=$res[$i][changesqlquote(tbl_countries::col_id,"")];
				$cname=$res[$i][changesqlquote(tbl_country::col_name,"")];
				$csname=$res[$i][changesqlquote(tbl_countries::col_sortname,"")];
				$cflag=$res[$i][changesqlquote(tbl_country::col_flag_32,"")];
				
				$cflag="/master/securedir/m_images/flags/".$cflag;
				$newret["results"][$i]["id"]=$csname;
				$newret["results"][$i]["text"]=$cname;
				$newret["results"][$i]["photo"]=$cflag;
			}
			$retobj=json_encode($newret);
			echo $retobj;
		break;
	case "states":
		$dbobj=new ta_dboperations();
		if(isset($_POST["cname"]))
		{
			$cname=$_POST["cname"];
			$res1=$dbobj->dbquery("SELECT * FROM ".tbl_countries::tblname." WHERE ".tbl_countries::col_sortname."='$cname' LIMIT 0,1", tbl_countries::dbname);
			$cid=$res1[0][changesqlquote(tbl_countries::col_id,"")];
		}
		else
		if(isset($_POST["cid"]))
		{
			$cid=$_POST["cid"];
		}
		
		$sql="SELECT * FROM ".tbl_states::tblname." WHERE ".tbl_states::col_country_id."='$cid'";
		
		if(isset($_POST["query"])&&$_POST["query"]!="")
		{
			$query=$_POST["query"];
			$sql.=" AND ".tbl_states::col_name." LIKE '".$query."%'";
		}
		
		$res=$dbobj->dbquery($sql, tbl_states::dbname);
		
		for($i=0;$i<count($res);$i++)
		{
			$sid=$res[$i][changesqlquote(tbl_states::col_id,"")];
			$sname=$res[$i][changesqlquote(tbl_states::col_name,"")];
		
			$newret["results"][$i]["id"]=$sid;
			$newret["results"][$i]["text"]=$sname;
			$newret["results"][$i]["photo"]="";
		}
		$retobj=json_encode($newret);
		
		echo $retobj;
		break;
	case "cities":
		$sid=$_POST["sid"];
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_cities::tblname." WHERE ".tbl_cities::col_state_id."='$sid'", tbl_states::dbname);
		$retobj=json_encode($res);
		echo $retobj;
		break;
}
?>
