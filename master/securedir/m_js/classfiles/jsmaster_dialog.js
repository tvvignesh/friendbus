/**
 *
 * CONTAINS DIALOG RELATED FUNCTIONS
 * @returns
 */
function JS_DIALOG(){};

JS_DIALOG.prototype.open_modalmessage=function(containerid,boxtitle,buttonobj,boxheight,boxwidth,effect_show,effect_hide)
{
	if(typeof boxtitle==="undefined"||boxtitle=="")boxtitle="Dialog Window";
	if(typeof boxheight==="undefined"||boxheight=="")boxheight="auto";
	if(typeof boxwidth==="undefined"||boxwidth=="")boxwidth="auto";
	if(typeof effect_show==="undefined"||effect_show=="")effect_show="clip";
	if(typeof effect_hide==="undefined"||effect_hide=="")effect_hide="blind";
	if(typeof buttonobj==="undefined"||buttonobj=="")buttonobj={Ok:function(){$(this).dialog("close");}};

	$(containerid).prepend('<p class="dialog_validate_statusmsg"></p>');

	$(containerid).dialog({
	  title:boxtitle,
	  height:boxheight,
	  width:boxwidth,
	  modal: true,
	  show: {
        effect:effect_show,
        duration: 500
      },
      hide: {
        effect:effect_hide,
        duration: 500
      },
      buttons:buttonobj
     });
};

JS_DIALOG.prototype.closebox=function(html_content)
{

};

JS_DIALOG.prototype.status_change=function(status,msgstatid)
{
	var tips;
	if(typeof msgstatid==="undefined"||msgstatid=="")
	{
		tips=$("#dialog_validate_statusmsg");
	}
	else
	{
		tips=$(msgstatid);
	}
	tips.html(status);
	tips.text(status).addClass( "ui-state-highlight" );
	setTimeout(function() {tips.removeClass( "ui-state-highlight", 1500 );}, 500 );
};

JS_DIALOG.prototype.popover=function(elem,options)
{
	return elem.popover(options);
};