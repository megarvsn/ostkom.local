<?
global $APPLICATION, $USER;

$uParams["USE_EMAIL_AS_LOGIN"] = $arFormAttrs["settings"]["use-email-as-login"]["PREVIEW_TEXT"];
if ($uParams["USE_EMAIL_AS_LOGIN"] == "Y" && isset($_REQUEST['REGISTER'])) {
    $email = trim($_REQUEST["REGISTER"]['EMAIL']);
    $login = trim($_REQUEST["REGISTER"]['LOGIN']);

    if (!$login) $_REQUEST["REGISTER"]['LOGIN'] = $email;
    elseif (!$email && check_email($login)) $_REQUEST["REGISTER"]['EMAIL'] = $login;
}

ob_start();

//Компонент авторизации с шаблоном errors выводит только ошибки
$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form",
    "errors",
    Array(
        "REGISTER_URL"        => "",
        "FORGOT_PASSWORD_URL" => "",
        "PROFILE_URL"         => "",
        "SHOW_ERRORS"         => "Y",
    )
);

$uParams["ERROR_HTML"] = ob_get_contents();
ob_end_clean();

if (!$ajax) {
    ?><div id="<?= $formCssId ?>" class="form-wrapper"><?

} else {
    ob_start();
}

?><div class="af-register"><?

$APPLICATION->IncludeComponent(
    "bitrix:main.register",
    $uParams["TEMPLATE_NAME"],
    Array(
        "AUTH" => "Y",
        "COMPONENT_TEMPLATE" => $uParams["TEMPLATE_NAME"],
        "REQUIRED_FIELDS" => array(
            0 => "EMAIL",
        ),
        "SET_TITLE" => "N",
        "SHOW_FIELDS" => array(
            0 => "NAME",
            1 => "LAST_NAME",
            2 => "EMAIL",
            //3 => "PERSONAL_PHONE",
        ),
        "SUCCESS_PAGE" => $uParams["SUCCESS_URL"],
        "USER_PROPERTY" => array(),
        "USER_PROPERTY_NAME" => "",
        "USE_BACKURL" => ($arFormAttrs["settings"]["use-backurl"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N"),

        "U_PARAMS" => $uParams
    )
);

?></div><? // class="af-register"

if (!$ajax) {
    ?></div><? // class="form-wrapper

} else {

    $html = ob_get_contents();
    ob_end_clean();

    if (intval($GLOBALS["NEW_USER_ID"]) > 0) {
        include __DIR__ . '/af-form-result.php';
        unset($GLOBALS["NEW_USER_ID"]);

    } else {

        $arRes = [
            "RESULT" => "form",
            "MODAL" => "N",
            "AUTH_FORM" => "Y",
            "FORM" => $arAFParams["CODE"],
            "AJAX" => ($ajax ? "Y" : "N"),
            "TYPE" => $formCssId,
            "HTML" => $html
        ];

        echo json_encode($arRes);
    }
}