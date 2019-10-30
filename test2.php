<?php
include "class_imagedna.php";
// Prevent Caching
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$f1 = dirname(__FILE__).'/estella.png';
$f2 = dirname(__FILE__).'/letseat.jpg';
$IDNA = new ImageDNA();
$data1 = $IDNA->img2D($f1);
$data2 = $IDNA->img2D($f2);

$t1 = $data1['sum'];
$t2 = $data2['sum'];

$h1 = $data1['hashes']['md5'];
$h2 = $data2['hashes']['md5'];

$data1 = implode(",",$data1['dna']);
$data2 = implode(",",$data2['dna']);

echo "Image MD5: $h1\nImageDNA: $data1 = $t1\n\n";
echo "Image MD5: $h2\nImageDNA: $data2 = $t2\n";

exit;
