/**
 *
 * CONTAINS ALL JAVASCRIPT EFFECTS
 * @returns
 */
function JS_EFFECTS(){};

/**
 * FUNCTION WHICH HAS SLIDE EFFECTS
 * @param selector Specifies the element to which the effect has to be applied
 * @param direction Specifies direction of slide (1-up,2-down,3-slideRightShow,4-slideLeftHide,5-slideRightHide,6-slideLeftShow,7-slidetoggle)
 * @param duration Duration for which animation exists
 * @param completefunc Function to be called when animation completes
 */
JS_EFFECTS.prototype.slide=function(selector,direction,duration,completefunc)
{
	switch (direction) {
	case "1":
		$(selector).slideUp(duration,completefunc);
		break;
	case "2":
		$(selector).slideDown(duration,completefunc);
		break;
	case "3":
		var extendobj=new JS_EXTENDER();
		extendobj.jquery("1");
		$(selector).slideRightShow(duration,completefunc);
		break;
	case "4":
		var extendobj=new JS_EXTENDER();
		extendobj.jquery("1");
		$(selector).slideLeftHide(duration,completefunc);
		break;
	case "5":
		var extendobj=new JS_EXTENDER();
		extendobj.jquery("1");
		$(selector).slideRightHide(duration,completefunc);
		break;
	case "6":
		var extendobj=new JS_EXTENDER();
		extendobj.jquery("1");
		$(selector).slideLeftShow(duration,completefunc);
		break;
	case "7":
		$(selector).slideToggle(duration,completefunc);
		break;
	default:alert("Invalid choice");
		break;
	}
};

/**
 *
 * TOGGLES AN ELEMENT's VISIBILITY
 * @param selector Specifies the element to which the effect has to be applied
 */
JS_EFFECTS.prototype.toggle=function(selector){
	$(selector).toggle();
};

/**
 *
 * APPLY FADE EFFECTS TO AN ELEMENT
 * @param selector Specifies the element to which the effect has to be applied
 * @param fadetype Contains fade type 1-fadein,2-fadeout,3-fadetoggle,4-fadeto
 * @param duration Duration for which the effect should last
 * @param completefunc Callback to be executed when fade effect is complete
 * @param opacity Opacity to which the element has to be faded (Only applicable to 4-fadeTo)
 */
JS_EFFECTS.prototype.fade=function(selector,fadetype,duration,completefunc,opacity){
	if(typeof opacity==="undefined")
	{
		opacity=0;
	}

	switch (fadetype) {
	case "1":
		$(selector).fadeIn(duration,completefunc);
		break;
	case "2":
		$(selector).fadeOut(duration,completefunc);
		break;
	case "3":
		$(selector).fadeToggle(duration,completefunc);
		break;
	case "4":
		$(selector).fadeTo(duration,opacity,completefunc);
		break;
	default:
		break;
	}
};

/**
 *
 * ANIMATES AN ELEMENT
 * @param selector Specifies the element to which the effect has to be applied
 * @param properties Animation properties as an object eg.{opacity: 0.25,left: '+=50',height: 'toggle'}
 * @param duration Duration for which the animation should last
 * @param completefunc Callback to be executed when animation is complete
 * @param easing Type of easing to the animation (Defaults to swing)
 */
JS_EFFECTS.prototype.animate=function(selector,properties,duration,completefunc,easing){
	if(typeof easing==="undefined")
	{
		easing="swing";
	}
	 $(selector).animate(properties,duration,easing,completefunc);
};

/**
 *
 * SHOWS AN ELEMENT
 * @param selector Specifies the element to which the effect has to be applied
 * @param duration (Optional) Duration for which the effect should last
 * @param completefunc (Optional) Callback to be called when the effect is complete
 */
JS_EFFECTS.prototype.show=function(selector,duration,completefunc){
	if(typeof duration==="undefined"||typeof completefunc==="undefined")
	{
		$(selector).show();
	}
	else
	{
		$(selector).show(duration,completefunc);
	}
};

/**
 *
 * HIDES AN ELEMENT
 * @param selector Specifies the element to which the effect has to be applied
 * @param duration (Optional) Duration for which the effect should last
 * @param completefunc (Optional) Callback to be called when the effect is complete
 */
JS_EFFECTS.prototype.hide=function(selector,duration,completefunc){
	if(typeof duration==="undefined"||typeof completefunc==="undefined")
	{
		$(selector).hide();
	}
	else
	{
		$(selector).hide(duration,completefunc);
	}
};

/**
 *
 * GET THE QUEUE STATUS
 * @param selector Specifies the element to which the effect has to be applied
 * @param queuevar Queueobject if it exists (Defaults to fx)
 * @returns THE LENGTH OF THE QUEUE
 */
JS_EFFECTS.prototype.queuestatus=function(selector,queuevar){
	if(typeof queuevar==="undefined"){queuevar="fx";}
	 var n = $(selector).queue(queuevar);
	 return n.length;
};

/**
 *
 * STOP A CURRENTLY RUNNING ANIMATION
 * @param selector Specifies the element to which the effect has to be applied
 * @param clearqueue Boolean - true if queue has to be cleared,false if not
 * @param jumptoend Boolean - true if animation has to jump to end,false if not
 */
JS_EFFECTS.prototype.stopanimation=function(selector,clearqueue,jumptoend){
	$(selector).stop(clearqueue,jumptoend);
};

/**
 *
 * DEQUEUE AN ELEMENT
 * @param selector Specifies the element to which the effect has to be applied
 */
JS_EFFECTS.prototype.dequeue=function(selector){
	$(selector).dequeue();
};

/**
 *
 * GLOBALLY ENABLE OR DISABLE ALL ANIMATIONS
 * @param status true if it has to be disabled,false if it has to be enabled
 */
JS_EFFECTS.prototype.animationstatus=function(status){
	$.fx.off=status;
};

/**
 *
 * SETS FRAME RATE FOR ALL ANIMATIONS
 * @param frames No. of frames/second
 */
JS_EFFECTS.prototype.setframerate=function(frames){
	jQuery.fx.interval=frames;
};

/**
 *
 * SET PULSATE EFFECT
 * @param times No. of times pulsating effect occurs
 * @param duration Duration for which pulsate effect occurs
 */
JS_EFFECTS.prototype.pulsate=function(times,duration){
	var extendobj=new JS_EXTENDER();
	extendobj.jquery("2");
	$(this).pulse({times: times, duration: duration});
};