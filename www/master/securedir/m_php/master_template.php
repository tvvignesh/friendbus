<?php
/**
 *
* CONTAINS ALL TEMPLATE FUNCTIONS
* @author T.V.VIGNESH
*
*/
class ta_template
{
	/**
	 *
	 * FUNCTION WHICH HAS THE SKELETON OF THE TOP NAVBAR
	 */
	public function template_load_top()
	{
		if(!isset($GLOBALS["template_tloaded"]))
		{
			$GLOBALS["template_tloaded"]=1;
			require_once MASTER_TEMPLATE."/bar_top.php";
		}
	}

	public function template_load_right()
	{
		if(!isset($GLOBALS["template_rloaded"]))
		{
			$GLOBALS["template_rloaded"]=1;
			require_once MASTER_TEMPLATE."/content_right.php";
		}
	}

	public function template_load_left()
	{
		if(!isset($GLOBALS["template_lloaded"]))
		{
			$GLOBALS["template_lloaded"]=1;
			require_once MASTER_TEMPLATE."/content_left.php";
		}
	}


	/**
	 *
	 * CREATES A NEW THEME
	 * @param unknown $themename Name of the theme
	 * @param unknown $uid User ID of the person who created the theme
	 * @param unknown $desc Description of the theme
	 * @param string $galid Gallery ID of the theme preview screenshot gallery
	 * @param string $themeflag Flag value specifying permissions (1-allowed,2-under review,3-blocked)
	 * @param string $privacyflag Privacy Flag specifying privacy (1-Public,2-Private,Others-List ID from listdb)
	 * @param string $prodflag Flag value specifying the product for which the theme is created (1-universal,2-techahoy,3-mplace,4-temple,etc.)
	 * @return Ambigous <string, unknown>|string Theme ID of the created theme on success, FAILURE on failure
	 */
	public function newtheme($themename,$uid,$desc,$galid="",$themeflag="1",$privacyflag="1",$prodflag="1")
	{
		$dbobj=new ta_dboperations();
		$uiobj=new ta_uifriend();
		$themeid=$uiobj->randomstring(30,tbl_themedb::dbname,tbl_themedb::tblname,tbl_themedb::col_themeid);
		$fileobj=new ta_fileoperations();
		$res=$fileobj->writefile("",ROOT_THEME."/thm_".$themeid.".css");
		if($res!=FAILURE)
		{
			$csspath=$res;
		}
		else
		{
			return FAILURE;
		}
		if($dbobj->dbinsert("INSERT INTO ".tbl_themedb::tblname." (".tbl_themedb::col_themeuid.",".tbl_themedb::col_noofrates.",".tbl_themedb::col_prodflag.",".tbl_themedb::col_themecss.
		",".tbl_themedb::col_themedesc.",".tbl_themedb::col_themeflag.",".tbl_themedb::col_galid.",".tbl_themedb::col_themeid.",".tbl_themedb::col_themename.",".tbl_themedb::col_themeprivacy.",".
		tbl_themedb::col_totalrates." VALUES ('$uid','0','$prodflag','$csspath','$desc','$themeflag','$themeid','$themename','$privacyflag','0')",tbl_themedb::dbname)==SUCCESS)
		{
			return $themeid;
		}
		else
		{
			return FAILURE;
		}
	}


	/**
	 *
	 * LOAD A CSS FILE OF A THEME WHEN THEMEID IS GIVEN
	 * @param string $themeid
	 */
	public function load_css_theme_custom($themeid="123")
	{
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_themedb::tblname." WHERE ".tbl_themedb::col_themeid."='$themeid' LIMIT 0,1",tbl_themedb::dbname);
		if($res!=EMPTY_RESULT)
		{
			$csspath=$res[0][changesqlquote(tbl_themedb::col_themecss,"")];
			$assetobj=new ta_assetloader();
			$assetobj->load_css($csspath);
		}
	}

	/**
	 *
	 * LOAD ALL ASSETS OF DEFAULT THEME (CSS,JS,IMAGES,etc.)
	 */
	public function load_theme_default()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css_theme_default();
	}


	/**
	 *
	 * EDIT THEME INFORMATION
	 * @param unknown $themeid Theme ID of the theme to be edited
	 * @param unknown $colflag Flag value specifying column (1-Theme Name,2-CSS Path,3-Theme Description,4-Theme Perm Flag,5-Theme Product Flag,6-Gallery ID,7-Theme privacy flag)
	 * @param unknown $colvalue The value to be inserted
	 * @return string
	 */
	public function theme_edit($themeid,$colflag,$colvalue)
	{
		$dbobj=new ta_dboperations();
		switch ($colflag)
		{
			case "1":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_themename."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "2":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_themecss."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "3":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_themedesc."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "4":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_themeflag."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "5":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_prodflag."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "6":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_galid."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			case "7":$res=$dbobj->dbupdate("UPDATE ".tbl_themedb::tblname." SET ".tbl_themedb::col_themeprivacy."='$colvalue' WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname);break;
			default:die("INVALID VALUE!");
		}
		if($res==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * DELETE A THEME
	 * @param unknown $themeid Theme ID of the theme to be deleted
	 * @return string SUCCESS on successful deletion, FAILURE on failure
	 */
	public function theme_delete($themeid)
	{
		$dbobj=new ta_dboperations();
		if($dbobj->dbdelete("DELETE FROM ".tbl_themedb::tblname." WHERE ".tbl_themedb::col_themeid."='$themeid'",tbl_themedb::dbname)==SUCCESS)
		{
			return SUCCESS;
		}
		else
		{
			return FAILURE;
		}
	}

	/**
	 *
	 * CREATE A NEW IMAGE GALLERY FOR THEMES
	 * @param unknown $themeid Theme ID of the theme for which gallery has to be created
	 * @param unknown $uid User ID of person creating the gallery (Defaults to "" which means script owns it (Not user))
	 * @return Ambigous <Ambigous, string, unknown> GALID on success, FAILURE on failure
	 */
	public function theme_create_imggal($themeid,$uid="")
	{
		$galobj=new ta_galleryoperations();
		$galid=$galobj->creategallery("Theme Gallery_".$themeid,"Theme Screenshots","2",$uid);
		return $galid;
	}
}
?>