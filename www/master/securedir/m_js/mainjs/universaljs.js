var galbox_audio_player_id;
var galbox_audiocover;
var playlistChanged = false;

$(function() {
	
	$(".tachatboxcont").hide();
	
	$('.responsive-tabs').responsiveTabs({accordionOn: ['xs', 'sm']});
	
	listenevent_future('.nav-tabs a:not(".nav-tabs-ignorehash a"),.btn-tabs a:not(".nav-tabs-ignorehash a")',$("body"),"click",function(){
		var tabtar=$(this).attr("href");
		var taburl=$(tabtar).attr("data-taburl");
		var tab_id="";
		if($(this).parents(".nav-tabs:first").length>0)
		{
			tab_id=$(this).parents(".nav-tabs:first").attr("id");
		}
		/*$(tabtar).load(taburl);*/
		tabtar = String(tabtar).replace( "#","");
		
		if(tab_id!="rbar_tabs")
		{
			window.location.hash=tabtar;
		}
		else
		{
			var loadobj=new JS_LOADER();
			loadobj.hashmanager("off","#"+tabtar);
		}
		
	});
	
	if($("#tempbody_wrapper").length<=0)
	{
		$("#template_content_body").wrapInner( "<div id='tempbody_wrapper' style='background-color:rgba(255, 255, 255, 0.91);padding:5px;border-radius:5px;'></div>");
	}
	changebodybg();
    setInterval(changebodybg,15000);
	
		$(".lo_menu-button").click(function(){
			$(".lo_menu-bar").toggleClass( "open" );
		})
	
	//FOR BROWSERS NOT SUPPORTING console.log remove functionality or turn it into alerts
	var alertFallback = true;
	   if (typeof console === "undefined" || typeof console.log === "undefined") {
	     console = {};
	     if (alertFallback) {
	         console.log = function(msg) {
	              //alert(msg);
	         };
	     } else {
	         console.log = function() {};
	     }
	   }

		$('.expose').click(function(e){
		    $(this).css('z-index','99999');
		    $('#overlay').fadeIn(300);
		});

		$('#overlay').click(function(e){
		    $('#overlay').fadeOut(300, function(){
		        $('.expose').css('z-index','1');
		    });
		});
		
	   //var notifyobj=new JS_NOTIFICATIONS();
	   var utilityobj=new JS_UTILITY();
	   //notifyobj.get_all_nos();
	   //notifyobj.update_notifyicon_counter();
	   
	   
//	   $( ".taprofilechatbox" ).draggable({
//		   axis: "x",
//		   cursor: "move",
//		   handle:".tachatboxlabel",
//		   containment:"#taprofilechatbox_container",
//		      start: function() { },
//		      drag: function() { },
//		      stop: function() {$(this).css("top","");}
//		    });
	   
	// on window resize run function
	   $(window).resize(function () {
	       fluidDialog();
	   });

	   // catch dialog if opened within a viewport smaller than the dialog width
	   $(document).on("dialogopen", ".ui-dialog", function (event, ui) {
	       fluidDialog();
	   });
	   
	   $('.toggle-menu').jPushMenu({closeOnClickLink: false});
	   $('.dropdown-toggle').dropdown();
	   rebind_all();
	   
 });

$(window).resize();

if($(".cnt-notif:first").html()=="0")
{
	$(".cnt-notif").css("display","none");
}

var utilityobj=new JS_UTILITY();

utilityobj.select2($(".sbox_input"),{
	  ajax: {
	    url: "/item_getter.php",
	    dataType: "json",
		method:"POST",
	    data: function(params) {
	      return {
			key:"search",query:params.term,page:params.page
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
	minimumInputLength: 0,
	escapeMarkup: function (markup) { return markup; },
	templateResult: formatvalues_search,
	formatSelection: formatvalues_search,
	templateSelection:formatselection_search,
	multiple: false,
	placeholder: "Search for People, Places & Things",
	maximumSelectionSize: 1
});

$(".sbox_input").on("change", function(e) {
	var elid=$('.sbox_input').find(":selected").val();
	var eltext=$('.sbox_input').find(":selected").text();
	if(eltext.indexOf("ta-myusers") > -1)
	{
		window.location.assign("/users.php?uid="+elid);
	}
	else
	if(eltext.indexOf("ta-sgroups") > -1)
	{
		window.location.assign("/social_groups.php?gpid="+elid);
	}
});



/*MODAL STACKING FIX*/
/*$(document).on('show.bs.modal', '.modal', function (event) {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});*/


/*
 IE10 viewport hack for Surface/desktop Windows 8 bug
*/

(function () {
  'use strict';

  if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
      document.createTextNode(
        '@-ms-viewport{width:auto!important}'
      )
    )
    document.querySelector('head').appendChild(msViewportStyle)
  }

})();

/*
 * FORM DATA POLYFILL
 */
(function(w) {
    if (w.FormData)
        return;
    function FormData() {
        this.fake = true;
        this.boundary = "--------FormData" + Math.random();
        this._fields = [];
    }
    FormData.prototype.append = function(key, value) {
        this._fields.push([key, value]);
    }
    FormData.prototype.toString = function() {
        var boundary = this.boundary;
        var body = "";
        this._fields.forEach(function(field) {
            body += "--" + boundary + "\r\n";
            if (field[1].name) {
                var file = field[1];
                body += "Content-Disposition: form-data; name=\""+ field[0] +"\"; filename=\""+ file.name +"\"\r\n";
                body += "Content-Type: "+ file.type +"\r\n\r\n";
                body += file.getAsBinary() + "\r\n";
            } else {
                body += "Content-Disposition: form-data; name=\""+ field[0] +"\";\r\n\r\n";
                body += field[1] + "\r\n";
            }
        });
        body += "--" + boundary +"--";
        return body;
    }
    w.FormData = FormData;
})(window);