/**
 *
 * HAS ALL UTILITY FUNCTIONS
 * @returns
 */
function JS_UTILITY(){}
/**
 *
 * FUNCTION WHICH IS BEING CALLED WHEN HASH VALUE CHANGES
 */
JS_UTILITY.prototype.hashlisten=function()
{
	var hashval=window.location.hash;
	if(hashval=="")
	{
		window.location.assign(location.href);
	}
	else
	if(hashval=="#editprofile")
	{
		editprofilefunc();
	}
};

/**
 *
 * CHANGE THE TEXT IN THE TITLE BAR
 * @param newtitle THE NEW TITLE TO BE ASSIGNED
 */
JS_UTILITY.prototype.changetitle=function(newtitle)
{
	document.title=newtitle;
};

JS_UTILITY.prototype.checkunameavail=function(uname)
{
	$.ajax({
		url: "unameavail.php",
		type:'POST',
		data: {uname:uname},
		cache: false,
		success: function(html){
			if(html=="SUCCESS")
			{
				return true;
		  	}
			else
			if(html=="FAILURE")
			{
				return false;
			}
			else
			{
				return "";
			}
		}
		,
		error:function(){
				return "error";
			}
		});
};

/**
 *
 * RETURNS A SET OF COLOR NAMES WITH THEIR HEX CODES
 * @returns an object with color text and hex code {'aliceblue':'#f0f8ff','antiquewhite':'#faebd7',...} etc.
 */
JS_UTILITY.prototype.colorsinit=function(){
	return {
			'aliceblue':'#f0f8ff',
			'antiquewhite':'#faebd7',
			'aqua':'#00ffff',
			'aquamarine':'#7fffd4',
			'azure':'#f0ffff',
			'beige':'#f5f5dc',
			'bisque':'#ffe4c4',
			'black':'#000000',
			'blanchedalmond':'#ffebcd',
			'blue':'#0000ff',
			'blueviolet':'#8a2be2',
			'brown':'#a52a2a',
			'burlywood':'#deb887',
			'cadetblue':'#5f9ea0',
			'chartreuse':'#7fff00',
			'chocolate':'#d2691e',
			'coral':'#ff7f50',
			'cornflowerblue':'#6495ed',
			'cornsilk':'#fff8dc',
			'crimson':'#dc143c',
			'cyan':'#00ffff',
			'darkblue':'#00008b',
			'darkcyan':'#008b8b',
			'darkgoldenrod':'#b8860b',
			'darkgray':'#a9a9a9',
			'darkgrey':'#a9a9a9',
			'darkgreen':'#006400',
			'darkkhaki':'#bdb76b',
			'darkmagenta':'#8b008b',
			'darkolivegreen':'#556b2f',
			'darkorange':'#ff8c00',
			'darkorchid':'#9932cc',
			'darkred':'#8b0000',
			'darksalmon':'#e9967a',
			'darkseagreen':'#8fbc8f',
			'darkslateblue':'#483d8b',
			'darkslategray':'#2f4f4f',
			'darkslategrey':'#2f4f4f',
			'darkturquoise':'#00ced1',
			'darkviolet':'#9400d3',
			'deeppink':'#ff1493',
			'deepskyblue':'#00bfff',
			'dimgray':'#696969',
			'dimgrey':'#696969',
			'dodgerblue':'#1e90ff',
			'firebrick':'#b22222',
			'floralwhite':'#fffaf0',
			'forestgreen':'#228b22',
			'fuchsia':'#ff00ff',
			'gainsboro':'#dcdcdc',
			'ghostwhite':'#f8f8ff',
			'gold':'#ffd700',
			'goldenrod':'#daa520',
			'gray':'#808080',
			'grey':'#808080',
			'green':'#008000',
			'greenyellow':'#adff2f',
			'honeydew':'#f0fff0',
			'hotpink':'#ff69b4',
			'indianred ':'#cd5c5c',
			'indigo  ':'#4b0082',
			'ivory':'#fffff0',
			'khaki':'#f0e68c',
			'lavender':'#e6e6fa',
			'lavenderblush':'#fff0f5',
			'lawngreen':'#7cfc00',
			'lemonchiffon':'#fffacd',
			'lightblue':'#add8e6',
			'lightcoral':'#f08080',
			'lightcyan':'#e0ffff',
			'lightgoldenrodyellow':'#fafad2',
			'lightgray':'#d3d3d3',
			'lightgrey':'#d3d3d3',
			'lightgreen':'#90ee90',
			'lightpink':'#ffb6c1',
			'lightsalmon':'#ffa07a',
			'lightseagreen':'#20b2aa',
			'lightskyblue':'#87cefa',
			'lightslategray':'#778899',
			'lightslategrey':'#778899',
			'lightsteelblue':'#b0c4de',
			'lightyellow':'#ffffe0',
			'lime':'#00ff00',
			'limegreen':'#32cd32',
			'linen':'#faf0e6',
			'magenta':'#ff00ff',
			'maroon':'#800000',
			'mediumaquamarine':'#66cdaa',
			'mediumblue':'#0000cd',
			'mediumorchid':'#ba55d3',
			'mediumpurple':'#9370d8',
			'mediumseagreen':'#3cb371',
			'mediumslateblue':'#7b68ee',
			'mediumspringgreen':'#00fa9a',
			'mediumturquoise':'#48d1cc',
			'mediumvioletred':'#c71585',
			'midnightblue':'#191970',
			'mintcream':'#f5fffa',
			'mistyrose':'#ffe4e1',
			'moccasin':'#ffe4b5',
			'navajowhite':'#ffdead',
			'navy':'#000080',
			'oldlace':'#fdf5e6',
			'olive':'#808000',
			'olivedrab':'#6b8e23',
			'orange':'#ffa500',
			'orangered':'#ff4500',
			'orchid':'#da70d6',
			'palegoldenrod':'#eee8aa',
			'palegreen':'#98fb98',
			'paleturquoise':'#afeeee',
			'palevioletred':'#d87093',
			'papayawhip':'#ffefd5',
			'peachpuff':'#ffdab9',
			'peru':'#cd853f',
			'pink':'#ffc0cb',
			'plum':'#dda0dd',
			'powderblue':'#b0e0e6',
			'purple':'#800080',
			'red':'#ff0000',
			'rosybrown':'#bc8f8f',
			'royalblue':'#4169e1',
			'saddlebrown':'#8b4513',
			'salmon':'#fa8072',
			'sandybrown':'#f4a460',
			'seagreen':'#2e8b57',
			'seashell':'#fff5ee',
			'sienna':'#a0522d',
			'silver':'#c0c0c0',
			'skyblue':'#87ceeb',
			'slateblue':'#6a5acd',
			'slategray':'#708090',
			'slategrey':'#708090',
			'snow':'#fffafa',
			'springgreen':'#00ff7f',
			'steelblue':'#4682b4',
			'tan':'#d2b48c',
			'teal':'#008080',
			'thistle':'#d8bfd8',
			'tomato':'#ff6347',
			'turquoise':'#40e0d0',
			'violet':'#ee82ee',
			'wheat':'#f5deb3',
			'white':'#ffffff',
			'whitesmoke':'#f5f5f5',
			'yellow':'#ffff00',
			'yellowgreen':'#9acd32'
		};
};

JS_UTILITY.prototype.checkconnection=function(){
	var _check=true;

    var networkState = navigator.network.connection.type;
    var states = {};
    states[Connection.UNKNOWN]  = 'Unknown connection';
    states[Connection.ETHERNET] = 'Ethernet connection';
    states[Connection.WIFI]     = 'WiFi connection';
    states[Connection.CELL_2G]  = 'Cell 2G connection';
    states[Connection.CELL_3G]  = 'Cell 3G connection';
    states[Connection.CELL_4G]  = 'Cell 4G connection';
    states[Connection.NONE]     = 'No network connection';
    //alert('Connection type: '+ networkState + states[networkState]);
    if(networkState==="unknown"){
        _check=false;
        //showAlert();
        return _check;
    }
    else {
        return true;
    }
};

JS_UTILITY.prototype.theme_topbar_changeprodname=function(prodname,rooturl,tip){
	if(typeof rooturl==="undefined")
	{
		rooturl="index.php";
	}
	if(typeof tip==="undefined")
	{
		tip="Click here to go to the Home Page of "+prodname;
	}
	$("#tbar_i_prodname").html('<a href="'+rooturl+'" title="'+tip+'" style="text-decoration:none;color:inherit;">'+prodname+'</a>');
};

JS_UTILITY.prototype.search_suggest=function(){
	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	var query=$("#sbox_input").val();

	loadobj.ajaxloadpage(host_process+"/process_search_suggest.php",function(results){
		var jsonobj=convobj.xmltojson(results);

	},function(error){
		alert("error");
		console.log(error);
	},"xml","2",{q:query});
};

JS_UTILITY.prototype.collapse_rbaritem=function(){
	$(this).next(".rbar_item_cont_enclose").slideToggle();
};

JS_UTILITY.prototype.show_rbar_more=function(){
	var utilityobj=new JS_UTILITY();

	if($(this).attr("disp")=="")
	{
		$(this).attr("disp","block");
		utilityobj.show_rbar_more_show();
		$(this).html("Hide");
		return;
	}
	if($(this).attr("disp")=="none")
	{
		$(this).attr("disp","block");
		utilityobj.show_rbar_more_show();
		$(this).html("Hide");
		return;
	}
	else
	{
		$(this).attr("disp","none");
		utilityobj.show_rbar_more_hide();
		$(this).html("More....");
		return;
	}
};

JS_UTILITY.prototype.rating=function(selector,options){
	function temp_ratingfunc()
	{
		selector.rating(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_bstrap_starrate(temp_ratingfunc);
};

JS_UTILITY.prototype.multiselect=function(selector,options){
	function temp_selfunc()
	{
		selector.multiselect(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_bstrap_multisel(temp_selfunc);
};

JS_UTILITY.prototype.table=function(selector,options){
	function temp_tablefunc()
	{
		return selector.bootstrapTable(options);
	}
	var loadobj=new JS_LOADER();
	var tblobj=loadobj.jsload_bstrap_table(temp_tablefunc);
	return tblobj;
};

/*
 * function(agent) {
		 agent.show();
		 agent.play('Greet');
		 agent.speak("How are you today? How can I help you?");
	}
 * */
JS_UTILITY.prototype.clippy=function(agentname,callback){
	function temp_clippyfunc()
	{
		clippy.load(agentname,callback);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_clippy(temp_clippyfunc);
};

JS_UTILITY.prototype.popcornsubs=function(idclass,options){
	function temp_popcornfunc()
	{
		return Popcorn(idclass).footnote(options)
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_popcornjs(temp_popcornfunc);
};

/*$('input[type="checkbox"]').checkbox({
checkedClass: 'glyphicon glyphicon-ok'
});*/
JS_UTILITY.prototype.checkbox=function(selector,options){
	function temp_checkboxfunc()
	{
		selector.checkbox(options);
		
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_checkbox(temp_checkboxfunc);
};

JS_UTILITY.prototype.toggle=function(selector,options){
	function temp_togglefunc()
	{
		selector.bootstrapToggle(options);
		
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_toggle(temp_togglefunc);
};

JS_UTILITY.prototype.moment=function(param2,param1){
	function temp_momentfunc()
	{
		selector.bootstrapToggle(options);
		
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_momentjs(temp_momentfunc);
};

JS_UTILITY.prototype.datetimepicker=function(selector,options){
	function temp_datetimefunc()
	{
		selector.datetimepicker(options);
		
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_datetimepicker(temp_datetimefunc);
};

JS_UTILITY.prototype.tags=function(selector,options){
	function temp_tagfunc()
	{
		selector.tagautocomplete(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_tags(temp_tagfunc);
};

JS_UTILITY.prototype.tagsonly=function(selector,options){
	function temp_tagfunc1()
	{
		selector.tagsinput(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_tagsonly(temp_tagfunc1);
};

JS_UTILITY.prototype.editable=function(selector,options){
	function temp_editablefunc()
	{
		selector.editable(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_editable(temp_editablefunc);
};

JS_UTILITY.prototype.select2=function(selector,options){
	function temp_sel2func()
	{
		if(!$(selector).data('select2'))
		{
			selector.select2(options);
		}
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_select2(temp_sel2func);
};

JS_UTILITY.prototype.typeahead=function(selector,options){
	function temp_typeaheadfunc()
	{
		selector.typeahead(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_typeahead(temp_typeaheadfunc);
};

JS_UTILITY.prototype.isonscreen=function(selector){
	function temp_isonscreen()
	{
		return selector.isOnScreen();
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_isonscreen(temp_isonscreen);
};

JS_UTILITY.prototype.mentions=function(selector,options){
	function temp_mentions()
	{
		return selector.mentionsInput(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_mentions(temp_mentions);
};

JS_UTILITY.prototype.lazyload=function(selector,options){
	function temp_lazyload()
	{
		return selector.jscroll(options);
	}
	var loadobj=new JS_LOADER();
	loadobj.jsload_lazyload(temp_lazyload);
};