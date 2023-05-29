<?
global $APPLICATION, $USER;

ob_start();

if (!$ajax) {
    ?><div id="<?= $formCssId ?>" class="form-wrapper"><?

} else {
    ob_start();
}

$profileUrl = \Baza23\Settings::psf_getUrl("page-profile");

$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form",
    $uParams["TEMPLATE_NAME"],
    Array(
        "REGISTER_URL"        => '',
        "FORGOT_PASSWORD_URL" => '',
        "PROFILE_URL"         => $profileUrl,
        "SHOW_ERRORS"         => "Y",

        "U_PARAMS"            => $uParams
    )
);

?></div><? // class="af-login"

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