<?php
include "class_imagedna.php";
// Prevent Caching
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$f1 = dirname(__FILE__).'/estella.png';
$IDNA = new ImageDNA();
$data1 = $IDNA->img2D($f1,512,32,15,5);

exit;
