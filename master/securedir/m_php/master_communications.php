<?php

/**
 *
 * CONTAINS COMMUNICATION FUNCTIONS RELATED TO WEBSOCKETS,WebRTC,etc.
 * @author T.V.VIGNESH
 *
 */
class ta_communication
{
	/**
	 * 
	 * Create a socket connection for a user
	 * @param unknown $uid UID of the user
	 */
	public function socket_add_user($uid)
	{
		?>
			<script type="text/javascript">
				var comobj=new JS_COMMUNICATION();
				var mysocket=comobj.socket_init();
				mysocket.onopen=function(msg){
					comobj.socket_send(mysocket,'{"uid":"<?php echo $uid;?>","mtype":"1","msg":"Initialize User socket","target":"-1"}');
					window.socket_user=mysocket;
				};
			</script>
		<?php
	}
	
	/**
	 * 
	 * ADD A USER TO SOCKET DB
	 * @param unknown $user User Object
	 * @param unknown $msgobj Message Object
	 */
	public function socket_add_user_db($user,$msgobj)
	{
		$comobj=new ta_communication();
		$dbobj=new ta_dboperations();
		$utilityobj=new ta_utilitymaster();
		
		if(isset($msgobj->uid))$uid=$msgobj->uid;
		if(isset($msgobj->mtype))$rtype=$msgobj->mtype;
		if(isset($msgobj->msg))$message=$msgobj->msg;
		if(isset($msgobj->target))$target=$msgobj->target;
		if(!$comobj->socket_check_user($uid))
		{
			$jsondata=$utilityobj->object_to_json($user);
			$jsonid=$utilityobj->jsondata_add($jsondata);
			$dbobj->dbinsert("INSERT INTO ".tbl_socketdb::tblname." (".tbl_socketdb::col_uid.",".tbl_socketdb::col_jsonid.") VALUES ('$uid','$jsonid')", tbl_socketdb::dbname);
		}
	}
	
	/**
	 * 
	 * Remove a socket connection of a user
	 * @param unknown $uid UID of the user
	 */
	public function socket_remove_user($uid)
	{
		?>
		<script type="text/javascript">
			if(typeof window.socket_user!=='undefined')
			{
				var comobj=new JS_COMMUNICATION();
				comobj.socket_quit(window.socket_user);
			}
		</script>
		<?php
	}
	
	/**
	 * 
	 * REMOVE A USER FROM SOCKET DB
	 * @param unknown $user USER object
	 * @param unknown $msgobj Message Object
	 */
	public function socket_remove_user_db($user,$msgobj)
	{
		if(isset($msgobj->uid))$uid=$msgobj->uid;
		if(isset($msgobj->mtype))$rtype=$msgobj->mtype;
		if(isset($msgobj->msg))$message=$msgobj->msg;
		if(isset($msgobj->target))$target=$msgobj->target;
		$dbobj=new ta_dboperations();
		return $dbobj->dbdelete("DELETE FROM ".tbl_socketdb::tblname." WHERE ".tbl_socketdb::col_uid."='$uid'", tbl_socketdb::dbname);
	}
	
	/**
	 * 
	 * CHECK WHETHER A USER EXISTS IN SOCKET DB
	 * @param unknown $uid UID of the user
	 */
	public function socket_check_user($uid)
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_socketdb::tblname." WHERE ".tbl_socketdb::col_uid."='$uid'", tbl_socketdb::dbname);
		if(count($res)==0)
			return BOOL_FAILURE;
		else
			return BOOL_SUCCESS;
	}
	
	public function socket_getsocket_user($uid)
	{
		
	}
	
	/**
	 * 
	 * Send message through socket to the server
	 * @param unknown $msg The message sent as JSON string
	 */
	public function socket_sendnotif($msg,$socket="")
	{
		if($socket=="")
		{
			$sockvar="window.socket_user";
		}
		else
		{
			$sockvar=$socket;
		}
		
	return '<script type="text/javascript">var retries=0;var myfunc=function(){
					if(typeof window.socket_user!=="undefined"){console.log("sent successfully");var comobj=new JS_COMMUNICATION();comobj.socket_send('.$sockvar.',"'. $msg.'");}
	 		else{
					console.log("failed");retries++;if(retries<5)setTimeout(myfunc,2000);
				}
		};myfunc();</script>';
	}
	
	/**
	 * 
	 * Process an incoming JSON message
	 * @param unknown $user The user object
	 * @param unknown $msg The message as json string
	 * 
	 * Mytpe - (1-Init UID for connected user, 2-Remove UID from socket array,3-Send notification/msg,4-Receive notification/msg)
	 */
	public function process_message($sender,$msg,&$clients_con,&$uidlink)
	{
		$utilityobj=new ta_utilitymaster();
		$logobj=new ta_logs();
		
		$msgobj=$utilityobj->json_to_object($msg);
		$uid=$mtype=$message=$target="";
		if(isset($msgobj->uid))$uid=$msgobj->uid;
		if(isset($msgobj->mtype))$mtype=$msgobj->mtype;
		if(isset($msgobj->msg))$message=$msgobj->msg;
		if(isset($msgobj->target))$target=$msgobj->target;
		
		switch($mtype)
		{
			case "1":$uidlink[$uid]=$sender;break;
			case "2":$uidlink[$uid]="";unset($uidlink[$uid]);break;
			case "3":$this->socket_sendnotification($sender,$msg,$clients_con,$uidlink);break;
			case "4":;break;
			default:;break;			
		}
	}
	
	/**
	 * 
	 * GET ALL USERS FROM SOCKET DB
	 * @param unknown $start Start Limit
	 * @param unknown $tot Number of results from start limit
	 */
	public function socket_getallusers($start,$tot)
	{
		$dbobj=new ta_dboperations();
		return $dbobj->dbquery("SELECT * FROM ".tbl_socketdb::tblname." LIMIT ".$start.",".$tot, tbl_socketdb::dbname);
	}

	public function socket_sendnotification($sender,$msg,&$clients_con,&$uidlink)
	{
		$utilityobj=new ta_utilitymaster();
		$logobj=new ta_logs();
		
		$msgobj=$utilityobj->json_to_object($msg);
		$uid=$mtype=$message=$target="";
		
		$recuid=$msgobj->target;
		$msgobj->mtype="4";
		
		$newmsg=$utilityobj->object_to_json($msgobj);
		
		foreach ($clients_con as $client) 
		{
			if($client===$uidlink[$recuid])
			{
				$client->send($newmsg);
				$logobj->store_templogs("FOUND DEST:".$recuid);
			}
		}
	}
	
	public function socket_getnotification($user,$msg,&$sclass)
	{
		/*$utilityobj=new ta_utilitymaster();
		$msgobj=$utilityobj->json_to_object($msg);
		$uid=$mtype=$message=$target="";
		if(isset($msgobj->uid))$uid=$msgobj->uid;
		$user=$sclass->getUserByid($uid);
		$usock=$user->socket;
		$this->socket_sendmessage($msg,$usock);*/
	}
	
}
