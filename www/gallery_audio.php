    <?php
	    require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	    $utilityobj=new ta_utilitymaster();
	    $userobj=new ta_userinfo();
	    $socialobj=new ta_socialoperations();
	    $uiobj=new ta_uifriend();
	    $galobj=new ta_galleryoperations();
	    $fileobj=new ta_fileoperations();
	    $logobj=new ta_logs();
	    $vuobj=new ta_userinfo();
	    
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
	    
	    $res=$galobj->gallery_get_user($vuid,"0");
	    $totgal=count($res);
	    $galid_init=$res[0][changesqlquote(tbl_galinfo::col_galid,"")];
    ?>
    <style type="text/css">
    .jp-gui
    {
    	width:100%;
    	max-width:670px;
    }
    </style>
    <div class="galbox_viewaud_rcontrol">
	    		<!-- <div class="dropdown controlbtns">
			    	<a href="#" class="btn btn-primary" id="galbox_audbtn_more" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      		<span class="glyphicon glyphicon-collapse-down"></span> More
			   		</a>
			   		
			   		<ul class="dropdown-menu bullet" aria-labelledby="galbox_audbtn_more">
						<li><a href="#1">Organize</a></li>
						<li><a href="#2">Manage Playlists</a></li>
						<li><a href="#3">Stats</a></li>
						<li><a href="#4">Browse Live Audio Streams</a></li>
				  	</ul>
			   	</div>-->
		    
    <?php 
   if($GLOBALS["iscr"])
   {
		    echo '<div class="dropdown controlbtns">
			    <a href="#" class="btn btn-primary" id="galbox_audbtn_add" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      <span class="glyphicon glyphicon-plus"></span> Add Stuff
			    </a>
			    
			    <ul class="dropdown-menu dropdown-menu-right bullet" aria-labelledby="galbox_audbtn_add">
			    
			    
			    		<li><a class="gbx-aud-upload box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid_init.'"> <span class="hidden-xs hidden-sm hidden-md">Upload Tracks</span></a></li>
					<!-- <li><a href="#2">Record your Voice</a></li>
					<li><a href="#3">Start a Live Audio Stream</a></li>-->
				</ul>
			</div>
		    ';
		    
   }
    
    ?>
    
</div>     
    
    <br>
      
   		<div id="jp_container_1">
        <div style="float:left;">
    <div id="jquery_jplayer_1" class="jp-jplayer" style="width:100%;"></div>
    <div class="jp-gui ui-widget ui-widget-content ui-corner-all">
        <ul>
            <li class="jp-play ui-state-default ui-corner-all"><a href="javascript:;" class="jp-play ui-icon ui-icon-play" tabindex="1"
                title="play">play</a>
            </li>
            <li class="jp-pause ui-state-default ui-corner-all"><a href="javascript:;" class="jp-pause ui-icon ui-icon-pause" tabindex="1"
                title="pause">pause</a>
            </li>
            <li class="jp-stop ui-state-default ui-corner-all"><a href="javascript:;" class="jp-stop ui-icon ui-icon-stop" tabindex="1"
                title="stop">stop</a>
            </li>
            <li class="jp-repeat ui-state-default ui-corner-all"><a href="javascript:;" class="jp-repeat ui-icon ui-icon-refresh" tabindex="1"
                title="repeat">repeat</a>
            </li>
            <li class="jp-repeat-off ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-repeat-off ui-icon ui-icon-refresh" tabindex="1"
                title="repeat off">repeat off</a>
            </li>
            <li class="jp-mute ui-state-default ui-corner-all"><a href="javascript:;" class="jp-mute ui-icon ui-icon-volume-off" tabindex="1"
                title="mute">mute</a>
            </li>
            <li class="jp-unmute ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-unmute ui-icon ui-icon-volume-off" tabindex="1"
                title="unmute">unmute</a>
            </li>
            <li class="jp-volume-max ui-state-default ui-corner-all"><a href="javascript:;" class="jp-volume-max ui-icon ui-icon-volume-on"
                tabindex="1" title="max volume">max volume</a>
            </li>
            <li class="jp-nextsong ui-state-default jp-next" style="float:right;"><a href="javascript:;" class="jp-nextsong ui-icon ui-icon-seek-next"
                tabindex="3" title="Next Track">Next</a>
            </li>
            <li class="jp-prevsong ui-state-default jp-previous" style="float:right;"><a href="javascript:;" class="jp-prevsong ui-icon ui-icon-seek-prev"
                tabindex="2" title="Previous Track">Prev</a>
            </li>
            <li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
            <li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle-off">shuffle-off</a></li>
        </ul>
        <div class="jp-progress-slider"></div>
        <div class="jp-volume-slider"></div>
        <div class="jp-current-time"></div>
        <div class="jp-duration"></div>
        <div class="jp-clearboth"></div>
    </div>
    
    <div id="galbox_audio_notes">
    	<div id="galbox_audio_notes_audioinfo">
	    	<div id="galbox_audio_notes_audiocover"></div>
	    	<div id="galbox_audio_notes_audiometa"></div>
	    	<div id="galbox_audio_notes_audiocontrol">
	    					<a class="btn btn-primary btn-sm galbox_notes_viewbtn" title='Click to see the Notes on this Track'>
						      <span class="glyphicon glyphicon-pencil"></span> <span class="hidden-xs hidden-sm hidden-md">View Notes</span>
						    </a>
	    	
	    	</div>
	    	
	    	<div style="clear: both;"></div>
    	</div>
    	
    	<div style="clear: both;"></div>
	<div id="galbox_audio_notes_cont">
		<div id="galbox_audio_notes_ctext">
		Notes
		</div>
		<div id="galbox_audio_notes_cbtn">
			<a class="btn btn-primary btn-sm galbox_notes_backbtn" title='Click to see the Notes on this Audio'>
			     <span class="glyphicon glyphicon-arrow-left"></span> <span class="hidden-xs hidden-sm hidden-md">Back to Track Info</span>
			</a>
			
		</div>
	</div>
	</div>
    
    </div>
    
    <div class="jp-playlist" style="float:left;">
      <ul id="jp-playlist-list">
        <!-- The method Playlist.displayPlaylist() uses this unordered list -->
        <li></li>
      </ul>
      	<!-- <a class="btn btn-primary btn-sm galbox_notes_newplaylist box-tog" data-mkey="box_gal_new" data-toggle="modal" data-eltarget="-1" title='Create a New Playlist'>
	     	<i class="fa fa-plus"></i> New Playlist
      </a>
      	<a href="#" class="btn btn-primary btn-sm" title='Click to see the Save these Tracks as a Playlist'>
			<i class="fa fa-floppy-o"></i> Save Playlist
		</a>
		<a href="#" class="btn btn-primary btn-sm" title='Click to Open a Specific Playlist'>
			<i class="fa fa-folder-open"></i> Open Playlist
		</a>
		<br>
		<a class="btn btn-primary btn-sm galbox_notes_shareplaylist" title='Click to Share this Playtlist with your circle'>
	     	<span class="glyphicon glyphicon-share-alt"></span> Share Playlist
		</a>-->
		<a class="btn btn-primary btn-sm galbox_notes_clearqueue" title='Click to Remove all Tracks from this Queue'>
	     	<i class="fa fa-minus-circle"></i><span class="hidden-xs hidden-sm hidden-md"> Clear Queue</span>
		</a>
    </div>

    <div class="jp-no-solution">
    	<span>Update Required</span> To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
    </div>
    
</div>

<div style="clear:both;"></div>
<div style="clear:both;"></div>

<div id="galbox_audioguidetab">
    <ul>
    <li><a href="#ga-tabs-own">Your Tracks &amp; Albums</a></li>
  	<!-- <li><a href="#ga-tabs-genre">Genre</a></li>
    <li><a href="#ga-tabs-artist">Artist</a></li>
    <li><a href="#ga-tabs-album">Album</a></li>
    <li><a href="#ga-tabs-playlist">Playlist</a></li>
    <li><a href="#ga-tabs-time">Time</a></li>
    <li><a href="#ga-tabs-rating">Rating</a></li>
    <li><a href="#ga-tabs-duration">Duration</a></li>
    <li><a href="#ga-tabs-custom">Custom Filters</a></li>-->
  </ul>
  		<?php require_once 'gallery_audio_own.php';
		/* require_once 'gallery_audio_genre.php';
		 require_once 'gallery_audio_artist.php';
		 require_once 'gallery_audio_album.php';
		 require_once 'gallery_audio_playlist.php';
		 require_once 'gallery_audio_time.php';
		 require_once 'gallery_audio_rating.php';
		 require_once 'gallery_audio_duration.php';
		 require_once 'gallery_audio_custom.php';*/
		 ?>
 	</div>
 	
<script type="text/javascript">
$("#galbox_audioguidetab").tabs({
	activate: function( event, ui ) {
		var str=String(ui.newTab.context);
			/*if(str.search("gv-tabs-stats")!=-1)
			{
			}*/
		},
		active:0,
    hide: {
        effect: "slide",
        duration: 500
    },
    show:{
		effect: "slide",
        duration: 500
	}
});


var utilityobj=new JS_UTILITY();
utilityobj.multiselect($('.gbx-playlist-sel'),{
	buttonWidth: '150px',
	enableCaseInsensitiveFiltering: true,
	filterPlaceholder: 'Search',
	enableHTML:true,
	disableIfEmpty: true,
    disabledText: 'None Selected'
});

listenevent($(".gbx-playlist-sel"),"change",function(e){
	var galid=$(this).val();
	var predata=$(this).data();
	predata.galid=galid;
	ajax_sender(e,predata,function(){
		$(".gbx-aud-upload").attr("data-galid",galid);
		rebind_all();
	});
});

var playlist =[];
var pobj={};
<?php 
	$resjs='';
	$res=$galobj->get_children_media($galid_init);
	for($i=0;$i<count($res);$i++)
	{
		$mediaid=$res[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
		$mediaurl=$res[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
		$mediatype=$res[$i][changesqlquote(tbl_galdb::col_mediatype,"")];
		$jsonid=$res[$i][changesqlquote(tbl_galdb::col_jsonid,"")];
		$uid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
		$mediatitle=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
		
		if($mediatype!="4")continue;
		
		$jsonobj=$utilityobj->jsondata_get($jsonid);
		$fext=$fileobj->fileinfo($mediaurl,"3");
		
		$resjs.='pobj={
					audid:"'.$mediaid.'",
					title:"'.$mediatitle.'"
				};';
		if(isset($jsonobj->formats))
		{
			$galid_audproc=$galobj->get_galid_special($uid,"11");
			foreach ($jsonobj->formats as $key=>$value)
			{
				$format=$key;
				$medid=$value;
				$medurl=$galobj->geturl_media($galid_audproc,$medid,"3");
				$fext=$fileobj->fileinfo($medurl,"3");
				$resjs.='pobj.'.$fext.'="'.$utilityobj->pathtourl($medurl).'";';
			}
		}
		else
		{
			$resjs.='pobj.'.$fext.'="'.$utilityobj->pathtourl($mediaurl).'";';
		}
		
		if(isset($jsonobj->artist))
		{
			$resjs.='pobj.artist="'.$jsonobj->artist.'";';
		}
		
		if(isset($jsonobj->poster))
		{
			$resjs.='pobj.poster="'.$jsonobj->poster.'";';
		}
		
		if(isset($jsonobj->metadata))
		{
			$resjs.='pobj.metadata="'.$utilityobj->object_to_json($jsonobj->metadata).'";';
		}
		
		$resjs.='playlist.push(pobj);';
	}
	echo $resjs;
?>

/*
 * 
 audid:555,
	title:"Cro Magnon Man",
 artist:"The Stark Palace",
 mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",
 oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",
 poster: "http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png",
 metadata:{at1:'bb',at2:'cc',at3:'dd'}
 */

/*var playlist =
	[{
		audid:456,
		title:"Tempered Song",
		mp3:"http://www.jplayer.org/audio/mp3/Miaow-01-Tempered-song.mp3",
		oga:"http://www.jplayer.org/audio/mp3/Miaow-01-Tempered-song.ogg",
	},
	{
		audid:987,
		title:"Your Face",
		mp3:"http://www.jplayer.org/audio/mp3/TSP-05-Your_face.mp3",
		oga:"http://www.jplayer.org/audio/ogg/TSP-05-Your_face.ogg"
	}];*/

var mediaobj=new JS_MEDIA();
mediaobj.audio_init($("#jquery_jplayer_1"), "#jp_container_1",playlist);

listenevent($(".ga_play_audio"),"click",function(){
	var flag=0;
	if($(this).parents(".ga_album_cont").length!=0)
	{
		var elem=$(this).parents(".ga_album_cont:first").attr("data-target");
		var plcount=0;
		var initelem=window.jplaylist.playlist.length;
		$(elem).children(".panel").each(function(){
			var par=$(this);
			var audsrc_mp3=par.attr("data-audsrc-mp3");
			var audsrc_oga=par.attr("data-audsrc-oga");
			var audid=par.attr("data-audid");
			var audtitle=par.attr("data-audtitle");
			var audartist=par.attr("data-audartist");
			var audposter=par.attr("data-audposter");
			var audmetadata=par.attr("data-audmetadata");
			$("#jp_audio_0").attr("data-audposter",audposter);
			var metaobject ={};
			if(audmetadata!="")metaobject = JSON.parse(audmetadata);
			
			var aud_options={audid:audid,title:audtitle,artist:audartist,mp3:audsrc_mp3,oga:audsrc_oga,poster:audposter};
			var audoptions = $.extend({}, aud_options,metaobject);
			
			plcount--;
			
			flag=0;
			for(i=0;i<window.jplaylist.playlist.length;i++)
			{
				if(window.jplaylist.playlist[i].audid==audid)
				{
					flag=1;
					break;
				}
			}
			if(flag!=1)
			{
				mediaobj.audio_add(window.jplaylist,audoptions);
			}
			
		});
		
		window.jplaylist.play(initelem);
		
	}
	else
	{
		var par=$(this).parents(".panel:first");
		var audsrc_mp3=par.attr("data-audsrc-mp3");
		var audsrc_oga=par.attr("data-audsrc-oga");
		var audid=par.attr("data-audid");
		var audtitle=par.attr("data-audtitle");
		var audartist=par.attr("data-audartist");
		var audposter=par.attr("data-audposter");
		var audmetadata=par.attr("data-audmetadata");
		var metaobject ={};
		if(audmetadata!="")metaobject = JSON.parse(audmetadata);
		
		var aud_options={audid:audid,title:audtitle,artist:audartist,mp3:audsrc_mp3,oga:audsrc_oga,poster:audposter};
		var audoptions = $.extend({}, aud_options,metaobject);
		
		flag=0;
		for(i=0;i<window.jplaylist.playlist.length;i++)
		{
			if(window.jplaylist.playlist[i].audid==audid)
			{
				window.jplaylist.play(i);
				flag=1;
				break;
			}
		}
		if(flag!=1)
		{
			mediaobj.audio_add(window.jplaylist,audoptions);
			window.jplaylist.play(-1);
		}
	}
	
});

document.addEventListener( "DOMContentLoaded", function() {
	var utilityobj=new JS_UTILITY();
	var popcorn = utilityobj.popcornsubs("#"+galbox_audio_player_id ,{
      start: 2,
      end: 5,
      target: "galbox_audio_notes_ctext",
      text: "Pop!"
    })
	
  }, false );


$.fn.v2p = function( props ) 
{
    return this.css( parseProps( props ) );
};

utilityobj.checkbox($('input[type="checkbox"]'), {
	checkedClass: 'glyphicon glyphicon-ok'
});
</script>