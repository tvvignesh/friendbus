<?php
$GLOBALS["dbr_total"]=0;$GLOBALS["dbr_query"]=0;$GLOBALS["dbr_del"]=0;$GLOBALS["dbr_update"]=0;$GLOBALS["dbr_insert"]=0;
if(isset($GLOBALS["noecho"]))
{
	if($GLOBALS["noecho"]=="yes")
	{
		ob_start();
	}
}
else
{
	$GLOBALS["noecho"]="no";
}
	global $noheaders;global $noecho;
	if (php_sapi_name() !== 'cli')
	{
		if ((!headers_sent())&&($noheaders!="yes")&&($GLOBALS["noheaders"]!="yes"))
		{
			ob_start();
				session_start();
			ob_end_clean();
		}
	}
	else
	{
		$GLOBALS["noecho"]="yes";$noecho="yes";
	}
	if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['SCRIPT_FILENAME'])){
		$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
	}; };
	if(!isset($_SERVER['DOCUMENT_ROOT'])){ if(isset($_SERVER['PATH_TRANSLATED'])){
		$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
	}; };
	$GLOBALS["alljs"]='';
	$GLOBALS["allcss"]='';
	$GLOBALS["dbretry"]=0;
	if(!isset($GLOBALS["appid"]))
	{
		$GLOBALS["appid"]="000000";
	}
	include_once("master/configconstants.php");

	//TA, DATABASE AND TABLE DEFINITIONS
	include_once(ROOT_DB_MAIN."/define_ta.php");//HAS ALL TA DEFINITIONS - VERY IMPORTANT
	include_once(ROOT_DB_MAIN."/define_db.php");//HAS ALL DATABASE DEFINITIONS - VERY IMPORTANT
	include_once(ROOT_DB_MAIN."/define_table.php");//HAS ALL TABLE DEFINITIONS - VERY IMPORTANT
	include_once(ROOT_DB_PRODUCTS."/define_db_products.php");//HAS ALL PRODUCT DATABASE DEFINITIONS

	//PRODUCT TABLE DEFINITIONS
	include_once(ROOT_DB_PRODUCTS."/define_table_subscriberbot.php");//HAS ALL SUBSCRIBERBOT TABLE DEFINITIONS
	
	if((!headers_sent())&&$noheaders!="yes"&&$GLOBALS["noheaders"]!="yes")
	{
		if(!isset($_COOKIE["returnpath"]))
		{
			$GLOBALS["returnpath"]=HOST_SERVER."/index.php";
			setcookie("returnpath",HOST_SERVER."/index.php",0,'/');
		}
		else
		{
			$GLOBALS["returnpath"]=$_COOKIE["returnpath"];
		}
	}

	require_once ROOT_SERVER.'/vendor/autoload.php';//LOAD LIBRARIES FROM COMPOSER
	
	include_once(MASTER_PHP."/master_advertisements.php");//HAS ALL AD RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_apps.php");//HAS ALL APP RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_archive.php");//DOES ALL ARCHIVING OPERATIONS
	include_once(MASTER_PHP."/master_assetloader.php");//DOES ASSET LOADING OPERATIONS
	include_once(MASTER_PHP."/master_audience.php");//HAS ALL AUDIENCE FUNCTIONS
	include_once(MASTER_PHP."/master_backup.php");//DOES BACKUP OPERATIONS
	include_once(MASTER_PHP."/master_blacklists.php");//DOES BLACKLISTING OPERATIONS
	include_once(MASTER_PHP."/master_collection.php");//HAS ALL COLLECTION RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_conversations.php");//DOES CONVERSATIONS HANDLING
	include_once(MASTER_PHP."/master_datacleaner.php");//DOES DATA CLEANING OPERATIONS
	include_once(MASTER_PHP."/master_date.php");//DOES DATE CONVERSIONS AND RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_db.php");//DOES ALL DATABASE OPERATIONS AND RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_delivery.php");//HAS ALL DELIVERY RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_error.php");//DOES ERROR HANDLING
	include_once(MASTER_PHP."/master_externalconnect.php");//DOES EXTERNAL CONNECTION OPERATIONS
	include_once(MASTER_PHP."/master_filehandling.php");//DOES ALL FILE & DIRECTORY HANDLING OPERATIONS
	include_once(MASTER_PHP."/master_gallery.php");//DOES GALLERY OPERATIONS
	include_once(MASTER_PHP."/master_graphics.php");//HAS GRAPHICAL FUNCTIONS, SMILEYS, ICONS,etc.
	include_once(MASTER_PHP."/master_language.php");//HAS LANGUAGE RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_location.php");//HAS ALL LOCATION AND PLACE RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_logs.php");//HAS ALL LOGS RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_mail.php");//HANDLES ALL MAIL RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_miscellaneous.php");//HAS MISCELLANEOUS CLASSES AND FUNCTIONS
	include_once(MASTER_PHP."/master_objects.php");//HAS OBJECTS CLASSES AND FUNCTIONS
	include_once(MASTER_PHP."/master_optimizer.php");//HAS FUNCTIONS WHICH CAN OPTIMIZE THINGS
	include_once(MASTER_PHP."/master_organization.php");//HAS ORGANIZATION CLASSES AND FUNCTIONS
	include_once(MASTER_PHP."/master_performance.php");//HAS PERFORMANCE FUNCTIONS
	include_once(MASTER_PHP."/master_settings.php");//HAS SETTING RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_plugins.php");//HAS PLUGIN RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_products.php");//HAS PRODUCT RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_secure.php");//DOES ALL ENCRYPT/DECRYPT OPERATIONS, UID GENERATIONS
	include_once(MASTER_PHP."/master_sms.php");//DOES SMS OPERATIONS
	include_once(MASTER_PHP."/master_social.php");//DOES SOCIAL OPERATIONS AND INTERACTIONS
	include_once(MASTER_PHP."/master_statistics.php");//HAS STATISTICS RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_support.php");//HAS SUPPORT OPERATIONS
	include_once(MASTER_PHP."/master_template.php");//HAS ALL TEMPLATE FUNCTIONS
	include_once(MASTER_PHP."/master_transactions.php");//DOES PAYMENT OPERATIONS
	include_once(MASTER_PHP."/master_ui.php");//HANDLES ALL FUNCTIONS RELATING TO USER INTERFACE
	include_once(MASTER_PHP."/master_units.php");//HANDLES ALL UNIT RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_user.php");//HAS ALL USER INFO AND RELATED FUNCTIONS
	include_once(MASTER_PHP."/master_utility.php");//HAS SOME UTILITY FUNCTIONS
	include_once(MASTER_PHP."/master_validation.php");//VALIDATES ALL DATA BEING PASSED TO IT
	include_once(MASTER_PHP."/master_xml.php");//DOES XML OPERATIONS
	include_once(MASTER_PHP."/master_system.php");//DOES SYSTEM OPERATIONS
	include_once(MASTER_PHP."/master_communications.php");//DOES SYSTEM OPERATIONS
	include_once(MASTER_PHP."/master_boxfile.php");//DOES BOXFILE OPERATIONS
	include_once(MASTER_PHP."/master_reqprocess.php");//PROCESS INCOMING REQUESTS
	include_once(MASTER_PHP."/master_search.php");//PROCESS SEARCH REQUESTS
	include_once(MASTER_PHP."/master_intouch.php");//PROCESS INTOUCH REQUESTS
	include_once(MASTER_PHP."/master_recommendations.php");//PROCESS RECOMMENDATION REQUESTS
	
	include_once(ROOT_SECURE."/min/lib/JSMin.php");
	include_once(ROOT_SECURE."/min/lib/CSSmin.php");
	
	//include_once(ROOT_SECURE."/min/lib/JSMinPlus.php");
	
	/**
	 * LOGIN,SOCIAL,SUBSCRIBERBOT
	 */
	$GLOBALS["product"]='';
	
	//PRODUCT MASTER FILES
	include_once(MASTER_PRODUCT."/master_p_subscriberbot.php");//HAS ALL FUNCTIONS RELATED TO SUBSCRIBERBOT
	
	if (php_sapi_name() !== 'cli')
	{
		ob_clean();
	}
	function buffercallback($buffer)
	{
		$utilityobj=new ta_utilitymaster();
		$optobj=new ta_optimizer();
		$buffer=$optobj->compress_html($buffer);
		return $buffer;
	}
	if($noecho!="yes"&&$GLOBALS["noecho"]!="yes")
	{
		ob_start("buffercallback");
		ob_start("ob_gzhandler");
	}
	set_exception_handler(array('ta_errorhandle','exceptionhandler'));

	$filterobj=new ta_filtervalue();
	$filterobj->filterrequest();

	if($noecho!="yes"&&$GLOBALS["noecho"]!="yes")
	{
		$utilityobj=new ta_utilitymaster();
		$utilityobj->includeextassets();
	}
	
	if($GLOBALS["noecho"]=="yes")
	{
		if (php_sapi_name() !== 'cli')
		{
			ob_end_clean();
		}
	}
?>
