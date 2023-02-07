<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/invoice/import_invoices.php" );
$invoice_dir="/upload/invoices/PDF/";
$file_name=explode("/",decode($_GET['f'], ENCRYPTION_KEY));
$file_name=$file_name[0];
$file_path = "/var/www/html/ostkom".$invoice_dir.$file_name;
//$file_path = "/home/n/nikanet533/ostkom/public_html".$invoice_dir.$file_name;
if(file_exists ($file_path)){
//echo"$file_path ";  echo" $file_name "; echo filesize($file_path);
?>
<?php
//echo filesize($file_path);

header("Content-Type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Content-Length: ".filesize($file_path));
header("Content-Disposition: attachment; filename=".$file_name);  
readfile($file_path);
}
else{
	 echo"File is missing";
}	
?>
