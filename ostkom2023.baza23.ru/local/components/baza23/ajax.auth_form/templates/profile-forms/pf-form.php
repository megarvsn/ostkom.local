<?
$uniqueKey = $_REQUEST["pf_params_key"];
if ($uniqueKey) {
    $objParams = \Baza23\Ajax::psf_getObject($uniqueKey);
    if (!empty($objParams)) {
        $uParams = $objParams["OBJECT"];
        $arPFParams = \Baza23\AuthForms::psf_af_attrsByCode($uParams["FORM"]["CODE"]);
    }
    unset($objParams);

} elseif ($arParams["FORM_CODE"]) {
    $arPFParams = \Baza23\AuthForms::psf_af_attrsByCode($arParams["FORM_CODE"]);
}

$formCssId = \Baza23\AuthForms::AF_FORM_CSS_ID . '--' . $arPFParams["CODE"];

if (empty($uParams)) {
    $ibSectionCode = ($_REQUEST["ib_section_code"]
            ? $_REQUEST["ib_section_code"]
            : $arPFParams["IBLOCK_FORM_SECTION_CODE"]);
    if ($ibSectionCode) {
        $arFormAttrs = \Baza23\Settings::psf_form_all($ibSectionCode);
    } else {
        $arFormAttrs = \Baza23\Settings::psf_form_all('defaults');
    }

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

        "TEMPLATE_NAME" => $_REQUEST["template_name"] ?
                $_REQUEST["template_name"] : $arPFParams["TEMPLATE_NAME"],

        "AJAX" => $_REQUEST["ajax"],

        "ON_SUBMIT_CLICK" => $arFormAttrs["texts"]["on-submit-click"]["PREVIEW_TEXT"],

        "DATA_PHP" => $dataPhp,
        "DATA_BLOCK" => $dataBlock,

        "ERROR_PHP" => $errorPhp
    );

    $uParams["SUCCESS_URL"] = $arFormAttrs["success"]["success-url"]["PREVIEW_TEXT"];

    $uParams["CACHE_TYPE"] = "N";
    $uParams["CACHE_TIME"] = \Baza23\Site::psf_getCacheTime("forms");

    $pageId = htmlspecialchars_decode($_REQUEST["PAGE_ID"]);
    if (!$pageId && !$ajax) $pageId = \Baza23\Settings::$s_currentPageName;

    $pageUrl = htmlspecialchars_decode($_REQUEST["PAGE_URL"]);
    if (!$pageUrl && !$ajax) $pageUrl = \Baza23\Site::psf_getCurrentUrl();

    $uParams["REQUEST"] = array(
        "SITE_ID"          => $siteId,
        "PAGE_ID"          => $pageId,
        "PAGE_URL"         => $pageUrl
    );

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

if (!$ajax) {
    ?><div id="<?= $formCssId ?>" class="form-wrapper"><?

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

if (!$ajax) {
    ?></div><? // class="form-wrapper

} else {

    $html = ob_get_contents();
    ob_end_clean();

    $dataSaved = ($GLOBALS["U_DATA_SAVED"] == "Y");
    if ($dataSaved) {
        include __DIR__ . '/pf-form-result.php';

    } else {
        $arRes = [
            "RESULT" => "form",
            "PROFILE_FORM" => "Y",
            "FORM" => $arPFParams["CODE"],
            "AJAX" => ($ajax ? "Y" : "N"),
            "TYPE" => $formCssId,
            "HTML" => $html
        ];

        echo json_encode($arRes);
    }
}
