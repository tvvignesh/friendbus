<?php
if (php_sapi_name() === 'cli')
{
	$_SERVER['HTTP_HOST']="localhost";
}
//SERVER PATH (FILESYSTEM AND ABSOLUTE)
define("ROOT_SERVER", $_SERVER['DOCUMENT_ROOT']);
define("HOST_SERVER", "");

//SECURE MASTER PATH (FILESYSTEM AND ABSOLUTE)
define("ROOT_SECURE", ROOT_SERVER.'/master/securedir');
define("HOST_SECURE", HOST_SERVER.'/master/securedir');

//ASSET MASTER PATH (FILESYSTEM)
define("MASTER_PHP",ROOT_SECURE."/m_php");
define("MASTER_JS_ROOT",ROOT_SECURE."/m_js");
define("MASTER_CSS_ROOT",ROOT_SECURE."/m_css");
define("MASTER_PRODUCT",ROOT_SECURE."/m_products");
define("MASTER_TEMPLATE",ROOT_SECURE."/m_template");
define("MASTER_PROCESS",ROOT_SECURE."/m_process");
define("MASTER_IMAGES",ROOT_SECURE."/m_images");

define("HOST_TEMPLATE",HOST_SECURE."/m_template");

//ASSET MASTER PATH (ABSOLUTE)
define("MASTER_JS_HOST",HOST_SECURE."/m_js");
define("MASTER_CSS_HOST",HOST_SECURE."/m_css");
define("MASTER_IMAGES_HOST",HOST_SECURE."/m_images");

//PATH OF DATABASE AND TABLE DEFINITION FILES
define("ROOT_DB_MAIN",ROOT_SECURE."/m_databases");
define("ROOT_DB_PRODUCTS",ROOT_DB_MAIN."/productdb");

//PATH OF JS HOST (ABSOLUTE)

//PATH OF MEDIA GALLERY (FILE SYSTEM PATH)
define("ROOT_MEDIA",ROOT_SERVER.'/mediainfo');
define("ROOT_DOCS",ROOT_SERVER.'/docsinfo');
define("ROOT_ATTACHMENT",ROOT_SERVER.'/docsinfo/attachments');
define("ROOT_BACKUP",ROOT_SERVER.'/backupdocs');
define("ROOT_THEMECSS",ROOT_SERVER.'/themecss');

//BOOLEAN AND OTHER CONSTANTS
define("SUCCESS","SUCCESS");
define("FAILURE","");
define("BOOL_FAILURE",false);
define("BOOL_SUCCESS",true);

define("EMPTY_RESULT",NULL);

//DEFAULTS
define("MAILSERVER_DEFAULT","{mail.techahoy.com:143}");
define("MAILBOX_DEFAULT","{mail.techahoy.com:143}INBOX");

//MASTER IMAGE HOST
define("HOST_IMG_MASTER",HOST_SECURE."/m_images");

//PRODUCT NAMES
define("PROD_FBUS","friendbus");
define("PROD_LOGIN","friendbus");
define("PROD_SOCIAL","Social");
define("PROD_MPLACE","Marketplace");
define("PROD_BUILDMYIQ","Build My IQ");
define("PROD_MATRIMONY","Matrimony");
define("PROD_TEMPLE","Godly GOD");
define("PROD_FUNZONE","FunZone");

//APPID
define("APPID_SUBSCRIBERBOT","00001");

//PRODUCT ROOTS
define("PRODROOT_SUBSCRIBERBOT",ROOT_SERVER.'/subscriberbot');
define("PRODROOT_LOGIN",ROOT_SERVER);
define("PRODROOT_BUILDMYIQ",ROOT_SERVER.'/buildmyiq');
define("PRODROOT_HOOKMOVIES",ROOT_SERVER.'/hookmovies');
define("PRODROOT_SOCIAL",ROOT_SERVER.'/social');

//PRODUCT HOSTS
define("PRODHOST_SUBSCRIBERBOT",HOST_SERVER.'/subscriberbot');
define("PRODHOST_BUILDMYIQ",HOST_SERVER.'/buildmyiq');
define("PRODHOST_LOGIN",HOST_SERVER);
define("PRODHOST_HOOKMOVIES",HOST_SERVER.'/hookmovies');
define("PRODHOST_SOCIAL",HOST_SERVER.'/social');
?>
