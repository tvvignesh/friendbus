<?php


$presult='';
if(isset($_GET["tid"]))
{
	ob_start();
	require_once 'header.php';
	$assetobj=new ta_assetloader();
	$assetobj->load_css_theme_default();
	$assetobj->load_css_product_login();
	$themeobj->template_load_left();
	echo '
			<div id="template_content_body">
			<div class="row">
			<div class="col-lg-6 col-md-4 col-sm-6">
		';
	$utilityobj=new ta_utilitymaster();
	$userobj=new ta_userinfo();	
	$uiobj=new ta_uifriend();
	
	if(!$userobj->checklogin())
	{
	
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		setcookie("returnpath",HOST_SERVER."/social_feeds.php",0,'/');
	}
	else
	{
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		$userobj->userinit();
		$uid=$userobj->uid;
	}
	$tid=$_GET["tid"];
}
else
if(isset($mytid))
{
	$noecho="yes";
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	$tid=$mytid;
}
else
{
	$noecho="yes";
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	$presult.= "";
	return;
}

$dbobj=new ta_dboperations();
$socialobj=new ta_socialoperations();
$userobj=new ta_userinfo();
$uobj=new ta_userinfo();
$cuobj=new ta_userinfo();
$ruobj=new ta_userinfo();
$msgobj=new ta_messageoperations();
$utilityobj=new ta_utilitymaster();
$colobj=new ta_collection();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$audobj=new ta_audience();
$uiobj=new ta_uifriend();

$userobj->userinit();

$galid_att=$galobj->get_galid_special($userobj->uid,"16");

$postres=$msgobj->getthreadoutline($tid);

if(count($postres)==0)return "";

$audid=$postres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
$msgtype=$postres[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
if(!$audobj->audience_check_user($audid,$userobj->uid))
{
	return "";
}

if($msgtype=="5")
{
	$gtres=$dbobj->dbquery("SELECT * FROM ".tbl_threads_attached::tblname." WHERE ".tbl_threads_attached::col_tid."='$tid' LIMIT 0,1", tbl_threads_attached::dbname);
	$gpid=$gtres[0][changesqlquote(tbl_threads_attached::col_gpid,"")];
}

if($msgtype=="5"&&(!$socialobj->group_user_check($gpid, $userobj->uid)))
{
	return "";
}

$tid=$postres[0][changesqlquote(tbl_message_outline::col_tid,"")];
$subject=$postres[0][changesqlquote(tbl_message_outline::col_subject,"")];

$fuid=$postres[0][changesqlquote(tbl_message_outline::col_fid,"")];


$uobj->user_initialize_data($fuid);

$fprofpic=$utilityobj->pathtourl($userobj->getprofpic($fuid));

$stext="";

$contres=$msgobj->getmsg($tid,"0","1");
$contmsg=$contres[0][changesqlquote(tbl_message_content::col_msg,"")];
$rateid=$contres[0][changesqlquote(tbl_message_content::col_rateid,"")];
$msgid=$contres[0][changesqlquote(tbl_message_content::col_msgid,"")];
$col_medid=$contres[0][changesqlquote(tbl_message_content::col_col_mediaid,"")];
$msgtime=$contres[0][changesqlquote(tbl_message_content::col_msgtime,"")];

if(!$msgobj->share_checkoriginal($tid,$msgid))
{
	$stext.='(<a href="#">See Original</a>)';/*TODO FRAME POST LINK USE GET ORIGINAL FUNC TO GET*/
}

$sharecount=$msgobj->share_get_no($tid,$msgid);

$colres=$colobj->get_collection_complete_info(tbl_collection_media::tblname, tbl_collection_media::col_col_mediaid, $col_medid);

$atttext='';
$imgatt='';
$totatt=count($colres);
$mmtype=0;
for($a=0;$a<$totatt;$a++)
{
	$medid=$colres[$a][changesqlquote(tbl_collection_media::col_mediaid,"")];
	$medres=$galobj->media_get_info($medid);
	$medtitle=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_mediatitle,"")]);
	$medurl=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_mediaurl,"")]);
	$medthumb=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_mediathumb,"")]);
	$medgalid=$utilityobj->pathtourl($medres[0][changesqlquote(tbl_galdb::col_galid,"")]);
	$medtype=$medres[0][changesqlquote(tbl_galdb::col_mediatype,"")];
	
	$atttext.='<a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$medid.'" data-galid="'.$medgalid.'">'.$medtitle.'</a><br>';
	if($a==0)
	{
		$imgatt.='<a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$medid.'" data-galid="'.$medgalid.'"><img src="'.$medthumb.'" class="img-responsive" style="width:100%;max-height:500px;"><br></a>';
		$mmtype=$medtype;
	}
	else
	{
		$imgatt.='<a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_medviewer" data-mediaid="'.$medid.'" data-galid="'.$medgalid.'"><img src="'.$medthumb.'" class="ta-rmargin" width="50" height="50"><br></a>';
	}
}

if($totatt!=0)
{
	$atttext="<hr>Attached files:<br>".$atttext;
}
$pf=0;
$ptagvar='<span class="label label-success comp-feed-label"';
$tgres=$msgobj->tagpost_getlinks($tid);
if(count($tgres)!=0)
{
	$colptagid=$tgres[0][changesqlquote(tbl_message_tags::col_col_ptagid,"")];
	$coltres=$msgobj->tagpost_getcol($colptagid);
	
	if(count($coltres)>1)
	{
		$ptagvar.=' title="';
	}
	else
	{
		$ptagvar.='>';
	}
	
	for($t=0;$t<count($coltres);$t++)
	{
		$ptagid=$coltres[$t][changesqlquote(tbl_collection_tagpost::col_tagid,"")];
		$tgres_o=$msgobj->tagpost_get("",$ptagid);
		if(count($tgres_o)!=0)
		{
			if($pf==1)$ptagvar.=',';
			$tgname=$tgres_o[0][changesqlquote(tbl_tags_post::col_tagname,"")];
			$ptagvar.=$tgname;
			$pf=1;
		}
	}
	
	if(count($coltres)>1)
	{
		$ptagvar.='">'.count($coltres).' Tags</span>';
	}
}
else
{
	$ptagvar.='>No Tags';
}
$ptagvar.='</span>';

$upvotes=$socialobj->rating_get_upvotes($rateid);
$downvotes=$socialobj->rating_get_downvotes($rateid);
$views=$msgobj->get_total_views($tid, $msgid);
$cres=$msgobj->thread_get_comthread($tid);
$comtid=$cres[0][changesqlquote(tbl_thread_comments::col_ctid,"")];


$presult.=
'<div class="ta-fdbx" data-threadid="'.$tid.'" data-msgid="'.$msgid.'">';



$presult.='<div class="panel panel-default">'.$ptagvar.'
<div class="panel-thumbnail">
		
<div style="position: relative; left: 0; top: 0;">
  '.$imgatt;
  		
if(($imgatt!='')&&($mmtype=="3"||$mmtype=="4"))
{
	$presult.='<i class="fa fa-play-circle-o fa-5x" style="position:absolute;top:36%;left:45%;cursor:pointer;color:white;"></i>';
}

 $presult.='
</div>
		

          </div>
          <div class="panel-body">
            <p class="lead pull-left">'.$subject.' <font size="1">'.$stext.'</font></p>
            <img src="'.$fprofpic.'" width="32" height="32" class="pull-right" data-toggle="popover" data-diatarget="#p_cnt_uid_'.$uobj->uid.'_a" data-diaplace="top" data-diatrigger="click">
            <div style="clear: both;"></div>
            <p class="ta-fdbx-body" data-threadid="'.$tid.'" data-msgid="'.$msgid.'">
            '.$contmsg.$atttext.'
            </p>
            <hr class="zero-top-margin">

            <div>

		            <p><em><a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_viewupvoters" data-rateid="'.$rateid.'"><span class="pd_upv_'.$tid.'">'.abs($upvotes).'</span> Upvotes</a>, '.abs($sharecount).' Shares, <span class="pd_dv_'.$tid.'">'.abs($downvotes).'</span> Downvotes, <span class="pd_views_'.$tid.'">'.$views.'</span> Views</em></p>

		            <p class="pull-left">
		            	<em>Posted by <a href="/users.php?uid='.$uobj->uid.'">'.$uobj->fname.'</a></em>
		            </p>

		            <div class="pull-right">
		            	<div class="dropdown pull-right comp-more-feed">
	                		<button class="btn btn-default" title="More" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
	                		<ul class="dropdown-menu">
						      <!--<li><a href="#"><i class="fa fa-area-chart"></i> View Activity</a></li>-->
						      <!--<li><a href="#"><i class="fa fa-clock-o"></i> Read Later</a></li>-->	
						      <li><a class="box-tog" data-mkey="tbx_getlink" data-toggle="modal" data-threadid="'.$tid.'"><i class="fa fa-link"></i> Get Link</a></li>
						      <!--<li><a href="#"><i class="fa fa-pencil-square-o"></i> Suggest Edits</a></li>-->
						      <!--<li><a href="#"><i class="fa fa-list"></i> Add to List</a></li>-->
						      <!--<li><a href="#"><i class="fa fa-bell-o"></i> Unfollow Post</a></li>-->
		            			';

if(($userobj->uid==$fuid)||($msgtype=="5"&&$socialobj->group_user_check_admin($gpid, $userobj->uid)))
{
	$presult.= '<li><a class="box-tog" data-mkey="box_audience" data-toggle="modal" data-autoset="1" data-toggle="modal" data-pelem="post_audience" data-elemid="'.$tid.'"><i class="fa fa-users"></i> Change Privacy</a></li>
			<li><a class="ajax-btn" data-mkey="tbx_postdel" data-threadid="'.$tid.'" data-suchide=".ta-fdbx[data-threadid='.$tid.']" data-prompt="1"><i class="fa fa-trash"></i> Delete Post</a></li>';
	
}
else
{
	$presult.= '<li><a href="#" class="box-tog" data-mkey="tbx_reportpost" data-toggle="modal" data-threadid="'.$tid.'"><i class="fa fa-flag"></i> Report Content</a></li>';
}

$presult.= '
						    </ul>
                		</div>

		            	<button class="btn btn-default comp-thumb-up ajax-btn" data-mkey="thread_upvote" data-threadid="'.$tid.'" data-msgid="'.$msgid.'" title="Thumbs Up if you like it"><i class="fa fa-thumbs-up"></i></button>
		            	<button class="btn btn-default comp-thumb-down ajax-btn" data-mkey="thread_downvote" data-threadid="'.$tid.'" data-msgid="'.$msgid.'" title="Thumbs Down if you don\'t like it"><i class="fa fa-thumbs-down"></i></button>
		            	<button class="btn btn-default box-tog" data-mkey="tbx_share" data-threadid="'.$tid.'" data-msgid="'.$msgid.'" title="Share"><i class="glyphicon glyphicon-share"></i></button>
		            </div>

		            <div style="clear: both;"></div>

		            <p class="pull-left">
		            	<em><a href="#">'.$msgobj->get_no_msg($comtid).' Comments</a></em>
		            </p>

		            <p class="pull-right">
		            	<em>'.$msgtime.'</em>
		            </p>

		            <div style="clear: both;"></div>

             </div>
             <div style="clear: both;"></div>
       
             <div class="widget-area no-padding blank">
								<div class="status-c-upload">
									<form>
										<div class="status-c-input" data-threadid="'.$tid.'" contenteditable="true" data-placeholder="What would you like to say?"></div>
										<ul>

											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Attach Documents" class="box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a></li>
											<!--<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Picture"><i class="fa fa-picture-o"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Link"><i class="fa fa-link"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Poll"><i class="fa fa-bar-chart"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Location"><i class="fa fa-map-marker"></i></a></li>-->
							
											<li><a title="Insert Smileys" data-placement="bottom" data-original-title="Add Smileys" class="box-tog" data-mkey="box_smileys" data-toggle="modal" data-eltarget="-1" data-intarget=".status-c-input[data-threadid='.$tid.']"><i class="fa fa-smile-o"></i></a></li>
										</ul>
										<button type="button" class="btn btn-success post-combtn pull-right" data-threadid="'.$tid.'" data-replyto=""><i class="fa fa-share"></i><span class="hidden-xs hidden-sm hidden-md"> Post Comment</span></button>
									</form>
								</div><!-- Status Upload  -->
						</div><!-- Widget Area -->


             <div style="clear: both;"></div>
       
       
              <!-- <form>
                <textarea placeholder="Add a comment" class="comp-commentbox"></textarea>
                <button type="button" class="btn btn-default"><i class="fa fa-paper-plane"></i><span class="hidden-xs hidden-sm hidden-md"> Post Comment</span></button>
          	  </form>-->

 			<br>
		     <ul class="list-group ta-fdbx-com" data-comtid="'.$comtid.'">
		     ';

$comstart=0;$comtot=1;
$presult.=$uiobj->load_comments($tid,$comtid,$comstart,$comtot);

$cstart=$comstart+$comtot;
$ctot=10;

$presult.= '</ul> <a class="fdbx-com-ldmore" data-threadid="'.$tid.'" data-ctid="'.$comtid.'" data-cstart="'.$cstart.'" data-ctot="'.$ctot.'" style="cursor:pointer;">View More Comments</a>

          </div>
</div>
		</div>
		';

if(!isset($_GET["tid"]))
{
	return $presult;
}
else
{
	echo $presult;
	echo '</div></div></div>';
	$themeobj->template_load_right();
	require MASTER_TEMPLATE.'/footer.php';
	$assetobj->load_js_product_login();
	echo
	'
<script type="text/javascript">
function pdisp_init_temp()
{
	var delay=1000;
	process_tarea($(".status-c-input")); 
}
document.addEventListener("DOMContentLoaded",pdisp_init_temp);
</script>
	';
}
?>