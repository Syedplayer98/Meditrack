<?php
	include 'C:/xampp/php/lib/phpqrcode/qrlib.php';
	$st = "hello world";
	$num = 34;
	$var = $st.$num;
	$hash = hash('sha256',$var);
    QRcode::png($hash);
?>