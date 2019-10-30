<?php
class ImageDNA {
  public function img2D($f,$rezz = 512,$iris = 32,$sig = 16,$debug_level = 0) {
    if (!file_exists($f)) { return false; }
    $output = array();
    $output['hashes']['md5'] = hash_file('md5', $f);
    $output['hashes']['sha1'] = hash_file('sha1', $f);
    $output['hashes']['sha256'] = hash_file('sha256', $f);
    $extension = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    switch ($extension) {
      case 'jpg':
      case 'jpeg':
        $im = imagecreatefromjpeg($f);
        break;
      case 'gif':
        $im = imagecreatefromgif($f);
        break;
      case 'png':
        $im = imagecreatefrompng($f);
         break;
      default:
        $im = imagecreatefromstring(file_get_contents($f));
        break;
    }
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
    if ($debug_level == 1) {
      header('Content-type: image/png');
      imagepng($im);
      exit;
    }
    $bw_img = imagecreatetruecolor($rezz, $rezz);
    imagecopyresampled($bw_img, $im, 0, 0, 0, 0, $rezz, $rezz, $imgw, $imgh);
    if ($debug_level == 2) {
      header('Content-type: image/png');
      imagepng($bw_img);
      exit;
    }
    for($y = 0;$y < $rezz;$y += $iris) {
      for($x = 0;$x < $rezz;$x += $iris) {
        $rgb = imagecolorsforindex($bw_img, imagecolorat($bw_img, $x, $y));
        $im_cell = imagecreatetruecolor($iris,$iris);
        imagecopy($im_cell,$bw_img, 0, 0, $x, $y, $iris,$iris);
        $shade = 0;
        for ($i=0; $i<$iris; $i++){
          for ($j=0; $j<$iris; $j++) {
            $cell_rgb = ImageColorAt($im_cell, $i, $j);
            $rr = ($cell_rgb >> 16) & 0xFF;
            $gg = ($cell_rgb >> 8) & 0xFF;
            $bb = $cell_rgb & 0xFF;
            $g = round(($rr + $gg + $bb) / 3);
            $shade += $g;
          }
        }
        $shade = ($shade / ($iris * $iris)) & 0xFF;
        $shade = imagecolorallocate($im_cell, $shade, $shade, $shade);
        //$color = imagecolorclosest($bw_img, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($bw_img, $x, $y, ($x+$iris-1), ($y+$iris-1), $shade);
        if ($debug_level == 3) {
          header('Content-type: image/png');
          imagepng($bw_img);
          exit;
        }
      }
    }
    if ($debug_level == 4) {
      header('Content-type: image/png');
      imagepng($bw_img);
      exit;
    }
    $im_sig = imagecreatetruecolor($sig, $sig);
    imagecopyresampled($im_sig, $bw_img, 0, 0, 0, 0, $sig, $sig, $rezz, $rezz);
    if ($debug_level == 5) {
      header('Content-type: image/png');
      imagepng($im_sig);
      exit;
    }
    $data = array();
    for($x=0; $x<$sig; $x++) {
      for($y=0; $y<$sig; $y++) {
        $color = imagecolorat($im_sig,$x,$y);
        $pix = array('R'=>($color>>16)&0xFF,'G'=>($color>>8)&0xFF,'B'=>$color&0xFF);
        $data[] = round(($pix['R'] + $pix['G'] + $pix['B']) / 3);
      }
    }
    $output['dna'] = $data;
    $output['sum'] = array_sum($data);

    return $output;
  }
}
