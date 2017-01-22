/**
 *
 * HAS ALL SHORTCUT KEY OPERATIONS
 * @returns
 */
function JS_SHORTCUTS() {};

/**
 *
 * BIND A KEY OR COMBINATION OF KEYS TO MOUSE TRAP SO THAT THE CALL BACK FIRES WHEN PRESSED
 * @param key KEY OR COMBINATION TO BE BOUND (eg.'/' or 'ctrl+s' or ['ctrl+s', 'command+s'] or 'mod+s')
 * @param callback Callback to be fired when key is pressed
 * @param event Optional Parameter to tell which event triggers this call like keyup,keydown,etc.
 */
JS_SHORTCUTS.prototype.bind=function(key,callback,event)
{
	if(typeof event==="undefined")
	{
		Mousetrap.bind(key,callback);
	}
	else
	{
		Mousetrap.bind(key,callback,event);
	}
};

/**
 *
 * UNBIND A PREVIOUSLY BOUND KEY EVENT THROUGH MOUSETRAP
 * @param key Key which was bound in exact order
 */
JS_SHORTCUTS.prototype.unbind=function(key)
{
	Mousetrap.unbind(key);
};

/**
 *
 * BIND A SEQUENCE OF KEYS (ONE PRESSED AFTER ANOTHER)
 * @param sequence The Sequence (eg.'* a' or 'g o command+enter')
 * @param callback The Callback to be fired when the event occurs
 * @param event Optional (Event like keyup,keydown,etc.)
 */
JS_SHORTCUTS.prototype.bindsequence=function(sequence,callback,event){
	if(typeof event==="undefined")
	{
		Mousetrap.bind(sequence,callback);
	}
	else
	{
		Mousetrap.bind(sequence,callback,event);
	}
};

/**
 *
 *TRIGGER AN ALREADY BOUND MOUSE TRAP EVENT MANUALLY
 * @param key The key in exact order when it was bound
 * @param event Optional (Event like keyup,keydown,etc.)
 */
JS_SHORTCUTS.prototype.triggermousetrapevent=function(key,event){
	if(typeof event==="undefined")
	{
		Mousetrap.trigger(key);
	}
	else
	{
		Mousetrap.trigger(key,event);
	}
};

/**
 *
 * RESETS ALL BOUND KEYS TO BE BOUND AGAIN
 */
JS_SHORTCUTS.prototype.resetallkeys=function(){
	Mousetrap.reset();
};
//TODO PAUSE AND UNPAUSE MOUSETRAP