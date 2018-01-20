<?php
/**
 *
 * CONTAINS FUNCTIONS RELATED TO FILE HANDLING
 * @author T.V.VIGNESH
 *
 */
class ta_fileoperations
{
	/**
	 *
	 * MOVE A FILE FROM ONE LOCATION TO ANOTHER
	 * @param string $source Source of the file (Relative or complete path)
	 * @param string $destination Destination of the file (Relative or complete path)
	 * @return boolean True on success false on failure
	 */
	public function movefile($source,$destination)
	{
		return move_uploaded_file($source,$destination);
	}

	/**
	 *
	 * DELETES A FILE
	 * @param string $file Path of the file to be deleted
	 * @return boolean True on success, false on failure
	 */
	public function deletefile($file)
	{
		return unlink($file);
	}

	/**
	 *
	 * COPY FILE FROM ONE LOCATION TO ANOTHER
	 * @param string $curfile Original file to be copied (With complete path)
	 * @param string $newfile Path of the file destination (With complete path)
	 * @return boolean|string Path of the new file copied
	 */
	public function copyfile($curfile,$newfile)
	{
		if (!copy($curfile,$newfile))
		{
			throw new Exception('#ta@0000000_0000036');
			return BOOL_FAILURE;
		}
		else
		{
			return $newfile;
		}
	}

	/**
	 *
	 * Renames a file
	 * @param string $oldname Old name of the file (With complete path)
	 * @param string $newname New name of the file (With complete path)
	 */
	public function rename($oldname,$newname)
	{
		if(!rename($oldname,$newname))
		{
			throw new Exception('#ta@0000000_0000037');
			return BOOL_FAILURE;
		}
		else
		{
			return $newname;
		}
	}

	/**
	 * 
	 * DELETES A FOLDER AND ALL FOLDERS AND FILE WITHIN
	 * @param unknown $dir The DIR path to be deleted
	 * @return boolean TRUE on success FALSE on failure
	 */
	public function delTree($dirPath) {
		if (! is_dir($dirPath)) {
        	throw new InvalidArgumentException("$dirPath must be a directory");
    	}
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::delTree($file);
	        } else {
	            unlink($file);
	        }
	    }
    	rmdir($dirPath);
	}
	
	/**
	 *
	 * Reads a file
	 * @param string $path Path of the file to be read
	 * @return string Returns the entire contents of the file
	 */
	public function readfile($path)
	{
		$handle = fopen($path,"r");
		$contents = fread($handle, filesize($path));
		fclose($handle);
		return $contents;
	}

	/**
	 *
	 * Reads a binary file
	 * @param string $path Path of the file to be read
	 * @return string The complete contents of the binary file
	 */
	public function readbinaryfile($path)
	{
		$handle = fopen($path, "rb");
		$contents = fread($handle, filesize($path));
		fclose($handle);
		return $contents;
	}

	/**
	 *
	 * Get contents from a FTP stream
	 * @param string $username Username
	 * @param string $password Password
	 * @param string $server Server(Host)
	 * @param string $file File
	 * @return string
	 */
	function ftp_get_contents($username,$password,$server,$file)
	{
		$login = "ftp://".$username.":".$password."@".$server."/".$file;
		$handle = fopen($login, "rb");
		return stream_get_contents($handle);
		fclose($handle);
	}

	/**
	 *
	 * Get the MIME type after specifying an extension
	 * @param string $file_extension Extension of the file
	 * @return string The MIME type of the file
	 */
	public function contenttype($file_extension)
	{
		$file_extension=strtolower($file_extension);
		switch($file_extension)
		{
			case 'exe': $ctype='application/octet-stream'; break;
			case 'zip': $ctype='application/zip'; break;
			case 'mp3': $ctype='audio/mpeg'; break;
			case 'mp4a':$ctype='audio/mp4'; break;
			case 'mpg': $ctype='video/mpeg'; break;
			case 'mp4': $ctype='video/mp4'; break;
			case 'flv': $ctype='video/x-flv';break;
			case 'webm': $ctype='video/webm';break;
			case 'ogv': $ctype='video/ogg';break;
			case 'avi': $ctype='video/x-msvideo'; break;
			case 'pdf': $ctype='application/pdf'; break;
			case 'jpeg': $ctype='image/jpeg'; break;
			case 'png': $ctype='image/png'; break;
			case 'svg': $ctype='image/svg+xml'; break;
			case 'ppt': $ctype='application/vnd.openxmlformats-officedocument.presentationml.presentation'; break;
			case 'pptx': $ctype='application/vnd.openxmlformats-officedocument.presentationml.presentation'; break;
			case 'html': $ctype='text/html'; break;
			case 'txt': $ctype='text/plain'; break;
			case 'rtf': $ctype='application/rtf'; break;
			case 'odt': $ctype='application/vnd.oasis.opendocument.text'; break;
			case 'doc': $ctype='application/vnd.openxmlformats-officedocument.wordprocessingml.document'; break;
			case 'docx': $ctype='application/vnd.openxmlformats-officedocument.wordprocessingml.document'; break;
			case 'xls': $ctype='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; break;
			case 'xlsx': $ctype='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; break;
			default:    $ctype='application/force-download';
		}
		return $ctype;
	}
	
	/**
	 * 
	 * GET MEDIA TYPE FLAG FROM EXTENSION
	 * @param unknown $fext The extension (jpg,mp3,etc.)
	 * @return Returns flag value (0-Thumbnail,1-Photo,2-Document,3-Video,4-Audio)
	 */
	public function get_mediatype_flag($fext)
	{
		$fext=strtolower($fext);
		switch ($fext)
		{
			case "jpg":
			case "jpeg":
			case "gif":
			case "pjpeg":
			case "png":
			case "bmp":
				$medflag="1";
				break;
			case 'ppt':
			case 'pptx':
			case 'html':
			case 'txt':
			case 'rtf':
			case 'odt':
			case "doc":
			case "docx":
			case "xls":
			case "xlsx":
			case "pdf":
				$medflag="2";
				break;
			case "avi":
			case "mp4":
			case "flv":
			case "mov":
			case "mpeg":
			case "ogv":
			case "ogg":
			case "mkv":
			case "mpg":
			case "webm":
			case "3gp":
			case "wmv":
			case "wmv":
				$medflag="3";
				break;
			case "mp3":
			case "wav":
			case "mid":
			case "aif":
			case "oga":
				$medflag="4";
				break;
			default:$medflag="1";
				break;
		}
		return $medflag;
	}

	/**
	 *
	 * @param string $string String to be written
	 * @param string $file Complete Path of the file
	 * @param string $mode Mode of writing (Defaults to w)
	 * @return boolean|string Returns the same path given on success,false on failure
	 */
	public function writefile($string,$file,$mode='w')
	{
		$fp = fopen($file,$mode);
		if(!fwrite($fp,$string))
		{
			return BOOL_FAILURE;
		}
		else
		{
			fclose($fp);return $file;
		}
	}

	/**
	 * SAVES AN ARRAY TO FILE
	 * @param string $filename Complete path of the file where array has to be saved
	 * @param array $b Array to be stored in the file
	 * @return boolean True on success,false on failure
	 */
	public function save_array_to_file($filename,$b)
	{
		if (!is_resource($filename))
		{
			if (!$file = fopen($filename,'w+')) return BOOL_FAILURE;
		} else {
			$file = $filename;
		}
		foreach ($b as $key=>$val)
		{
			fwrite($file,(is_int($key) ? chr(6).(string)$key : chr(5).$key));
			if (is_array($val))
			{
				fwrite($file,chr(0)); //array starts
				$fileobj=new ta_fileoperations();
				$fileobj->save_array_to_file($file,$val);
				fwrite($file,chr(1)); //array ends
			}
			elseif (is_int($val))
			{
				fwrite($file,chr(2).(string) $val); //int
			}
			elseif (is_string($val))
			{
				fwrite($file,chr(3).$val); //string
			}
		}
		if (!is_resource($filename)) fclose($file);
		return BOOL_SUCCESS;
	}

	/**
	 *
	 * Reads an array from the file
	 * @param string $filename Complete path of the file to be read
	 * @return boolean|multitype:Ambigous <NULL, number, string> Gives the array on success, false on failure
	 */
	public function read_array_from_file($filename)
	{
		if (!is_resource($filename))
		{
			if (!$file = fopen($filename,'r')) return BOOL_FAILURE;
		} else {
			$file = $filename;
		}
		$ret=array();
		$key='';
		$val=null;
		$mod=0;
		while (!feof($file))
		{
			$b = fread($file,1);
			if (ord($b) < 9)
			{
				if ($val!=null)
				{
					if ($mod==2) $val=(int) $val;
					if ($mod==3) $val=(string) $val;
					$ret[$key]=$val;
					$key='';
					$val=null;
					$mod=0;
				} else {
					if (ord($b)==0)
						$mod=0;
					elseif (ord($b)==1)
					return $ret;
					else
					{
						if ($mod==5) $key=(string) $key;
						if ($mod==6) $key=(int) $key;
						$mod=ord($b);
					}
				}
			} else {
				if ($mod==5 || $mod==5)
					$key.=$b;
				elseif ($mod==0)
				{
					$fileobj=new ta_fileoperations();
					$val=$fileobj->read_array_from_file($file);
				}
				else
					$val.=$b;
			}
		}
		if (!is_resource($filename)) fclose($file);
		return $ret;
	}

	/**
	 *
	 * Writing to network stream and rewriting if failed
	 * @param unknown_type $sock The socket
	 * @param string $data The data to be written
	 * @return Ambigous <boolean, number>|number Gives the number of bytes written to the stream
	 */
	public function fwrite_with_retry($sock, &$data)
	{
		$bytes_to_write = strlen($data);
		$bytes_written = 0;

		while ( $bytes_written < $bytes_to_write )
		{
			if ( $bytes_written == 0 ) {
				$rv = fwrite($sock, $data);
			} else {
				$rv = fwrite($sock, substr($data, $bytes_written));
			}

			if ( $rv === BOOL_FAILURE || $rv == 0 )
				return( $bytes_written == 0 ? BOOL_FAILURE : $bytes_written );

			$bytes_written += $rv;
		}

		return $bytes_written;
	}

	/**
	 *
	 * Writes to a socket
	 * @param unknown_type $sock Socket
	 * @param unknown_type $string Data to be written
	 * @return string
	 */
	public function writetosocket($sock,$string)
	{
		$fileobj=new ta_fileoperations();
		$rv = $fileobj->fwrite_with_retry($sock, $string);
		if ( ! $rv )
		{
			throw new Exception('#ta@0000000_0000038');
			return FAILURE;
		}
		if ( $rv != strlen($string) )
		{
			throw new Exception('#ta@0000000_0000039');
			return FAILURE;
		}
	}

	/**
	 * 
	 * CLEANS ARRAY OF MULTIPLE FILES FOR EASY UPLOAD
	 * @param unknown $file_post
	 * @return multitype:
	 */
	public function reArrayFiles(&$file_post) 
	{
		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);
	
		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}
	
		return $file_ary;
	}
	
	/**
	 * 
	 * UPLOAD A PICTURE
	 * @param unknown $fileobj $fileobj["keyname"] The file object
	 * @param unknown $uid UID of the user
	 * @param unknown $galid GALID where the pic has to be uploaded
	 * @param string $title Title of the picture 
	 * @param number $uplimit Upload Size Limit of the Picture
	 * @throws Exception Returns Mediaid on success, FAILURE on failure
	 */
	public function upload_pic($fileobj,$uid,$galid,$title="",$uplimit=6000000)
	{
		$uiobj=new ta_uifriend();
		$filobj=new ta_fileoperations();
		$galobj=new ta_galleryoperations();
		$logobj=new ta_logs();
		
		//$randstr=$uiobj->randomstring(3);
		
		$mediaid=$filname=$galobj->generate_mediaid();
		
		if($fileobj==""){throw new Exception('#ta@0000000_0000040');return FAILURE;}
		
		$galpath=$galobj->geturl_gallery($uid,$galid);
		$galpath=realpath($galpath);
		$allowed=Array("image/gif","image/jpeg","image/jpg","image/pjpeg","image/png","image/bmp");
		
		if($fileobj["name"]!=NULL)
		{
			$origfile=$fileobj["tmp_name"];
			$fext=$filobj->fileinfo($fileobj["name"],"3");
			$filname.=".".$fext;
			
			if ((in_array($fileobj["type"], $allowed))&& ($fileobj["size"] < $uplimit))
			{
				if ($fileobj["error"] > 0){throw new Exception('#ta@0000000_0000043');return FAILURE;}
				else
				{
					while (file_exists($galpath."/".$filname))
					{
						$filname=$galobj->generate_mediaid();
					};

					$filesobj=new ta_fileoperations();
					$comptype=0;
					switch ($comptype)
					{
						case 0:
							if($filesobj->copyfile($origfile,$galpath."/".$filname))
							{
								$galobj->addmediatogal($galpath."/".$filname,$galid,"1","",$title,"","","","1","",$mediaid);
								return $mediaid;
							}
							else
							{
								throw new Exception('#ta@0000000_0000044');
								return FAILURE;
							}
							break;
					}
				}
			}
			else
			{
				throw new Exception('#ta@0000000_0000045');
				return FAILURE;
			}
		}
	}
	
	/**
	 * 
	 * GENERATE THUMBNAILS OF MEDIA
	 * @param unknown $galid GALID of the original image source
	 * @param unknown $mediaid Media ID of the original image source
	 * @param unknown $uid UID of the user
	 * @param string $mediatype Type of media whose thumb is to be generated (1-Photo,2-Video)
	 * @param number $width Width of the thumbnail to be generated
	 * @param number $height Height of the thumbnail to be generated
	 * @param number $quality Quality of the thumbnail (Defaults to 100)
	 * @return Ambigous Returns mediaid of the thumbnail
	 */
	public function generate_thumbs($galid,$mediaid,$uid="",$mediatype="1",$width=80,$height=80,$quality=100)
	{
		$galobj=new ta_galleryoperations();
		$userobj=new ta_userinfo();
		$filobj=new ta_fileoperations();
		$logobj=new ta_logs();
		
		$logobj->store_templogs("GEN THUMB");
		
		if($uid=="")
		{
			if($userobj->userinit()==FAILURE)die("OOPS! Please login!");
			$uid=$userobj->uid;
		}
		
		$mediaurl=$galobj->geturl_media($galid, $mediaid,"1");
		
		$logobj->store_templogs("MEDURL:".$mediaurl);
		
		$fext=$filobj->fileinfo($mediaurl,"3");
		
		$logobj->store_templogs("EXT:".$fext);
		
		$galid_t=$galobj->get_gallery_thumbs($uid);
		
		$logobj->store_templogs("THUMB GALID:".$galid_t);
		
		$galurl=$galobj->geturl_gallery($uid,$galid_t);
		
		$logobj->store_templogs("THUMB GAL URL:".$galurl);
		
		switch ($mediatype)
		{
			case "1":
				$image = new Imagick($mediaurl);
				$geo=$image->getImageGeometry();
				$sizex=$geo['width'];
				$sizey=$geo['height'];
				if($sizex<$width)
				{
					if($image->getFormat()=="gif")
					{
						foreach($image as $frame){$frame->scaleImage($width, 0,BOOL_SUCCESS);}
					}
					else
					{
						$image->scaleImage($width, 0, BOOL_SUCCESS);
					}
				}
				else
				{
					if($image->getFormat()=="gif")
					{
						foreach($image as $frame){$frame->scaleImage($width, 0,BOOL_SUCCESS);}
					}
					else
					{
						$image->scaleImage($width, 0, BOOL_FAILURE);
					}
				}
				$image->setcompressionquality($quality);
				$logobj->store_templogs("S1");
				$co=0;
				do
				{
					$finalpath=$galurl."/t_".$mediaid."_".$width."x".$height;
					if($co!=0){$finalpath.="_".$co;}
					$co++;
					$thumbpath=$finalpath.".".$fext;
				}while (file_exists($thumbpath));
				$logobj->store_templogs("THUMBPATH:".$thumbpath);
				$image->writeimage($thumbpath);
				$mediaid=$galobj->addmediatogal($thumbpath,$galid_t,"0");
				
				$logobj->store_templogs("THUMB MEDID:".$mediaid);
				
				return $mediaid;
				break;
			default:
				die("invalid option");throw new Exception('-1');
				break;
		}
	}
	
	/**
	 * 
	 * GET THUMBNAIL URL OF A MEDIA
	 * @param unknown $galid GALID of the gallery where original media lies
	 * @param unknown $mediaid Media ID of the media where original media lies
	 * @param unknown $uid UID of the user
	 */
	public function get_thumb($galid,$mediaid,$uid,$width,$height)
	{
		$userobj=new ta_userinfo();
		$galobj=new ta_galleryoperations();
		$fileobj=new ta_fileoperations();
		
		$uid=$galobj->get_gallery_owner($galid);
		$mediaurl=$galobj->geturl_media($galid,$mediaid);
		$fext=$fileobj->fileinfo($mediaurl,"3");
		$mediatype=$galobj->get_media_typeflag($mediaid);
		
		$galid_t=$galobj->get_gallery_thumbs($uid);
		$tgalpath=$galobj->geturl_gallery($uid,$galid_t);
		
		
		$filpath=$tgalpath."/t_".$mediaid."_".$width."x".$height.".".$fext;
		if(file_exists($filpath))
			return $filpath;
			else
			{
				$mediaid_thumb=$fileobj->generate_thumbs($galid,$mediaid,$uid,$mediatype,$width,$height,100);
				$filpath=$galobj->geturl_media($galid_t,$mediaid_thumb);
				return $filpath;
			}
	}
	
	
	/**
	 *
	 * UPLOAD A VIDEO
	 * @param unknown $fileobj $fileobj["keyname"] The file object
	 * @param unknown $uid UID of the user
	 * @param unknown $galid GALID where the vid has to be uploaded
	 * @param string $title Title of the video
	 * @param number $uplimit Upload Size Limit of the Video
	 * @throws Exception Returns Mediaid on success, FAILURE on failure
	 */
	public function upload_vid($fileobj,$uid,$galid,$title="",$uplimit=6000000)
	{
		$uiobj=new ta_uifriend();
		$filobj=new ta_fileoperations();
		$galobj=new ta_galleryoperations();
	
	
		$randstr=$uiobj->randomstring(3);
	
		$mediaid=$filname=$galobj->generate_mediaid();
	
		if($fileobj==""){throw new Exception('#ta@0000000_0000040');return FAILURE;}
	
		$galpath=$galobj->geturl_gallery($uid,$galid);
		$galpath=realpath($galpath);
		$allowed=Array("application/octet-stream","video/mp4","video/x-msvideo","video/quicktime","video/x-flv","video/3gpp","video/x-ms-wmv","application/x-mpegURL","video/MP2T");
	
		if($fileobj["name"]!=NULL)
		{
			$fext=$filobj->fileinfo($fileobj["name"],"3");
			$filname.=".".$fext;
				
			if ((in_array($fileobj["type"], $allowed))&& ($fileobj["size"] < $uplimit))
			{
				$origfile=$fileobj["tmp_name"];
				if ($fileobj["error"] > 0){throw new Exception('#ta@0000000_0000043');return FAILURE;}
				else
				{
					while (file_exists($galpath."/".$filname))
					{
						$filname=$galobj->generate_mediaid();
					};
	
					$filesobj=new ta_fileoperations();
					$comptype=0;
					switch ($comptype)
					{
						case 0:
							if($filesobj->copyfile($origfile, $galpath."/".$filname))
							{
								$galobj->addmediatogal($galpath."/".$filname,$galid,"1","",$title,"","","","1","",$mediaid);
								return $mediaid;
							}
							else
							{
								throw new Exception('#ta@0000000_0000044');
								return FAILURE;
							}
							break;
					}
				}
			}
			else
			{
				throw new Exception('#ta@0000000_0000045');
				return FAILURE;
			}
		}
	}
	
	
	public function vidupload($fileobj,$vidtype,$convtype=0,$uplimit=600000000,$path="")
	{
		$uiobj=new ta_uifriend();
		$userobj=new ta_userinfo();
		$utilityobj=new ta_utilitymaster();
		$logobj=new ta_logs();
		$galobj=new ta_galleryoperations();
		$fobj=new ta_fileoperations();
		
		$randstr=$uiobj->randomstring(3);
		$realvidpath="";
		if($fileobj=="")
		{
			throw new Exception('#ta@0000000_0000040');
			return FAILURE;
		}
		if($path=="")
		{
				if($userobj->userinit()=="")
				{
					throw new Exception('#ta@0000000_0000041');
					return FAILURE;
				}
				else
				{
					$vidpath=$userobj->docroot."/".$vidtype."/";
					if(!file_exists($vidpath))
					{
						if(!mkdir($vidpath,0700,true))
						{
							throw new Exception('#ta@0000000_0000042');
							return FAILURE;
						}
					}
				}
			
			$realvidpath=$utilityobj->pathtourl($vidpath);
		}
		else
		{
			$vidpath=realpath($path);
			$realvidpath=$path;
		}
		
		$logobj->store_templogs($vidpath."..".$realvidpath);
	
		//VIDEO UPLOAD
		if($fileobj["name"]!=NULL)
		{
			if ((($fileobj["type"] == "application/octet-stream")||($fileobj["type"] == "video/mp4")|| ($fileobj["type"] == "video/x-msvideo")|| ($fileobj["type"] == "video/quicktime")|| ($fileobj["type"] == "video/x-flv")|| ($fileobj["type"] == "video/3gpp")|| ($fileobj["type"] == "video/x-ms-wmv")|| ($fileobj["type"] == "application/x-mpegURL")||($fileobj["type"] == "video/MP2T"))&& ($fileobj["size"] < $uplimit))
			{
				$filname1=$fileobj["name"];
				$filname1= str_replace(' ', '_', $filname1);
				if ($fileobj["error"] > 0)
				{
					throw new Exception('#ta@0000000_0000043');
					return FAILURE;
				}
				else
				{
					$co=0;
					do
					{
						if($co<=0)
						{$filname="ta_".$randstr.time().$filname1;}
						else
						{$filname="ta_".$randstr.$co.time()."_".$filname1;}
						$co++;
					}while (file_exists($vidpath.$filname));

					$filname2=$fobj->fileinfo($filname,"2");
					$origfile=$fileobj["tmp_name"];
	
					switch ($convtype)
					{
						case 0:
							$flvpath=$vidpath.$filname2.".flv";
							$flvpath=$utilityobj->pathtourl($flvpath);
							$logobj->store_templogs("FLV PATH:".$flvpath);
							if($fobj->copyfile($origfile, $vidpath.$filname))
							{
								return array("flv"=>$flvpath,"orig"=>$vidpath.$filname);
							}
							else
							{
								throw new Exception('#ta@0000000_0000044');
								return FAILURE;
							}
							break;
					}
				}
			}
			else
			{
				throw new Exception('#ta@0000000_0000045');
				return FAILURE;
			}
		}
	}


	/**
	 *
	 * UPLOADS FILE TO SERVER
	 * @param unknown_type $fileobj File object $_FILE["keyname"] received from the form
	 * @param Array $allowedfiletype Defaults to "", Specifies the allowed filetype in an array
	 * @param string $context Key for the file path to be uploaded User sub directory (Defaults to documents)
	 * @param longint $uplimit Upload limit of the file in bytes  (Defaults to 2000000 bytes)
	 * @param string $path Path where the file has to be uploaded
	 * @return string Absolute Uploaded Path
	 */
	public function fileupload($fileobj="",$allowedfiletype="",$context="documents",$uplimit=2000000,$path="")
	{
		$userobj=new ta_userinfo();

		if($fileobj==""||$allowedfiletype=="")
		{
			throw new Exception('#ta@0000000_0000046');
			return FAILURE;
		}
		else
		{
			if($fileobj["name"]==NULL)
			{
				throw new Exception('#ta@0000000_0000047');
				return FAILURE;
			}
			if($fileobj["size"]>$uplimit)
			{
				throw new Exception('#ta@0000000_0000048');
				return FAILURE;
			}

			if(!is_array($fileobj["name"]))
			{
				$fileobject=new ta_fileoperations();
			$ext=$fileobject->fileinfo($fileobj["name"],3);
			$flag=0;
			foreach($allowedfiletype as $ftype)
			{
				if($ftype==$ext)
				{
					$flag=1;break;
				}
			}
			if($flag==0)
			{
				throw new Exception('#ta@0000000_0000049');
				return FAILURE;
			}

			if($path=="")
			{
				if($userobj->userinit()=="")
				{
					$uploadpath=ROOT_DOCS."/".$context."/".$ext;
				}
				else
				{
					$uploadpath=$userobj->docroot."/".$context."/".$ext;
				}
			}
			else
			{
				$uploadpath=$path;
			}

			if(!file_exists($uploadpath))
			{
				if(!mkdir($uploadpath,0700,BOOL_SUCCESS))
				{
					throw new Exception('#ta@0000000_0000050');
					return FAILURE;
				}
			}

			$filname1=$fileobj["name"];
			$filname1 = preg_replace('/\s+/', '_', $filname1);
			
			$co=0;
			do
			{
				if($co<=0)
				{$filname="tadoc".time().$filname1;}
				else
				{$filname="tadoc".time().$filname1."_".$co;}
				$co++;
			}while (file_exists($uploadpath));

			$fileobject=new ta_fileoperations();
			if($fileobject->movefile($fileobj["tmp_name"],$uploadpath. $filname))
			{
				return $uploadpath.$filname;
			}
			else
			{
				throw new Exception('#ta@0000000_0000051');
				return FAILURE;
			}
		}
		else
		{
			throw new Exception('#ta@0000000_0000052');
			return FAILURE;
		}

		}
	}

	/**
	 *
	 * Get information about a file
	 * @param string $path Complete path of the file
	 * @param integer $reqtype Information requested (1-dirname,2-basename,3-extension,4-filename) Defaults to 3
	 * @return mixed|string
	 */
	public function fileinfo($path,$reqtype=3)
	{
		$path_parts = pathinfo($path);
		switch ($reqtype)
		{
			case 1:return $path_parts['dirname'];break;
			case 2:return $path_parts['basename'];break;
			case 3:if(isset($path_parts['extension']))return $path_parts['extension'];else return "";break;
			case 4:return $path_parts['filename'];break;
			default:return FAILURE;break;
		}
	}

	/**
	 *
	 * Gets file size
	 * @param string $path Complete path of the file
	 * @param integer $custsize Type in which size has to be displayed (0-Gives just number in bytes, 1-Gives the size with appropriate units after conversion) Defaults to 1
	 * @return number The size of the file
	 */
	public function getfilesize($path,$custsize=1)
	{
		/*
		 * CUST SIZE
		 * 0-JUST THE SIZE IN BYTES
		 * 1-SIZE WITH FLEXIBLE PARAMETERS
		 */
		$filesobj=new ta_fileoperations();
		if($custsize==0)
		{return filesize($path);}
		else
			if($custsize==1)
			{return $filesobj->display_filesize(filesize($path));}
	}

	/**
	 *
	 * Displays file size with appropriate units (Does not fetch file size)
	 * @param integer $filesize Size of the file in bytes
	 * @return string File size with appropriate units
	 */
	public function display_filesize($filesize){
		if(is_numeric($filesize)){
			$decr = 1024; $step = 0;
			$prefix = array('Byte','KB','MB','GB','TB','PB');

			while(($filesize / $decr) > 0.9){
				$filesize = $filesize / $decr;
				$step++;
			}
			return round($filesize,2).' '.$prefix[$step];
		} else {

			return 'NaN';
		}
	}

	/**
	 *
	 * Gets the list of files and(or) folders from a directory (not recursive)
	 * @param string $path The complete path to be checked
	 * @param string $folders Customize list (1-if both files and folders are to be displayed,2-Only files,3-Only folders)
	 * @return NULL/Array NULL if no file or folder, Array if exists (arr[index]["filename"],arr[index]["type"])
	 */
	public function getdirlist($path,$folders="1")
	{
		/*
		 * FOLDERS
		 * 1-FILES & FOLDERS
		 * 2-FILES ONLY
		 * 3-FOLDERS ONLY
		 */
		$flist=NULL;
		if($folders=="1")
		{
			$co=0;
			if (is_dir($path)) {
				if ($dh = opendir($path)) {
					while (($file = readdir($dh)) !== BOOL_FAILURE) {
						$flist[$co]["filename"]=$file;
						$flist[$co]["type"]=filetype($path . $file);
						$co++;
					}
								closedir($dh);
				}
				}
				return $flist;
		}
		else
			if($folders=="2")
		{
			$co=0;
			if (is_dir($path)) {
				if ($dh = opendir($path)) {
					while (($file = readdir($dh)) !== BOOL_FAILURE) {
						if(filetype($path . $file)!="dir")continue;
						$flist[$co]["filename"]=$file;
						$flist[$co]["type"]=filetype($path . $file);
						$co++;
					}
					closedir($dh);
				}
			}
			return $flist;
		}
		else
			if($folders=="3")
			{
				$co=0;
				if (is_dir($path)) {
					if ($dh = opendir($path)) {
						while (($file = readdir($dh)) !== BOOL_FAILURE) {
							if(filetype($path . $file)=="dir")continue;
							$flist[$co]["filename"]=$file;
							$flist[$co]["type"]=filetype($path . $file);
							$co++;
						}
						closedir($dh);
					}
				}
				return $flist;
			}
	}

}//END OF CLASS