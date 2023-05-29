<?
global $APPLICATION, $USER;

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

    ?><div id="<?= \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID ?>" class="form-wrapper maf-login"><?

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