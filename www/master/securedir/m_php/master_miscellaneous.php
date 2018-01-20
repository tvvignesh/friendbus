<?php
class JSIncluder {
	private static $included = array();

	private static function ext($filename)
	{
		return pathinfo($filename, PATHINFO_EXTENSION);
	}

	public static function includeFile($file)
	{
		if (isset(JSIncluder::$included[$file]))
			return;
		JSIncluder::$included[$file] = BOOL_SUCCESS;

		$contents = file_get_contents($file);
		
		if($file==MASTER_JS_ROOT.'/jsclassfile.js')
		{
			preg_match_all('/\/\*include:([^*]+)\*\//', $contents, $deps);
	
			foreach ($deps[1] as $dep) {
				if (is_file(ROOT_SERVER.$dep)) {
					JSIncluder::includeFile(ROOT_SERVER.$dep);
				} else
					JSIncluder::includeAll(ROOT_SERVER.$dep);
			}
		}
		else
		{
			switch($file)
			{
				case ROOT_SERVER."/master/securedir/m_js/libs/modernizr.custom.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jquery.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/mousetrap.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jquery.dropdown.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/bootstrap-3.3.5-dist/js/bootstrap.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jquery-ui-1.11.4.custom/jquery-ui.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jquery.ui.touch-punch.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jquery.cssemoticons.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/sketch.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/Chart.js-master/Chart.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/gallery/js/blueimp-gallery.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/cropper/dist/cropper.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jPlayer-master/dist/jplayer/jquery.jplayer.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/jPlayer-master/dist/add-on/jplayer.playlist.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/pnotify.custom.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/clippy.js-master/build/clippy.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/video.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-errors.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs.loopbutton.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-seek.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-transcript.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs-playlists.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/video-js-4.12.11/video-js/videojs.hotkeys.min.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/pdfjs-1.1.366-dist/build/pdf.js":
				case ROOT_SERVER."/master/securedir/m_js/libs/bootstrap-table-master/dist/bootstrap-table-all.min.js":
					
					$GLOBALS["alljs"].=$contents."\r\n";
				break;
				default:
					$contents=JSMin::minify($contents);
					$GLOBALS["alljs"].=$contents."\r\n";
				break;
			}
		}
	}

	private static function includeAll($dir)
	{
		if (($handle = opendir($dir))) {
			while ($file = readdir($handle)) {
				clearstatcache();
				if (is_file($dir . '/' . $file)) {
					if (JSIncluder::ext($file) == 'js') {
						JSIncluder::includeFile($dir . '/' . $file);
					}
				} elseif ($file != '..' && $file != '.')
				JSIncluder::includeAll($dir . '/' . $file);
			}
			closedir($handle);
		}
	}

}
?>