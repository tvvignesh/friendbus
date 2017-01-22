<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO SYSTEM
 * @author T.V.VIGNESH
 *
 */
class ta_system
{
	public function shellcmd($cmd,$async="")
	{
		$logobj=new ta_logs();
		//if($async!=""){$cmd.=" > /dev/null 2>/dev/null &";}
		if($async!=""){
			$cmd="(".$cmd.")";
			$cmd.=" > /dev/null 2>&1 &";
		}
		$logobj->store_templogs("CMD RUN:".$cmd);
		$ret=shell_exec($cmd);
		$logobj->store_templogs($ret);
		return $ret;
	}
	
	public function process_video($mediaid,$format,$fpath,$mkey)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		
		$fpath=str_replace('"', "", $fpath);$fpath=str_replace("'", "", $fpath);
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1", tbl_galdb::dbname);
		for($i=0;$i<count($res);$i++)
		{
			
			$mediathumb=$res[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
			$filname_real=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$galid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
			$jsonid=$res[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
			$medflag=$res[$i][changesqlquote(tbl_galdb::col_mediaflag,"")];
			
			$galid_vidpro=$galobj->get_galid_special($uid,"9");
			
			if(!file_exists($mediathumb))
			{
				$galobj->video_get_frame($fpath,$mediathumb,"-1","1");
			}
			
			$mediaid_new=$galobj->addmediatogal($fpath,$galid_vidpro,"3",$mediathumb,$filname_real,"","","","1","","",$uid);
			if($jsonid=="")
			{
				$duration=$galobj->video_get_duration($fpath);
				$jsonid=$utilityobj->jsondata_add('{"duration":"'.$duration.'","formats":{"'.$format.'":"'.$mediaid_new.'"}}');
			}
			else
			{
				$jsondata_old=$utilityobj->jsondata_get($jsonid,"1","1");
				if (isset($jsondata_old->formats)) 
				{
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
				else
				{
					$duration=$galobj->video_get_duration($fpath);
					
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_old->duration=$duration;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
			}
			
			if($medflag=="4")
			{
				$dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_mediaflag."='1',".tbl_galdb::col_jsonid."='$jsonid' WHERE ".tbl_galdb::col_mediaid."='$mediaid'", tbl_galdb::dbname);
			}
		}
	}
	
	public function process_document($mediaid,$format,$fpath,$mkey)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		$fpath.="/".$mediaid.".pdf";
		$fpath=str_replace('"', "", $fpath);$fpath=str_replace("'", "", $fpath);
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1", tbl_galdb::dbname);
		for($i=0;$i<count($res);$i++)
		{
				
			$mediathumb=$res[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
			$filname_real=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$galid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
			$jsonid=$res[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
			$medflag=$res[$i][changesqlquote(tbl_galdb::col_mediaflag,"")];
			$medurl=$res[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
				
			$galid_docpro=$galobj->get_galid_special($uid,"10");
		
			if(!file_exists($mediathumb))
			{
				$pages=$galobj->get_thumbnail_pdf($fpath,$mediathumb);
				if($pages==BOOL_FAILURE)$pages=0;
			}
				
			$mediaid_new=$galobj->addmediatogal($fpath,$galid_docpro,"2",$mediathumb,$filname_real,"","","","1","","",$uid);
			if($jsonid=="")
			{
				$jsonid=$utilityobj->jsondata_add('{"size":"'.filesize($medurl).'","pages":"'.$pages.'","formats":{"'.$format.'":"'.$mediaid_new.'"}}');
			}
			else
			{
				$jsondata_old=$utilityobj->jsondata_get($jsonid,"1","1");
				if (isset($jsondata_old->formats))
				{
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
				else
				{
					$jsonid=$utilityobj->jsondata_add('{"size":"'.filesize($medurl).'","pages":"'.$pages.'",formats":{"'.$format.'":"'.$mediaid_new.'"}}');
				}
			}
				
			if($medflag=="4")
			{
				$dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_mediaflag."='1',".tbl_galdb::col_jsonid."='$jsonid' WHERE ".tbl_galdb::col_mediaid."='$mediaid'", tbl_galdb::dbname);
			}
		}
	}
	
	public function process_audio($mediaid,$format,$fpath,$mkey)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		
		$fpath=str_replace('"', "", $fpath);$fpath=str_replace("'", "", $fpath);
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1", tbl_galdb::dbname);
		for($i=0;$i<count($res);$i++)
		{
		
			$mediathumb=$res[$i][changesqlquote(tbl_galdb::col_mediathumb,"")];
			$filname_real=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$galid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
			$jsonid=$res[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
			$medflag=$res[$i][changesqlquote(tbl_galdb::col_mediaflag,"")];
			$medurl=$res[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
		
			$galid_audpro=$galobj->get_galid_special($uid,"11");
		
			$mediaid_new=$galobj->addmediatogal($fpath,$galid_audpro,"4",$mediathumb,$filname_real,"","","","1","","",$uid);
			$duration=$galobj->audio_get_duration($medurl);
			if($jsonid=="")
			{
				$jsonid=$utilityobj->jsondata_add('{"size":"'.filesize($medurl).'","duration":"'.$duration.'","formats":{"'.$format.'":"'.$mediaid_new.'"}}');
			}
			else
			{
				$jsondata_old=$utilityobj->jsondata_get($jsonid,"1","1");
				if (isset($jsondata_old->formats))
				{
					$logobj->store_templogs("PROCESSING JSON: ");
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
				else
				{
					$jsondata_old->size=filesize($medurl);
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_old->duration=$duration;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
			}
		
			if($medflag=="4")
			{
				$dbobj->dbupdate("UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_mediaflag."='1',".tbl_galdb::col_jsonid."='$jsonid' WHERE ".tbl_galdb::col_mediaid."='$mediaid'", tbl_galdb::dbname);
			}
		}
	}
	
	public function process_scan($mediaid,$format,$fpath,$mkey,$opfile)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		
		$fpath=str_replace('"', "", $fpath);$fpath=str_replace("'", "", $fpath);
		$opfile=str_replace('"', "", $opfile);$opfile=str_replace("'", "", $opfile);
		$fp = fopen($fpath, "r+");
		$fp1 = fopen($opfile, "r");

		if (flock($fp, LOCK_EX)) {  // acquire an exclusive lock
			$writer=fread($fp1,filesize($opfile));
			$myfile = file_put_contents($fpath, $writer.PHP_EOL , FILE_APPEND);
			fflush($fp);            // flush output before releasing the lock
			flock($fp, LOCK_UN);    // release the lock
		} else {
			return FAILURE;
		}
		
		fclose($fp);
		unlink($opfile);
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1", tbl_galdb::dbname);
		for($i=0;$i<count($res);$i++)
		{
		
			$filname_real=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$galid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
		
			$galid_rep=$galobj->get_galid_special($uid,"12");
			
			$galobj->deletemedia($mediaid,$galid);
		
			$mediaid_new=$galobj->addmediatogal($fpath,$galid_rep,"2","",$filname_real,"","","","1","","",$uid);
		}
	}
	
	public function process_pic($mediaid,$format,$fpath,$mkey)
	{
		$dbobj=new ta_dboperations();
		$logobj=new ta_logs();
		$utilityobj=new ta_utilitymaster();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		
		$fpath=str_replace('"', "", $fpath);$fpath=str_replace("'", "", $fpath);
		if($mkey=="galthumb_main")
		{
			$conv="0";
		}
		else
			$conv="1";
		
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_galdb::tblname." WHERE ".tbl_galdb::col_mediaid."='$mediaid' LIMIT 0,1", tbl_galdb::dbname);
		for($i=0;$i<count($res);$i++)
		{
		
			$filname_real=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$galid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
			$jsonid=$res[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
			$medflag=$res[$i][changesqlquote(tbl_galdb::col_mediaflag,"")];
			$medurl=$res[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
		
			$galid_thumbpro=$galobj->get_galid_special($uid,"8");
		
			$mediaid_new=$galobj->addmediatogal($fpath,$galid_thumbpro,$conv,"",$filname_real,"","","","1","","",$uid);
			
			if($jsonid=="")
			{
				$jsonid=$utilityobj->jsondata_add('{"size":"'.filesize($medurl).'","formats":{"'.$format.'":"'.$mediaid_new.'"}}');
			}
			else
			{
				$jsondata_old=$utilityobj->jsondata_get($jsonid,"1","1");
				if (isset($jsondata_old->formats))
				{
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
				else
				{
					$jsondata_old->size=filesize($medurl);
					$jsondata_old->formats->$format=$mediaid_new;
					$jsondata_new=$utilityobj->object_to_json($jsondata_old);
					$utilityobj->jsondata_edit($jsonid,$jsondata_new);
				}
			}
		
			if($medflag=="4"||$conv=="0")
			{
				$sql="UPDATE ".tbl_galdb::tblname." SET ".tbl_galdb::col_mediaflag."='1',".tbl_galdb::col_jsonid."='$jsonid' ";
				if($conv=="0")
				{
					$sql.=tbl_galdb::col_mediathumb."='$mediaid_new' ";
				}
				$sql.="WHERE ".tbl_galdb::col_mediaid."='$mediaid'";
				$dbobj->dbupdate($sql, tbl_galdb::dbname);
			}
		}
	}
}
?>