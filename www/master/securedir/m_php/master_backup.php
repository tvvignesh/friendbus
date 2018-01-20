<?php
/**
 * 
 * CONTAINS ALL BACKUP FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_backupoperations
{
	/**
	 * 
	 * BACKUP GALLERY AND RENDER IN GIVEN FORMAT (CONTAINS ALL DOCUMENTS & PICS EXCEPT PROFILE PIC)
	 * @param unknown_type $uid User id of person generating backup
	 * @param unknown_type $format Format of backup file (Defaults to zip)
	 * @return string The path of backup file on generation
	 */
	public function backupgallery($uid,$format="zip")
	{
		$archivename="Backup_".$userobj->uname."_".time();
		$userobj=new ta_userinfo();
		if($userobj->userinit()!="SUCCESS")
		{
			throw new Exception('#ta@0000000_0000023');
			return FAILURE;
		}
		else
		{

			$docroot=$userobj->docroot;
			$archivemaster=new ta_archive();
			if(!$archivemaster->compressfiles($archivename,$docroot,ROOT_BACKUP."/GALBACKUP",$format))
			{
				return FAILURE;
			}
			else
			{
				return ROOT_BACKUP."/GALBACKUP/".$archivename.".".$format;
			}
		}
	}

	/**
	 * 
	 * BACKUP USER'S PROFILE INFORMATION
	 * @param unknown_type $uid User ID of Person requesting backup
	 * @param unknown_type $format Format of Backup (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> Path of backup file
	 */
	public function backupprofile($uid,$format="txt")
	{
		$filename="ProfileBackup_".$userobj->uname."_".time().".txt";
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000024');
			return FAILURE;
		}
		$profileconts=var_export($userobj,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($profileconts,ROOT_BACKUP."/PROFILEBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000025');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}

	/**
	 * 
	 * BACKUP ALL FRIENDS OF A USER
	 * @param unknown_type $uid User ID of person requesting Backup
	 * @param unknown_type $format Format of Backup (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> Path of Backup File
	 */
	public function backupfriends($uid,$format="txt")
	{
		$socialobj=new ta_socialoperations();
		$friendinfo=$socialobj->getfriends($uid);
		$filename="FriendsBackup_".$userobj->uname."_".time().".txt";
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000026');
			return FAILURE;
		}
		$friendconts=var_export($friendinfo,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($friendconts,ROOT_BACKUP."/FRIENDSBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000027');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}

	/**
	 * 
	 * BACKUP  ALL THREAD OUTLINES OF A USER (NOT INCOMING THREADS)
	 * @param unknown_type $uid User ID of Person Requesting Backup
	 * @param unknown_type $format Format of Backup File (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> The path of Backup Generated
	 */
	public function backupuserthreadoutline($uid,$format="txt")
	{
		$msgobj=new ta_messageoperations();
		$threadinfo=$msgobj->getuserthreadoutline($uid);
		$filename="FriendsBackup_".$userobj->uname."_".time().".txt";
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000028');
			return FAILURE;
		}
		$threadconts=var_export($threadinfo,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($threadconts,ROOT_BACKUP."/THREADSOUTLINEBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000029');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}

	/**
	 * 
	 * BACKUP ALL THREAD CONTENTS OF A USER
	 * @param unknown_type $uid User ID of person requesting Backup
	 * @param unknown_type $format Format of Backup (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> The path of the backup file
	 */
	public function backupuserthreadcontents($uid,$format="txt")
	{
		$msgobj=new ta_messageoperations();
		$threadmsg=$msgobj->getuserthreadcontents($uid);
		$filename="FriendsBackup_".$userobj->uname."_".time().".txt";
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000065');
			return FAILURE;
		}
		$threadconts=var_export($threadmsg,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($threadconts,ROOT_BACKUP."/THREADSBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000030');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}

	/**
	 * 
	 * BACKUP ALL LIST OUTLINES CREATED BY A USER
	 * @param unknown_type $uid User ID of person requesting backup
	 * @param unknown_type $format Format of backup file (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> The path of the Backup file
	 */
	public function backuplistoutlines($uid,$format="txt")
	{
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000031');
			return FAILURE;
		}
		$socialobj=new ta_socialoperations();
		$listoutcontents=$socialobj->getalllistoutlines($uid);
		$filename="ListOutlineBackup_".$userobj->uname."_".time().".txt";
		$listoutconts=var_export($listoutcontents,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($listoutconts,ROOT_BACKUP."/LISTOUTLINEBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000032');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}
	
	/**
	 * 
	 * BACKUP LIST PARTICIPANTS GIVEN THE LISTID
	 * @param unknown_type $listid LISTID of List for which Backup is needed
	 * @param unknown_type $format Format of Backup File (Defaults to txt)
	 * @return string|Ambigous <boolean, string, string> The path of generated backup file
	 */
	public function backuplistparticipants($listid,$format="txt")
	{
		$userobj=new ta_userinfo();
		if($userobj->userinit()=="")
		{
			throw new Exception('#ta@0000000_0000033');
			return FAILURE;
		}
		$socialobj=new ta_socialoperations();
		$listpartcontents=$socialobj->getalllistparticipants($listid);
		$filename="ListParticipantBackup_".$userobj->uname."_".time().".txt";
		$listpartconts=var_export($listpartcontents,BOOL_SUCCESS);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile($listpartconts,ROOT_BACKUP."/LISTPARTICIPANTBACKUP/".$filename);
		if(!$res)
		{
			throw new Exception('#ta@0000000_0000034');
			return FAILURE;
		}
		else
		{
			return $res;
		}
	}
}