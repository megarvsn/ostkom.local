<?
global $invoice_dir;
$invoice_dir="/upload/invoices/PDF/";

//$file_path = 'http://ostkom.nikanet.ru'.$invoice_dir."m-01-002-003-028038-0-1217.pdf";
$file_path = $invoice_dir."m-01-002-003-028038-0-1217.pdf";
//header('Content-Type: application/pdf');
//readfile($file_path);
?>
<?php
echo filesize($file_path);
/*
header("Content-Type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Length: ".filesize($file_path));
header("Content-Disposition: attachment; filename=".$file_path);  

// Прочитать файл
readfile($file_path);
*/
?>