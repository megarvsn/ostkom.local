<?
if (!defined('SITE_ID') && !empty($_REQUEST['SITE_ID'])) {
    $siteId = $_REQUEST['SITE_ID'];
    if (ctype_alnum($siteId) && strlen($siteId) == 2) define('SITE_ID', $siteId);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!$siteId) $siteId = SITE_ID;

if ($_REQUEST["formresult"] == "addok") {
    include __DIR__ . '/mwf-form-result.php';
} else {
    include __DIR__ . '/mwf-form.php';
}