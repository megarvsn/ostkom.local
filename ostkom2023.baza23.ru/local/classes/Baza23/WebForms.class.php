<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class WebForms {
    const WEB_FORM_IDS = "16,17,18,19,20,21"; // web forms
    const WEB_FORM_RESULT_IDS = "16,17,18,19,20,21";
    const WEB_FORM_STATUS_IDS = "";

    const ALL_WEB_FORM_EVENTS = "Y";
    protected static $WEB_FORM_EVENTS = array(
        //"FORM_FILLING_callback"
    );

    protected static $WEB_FORM_VALIDATORS = array(
        //"\Baza23\WebForm_ValidatorPhone"
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
        if (self::ALL_WEB_FORM_EVENTS == "Y" || !empty(self::$WEB_FORM_EVENTS)) {
            $EventManager->addEventHandler('main', 'OnBeforeEventAdd', ["\Baza23\WebForms", "checkPostEvent"]);
        }
    }

    public function initValidators() {
        if (!empty(self::$WEB_FORM_VALIDATORS)) {
            $EventManager = EventManager::getInstance();

            foreach (self::$WEB_FORM_VALIDATORS as $class) {
                $EventManager->addEventHandler('form', 'onFormValidatorBuildList', [$class, "getDescription"]);
            }
        }
    }

    public static function checkWebForms($p_WEB_FORM_ID, &$p_arFields, &$p_arrVALUES) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $webformIDs = self::WEB_FORM_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        if ($p_WEB_FORM_ID == 19
                || $p_WEB_FORM_ID == 20
                || $p_WEB_FORM_ID == 21) {
            if (self::psf_checkWebForms($p_WEB_FORM_ID, $p_arFields, $p_arrVALUES)) {
                self::psf_wf19_20_21_checkWebForms($p_WEB_FORM_ID, $p_arFields, $p_arrVALUES);
            }

        } else {
            self::psf_checkWebForms($p_WEB_FORM_ID, $p_arFields, $p_arrVALUES);
        }

        return true;
    }

    public static function processWebFormResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        $webformIDs = self::WEB_FORM_RESULT_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        if ($p_WEB_FORM_ID == 19
                || $p_WEB_FORM_ID == 20
                || $p_WEB_FORM_ID == 21) {
            self::psf_wf19_20_21_processResult($p_WEB_FORM_ID, $p_RESULT_ID);

        } else {
            self::psf_processResult($p_WEB_FORM_ID, $p_RESULT_ID);
        }

        return true;
    }

    public static function processWebFormStatus($p_WEB_FORM_ID, $p_RESULT_ID, $p_NEW_STATUS_ID, $p_CHECK_RIGHTS) {
        $webformIDs = self::WEB_FORM_STATUS_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs)) return true;

        return true;
    }

    public static function psf_checkWebForms($p_WEB_FORM_ID, &$p_arFields, &$p_arrVALUES) {
        if (!$p_WEB_FORM_ID || !Loader::IncludeModule("form")) return false;

        $arQuestions = self::psf_question_getMap($p_arrVALUES);
        if (empty($arQuestions)) return false;

        $arErrors = [];

        if (isset($p_arrVALUES[$arQuestions["name"]["NAME"]])) {
            $nameValue = $p_arrVALUES[$arQuestions["name"]["NAME"]];
            $required = ($arQuestions["name"]["REQUIRED"] == "Y");
            if ($name = self::psf_check_name($arErrors, $nameValue, "name", $required, 50)) {
                $p_arrVALUES[$arQuestions["name"]["NAME"]] = $name;
            }
        }

        if (isset($p_arrVALUES[$arQuestions["email"]["NAME"]])) {
            $emailValue = $p_arrVALUES[$arQuestions["email"]["NAME"]];
            $required = ($arQuestions["email"]["REQUIRED"] == "Y");
            if ($email = self::psf_check_email($arErrors, $emailValue, "email", $required)) {
                $p_arrVALUES[$arQuestions["email"]["NAME"]] = $email;
            }
        }

        if (isset($p_arrVALUES[$arQuestions["phone"]["NAME"]])) {
            $phoneValue = $p_arrVALUES[$arQuestions["phone"]["NAME"]];
            $required = ($arQuestions["phone"]["REQUIRED"] == "Y");
            if ($phone = self::psf_check_phone($arErrors, $phoneValue, "phone", $required)) {
                $p_arrVALUES[$arQuestions["phone"]["NAME"]] = $phone;
            }
        }

        if (isset($p_arrVALUES[$arQuestions["message"]["NAME"]])) {
            $messValue = $p_arrVALUES[$arQuestions["message"]["NAME"]];
            $required = ($arQuestions["message"]["REQUIRED"] == "Y");
            if ($mess = self::psf_check_message($arErrors, $messValue, "message", $required)) {
                $p_arrVALUES[$arQuestions["message"]["NAME"]] = $mess;
            }
        }

        if (!empty($arErrors)) {
            $errorText = self::psf_error_text($arErrors);
            $p_arFields["ERROR"] = "Y";

            global $APPLICATION;
            $APPLICATION->ThrowException($errorText);
            return false;
        }

        return true;
    }

    public static function psf_check_email(&$p_arErrors, $emailValue, $field, $required = false) {
        $email = trim($emailValue);
        if (empty($email)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
        } elseif (!\Baza23\Utils::psf_checkEmail($email)) {
            $p_arErrors[$field] = "email-incorrect";
            return false;
        }
        return $email;
    }

    public static function psf_check_phone(&$p_arErrors, $phoneValue, $field, $required = false) {
        $phone = trim($phoneValue);
        if (empty($phone)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
        } else {
            $phone = \Baza23\Utils::psf_clearPhone($phone, true);
            if (!$phone || strlen($phone) != 12) {
                $p_arErrors[$field] = "tel-incorrect";
                return false;
            }
        }
        return $phone;
    }

    public static function psf_check_name(&$p_arErrors, $nameValue, $field, $required = false, $maxLength = 30) {
        $name = trim($nameValue);
        if (empty($name)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        $chars = '\.\,\<\>\/\?\;\:\'\"\[\]\{\}\|\`\~\!\@\#\\\$\%\^\&\*\(\)\_\+\=0123456789';
        if (preg_match('/[' . $chars . ']/', $name) == 1) {
            $p_arErrors[$field] = "name-incorrect";
            return false;

        } elseif (mb_strlen($name) < 2) {
            $p_arErrors[$field] = "name-too-short";
            return false;
        }

        if (mb_strlen($name) > $maxLength) $name = mb_substr($name, 0, $maxLength);
        return $name;
    }

    public static function psf_check_message(&$p_arErrors, $messValue, $field, $required = false, $maxLength = 350) {
        $mess = trim($messValue);
        if (empty($mess)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        $chars = '\<\>';

        if (mb_strpos($mess, 'http') !== false || mb_strpos($mess, 'ftp') !== false) {
            $p_arErrors[$field] = "text-contain-url";
            return false;

        } elseif (preg_match('/[' . $chars . ']/', $mess) == 1) {
            $p_arErrors[$field] = "text-contain-tags";
            return false;

        } elseif (mb_strlen($mess) < 10) {
            $p_arErrors[$field] = "text-too-short";
            return false;
        }

        if ($maxLength > 0 && mb_strlen($mess) > $maxLength) {
            $mess = mb_substr($mess, 0, $maxLength);
        }
        return $mess;
    }

    public static function psf_error_text($p_arErrors) {
        return json_encode($p_arErrors);
    }

    public static function psf_error_parse($p_errorStr) {
        $errorStr = \Baza23\Utils::psf_strFind($p_errorStr, '{', '}', true);
        $arRet = json_decode($errorStr, true);
        if (!is_array($arRet) || empty($arRet)) return false;
        return $arRet;
    }

    protected static function psf_question_getMap($p_arrVALUES) {
        $arFieldList = self::psf_wf_getFieldListInfo($p_arrVALUES);
        if (empty($arFieldList)) return false;

        $arQuestions = [];
        foreach ($arFieldList as $name => $arInfo) {
            if ($arQuestionInfo = self::psf_question_getInfo($arInfo)) {
                $arQuestionInfo["NAME"] = $name;
                $arQuestions[$arQuestionInfo["CODE"]] = $arQuestionInfo;
            }
        }
        return $arQuestions;
    }

    protected static function psf_question_getInfo($p_arFieldInfo) {
        if (empty($p_arFieldInfo)) return false;

        $arRet = false;
        $questionId = false;

        if ($p_arFieldInfo["ANSWER_ID"]) {
            $dbAnswer = \CFormAnswer::GetByID($p_arFieldInfo["ANSWER_ID"]);
            $arAnswer = $dbAnswer->Fetch();
            if ($arAnswer) {
                $questionId = $arAnswer["QUESTION_ID"];
            }

        } elseif ($p_arFieldInfo["QUESTION_ID"]) {
            $questionId = $p_arFieldInfo["QUESTION_ID"];
        }

        if ($questionId) {
            $dbQuestion = \CFormField::GetByID($questionId);
            $arQuestion = $dbQuestion->Fetch();
            if ($arQuestion) {
                $arRet = [
                    "ID"       => $arQuestion["ID"],
                    "CODE"     => $arQuestion["SID"],
                    "REQUIRED" => $arQuestion["REQUIRED"],
                ];
            }
        }
        return $arRet;
    }

    public static function psf_processResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        if (!$p_WEB_FORM_ID || !$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arResult = self::psf_getResult($p_WEB_FORM_ID, $p_RESULT_ID);
        if (empty($arResult)) return false;

        self::psf_updateUtm(
                $p_RESULT_ID,
                $arResult["ANSWER_LIST"]["utm"][0]["ANSWER_ID"]);
        return true;
    }

    public static function psf_updateUtm($p_RESULT_ID, $p_ANSWER_ID) {
        $arUtm = \Baza23\Site::psf_loadUtm();
        if (!$p_RESULT_ID || !$p_ANSWER_ID || empty($arUtm)) return false;

        $fieldSid = "utm";
        $value = \Baza23\Utils::psf_implodeWithKeys($arUtm, PHP_EOL);
        \CFormResult::SetField($p_RESULT_ID, $fieldSid, [$p_ANSWER_ID => $value]);
        return true;
    }

    public static function psf_getResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        if (!$p_WEB_FORM_ID || !$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arFields = array("utm");
        $arAnswers = \CFormResult::GetDataByID($p_RESULT_ID, $arFields, $arResult, $arAnswer2);
        if (empty($arResult)) return false;

        $arValue = array(
            "WEB_FORM_ID" => $p_WEB_FORM_ID,
            "RESULT_ID"   => $p_RESULT_ID,
            "STATUS_ID"   => $arResult["STATUS_ID"],
            "STATUS_CODE" => strtolower($arResult["STATUS_TITLE"]),

            "SITE_ID"     => $arAnswers["site_id"][0]["USER_TEXT"],
            "UTM"         => $arAnswers["utm"][0]["USER_TEXT"],

            "phone"       => $arAnswers["phone"][0]["USER_TEXT"],

            "ANSWER_LIST" => $arAnswers
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
        if (self::ALL_WEB_FORM_EVENTS == "Y") {
            self::psf_post_addSiteFields($p_arFields);
        }

        if (!in_array($p_event, self::$WEB_FORM_EVENTS)) return true;

        return true;
    }

    public static function psf_post_addSiteFields(&$p_arFields) {
        $p_arFields["SITE_LOGO"] = \Baza23\Settings::psf_contacts_getImageSrc('email', 'logo');
        $p_arFields["SITE_NAME"] = \Baza23\Settings::psf_contacts_getText('email', 'company-name');

        $url = \Baza23\Settings::psf_contacts_getText('email', 'site-url');
        $urlText = \Baza23\Settings::psf_contacts_getText('email', 'site-url-text');
        $strUrl = '<a href="' . $url . '" style="color:inherit;">' . $urlText . '</a>';
        $p_arFields["SITE_URL"] = $strUrl;

        $email = \Baza23\Settings::psf_contacts_getText('email', 'email');
        $strEmail = '<a href="' . $email . '" style="color:inherit;">' . $email . '</a>';
        $p_arFields["SITE_EMAIL"] = $strEmail;

        $phone = \Baza23\Settings::psf_contacts_getText('email', 'phone');
        $phoneHref = \Baza23\Utils::psf_clearPhone($phone, true);
        $strPhone = '<a href="tel:' . $phoneHref . '" style="color:inherit;">' . $phone . '</a>';
        $p_arFields["SITE_PHONE"] = $strPhone;
    }

    public static function psf_getLinkFromField($p_field) {
        //preg_match("/(https?\:.*form_show_file.*action\=download)/", $p_field, $arRet);
        preg_match_all("/(https?\:.*form_show_file.*action\=download)/", $p_field, $arRet);
        return ($arRet[1] ? $arRet[1] : false);
    }

    public static function psf_getFileFromLink($p_arLink) {
        $arFiles = [];
        foreach ($p_arLink as $link) {
            $uri = new \Bitrix\Main\Web\Uri($link);
            parse_str($uri->getQuery(), $arQuery);

            $arFile = self::psf_getFileByHash($arQuery["rid"], $arQuery["hash"]);
            if ($arFile["FILE_ID"]) $arFiles[] = $arFile["FILE_ID"];
        }
        return $arFiles;
    }

    protected static function psf_getFileByHash($p_RESULT_ID, $p_HASH) {
        global $DB;

        $err_mess = (\CAllFormResult::err_mess()) . PHP_EOL
                . "Function: GetAnswerFile" . PHP_EOL
                . "Line: ";

        $RESULT_ID = intval($p_RESULT_ID);
        if ($RESULT_ID <= 0 || strlen(trim($p_HASH)) <= 0) return;

        $strSql = "
                SELECT
                    F.ID as FILE_ID,
                    F.FILE_NAME,
                    F.SUBDIR,
                    F.CONTENT_TYPE,
                    F.HANDLER_ID,
                    F.FILE_SIZE,
                    RA.USER_FILE_NAME ORIGINAL_NAME,
                    RA.USER_FILE_IS_IMAGE,
                    RA.FORM_ID, R.USER_ID
                FROM b_form_result R
                LEFT JOIN b_form_result_answer RA ON RA.RESULT_ID=R.ID
                INNER JOIN b_file F ON (F.ID = RA.USER_FILE_ID)
                WHERE R.ID = '" . $p_RESULT_ID . "'
                AND RA.USER_FILE_HASH = '" . $DB->ForSql($p_HASH, 255) . "'
		";

        $dbQuery = $DB->Query($strSql, false, $err_mess . __LINE__);
        $ret = $dbQuery->Fetch();
        return $ret;
    }

    // web forms

    const WF_MODAL_CSS_ID = 'mwf-modal';
    const WF_MODAL_FORM_CSS_ID = 'mwf-form-wrapper';
    const WF_FORM_CSS_ID = 'wf-form-wrapper';

    const WEB_FORM_ATTRS = [
        "callback" => [
            "ID"                       => "16",
            "CODE"                     => "callback",
                "TEMPLATE_NAME"            => "callback",
                "IBLOCK_FORM_SECTION_CODE" => "callback",
            "MODAL" => [
                "TEMPLATE_NAME"            => "callback",
                "IBLOCK_FORM_SECTION_CODE" => "callback",
            ]
        ],
        "question" => [
            "ID"                       => "17",
            "CODE"                     => "question",
            "MODAL" => [
                "TEMPLATE_NAME"            => "question",
                "IBLOCK_FORM_SECTION_CODE" => "question",
            ]
        ],
        "specialist" => [
            "ID"                       => "18",
            "CODE"                     => "specialist",
            "MODAL" => [
                "TEMPLATE_NAME"            => "specialist",
                "IBLOCK_FORM_SECTION_CODE" => "specialist",
            ]
        ],

        "status-receipt" => [
            "ID"                       => "19",
            "CODE"                     => "status-receipt",
            "MODAL" => [
                "TEMPLATE_NAME"            => "status-receipt",
                "IBLOCK_FORM_SECTION_CODE" => "status-receipt",
            ]
        ],
        "status-order" => [
            "ID"                       => "20",
            "CODE"                     => "status-order",
            "MODAL" => [
                "TEMPLATE_NAME"            => "status-order",
                "IBLOCK_FORM_SECTION_CODE" => "status-order",
            ]
        ],
        "buy-1-click" => [
            "ID"                       => "21",
            "CODE"                     => "buy-1-click",
            "MODAL" => [
                "TEMPLATE_NAME"            => "buy-1-click",
                "IBLOCK_FORM_SECTION_CODE" => "buy-1-click",
            ]
        ],
    ];

    public static function psf_wf_attrsByCode($p_arWebFormCode) {
        if (empty($p_arWebFormCode)) return false;
        return self::WEB_FORM_ATTRS[$p_arWebFormCode];
    }

    public static function psf_wf_attrsById($p_arWebFormId) {
        if (!$p_arWebFormId) return false;

        $arRet = false;
        foreach (self::WEB_FORM_ATTRS as $key => $arAttrs) {
            if ($arAttrs["ID"] != $p_arWebFormId) continue;

            $arRet = $arAttrs;
            break;
        }
        return $arRet;
    }

    public static function psf_wf_getFieldListInfo($p_arFieldValues) {
        if (empty($p_arFieldValues)) return false;

        $arRet = [];
        foreach ($p_arFieldValues as $name => $value) {
            $arRet[$name] = self::psf_wf_getFieldInfo($name);
        }
        return $arRet;
    }

    public static function psf_wf_getFieldInfo($p_fieldName) {
        if (strpos($p_fieldName, 'form_') !== 0) return false;
        if (($pos = strpos($p_fieldName, '_', 5)) === false) return false;

        $answerId = '';
        $questionSid = '';

        $pos1 = strpos($p_fieldName, '[]', $pos);
        if ($pos1 === false) $answerId = substr($p_fieldName, $pos + 1);
        else $questionId = substr($p_fieldName, $pos + 1, $pos1 - ($pos + 1));

        return [
            "NAME"         => $p_fieldName,
            "TYPE"         => substr($p_fieldName, 5, $pos - 5),
            "ANSWER_ID"    => $answerId,
            "QUESTION_ID"  => $questionId
        ];
    }

    public static function psf_wf_getFieldName($p_fieldType, $p_fieldId, $p_fieldCode) {
        if ($p_fieldType == "checkbox"
                || $p_fieldType == "multiselect") {
            $name = 'form_' . $p_fieldType . '_' . $p_fieldCode . '[]';

        } elseif ($p_fieldType == "radio"
                || $p_fieldType == "dropdown") {
            $name = 'form_' . $p_fieldType . '_' . $p_fieldCode;

        } else {
            $name = 'form_' . $p_fieldType . '_' . $p_fieldId;
        }
        return $name;
    }

    public static function psf_wf_getFieldAttrs($p_arWebFormResult, $p_fieldCode, $p_defaultValue = false) {
        $arAttrs = $p_arWebFormResult["QUESTIONS"][$p_fieldCode];
        if (empty($arAttrs)) return false;

        $name = self::psf_wf_getFieldName(
                $arAttrs["STRUCTURE"][0]["FIELD_TYPE"],
                $arAttrs["STRUCTURE"][0]["ID"],
                $p_fieldCode);

        $arRet = [
            "QUESTION_ID" => $arAttrs["STRUCTURE"][0]["QUESTION_ID"],
            "FIELD_CODE" => $p_fieldCode,
            "NAME" => $name,
            "LABEL" => $arAttrs["CAPTION"],
            "REQUIRED" => $arAttrs["REQUIRED"],
        ];

        if (!empty($arRet["NAME"])) {
            if ($arAttrs["STRUCTURE"][0]["FIELD_TYPE"] == "checkbox") {
                $arRet["VALUE"] = $p_arWebFormResult["arrVALUES"][$arRet["NAME"]][0];
                if (empty($arRet["VALUE"])) $arRet["VALUE"] = $arAttrs["STRUCTURE"][0]["ID"];
                if (empty($arRet["VALUE"])) $arRet["VALUE"] = $p_defaultValue;
            } else {
                $arRet["VALUE"] = $p_arWebFormResult["arrVALUES"][$arRet["NAME"]];
                if (empty($arRet["VALUE"])) $arRet["VALUE"] = $p_defaultValue;
            }
        }
        return $arRet;
    }

    public static function psf_wf_getLabels($p_arField, $p_arRequired) {
        if (empty($p_arField["ARIA_LABEL"])) {
            $p_arField["ARIA_LABEL"] = ($p_arField["PLACEHOLDER"] ? $p_arField["PLACEHOLDER"] : $p_arField["LABEL"]);
        }
        if ($p_arField["REQUIRED"] != "Y") return $p_arField;

        if ($p_arRequired["show-required-label"]["PREVIEW_TEXT"] == "Y") {
            $p_arField["LABEL"] .= ' ' . $p_arRequired["required-label"]["PREVIEW_TEXT"];
        } elseif ($p_arRequired["show-required-marker"]["PREVIEW_TEXT"] == "Y") {
            $p_arField["LABEL"] .= '&nbsp;' . $p_arRequired["required-marker"]["PREVIEW_TEXT"];
        }

        if ($p_arRequired["show-required-ph-marker"]["PREVIEW_TEXT"] == "Y") {
            $p_arField["PLACEHOLDER"] .= ' ' . $p_arRequired["required-ph-marker"]["PREVIEW_TEXT"];
        }
        return $p_arField;
    }

    public static function psf_wf_getErrors($p_arErrors) {
        $arRet = [];
        foreach ($p_arErrors as $arError) {
            $arRet[$arError["CODE"]] = $arError["PREVIEW_TEXT"];
        }
        return $arRet;
    }

    // check input

    public static function psf_wf19_20_21_checkWebForms($p_WEB_FORM_ID, &$p_arFields, &$p_arrVALUES) {
        if (!$p_WEB_FORM_ID || !Loader::IncludeModule("form")) return false;

        $arQuestions = self::psf_question_getMap($p_arrVALUES);
        if (empty($arQuestions)) return false;

        $arErrors = [];

        if (isset($p_arrVALUES[$arQuestions["city"]["NAME"]])) {
            $cityValue = $p_arrVALUES[$arQuestions["city"]["NAME"]];
            $required = ($arQuestions["city"]["REQUIRED"] == "Y");
            if ($city = self::psf_check_name($arErrors, $cityValue, "city", $required)) {
                $p_arrVALUES[$arQuestions["city"]["NAME"]] = $city;
            }
        }

        if (self::psf_wf19_20_21_check_productId($arErrors,
                $p_arrVALUES[$arQuestions["product_id"]["NAME"]], "product_id")) {
            if ($p_WEB_FORM_ID == 19 || $p_WEB_FORM_ID == 20)
            self::psf_wf19_20_checkResults($p_WEB_FORM_ID, $arErrors,
                    $p_arrVALUES[$arQuestions["product_id"]["NAME"]],
                    $p_arrVALUES[$arQuestions["phone"]["NAME"]],
                    $p_arrVALUES[$arQuestions["email"]["NAME"]]);
        }

        if (!empty($arErrors)) {
            $errorText = self::psf_error_text($arErrors);
            $p_arFields["ERROR"] = "Y";

            global $APPLICATION;
            $APPLICATION->ThrowException($errorText);
            return false;
        }

        return true;
    }

    public static function psf_wf19_20_21_check_productId(&$p_arErrors, $fieldValue, $field) {
        if (intval($fieldValue) <= 0) {
            $p_arErrors[$field] = "empty-product-id";
            return false;
        }
        return true;
    }

    public static function psf_wf19_20_checkResults($p_WEB_FORM_ID, &$p_arErrors, $productId, $phone, $email) {
        if (!Loader::IncludeModule("form")) return true;

        if ($phone) {
            $arFilter["FIELDS"] = [
                [
                    "CODE"              => "product_id", // код поля по которому фильтруем
                    "FILTER_TYPE"       => "text",       // фильтруем по числовому полю
                    "PARAMETER_NAME"    => "USER",       // по значению введенному с клавиатуры
                    "VALUE"             => $productId,   // значение по которому фильтруем
                    "PART"              => 0,            // прямое совпадение со значением (не интервал)
                    "EXACT_MATCH"       => "N"
                ],
                [
                    "CODE"              => "phone",      // код поля по которому фильтруем
                    "FILTER_TYPE"       => "text",       // фильтруем по числовому полю
                    "PARAMETER_NAME"    => "USER",       // по значению введенному с клавиатуры
                    "VALUE"             => $phone,       // значение по которому фильтруем
                    "PART"              => 0,            // прямое совпадение со значением (не интервал)
                    "EXACT_MATCH"       => "N"
                ],
            ];

            $rsResults = \CFormResult::GetList(
                $p_WEB_FORM_ID,
                ($by="s_timestamp"),
                ($order="desc"),
                $arFilter,
                $is_filtered,
                "N"
            );
            if ($arResult = $rsResults -> Fetch()) {
                $p_arErrors['phone'] = "phone-already-added";
            }
        }

        if ($email) {
            $arFilter["FIELDS"] = [
                [
                    "CODE"              => "product_id", // код поля по которому фильтруем
                    "FILTER_TYPE"       => "text",       // фильтруем по числовому полю
                    "PARAMETER_NAME"    => "USER",       // по значению введенному с клавиатуры
                    "VALUE"             => $productId,   // значение по которому фильтруем
                    "PART"              => 0,            // прямое совпадение со значением (не интервал)
                    "EXACT_MATCH"       => "N"
                ],
                [
                    "CODE"              => "email",      // код поля по которому фильтруем
                    "FILTER_TYPE"       => "text",       // фильтруем по числовому полю
                    "PARAMETER_NAME"    => "USER",       // по значению введенному с клавиатуры
                    "VALUE"             => $email,       // значение по которому фильтруем
                    "PART"              => 0,            // прямое совпадение со значением (не интервал)
                    "EXACT_MATCH"       => "N"
                ],
            ];

            $rsResults = \CFormResult::GetList(
                $p_WEB_FORM_ID,
                ($by="s_timestamp"),
                ($order="desc"),
                $arFilter,
                $is_filtered,
                "N"
            );
            if ($arResult = $rsResults -> Fetch()) {
                $p_arErrors['email'] = "email-already-added";
            }
        }

        return true;
    }

    // process results

    public static function psf_wf19_20_21_processResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        if (!$p_WEB_FORM_ID || !$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arResult = self::psf_wf19_20_21_getResult($p_WEB_FORM_ID, $p_RESULT_ID);
        if (empty($arResult)) return false;

        $arProduct = self::psf_wf19_20_21_getProduct($arResult["SITE_ID"], $arResult["product_id"]);
        if (!$arProduct) return false;

        self::psf_updateUtm(
                $p_RESULT_ID,
                $arResult["ANSWER_LIST"]["utm"][0]["ANSWER_ID"]);

        $fieldSid = "product_name";
        $answerId = $arResult["ANSWER_LIST"][$fieldSid][0]["ANSWER_ID"];
        $value = $arProduct["NAME"];
        \CFormResult::SetField($p_RESULT_ID, $fieldSid, [$answerId => $value]);

        if ($arProduct["PREVIEW_PICTURE"]["SRC"]) {
            $fieldSid = "product_picture_src";
            $answerId = $arResult["ANSWER_LIST"][$fieldSid][0]["ANSWER_ID"];
            $value = \Baza23\Site::psf_getFullUrl($arProduct["PREVIEW_PICTURE"]["SRC"]);
            \CFormResult::SetField($p_RESULT_ID, $fieldSid, [$answerId => $value]);
        }

        if ($arProduct["DETAIL_PAGE_URL"]) {
            $fieldSid = "product_url";
            $answerId = $arResult["ANSWER_LIST"][$fieldSid][0]["ANSWER_ID"];
            $value = \Baza23\Site::psf_getFullUrl($arProduct["DETAIL_PAGE_URL"]);
            \CFormResult::SetField($p_RESULT_ID, $fieldSid, [$answerId => $value]);
        }

        if ($arProduct["UP_SHORT_URL"]) {
            $fieldSid = "product_short_url";
            $answerId = $arResult["ANSWER_LIST"][$fieldSid][0]["ANSWER_ID"];
            $value = $arProduct["UP_SHORT_URL"];
            \CFormResult::SetField($p_RESULT_ID, $fieldSid, [$answerId => $value]);
        }
        return true;
    }

    public static function psf_wf19_20_21_getResult($p_WEB_FORM_ID, $p_RESULT_ID) {
        if (!$p_WEB_FORM_ID || !$p_RESULT_ID || !Loader::IncludeModule("form")) return false;

        $arFields = array("site_id", "utm", "product_id",
                "product_name", "product_picture_src", "product_url", "product_short_url");
        $arAnswers = \CFormResult::GetDataByID($p_RESULT_ID, $arFields, $arResult, $arAnswer2);
        if (empty($arResult)) return false;

        $arValue = array(
            "WEB_FORM_ID" => $p_WEB_FORM_ID,
            "RESULT_ID"   => $p_RESULT_ID,
            "STATUS_ID"   => $arResult["STATUS_ID"],
            "STATUS_CODE" => strtolower($arResult["STATUS_TITLE"]),

            "SITE_ID"     => $arAnswers["site_id"][0]["USER_TEXT"],
            "UTM"         => $arAnswers["utm"][0]["USER_TEXT"],

            "phone"  => $arAnswers["phone"][0]["USER_TEXT"],
            "product_id"  => $arAnswers["product_id"][0]["USER_TEXT"],

            "ANSWER_LIST" => $arAnswers
        );
        return $arValue;
    }

    protected static function psf_wf19_20_21_getProduct($p_siteId, $p_productId) {
        if ($p_siteId) {
            $iblockId = \Baza23\Site::psf_getIBlockId("catalog", array("SITE_ID" => $p_siteId));
        } else {
            $iblockId = \Baza23\Site::psf_getIBlockId("catalog");
        }
        if (!$iblockId || !$p_productId) return false;

        $arRet = false;
        if (Loader::includeModule('iblock')) {
            $dbElement = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $iblockId, "ID" => $p_productId],
                false,
                ["nTopCount" => 1],
                ["IBLOCK_ID", "ID", "NAME", "DETAIL_PAGE_URL",
                 "PREVIEW_PICTURE", "PROPERTY_UP_SHORT_URL"]
            );
            if ($arElement = $dbElement->GetNext(false, false)) {
                $arRet = [
                    "ID"              => $arElement["ID"],
                    "NAME"            => $arElement["NAME"],
                    "DETAIL_PAGE_URL" => $arElement["DETAIL_PAGE_URL"],
                    "PREVIEW_PICTURE" => \CFile::GetFileArray($arElement["PREVIEW_PICTURE"]),
                    "UP_SHORT_URL"    => $arElement["PROPERTY_UP_SHORT_URL_VALUE"],
                ];
            }
        }
        return $arRet;
    }
}