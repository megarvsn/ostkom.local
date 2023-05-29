<?
$uniqueKey = $_REQUEST["af_params_key"];
if ($uniqueKey) {
    $objParams = \Baza23\Ajax::psf_getObject($uniqueKey);
    if (!empty($objParams)) {
        $uParams = $objParams["OBJECT"];
        $arAFParams = \Baza23\AuthForms::psf_af_attrsByCode($uParams["FORM"]["CODE"]);
    }
    unset($objParams);

} else {
    $arAFParams = \Baza23\AuthForms::psf_af_attrsByCode($formId);
}

if (empty($uParams)
        || empty($uParams["FORM"]["CODE"])
        || $uParams["FORM"]["CODE"] != $formId) {

    $ibSectionCode = ($_REQUEST["ib_section_code"]
            ? $_REQUEST["ib_section_code"]
            : $arAFParams["MODAL"]["IBLOCK_FORM_SECTION_CODE"]);
    if ($ibSectionCode) {
        $arFormAttrs = \Baza23\Settings::psf_form_all($ibSectionCode);
    } else {
        $arFormAttrs = \Baza23\Settings::psf_form_all('defaults');
    }

    $modalCssId = ($_REQUEST["modal"] == 'Y' ? \Baza23\AuthForms::AF_MODAL_CSS_ID : false);
    $dataBlock = '#' . \Baza23\AuthForms::AF_MODAL_FORM_CSS_ID;

    $dataPhp = false;
    if ($templateFolder) {
        $dataPhp = $templateFolder . '/modal-auth-form.php';
    } elseif ($_REQUEST['data-php']) {
        $dataPhp = $_REQUEST['data-php'];
    } elseif ($_SERVER["SCRIPT_URL"]) {
        $dataPhp = $_SERVER["SCRIPT_URL"];
    } elseif ($_SERVER["REQUEST_URI"]) {
        $dataPhp = $_SERVER["REQUEST_URI"];
    }

    $errorPhp = str_replace('/modal-auth-form.php', '/errors.php', $dataPhp);

    $uParams = array(
        "FORM" => [
            "CODE" => $arAFParams["CODE"],
            "CSS_NAME" => 'af-' . $arAFParams["CODE"],
            "CSS_ID" => '',
            "IBLOCK_SECTION_CODE" => $ibSectionCode
        ],

        "TEMPLATE_NAME" => ($_REQUEST["template_name"]
                ? $_REQUEST["template_name"]
                : $arAFParams["MODAL"]["TEMPLATE_NAME"]),

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

    $pageId = htmlspecialchars($_REQUEST["PAGE_ID"]);
    if (!$pageId && $_REQUEST["ajax"] != "Y") $pageId = \Baza23\Settings::$s_currentPageName;

    $pageUrl = htmlspecialchars($_REQUEST["PAGE_URL"]);
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

    if ($arFormAttrs["user-consent"]["show-user-consent"]["PREVIEW_TEXT"] == "Y") {
        $inputName = 'user_consent';
        $isChecked = ($arFormAttrs["user-consent"]["user-consent-is-checked"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N");
        if ($_REQUEST["user_consent"]) $isChecked = ($_REQUEST["user_consent"] == "Y" ? "Y" : "N");
        $required = ($arFormAttrs["user-consent"]["user-consent-required"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N");

        $consentId = $arFormAttrs["user-consent"]["user-consent-id"]["PREVIEW_TEXT"];
        if ($consentId) {
            $arUserConsent = array(
                "TEMPLATE_NAME" => "custom",
                "CONSENT_ID"    => $consentId,
                "IS_CHECKED"    => $isChecked,
                "IS_LOADED"     => 'N',
                "INPUT_NAME"    => $inputName,
                "REQUIRED"      => $required
            );

            $uParams["SHOW_USER_CONSENT"] = "Y";
            $uParams["USER_CONSENT"] = $arUserConsent;
        }
    }
    if ($arFormAttrs["user-consent"]["show-user-consent-2"]["PREVIEW_TEXT"] == "Y") {
        $inputName2 = 'user_consent_2';
        $isChecked2 = ($arFormAttrs["user-consent"]["user-consent-2-is-checked"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N");
        if ($_REQUEST["user_consent_2"]) $isChecked2 = ($_REQUEST["user_consent_2"] == "Y" ? "Y" : "N");
        $required2 = ($arFormAttrs["user-consent"]["user-consent-2-required"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N");

        $consentId2 = $arFormAttrs["user-consent"]["user-consent-2-id"]["PREVIEW_TEXT"];
        if ($consentId2) {
            $arUserConsent2 = array(
                "TEMPLATE_NAME" => "custom",
                "CONSENT_ID"    => $consentId2,
                "IS_CHECKED"    => $isChecked2,
                "IS_LOADED"     => 'N',
                "INPUT_NAME"    => $inputName2,
                "REQUIRED"      => $required2
            );

            $uParams["SHOW_USER_CONSENT_2"] = "Y";
            $uParams["USER_CONSENT_2"] = $arUserConsent2;
        }
    }

    if ($arFormAttrs["settings"]["show-user-info"]["PREVIEW_TEXT"] == "Y" && $USER->IsAuthorized()) {
        $uParams["USER_INFO"] = \Baza23\Settings::psf_getUserInfo();
    }

    // save $uParams
    $uniqueKey = \Baza23\Ajax::psf_addObject($uParams);

} else {
    $arFormAttrs = \Baza23\Settings::psf_form_all($uParams["FORM"]["IBLOCK_SECTION_CODE"]);

    if ($uParams["SHOW_USER_CONSENT"] == "Y") {
        $uParams["USER_CONSENT"]["IS_CHECKED"] = ($_REQUEST["user_consent"] == "Y" ? "Y" : "N");
    }
    if ($uParams["SHOW_USER_CONSENT_2"] == "Y") {
        $uParams["USER_CONSENT_2"]["IS_CHECKED"] = ($_REQUEST["user_consent_2"] == "Y" ? "Y" : "N");
    }
}

$uParams["FORM"]["ATTRS"] = $arFormAttrs;
$uParams["AF_PARAMS_KEY"] = $uniqueKey;