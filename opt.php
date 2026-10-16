<?php
$filePath = 'lori.jpg';
$im = new Imagick($filePath);
$quality = $im->getImageCompressionQuality();
// Include this part if you also want to specify a maximum size for the images

$size = $im->getImageGeometry();
$maxWidth = 1920;
$maxHeight = 1080;
echo $quality;
if ($quality>73) {
    $im->setImageCompression(Imagick::COMPRESSION_JPEG);
    $im->setImageCompressionQuality(73);
    $im->stripImage();
    $im->setInterlaceScheme(Imagick::INTERLACE_PLANE);
    // Try between 0 or 5 radius. If you find radius of 5 
    // produces too blurry  pictures decrease to 0 until you 
    // find a good balance between size and quality. 
    $im->gaussianBlurImage(0.005, 1);
    $im->setImageProperty('jpeg:sampling-factor', '4:2:0');
}


// ----------
// |        |
// ----------
if($size['width'] >= $size['height']){
  if($size['width'] > $maxWidth){
    $im->resizeImage($maxWidth, 0, Imagick::FILTER_LANCZOS, 1);
    echo '1';
  } else {
      $im->resizeImage($size['width'], 0, Imagick::FILTER_LANCZOS, 1);
    echo '2';
  }
}


// ------
// |    |
// |    |
// |    |
// |    |
// ------
else{
  if($size['height'] > $maxHeight){
    $im->resizeImage(0, $maxHeight, Imagick::FILTER_LANCZOS, 1);
    echo '3';
  } else {
      $im->resizeImage(0, $size['height'], Imagick::FILTER_LANCZOS, 1);
        echo '4';
  }
}

$im->writeImages($filePath, true);
        $im->clear();
        $im->destroy();
exec('magick '.$filePath.' -strip -interlace Plane -gaussian-blur 0.05 -quality 85% result.jpg');
echo __DIR__.$filePath;