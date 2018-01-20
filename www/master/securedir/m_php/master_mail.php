<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO MAILING
 * @author T.V.VIGNESH
 *
 */
class ta_mailclass
{
	/**
	 * CHECKS FOR SPAM
	 *
	 * @param string $field Field for which SPAM must be checked
	 * @return boolean True on valid field, False on invalid field
	 */
	public function spamcheck($field)
	{
		$sanitized_m = filter_var($field, FILTER_SANITIZE_EMAIL);
		if (filter_var($sanitized_m, FILTER_VALIDATE_EMAIL)) {
			return $sanitized_m;
		}
		else
		{
			return BOOL_FAILURE;
		}
		
		
		/*$field=filter_var($field, FILTER_SANITIZE_EMAIL);
		if(filter_var($field, FILTER_VALIDATE_EMAIL))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}*/
	}
	
	
	
	
	/**
	 * SENDS A MAIL
	 *
	 * @param string $to TO ADDRESS
	 * @param string $msg Message to be sent
	 * @param string $subject Subject of the mail (Default:TECH AHOY ALERTS)
	 * @param string $duplicate Allow duplicate e-mails or not ("1"-allow,"2"-disallow) Defaults to "1"
	 * @param string $from From address of the mail (Default:no-reply@techahoy.com)
	 * @param string $unsublink Unsubscription Link (Defaults to "")
	 * @return string
	 */
	public function sendmail($to,$msg,$subject="TECH AHOY ALERTS",$duplicate="1",$threadid="unknown",$from="no-reply@techahoy.com",$attach="",$unsublink="")
	{
		
		
		
		
		$logobj=new ta_logs();
		
		$eol="\r\n";//TODO doubt in code here
		$separator=DIRECTORY_SEPARATOR;//TODO doubt in code here
		if($attach!="")
		{
			$file = $attach;
			$file_size = filesize($file);
			$handle = fopen($file, "r");
			$content = fread($handle, $file_size);
			fclose($handle);
			
			$content = chunk_split(base64_encode($content));
		}
		else
		{
			$content="";
		}
		
		$from_mail='no-reply@'.str_replace('www.', '', $_SERVER['SERVER_NAME']);
		$from_name='TECH AHOY';
		$replyto='webadmin@friendbus.com';
		$body=$msg;
		$filename="";
		
		
		$type = 'html'; 
		$charset = 'utf-8';
		
		$uid = md5(uniqid(time()));
		
		$eol = PHP_EOL;
		
		
		$name="TECH AHOY ALERTS";
		
		require_once('class.phpmailer.php');
		$mail             = new PHPMailer();
		$body             = $msg;
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->Host       = "smtp.gmail.com";
		$mail->Port       = 587;
		$mail->Username   = "vigneshviswam@gmail.com";
		$mail->Password   = "vicrosoftpista@123";
		$mail->SMTPSecure = 'tls';
		$mail->SetFrom('youraccount@gmail.com', 'Your name');
		$mail->AddReplyTo("youraccount@gmail.com","Your name");
		$mail->Subject    = $subject;
		$mail->AltBody    = "Any message.";
		$mail->MsgHTML($body);
		$address = $to;
		$mail->AddAddress($address, $name);
		if(!$mail->Send()) {
			return FAILURE;
		} else {
			return SUCCESS;
		}
		
		
		
		
		
		
		/*$header = "From: ".$from_name." <".$from_mail.">".$eol;
		$header .= "Reply-To: ".$replyto.$eol;
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"";
		$message = "--".$uid.$eol;
		$message .= "Content-Type: text/".$type."; charset=ISO-8859-1".$eol;
		$message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
		$message .= $body.$eol;
		if($attach!="")
		{
			$message .= "--".$uid.$eol;
			$message .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
			$message .= "Content-Transfer-Encoding: base64".$eol;
			$message .= "Content-Disposition: attachment; filename=\"".$filename."\"".$eol;
			$message .= $content.$eol;
			$message .= "--".$uid."--";
		}
		

		$mailobj=new ta_mailclass();
		$to=$mailobj->spamcheck($to);
		if ($to==BOOL_FAILURE)
		{
			return FAILURE;
		}
		else
		{
			
			$logobj->store_templogs("SENDING MAIL TO:".$to."..".$subject."..".$msg."..".$header);
			
			$msg.="<br><br>To unsubscribe from these mail alerts: ".$unsublink;
			if(mail($to, "Subject: $subject",$message, $header))
			{
				return SUCCESS;
			}
			else
			{
				$logobj->store_templogs(print_r(error_get_last()));
				throw new Exception('#ta@0000000_0000066');
				return BOOL_FAILURE;
			}
		}*/
	}

	/**
	 *
	 * LOGIN TO AN IMAP MAIL SERVER
	 * @param unknown $uname User Name for the account
	 * @param unknown $password Password for the account
	 * @param string $mailbox Mailbox to check
	 * @return resource The mailobject to be used for processing, BOOL_FAILURE on failure
	 */
	public function login_imap($uname,$password,$mailbox=MAILBOX_DEFAULT)
	{
		if($mailobj=imap_open($mailbox,$uname,$password))
		{
			return $mailobj;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 * CLOSE IMAP SERVER CONNECTION
	 * @param unknown $mailobj
	 */
	public function close_imap($mailobj)
	{
		return imap_close($mailobj);
	}

	/**
	 *
	 * GET NAME OF THE MAIL BOX
	 * @param unknown $mailobj
	 */
	public function mboxname_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Mailbox;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET NUMBER OF MESSAGES IN THE MAILBOX
	 * @param unknown $mailobj The mail object
	 * @return integer The no of messages
	 */
	public function noofmessages_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Nmsgs;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET NUMBER OF UNREAD MESSAGES IN THE MAILBOX
	 * @param unknown $mailobj The mail object
	 */
	public function noofunread_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Unread;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET NUMBER OF RECENT MESSAGES IN THE MAILBOX
	 * @param unknown $mailobj The mail object
	 */
	public function noofrecent_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Recent;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET NUMBER OF DELETED MESSAGES IN THE MAILBOX
	 * @param unknown $mailobj The mail object
	 */
	public function noofdeleted_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Deleted;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET THE DATE THE MAILBOX WAS CHANGED LAST
	 * @param unknown $mailobj The mail object
	 */
	public function date_lastchange_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Date;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET THE DRIVER OF THE MAILBOX
	 * @param unknown $mailobj The mail object
	 */
	public function driver_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Driver;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * GET THE SIZE OF THE MAILBOX
	 * @param unknown $mailobj The mail object
	 */
	public function sizembox_imap($mailobj)
	{
		$check = imap_mailboxmsginfo($mailobj);
		if ($check)
		{
			return $check->Size;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * RETURNS ALL MAILBOXES IN THE SERVER
	 * @param unknown $mailobj Mail object
	 * @param string $server Server MAIL URL defaults to "{mail.techahoy.com:143}"
	 * @return multitype: An array of mailboxes.
	 * eg. Array
(
    [0] => {imap.example.com}Calendar
    [1] => {imap.example.com}Contacts
    [2] => {imap.example.com}Deleted Items
    [3] => {imap.example.com}Drafts
    [4] => {imap.example.com}Journal
    [5] => {imap.example.com}Junk E-mail
    [6] => {imap.example.com}Notes
    [7] => {imap.example.com}Outbox
    [8] => {imap.example.com}RSS Feeds
    [9] => {imap.example.com}Sent Items
    [10] => {imap.example.com}Tasks
)
	 */
	public function listmbox_imap($mailobj,$server=MAILSERVER_DEFAULT)
	{
		$boxes = imap_list($mailobj,$server,'*');
		return $boxes;
	}

	/**
	 *
	 * SEARCH THE MAILBOX FOR GIVEN CONDITIONS
	 * @param unknown $mailobj The mail object
	 * @param unknown $searchstr The search string (Criteria here:http://www.php.net/manual/en/function.imap-search.php)
	 * @param string $options Options to the Search eg.for returning UID instead of MsgID (SE_UID)
	 * @param string $charset Charset (Defaults to "") can be used when searching other languages
	 * @return multitype: False if search not understood or no mails found, else returns array of matching msg indexes
	 */
	public function searchmbox_imap($mailobj,$searchstr,$options=NULL,$charset=NULL)
	{
		return imap_search($mailobj,$searchstr,$charset);
	}

	/**
	 *
	 * MARK A MESSAGE FOR DELETION FROM THE MAILBOX
	 * @param unknown $mailbox The mail object
	 * @param unknown $index Index of the message (Defaults to msgno)
	 * @param string $options Options to the message
	 * @return boolean True on success
	 */
	public function delete_mark_imap($mailbox,$index,$options=NULL)
	{
		return imap_delete($mailbox,$index,$options);
	}

	/**
	 *
	 * REMOVE MARK FROM A MESSAGE MARKED FOR DELETION
	 * @param unknown $mailbox The mail object
	 * @param unknown $msgno Message Number of the message to be unmarked for deletion
	 * @return boolean True on success, False on failure
	 */
	public function delete_removemark_imap($mailbox,$msgno)
	{
		return imap_undelete($mailbox,$msgno);
	}

	/**
	 *
	 * DELETE MESSAGES MARKED FOR DELETION
	 * @param unknown $mailbox The mail object
	 * @return boolean True on success
	 */
	public function delete_markedmsg_imap($mailbox)
	{
		return imap_expunge($mailbox);
	}

	/**
	 *
	 * MOVE A MESSAGE FROM ONE MAILBOX TO ANOTHER
	 * @param unknown $mailobj The mail object
	 * @param unknown $msgset The message set to be deleted: eg."1,3,5,7,8"  (To be given as string)
	 * @param unknown $destination Destination of the message (Folder or mailbox)
	 */
	public function move_mail_imap($mailobj,$msgset,$destination)
	{
		if (!(empty($msgset)))
		{
			$messageSetImpl = implode (",",$msgset);
			imap_mail_move($mailobj,$messageSetImpl,$destination);
		}
	}

	/**
	 *
	 * COPY A MESSAGE FROM ONE MAILBOX TO ANOTHER
	 * @param unknown $mailobj The mail object
	 * @param unknown $msgset The message set to be deleted: eg."1,3,5,7,8"  (To be given as string)
	 * @param unknown $destination Destination of the message (Folder or mailbox)
	 */
	public function copy_mail_imap($mailbox,$msgset,$destination)
	{
		if (!(empty($msgset)))
		{
			$messageSetImpl = implode (",",$msgset);
			imap_mail_copy($mailbox,$messageSetImpl,$destination);
		}
	}

	/**
	 *
	 * SETS FLAGS TO MESSAGES
	 * @param unknown $mailbox The mailbox object
	 * @param unknown $msgset The set of msg nos. eg. "2,5"
	 * @param unknown $flags Flags to be set on the message (\Seen, \Answered, \Flagged, \Deleted, and \Draft) eg. "\\Seen \\Flagged"
	 * @return boolean True on success, false on failure
	 */
	public function flag_msg_imap($mailbox,$msgset,$flags)
	{
		return imap_setflag_full($mailbox,$msgset,$flags);
	}

	/**
	 *
	 * REMOVES FLAGS FROM MESSAGES
	 * @param unknown $mailbox The mailbox object
	 * @param unknown $msgset The set of msg nos. eg. "2,5"
	 * @param unknown $flags Flags to be removed from the message (\Seen, \Answered, \Flagged, \Deleted, and \Draft) eg. "\\Seen \\Flagged"
	 * @return boolean True on success, false on failure
	 */
	public function flag_clear_imap($mailbox,$msgset,$flag)
	{
		return imap_clearflag_full($mailbox,$msgset,$flag);
	}

	/**
	 *
	 * SUBSCRIBE TO A MAILBOX
	 * @param unknown $mailobj The mail object
	 * @param unknown $mailbox The mailbox to which subscription is made. eg."{your.host:143}INBOX"
	 * @return boolean True on succes, false on failure
	 */
	public function subscribe_mbox_imap($mailobj,$mailbox)
	{
		return imap_subscribe($mailobj,$mailbox);
	}

	/**
	 *
	 * UNSUBSCRIBE FROM A MAILBOX
	 * @param unknown $mailobj The mail object
	 * @param unknown $mailbox The mailbox from which subscription is removed. eg."{your.host:143}INBOX"
	 * @return boolean True on succes, false on failure
	 */
	public function unsubscribe_mbox_imap($mailobj,$mailbox)
	{
		return imap_unsubscribe($mailobj,$mailbox);
	}

	/**
	 *
	 * REMOVES REPLY OR FORWARD LABELS FROM A MESSAGE's SUBJECT
	 * @param unknown $subjectstr The subject string
	 * @return string The resultant string after removal
	 */
	public function remove_replyforward($subjectstr)
	{
		$subjectstr = trim(preg_replace("/Re\:|re\:|RE\:|Fwd\:|fwd\:|FWD\:/i", '', $subjectstr));
		return $subjectstr;
	}

	/**
	 *
	 * GETS MESSAGE'S OVERVIEW
	 * @param unknown $mailobject The mail object
	 * @param unknown $msgset The set of msg nos. eg. "2,5"
	 * @param number $options The options to this message (Defaults to 0)
	 * @return multitype: An object having the complete message overview with properties: subject - the messages subject
	from - who sent it
	to - recipient
	date - when was it sent
	message_id - Message-ID
	references - is a reference to this message id
	in_reply_to - is a reply to this message id
	size - size in bytes
	uid - UID the message has in the mailbox
	msgno - message sequence number in the mailbox
	recent - this message is flagged as recent
	flagged - this message is flagged
	answered - this message is flagged as answered
	deleted - this message is flagged for deletion
	seen - this message is flagged as already read
	draft - this message is flagged as being a draft
	 */
	public function getmsgoverview_imap($mailobject,$msgset,$options=0)
	{
		return imap_fetch_overview($mailobject,$msgset,$options);
	}

	/**
	 *
	 * GETS COMPLETE HEADERS OF A MESSAGE
	 * @param unknown $mailobject The mail object
	 * @param unknown $msgno Message number of the message whose headers is to be retrieved
	 * @return object The header information as an object (For returned properties, see: http://www.php.net/manual/en/function.imap-headerinfo.php)
	 */
	public function getheaders_imap($mailobject,$mid)
	{
		return imap_headerinfo($mailobject,$mid);
	}

	/**
	 *
	 * CLEARS IMAP CACHE
	 * @param unknown $mailobject The mail object
	 * @param string $caches Refer http://www.php.net/manual/en/function.imap-gc.php
	 * @return boolean True on success, false on failure
	 */
	public function clearcache_imap($mailobject,$caches=IMAP_GC_ELT)
	{
		return imap_gc($mailobject,$caches);
	}

	/**
	 *
	 * GETS A MESSAGE AND PROCESSESS IT
	 * @param unknown $mbox The mailbox object
	 * @param unknown $mid The Message ID
	 */
	public function getmsg_imap($mbox,$mid)
	{
		// input $mbox = IMAP stream, $mid = message id
		// output all the following:
		global $charset,$htmlmsg,$plainmsg,$attachments;
		$htmlmsg = $plainmsg = $charset = '';
		$attachments = array();

		// HEADER
		$h = imap_header($mbox,$mid);
		// add code here to get date, from, to, cc, subject...

		// BODY
		$s = imap_fetchstructure($mbox,$mid);
		$mailobj=new ta_mailclass();
		if (!$s->parts)  // simple
		{
			$mailobj->getpart_imap($mbox,$mid,$s,0);  // pass 0 as part-number
		}
		else
		{  // multipart: cycle through each part
			foreach ($s->parts as $partno0=>$p)
				$mailobj->getpart_imap($mbox,$mid,$p,$partno0+1);
		}
	}

	/**
	 *
	 * GET PARTS OF MESSAGE
	 * @param unknown $mbox The mailbox object
	 * @param unknown $mid The message ID
	 * @param unknown $p
	 * @param unknown $partno The part No
	 */
	public function getpart_imap($mbox,$mid,$p,$partno)
	{
		// $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
		global $htmlmsg,$plainmsg,$charset,$attachments;

		// DECODE DATA
		$data = ($partno)?
		imap_fetchbody($mbox,$mid,$partno):  // multipart
		imap_body($mbox,$mid);  // simple
		// Any part may be encoded, even plain text messages, so check everything.
		if ($p->encoding==4)
			$data = quoted_printable_decode($data);
		elseif ($p->encoding==3)
		$data = base64_decode($data);

		// PARAMETERS
		// get all parameters, like charset, filenames of attachments, etc.
		$params = array();
		if ($p->parameters)
		foreach ($p->parameters as $x)
			$params[strtolower($x->attribute)] = $x->value;
		if ($p->dparameters)
		foreach ($p->dparameters as $x)
			$params[strtolower($x->attribute)] = $x->value;

		// ATTACHMENT
		// Any part with a filename is an attachment,
		// so an attached text file (type 0) is not mistaken as the message.
		if ($params['filename'] || $params['name']) {
			// filename may be given as 'Filename' or 'Name' or both
			$filename = ($params['filename'])? $params['filename'] : $params['name'];
			// filename may be encoded, so see imap_mime_header_decode()
			$attachments[$filename] = $data;  // this is a problem if two files have same name
		}

		// TEXT
		if ($p->type==0 && $data) {
			// Messages may be split in different parts because of inline attachments,
			// so append parts together with blank row.
			if (strtolower($p->subtype)=='plain')
				$plainmsg.= trim($data) ."\n\n";
			else
				$htmlmsg.= $data ."<br><br>";
			$charset = $params['charset'];  // assume all parts are same charset
		}

		// EMBEDDED MESSAGE
		// Many bounce notifications embed the original message as type 2,
		// but AOL uses type 1 (multipart), which is not handled here.
		// There are no PHP functions to parse embedded messages,
		// so this just appends the raw source to the main message.
		elseif ($p->type==2 && $data) {
			$plainmsg.= $data."\n\n";
		}

		// SUBPART RECURSION
		if ($p->parts)
		{
			$mailobj=new ta_mailclass();
			foreach ($p->parts as $partno0=>$p2)
				$mailobj->getpart_imap($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
		}
	}

	/**
	 *
	 * CREATE A NEW MAIL BOX
	 * @param unknown $mailobj The mail object
	 * @param unknown $newmailbox The new folder in INBOX eg."{imap.example.org}INBOX.foldername"
	 * @return string True on success, False on failure
	 */
	public function createmailbox_imap($mailobj,$newmailbox)
	{
		if (@imap_createmailbox($mailobj,imap_utf7_encode($newmailbox)))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * DELETES AN EXISTING MAILBOX
	 * @param unknown $mailobj The mail object
	 * @param unknown $new The folder to be deleted eg."{imap.example.org}INBOX.foldername"
	 * @return True on success,False on failure
	 */
	public function deletemailbox_imap($mailobj,$newmboxname)
	{
		if (@imap_deletemailbox($mailobj,$newmboxname))
		{
			return BOOL_SUCCESS;
		}
		else
		{
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * RENAME A MAILBOX
	 * @param unknown $mailobj The mail object
	 * @param unknown $oldmbox The old mailbox to be renamed eg.'{imap.example.com}INBOX.Foo'
	 * @param unknown $newmbox The new name for the mailbox eg.'{imap.example.com}INBOX.Bar'
	 * @return boolean True on success, false on failure
	 */
	public function renamemailbox_imap($mailobj,$oldmbox,$newmbox)
	{
		return imap_renamemailbox($mailobj,$oldmbox,$newmbox);
	}

	public function getlasterror_imap()
	{
		return imap_last_error();
	}

	/**
	 *
	 * EXTRACTS ATTACHMENTS ALONE FROM A MAIL
	 * @param unknown $mailbox The mail object
	 * @param unknown $msg_number The message no for which the attachments are to be extracted
	 * @param unknown $dir The directory where the attachments have to be saved
	 * @return boolean|unknown True on success, False on failure
	 */
	public function extractattachments_imap($mailbox,$msg_number,$dir)
	{
		if (!file_exists($dir)){
			mkdir($dir);
		}
		$filename = "tmp.eml";
		$email_file = $dir."/".$filename;
		// write the message body to disk
		imap_savebody  ($mailbox, $email_file, $msg_number);
		$command = "munpack -C $dir -fq $email_file";
		// invoke munpack which will
		// write all the attachments to $dir
		exec($command,$output);

		// if($output[0]!='Did not find anything to unpack from $filename') {
		$found_file = false;
		foreach ($output as $attach) {
			$pieces = explode(" ", $attach);
			$part = $pieces[0];
			if (file_exists($dir.$part)){
				$found_file = true;
				$files[] = $part;
			}
		}
		if (!$found_file){
			// didn't find any output files - delete the directory and email file
			unlink($email_file);
			rmdir($dir);
			return false;
		}
		else {
			// found some files-  just delete the email file
			unlink($email_file);
			return $files;
		}
	}

	/**
	 *
	 * CHECKS WHETHER A MESSAGE IS MULTIPART OR NOT
	 * @param unknown $structure The structure of the message
	 * @return boolean True if multipart,False if not multipart
	 */
	public function check_type_imap($structure) ## CHECK THE TYPE
	{
	  if($structure->type == 1)
	    {
	     return(true); ## YES THIS IS A MULTI-PART MESSAGE
	    }
	 else
	    {
	     return(false); ## NO THIS IS NOT A MULTI-PART MESSAGE
	    }
	}

	/**
	 *
	 * RETRIEVE A MESSAGE FROM THE MESSAGE BOX
	 * @param unknown $mbox The message box stream
	 * @param unknown $messageid The message no
	 * @return multitype:string NULL An array having indexes ('subject','fromaddress','toaddress','ccaddress','date','body')
	 */
	public function retrieve_message($mbox, $messageid)
	{
	   $message = array();

	   $header = imap_header($mbox, $messageid);
	   $structure = imap_fetchstructure($mbox, $messageid);

	   $message['subject'] = $header->subject;
	   $message['fromaddress'] =   $header->fromaddress;
	   $message['toaddress'] =   $header->toaddress;
	   $message['ccaddress'] =   $header->ccaddress;
	   $message['date'] =   $header->date;

	   $mailobject=new ta_mailclass();
	  if ($mailobject->check_type($structure))
	  {
	   $message['body'] = imap_fetchbody($mbox,$messageid,"1"); ## GET THE BODY OF MULTI-PART MESSAGE
	   if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
	  }
	  else
	  {
	   $message['body'] = imap_body($mbox, $messageid);
	   if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
	  }

	  return $message;
	}

	/**
	 *
	 * RETURNS THE MIME TYPE WHEN STRUCTURE OF MESSAGE IS PASSED AS A PARAMETER
	 * @param unknown $structure The structure of the message
	 * @return string The MIME type of the message
	 */
	public function get_mime_type_imap(&$structure)
	{
	    $primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
	    if($structure->subtype)
	    {
	      return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
	    }
	     return "TEXT/PLAIN";
	}

	/**
	 *
	 * GET STRUCTURE OF A MESSAGE
	 * @param unknown $stream The message stream
	 * @param unknown $msgno The message number for which the structure is to be retrieved
	 * @return mixed The structure of the message
	 */
	public function getstructure_imap($stream,$msgno)
	{
		return imap_fetchstructure($stream,$msgno);
	}

	/**
	 *
	 * SET QUOTA FOR AN USER'S MAILBOX
	 * @param unknown $mbox The mail stream
	 * @param unknown $root The root user "user.kalowsky"
	 * @param unknown $limit The limit of the quota
	 * @return string True on success, False on failure
	 */
	public function setquota_imap($mbox,$root,$limit)
	{
		if (!imap_set_quota($mbox,$root,$limit))
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}

	/**
	 *
	 * REMOVE A QUOTA WHICH IS SET
	 * @param unknown $mbox The mail stream
	 * @param unknown $root The root user "user.kalowsky"
	 * @param unknown $limit The limit of the quota (Defaults to -1)
	 * @return string True on success,false on failure
	 */
	public function removequota_imap($mbox,$root,$limit=-1)
	{
		if(!imap_set_quota($mbox,$root,$limit))
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}

	/**
	 *
	 * CHECK IF AN IMAP STREAM IS ACTIVE
	 * @param unknown $mailobj The mail object
	 * @return string True if stream is active,false if not active
	 */
	public function checkactivity_imap($mailobj)
	{
		if (!imap_ping($mailobj))
		{
			return BOOL_FAILURE;
		}
		else
		{
			return BOOL_SUCCESS;
		}
	}

	/**
	 *
	 * SETS AN IMAP TIME OUT
	 * @param unknown $time The time for the timeout to be set (in seconds)
	 * @return mixed True on success,false on failure
	 */
	public function settimeout_imap($time)
	{
		return imap_timeout(IMAP_WRITETIMEOUT,$time);
	}

	/**
	 *
	 * GETS THE TIME OUT CURRENTLY SET
	 * @return mixed The timeout set in seconds
	 */
	public function gettimeout_imap()
	{
		return imap_timeout(IMAP_READTIMEOUT);
	}

	/**
	 *
	 * CLOSE THE IMAP TIMEOUT
	 * @return mixed
	 */
	public function closetimeout_imap()
	{
		return imap_timeout(IMAP_CLOSETIMEOUT);
	}

	/**
	 *
	 * OPEN A TIME OUT
	 * @return mixed True on success, False on failure
	 */
	public function opentimeout_imap()
	{
		return imap_timeout(IMAP_OPENTIMEOUT);
	}

	/**
	 *
	 * GET UID OF A MESSAGE
	 * @param unknown $msgobj The message object
	 * @param unknown $msgno The message number
	 * @return number The UID of the message
	 */
	public function getuid_imap($msgobj,$msgno)
	{
		return imap_uid($msgobj,$msgno);
	}

	/**
	 *
	 * GET MSG NO OF A MESSAGE
	 * @param unknown $msgobj The message object
	 * @param unknown $uid The UID of the message
	 * @return number The message no of the message
	 */
	public function getmsgno_imap($msgobj,$uid)
	{
		return imap_msgno($msgobj,$uid);
	}
}