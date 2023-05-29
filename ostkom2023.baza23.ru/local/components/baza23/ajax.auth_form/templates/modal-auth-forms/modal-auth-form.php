<?
if (!defined('SITE_ID') && !empty($_REQUEST['SITE_ID'])) {
    $siteId = $_REQUEST['SITE_ID'];
    if (ctype_alnum($siteId) && strlen($siteId) == 2) define('SITE_ID', $siteId);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!$siteId) $siteId = SITE_ID;

$formId = $_REQUEST["TYPE"];
if (!$formId) $formId = strtoupper($_REQUEST["form"]);

if ($formId == "AUTH" || $formId == "LOGIN") $formId = "login";
elseif ($formId == "REGISTRATION" || $formId == "REGISTER") $formId = "register";
elseif ($formId == "FORGOT_PASS" || $formId == "SEND_PWD") $formId = "send_pwd";
elseif ($formId == "CHANGE_PASS" || $formId == "CHANGE_PWD") $formId = "change_pwd";
elseif ($formId == "CONFIRM_REG") $formId = "confirm_reg";
else $formId = "login";

global $APPLICATION, $USER;

$arResultMessage = false;
if (isset($APPLICATION->arAuthResult) && $APPLICATION->arAuthResult !== true) {
    $arResultMessage = $APPLICATION->arAuthResult;
}

if ($arResultMessage["TYPE"] == "OK"
        || strtolower($_REQUEST["formresult"]) == "ok"
        || $USER->IsAuthorized()) {

    include __DIR__ . '/maf-form-result.php';

} else {

    include __DIR__ . '/maf-form.php';

    switch ($formId) {
        //---------- Подтверждение регистрации ----------//
        case "confirm_reg":
            include __DIR__ . '/maf-form-confirm-reg.php';
            break;

        //---------- Вспомнить пароль ----------//
        case "send_pwd":
            include __DIR__ . '/maf-form-forgot-pass.php';
            break;

        //---------- Изменить пароль ----------//
        case "change_pwd":
            include __DIR__ . '/maf-form-change-pass.php';
            break;

        //---------- Регистрация ----------//
        case "register":
            include __DIR__ . '/maf-form-register.php';
            break;

        //---------- Авторизация по умолчанию ----------//
        default:
            include __DIR__ . '/maf-form-login.php';
    }
}