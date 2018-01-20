/**
 * 
 * VALIDATION CLASS
 * @returns
 */
function JS_VALIDATION(){}

/**
 * 
 * VALIDATES USER NAME
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.uname=function(str)
{
	var regex =new RegExp("^[a-zA-Z_\.]*$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES EMAIL ADDRESS
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.email=function(str)
{
	var regex =new RegExp("^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES URL
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.url=function(str)
{
	var regex=new RegExp("^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|www\\.){1}([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?");
	return regex.test(str);
};

/**
 * 
 * VALIDATES ALPHA NUMERIC CHARACTERS
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.alphanumeric=function(str)
{
	var regex=new RegExp("^[0-9a-zA-Z]+$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES ONLY NUMERIC CHARACTERS
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.allnumeric=function(str)
{
	var regex=new RegExp("^[0-9]+$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES PASSWORD (Password must be at least 8 characters, no more than 15 characters, and must include at least one upper case letter, one lower case letter, and one numeric digit.)
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.password=function(str)
{
	var regex=new RegExp("^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES FLOATING POINT NUMBERS
 * @param evt
 */
JS_VALIDATION.prototype.floatingnumber=function (str) {
	var regex=new RegExp("^\d*[0-9](\.\d*[0-9])?$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES IP ADDRESS
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.ipaddress=function (str) {
	var regex=new RegExp("^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES DATE IN DD_MM-YYYY FORMAT
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.date_ddmmyyyy=function(str)
{
	var regex=new RegExp("^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES DATE IN MM/DD/YYYY FORMAT
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.date_mmddyyyy=function(str)
{
	var regex=new RegExp("^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES A DATE IN YYYY-MM-DD FORMAT
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.date_yyyymmdd=function(str)
{
	var regex=new RegExp("^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES GENERAL PHONE NUMBER
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.generalphone=function (str) {
	var regex=new RegExp("^(([0-9]{1})*[- .(]*([0-9a-zA-Z]{3})*[- .)]*[0-9a-zA-Z]{3}[- .]*[0-9a-zA-Z]{4})+$");
	return regex.test(str);
};

JS_VALIDATION.prototype.filename=function (str) {
	var regex=new RegExp("^[a-zA-Z0-9-_\.]+\.(pdf|txt|doc|csv|jpg|gif|png|swf|mov|wma|mpg|mp3|wav)$");
	return regex.test(str);
};

JS_VALIDATION.prototype.colorcode=function (str) {
	var regex=new RegExp("^#?([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$");
	return regex.test(str);
};

/**
 * 
 * VALIDATES INDIAN MOBILE NO in different format like +919847444225, +91-98-45017381, 9844111116, 98 44111112, 98-44111116
 * @param str
 * @returns
 */
JS_VALIDATION.prototype.mobile_india=function(str)
{
	var regex=new RegExp("^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}98(\s){0,1}(\-){0,1}(\s){0,1}[1-9]{1}[0-9]{7}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.phone_us=function(str)
{
	var regex=new RegExp("^[\\(]{0,1}([0-9]){3}[\\)]{0,1}[ ]?([^0-1]){1}([0-9]){2}[ ]?[-]?[ ]?([0-9]){4}[ ]*((x){0,1}([0-9]){1,5}){0,1}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.phone_italy=function(str)
{
	var regex=new RegExp("^([+]39)?((38[{8,9}|0])|(34[{7-9}|0])|(36[6|8|0])|(33[{3-9}|0])|(32[{8,9}]))([\d]{7})$");
	return regex.test(str);
};

JS_VALIDATION.prototype.phone_australia=function(str)
{
	var regex=new RegExp("(^1300\d{6}$)|(^1800|1900|1902\d{6}$)|(^0[2|3|7|8]{1}[0-9]{8}$)|(^13\d{4}$)|(^04\d{2,3}\d{6}$)");
	return regex.test(str);
};

JS_VALIDATION.prototype.phone_australia=function(str)
{
	var regex=new RegExp("^[S-s]( |-)?[1-9]{1}[0-9]{2}( |-)?[0-9]{2}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_us=function(str)
{
	var regex=new RegExp("^(?!00000)(?<zip>(?<zip5>\d{5})(?:[ -](?=\d))?(?<zip4>\d{4})?)$");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_uk=function(str)
{
	var regex=new RegExp("(((^[BEGLMNS][1-9]\d?)|(^W[2-9])|(^(A[BL]|B[ABDHLNRST]|C[ABFHMORTVW]|D[ADEGHLNTY]|E[HNX]|F[KY]|G[LUY]|H[ADGPRSUX]|I[GMPV]|JE|K[ATWY]|L[ADELNSU]|M[EKL]|N[EGNPRW]|O[LX]|P[AEHLOR]|R[GHM]|S[AEGKL-PRSTWY]|T[ADFNQRSW]|UB|W[ADFNRSV]|YO|ZE)\d\d?)|(^W1[A-HJKSTUW0-9])|(((^WC[1-2])|(^EC[1-4])|(^SW1))[ABEHMNPRVWXY]))(\s*)?([0-9][ABD-HJLNP-UW-Z]{2}))$|(^GIR\s?0AA$)");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_netherlands=function(str)
{
	var regex=new RegExp("^[1-9]{1}[0-9]{3}\s?[A-Z]{2}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_danmark=function(str)
{
	var regex=new RegExp("^[D-d][K-k]-[1-9]{1}[0-9]{3}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_dutch=function(str)
{
	var regex=new RegExp("^[1-9][0-9]{3}\s?[a-zA-Z]{2}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.postal_quebec=function(str)
{
	var regex=new RegExp("^[a-zA-Z]{1}[0-9]{1}[a-zA-Z]{1}(\-| |){1}[0-9]{1}[a-zA-Z]{1}[0-9]{1}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_visa=function(str)
{
	var regex=new RegExp("^4[0-9]{12}(?:[0-9]{3})?$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_mastercard=function(str)
{
	var regex=new RegExp("^5[1-5][0-9]{14}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_americanexpress=function(str)
{
	var regex=new RegExp("^3[47][0-9]{13}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_dinersclub=function(str)
{
	var regex=new RegExp("^3(?:0[0-5]|[68][0-9])[0-9]{11}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_discover=function(str)
{
	var regex=new RegExp("^6(?:011|5[0-9]{2})[0-9]{12}$");
	return regex.test(str);
};

JS_VALIDATION.prototype.creditcard_jcb=function(str)
{
	var regex=new RegExp("^(?:2131|1800|35\d{3})\d{11}$");
	return regex.test(str);
};