<?
$uniqueKey = $_REQUEST["wf_params_key"];
if ($uniqueKey) {
    $objParams = \Baza23\Ajax::psf_getObject($uniqueKey);
    if (!empty($objParams)) {
        $uParams = $objParams["OBJECT"];
        $arWFParams = \Baza23\WebForms::psf_wf_attrsByCode($uParams["FORM"]["CODE"]);
    }
    unset($objParams);

} elseif ($arParams["FORM_CODE"]) {
    $arWFParams = \Baza23\WebForms::psf_wf_attrsByCode($arParams["FORM_CODE"]);
}

$formCssId = \Baza23\WebForms::WF_FORM_CSS_ID . '--' . $arWFParams["CODE"];

if (empty($uParams)) {
    $ibSectionCode = ($arParams["U_PARAMS"]["IBLOCK_FORM_SECTION_CODE"] ?
            $arParams["U_PARAMS"]["IBLOCK_FORM_SECTION_CODE"] : $arWFParams["IBLOCK_FORM_SECTION_CODE"]);
    if ($ibSectionCode) {
        $arFormAttrs = \Baza23\Settings::psf_form_all($ibSectionCode);
    } else {
        $arFormAttrs = \Baza23\Settings::psf_form_all('defaults');
    }

    $dataBlock = '#' . $formCssId;

    $dataPhp = false;
    if ($templateFolder) {
        $dataPhp = $templateFolder . '/web-form.php';
    } elseif ($_REQUEST['data-php']) {
        $dataPhp = $_REQUEST['data-php'];
    } elseif ($_SERVER["SCRIPT_URL"]) {
        $dataPhp = $_SERVER["SCRIPT_URL"];
    } elseif ($_SERVER["REQUEST_URI"]) {
        $dataPhp = $_SERVER["REQUEST_URI"];
    }

    $errorPhp = str_replace('/web-form.php', '/errors.php', $dataPhp);

    $uParams = array(
        "WEB_FORM_ID" => $arWFParams["ID"],

        "FORM" => [
            "CODE" => $arWFParams["CODE"],
            "CSS_NAME" => 'wf-' . $arWFParams["CODE"],
            "CSS_ID" => $arParams["FORM_CSS_ID"],
            "IBLOCK_SECTION_CODE" => $ibSectionCode
        ],

        "TEMPLATE_NAME" => ($arParams["U_PARAMS"]["TEMPLATE_NAME"] ?
                $arParams["U_PARAMS"]["TEMPLATE_NAME"] : $arWFParams["TEMPLATE_NAME"]),

        "AJAX" => $_REQUEST["ajax"],

        "ON_SUBMIT_CLICK" => $arFormAttrs["texts"]["on-submit-click"]["PREVIEW_TEXT"],

        "DATA_PHP" => $dataPhp,
        "DATA_BLOCK" => $dataBlock,

        "ERROR_PHP" => $errorPhp
    );

    $uParams["SUCCESS_URL"] = ($arParams["SUCCESS_URL"] ?
            $arParams["SUCCESS_URL"] : $arFormAttrs["settings"]["success-url"]["PREVIEW_TEXT"]);

    $uParams["CACHE_TYPE"] = ($arParams["CACHE_TYPE"] ? $arParams["CACHE_TYPE"] : "N");
    $uParams["CACHE_TIME"] = ($arParams["CACHE_TIME"] !== false ?
            $arParams["CACHE_TIME"] : \Baza23\Site::psf_getCacheTime("forms"));

    $pageId = htmlspecialchars_decode($_REQUEST["PAGE_ID"]);
    if (!$pageId && !$ajax) $pageId = \Baza23\Settings::$s_currentPageName;

    $pageUrl = htmlspecialchars_decode($_REQUEST["PAGE_URL"]);
    if (!$pageUrl && !$ajax) $pageUrl = \Baza23\Site::psf_getCurrentUrl();

    $uParams["REQUEST"] = array(
        "SITE_ID"          => $siteId,
        "PAGE_ID"          => $pageId,
        "PAGE_URL"         => $pageUrl
    );

    if ($arFormAttrs["user-consent"]["show-user-consent"]["PREVIEW_TEXT"] == "Y") {
        $inputName = 'user_consent';
        $isChecked = ($arFormAttrs["user-consent"]["user-consent-is-checked"]["PREVIEW_TEXT"] == "Y" ? "Y" : "N");
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
$uParams["WF_PARAMS_KEY"] = $uniqueKey;

if (!$ajax) {
    ?><div id="<?= $formCssId ?>" class="form-wrapper"><?

} else {
    ob_start();
}

$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    $uParams["TEMPLATE_NAME"],
    Array(
        "CACHE_TIME" => $uParams["CACHE_TIME"],
        "CACHE_TYPE" => $uParams["CACHE_TYPE"],

        "CHAIN_ITEM_LINK" => "",
        "CHAIN_ITEM_TEXT" => "",
        "EDIT_URL" => "",
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "LIST_URL" => "",
        "SEF_MODE" => "N",
        "SUCCESS_URL" => $uParams["SUCCESS_URL"],
        "USE_EXTENDED_ERRORS" => "Y",
        "VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID"),
        "WEB_FORM_ID" => $uParams["WEB_FORM_ID"],

        "U_PARAMS" => $uParams
    )
);

if (!$ajax) {
    ?></div><? // class="form-wrapper

} else {

    $html = ob_get_contents();
    ob_end_clean();

    $arRes = [
        "RESULT" => "form",
        "WEB_FORM" => "Y",
        "FORM" => $arWFParams["CODE"],
        "AJAX" => ($ajax ? "Y" : "N"),
        "TYPE" => $formCssId,
        "HTML" => $html
    ];

    echo json_encode($arRes);
}