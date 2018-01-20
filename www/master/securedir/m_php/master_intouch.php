<?php
/**
 *
* CONTAINS FUNCTIONS RELATED TO EXTERNAL APIS AND INTOUCH
* @author T.V.VIGNESH
*
*/
class ta_intouch
{
	/**
	 * 
	 * ADD A INTOUCH ELEMENT TO INTOUCH DB
	 * @param unknown $uid UID of the user
	 * @param unknown $extflag Flag specifying the service (1-Facebook) 
	 * @param unknown $exttype Flag specifying type (1&1-Facebook Page)
	 * @param unknown $jsondata Json data to be sent to the intouch db
	 * @return Intouch ID on success, FAILURE on failure
	 */
	public function intouch_add_element($uid,$extflag,$exttype,$jsondata)
	{
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		$uiobj=new ta_uifriend();
		
		$intouchid=$uiobj->randomstring(30,tbl_intouchdb::dbname,tbl_intouchdb::tblname,tbl_intouchdb::col_intouchid);
		
		$jsonid=$utilityobj->jsondata_add($jsondata);
		if($dbobj->dbinsert("INSERT INTO ".tbl_intouchdb::tblname." (".tbl_intouchdb::col_intouchid.",".tbl_intouchdb::col_uid.",".tbl_intouchdb::col_extflag.",".tbl_intouchdb::col_exttype.",".tbl_intouchdb::col_jsonid.") VALUES ('$intouchid','$uid','$extflag','$exttype','$jsonid')", tbl_intouchdb::dbname)==SUCCESS)
		{
			return $intouchid;
		}
		else
		{
			return FAILURE;
		}
	}
	
	/**
	 * 
	 * LOAD THE FACEBOOK SDK TO A PAGE
	 */
	public function facebook_load_sdk()
	{
		return '<script>
				window.fbAsyncInit = function() {
					FB.init({
						appId      : "1037527712965926",
						xfbml      : true,
						version    : "v2.5"
					});	
	};
	
				(function(d, s, id){
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) {return;}
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_US/sdk.js";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, "script", "facebook-jssdk"));
					</script>';
	}
	
	/**
	 * 
	 * REQUEST PERMISSION FROM FACEBOOK FOR A SCOPE
	 * @param unknown $scope
	 * @return string
	 */
	public function facebook_request_permission($scope)
	{
		return '
			var uri = encodeURI("https://www.friendbus.com/dash_contacts.php?tabopen=intouch");
				FB.getLoginStatus(function(response) {
			      if (response.status === "connected") {
			                window.location.href=uri;
			      } else {
			         window.location = encodeURI("https://www.facebook.com/dialog/oauth?client_id=1037527712965926&redirect_uri="+uri+"&response_type=token&scope='.$scope.'&auth_type=reauthenticate");
			      }
							
			});	
		';
	}
	
	/**
	 * 
	 * GET FACEBOOK PAGES ADDED BY A USER TO INTOUCH DB
	 * @param unknown $uid UID of user
	 * @return string DBArray of all results
	 */
	public function facebook_get_pages($uid) 
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_intouchdb::tblname." WHERE ".tbl_intouchdb::col_uid."='$uid' AND ".tbl_intouchdb::col_extflag."='1' AND ".tbl_intouchdb::col_exttype."='1'", tbl_intouchdb::dbname);
	}
}
	?>