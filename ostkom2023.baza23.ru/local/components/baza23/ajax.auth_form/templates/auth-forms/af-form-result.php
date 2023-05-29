<?
$arAFParams = \Baza23\AuthForms::psf_af_attrsByCode($formId);
if ($arAFParams["IBLOCK_FORM_SECTION_CODE"]) {
    $arFormAttrs = \Baza23\Settings::psf_form_all($arAFParams["IBLOCK_FORM_SECTION_CODE"]);
}
if (empty($arFormAttrs)) $arFormAttrs = \Baza23\Settings::psf_form_all('defaults');

$arStrReplace = [];
if ($arFormAttrs["success"]["use-phone"]["PREVIEW_TEXT"] == "Y") {
    $template = $arFormAttrs["success"]["success-template-phone"]["PREVIEW_TEXT"];
    $phone = \Baza23\Settings::psf_iblock_getText('contacts', 'company-contacts', 'phone');
    if ($template) {
        if ($phone) {
            $arStrReplace[$template] = '<a href="tel:' . \Baza23\Utils::psf_clearPhone($phone, true) . '">' . $phone . '</a>';
        } else {
            $arStrReplace[$template] = '';
        }
    }
}
if ($arFormAttrs["success"]["use-email"]["PREVIEW_TEXT"] == "Y") {
    $template = $arFormAttrs["success"]["success-template-email"]["PREVIEW_TEXT"];
    $email = \Baza23\Settings::psf_iblock_getText('contacts', 'company-contacts', 'email');
    if ($template) {
        if ($email) {
            $arStrReplace[$template] = '<a href="mailto:' . $email . '">' . $email . '</a>';
        } else {
            $arStrReplace[$template] = '';
        }
    }
}
if ($arFormAttrs["success"]["use-user-name"]["PREVIEW_TEXT"] == "Y") {
    $template = $arFormAttrs["success"]["success-template-user-name"]["PREVIEW_TEXT"];
    $arUserInfo = \Baza23\Settings::psf_getUserInfo();
    if ($template) {
        if (!empty($arUserInfo)) {
            $arStrReplace[$template] = $arUserInfo["FORMATTED_NAME"];
        } else {
            $arStrReplace[$template] = '';
        }
    }
}

$text = $arFormAttrs["success"]["success-text"]["PREVIEW_TEXT"];
if (!empty($arStrReplace)) $text = \Baza23\Utils::psf_strReplace($text, $arStrReplace);

$titleHtml = false;
if ($arFormAttrs["success"]["show-success-title"]["PREVIEW_TEXT"] == "Y") {
    $title = $arFormAttrs["success"]["success-title"]["PREVIEW_TEXT"];
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

?><div id="<?= \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID ?>" class="form-wrapper af-success"><?
    if ($text) {
        ?><div class="success-text"><?= $text ?></div><?
    }

    if ($arFormAttrs["success"]["show-success-btn-close"]["PREVIEW_TEXT"] == "Y") {
        $btnClose = $arFormAttrs["success"]["success-btn-close"]["PREVIEW_TEXT"];
        if ($btnClose) {
            ?><div class="success-buttons"><?
                ?><a type="button" aria-label="<?= $btnClose ?>" class="btn--all btn-1 btn-close"><?= $btnClose ?></a><?
            ?></div><?
        }
    }
?></div><? // class="form-wrapper af-success"

$html = ob_get_contents();
ob_end_clean();

$arRes = [
    "RESULT" => "success",
    "RELOAD" => "Y",
    "TIMEOUT" => 1500,
    "MODAL" => "Y",
    "AUTH_FORM" => "Y",
    "TYPE" => \Baza23\AuthForms::AF_MODAL_CSS_ID,
    "TITLE" => $titleHtml,
    "ICON_CLOSE" => $arFormAttrs["modal"]["show-modal-icon-close"]["PREVIEW_TEXT"],
    "HTML" => $html
];

echo json_encode($arRes);