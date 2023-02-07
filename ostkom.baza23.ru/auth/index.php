<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);
$APPLICATION->SetTitle("Autorizācija");
?>
<p>Jūs esat reģistrēts un veiksmīgi pilnvarots.</p>
 
<p>Ekrāna augšdaļā izmantojiet administratīvo paneli, lai ātri piekļūtu vietnes struktūras un satura pārvaldības funkcijām. Augšējā paneļa pogas ir atšķirīgas dažādām vietnes sadaļām. Tātad ir paredzētas atsevišķas darbības kopas, lai pārvaldītu lapu statisko saturu, dinamiskās publikācijas (ziņas, katalogs, foto galerija) u.c.</p>
 
<p><a href="/">Atpakaļ uz galveno lapu</a></p><br><br>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>