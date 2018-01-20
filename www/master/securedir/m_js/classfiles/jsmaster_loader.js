/**
 *
 * HAS FUNCTIONS WHICH LOADS/PRELOADS ALL NECESSARY PAGES OR FILES USING JAVASCRIPT AND DOES NECESSARY OPERATIONS
 * @returns
 */
function JS_LOADER(){};

/**
 *
 * LOAD A PAGE USING AJAX (USE THE SUCCESSFUNC TO RETRIEVE THE RESPONSE)
 * @param url URL of the page to be loaded
 * @param successfunc Function to be executed on successful load
 * @param errorfunc Function to be executed on error loading
 * @param datatype Data type returned as response (xml,html,script,json,jsonp,text)
 * @param method 1-GET,2-POST
 * @param dataobj An object with key value pairs to be sent to the page eg: {key1:value1,key2:value2}
 * @param asyncvar Tell if the loader is synchronous or not "true" if async "false" if not async
 * @param timeout Time out in milliseconds after which request fails
 * @param username User Name which is to be returned during HTTP authentication (if any)
 * @param completefunc Function which is to be executed when request is complete irrespective of success or failure
 */
JS_LOADER.prototype.ajaxloadpage=function(url,successfunc,errorfunc,datatype,method,dataobj,asyncvar,timeout,username,completefunc,headers){
	if(typeof completefunc==="undefined")
	{
		completefunc=function(){};
	}
	if(typeof username==="undefined")
	{
		username="";
	}
	if(typeof timeout==="undefined")
	{
		timeout=3000;
	}
	if(typeof dataobj==="undefined")
	{
		dataobj={};
	}
	if(typeof errorfunc==="undefined")
	{
		errorfunc=function(){};
	}
	if(typeof headers==="undefined")
	{
		headers={};
	}
	if(typeof asyncvar==="undefined")
	{
		asyncvar=true;
	}
	var methodtype;
	if(method=="1")
	{
		methodtype="GET";
	}
	else
	{
		methodtype="POST";
	}
	var request = $.ajax({
	  url:url,
	  type:methodtype,
	  data:dataobj,
	  dataType:datatype,
	  username:username,
	  complete:completefunc,
	  headers:headers,
	  cache:false,
	  async:asyncvar
	});
		request.done(successfunc);
		request.fail(errorfunc);
};

JS_LOADER.prototype.ajax_call=function(options)
{
	 var callComplete = new $.Deferred();
	    $.ajax(options).done(function(data){
	    	callComplete.resolve(data);
	    })
	    return callComplete.promise();
}

JS_LOADER.prototype.hashmanager=function(hashmode,hashtar){
	var loadobj=new JS_LOADER();
	var taburl,myhash;
	if((typeof hashmode==='undefined')&&(typeof hashtar==='undefined'))
	{
		myhash=window.location.hash;
		if(myhash=="")return;
		myhash=processhash(myhash);
		taburl=$("#"+myhash).attr("data-taburl");
	}
	else
	if(hashmode=="off")
	{
		myhash=hashtar;
		if(myhash=="")return;
		myhash=processhash(myhash);
		taburl=$("#"+myhash).attr("data-taburl");
	}
	
	var sucfunc=$("#"+myhash).attr("data-sucfunc");
	
	window.tabloaded=loadobj.ajax_call({
		  url:taburl,
		  cache:false,
		  success:function(data){
			  $("#"+myhash).html(data);
			if(hashmode!="off"){rebind_all();}
			if(typeof sucfunc!=='undefined'&&sucfunc!=""){window[sucfunc]();}
		  }
		});
	
	if($('.nav-tabs a[href=#'+myhash+']').length>0)
	{
		$('.nav-tabs a[href=#'+myhash+']').tab('show') ;
	}
	else
	if($('.jquitabs a[href=#'+myhash+']').length>0)
	{
		var mypar=$('a[href="#'+myhash+'"]').parents(".jquitabs:first");
		var myli=$('a[href=#'+myhash+']:first').parent("li");
		var index = mypar.find('a[href="#'+myhash+'"]:first').parent().index();
		mypar.on("tabscreate",function(){
			mypar.tabs( "option", "active",index);
		});
		/*mypar.on( "tabsactivate", function( event, ui ) {});*/
	}
	else
	if($('.btn-tabs a[href=#'+myhash+']').length>0)
	{
		var sel=$('.btn-tabs a[href=#'+myhash+']').parents(".btn-tabs:first").find("button[data-toggle='tab']");
		sel.removeClass("btn-primary");
		sel.addClass("btn-default");
		$('.btn-tabs a[href=#'+myhash+']').tab('show') ;
		var sel1=$('.btn-tabs a[href=#'+myhash+']').children("button[data-toggle='tab']");
		sel1.removeClass("btn-default");
		sel1.addClass("btn-primary");
	}
	return window.tabloaded;
};

/**
 * 
 * Loads Javascript file asynchronously
 * @param filepath
 */
JS_LOADER.prototype.loadjsfile=function(filepath){
	var s = document.createElement('script');
	s.type = 'text/javascript';
	s.async = true;
	s.src = filepath;
	var x = document.getElementsByTagName('script')[0];
	x.parentNode.insertBefore(s, x);
};


JS_LOADER.prototype.jsload=function(path,callback_success,cache){
	if(cache===undefined){
		cache=true;
	}
	var cbk;
	cbk=callback_success;
	if(cbk===undefined||typeof cbk===undefined){
		cbk=function(){};
	}
	
	if(window.tascripts[path]==1)
	{
		return $.Deferred(function() {
			var self = this;
			self.resolve();
			cbk();
		});
	}
	
	return $.Deferred(function() {
        	var self = this;
			$.ajax({
		        type: "GET",
		        url:path,
		        success:cbk,
		        error:function(){
		        	console.log("Unable to load JS file:"+path);
		        },
		        dataType: "script",
		        cache: cache,
		        complete:function(){self.resolve();window.tascripts[path]=1;}
		    });
	
	});
};

JS_LOADER.prototype.jsload_vidlibs=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/video.js",function(){
	//loadobj.jsload("https://vjs.zencdn.net/5.4.6/video.js",function(){
		videojs.options.flash.swf ="/master/securedir/m_js/libs/video-js/video-js/video-js.swf";
		$.when(
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs.playlists.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/videojs.hotkeys.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/videojs.thumbnails.js"),				
				loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/videojs.watermark.js"),
				loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/videojs.zoomrotate.js"),
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-resolution-switcher-master/lib/videojs-resolution-switcher.js"),
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-transcript.min.js"),
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/video-framebyframe.js"),
				loadobj.jsload("/master/securedir/m_js/libs/video-js/video-js/videojs-seek.min.js")
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs.loopbutton.min.js"),
				//loadobj.jsload("/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-errors.min.js")
			).then(callback);
		        },function(){});
};

JS_LOADER.prototype.jsload_pdfjs=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/pdfjs-1.1.366-dist/build/pdf.js",function(){
		PDFJS.workerSrc = '/master/securedir/m_js/libs/pdfjs-1.1.366-dist/build/pdf.worker.js';
		callback();
	},true);
};

JS_LOADER.prototype.jsload_chart=function(callback){
		var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/Chart.js-master/Chart.min.js",callback,true);
};

JS_LOADER.prototype.jsload_blueimpgal=function(callback){
		var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/gallery/js/blueimp-gallery.min.js",callback,true);
};

JS_LOADER.prototype.jsload_bstrap_starrate=function(callback){
		var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/bootstrap-star-rating/js/star-rating.min.js",callback,true);
};

JS_LOADER.prototype.jsload_bstrap_multisel=function(callback){
		var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js",function(){
			loadobj.jsload("/master/securedir/m_js/libs/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js",callback,true);
		},true);
};

JS_LOADER.prototype.jsload_bstrap_table=function(callback){
	
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-master/dist/bootstrap-table-all.min.js",function(){
		$.when(
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-master/src/extensions/toolbar/bootstrap-table-toolbar.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-master/src/extensions/export/bootstrap-table-export.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-ext/tableexport/tableExport.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-ext/tableexport/libs/FileSaver/FileSaver.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-ext/tableexport/libs/html2canvas/html2canvas.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-ext/tableexport/libs/jsPDF/jspdf.min.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-ext/tableexport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"),
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-table-master/src/extensions/mobile/bootstrap-table-mobile.js")
			).then(callback);
		        },true);
};

JS_LOADER.prototype.jsload_clippy=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/clippy.js-master/build/clippy.min.js",callback,true);
};

JS_LOADER.prototype.jsload_jplayer=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/jPlayer-master/dist/jplayer/jquery.jplayer.min.js",function(){
		loadobj.jsload("/master/securedir/m_js/libs/jPlayer-master/dist/add-on/jplayer.playlist.min.js",callback,true);
	},true);
};

JS_LOADER.prototype.jsload_checkbox=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-checkbox/js/bootstrap-checkbox.js",callback,true);
};

JS_LOADER.prototype.jsload_toggle=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-toggle-master/js/bootstrap-toggle.min.js",callback,true);
};

JS_LOADER.prototype.jsload_momentjs=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/moment-with-locales.js",callback,true);
};

JS_LOADER.prototype.jsload_datetimepicker=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/moment-with-locales.js",function(){
		loadobj.jsload("/master/securedir/m_js/libs/bootstrap-datetimepicker-master/src/js/bootstrap-datetimepicker.js",callback,true);
	},true);
};

JS_LOADER.prototype.jsload_typeahead=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-typeahead.js",callback,true);
};

JS_LOADER.prototype.jsload_tags=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-typeahead.js",function(){
		
		$.when(
				loadobj.jsload("/master/securedir/m_js/libs/rangy/lib/rangy-core.js"),
				loadobj.jsload("/master/securedir/m_js/libs/caret-position.js")
			).then(function(){
				loadobj.jsload("/master/securedir/m_js/libs/bootstrap-tagautocomplete.js",callback,true);
			});
		
	},true);
};

JS_LOADER.prototype.jsload_tagsonly=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",callback,true);
};

JS_LOADER.prototype.jsload_editable=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap3-editable-1.5.1/bootstrap3-editable/js/bootstrap-editable.js",callback,true);
};

JS_LOADER.prototype.jsload_select2=function(callback){
	var loadobj=new JS_LOADER();
	/*loadobj.jsload("http://vitalets.github.io/x-editable/assets/select2/select2.js",callback,true);*/	
	loadobj.jsload("/master/securedir/m_js/libs/select2-4.0.1/dist/js/select2.full.min.js",callback,true);
};

JS_LOADER.prototype.jsload_countries=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/mainjs/countries.js",callback,true);
};

JS_LOADER.prototype.jsload_multipleinputs=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/bootstrap3-editable-1.5.1/inputs-ext/address/address.js",callback,true);
};

JS_LOADER.prototype.jsload_flowjs=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/flow.min.js",callback,true);
};

JS_LOADER.prototype.jsload_isonscreen=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/isOnScreen-master/jquery.isonscreen.min.js",callback,true);
};

JS_LOADER.prototype.jsload_emoticons=function(callback){
	var loadobj=new JS_LOADER();
	loadobj.jsload("/master/securedir/m_js/libs/jquery.cssemoticons.min.js",callback,true);
};

JS_LOADER.prototype.jsload_mentions=function(callback){
	var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/mentions/jquery.mentions.js",callback,true);
};

JS_LOADER.prototype.jsload_lazyload=function(callback){
	var loadobj=new JS_LOADER();
		loadobj.jsload("/master/securedir/m_js/libs/jscroll-master/jquery.jscroll.min.js",callback,true);
};