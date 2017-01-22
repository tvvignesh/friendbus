/**
 *
 * HAS ALL STORAGE OPERATIONS
 * @returns
 */
function JS_STORAGE() {};

/**
 *
 * CHECK LOCAL STORAGE AND SESSION STORAGE SUPPORT BY THE BROWSER
 * @returns {Boolean} true if it supports,false if it does not support
 */
JS_STORAGE.prototype.checksupport=function()
{
	if(typeof(Storage)!=="undefined")
	{
		return true;
	}
	else
	{
		return false;
	}
};

/**
 *
 * SET KEY VALUE PAIRS (LOCAL STORAGE)
 * @param key KEY of the stored value
 * @param value Value to be stored to the key
 * @returns {Boolean} true on success,false on failure
 */
JS_STORAGE.prototype.set_local=function(key,value)
{
	var obj=new JS_STORAGE();
	if(obj.checksupport())
	{
		localStorage[key]=value;
		return true;
	}
	else
	{
		return false;
	}
};

/**
 *
 * GET THE VALUE OF THE LOCAL STORAGE BY GIVING THE KEY
 * @param key The KEY of the Local Storage value to be retrieved
 * @returns The value stored on success,false on failure
 */
JS_STORAGE.prototype.get_local=function(key)
{
	var obj=new JS_STORAGE();
	if(obj.checksupport())
	{
		var value=localStorage.getItem(key);
		return value;
	}
	else
	{
		return false;
	}
};

/**
 *
 * REMOVE AN ITEM FROM LOCAL STORAGE BY GIVING THE KEY
 * @param key The Key of the Item to be removed
 * @returns true on success,false on failure
 */
JS_STORAGE.prototype.remove_local=function(key)
{
	var obj=new JS_STORAGE();
	if(obj.checksupport())
	{
		return localStorage.removeItem(key);
	}
	else
	{
		return false;
	}
};