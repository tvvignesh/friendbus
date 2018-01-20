<?php
ob_start();
require_once 'header.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/master/securedir/googapi/vendor/autoload.php';

$logobj=new ta_logs();


/*GOOGLE*/
$google_client_id = '85436965142-5ote63u37keaujm7s5o8f8h2f5b38ccs.apps.googleusercontent.com';
$google_client_secret ='wQKLOz5i98sMf_sfvEld5EIz';
$google_redirect_uri ='https://www.friendbus.com/dash_import.php';

$GLOBALS["googlecontacts"]=Array();

//setup new google client
$client = new Google_Client();
$client -> setApplicationName('Friendbus');
$client -> setClientid($google_client_id);
$client -> setClientSecret($google_client_secret);
$client -> setRedirectUri($google_redirect_uri);
$client -> setAccessType('online');

$client -> setScopes('https://www.google.com/m8/feeds');

$googleImportUrl = $client -> createAuthUrl();


function curl_google($url, $post = "") 
{
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	curl_setopt($curl, CURLOPT_URL, $url);
	//The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	//The number of seconds to wait while trying to connect.
	if ($post != "") {
		curl_setopt($curl, CURLOPT_POST, 5);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
	//The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	//To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
	//To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	//The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	//To stop cURL from verifying the peer's certificate.
	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}

if(isset($_GET['code']))
{
	$auth_code = $_GET['code'];
	$max_results = 3000;
	$fields=array(
			'code'=>  urlencode($auth_code),
			'client_id'=>  urlencode($google_client_id),
			'client_secret'=>  urlencode($google_client_secret),
			'redirect_uri'=>  urlencode($google_redirect_uri),
			'grant_type'=>  urlencode('authorization_code')
	);
	$post = '';
	foreach($fields as $key=>$value)
	{
		$post .= $key.'='.$value.'&';
	}
	$post = rtrim($post,'&');
	$result = curl_google('https://accounts.google.com/o/oauth2/token',$post);
	$response =  json_decode($result);
	$accesstoken = $response->access_token;
	$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
	$xmlresponse =  curl_google($url);
	$contacts = json_decode($xmlresponse,true);
	
	$return = array();
	if (!empty($contacts['feed']['entry'])) {
		foreach($contacts['feed']['entry'] as $contact) {
			//retrieve Name and email address
			$return[] = array (
					'id'=>$contact['id'],
					'name'=> $contact['title']['$t'],
					'email' => @$contact['gd$email'][0]['address'],
			);
		}
	}
	$GLOBALS["googlecontacts"] = $return;
}

/*END GOOGLE*/



/*YAHOO*/

/*END YAHOO*/



$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_import.php",0,'/');
	echo '<div id="template_content_body">Please <a href="/index.php">Login</a></div>';
	return;
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
}
$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
$socialobj=new ta_socialoperations();
?>

<div id="template_content_body">
<div class="panel panel-default">
	<div class="panel-heading">Import contacts from your Accounts</div>
	<div class="panel-body">
		<a class="btn btn-primary" href="<?php echo $googleImportUrl; ?>"> Import Google Contacts </a>
		<br><br>
		(This will help you find your friends who are already in Friendbus and will also invite people who are in your contacts to join Friendbus so that you can have a network of all your loved ones.)
		<?php 
		
		for($i=0;$i<count($GLOBALS["googlecontacts"]);$i++)
		{
			$usrname=$GLOBALS["googlecontacts"][$i]["name"];
			$email=$GLOBALS["googlecontacts"][$i]["email"];
			$id=$GLOBALS["googlecontacts"][$i]["id"];
			$socialobj->importer_add($userobj->uid,$usrname,$email,$id,"1");
			//$socialobj->importer_invite($userobj->uid,"1");
		}
		
		//echo print_r($GLOBALS["googlecontacts"],true);
		//echo print_r($contacts,true);		
		?>
	</div>
	<div class="panel-footer">
	Importing from your accounts lets us provide you better information and recommendations.
	</div>
</div>
</div>

 <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>