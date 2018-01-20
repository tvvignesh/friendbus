<?php
/**
 *
 * CONTAINS FUNCTIONS USED TO LOAD ASSETS LIKE CSS FILES, JS FILES, IMAGES, etc.
 * @author T.V.VIGNESH
 *
 */
class ta_assetloader
{
	public function load_css($url)
	{
		$utilityobj=new ta_utilitymaster();
		$patharray=$utilityobj->parseurl($url);
		$path=$patharray['path'];
		$contents = file_get_contents(ROOT_SERVER."/".$url);
		//$GLOBALS["allcss"].="/*".$url."*/";
		$GLOBALS["allcss"].=$contents;
	}

	public function load_js($url)
	{
		echo '<script type="text/javascript" src="'.$url.'"></script>';
	}

	public function load_css_theme_default()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css_libs();
		$assetobj->load_css(MASTER_CSS_HOST.'/theme_default.css');
	}
	
	public function load_css_libs()
	{
		$assetobj=new ta_assetloader();
		//$assetobj->load_font("oswald","ofl");
		//$assetobj->load_font("opensans","ofl");
		//$assetobj->load_font("josefinslab","ofl");
		$assetobj->load_css_constants();
		//$assetobj->load_css(MASTER_CSS_HOST.'/pnotify.custom.min.css');
		$assetobj->load_css(MASTER_JS_HOST."/libs/jquery-ui-1.11.4.custom/jquery-ui.min.css");
		$assetobj->load_css(MASTER_JS_HOST."/libs/bootstrap-3.3.5-dist/css/bootstrap.min.css");
		$assetobj->load_css(MASTER_JS_HOST."/libs/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css");
		//$assetobj->load_css(MASTER_JS_HOST."/libs/bootstrap-fileinput-master/css/fileinput.min.css");
		$assetobj->load_css(MASTER_CSS_HOST."/jquery.cssemoticons.css");
		$assetobj->load_css(MASTER_CSS_HOST."/animate.min.css");
		$assetobj->load_css(MASTER_CSS_HOST."/font-awesome-4.4.0/css/font-awesome.min.css");
		$assetobj->load_css(MASTER_JS_HOST.'/libs/cropper/dist/cropper.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/gallery/css/blueimp-gallery.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-tour-0.10.2/build/css/bootstrap-tour.min.css');
		//$assetobj->load_css(MASTER_JS_HOST.'/libs/clippy.js-master/build/clippy.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-checkbox/css/bootstrap-checkbox.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-star-rating/css/star-rating.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/video-js/video-js/video-js.css');
		//$assetobj->load_css(MASTER_JS_HOST.'/libs/video-js-4.12.11/video-js/video-js.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-table-master/dist/bootstrap-table.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-toggle-master/css/bootstrap-toggle.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/bootstrap3-editable-1.5.1/bootstrap3-editable/css/bootstrap-editable.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/select2-master/dist/css/select2.min.css');
		$assetobj->load_css(MASTER_JS_HOST.'/libs/mentions/jquery.mentions.css');
	}

	/**
	 * 
	 * @param unknown $family folder name of font file
	 * @param unknown $licence either apache or ofl
	 */
	public function load_font($family,$licence)
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(HOST_SECURE."/fontserver.php?family=".$family."&licence=".$licence);
	}
	
	public function load_css_constants()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(MASTER_CSS_HOST.'/constants_css.css');
	}
	
	public function load_css_product_login()
	{
		$GLOBALS["product"]='LOGIN';
		$assetobj=new ta_assetloader();
		$assetobj->load_css(PRODHOST_LOGIN.'/css/login_style.css');
	}

	public function load_css_product_subscriberbot()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(PRODHOST_SUBSCRIBERBOT.'/css/sb_style.css');
	}
	
	public function load_css_product_social()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(PRODHOST_SOCIAL.'/css/so_style.css');
	}

	public function load_css_product_buildmyiq()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(PRODHOST_BUILDMYIQ.'/css/bmi_style.css');
	}

	public function load_css_product_hookmovies()
	{
		$assetobj=new ta_assetloader();
		$assetobj->load_css(PRODHOST_HOOKMOVIES.'/css/hm_style.css');
	}

	public function load_css_final()
	{
		$optobj=new ta_optimizer();
		if($GLOBALS["product"]==''||!isset($GLOBALS["product"]))
		{
			$GLOBALS["product"]='general';
		}
		if(!file_exists(MASTER_CSS_ROOT."/tempcss/tempcss_".$GLOBALS['product'].".css"))
		{
			$fhandle=fopen(MASTER_CSS_ROOT."/tempcss/tempcss_".$GLOBALS['product'].".css",'w');
			if($fhandle)
			{
				$GLOBALS["allcss"]=$optobj->optimize_css($GLOBALS["allcss"]);
				fwrite($fhandle, $GLOBALS["allcss"]);
				fclose($fhandle);
			}
			else
			{
				die("Some unexpected error occured in loading css files. ".MASTER_JS_ROOT."/tempcss.css");
			}
		}
		//echo '<link rel="stylesheet" href="'.MASTER_CSS_HOST.'/tempcss/tempcss.css">';
		echo '<link rel="stylesheet" href="/master/securedir/cssrenderer.php?url=/master/securedir/m_css/tempcss/tempcss_'.$GLOBALS["product"].'.css">';
	}
	
	public function load_js_final()
	{
		if(!isset($GLOBALS["js_finalloaded"]))
		{
			$GLOBALS["js_finalloaded"]=1;
			echo '
			<!--[if lt IE 9]>
		      <script src="master/securedir/m_js/libs/html5shiv.min.js"></script>
		      <script src="master/securedir/m_js/libs/respond.min.js"></script>
		    <![endif]-->
			<script type="text/javascript" src="/master/securedir/jsrenderer.php?url=/master/securedir/m_js/tempjs.js"></script>
			';
		}
	}
	
	public function load_js_product_login()
	{
		if(!isset($GLOBALS["js_loginloaded"]))
		{
			$GLOBALS["js_loginloaded"]=1;
			$assetobj=new ta_assetloader();
			$assetobj->load_js("/master/securedir/jsrenderer.php?url=/js/lo_functions.js");
			$assetobj->load_js("/master/securedir/jsrenderer.php?url=/js/lo_mainscript.js");
		}
	}
}
?>