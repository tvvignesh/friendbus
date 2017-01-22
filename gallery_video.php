<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$vuobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$fileobj=new ta_fileoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/dash_gallery.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
}

if(isset($_GET["uid"]))
{
	$vuid=$_GET["uid"];
	if($vuid==$userobj->uid)
	{
		$vuid=$userobj->uid;
		$GLOBALS["iscr"]=true;
	}
	else
	{
		$GLOBALS["iscr"]=false;
	}
}
else
{
	if(isset($GLOBALS["vuid"]))
	{
		$vuid=$GLOBALS["vuid"];
		if($userobj->uid==$vuid)
		{
			$GLOBALS["iscr"]=true;
		}
		else
		{
			$GLOBALS["iscr"]=false;
		}
	}
	else
	{
		$GLOBALS["iscr"]=true;
		$vuid=$userobj->uid;
	}
}
$vuobj->user_initialize_data($vuid);

$res=$galobj->gallery_get_user($vuid);
$totgal=count($res);

$galid_init=$res[0][changesqlquote(tbl_galinfo::col_galid,"")];
$res1=$galobj->gallery_getmedia($galid_init,"3");

$mediaid_init=$mediaurl=$mediathumb=$mediatitle=$mediadesc='';
if(count($res1)!=0)
{
	$mediaid_init=$res1[0][changesqlquote(tbl_galdb::col_mediaid,"")];
	$mediaurl=$res1[0][changesqlquote(tbl_galdb::col_mediaurl,"")];
	$mediathumb=$res1[0][changesqlquote(tbl_galdb::col_mediathumb,"")];
	$mediatitle=$res1[0][changesqlquote(tbl_galdb::col_mediatitle,"")];
	$mediadesc=$res1[0][changesqlquote(tbl_galdb::col_mediadesc,"")];
	$jsonid=$res1[0][changesqlquote(tbl_galdb::col_jsonid,"")];
	$jsonobj=$utilityobj->jsondata_get($jsonid);
}
?>

<div id="galbox_vidcontainer" style="display:none;" align="center" data-galid="<?php echo $galid_init;?>" data-mediaid="<?php echo $mediaid_init;?>">
		
		<video controls="controls" poster="<?php echo $utilityobj->pathtourl($mediathumb);?>" class="img img-responsive video-js vjs-default-skin vjs-big-play-centered" preload="auto" id="galbox_videlement" height="360">
		
		<?php 
		
		if(isset($jsonobj->formats))
		{
			$galid_vidpro=$galobj->get_galid_special($vuid,"9");
			foreach($jsonobj->formats as $key => $value)
			{		
				$medid=$value;
				$medurl=$galobj->geturl_media($galid_vidpro,$medid,"3");
				$medext=$fileobj->fileinfo($medurl,"3");
				echo '<source src="'.$utilityobj->pathtourl($medurl).'" type="'.$fileobj->contenttype($medext).'" />';
			}
		}
		
		?>
      
			<track label="English" kind="captions" srclang="en" src="/tests/bigbuckbunny.vtt" default>
		   	<track label="Deutsch" kind="captions" srclang="de" src="captions/vtt/sintel-de.vtt">
		   	<track label="Espanol" kind="captions" srclang="es" src="captions/vtt/sintel-es.vtt">
			<!-- <object type="application/x-shockwave-flash" data="/master/securedir/flashfox.swf" width="640" height="360">
				<param name="movie" value="//securedir/flashfox.swf" />
				<param name="allowFullScreen" value="true" />
				<param name="wmode" value="transparent" />
				<param name="flashVars" value="controls=true&amp;poster=<?php echo $utilityobj->pathtourl($mediathumb);?>&amp;src=<?php echo $utilityobj->pathtourl($mediaurl);?>" />
				<img alt="<?php echo $mediatitle;?>" src="<?php echo $utilityobj->pathtourl($mediathumb);?>" width="640" height="360" title="No video playback capabilities, please download the video below" />
			</object>-->
	</video>
	
<p>
	<strong>Download video:</strong> <a href="<?php echo $utilityobj->pathtourl($mediaurl);?>">Download</a>
</p>	

</div>
		
<div style="clear:both;"></div> 
<div id="galbox_vidguidetab">
  <ul>
  	<li><a href="gallery_video_suggest.php?uid=<?php echo $vuid;?>"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-play-circle-o fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-play-circle-o"></i></span></a></li>
    <!-- <li><a href="gallery_video_comment.php?uid=<?php echo $vuid;?>"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-comments-o fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-comments-o"></i></span></a></li>-->
    <li><a href="gallery_video_info.php?uid=<?php echo $vuid;?>"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-info-circle fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-info-circle"></i></span></a></li>
    <!-- <li><a href="gallery_video_stats.php"><i class="fa fa-bar-chart fa-2x"></i></a></li>-->
    <!-- <li><a href="gallery_video_share.php"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-share-alt fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-share-alt"></i></span></a></li>-->
    <li><a href="gallery_video_download.php?uid=<?php echo $vuid;?>"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-download fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-download"></i></span></a></li>
    <!-- <li><a href="gallery_video_lists.php?uid=<?php echo $vuid;?>"><span class="hidden-xs hidden-sm hidden-md"><i class="fa fa-list-alt fa-2x"></i></span><span class="hidden-lg"><i class="fa fa-list-alt"></i></span></a></li>-->
  </ul>
</div>

		
<br><br>		
		
		
			<div style="clear:both;"></div>
		
<script type="text/javascript">
function load_elem_galvideo()
{
	var videobj=new JS_MEDIA();
	if(typeof window.vidobj_gbx!=='undefined')
	{
		videobj.video_destroy(window.vidobj_gbx);
	}
	window.vidobj_gbx=videobj.loadvideo("galbox_videlement",{controlBar: {
	    muteToggle: false
	  },"playbackRates": [0.5, 1, 1.5, 2],"controls": true, "autoplay": false, "preload": "auto","width":700,"height":360,plugins:{}},function(){
		  window.vidobj_gbx.addChild('BigPlayButton');
		 });
	
	window.vidobj_i=window.vidobj_gbx;
			
	var srcarr=[];
listenevent_future(".vidsug_play ,.thumb a .play,.thumb,.overlay",$("body"),"click",function(){
		
		$("#galbox_vidcontainer").show();
		var vidsrc=$(this).parents(".thumbnail:first").attr("data-vidsrc");
		var vidposter=$(this).parents(".thumbnail:first").attr("data-vidposter");
		var galid=$(this).parents(".thumbnail:first").attr("data-galid");
		var mediaid=$(this).parents(".thumbnail:first").attr("data-mediaid");
		var tracksrc=$(this).parents(".thumbnail:first").attr("data-tracksrc");

		/*var oldTracks = window.vidobj_gbx.remoteTextTracks();
		var i = oldTracks.length;
		while (i--) 
		{
			window.vidobj_gbx.removeRemoteTextTrack(oldTracks[i]);
		}
			window.vidobj_gbx.addRemoteTextTrack({ kind: 'captions', label: 'English', language: 'en', srclang: 'en', src: '/tests/subtitle-english-golden.vtt' });
			window.vidobj_gbx.addRemoteTextTrack({ kind: 'captions', label: 'English 1', language: 'en', srclang: 'en', src: '/tests/bigbuckbunny.vtt' });*/


		$("#galbox_vidcontainer").attr("data-galid",galid);
		$("#galbox_vidcontainer").attr("data-mediaid",mediaid);
		
		myObject = JSON.parse(vidsrc);
		myposterObject = JSON.parse(vidposter);
		for (var key in myObject) 
		{
			srcarr.push({type:key,src:myObject[key],label:'SD'});
		}
		
		videobj.video_src(window.vidobj_gbx,srcarr);
		videobj.video_poster(window.vidobj_gbx, myposterObject["normal"]);
		videobj.video_autoplay(window.vidobj_gbx,3000);

		console.log(window.vidobj_gbx.textTracks());
		
		scrolltodiv($("#galbox_videlement"));
		srcarr=[];
	});

}
	    	
$("#galbox_vidguidetab").tabs({
	active:0,
	cache: false,
    hide: {
        effect: "slide",
        duration: 500
    },
    show:{
		effect: "slide",
        duration: 500
	}
});

	var loadobj=new JS_LOADER();
	loadobj.jsload_vidlibs(load_elem_galvideo);

</script>