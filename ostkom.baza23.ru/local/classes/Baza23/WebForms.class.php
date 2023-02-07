<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader;

class WebForms {
    const FAKE_FIELD_NAME = 'FIELD_FK';

    const WEB_FORM_IDS = "1,2,3"; // web forms
    const WEB_FORM_RESULT_IDS = "";
    const WEB_FORM_STATUS_IDS = "";

    protected static $WEB_FORM_EVENTS = array(
    );

    public function __construct() {
    }

    /**
     * Подключаем проверку веб форм
     */
    public function initCheckWebForms() {
        $EventManager = EventManager::getInstance();
        if (!empty(self::WEB_FORM_IDS)) {
            $EventManager->addEventHandler('form', 'onBeforeResultAdd', ["\Baza23\WebForms", "checkWebForms"]);
        }
        if (!empty(self::WEB_FORM_RESULT_IDS)) {
            $EventManager->addEventHandler('form', 'onAfterResultAdd', ["\Baza23\WebForms", "processWebFormResult"]);
        }
        if (!empty(self::WEB_FORM_STATUS_IDS)) {
            $EventManager->addEventHandler('form', 'onAfterResultStatusChange', ["\Baza23\WebForms", "processWebFormStatus"]);
        }
        if (!empty(self::$WEB_FORM_EVENTS)) {
            $EventManager->addEventHandler('main', 'OnBeforeEventAdd', ["\Baza23\WebForms", "checkPostEvent"]);
        }
    }

    public function checkWebForms($p_WEB_FORM_ID, &$p_arFields, &$p_arrVALUES) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $webformIDs = self::WEB_FORM_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        $ret = self::checkSpam();
        if (!$ret) {
            \CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => "WEB_FORM_FAIL",
                "MODULE_ID" => "form",
                "SITE_ID" => SITE_ID,
                "ITEM_ID" => "UNKNOWN",
                "DESCRIPTION" => sprintf("Attempt to send a web form by bot.\n%s",
                    var_export($_REQUEST, true)),
            ));

            $p_arFields["ERROR"] = "Y";
        }

        if ($p_WEB_FORM_ID == 1) {
            if (!self::psf_wf1_checkAttrs($p_arrVALUES)) {
                $p_arFields["ERROR"] = "Y";
            }
        }

        return true;
    }

    public function processWebFormResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        $webformIDs = self::WEB_FORM_RESULT_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        return true;
    }

    public function processWebFormStatus($p_WEB_FORM_ID, $p_RESULT_ID, $p_NEW_STATUS_ID, $p_CHECK_RIGHTS) {
        $webformIDs = self::WEB_FORM_STATUS_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        return true;
    }

    public static function psf_getResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        if (!$p_WEB_FORM_ID || !$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arFields = array("site", "page_url");
        $arAnswer = \CFormResult::GetDataByID($p_RESULT_ID, $arFields, $arResult, $arAnswer2);
        if (empty($arResult)) return false;

        $arValue = array(
            "WEB_FORM_ID" => $p_WEB_FORM_ID,
            "RESULT_ID"   => $p_RESULT_ID,
            "STATUS_ID"   => $arResult["STATUS_ID"],
            "STATUS_CODE" => strtolower($arResult["STATUS_TITLE"]),
            "SITE_ID"     => $arAnswer["site"][0]["USER_TEXT"],
            "PAGE_URL"     => $arAnswer["page_url"][0]["USER_TEXT"],
        );
        return $arValue;
    }

    public static function psf_getResultStatusTitle($p_RESULT_ID) {
        if (!$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arAnswer = \CFormResult::GetDataByID($p_RESULT_ID, array(), $arResult, $arAnswer2);
        if (empty($arResult)) return false;

        return strtolower($arResult["STATUS_TITLE"]);
    }

    public static function psf_getAllStatuses($p_FORM_ID) {
        if (!$p_FORM_ID || !Loader::IncludeModule("form")) return false;

        $arRet = array();
        $rsStatuses = CFormStatus::GetList(
            $p_FORM_ID,
            $by = "s_title", $order = "asc",
            array("ACTIVE" => "Y"),
            $is_filtered
        );
        while ($arStatus = $rsStatuses -> fetch()) {
            $arRet[$arStatus["TITLE"]] = $arStatus;
        }
        return $arRet;
    }

    public static function psf_getStatus($p_STATUS_ID) {
        if (!$p_STATUS_ID || !Loader::IncludeModule("form")) return false;

        $rsStatus = \CFormStatus::GetByID($p_STATUS_ID);
        $arStatus = $rsStatus->Fetch();
        return $arStatus;
    }

    public static function psf_getStatusTitle($p_STATUS_ID) {
        $arStatus = self::psf_getStatus($p_STATUS_ID);
        $ret = (empty($arStatus) ? false : strtolower($arStatus["TITLE"]));
        return $ret;
    }

    /** POST EVENT */

    public static function checkPostEvent(&$p_event, &$p_lid, &$p_arFields, &$p_message_id, &$p_files) {
        if (!in_array($p_event, self::$WEB_FORM_EVENTS)) return true;

        return true;
    }

    /**
     * Основной метод проверки капчи
     */
    public function checkSpam() {
        global $APPLICATION;

        // если мы добавляем данные из админки, то не проверяем
        if (preg_match('/^\/bitrix\/admin\/.*$/i', $APPLICATION->GetCurPage())) return true;

        if (isset($_REQUEST[self::FAKE_FIELD_NAME])
				&& strlen(trim($_REQUEST[self::FAKE_FIELD_NAME])) > 0) {
            $APPLICATION->ThrowException("Your actions seem suspicious to us. "
                    . "Try reloading the page and filling out the form again.",
                    "fake-field");
            return false;
        }
        return true;
    }

    public static function psf_wf1_checkAttrs(&$p_arrVALUES) {
        global $APPLICATION;

        if ($p_arrVALUES["form_text_6"]) {
            $phone = self::psf_clearPhone($p_arrVALUES["form_text_6"], true);
            if (strlen($phone) < 7 || strlen($phone) > 11) {
                $APPLICATION->ThrowException("Uncorrect phone number.",
                        "phone-uncorrect");
                return false;
            }

            $p_arrVALUES["form_text_6"] = $phone;

        }

        if (empty(trim($p_arrVALUES["form_hidden_8"]))) {
            $APPLICATION->ThrowException("Uncorrect department.",
                    "department-uncorrect");
            return false;
        }
        return true;
    }

    public static function psf_clearPhone($p_phone, $p_onlyDigit = false) {
        if ($p_onlyDigit) {
            $ret = preg_replace("/[^0-9]/", '', $p_phone);
        } else {
            $ret = filter_var($p_phone, FILTER_SANITIZE_NUMBER_INT);
        }
        return $ret;
    }

}