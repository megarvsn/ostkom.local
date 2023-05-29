<?
if (!defined('SITE_ID') && !empty($_REQUEST['SITE_ID'])) {
    $siteId = $_REQUEST['SITE_ID'];
    if (ctype_alnum($siteId) && strlen($siteId) == 2) define('SITE_ID', $siteId);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!$siteId) $siteId = SITE_ID;

$formIblockCode = $_REQUEST['form_ib_section'];
if (!$formIblockCode) $formIblockCode = "default";

$arFormAttrs = \Baza23\Settings::psf_form_all($formIblockCode);
$arErrors = \Baza23\WebForms::psf_wf_getErrors($arFormAttrs["errors"]);

$arRet = ["ERRORS" => $arErrors];
echo json_encode($arRet);