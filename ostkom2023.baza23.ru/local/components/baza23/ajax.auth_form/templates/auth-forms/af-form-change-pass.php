<?
global $APPLICATION, $USER;

$uParams["HIDE_CHECKWORD"] = $arFormAttrs["settings"]["hide-checkword"]["PREVIEW_TEXT"];
$uParams["HIDE_LOGIN"] = $arFormAttrs["settings"]["hide-login"]["PREVIEW_TEXT"];
$uParams["USE_EMAIL_AS_LOGIN"] = $arFormAttrs["settings"]["use-email-as-login"]["PREVIEW_TEXT"];

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

?><div class="af-change-pass"><?

$APPLICATION->IncludeComponent(
    "bitrix:system.auth.changepasswd",
    $uParams["TEMPLATE_NAME"],
    Array(
        "SHOW_ERRORS" => "Y",

        "U_PARAMS" => $uParams
    )
);

?></div><? // class="af-change-pass"

if (!$ajax) {
    ?></div><? // class="form-wrapper

} else {

    $html = ob_get_contents();
    ob_end_clean();

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