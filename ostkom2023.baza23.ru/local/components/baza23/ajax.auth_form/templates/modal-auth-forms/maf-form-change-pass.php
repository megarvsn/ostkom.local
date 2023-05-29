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

if ($_REQUEST["modal"] == "Y") {
    $titleHtml = false;
    if ($arFormAttrs["modal"]["show-modal-title"]["PREVIEW_TEXT"] == "Y") {
        $title = $arFormAttrs["modal"]["modal-title"]["PREVIEW_TEXT"];
        if ($title) {
            $iconSrc = false;
            $iconText = false;
            if ($arFormAttrs["modal"]["show-modal-title-icon"]["PREVIEW_TEXT"] == "Y") {
                $code = $arFormAttrs["modal"]["modal-title-icon-code"]["PREVIEW_TEXT"];
                if ($code) {
                    $iconSrc = \Baza23\Settings::psf_icon_getImageSrc($code);
                    if (!$iconSrc) $iconText = \Baza23\Settings::psf_icon_getText($code);
                }
            }

            $titleHtml = '<div class="modal-title">';
            if ($iconSrc) {
                $titleHtml .= '<div class="title-icon" style="background-image: url(' . $iconSrc . ');"></div>';
            } elseif ($iconText) {
                $titleHtml .= '<div class="title-icon">' . $iconText . '</div>';
            }
            $titleHtml .= '<div class="title-text">' . $title . '</div>';
            $titleHtml .= '</div>';
        }
    }

    ob_start();

    ?><div id="<?= \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID ?>" class="form-wrapper maf-change-pass"><?

} else {
    ob_start();
}

$APPLICATION->IncludeComponent(
    "bitrix:system.auth.changepasswd",
    $uParams["TEMPLATE_NAME"],
    Array(
        "SHOW_ERRORS" => "Y",

        "U_PARAMS" => $uParams
    )
);

if ($_REQUEST["modal"] == "Y") {
    ?></div><? // class="form-wrapper
}

$html = ob_get_contents();
ob_end_clean();

$arRes = [
    "RESULT" => "form",
    "MODAL" => "Y",
    "AUTH_FORM" => "Y",
    "FORM" => $arAFParams["CODE"],
    "TYPE" => \Baza23\AuthForms::AF_MODAL_CSS_ID,
    "HTML" => $html
];

if ($_REQUEST["modal"] == "Y") {
    $arRes["RESULT"] = "modal";
    $arRes["ICON_CLOSE"] = $arFormAttrs["modal"]["show-modal-icon-close"]["PREVIEW_TEXT"];
    $arRes["TITLE"] = $titleHtml;

    $arErrors = \Baza23\AuthForms::psf_af_getErrors($arFormAttrs["errors"]);
    if (!empty($arErrors)) $arRes["ERRORS"] = $arErrors;
}

echo json_encode($arRes);