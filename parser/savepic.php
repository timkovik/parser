<?php
$image=$_GET['image'];
$file=explode('/',$image);
$file=end($file);
$r=explode('.',$file);
$r=end($r);
switch($r){
	case 'gif':
	header("Content-type: image/gif");
	break;
	case 'png':
	header("Content-type: image/png");
	break;
	default:
	header("Content-type: image/jpeg");
}
header("Content-Disposition: attachment; filename=$file");
readfile($image);
?>