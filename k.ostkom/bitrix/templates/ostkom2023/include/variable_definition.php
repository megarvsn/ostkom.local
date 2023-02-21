<?
$rootURL = ($APPLICATION->IsHTTPS() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
$currentURL = $rootURL . $APPLICATION->GetCurPage(false);
