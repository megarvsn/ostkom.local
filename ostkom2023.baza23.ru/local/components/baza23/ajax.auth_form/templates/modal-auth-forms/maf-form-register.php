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

    ?><div id="<?= \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID ?>" class="form-wrapper maf-register"><?

} else {
    ob_start();
}

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

if ($_REQUEST["modal"] == "Y") {
    ?></div><? // class="form-wrapper
}

$html = ob_get_contents();
ob_end_clean();

if (intval($GLOBALS["NEW_USER_ID"]) > 0) {
    include __DIR__ . '/maf-form-result.php';
    unset($GLOBALS["NEW_USER_ID"]);

} else {

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
}