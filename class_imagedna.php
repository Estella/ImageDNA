<?php
class ImageDNA {
  public function img2D($f,$rezz = 512,$iris = 32,$sig = 16) {
    $im = imagecreatefromstring(file_get_contents($f));
    list($imgw, $imgh) = getimagesize($f);
    for ($i=0; $i<$imgw; $i++){
      for ($j=0; $j<$imgh; $j++) {
        $rgb = ImageColorAt($im, $i, $j);
        $rr = ($rgb >> 16) & 0xFF;
        $gg = ($rgb >> 8) & 0xFF;
        $bb = $rgb & 0xFF;
        $g = round(($rr + $gg + $bb) / 3);
        $val = imagecolorallocate($im, $g, $g, $g);
        imagesetpixel ($im, $i, $j, $val);
      }
    }
    $bw_img = imagecreatetruecolor($rezz, $rezz);
    imagecopyresampled($bw_img, $im, 0, 0, 0, 0, $rezz, $rezz, $imgw, $imgh);
    for($y = 0;$y < $rezz;$y += $iris) {
      for($x = 0;$x < $rezz;$x += $iris) {
        $rgb = imagecolorsforindex($bw_img, imagecolorat($bw_img, $x, $y));
        $color = imagecolorclosest($bw_img, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($bw_img, $x, $y, $x+($iris - 1), $y+($iris - 1), $color);
      }
    }
    $im_sig = imagecreatetruecolor($sig, $sig);
    imagecopyresampled($im_sig, $bw_img, 0, 0, 0, 0, $sig, $sig, $rezz, $rezz);
    $data = array();
    for($x=0; $x<$sig; $x++) {
      for($y=0; $y<$sig; $y++) {
        $color = imagecolorat($im_sig,$x,$y);
        $pix = array('R'=>($color>>16)&0xFF,'G'=>($color>>8)&0xFF,'B'=>$color&0xFF);
        $data[] = round(($pix['R'] + $pix['G'] + $pix['B']) / 3);
      }
    }
    return $data;
  }
}
