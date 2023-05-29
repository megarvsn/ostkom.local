<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class AuthForms {
    const REGISTRATION_CHECK = "Y";
    const REGISTRATION_PROCESS = "N";

    const REGISTRATION_SUBSCRIBE = "N";

    const REGISTRATION_REQUIRED_FIELDS = [
        "EMAIL", "LOGIN", "PASSWORD", "CONFIRM_PASSWORD"
    ];

    const USER_PROFILE_CHECK = "Y";
    const USER_PROFILE_PROCESS = "N";

    const USER_PROFILE_REQUIRED_FIELDS = [
        "EMAIL", "LOGIN", "NEW_PASSWORD", "NEW_CONFIRM_PASSWORD", "CURRENT_PASSWORD"
    ];

    public function __construct() {
    }

    /**
     * Подключаем проверку форм авторизации и регистрации
     */
    public function initCheckAuthForms() {
        $EventManager = EventManager::getInstance();

        if (self::REGISTRATION_CHECK == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserRegister', ['\Baza23\AuthForms', 'beforeRegistration']);
        }
        if (self::REGISTRATION_PROCESS == "Y") {
            $EventManager->addEventHandler('main', 'OnAfterUserRegister', ['\Baza23\AuthForms', 'afterRegistration']);
        }

        if (self::USER_PROFILE_CHECK == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserUpdate', ['\Baza23\AuthForms', 'beforeUserUpdate']);
        }
        if (self::USER_PROFILE_PROCESS == "Y") {
            $EventManager->addEventHandler('main', 'OnAfterUserUpdate', ['\Baza23\AuthForms', 'afterUserUpdate']);
        }
    }

    public static function beforeRegistration(&$p_arFields) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $arErrors = [];

        if (isset($p_arFields["NAME"])) {
            $nameValue = $p_arFields["NAME"];
            $required = in_array("NAME", self::REGISTRATION_REQUIRED_FIELDS);
            if ($name = self::psf_check_name($arErrors, $nameValue, "name", $required)) {
                $p_arFields["NAME"] = $name;
            }
        }

        if (isset($p_arFields["LAST_NAME"])) {
            $nameValue = $p_arFields["LAST_NAME"];
            $required = in_array("LAST_NAME", self::REGISTRATION_REQUIRED_FIELDS);
            if ($name = self::psf_check_name($arErrors, $nameValue, "last-name", $required)) {
                $p_arFields["LAST_NAME"] = $name;
            }
        }

        if (isset($p_arFields["EMAIL"])) {
            $emailValue = $p_arFields["EMAIL"];
            $required = in_array("EMAIL", self::REGISTRATION_REQUIRED_FIELDS);
            if ($email = self::psf_check_email($arErrors, $emailValue, "email", $required)) {
                $p_arFields["EMAIL"] = $email;
            }
        }

        if (isset($p_arFields["PHONE"])) {
            $phoneValue = $p_arFields["PHONE"];
            $required = in_array("PHONE", self::REGISTRATION_REQUIRED_FIELDS);
            if ($phone = self::psf_check_phone($arErrors, $phoneValue, "phone", $required)) {
                $p_arFields["PHONE"] = $phone;
            }
        }

        if (isset($p_arFields["LOGIN"])) {
            $loginValue = $p_arFields["LOGIN"];
            $required = in_array("LOGIN", self::REGISTRATION_REQUIRED_FIELDS);
            if ($login = self::psf_check_login($arErrors, $loginValue, "login", $required)) {
                $p_arFields["LOGIN"] = $login;
            }
        }

        if (isset($p_arFields["PASSWORD"])) {
            $passValue = $p_arFields["PASSWORD"];
            $required = in_array("PASSWORD", self::REGISTRATION_REQUIRED_FIELDS);
            if ($pass = self::psf_check_password($arErrors, $passValue, "password", $required)) {
                $p_arFields["PASSWORD"] = $pass;
            }
        }

        if (isset($p_arFields["CONFIRM_PASSWORD"])) {
            $passValue = $p_arFields["PASSWORD"];
            $confPassValue = $p_arFields["CONFIRM_PASSWORD"];
            $required = in_array("CONFIRM_PASSWORD", self::REGISTRATION_REQUIRED_FIELDS);
            if ($confPass = self::psf_check_confirmPassword($arErrors, $confPassValue, $passValue, "confirm-password", $required)) {
                $p_arFields["CONFIRM_PASSWORD"] = $confPass;
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

    /**
     * Действия после регистрации пользователя
     */
    public static function afterRegistration(&$p_arFields) {
        if (self::REGISTRATION_SUBSCRIBE == "Y") {
            self::psf_subscribe($arFields['EMAIL']);
        }

        return true;
    }

    public static function beforeUserUpdate(&$p_arFields) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $arErrors = [];

        if (isset($p_arFields["NAME"])) {
            $nameValue = $p_arFields["NAME"];
            $required = in_array("NAME", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($name = self::psf_check_name($arErrors, $nameValue, "name", $required)) {
                $p_arFields["NAME"] = $name;
            }
        }

        if (isset($p_arFields["LAST_NAME"])) {
            $nameValue = $p_arFields["LAST_NAME"];
            $required = in_array("LAST_NAME", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($name = self::psf_check_name($arErrors, $nameValue, "last-name", $required)) {
                $p_arFields["LAST_NAME"] = $name;
            }
        }

        if (isset($p_arFields["EMAIL"])) {
            $emailValue = $p_arFields["EMAIL"];
            $required = in_array("EMAIL", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($email = self::psf_check_email($arErrors, $emailValue, "email", $required)) {
                $p_arFields["EMAIL"] = $email;
            }
        }

        if (isset($p_arFields["PERSONAL_PHONE"])) {
            $phoneValue = $p_arFields["PERSONAL_PHONE"];
            $required = in_array("PERSONAL_PHONE", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($phone = self::psf_check_phone($arErrors, $phoneValue, "phone", $required)) {
                $p_arFields["PERSONAL_PHONE"] = $phone;
            }
        }

        if (isset($p_arFields["LOGIN"])) {
            $loginValue = $p_arFields["LOGIN"];
            $required = in_array("LOGIN", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($login = self::psf_check_login($arErrors, $loginValue, "login", $required)) {
                $p_arFields["LOGIN"] = $login;
            }
        }

        if (isset($p_arFields["NEW_PASSWORD"])) {
            $passValue = $p_arFields["NEW_PASSWORD"];
            $required = in_array("NEW_PASSWORD", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($pass = self::psf_check_password($arErrors, $passValue, "password", $required)) {
                $p_arFields["NEW_PASSWORD"] = $pass;
            }
        }

        if (isset($p_arFields["NEW_CONFIRM_PASSWORD"])) {
            $passValue = $p_arFields["PASSWORD"];
            $confPassValue = $p_arFields["NEW_CONFIRM_PASSWORD"];
            $required = in_array("NEW_CONFIRM_PASSWORD", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($confPass = self::psf_check_confirmPassword($arErrors, $confPassValue, $passValue, "confirm-password", $required)) {
                $p_arFields["NEW_CONFIRM_PASSWORD"] = $confPass;
            }
        }

        if (isset($p_arFields["CURRENT_PASSWORD"])
                || isset($_REQUEST["CURRENT_PASSWORD"])) {
            $passValue = (isset($p_arFields["CURRENT_PASSWORD"])
                    ? $p_arFields["CURRENT_PASSWORD"]
                    : $_REQUEST["CURRENT_PASSWORD"]);
            $userId = $p_arFields["ID"];
            $required = in_array("CURRENT_PASSWORD", self::USER_PROFILE_REQUIRED_FIELDS);
            if ($currPass = self::psf_check_currentPassword($arErrors, $passValue, $userId, "current-password", $required)) {
                $p_arFields["CURRENT_PASSWORD"] = $currPass;
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

    /**
     * Действия после изменения данных пользователя
     */
    public static function afterUserUpdate(&$p_arFields) {
        return true;
    }

    public static function psf_subscribe($p_email) {
        if ($_REQUEST["subscribe"] == "Y"
                || $_REQUEST["subscribe"] == "on") {

            if (check_email($p_email) && Loader::includeModule("subscribe")) {
                $subscriptionId = self::psf_subscription_getId($p_email);
                if ($subscriptionId <= 0) {
                    $arRub = self::psf_subscription_getRubrics(false);
                    $format = trim($_REQUEST['FORMAT']);

                    global $USER;
                    $arFields = Array(
                            "ACTIVE"        => "Y",
                            "USER_ID"       => ($USER -> IsAuthorized() ? $USER -> GetID() : false),
                            "FORMAT"        => ($format ? $format : 'html'),
                            "EMAIL"         => $p_email,
                            "RUB_ID"        => $arRub,
                            "SEND_CONFIRM"  => "N",
                            "CONFIRMED"     => "Y",
                    );

                    $subscr = new \CSubscription;
                    //can add without authorization
                    $ID = $subscr -> Add($arFields);
                    if ($ID <= 0) AddMessage2Log(Loc::getMessage("ERROR_ADDING_SUBSCRIPTION") . $subscr -> LAST_ERROR, "ERROR_ADDING_SUBSCRIPTION");
                }
            }
        }
        return true;
    }

    public static function psf_subscription_getId($p_email) {
        if (!$p_email) return 0;

        $ret = 0;
        $dbSubscr = \CSubscription::GetByEmail($p_email);
        if ($arSubscr = $dbSubscr -> Fetch()) {
            $ret = $arSubscr["ID"];
        }
        return $ret;
    }

    public static function psf_subscription_getRubrics($p_includeHidden) {
        $arRet = array();
        $arFilter = array("ACTIVE" => "Y");
        if (!$p_includeHidden) $arFilter["VISIBLE"] = "Y";

        $dbRubric = \CRubric::GetList(
            array("SORT" => "ASC", "NAME" => "ASC"),
            $arFilter
        );
        while ($arRubric = $dbRubric -> Fetch()):
            $arRet[$arRubric["ID"]] = $arRubric;
        endwhile;
        return $arRet;
    }

    public static function psf_pass_getPolicy() {
        global $USER;

        $def_group = \COption::GetOptionString("main", "new_user_registration_def_group", "");
        if ($def_group != "") {
            $GROUP_ID = explode(",", $def_group);
            $arPolicy = $USER->GetGroupPolicy($GROUP_ID);
        } else {
            $arPolicy = $USER->GetGroupPolicy(array());
        }
        return $arPolicy;
    }

    public static function psf_pass_getMinLength() {
        $arPolicy = self::psf_pass_getPolicy();
        return ($arPolicy["PASSWORD_LENGTH"] ? $arPolicy["PASSWORD_LENGTH"] : 8);
    }

    public static function psf_pass_getRequirements() {
        $arPolicy = self::psf_pass_getPolicy();
        return $arPolicy["PASSWORD_REQUIREMENTS"];
    }

    public static function psf_pass_generate() {
        $arPolicy = self::psf_pass_getPolicy();

        $passwordMinLength = self::psf_pass_getMinLength() + rand(1, 5);
        $passwordChars = array(
                "abcdefghijklnmopqrstuvwxyz",
                "ABCDEFGHIJKLNMOPQRSTUVWXYZ",
                "0123456789",
        );
        if ($arPolicy["PASSWORD_PUNCTUATION"] === "Y") {
            $passwordChars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
        }

        return \Bitrix\Main\Security\Random::getString($passwordMinLength, $passwordChars);
    }

    // auth forms

    const AF_MODAL_CSS_ID = 'maf-modal';
    const AF_MODAL_FORM_CSS_ID = 'maf-form-wrapper';
    const AF_FORM_CSS_ID = 'af-form-wrapper';

    const AUTH_FORM_ATTRS = [
        "login"       => [
            "CODE"  => "login",
            "MODAL" => [
                "TEMPLATE_NAME"            => "",
                "IBLOCK_FORM_SECTION_CODE" => "login",
            ]
        ],
        "register"    => [
            "CODE"  => "register",
            "MODAL" => [
                "TEMPLATE_NAME"            => "",
                "IBLOCK_FORM_SECTION_CODE" => "register",
            ]
        ],
        "send_pwd"    => [
            "CODE"  => "send_pwd",
            "MODAL" => [
                "TEMPLATE_NAME"            => "",
                "IBLOCK_FORM_SECTION_CODE" => "forgot-pass",
            ]
        ],
        "change_pwd"  => [
            "CODE"  => "change_pwd",
            "MODAL" => [
                "TEMPLATE_NAME"            => "",
                "IBLOCK_FORM_SECTION_CODE" => "change-pass",
            ]
        ],
        "confirm_reg" => [
            "CODE"  => "confirm_reg",
            "MODAL" => [
                "TEMPLATE_NAME"            => "",
                "IBLOCK_FORM_SECTION_CODE" => "confirm-reg",
            ]
        ],

        "user_profile" => [
            "CODE"  => "user_profile",
            "TEMPLATE_NAME"            => "",
            "IBLOCK_FORM_SECTION_CODE" => "user-profile",
        ],
        "user_change_pass" => [
            "CODE"  => "user_change_pass",
            "MODAL" => [
                "TEMPLATE_NAME"            => "change-pass",
                "IBLOCK_FORM_SECTION_CODE" => "user-change-pass",
            ]
        ],
    ];

    public static function psf_af_attrsByCode($p_arAuthFormCode) {
        if (empty($p_arAuthFormCode)) return false;
        return self::AUTH_FORM_ATTRS[$p_arAuthFormCode];
    }

    public static function psf_af_getLabels($p_arField, $p_arRequired) {
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

    public static function psf_af_getErrors($p_arErrors) {
        $arRet = [];
        foreach ($p_arErrors as $arError) {
            if ($arError["CODE"] == 'pass-too-short') {
                $length = \Baza23\AuthForms::psf_pass_getMinLength();
                $arError["PREVIEW_TEXT"] = \Baza23\Utils::psf_strReplace(
                        $arError["PREVIEW_TEXT"], ['#LENGTH#' => $length]);
            }

            $arRet[$arError["CODE"]] = $arError["PREVIEW_TEXT"];
        }
        return $arRet;
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

    public static function psf_check_login(&$p_arErrors, $loginValue, $field, $required = false) {
        $login = trim($loginValue);
        if (empty($login)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        $chars = '\<\>\/\'\"\[\]\{\}\|\`\~\\\(\)';
        if (mb_strpos($login, ' ')) {
            $p_arErrors[$field] = "login-spaces";
            return false;

        } elseif (preg_match('/[' . $chars . ']/', $login) == 1) {
            $p_arErrors[$field] = "login-incorrect";
            return false;

        } elseif (mb_strlen($login) < 3) {
            $p_arErrors[$field] = "login-too-short";
            return false;

        } elseif (mb_strlen($login) > 30) {
            $p_arErrors[$field] = "login-too-long";
            return false;
        }

        return $login;
    }

    public static function psf_check_password(&$p_arErrors, $passValue, $field, $required = false) {
        $pass = trim($passValue);
        if (empty($pass)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        $minLength = self::psf_pass_getMinLength();
        if (mb_strpos($pass, ' ')) {
            $p_arErrors[$field] = "pass-spaces";
            return false;

        } elseif (mb_strlen($pass) < $minLength) {
            $p_arErrors[$field] = "pass-too-short";
            return false;

        } elseif (mb_strlen($pass) > 30) {
            $p_arErrors[$field] = "pass-too-long";
            return false;
        }
        return $pass;
    }

    public static function psf_check_confirmPassword(&$p_arErrors, $confirmPassValue, $passValue, $field, $required = false) {
        $confirmPass = trim($confirmPassValue);
        if (empty($confirmPass)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        if ($confirmPass !== $passValue) {
            $p_arErrors[$field] = "pass-unequal";
            return false;
        }

        return $confirmPass;
    }

    public static function psf_check_currentPassword(&$p_arErrors, $passValue, $userId, $field, $required = false) {
        $pass = trim($passValue);
        if (empty($pass)) {
            if ($required) {
                $p_arErrors[$field] = "required-field";
                return false;
            }
            return "";
        }

        if (!self::psf_isUserPassword($userId, $pass)) {
            $p_arErrors[$field] = "pass-invalid";
            return false;
        }
        return $pass;
    }

    public static function psf_isUserPassword($userId, $password) {
        $rsUser = \CUser::GetByID($userId);
        $checkUserPassword = false;

        if ($arUser = $rsUser->Fetch()) {
            $hashLength = strlen($arUser["PASSWORD"]);

            if ($hashLength > 100) {
                $salt = substr($arUser["PASSWORD"], 3, 16);
                $hashPassword = crypt($password, "$6$" . $salt . "$");

            } else if ($hashLength > 32) {
                $salt = substr($arUser["PASSWORD"], 0, $hashLength - 32);
                $hashPassword = $salt . md5($salt . $password);

            } else {
                $salt = "";
                $hashPassword = $arUser["PASSWORD"];
            }

            $checkUserPassword = ($hashPassword == $arUser["PASSWORD"]);
        }

        return $checkUserPassword;
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
}