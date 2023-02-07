<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if($_GET['s'])
  $_SESSION['ServiceType']=$_GET['s'];
elseif($_GET['cookie']){
 // $_SESSION['CookieWarning']=$_GET['cookie'];
  setcookie("CookieWarning",$_GET['cookie'],time()+3600000);
}
?>