<?php
$n=0;
if (isset($_REQUEST['n']))
	$n=$_REQUEST['n'];

$im = @imagecreatetruecolor(32, 26);

# important part one
imagesavealpha($im, true);
imagealphablending($im, false);

# important part two
$white = imagecolorallocatealpha($im, 255, 255, 255, 127);
imagefill($im, 0, 0, $white);

$font = 13;
# do whatever you want with transparent image
$col = imagecolorallocate($im, 255, 255, 255);
$offset = 0;
if ($n < 10)
	$offset = 5;
imagettftext($im, $font, 0, $offset, $font+2, $col, "../bddimg/sc/DejaVuSansCondensed.ttf", $n);

header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
?>
