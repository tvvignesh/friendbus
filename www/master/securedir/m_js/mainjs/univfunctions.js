function auto_add_compics()
{
	var selected_divs_array=$(".pluginitem_pastcommentsbox_comcont[cpicstat='2']:first");
	if(selected_divs_array.length==0)
	{
		return;
	}
	var selected_divs=selected_divs_array;

	var uid=selected_divs.attr("posterid");
	var comid=selected_divs.attr("comid");

	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	loadobj.ajaxloadpage(host_process+"/process_profpic.php",function(results){
		var jsonobj=convobj.xmltojson(results);
		var statuscode=jsonobj["document"]["statuscode"]["#text"];
		if(statuscode=="1")
		{
			pic_normal=jsonobj["document"]["pic_collection"]["pic_normal"]["#text"];
			pic_small=jsonobj["document"]["pic_collection"]["pic_small"]["#text"];
			pic_vsmall=jsonobj["document"]["pic_collection"]["pic_vsmall"]["#text"];
		}
		else
		{
			alert("error");return;
		}

		$(".pluginitem_pastcommentsbox_comcont[cpicstat='2'] > div.pluginitem_pastcommentsbox_comcont_poster > img.pluginitem_pastcommentsbox_posterpicimg").attr("src",pic_vsmall);
		$(".pluginitem_pastcommentsbox_comcont[comid='"+comid+"']").attr("cpicstat","1");

		auto_add_compics();
	}, function(error){
		console.log(error);
		alert("error");
	},"xml","2",{uid:uid},false);
}

function plugin_statusmsg_send(statusmsg,rateid,mood)
{
	if(typeof mood==="undefined")
	{
		mood="0";//info message
	}
	var icontext;
	if(mood=="0")
	{
		icontext="ui-icon-info";//info
	}
	else
	if(mood=="1")
	{
		icontext="ui-icon-check";//success
	}
	else
	if(mood=="2")
	{
		icontext="ui-icon-alert";//alert
	}
	else
	if(mood=="3")
	{
		icontext="ui-icon-close";//fatal error
	}

	statusmsg='<span class="ui-icon '+icontext+'" style="float: left; margin: 0 7px 20px 0;"></span><span style="float:left;">'+statusmsg+"</span>";
	var statusbox=$( ".pluginitem_statusbox[rid='"+rateid+"']" );
	statusbox.html(statusmsg);
	statusbox.show("slide", 1000);
}

function plugin_statusmsg_hide(rateid)
{
	var statusbox=$( ".pluginitem_statusbox[rid='"+rateid+"']" );
	statusbox.html("");
	statusbox.hide("slide", 1000);
}

function plugin_refresh(rateid)
{
	plugin_refresh_rating(rateid);
	plugin_refresh_comments(rateid);
}

function convertCanvasToImage(canvas) 
{
	var image = new Image();
	image.src = canvas.toDataURL("image/png");
	return image.src;
}

function plugin_refresh_stop(rateid)
{
	plugin_refresh_rating_stop(rateid);
	plugin_refresh_comments_stop(rateid);
}

function plugin_refresh_rating(rateid)
{
	if(($(".pluginbox[rid='"+rateid+"']").css("display")=="none"))
	{
		plugin_refresh_rating_stop(rateid);
		return;
	}
	var plugin_rating=$(".pluginitem_currating[rid='"+rateid+"']");

	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	loadobj.ajaxloadpage(host_process+"/process_rating_get.php",function(results){
		var jsonobj=convobj.xmltojson(results);
		var statuscode=jsonobj["document"]["statuscode"]["#text"];
		console.log(statuscode);
		if(statuscode=="1")
		{
			var rating=jsonobj["document"]["rating"]["#text"];
			plugin_rating.html(rating);
		}

		if(typeof window.tapluginrefresh_rating[rateid]!=="undefined")
		{
			clearTimeout(window.tapluginrefresh_rating[rateid]);
			window.tapluginrefresh_rating[rateid]=setTimeout(function() {plugin_refresh_rating(rateid);},window.timer_plugin_rating_fromserver);
		}
		else
		{
			window.tapluginrefresh_rating[rateid]=setTimeout(function() {plugin_refresh_rating(rateid);},window.timer_plugin_rating_fromserver);
		}

	},function(error){
		alert("error1");
		console.log(error);
	},"xml","2",{rateid:rateid});
}

function plugin_refresh_rating_stop(rateid)
{
	if(typeof window.tapluginrefresh_rating[rateid]!=="undefined")
	{
		clearTimeout(window.tapluginrefresh_rating[rateid]);
		return;
	}
}

function plugin_refresh_comments_stop(rateid)
{
	if(typeof window.tapluginrefresh_comments[rateid]!=="undefined")
	{
		clearTimeout(window.tapluginrefresh_comments[rateid]);
		return;
	}
}

function plugin_refresh_comments(rateid)
{
	console.log("COMREF");
	if(($(".pluginbox[rid='"+rateid+"']").css("display")=="none")||($(".pluginitem_pastcommentsbox_comcont[rid='"+rateid+"']").css("display")=="none"))
	{
		plugin_refresh_comments_stop(rateid);
		return;
	}

	var plugin_comstart=$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").attr("limit_start");
	var plugin_cominc=$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").attr("limit_inc");
	var plugin_comtot=$(".pluginitem_pastcommentsbox[rid='"+rateid+"']").attr("totcomments");

	plugin_cominc=parseInt(plugin_cominc);
	plugin_comstart=parseInt(plugin_comstart);

	var plugin_strt,plugin_incr;
	plugin_strt=0-plugin_comtot;
	plugin_incr=plugin_comtot;

	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	console.log ("RATEID:"+rateid);
	console.log ("PSTRT:"+plugin_strt);
	console.log("PCOMTOT"+plugin_comtot);
	//{rateid:rateid,start:plugin_strt,increment:plugin_comtot}


	loadobj.ajaxloadpage(host_process+"/process_comments_item_display.php",function(results1){
		var jsonobj1=convobj.xmltojson(results1);

		if(typeof jsonobj1["document"]["comment_body_enclose"]["comment_body"]==="undefined")
		{
//			alert("There are no more comments to show!");
//			plugin_statusmsg_send("There are no more comments to show!",rateid,"2");
		}
		else
		{
			var commentbody=jsonobj1["document"]["comment_body_enclose"]["comment_body"];
			rateid=jsonobj1["document"]["comment_body_enclose"]["@attributes"]["rid"];

			var this_comment,this_comment_html;
			this_comment_html='';

			if($.isArray(commentbody))
			{
				for(var i=0;i<commentbody.length;i++)
				{
					this_comment=commentbody[i];
					this_comment_comid=this_comment["comid"]["#text"];
					this_comment_comcont=this_comment["com_cont"]["#text"];
					this_comment_poster=this_comment["com_poster"]["#text"];
					this_comment_posttime=this_comment["com_posttime"]["#text"];
					this_comment_replyto=this_comment["com_replyto"]["#text"];
					this_comment_tagid=this_comment["com_tagid"]["#text"];
					this_comment_com_attachid=this_comment["com_attachid"]["#text"];
					this_comment_com_flag=this_comment["com_flag"]["#text"];

					this_comment_html+='<div class="pluginitem_pastcommentsbox_comcont" comid="'+this_comment_comid+'" posterid="'+this_comment_poster+'" cpicstat="2" rid="'+rateid+'"><div class="pluginitem_pastcommentsbox_comcont_poster"><img src="" width="50" height="50" class="pluginitem_pastcommentsbox_posterpicimg"></div><div class="pluginitem_pastcommentsbox_comcont_content">'+this_comment_comcont+'</div><div class="clear_float"></div></div>';
				}
			}
			else
			{
				this_comment=commentbody;
				this_comment_comid=this_comment["comid"]["#text"];
				this_comment_comcont=this_comment["com_cont"]["#text"];
				this_comment_poster=this_comment["com_poster"]["#text"];
				this_comment_posttime=this_comment["com_posttime"]["#text"];
				this_comment_replyto=this_comment["com_replyto"]["#text"];
				this_comment_tagid=this_comment["com_tagid"]["#text"];
				this_comment_com_attachid=this_comment["com_attachid"]["#text"];
				this_comment_com_flag=this_comment["com_flag"]["#text"];

				this_comment_html+='<div class="pluginitem_pastcommentsbox_comcont" comid="'+this_comment_comid+'" posterid="'+this_comment_poster+'" cpicstat="2" rid="'+rateid+'"><div class="pluginitem_pastcommentsbox_comcont_poster"><img src="" width="50" height="50" class="pluginitem_pastcommentsbox_posterpicimg"></div><div class="pluginitem_pastcommentsbox_comcont_content">'+this_comment_comcont+'</div><div class="clear_float"></div></div>';
			}

			//commentbox_append_comments(this_comment_html,rateid,plugin_cominc);

			this_comment_html+='<div class="pluginitem_pastcommentsbox_morecomments" limit_start="'+plugin_strt+'" limit_inc="'+plugin_incr+'" rid="'+rateid+'" title="Click to Load more Comments to this item">View More Comments</div><div class="pluginitem_pastcommentsbox_togglecomments" rid="'+rateid+'" title="Click to Hide all Comments">Hide all Comments</div><div class="clear_float"></div>';
			$(".pluginitem_pastcommentsbox[rid='"+rateid+"']").html(this_comment_html);
			auto_add_compics();
			listenevent($(".pluginitem_pastcommentsbox_togglecomments"),"click",pluginfunc_togglecomments);
			listenevent($(".pluginitem_pastcommentsbox_morecomments"),"click",pluginfunc_morecomments);
		}

		if(typeof window.tapluginrefresh_comments[rateid]!=="undefined")
		{
			clearTimeout(window.tapluginrefresh_comments[rateid]);
			window.tapluginrefresh_comments[rateid]=setTimeout(function() {plugin_refresh_comments(rateid);},window.timer_plugin_comments_fromserver);
		}
		else
		{
			window.tapluginrefresh_comments[rateid]=setTimeout(function() {plugin_refresh_comments(rateid);},window.timer_plugin_comments_fromserver);
		}
	},function(error){
		console.log(error);
		alert("error");
	},"xml","2",{rateid:rateid,start:plugin_strt,increment:plugin_comtot});
}

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}

function placeCaretAtEnd(el) 
{
    el.focus();
    if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}

function galbox_showpicdet(e)
{
	var target = $(e.target);
	  if (target.is("li")||target.is("a")) 
	  {
		  return;
	  }
	var imgobj=new JS_IMAGE();
	imgobj.lightbox_index();
	var galbox_pic_picindex=$(this).attr("data-galindex");
	$(".galbox_picshow_info_icon:first").attr("mediaid",$(this).attr("data-mediaid"));
	$(".galbox_picshow_info_icon:first").attr("galid",$(this).attr("data-galid"));
	
	galbox_open_pic(galbox_pic_picindex);
}

function galbox_showviddet(e)
{
	var galcont=$(this).parents(".galbox_viewvid_cont:first");
	var galop=galcont.find(".galbox_viewvid_contvidoperations:first");
	var galelem=galcont.find(".galbox_viewvid_contimgdet:first");
	var galthumb=galcont.find(".galbox_viewvid_contimg:first");
	scrolltodiv(galcont);
		galcont.animate({width:'100%',height:'100%'},function(){
		galelem.css("display","block");
		galop.css("display","block");
		galthumb.css("opacity","1");
		
		listenevent($(".galbox_vidwatch"),"click",function(){
			var gindex=$(this).parents(".galbox_viewvid_cont:first").find(".galbox_viewvid_contimg:first").attr("data-galindex");
			galbox_slideshow_start_vid(gindex);
		});
		
		listenevent($(".galbox_viewvid_contimgdet_toggle"),"click",function(){
			restore_elemprop($(this).parents(".galbox_viewvid_cont:first"));
			$(this).parents(".galbox_viewvid_cont:first").find(".galbox_viewvid_contimg:first").css("opacity","");
			$(this).parents(".galbox_viewvid_cont:first").find(".galbox_viewvid_contimgdet:first").css("display","none");
			$(this).parents(".galbox_viewvid_cont:first").find(".galbox_viewvid_contvidoperations:first").css("display","none");
			scrolltodiv($(this).parents(".galbox_viewpic_cont:first"));
		});
	});
}

function galbox_showdocdet(e)
{
	var galcont=$(this).parents(".galbox_viewdoc_cont:first");
	var galop=galcont.find(".galbox_viewdoc_contdocoperations:first");
	var galelem=galcont.find(".galbox_viewdoc_contimgdet:first");
	var galthumb=galcont.find(".galbox_viewdoc_contimg:first");
	scrolltodiv(galcont);
		galcont.animate({width:'100%',height:'100%'},function(){
		galelem.css("display","block");
		galop.css("display","block");
		galthumb.css("opacity","1");
		
		listenevent($(".galbox_viewdoc_contimgdet_toggle"),"click",function(){
			restore_elemprop($(this).parents(".galbox_viewdoc_cont:first"));
			$(this).parents(".galbox_viewdoc_cont:first").find(".galbox_viewdoc_contimg:first").css("opacity","");
			$(this).parents(".galbox_viewdoc_cont:first").find(".galbox_viewdoc_contimgdet:first").css("display","none");
			$(this).parents(".galbox_viewdoc_cont:first").find(".galbox_viewdoc_contdocoperations:first").css("display","none");
			scrolltodiv($(this).parents(".galbox_viewdoc_cont:first"));
		});
	});
}

function store_elemprop(selector)
{
	var elemwidth,elemheight;
	selector.each(function() {
		elemwidth=$(this).width()+8;
		elemheight=$(this).height()+12;
		$(this).attr("data-elemwidth",elemwidth);
		$(this).attr("data-elemheight",elemheight);
		$(this).css("width",elemwidth);
		$(this).css("height",elemheight);
	});		
}

function restore_elemprop(selector) 
{
	var ewidth,eheight;
	  selector.each(function() {
		ewidth=$(this).attr("data-elemwidth");
		eheight=$(this).attr("data-elemheight");
		$(this).animate({width: ewidth,height:eheight}, 500);  
	  });
}

function galbox_slideshow_start()
{
	var imgobj=new JS_IMAGE();
	window.handle_galbox=imgobj.lightbox(window.galobj);
	imgobj.lightbox_slideshow(window.handle_galbox,2000);
}

function galbox_open_pic(ind)
{
	var imgobj=new JS_IMAGE();
	window.handle_galbox=imgobj.lightbox(window.galobj);
	
	if(ind=="0")
	{
		imgobj.lightbox_slideshow(window.galobj,2000);
		imgobj.lightbox_pause(window.galobj);
	}
	
	if(typeof ind!==undefined && typeof ind!==null)
	{
		imgobj.lightbox_moveto(window.handle_galbox,ind);
	}
	else
	{
		alert("OOPS! Some error occured! :-(");
	}
}

function galbox_slideshow_start_vid(ind)
{
	var imgobj=new JS_IMAGE();
	window.handle_galbox_vid=imgobj.lightbox(window.galobj_vid);
	imgobj.lightbox_slideshow(window.handle_galbox_vid,2000);
	
	if(typeof ind!==undefined && typeof ind!==null)
	{
		imgobj.lightbox_moveto(window.handle_galbox_vid,ind);
	}
	
	vidindex=0;window.galobj=[];window.galobj_vid=[];
	$(".galbox_viewvid_contimg").each(function(){
		   origimg=$(this).attr("data-origvid");
		   imgtitle=$(this).attr("data-vidtitle");
		   thumbimg=$(this).attr("src");
		   $(this).attr("data-galindex",vidindex);
		   if (typeof origimg === typeof undefined || origimg === false) 
		   {
			   $(this).attr("data-origvid",thumbimg);
			   origimg=thumbimg;
		   }
		   if (typeof imgtitle === typeof undefined || imgtitle === false) 
		   {
			   fileNameIndex = $(this).attr("src").lastIndexOf("/") + 1;
			   filename = $(this).attr("src").substr(fileNameIndex);
			   $(this).attr("data-imgtitle",filename);
			   imgtitle=filename;
		   }
		   imgmime=imgobj.get_mime(origimg);
		   
		   galimg={title:imgtitle,type:'video/*',sources: [{href:origimg,type:imgmime}],poster:thumbimg};
		   console.log(galimg);
		   window.galobj_vid.push(galimg);
		   vidindex++;
	   });
}

function scrolltodiv(selector){
  $('html,body').animate({
      scrollTop: selector.offset().top-80},
      'slow');
}

function dropDownFixPosition(button,dropdown){
      var dropDownTop = button.position().top + button.outerHeight();
      dropdown.css('top', dropDownTop + "px");
      dropdown.css('left', button.position().left + "px");
      
}

function chatbox_toggle(){
	$(this).parents(".taprofilechatbox").children(".tachatboxcont:first").toggle();
}

function galbox_audio_shownotes(){
	$("#galbox_audio_notes_audioinfo").fadeOut(function(){
		$("#galbox_audio_notes_cont").fadeIn();
	});
}

function galbox_audio_hidenotes(){
	$("#galbox_audio_notes_cont").fadeOut(function(){
		$("#galbox_audio_notes_audioinfo").fadeIn();
	});
}

//function galbox_switchtabs(e)
//{
//	 e.preventDefault()
//	 $(this).tab('show');
//}

function galbox_audio_clearqueue(){
	window.jplaylist.remove();
}

function resetJpPlaylist_audio(){
    var playlist = new Array();
    $( '.jp-playlist ul li' ).each( function( index, element ){
        playlist[ index ] = JSON.parse( $( element ).html() );
    });
    window.jplaylist.setPlaylist( playlist );
    playlistChanged = false;
    return window.jplaylist;
}

function viewportToPixel(val){
    var percent = val.match(/\d+/)[0] / 100,
        unit = val.match(/[vwh]+/)[0];
    return ( unit == 'vh'
      ? $(window).height() * percent
      : $(window).width() * percent ) + 'px';
  }

  function parseProps( props ){
    var p, prop;
    for ( p in props ) {
      prop = props[ p ];
      if ( /[vwh]$/.test( prop ) ) {
        props[ p ] = viewportToPixel( prop );
      }
    }
    return props;
  }
  
  
  (function($) {    
	  if ($.fn.style) {
	    return;
	  }

	  // Escape regex chars with \
	  var escape = function(text) {
	    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
	  };

	  // For those who need them (< IE 9), add support for CSS functions
	  var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;
	  if (!isStyleFuncSupported) {
	    CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
	      return this.getAttribute(a);
	    };
	    CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
	      this.setAttribute(styleName, value);
	      var priority = typeof priority != 'undefined' ? priority : '';
	      if (priority != '') {
	        // Add priority manually
	        var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
	            '(\\s*;)?', 'gmi');
	        this.cssText =
	            this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
	      }
	    };
	    CSSStyleDeclaration.prototype.removeProperty = function(a) {
	      return this.removeAttribute(a);
	    };
	    CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
	      var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
	          'gmi');
	      return rule.test(this.cssText) ? 'important' : '';
	    }
	  }

	  // The style function
	  $.fn.style = function(styleName, value, priority) {
	    // DOM node
	    var node = this.get(0);
	    // Ensure we have a DOM node
	    if (typeof node == 'undefined') {
	      return this;
	    }
	    // CSSStyleDeclaration
	    var style = this.get(0).style;
	    // Getter/Setter
	    if (typeof styleName != 'undefined') {
	      if (typeof value != 'undefined') {
	        // Set style property
	        priority = typeof priority != 'undefined' ? priority : '';
	        style.setProperty(styleName, value, priority);
	        return this;
	      } else {
	        // Get style property
	        return style.getPropertyValue(styleName);
	      }
	    } else {
	      // Get CSSStyleDeclaration
	      return style;
	    }
	  };
	})(jQuery);
  
  function body_closepopovers(e){
	    if ($(e.target).data('toggle') !== 'popover'
	        && $(e.target).parents('.popover.in').length === 0) { 
	        $('[data-original-title]').popover('hide');
	    }
	    /*
	    if ($(e.target).data('toggle') !== 'popover'
	        && $(e.target).parents('[data-toggle="popover"]').length === 0
	        && $(e.target).parents('.popover.in').length === 0) { 
	        $('[data-toggle="popover"]').popover('hide');
	    }
	    */
  }
  
  //var hasCssTransitionSupport = detectCSSFeature("transition");
  function detectCSSFeature(featurename){
	    var feature = false,
	    domPrefixes = 'Webkit Moz ms O'.split(' '),
	    elm = document.createElement('div'),
    featurenameCapital = null;

    featurename = featurename.toLowerCase();

    if( elm.style[featurename] !== undefined ) { feature = true; } 

    if( feature === false ) {
        featurenameCapital = featurename.charAt(0).toUpperCase() + featurename.substr(1);
        for( var i = 0; i < domPrefixes.length; i++ ) {
            if( elm.style[domPrefixes[i] + featurenameCapital ] !== undefined ) {
              feature = true;
              break;
            }
        }
    }
    return feature; 
}

function render_pdf(pdfobj,num,containerid)
{
	pdfobj.getPage(num).then(function getPageHelloWorld(page) {
	      var scale = 0.9;
	      var viewport = page.getViewport(scale);
	      var canvas = document.getElementById(containerid);
	      var context = canvas.getContext('2d');
	      canvas.height = viewport.height;
	      canvas.width = viewport.width;
	      var renderContext = {
	        canvasContext: context,
	        viewport: viewport
	      };
	      page.render(renderContext);
	    });
}

function tarea_h(e) 
{
	  e.css({'height':'auto','overflow-y':'hidden'}).height(e.get(0).scrollHeight);
	  
}

/*Called whenever a paste event has occured*/
function onPasteTriggered(e,selector) {
	var copiedData = e.clipboardData.items[0]; //Get the clipboard data

	/*If the clipboard data is of type image, read the data*/
	if(copiedData.type.indexOf("image") == 0) {
		var imageFile = copiedData.getAsFile(); 

		/*We will use HTML5 FileReader API to read the image file*/
		var reader = new FileReader();
		
		reader.onload = function (evt) {
			var result = evt.target.result; //base64 encoded image

			/*Create an image element and append it to the content editable div*/
			var img = document.createElement("img");
			img.src = result;
			selector.get(0).appendChild(img);
        };

        /*Read the image file*/
        reader.readAsDataURL(imageFile);
	}
}

function process_tarea(selector)
{
	var sel=selector.get(0);
	if(sel.attachEvent) {
		sel.attachEvent('paste', function(e){onPasteTriggered(e,selector);});
	}
	else if(sel.addEventListener) {
		sel.addEventListener('paste', function(e){onPasteTriggered(e,selector);}, false);
	}
	
	selector.each(function () {
		  tarea_h($(this));
		}).on({
			'input':function(e){
					tarea_h($(this));
				},
			'focusout':function(){
				var element = $(this);        
		        if (!element.text().replace(" ", "").length) {
		            element.empty();
		        }
			}
		});
}

function changebodybg()
{
	var bgurl='/master/securedir/m_images/bodybg/bodybg_'+window.tabg_count+'.jpg';
	$("#template_content_body").css("background-image","url("+bgurl+")");
	 window.tabg_count++;
	 if(window.tabg_count==window.tabg_maxcount){window.tabg_count=1;}
}

function rebindhotkeys()
{
	$(document).bind('keydown', 'alt+ctrl+z',function(e){
		chatbox_toggle();
	});
}

function exec_pic_gal()
{
	imgobj=new JS_IMAGE();
	imgobj.lightbox_index();
	var loadobj=new JS_LOADER();
	loadobj.jsload_blueimpgal(function(){
		console.log("BLUEIMP LIB LOADED");
	});
	/*listenevent($(".galbox_viewpic_cont"),"click",galbox_showpicdet);*/
}

function processhash(hashval)
{
	if(hashval=="")return "";
	hashval = String(hashval).replace( "#", "" );
	hashval = String(hashval).replace( "!", "" );
	return hashval;
}

function rebinder(itemkey)
{
	var utilityobj=new JS_UTILITY();
	
	switch(itemkey)
	{
		case "work_popover":
		case "education_popover":
			rebinder_profile(itemkey);
		break;
	}
}

function fluidDialog() {
    var $visible = $(".ui-dialog:visible");
    // each open dialog
    $visible.each(function () {
        var $this = $(this);
        var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
        // if fluid option == true
        if (dialog.options.fluid) {
            var wWidth = $(window).width();
            // check window width against dialog width
            if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
                // keep dialog from filling entire screen
                $this.css("max-width", "90%");
            } else {
                // fix maxWidth bug
                $this.css("max-width", dialog.options.maxWidth + "px");
            }
            //reposition dialog
            dialog.option("position", dialog.options.position);
        }
    });

}

function rebind_popovers()
{
	console.log("POPOVERS BOUND");
	
	
	
	var originalLeave = $.fn.popover.Constructor.prototype.leave;
	$.fn.popover.Constructor.prototype.leave = function(obj){
	  var self = obj instanceof this.constructor ?
	    obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
	  var container, timeout;

	  originalLeave.call(this, obj);

	  if(obj.currentTarget) {
	    container = $(obj.currentTarget).siblings('.popover')
	    timeout = self.timeout;
	    container.one('mouseenter', function(){
	      clearTimeout(timeout);
	      container.one('mouseleave', function(){
	        $.fn.popover.Constructor.prototype.leave.call(self, self);
	      });
	    })
	  }
	};
	
	
	
	$('[data-toggle="popover"]').popover('destroy');
	
	$('[data-toggle="popover"]').each(function(){
		var popplace=$(this).attr("data-diaplace");
		var poptarget=$(this).attr("data-diatarget");
		var poptrigger=$(this).attr("data-diatrigger");
		if(poptrigger==""||poptrigger==undefined)
		{
			poptrigger='click';
		}
		
		$(this).popover({
			html:true,
			container:'body',
			   content: function()
			   {
			        return $(poptarget).html();
			   },
			   placement:popplace,
			   trigger:'manual'
		}).off(poptrigger).on(poptrigger,function(e) {
			if($(this).attr("data-popstate")==""||$(this).attr("data-popstate")==undefined||$(this).attr("data-popstate")=="closed")
			{
				$(this).attr("data-popstate","open");
			}
			else
			{
				$(this).attr("data-popstate","closed");
			}
				$(this).popover('toggle');
				/*if(poptrigger=="mouseover")
				{
					$(this).off("mouseleave").on("mouseleave",function(){
						$(this).popover('hide');
					});
				}*/
		       e.preventDefault(); 
		       $(this).focus(); 
		   });
	});
	
	/*$('[data-toggle="popover"]').popover({
		html:true,
		container:'body',
		   content: function()
		   {
			   var target=$(this).attr("data-diatarget");
		        return $(target).html();
		   },
		   placement:$(this).attr("data-diaplace"),
		   trigger:'manual'
	}).off("click").on("click",function(e) { 
			$(this).popover('toggle');
	       e.preventDefault(); 
	       $(this).focus(); 
	   });*/
	
	$('[data-toggle="popover"]').off('shown.bs.popover').on('shown.bs.popover', function () {
		if($(this).attr("data-rebinder"))
		{
			var itemkey=$(this).attr("data-rebinder");
			rebinder(itemkey);
		}
	});
}

function rebind_tooltips()
{
	var ttipsel=$( "*" ).not(".lo_menu li")
	.not(".tarea_icons")
	.not("#galbox_picbtn_add")
	.not("#galbox_picbtn_more")
	.not("#galbox_vidbtn_more")
	.not("#galbox_vidbtn_add")
	.not("#galbox_docbtn_more")
	.not("#galbox_docbtn_add")
	.not(".input-group-addon")
	.not("#ga-tabs-genre th")
	.not("br")
	.not("head")
	.not("body")
	.not("html")
	.not(".chatbx_headercontrol button")
	;

		ttipsel.tooltip({
			container: 'body',
	      position: {
	        my: "center top",
	        at: "center bottom+5",
	        collision: "flipfit"
	      },
	      show: {
	        duration: "fast"
	      },
	      hide: {
	        effect: "hide"
	      }
	 });
	
	$(".lo_menu li").tooltip({placement:"right"});
	
	$(".tarea_icons").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_picbtn_more").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_picbtn_add").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_vidbtn_more").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_vidbtn_add").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_docbtn_more").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$("#galbox_docbtn_add").tooltip({position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
	
	$(".chatbx_headercontrol button").tooltip({container:".tachatboxcont",position: {my: "center top",at: "center top-38"},show: {duration: "fast"},hide: {effect: "hide"}});
}

function rebind_imgerror()
{
	$("img").off("error").on("error",function(){
		$(this).each(function(){
			$( this ).attr( "data-errimg",$(this).attr("src"));
			var imw=$( this ).width();var imh=$( this ).height();
			$( this ).attr( "src", "/master/securedir/m_images/image-not-found.png");
			$(this).hide();
		     $(this).attr("src",$(this).attr("src"));
		     $( this ).attr( "width",imw);$( this ).attr( "width",imh);
		     $(this).show();
		});
	});
}

function rebind_all()
{
	rebind_popovers();
	/*rebind_tooltips();*/
	rebind_imgerror();
}

function toggle_btnico()
{
	var changenew=$(this).children(".fa:first");
	var newico=changenew.attr("data-togico");
	var newico1=changenew.attr("data-togico1");
	changenew.removeClass(newico1).addClass(newico);
	changenew.attr("data-togico",newico1);
	changenew.attr("data-togico1",newico);
}

function ajax_sender(e,predata,s_cbk,f_cbk)
{
	if(("prompt" in predata)&&(predata.prompt!="")){
		if(!confirm("Are you sure?"))
		{
			return;
		}
	}
	if(typeof e!=='undefined'){$(e.target).prop('disabled', true);}
	
	for (var key in predata) 
	{
	  if (predata.hasOwnProperty(key)) 
	  {
	    if(typeof predata[key]==='object')
    	{
	    	predata[key]="";
    	}
	    	
	  }
	}
	
	var mydata;
	if(typeof predata===undefined)
	{
		predata=mydata=$(this).data();
	}
	else
	{
		mydata=predata;
	}
	
	console.log(mydata);
	
	var elemtarget=$("");
	var ajaxoptions={
      method:"POST",
	  url:"/request_process.php",
	  dataType:"json",
	  data:mydata,
	  ddemand:"json"
	};
	
	if(("elpropstop" in mydata)&&(mydata.elpropstop!="")){ajaxoptions.elpropstop=mydata.elpropstop;if(ajaxoptions.elpropstop=="1")e.stopPropagation();}
	if(!("eltarget" in mydata))
	{elemtarget=$("");}
	else
	if(mydata.eltarget!="-1"&&mydata.eltarget!="-2"){elemtarget=$(mydata.eltarget);}
	if(("surl" in mydata)&&(mydata.surl!="")){ajaxoptions.url=mydata.surl;}
	if(("pmethod" in mydata)&&(mydata.pmethod!="")){ajaxoptions.method=mydata.pmethod;}
	if(("dtype" in mydata)&&(mydata.dtype!="")){ajaxoptions.dataType=mydata.dtype;}
	if(("ddemand" in mydata)&&(mydata.ddemand!="")){ajaxoptions.ddemand=mydata.ddemand;}
	if(typeof window.globalsender!=="undefined"){
		mydata.globalsender=window.globalsender;
	}
	if("sform" in mydata && mydata.sform!="")
	{
		var myform=$(mydata.sform)[0];
		var form_file=$(mydata.sform).find("[type=file]");
		var form_data;
		
		ajaxoptions.cache=false;ajaxoptions.contentType=false;ajaxoptions.processData=false;ajaxoptions.method="POST";ajaxoptions.dataType="text/html";
		if(("dtype" in mydata)&&(mydata.dtype!="")){ajaxoptions.dataType=mydata.dtype;}
		
		if (!("FormData" in window))
		{
			form_data=new FormData();
			for (var key in mydata)
			{
				form_data.append(key,mydata.key)
			}
			
			var myfile,myfilename;
			for(var i=0;i<form_file.length;i++)
			{
				myfilename=form_file[i].attr("name");
				myfile=form_file[i].get(0).files[0];
				form_data.append(myfilename,myfile);
			}
			console.log('FormData not supported.');
		}
		else
		{
			form_data = new FormData($(mydata.sform)[0]);
			for (var key in mydata)
			{
			    form_data.append(key, mydata[key]);
			}
		}
		
		mydata=form_data;
		ajaxoptions.data=mydata;
	}
	
	
	console.log(mydata);
	console.log(ajaxoptions);
	
	$.ajax(ajaxoptions).done(function(data_html) {
		if(typeof e!=='undefined'){$(e.target).prop('disabled', false);}
		var data;
		if((ajaxoptions.dataType!="json")&&(ajaxoptions.dataType!="jsonp")&&(ajaxoptions.ddemand=="json"))
		{data = $.parseJSON(data_html);}
		else
		{data=data_html;}
		
		if(ajaxoptions.ddemand!="json")
		{
			elemtarget.html(data);
		}
		else
		{
			if(mydata.eltarget!="-2")
			{
				if(("execscript" in data)&&(data.execscript)!="")
				{
					$("body").append(data.execscript);
				}
			}
			
			if(data.returnval<0)
			{
				alert("OOPS! There was an error! \n\n Reason:"+data.message);
				return;
			}
			else
			if(data.returnval>=1)
			{
				predata=mydata;
				if(predata.eltarget=="-3")
				{}
				else
				if(predata.eltarget=="-2")
				{
					$("#modal_box #modal_box_label").html("Loading");$("#modal_box .modal-body:first").html("Loading content.. Please wait..");$("#modal_box .modal-footer:first").html("Loading..");
					$("#modal_box #modal_box_label").html(data.modaltitle);
					$("#modal_box .modal-body:first").html(data.modalbody);
					$("#modal_box .modal-footer:first").html(data.modalfooter);
					if(("execscript" in data)&&(data.execscript)!="")
					{
						$("body").append(data.execscript);
					}
				}
				else
				if(predata.eltarget!="-1")
				{
					elemtarget.html(data.message);
				}
				else
				{
					alert(data.message);
					window.location.reload();
				}
				
				if(("suchide" in predata)&&(predata.suchide!=""))
				{
					$(predata.suchide).hide();
				}
			}
		}
		
		if(typeof(s_cbk)!=='undefined')s_cbk(data_html);
		if("sucfunc" in predata && predata.sucfunc!=""){window[predata.sucfunc]($(e.target));}
			
		}).fail(function( jqXHR, textStatus ) {
			if(typeof e!=='undefined'){$(e.target).prop('disabled', false);}
				alert("OOPS! Unable to submit data! Try Again Later!");
			  console.log( "Request failed: " + textStatus +".."+jqXHR);
			  if(typeof(f_cbk)!=='undefined')f_cbk(jqXHR, textStatus);
			  if("failfunc" in predata && predata.failfunc!=""){window[predata.failfunc]($(e.target));}
		});
}

function box_toggle(e,mydata,sucfunc)
{
	$("#modal_box").modal('toggle');
	mydata.eltarget="-2";
	if(typeof sucfunc==='undefined')
	{ajax_sender(e,mydata);}
	else
	{ajax_sender(e,mydata,sucfunc);}
}

function chatbox_open(e)
{
	var tid=$(this).parents(".rcbx_item:first").attr("data-threadid");
	var fuid=$(this).parents(".rcbx_item:first").attr("data-fuid");
	
	if($('.taprofilechatbox[data-threadid="'+tid+'"]').length>0)
	{
		$('.taprofilechatbox[data-threadid="'+tid+'"]').children(".tachatboxcont:first").show();
		return;
	}
	
	var loadobj=new JS_LOADER();
	
	loadobj.ajax_call({
		  url:"/master/securedir/chatbox.php",
		  method:"POST",
		  data:{tid:tid,fuid:fuid},
		  cache:false,
		  success:function(data){
			  window.totchatbox++;
			  $(".chatbox_container").append(data);
		  }
	});
}

function setEndOfContenteditable(contentEditableElement)
{
    var range,selection;
    if(document.createRange)//Firefox, Chrome, Opera, Safari, IE 9+
    {
        range = document.createRange();//Create a range (a range is a like the selection but invisible)
        range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        selection = window.getSelection();//get the selection object (allows you to change selection)
        selection.removeAllRanges();//remove any selections already made
        selection.addRange(range);//make the range you have just created the visible selection
    }
    else if(document.selection)//IE 8 and lower
    { 
        range = document.body.createTextRange();//Create a range (a range is a like the selection but invisible)
        range.moveToElementText(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        range.select();//Select the range (make it the visible selection
    }
}

function notif_read()
{
	var comobj=new JS_COMMUNICATION();
	comobj.notification_dec();
}

function notif_remove(elem)
{
	var nid=elem.attr("data-nid");
	var comobj=new JS_COMMUNICATION();
	comobj.notification_remove(nid);
}

function formatvalues_search(mydata) 
{
	if(typeof mydata.photo!=="undefined" && mydata.photo!="")
    return "<img src=\""+mydata.photo + "\" width=\"30\" height=\"30\"> " + mydata.text;
	else
	return mydata.text;
}

function formatselection_search(mydata) 
{
	if(typeof mydata.photo!=="undefined" && mydata.photo!="")
    return "<img src=\""+mydata.photo + "\" width=\"20\" height=\"20\"> " + mydata.text;
	else
	return mydata.text;
}

function getUrlParts(url) 
{
    var a = document.createElement('a');
    a.href = url;

    return {
        href: a.href,
        host: a.host,
        hostname: a.hostname,
        port: a.port,
        pathname: a.pathname,
        protocol: a.protocol,
        hash: a.hash,
        search: a.search
    };
}

function formatvalues_cflag(mydata) 
{
	if(typeof mydata.photo!=="undefined" && mydata.photo!="")
    return "<img src=\""+mydata.photo + "\" width=\"32\" height=\"16\"> " + mydata.text;
	else
	return mydata.text;
}

function formatselection_cflag(mydata) 
{
	if(typeof mydata.photo!=="undefined" && mydata.photo!="")
    return "<img src=\""+mydata.photo + "\" width=\"32\" height=\"16\"> " + mydata.text;
	else
	return mydata.text;
}

function country_init(selector)
{
	if(typeof selector==="undefined")
	{
		selector=$(".country_input");
	}
	selector.select2({
		  ajax: {
		    url: "/item_getter.php",
		    dataType: "json",
			method:"POST",
		    data: function(params) {
		      return {
				key:"countries",query:params.term,page:params.page
		      };
		    },
		    processResults: function (data, params) {
				console.log(data);
		      params.page = params.page || 1;
		 
		      return {
		        results: data.results,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
			cache: true
			 }, 
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatvalues_cflag,
		formatSelection: formatvalues_cflag,
		templateSelection:formatselection_cflag,
		multiple: false,
		placeholder: "Select country",
		maximumSelectionSize: 1,
		minimumInputLength: 0
	});
}

function state_init(countrysel,statesel)
{
	if(typeof countrysel==="undefined")
	{
		countrysel=$(".country_input");
	}
	if(typeof statesel==="undefined")
	{
		statesel=$(".country_input");
	}
	statesel.select2({
		  ajax: {
		    url: "/item_getter.php",
		    dataType: "json",
			method:"POST",
		    data: function(params) {
		      return {
				key:"states",cname:countrysel.val(),query:params.term,page:params.page
		      };
		    },
		    processResults: function (data, params) {
				console.log(data);
		      params.page = params.page || 1;
		 
		      return {
		        results: data.results,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
			cache: true
			 }, 
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatvalues_cflag,
		formatSelection: formatvalues_cflag,
		templateSelection:formatselection_cflag,
		multiple: false,
		placeholder: "Select state",
		maximumSelectionSize: 1,
		minimumInputLength: 0
	});
}

function uplded_threadpic()
{
	var attvar=JSON.stringify(window.mediaidarr);
	var tid=$(".convbx_convcont").attr("data-threadid");
	
	loadobj.ajax_call({
		url:"/request_process.php",
		  method:"POST",
		  data:{mkey:"tbx_threadpic",tid:tid,attachments:attvar},
		  cache:false,
		  success:function(data){
			  $(".alert-text").html("The file has been uploaded successfully");
			  $(".alert-dismissable").css("display","block");
			  $(".statusinput").html("");
			  $(".status-subject").val("");
			  $(".statusinput").css("background-color","white");
			  window.location.reload();
		  }
	});
}