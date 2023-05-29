<?
$ajax = ($arResult["AJAX"] != "N");

if ($ajax) {
    if (!defined('SITE_ID') && !empty($_REQUEST['SITE_ID'])) {
        $siteId = $_REQUEST['SITE_ID'];
        if (ctype_alnum($siteId) && strlen($siteId) == 2) define('SITE_ID', $siteId);
    }

    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}

if (!$siteId) $siteId = SITE_ID;

if ($ajax) {
    $formId = strtoupper($_REQUEST["TYPE"]);
    if (!$formId) $formId = strtoupper($_REQUEST["form"]);
} else {
    $formId = strtoupper($arParams["FORM_CODE"]);
}

if ($formId == "AUTH" || $formId == "LOGIN") $formId = "login";
elseif ($formId == "REGISTRATION" || $formId == "REGISTER" || $_REQUEST["register"] == "yes") $formId = "register";
elseif ($formId == "FORGOT_PASS" || $formId == "SEND_PWD" || $_REQUEST["forgot_password"] == "yes") $formId = "send_pwd";
elseif ($formId == "CHANGE_PASS" || $formId == "CHANGE_PWD" || $_REQUEST["change_password"] == "yes") $formId = "change_pwd";
elseif ($formId == "CONFIRM_REG" || $_REQUEST["confirm_registration"] == "yes") $formId = "confirm_reg";
else $formId = "login";

global $APPLICATION, $USER;

$arResultMessage = false;
if (isset($APPLICATION->arAuthResult) && $APPLICATION->arAuthResult !== true) {
    $arResultMessage = $APPLICATION->arAuthResult;
}

if ($arResultMessage["TYPE"] == "OK"
        || strtolower($_REQUEST["formresult"]) == "ok"
        || $USER->IsAuthorized()) {

    include __DIR__ . '/af-form-result.php';

} else {

    include __DIR__ . '/af-form.php';

    switch ($formId) {
        //---------- Подтверждение регистрации ----------//
        case "confirm_reg":
            include __DIR__ . '/af-form-confirm-reg.php';
            break;

        //---------- Вспомнить пароль ----------//
        case "send_pwd":
            include __DIR__ . '/af-form-forgot-pass.php';
            break;

        //---------- Изменить пароль ----------//
        case "change_pwd":
            include __DIR__ . '/af-form-change-pass.php';
            break;

        //---------- Регистрация ----------//
        case "register":
            include __DIR__ . '/af-form-register.php';
            break;

        //---------- Авторизация по умолчанию ----------//
        default:
            include __DIR__ . '/af-form-login.php';
    }
}