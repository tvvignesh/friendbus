<?php
ob_start();
$noecho="yes";
$GLOBALS["noecho"]="yes";
require_once $_SERVER["DOCUMENT_ROOT"].'/filemaster.php';
$logobj=new ta_logs();
require_once $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';

$utilityobj=new ta_utilitymaster();
$fileobj=new ta_fileoperations();
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$dbobj=new ta_dboperations();

if(!$userobj->checklogin())
{
	die("Please Login to do any file upload!");
}
$userobj->userinit();

$galid=$_POST["galid"];
$uptime=$_POST["uptime"];
if(isset($_POST["uploadtype"]))
{
	$uptype=$_POST["uploadtype"];
}

$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

$fext=$fileobj->fileinfo($_POST["flowFilename"],"3");
$fext=strtolower($fext);
$filname_real=$fileobj->fileinfo($_POST["flowFilename"],"4");

$config = new \Flow\Config();
$config->setTempDir($_SERVER["DOCUMENT_ROOT"].'/upload/chunks_temp_folder');
$file = new \Flow\File($config);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if ($file->checkChunk()) {
		header("HTTP/1.1 200 Ok");
	} else {
		header("HTTP/1.1 204 No Content");
		return ;
	}
} else {
	if ($file->validateChunk()) {
		$file->saveChunk();
	} else {
		// error, invalid chunk upload request, retry
		header("HTTP/1.1 400 Bad Request");
		return ;
	}
}

if($file->validateFile())
{		// File upload was completed

	$galurl=$galobj->geturl_gallery($userobj->uid,$galid);
	$mediaid=$galobj->generate_mediaid();
	$fpath_orig=$galurl."/".$mediaid.".".$fext;
	$mediatype=$fileobj->get_mediatype_flag($fext);
	
	if($uptype=="1")
	{
		$galid_sbox=$galobj->get_galid_special($userobj->uid,"15");
		$galid_reports=$galobj->get_galid_special($userobj->uid,"12");
		
		$galurl_sbox=$galobj->geturl_gallery($userobj->uid,$galid_sbox);
		$galurl_reports=$galobj->geturl_gallery($userobj->uid,$galid_reports);
		
		$fpath_orig=$galurl_sbox."/".$mediaid.".".$fext;
		$fpath_report=$galurl_reports."/rep_".$uptime.".txt";
		$logobj->store_templogs("REPORT:".$fpath_report);
	}
	else
	if($mediatype=="1")
	{
		$galid_thumbpro=$galobj->get_galid_special($userobj->uid,"8");
		
		$galurl_thumbpro=$galobj->geturl_gallery($userobj->uid,$galid_thumbpro);		
		
		$fpath_orig=$galurl."/".$mediaid.".".$fext;
		$fpath_thumb=$galurl_thumbpro."/".$mediaid.".jpg";
	}
	else
	if($mediatype=="3")
	{
		$galid_vidpro=$galobj->get_galid_special($userobj->uid,"9");
		$galid_vidthumb=$galobj->get_galid_special($userobj->uid,"8");
		$galid_meta=$galobj->get_galid_special($userobj->uid,"13");
		
		$galurl_vidthumb=$galobj->geturl_gallery($userobj->uid,$galid_vidthumb);
		$galurl_vidpro=$galobj->geturl_gallery($userobj->uid,$galid_vidpro);
		$galurl_meta=$galobj->geturl_gallery($userobj->uid,$galid_meta);
		
		$fpath_orig=$galurl."/".$mediaid.".".$fext;
		$fpath_flv=$galurl_vidpro."/".$mediaid."_flv.flv";
		$fpath_ogv=$galurl_vidpro."/".$mediaid."_ogv.ogv";
		$fpath_webm=$galurl_vidpro."/".$mediaid."_webm.webm";
		
		$fpath_meta=$galurl_meta."/".$mediaid.".txt";
	}
	else
	if($mediatype=="2")
	{
		$galid_docpro=$galobj->get_galid_special($userobj->uid,"10");
		$galid_docthumb=$galobj->get_galid_special($userobj->uid,"8");
		$galurl_docthumb=$galobj->geturl_gallery($userobj->uid,$galid_docthumb);
		$galurl_docpro=$galobj->geturl_gallery($userobj->uid,$galid_docpro);
		$fpath_orig=$galurl."/".$mediaid.".".$fext;
		$fpath_pdfdir=$galurl_docpro."/";
	}
	else
	if($mediatype=="4")
	{
		$galid_audpro=$galobj->get_galid_special($userobj->uid,"11");
		$galid_meta=$galobj->get_galid_special($userobj->uid,"13");
		
		$galurl_audpro=$galobj->geturl_gallery($userobj->uid,$galid_audpro);
		$galurl_meta=$galobj->geturl_gallery($userobj->uid,$galid_meta);
		
		$fpath_orig=$galurl."/".$mediaid.".".$fext;
		$fpath_mp3=$galurl_audpro."/".$mediaid."_mp3.mp3";
		$fpath_oga=$galurl_audpro."/".$mediaid."_oga.oga";
		
		$fpath_meta=$galurl_meta."/".$mediaid.".txt";
	}
	
	if ($file->save($fpath_orig)) 
	{
		if($uptype=="1")
		{
			echo $galobj->addmediatogal($fpath_orig,$galid_sbox,"-1","",$filname_real,"","","","1","",$mediaid,$userobj->uid);
			$galobj->test_virus($fpath_orig,$fpath_report,$mediaid,"1");
		}
		else
		if($mediatype=="1")
		{
			$jsonid=$utilityobj->jsondata_add('{}');
			$galobj->addmediatogal($fpath_orig,$galid,$mediatype,$fpath_thumb,$filname_real,"","","","4",$jsonid,$mediaid,$userobj->uid);
			echo $mediaid;
			$logobj->store_templogs("ATTMEDID:".$mediaid);
			if($uptype=="2")
			{$mkey="galthumb_process";}
			else
			{$mkey="galthumb_main";}
			$galobj->image_get_thumbnail($fpath_orig,$fpath_thumb,"150","",$mediaid,"1",$mkey);
			
			if($uptype=="-3")
			{
				$medurl=$galobj->geturl_media($galid, $mediaid,"3");
				$status=$dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_profpicurl."='$medurl',".tbl_user_info::col_cprofpic2."='$fpath_thumb' WHERE ".tbl_user_info::col_usrid."='$userobj->uid'",tbl_user_info::dbname);
			}
		}
		else
		if($mediatype=="3")
		{
			$galobj->metadata_get_vidaud($fpath_orig,$fpath_meta,$mediaid,"1");
			$thumburl=$galurl_vidthumb."/t_".$mediaid."_640x480.jpg";
			$mediaid_meta=$galobj->addmediatogal($fpath_orig,$galid_meta,"2",$thumburl,$filname_real,"","","","1","","",$userobj->uid);
			$jsonid=$utilityobj->jsondata_add('{"metafile":"'.$mediaid_meta.'"}');
			echo $galobj->addmediatogal($fpath_orig,$galid,$mediatype,$thumburl,$filname_real,"","","","4",$jsonid,$mediaid,$userobj->uid);
			$galobj->video_get_frame($fpath_orig,$thumburl);
			
			$galobj->convert_to_webm($fpath_orig,$fpath_webm,$mediaid,"1");
			$galobj->convert_to_ogv($fpath_orig,$fpath_ogv,$mediaid,"1");
			$galobj->convert_to_flv($fpath_orig,$fpath_flv,$mediaid,"1");
		}
		else
		if($mediatype=="2")
		{
			$thumburl=$galurl_docthumb."/t_".$mediaid."_d.jpg";
			echo $galobj->addmediatogal($fpath_orig,$galid,$mediatype,$thumburl,$filname_real,"","","","4","",$mediaid,$userobj->uid);
			$galobj->convert_to_pdf($fpath_orig,$fpath_pdfdir,$mediaid,"1");
		}	
		else
		if($mediatype=="4")
		{
			$mediaid_meta=$galobj->generate_mediaid();
			$jsonid=$utilityobj->jsondata_add('{"metafile":"'.$mediaid_meta.'"}');
			echo $galobj->addmediatogal($fpath_orig,$galid,$mediatype,"",$filname_real,"","","","4",$jsonid,$mediaid,$userobj->uid);
			$galobj->addmediatogal($fpath_meta,$galid_meta,"2","",$filname_real,"","","","1","",$mediaid_meta,$userobj->uid);
			$galobj->convert_to_mp3($fpath_orig,$fpath_mp3,$mediaid,"1");
			$galobj->convert_to_oga($fpath_orig,$fpath_oga,$mediaid,"1");
			$galobj->metadata_get_vidaud($fpath_orig,$fpath_meta,$mediaid,"1");
		}
		else
		{
			echo $galobj->addmediatogal($fpath_orig,$galid,$mediatype,"","","","","","1","",$mediaid,$userobj->uid);
		}
		
	}
}
else 
{
	// This is not a final chunk, continue to upload
}

$logobj->store_templogs("DONE FILUPLOAD");
?>