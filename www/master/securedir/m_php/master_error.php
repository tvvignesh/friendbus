<?php
/**
 *
 * CONTAINS ALL ERROR HANDLING FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_errorhandle
{
	/**
	 *
	 * ERROR HANDLING FUNCTION
	 *
	 * @param string $custerrormsg  Custom error message sent by developer Default:OOPS! An error occurred!
	 * @param string $errno  Error number sent by developer or script Default:"0"
	 * @param string $errmsg  Script default error message Default:""
	 * @param string $priority Level of error ("1" terminates script,"2" gives error but runs script ) Default:"1"
	 * @param string $callback1 URL of page where request has to be redirected on clicking btn 1 (Defaults to goback which goes one step back to history)
	 * @param string $ctext1 Text to be displayed on button 1 (Defaults to Go Back)
	 * @param string $callback2  URL of page where request has to be redirected on clicking btn 2 (Defaults to index.php which goes to the current app's homepage)
	 * @param string $ctext2 Text to be displayed on button 2 (Defaults to Go to Home Page)
	 * @param string $errtitle Title of error box Text to be displayed in error box titlebar (Defaults to OOPS! An Error Occured!)
	 * @return void Does not return anything
	 */
	public function senderror($custerrormsg="OOPS! An error occurred! ",$errno="0",$errmsg="",$priority="1",$callback1="goback",$ctext1="Go Back!",$callback2="index.php",$ctext2="Go to Home Page",$errtitle="OOPS! An Error Occured!")
	{
		echo 
		'
				<script type="text/javascript">
					function er_btn(cb)
					{
						if(cb=="goback")
						{
							history.go(-1);
						}
						else
						{
							window.location.assign(cb);
						}
					}
				</script>
		';
		if($priority=="1")
		{
			$userobj=new ta_userinfo();
			if($userobj->checklogin())
			{
				$uid=$userobj->uid;
			}
			else
			{
				$uid="";
			}
			error_log(time()."  ".$errno."  UID:".$uid."\r\n",3,ROOT_SERVER."/master/securedir/php-errors.log");
			ob_end_flush();
			die('
					<div id="errmsg">
						<div id="errbox_title">'.$errtitle.'</div>
						<div id="errbox_content">
							<div id="errbox_custmsg">'.$custerrormsg.'</div> <div id="errbox_msg">'.$errmsg.'</div> <div id="errbox_errcode">ERROR CODE:<div id="errbox_errno">'.$errno.'</div></div>
						</div>

						<div style="position:relative;left:35%;">
							<div class="talboxbtn" align="center" id="err_okaybtn" onclick=\'er_btn("'.$callback1.'");\'><input type="button" value="'.$ctext1.'"></div>
							<div class="talboxbtn" align="center" id="err_outbtn" onclick=\'er_btn("'.$callback2.'");\'><input type="button" value="'.$ctext2.'"></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				');
		}
		else
		if($priority=="2")
		{
			$userobj=new ta_userinfo();
			if($userobj->checklogin())
			{
				$uid=$userobj->uid;
			}
			else
			{
				$uid="";
			}
			error_log(time()."  ".$errno."  UID:".$uid."\r\n",3,ROOT_SERVER."/securedir/php-errors.log");

			echo '
					<div id="errmsg">
						<div id="errbox_title">'.$errtitle.'</div>
						<div id="errbox_content">
							<div id="errbox_custmsg">'.$custerrormsg.'</div> <div id="errbox_msg">'.$errmsg.'</div> <div id="errbox_errcode">ERROR CODE:<div id="errbox_errno">'.$errno.'</div></div>
						</div>

						<div style="position:relative;left:35%;">
							<div class="talboxbtn" align="center" id="err_okaybtn" onclick=\'er_btn("'.$callback1.'");\'><input type="button" value="'.$ctext1.'"></div>
							<div class="talboxbtn" align="center" id="err_outbtn" onclick=\'er_btn("'.$callback2.'");\'><input type="button" value="'.$ctext2.'"></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				';
		}
	}

	/**
	 *
	 * HANDLES EXCEPTIONS WHEN THROWN AND DISPLAYS APPROPRIATE ERROR MESSAGES BY FETCHING FROM DATABASE
	 * @param unknown_type $e The Exception object throwing the error code
	 */
	public static function exceptionhandler($e)
	{
		$utilityobj=new ta_utilitymaster();
		$logobj=new ta_logs();
		
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();

		$custerrcode=$e->getMessage();
		$logobj->store_templogs("ERR:".$custerrcode." @ line ".$e->getLine()." in file:".$e->getFile());
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_errorcodes::tblname." WHERE ".tbl_errorcodes::col_errcode."='$custerrcode' LIMIT 0,1",tbl_errorcodes::dbname);

		if($res==EMPTY_RESULT)
		{
			$errobj1=new ta_errorhandle();
			$errobj1->senderror("OOPS! An Unknown Error occured!","Unknown");
		}
		else
		{
			$errmsg=$res[0][changesqlquote(tbl_errorcodes::col_errdesc,"")];
			$errpriority=$res[0][changesqlquote(tbl_errorcodes::col_errpriority,"")];
			$errcallback1=$res[0][changesqlquote(tbl_errorcodes::col_errcallback1,"")];
			$errcalltext1=$res[0][changesqlquote(tbl_errorcodes::col_errcallbacktext1,"")];
			$errcallback2=$res[0][changesqlquote(tbl_errorcodes::col_errcallback2,"")];
			$errcalltext2=$res[0][changesqlquote(tbl_errorcodes::col_errcallbacktext2,"")];
			$errtitle=$res[0][changesqlquote(tbl_errorcodes::col_errtitle,"")];
			
			$errobj1=new ta_errorhandle();
			$errobj1->senderror($errmsg,$custerrcode,"",$errpriority,$errcallback1,$errcalltext1,$errcallback2,$errcalltext2,$errtitle);
		}
	}
}

?>