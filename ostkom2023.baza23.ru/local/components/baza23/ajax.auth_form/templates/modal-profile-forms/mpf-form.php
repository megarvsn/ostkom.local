<?
$uniqueKey = $_REQUEST["pf_params_key"];
if ($uniqueKey) {
    $objParams = \Baza23\Ajax::psf_getObject($uniqueKey);
    if (!empty($objParams)) {
        $uParams = $objParams["OBJECT"];
        $arPFParams = \Baza23\AuthForms::psf_af_attrsByCode($uParams["FORM"]["CODE"]);
    }
    unset($objParams);

} elseif ($_REQUEST["form"]) {
    $arPFParams = \Baza23\AuthForms::psf_af_attrsByCode($_REQUEST["form"]);
}

$formCssId = \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID;

if (empty($uParams)) {
    $ibSectionCode = ($_REQUEST["ib_section_code"]
            ? $_REQUEST["ib_section_code"]
            : $arPFParams["MODAL"]["IBLOCK_FORM_SECTION_CODE"]);
    if ($ibSectionCode) {
        $arFormAttrs = \Baza23\Settings::psf_form_all($ibSectionCode);
    } else {
        $arFormAttrs = \Baza23\Settings::psf_form_all('defaults');
    }

    $modalCssId = ($_REQUEST["modal"] == 'Y' ? \Baza23\AuthForms::AF_MODAL_CSS_ID : false);
    $dataBlock = '#' . $formCssId;

    $dataPhp = false;
    if ($templateFolder) {
        $dataPhp = $templateFolder . '/profile-form.php';
    } elseif ($_REQUEST['data-php']) {
        $dataPhp = $_REQUEST['data-php'];
    } elseif ($_SERVER["SCRIPT_URL"]) {
        $dataPhp = $_SERVER["SCRIPT_URL"];
    } elseif ($_SERVER["REQUEST_URI"]) {
        $dataPhp = $_SERVER["REQUEST_URI"];
    }

    $errorPhp = str_replace('/profile-form.php', '/errors.php', $dataPhp);

    $uParams = array(
        "FORM" => [
            "CODE" => $arPFParams["CODE"],
            "CSS_NAME" => 'pf-' . $arPFParams["CODE"],
            "CSS_ID" => '',
            "IBLOCK_SECTION_CODE" => $ibSectionCode
        ],

        "TEMPLATE_NAME" => $_REQUEST["template_name"]
                ? $_REQUEST["template_name"]
                : $arPFParams["MODAL"]["TEMPLATE_NAME"],

        "AJAX" => htmlspecialchars($_REQUEST["ajax"]),
        "MODAL" => htmlspecialchars($_REQUEST["modal"]),
        "MODAL_CSS_ID" => $modalCssId,

        "ON_SUBMIT_CLICK" => $arFormAttrs["texts"]["on-submit-click"]["PREVIEW_TEXT"],

        "DATA_PHP" => $dataPhp,
        "DATA_BLOCK" => $dataBlock,

        "ERROR_PHP" => $errorPhp
    );

    $uParams["SUCCESS_URL"] = $arFormAttrs["success"]["success-url"]["PREVIEW_TEXT"];

    $uParams["CACHE_TYPE"] = "N";
    $uParams["CACHE_TIME"] = \Baza23\Site::psf_getCacheTime("forms");

    $pageId = htmlspecialchars_decode($_REQUEST["PAGE_ID"]);
    if (!$pageId && $_REQUEST["ajax"] != "Y") $pageId = \Baza23\Settings::$s_currentPageName;

    $pageUrl = htmlspecialchars_decode($_REQUEST["PAGE_URL"]);
    if (!$pageUrl && $_REQUEST["ajax"] != "Y") $pageUrl = \Baza23\Site::psf_getCurrentUrl();

    $uParams["REQUEST"] = array(
        "SITE_ID"          => $siteId,
        "PAGE_ID"          => $pageId,
        "PAGE_URL"         => $pageUrl
    );
    $arSkipKeys = ["SITE_ID", "PAGE_ID", "PAGE_URL", "wf_params_key",
            "form", "modal", "ajax"];
    foreach ($_REQUEST as $key => $value) {
        if (in_array($key, $arSkipKeys)) continue;
        $uParams["REQUEST"][strtoupper($key)] = htmlspecialchars($value);
    }

    if ($arFormAttrs["settings"]["show-user-info"]["PREVIEW_TEXT"] == "Y"
            && $USER->IsAuthorized()) {
        $uParams["USER_INFO"] = \Baza23\Settings::psf_getUserInfo();
    }

    // save $uParams
    $uniqueKey = \Baza23\Ajax::psf_addObject($uParams);

} else {
    $arFormAttrs = \Baza23\Settings::psf_form_all($uParams["FORM"]["IBLOCK_SECTION_CODE"]);
}

$uParams["FORM"]["ATTRS"] = $arFormAttrs;
$uParams["PF_PARAMS_KEY"] = $uniqueKey;

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

    ?><div id="<?= $formCssId ?>" class="form-wrapper mpf-<?= $uParams["FORM"]["CODE"] ?>"><?

} else {
    ob_start();
}

$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	$uParams["TEMPLATE_NAME"],
	Array(
		"CHECK_RIGHTS" => "Y",
		"SEND_INFO" => "Y",
		"SET_TITLE" => "N",
		"USER_PROPERTY" => array(),
		"USER_PROPERTY_NAME" => "",

        "U_PARAMS" => $uParams
	)
);

if ($_REQUEST["modal"] == "Y") {
    ?></div><? // class="form-wrapper
}

$html = ob_get_contents();
ob_end_clean();

$dataSaved = ($GLOBALS["U_DATA_SAVED"] == "Y");
if ($dataSaved) {
    include __DIR__ . '/mpf-form-result.php';

} else {

    $arRes = [
        "RESULT" => "form",
        "MODAL" => "Y",
        "PROFILE_FORM" => "Y",
        "FORM" => $arPFParams["CODE"],
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