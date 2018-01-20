function JS_MEDIA(){};

JS_MEDIA.prototype.loadvideo=function(vidid,options,callback){
    
	if(options===undefined)
	{
		options={"controls": true, "autoplay": false, "preload": "auto","width":700,"height":360};
	}
	var myvidbox=videojs(vidid,options,callback);
	
	/*
	 * Space bar toggles play/pause.
Right and Left Arrow keys seek the video forwards and back.
Up and Down Arrow keys increase and decrease the volume.
M key toggles mute/unmute.
F key toggles fullscreen off and on. (Does not work in Internet Explorer, it seems to be a limitation where scripts cannot request fullscreen without a mouse click)
Double-clicking with the mouse toggles fullscreen off and on.
Number keys from 0-9 skip to a percentage of the video. 0 is 0% and 9 is 90%.
	 * 
	 */
	
	myvidbox.ready(function() {
		  this.hotkeys({
		    volumeStep: 0.1,
		    seekStep: 5,
		    enableMute: true,
		    enableFullscreen: true,
		    enableNumbers: true
		  });
		  
		  this.thumbnails({
			  0: {
			    src: 'https://i.ytimg.com/vi/dTqx1kPTslA/mqdefault.jpg'
			  },
			  5: {
			    src: 'https://i.ytimg.com/vi_webp/ILfBqOhAxCY/default.webp'
			  }
			});
		  
		  this.watermark({
			    file: '/master/securedir/m_images/logo_topbar.png',
			    xpos: 100,
			    ypos: 0,
			    xrepeat: 0,
			    opacity: 0.5,
			    className:"vjs-watermark",
			    clickable:true,
			    url:"/master"
			});
		
		  
		  /*var transcript=this.transcript({
		      showTitle: true,
		      showTrackSelector: true,
		    });
		  
		  $('#galbox_vidtranscript').html(transcript.el());*/
		  
		  /*this.zoomrotate({
			  rotate: 0,
			  zoom: 1.5
			  });*/
		  
		  
		  
/*		  var videos = [
		            {
		              src : [
		                'http://stream.flowplayer.org/bauhaus/624x260.webm',
		                'http://stream.flowplayer.org/bauhaus/624x260.mp4',
		                'http://stream.flowplayer.org/bauhaus/624x260.ogv'
		              ],
		              poster : '',
		              title : 'Whales'
		            },
		            {
		              src : [
		                'http://vjs.zencdn.net/v/oceans.mp4',
		                'http://vjs.zencdn.net/v/oceans.webm'
		              ],
		              poster : 'http://www.videojs.com/img/poster.jpg',
		              title : 'Ocean'
		            }
		          ];
		  
		  this.playList(videos);*/
		    
		    /*this.framebyframe({
		        fps: 23.98,
		        steps: [
		          { text: '-5', step: -5 },
		          { text: '-1', step: -1 },
		          { text: '+1', step: 1 },
		          { text: '+5', step: 5 },
		        ]
		      });*/
		    
		    this.seek({ 'seek_param': 'vidseek' });
		  
		});
	return myvidbox;
};

JS_MEDIA.prototype.video_autoplay=function(vidobj,delay){
	if(delay===undefined)
	{
		delay=1;
	}
	vidobj.autoplay(delay);
};

JS_MEDIA.prototype.video_controls=function(vidobj,boolval){
	vidobj.controls(boolval);
};

JS_MEDIA.prototype.video_controls=function(vidobj,boolval){
	vidobj.controls(boolval);
};

JS_MEDIA.prototype.video_getsrc=function(vidobj){
	vidobj.currentSrc();
};

JS_MEDIA.prototype.video_getsrc=function(vidobj){
	vidobj.currentSrc();
};

JS_MEDIA.prototype.video_time=function(vidobj,seconds){
	if(typeof seconds!==undefined)
	{
		vidobj.currentTime(seconds);
	}
	else
	{
		return vidobj.currentTime();
	}
};

JS_MEDIA.prototype.video_gettype=function(vidobj){
		return vidobj.currentType();
};

JS_MEDIA.prototype.video_setdimensions=function(vidobj,width,height){
	vidobj.dimensions(width,height);
};

JS_MEDIA.prototype.video_destroy=function(vidobj){
	vidobj.dispose();
};

JS_MEDIA.prototype.video_getduration=function(vidobj){
	return vidobj.duration();
};

JS_MEDIA.prototype.video_checkended=function(vidobj){
	return vidobj.ended();
};

JS_MEDIA.prototype.video_fullscreen_exit=function(vidobj){
	vidobj.myPlayer.exitFullscreen();
};

JS_MEDIA.prototype.video_hide=function(vidobj){
	vidobj.hide();
};

JS_MEDIA.prototype.video_getid=function(vidobj){
	return vidobj.id();
};

JS_MEDIA.prototype.video_isfullscreen=function(vidobj){
	vidobj.isFullscreen();
};

JS_MEDIA.prototype.video_setplayerlang=function(vidobj,langcode){
	vidobj.language(langcode);
};

JS_MEDIA.prototype.video_loop=function(vidobj,boolval){
	if(delay===undefined)
	vidobj.loop(false);
	else
	vidobj.loop(true);
};

JS_MEDIA.prototype.muted=function(vidobj,boolval){
	if(boolval===undefined)
	{
		vidobj.muted();
	}
	else
	{
		vidobj.muted(boolval);
	}
};

JS_MEDIA.prototype.video_pause=function(vidobj,delay){
	vidobj.pause();
};

JS_MEDIA.prototype.video_ispaused=function(vidobj){
	return vidobj.paused();
};

JS_MEDIA.prototype.video_play=function(vidobj){
	vidobj.play();
};

JS_MEDIA.prototype.video_obj_player=function(vidobj){
	return vidobj.player();
};

JS_MEDIA.prototype.video_poster=function(vidobj,poster){
	return vidobj.poster(poster);
};

JS_MEDIA.prototype.video_getremtime=function(vidobj){
	return vidobj.remainingTime();
};

JS_MEDIA.prototype.video_requestfullscreen=function(vidobj){
	return vidobj.requestFullscreen();
};

/**
 * 
 * [
  { type: "video/mp4", src: "http://www.example.com/path/to/video.mp4" },
  { type: "video/webm", src: "http://www.example.com/path/to/video.webm" },
  { type: "video/ogg", src: "http://www.example.com/path/to/video.ogv" }
]
 */
JS_MEDIA.prototype.video_src=function(vidobj,srcobj){
	return vidobj.src(srcobj);
};

JS_MEDIA.prototype.video_setvolume=function(vidobj,volume){
	return vidobj.volume(volume);
};

JS_MEDIA.prototype.video_getvolume=function(vidobj,volume){
	return vidobj.volume();
};

JS_MEDIA.prototype.audio_init=function(pselector,pancestor,playlist,selectorobj,swfpath){

	function temp_jplayer_func()
	{
			if(selectorobj===undefined)
			{
				selectorobj={
						info:$("#galbox_audio_notes_audioinfo"),
						notes:$("#galbox_audio_notes"),
						notes_meta:$("#galbox_audio_notes_audiometa"),
						notes_cover:$("#galbox_audio_notes_audiocover")
				};
			}
			
			if(swfpath===undefined)
			{
				swfpath="/master/securedir/m_js/libs/jPlayer-master/dist/jplayer";
			}
			
			if(playlist===undefined)
			{
				playlist=[];
			}
				
			var myPlayer =pselector;
			var jpAncestor =pancestor,
			myPlayerData,
			fixFlash_mp4, // Flag: The m4a and m4v Flash player gives some old currentTime values when changed.
			fixFlash_mp4_id, // Timeout ID used with fixFlash_mp4
			ignore_timeupdate, // Flag used with fixFlash_mp4
			myControl;
		
			// Instantiate the jPlayer playlist object, using the Soundcloud playlist array   
			window.jplaylist=new jPlayerPlaylist({
				jPlayer: myPlayer,
				cssSelectorAncestor: jpAncestor
			}, 
			playlist
			,
			{
				playlistOptions: {
					autoPlay: false,
					loopOnPrevious: true, // For restarting the playlist after the last track
					enableRemoveControls: true
				},
				ready: function (event) {
					selectorobj.info.css("display","none");
					// Hide the volume slider on mobile browsers. ie., They have no effect.
					if (event.jPlayer.status.noVolume) {
						// Add a class and then CSS rules deal with it.
						$(".jp-gui").addClass("jp-no-volume");
					}
					// Determine if Flash is being used and the mp4 media type is supplied. BTW, Supplying both mp3 and mp4 is pointless.
					fixFlash_mp4 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);
				},
				timeupdate: function (event) {
					if (!ignore_timeupdate) {
						$(jpAncestor + " .jp-progress-slider").slider("value", event.jPlayer.status.currentPercentAbsolute);
					}
				},
				volumechange: function (event) {
					if (event.jPlayer.options.muted) {
						$(jpAncestor + " .jp-volume-slider").slider("value", 0);
					} else {
						$(jpAncestor + " .jp-volume-slider").slider("value", event.jPlayer.options.volume);
					}
				},
				play:function(event){
					
					selectorobj.info.css("display","block");
					selectorobj.notes.css("display","block");
					var audio_title=myPlayer.find("audio:first").attr("title");
					var galbox_audiocover=$("#jp_audio_0").attr("data-audposter");
					
					if(audio_title!=""&&audio_title!=undefined&&audio_title!=null)
					{
						selectorobj.notes_meta.html('<div class="galbox_audio_notes_audionp">Now Playing :</div> <div class="galbox_audio_notes_np">'+audio_title+'</div>');
					}
					else
					{
						selectorobj.notes_meta.html("Track name not known");
					}
					
					if(galbox_audiocover!=""&&galbox_audiocover!=undefined&&galbox_audiocover!=null)
					{
						selectorobj.notes_cover.html('<img src="'+galbox_audiocover+'" width="125" height="125" class="galbox_audio_notes_cover">');
					}
					else
					{
						selectorobj.notes_cover.html('<img src="/master/securedir/m_images/image-not-found.png" width="125" height="125" class="galbox_audio_notes_cover">');
					}
					
					var artist=$(".jp-playlist-current").children(".jp-artist:first").html();
					if(artist!=""&&artist!=undefined&&artist!=null)
					{
						artist=artist.replace('by ','');
				    	selectorobj.notes_meta.append('<br><div class="galbox_audio_notes_audionp">Artist:</div> <div  class="galbox_audio_notes_np">'+artist+'</div>');
					}
					
				},
				loop:true,
				swfPath:swfpath,
				supplied:"mp3, m4a, oga",
				smoothPlayBar:true,
				keyEnabled:true
			});
			$('#jp-playlist-list').sortable({
				 update: function () {
					 window.jplaylist.scan();
			        },
			    placeholder: "ui-state-highlight"
			    
			});
			
			myPlayerData = myPlayer.data("jPlayer");
		
			$('.jp-gui ul li').hover(
				function () {
					$(this).addClass('ui-state-hover');
				},
				function () {
					$(this).removeClass('ui-state-hover');
				}
			);
			if(pselector.length>0)
			{
				myControl = {
						progress: $(myPlayerData.options.cssSelectorAncestor + " .jp-progress-slider"),
						volume: $(myPlayerData.options.cssSelectorAncestor + " .jp-volume-slider")
					};
				
			    // Create the progress slider control
			    myControl.progress.slider({
			        animate: "fast",
			        max: 100,
			        range: "min",
			        step: 0.1,
			        value: 0,
			        slide: function (event, ui) {
			            var sp = myPlayerData.status.seekPercent;
			            if (sp > 0) {
			                // Apply a fix to mp4 formats when the Flash is used.
			                if (fixFlash_mp4) {
			                    ignore_timeupdate = true;
			                    clearTimeout(fixFlash_mp4_id);
			                    fixFlash_mp4_id = setTimeout(function () {
			                        ignore_timeupdate = false;
			                    }, 1000);
			                }
			                // Move the play-head to the value and factor in the seek percent.
			                myPlayer.jPlayer("playHead", ui.value * (100 / sp));
			            } else {
			                // Create a timeout to reset this slider to zero.
			                setTimeout(function () {
			                    myControl.progress.slider("value", 0);
			                }, 0);
			            }
			        }
			    });
			    
			    myControl.volume.slider({
			        animate: "fast",
			        max: 1,
			        range: "min",
			        step: 0.01,
			        value: $.jPlayer.prototype.options.volume,
			        slide: function (event, ui) {
			            myPlayer.jPlayer("option", "muted", false);
			            myPlayer.jPlayer("option", "volume", ui.value);
			        }
			    });	   
			   
			    
			    galbox_audio_player_id=pselector.children("audio:first").attr("id");
			    
			    console.log(window.jplaylist.playlist);
			    
			    var mediaobj=new JS_MEDIA();
			    /*mediaobj.audio_add(window.jplaylist,{
			    	audid:555,
			    	title:"Cro Magnon Man",
			        artist:"The Stark Palace",
			        mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3",
			        oga:"http://www.jplayer.org/audio/ogg/TSP-01-Cro_magnon_man.ogg",
			        poster: "http://www.jplayer.org/audio/poster/The_Stark_Palace_640x360.png",
			        metadata:{at1:'bb',at2:'cc',at3:'dd'}
			    });*/
			}
			
			jPlayerPlaylist.prototype.scan = function() {
			    var self = this;
			    var isAdjusted = false;

			    var replace = [];
			    var maxName = 0; // maximum value that name attribute assumes.
			    $.each($(this.cssSelector.playlist + " ul li"), function(index, value) {
			        if ($(value).attr('name') > maxName)
			            maxName = parseInt($(value).attr('name'));
			    });

			    var diffCount = maxName + 1 != $(this.cssSelector.playlist + " ul li").length; // Flag that marks if the number of "ul li" elements doesn't match the name attribute counting.

			    $.each($(this.cssSelector.playlist + " ul li"), function(index, value) {
			        if (!diffCount) {
			            replace[index] = self.original[$(value).attr('name')];
			            if (!isAdjusted && self.current === parseInt($(value).attr('name'), 10)) {
			                self.current = index;
			                isAdjusted = true;
			            }
			        }
			        $(value).attr('name', index);
			    });

			    if (!diffCount) {
			        this.original = replace;
			        this._originalPlaylist();
			    }
			};
			
			return window.jplaylist;
		}
	
	var loadobj=new JS_LOADER();
	loadobj.jsload_jplayer(temp_jplayer_func);
};

JS_MEDIA.prototype.audio_add=function(jpobj,plobj){
	jpobj.add(plobj);
};

JS_MEDIA.prototype.audio_playnew=function(jpobj,newmedia){
	jpobj.add(newmedia);
	jpobj.play(-1);
};

/*VIDEO CONFERENCING*/

JS_MEDIA.prototype.videoconf=function(options_new,success_new,error_new){
	if(options_new===undefined){options_new={};}
	if(success_new===undefined){success_new={};}
	flashcam = document.getElementById('XwebcamXobjectX');
	if(error_new===undefined){error_new={};}
	var mediaobj=new JS_MEDIA();
	var options = {
			camlist:"convbx_videoconf_cams",
			status:"convbx_videoconf_status",
			filter_on:false,
			filter_id:0,
		    "audio": true,
		    "video": true,

		    // the element (by id) you wish to use for 
		    // displaying the stream from a camera
		    el: "convbx_webcam",

		    extern: null,
		    append: true,

		    // height and width of the output stream
		    // container

		    width: 320,
		    height: 240,

		    // the recommended mode to be used is 
		    // 'callback ' where a callback is executed 
		    // once data is available
		    mode: "callback",

		    // the flash fallback Url
		    swffile: "/master/securedir/m_js/libs/getUserMedia/dist/fallback/jscam_canvas_only.swf",

		    // quality of the fallback stream
		    quality: 85,

		    // a debugger callback is available if needed
		    debug: function (type, string) {},

		    onTick: function(remain) {

		        if (0 == remain) {
		            $("#"+options.status).text("Cheese!");
		        } else {
		            $("#"+options.status).text(remain + " seconds remaining...");
		        }
		    },
		    
		    
		    
		    // callback for capturing the fallback stream
		    onCapture: function () {
		        window.webcam.save();
		    },

		    // callback for saving the stream, useful for
		    // relaying data further.
		    onSave: function (data) {
		    	var col = data.split(";");
				var img = image;

				for(var i = 0; i < 320; i++) {
					var tmp = parseInt(col[i]);
					img.data[pos + 0] = (tmp >> 16) & 0xff;
					img.data[pos + 1] = (tmp >> 8) & 0xff;
					img.data[pos + 2] = tmp & 0xff;
					img.data[pos + 3] = 0xff;
					pos+= 4;
				}

				 var cnv = document.createElement('canvas');

				 cnv.width=options.width;
				 cnv.height=options.height;
				 cnv.style.width = options.width+"px";
				 cnv.style.height = options.height+"px";
				 cnv.style.position = "absolute";

				 console.log("CANVAS:");
				 console.log(cnv);
				 
				    var body = document.getElementsByTagName("body")[0];
				    var ctx = cnv.getContext("2d");
				
				if (pos >= 4 * 320 * 240) {
					ctx.putImageData(img, 0, 0);
					$.post("/master/securedir/m_process/process_streamupload.php", {type: "data", image: canvas.toDataURL("image/png")});
					pos = 0;
				}
		    },
		    onLoad: function () {
		    	var cams = options.getCameraList;
		        for(var i in cams) {
		            $("#"+options.camlist+" ul").append("<li>" + cams[i] + "</li>");
		        }
		    }
		};
	
	$.extend(true,options,options_new);
	
	var success=function (stream) {
        if (options.context === 'webrtc') {

            var video = options.videoEl;
            var vendorURL = window.URL || window.webkitURL;
            video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;

            video.onerror = function () {
                stream.stop();
                streamError();
            };

        } else {
            //flash context
        }
    };   
    
    $.extend(true,success,success_new);
    
    var error=function(error) {
        console.log('An error occurred: [CODE ' + error.code + ']');
    };
	$.extend(true,error,error_new);
	
	getUserMedia(options, success, error);
	
	var myvid=options.videoEl;
	var vidid="convvid_"+Math.random().toString(36).substr(2, 5);
	$(myvid).attr("id",vidid);
	
	window.vidconf[vidid]={
			options:options,
			success:success,
			error:error
		};
	
	// Initialize webcam options for fallback
	window.webcam = options;
	
	return vidid;
};

JS_MEDIA.prototype.createcanvas=function(canvasid,parentel,width,height)
{
	$("#"+parentel).append('<canvas id="'+canvasid+'" width="640" height="480"></canvas>');
    $("#"+canvasid).css({
        width: '150px',
        height: '120px'
    });
};

JS_MEDIA.prototype.videoconf_snapshot=function(parentid,vidid,snapcanvasid){
	var mediaobj=new JS_MEDIA();
	if(snapcanvasid===undefined)
	{
		do
		{
			snapcanvasid="cnv_"+Math.random().toString(36).substr(2, 5);
		}while($("#"+snapcanvasid).length!=0);
	}

	if (window.vidconf[vidid].options.context === 'webrtc') {
		//var video = document.getElementsByTagName('video')[0];
		var video=$("#"+vidid).get(0);
		mediaobj.createcanvas(snapcanvasid,parentid,window.vidconf[vidid].options.width,window.vidconf[vidid].options.height);
		
		var mycanvas=document.getElementById(snapcanvasid);
		mycanvas.getContext('2d').drawImage(video, 0, 0);

	// Otherwise, if the context is Flash, we ask the shim to
	// directly call window.webcam, where our shim is located
	// and ask it to capture for us.
	} else if(window.vidconf[vidid].options.context === 'flash'){
		window.webcam.capture();
		mediaobj.videoconf_changefilter(vidid);
	}
	else{
		alert('No context was supplied to getSnapshot()');
	}
};

JS_MEDIA.prototype.videoconf_changefilter=function(vidid){
	if (window.vidconf[vidid].options.filter_on) {
		window.vidconf[vidid].options.filter_id = (window.vidconf.options.filter_id + 1) & 7;
	}
};
