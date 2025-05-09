--TEST--
imagewebp() and imagecreatefromwebp() - basic test
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!GD_BUNDLED && version_compare(GD_VERSION, '2.2.0', '<')) {
    die("skip test requires GD 2.2.0 or higher");
}
if (!function_exists('imagewebp') || !function_exists('imagecreatefromwebp'))
    die('skip WebP support not available');
?>
--FILE--
<?php
require_once __DIR__ . '/func.inc';

$filename = __DIR__ . '/webp_basic.webp';

$im1 = imagecreatetruecolor(75, 75);
$white = imagecolorallocate($im1, 255, 255, 255);
$red = imagecolorallocate($im1, 255, 0, 0);
$green = imagecolorallocate($im1, 0, 255, 0);
$blue = imagecolorallocate($im1, 0, 0, 255);
imagefilledrectangle($im1, 0, 0, 74, 74, $white);
imageline($im1, 3, 3, 71, 71, $red);
imageellipse($im1, 18, 54, 36, 36, $green);
imagerectangle($im1, 41, 3, 71, 33, $blue);
imagewebp($im1, $filename);

$im2 = imagecreatefromwebp($filename);
imagewebp($im2, $filename);
echo 'Is lossy conversion close enough? ';
var_dump(mse($im1, $im2) < 500);

imagewebp($im1, $filename, IMG_WEBP_LOSSLESS);
$im_lossless = imagecreatefromwebp($filename);
echo 'Does lossless conversion work? ';
var_dump(mse($im1, $im_lossless) == 0);

try {
	imagewebp($im1, $filename, -10);
} catch (\ValueError $e) {
	echo $e->getMessage();
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/webp_basic.webp');
?>
--EXPECT--
Is lossy conversion close enough? bool(true)
Does lossless conversion work? bool(true)
imagewebp(): Argument #3 ($quality) must be greater than or equal to -1
