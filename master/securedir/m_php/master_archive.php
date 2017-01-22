<?php
/**
 *
 * CONTAINS ALL ARCHIVE FUNCTIONS
 * @author T.V.VIGNESH
 *
 */
class ta_archive
{
	//COMPRESS A FILE
	/**
	 *
	 * COMPRESS A FILE
	 * @param unknown_type $archivename Archive name
	 * @param unknown_type $filecollection Array of files to be compressed
	 * @param unknown_type $destination Complete Path (Destination)
	 * @param unknown_type $comptype Compression type (Defaults to zip)
	 * @return boolean/string "Compressed ".$no." STATUS:".$status." STATUS SYS:".statusSys;  false on failure
	 */
	public function compressfiles($archivename,$filecollection="",$destination="",$comptype="zip")
	{
		if($filecollection=="")
		{
			throw new Exception('#ta@0000000_0000016');
			return FAILURE;
		}
		if($comptype=="zip")
		{
			$zip=new ZipArchive();
			$filename = $destination."/".$archivename.".zip";
			if ($zip->open($filename, ZIPARCHIVE::CREATE)!==BOOL_SUCCESS) {
				throw new Exception('#ta@0000000_0000017');
				return BOOL_FAILURE;
			}
			foreach($filecollection as $file)
			{
				$fileobj=new ta_fileoperations();
				$filname=$fileobj->fileinfo($file,4);
				$ext=$fileobj->fileinfo($file,3);
				$zip->addFile($file, $filname.$ext);
			}
			$no=$zip->numFiles;
			$status=$zip->status;
			$zip->close();
			return "Compressed ".$no." STATUS:".$status." STATUS SYS:".$zip->statusSys;
		}
	}

	/**
	 *
	 * GETS INDEXES FROM ARCHIVE
	 * @param unknown_type $path Complete Path of the archive
	 * @return Array|boolean Returns all the files in the archive
	 */
	public function archiveindex($path)
	{
		$ftype=new ta_fileoperations();
		$ext=$ftype->fileinfo($path,3);
		if($ext=="zip")
		{
			$za = new ZipArchive();
			$za->open($path);
			for ($i=0; $i<$za->numFiles;$i++)
			{
				$filecollection[$i]=$za->statIndex($i);
			}
			return $filecollection;
		}
		else
		{
			throw new Exception('#ta@0000000_0000018');
			return BOOL_FAILURE;
		}
	}

	/**
	 *
	 * EXTRACTS AN ARCHIVE
	 * @param unknown_type $source Source of the file
	 * @param unknown_type $destination Destination of the file
	 * @return string|boolean False on failure, Extracted destination on success
	 */
	public function extractarchive($source,$destination)
	{
		$ftype=new ta_fileoperations();
		$ext=$ftype->fileinfo($source,3);
		$filname=$ftype->fileinfo($source,4);
		mkdir($destination."/".$filname, 0700);
		if($ext=="zip")
		{
			$zip = new ZipArchive;
			if ($zip->open($source) === BOOL_SUCCESS) {
				$zip->extractTo($destination."/".$filname);
				$zip->close();
				return $destination."/".$filname;
			}
			else
			{
				throw new Exception('#ta@0000000_0000019');
				return BOOL_FAILURE;
			}
		}
	}

	/**
	 *
	 * Renames an entry in the archive
	 * @param unknown_type $source Source of the archive (Complete Path)
	 * @param unknown_type $curname Current name of the file to be renamed
	 * @param unknown_type $newname New name of the file
	 * @return string|boolean false on failure, New name on success
	 */
	public function renameentry($source,$curname,$newname)
	{
		if($newname=="")
		{
			throw new Exception('#ta@0000000_0000020');
			return BOOL_FAILURE;
		}
		$ftype=new ta_fileoperations();
		$ext=$ftype->fileinfo($source,3);
		$filname=$ftype->fileinfo($source,4);
		if($ext=="zip")
		{
			$zip=new ZipArchive();
			$res = $zip->open($source);
			if ($res === BOOL_SUCCESS) {
				$zip->renameName($curname,$newname);
				$zip->close();
				return $newname;
			} else {
				throw new Exception('#ta@0000000_0000021');
				return BOOL_FAILURE;
			}
		}
	}

	/**
	 *
	 * Deletes an entry in the archive
	 * @param unknown_type $source Source of the archive (Complete path)
	 * @param unknown_type $name Name of the file to be deleted
	 * @param unknown_type $ftype File type (0-file,1-directory)
	 * @return string|boolean "SUCCESS" on success, false on failure
	 */
	public function deleteentry($source,$name,$ftype=0)
	{
		$ftype=new ta_fileoperations();
		$ext=$ftype->fileinfo($source,3);
		$filname=$ftype->fileinfo($source,4);
		if($ext=="zip")
		{
			$zip=new ZipArchive();
			$res = $zip->open($source);
			if ($res === BOOL_SUCCESS) {
				if($ftype==0)
				{$zip->deleteName($name);}
				else
				{$zip->deleteName($name."/");}
				$zip->close();
				return SUCCESS;
			} else {
				throw new Exception('#ta@0000000_0000022');
				return BOOL_FAILURE;
			}
		}
	}
}