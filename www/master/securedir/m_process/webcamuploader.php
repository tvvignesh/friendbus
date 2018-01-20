<?php
require_once 'adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';

if ($_POST['type'] == "pixel") {
	// input is in format 1,2,3...|1,2,3...|...
	$im = imagecreatetruecolor(320, 240);

	foreach (explode("|", $_POST['image']) as $y => $csv) {
		foreach (explode(";", $csv) as $x => $color) {
			imagesetpixel($im, $x, $y, $color);
		}
	}
} else {
	// input is in format: data:image/png;base64,...
	$im = imagecreatefrompng($_POST['image']);
}



imageAlphaBlending($im, true);
imageSaveAlpha($im, true);

imagepng($im,ROOT_MEDIA."/tempuploads/files/file.png");

echo "Upload successful";

?>