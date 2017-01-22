/**
 *
 * HAS ALL IMAGE FUNCTIONS
 * @returns
 */
function JS_IMAGE(){}
/**
 *
 * FUNCTION WHICH IS BEING CALLED WHEN HASH VALUE CHANGES
 */
JS_IMAGE.prototype.crop=function(imgdivid)
{	
	var $image = $('#'+imgdivid+' > img');
	var result = $image.cropper('getCroppedCanvas');
	return result.toDataURL();
};

/**
 * 
 * var gallery = blueimp.Gallery([
    {
        title: 'Banana',
        href: 'https://example.org/images/banana.jpg',
        type: 'image/jpeg',
        thumbnail: 'https://example.org/thumbnails/banana.jpg'
    },
    {
        title: 'Apple',
        href: 'https://example.org/images/apple.jpg',
        type: 'image/jpeg',
        thumbnail: 'https://example.org/thumbnails/apple.jpg'
    }
]);

https://github.com/blueimp/Gallery#api-methods
 * @param links
 * @param options
 */
JS_IMAGE.prototype.lightbox=function(links,options)
{
	options={closeOnSlideClick:false,closeOnSwipeUpOrDown:false,onslide:function (index, slide) {
		var description=$(".galbox_viewpic_cont[data-galindex='"+index+"']").attr("data-desc");
		$(".description").html("<span class='picdesc'>"+description+"</span>");
		
	}};
	var gallery = blueimp.Gallery(links, options);
	
	$(".blueimp-gallery-controls>.indicator").draggable({ 
		axis: "x" ,
		start:function(){
				$(".blueimp-gallery-controls>.indicator").css("width","auto");
				$(".blueimp-gallery-controls>.indicator").css("top","auto");
			},
		drag:function(){
				$(".blueimp-gallery-controls>.indicator").css("width","auto");
				$(".blueimp-gallery-controls>.indicator").css("top","auto");
			},
		stop:function(){
				$(".blueimp-gallery-controls>.indicator").css("top","auto");
			}
		});
	
	$('.toggle-menu').jPushMenu({closeOnClickLink: false});
	
	listenevent($(".slide"),"click",function(e){
//		if(!($("#blueimp-gallery").hasClass("blueimp-gallery-controls")))
//		{
//			gallery.toggleControls();
//		}
		//gallery.toggleControls();
		console.log("Slide click");
//		e.stopPropagation();
		//gallery.container.addClass("blueimp-gallery-controls");
		$("#blueimp-gallery").addClass("blueimp-gallery-controls");
		//$("#blueimp-gallery").addClass("blueimp-gallery-controls");
	});
	
	return gallery;
};

JS_IMAGE.prototype.lightbox_index=function()
{
	 window.galobj=[];
	 imgobj=new JS_IMAGE();
	  $(".galbox_viewpic_cont").each(function(index)
		{
		   	$(this).attr("data-galindex",index);
			var galbox_pic_thumb=$(this).css('background-image').replace('url("','').replace('")','');
			var galbox_pic_orig=$(this).attr("data-orig");
			var galbox_pic_title=$(this).attr("data-imgtitle");
			var galbox_pic_desc=$(this).attr("data-desc");
			var galbox_pic_picid=$(this).attr("data-mediaid");
			var galbox_pic_picindex=$(this).attr("data-galindex");
			
			galbox_pic_thumb=getUrlParts(galbox_pic_thumb).pathname
			
		   if (typeof galbox_pic_orig === typeof undefined || galbox_pic_orig === false) 
		   {
			   $(this).attr("data-orig",galbox_pic_thumb);
			   galbox_pic_orig=galbox_pic_thumb;
		   }
		   if (typeof galbox_pic_title === typeof undefined || galbox_pic_title === false) 
		   {
			   fileNameIndex = galbox_pic_orig.lastIndexOf("/") + 1;
			   filename = galbox_pic_orig.substr(fileNameIndex);
			   $(this).attr("data-imgtitle",filename);
			   galbox_pic_title=filename;
		   }
		   imgmime=imgobj.get_mime(galbox_pic_orig);
		   
		   galimg={title:galbox_pic_title,href:galbox_pic_orig, thumbnail:galbox_pic_thumb,type:imgmime};
		   
		   window.galobj.push(galimg);
	   });
};

JS_IMAGE.prototype.lightbox_getindex=function(gallery)
{
	var pos = gallery.getIndex();
	return pos;
};

JS_IMAGE.prototype.lightbox_getcount=function(gallery)
{
	var count = gallery.getNumber();
	return count;
};

JS_IMAGE.prototype.lightbox_prev=function(gallery)
{
	gallery.prev();
};

JS_IMAGE.prototype.lightbox_next=function(gallery)
{
	gallery.next();
};

JS_IMAGE.prototype.lightbox_moveto=function(gallery,index,duration)
{
	if(typeof duration===undefined)
	{
		duration=500;
	}
	gallery.slide(index, duration);
	
	
		
};

JS_IMAGE.prototype.lightbox_slideshow=function(gallery,interval)
{
	gallery.play(interval);
};

JS_IMAGE.prototype.lightbox_pause=function(gallery)
{
	gallery.pause();
};

JS_IMAGE.prototype.lightbox_add=function(gallery,list)
{
	gallery.add(list);
};

JS_IMAGE.prototype.lightbox_close=function(gallery)
{
	gallery.close();
};

JS_IMAGE.prototype.get_mime=function(url)
{
	var ext = url.split(".").pop();

	if( typeof(window.mimes[ext]) != "undefined" )
	 mime = window.mimes[ext];
	else
	 mime = "text/html";
	
	return mime;
};