<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CaptchaFakeField {
    /* Название фейкового поля */
    const FAKE_FIELD_NAME = 'FIELD_FK';
    const USE_EVENT_LOG = 'Y';

    const WEB_FORM_IDS = "16,17,18,19,20,21";
    const FEEDBACK_FORM_POST_IDS = "";
    const REGISTRATION_ENABLED = "N";
    const FORGOT_PASS_ENABLED = "N";
    const SALE_ORDER_ENABLED = "N";

    public function __construct() {
    }

    /**
     * Подключаем проверку на спам
     */
    public function initCheckSpam() {
        $EventManager = EventManager::getInstance();
        if (!empty(self::WEB_FORM_IDS)) {
            $EventManager->addEventHandler('form', 'onBeforeResultAdd', ['Baza23\CaptchaFakeField', 'checkWebForm']);
        }
        if (self::REGISTRATION_ENABLED == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserRegister', ['Baza23\CaptchaFakeField', 'checkRegistration']);
        }
        if (!empty(self::FEEDBACK_FORM_POST_IDS)) {
            $EventManager->addEventHandler('main', 'OnBeforeEventAdd', ['Baza23\CaptchaFakeField', 'checkFeedback']);
        }
        if (self::FORGOT_PASS_ENABLED == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserSendPassword', ["Baza23\CaptchaFakeField", "checkForgotPass"]);
        }
        if (self::SALE_ORDER_ENABLED == "Y") {
            $EventManager->addEventHandler('sale', 'OnBeforeOrderAdd', ['Baza23\CaptchaFakeField', 'checkSaleOrder']);
        }
    }

    /**
     * Проверка форм из модуля веб форм
     */
    public static function checkWebForm($p_WEB_FORM_ID, &$p_arFields, &$p_arValues) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $webformIDs = self::WEB_FORM_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем капчу
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs, true)) return true;

        $ret = self::checkSpam();
        if (!$ret) {
            if (self::USE_EVENT_LOG == "Y") {
                \CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "WEB_FORM_FAIL",
                    "MODULE_ID" => "form",
                    "SITE_ID" => SITE_ID,
                    "ITEM_ID" => "UNKNOWN",
                    "DESCRIPTION" => sprintf(Loc::getMessage('WEB_FORM_FAKE_ERROR_MESSAGE') . "\n%s",
                        var_export($_REQUEST, true)),
                ));
            }

            $p_arFields["ERROR"] = "Y";
        }
        return $ret;
    }

    /**
     * Проверка при регистрации пользователя
     */
    public static function checkRegistration(&$arArgs) {
        $registrationEnabled = self::REGISTRATION_ENABLED;
        if ($registrationEnabled != 'Y') return true;

        if ($arArgs["ERROR"] == "Y") return false;

        $ret = self::checkSpam();
        if (!$ret) {
            if (self::USE_EVENT_LOG == "Y") {
                \CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "USER_REGISTER_FAIL",
                    "MODULE_ID" => "main",
                    "SITE_ID" => SITE_ID,
                    "ITEM_ID" => "UNKNOWN",
                    "DESCRIPTION" => sprintf(Loc::getMessage('REGISTRATION_FAKE_ERROR_MESSAGE') . "\n%s",
                        var_export($_REQUEST, true)),
                ));
            }

            $arArgs["ERROR"] = "Y";
        }
        return $ret;
    }

    /**
     * Проверка при восстановлении пароля пользователя
     */
    public static function checkForgotPass(&$arArgs) {
        $forgotPassEnabled = self::FORGOT_PASS_ENABLED;
        if ($forgotPassEnabled != 'Y') return true;

        if ($arArgs["ERROR"] == "Y") return false;

        $ret = self::checkSpam();
        if (!$ret) {
            if (self::USE_EVENT_LOG == "Y") {
                \CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "USER_FORGOT_PASS_FAIL",
                    "MODULE_ID" => "main",
                    "SITE_ID" => SITE_ID,
                    "ITEM_ID" => "UNKNOWN",
                    "DESCRIPTION" => sprintf(Loc::getMessage('FORGOT_PASS_FAKE_ERROR_MESSAGE') . "\n%s",
                        var_export($_REQUEST, true)),
                ));
            }

            $arArgs["ERROR"] = "Y";
        }
        return $ret;
    }

    /**
     * Проверка при оформлении заказа
     */
    public static function checkSaleOrder(&$arFields) {
        $saleOrderEnabled = self::SALE_ORDER_ENABLED;
        if ($saleOrderEnabled != 'Y') return true;

        if ($arFields["ERROR"] == "Y") return false;

        $ret = self::checkSpam();
        if (!$ret) {
            if (self::USE_EVENT_LOG == "Y") {
                \CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "SALE_ORDER_FAIL",
                    "MODULE_ID" => "sale",
                    "SITE_ID" => SITE_ID,
                    "ITEM_ID" => "UNKNOWN",
                    "DESCRIPTION" => sprintf(Loc::getMessage('SALE_ORDER_FAKE_ERROR_MESSAGE') . "\n%s",
                        var_export($_REQUEST, true)),
                ));
            }

            $arFields["ERROR"] = "Y";
        }
        return $ret;
    }

    /**
     * Проверка при отправки формы обратной связи main.feedback
     */
    public static function checkFeedback(&$event, &$lid, &$arFields, &$messageId, &$files, &$languageId) {
        if ($arFields["ERROR"] == "Y") return false;

        $feedbackIDs = self::FEEDBACK_FORM_POST_IDS;
        if (empty($feedbackIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем капчу
        $feedbackIDs = explode(',', $feedbackIDs);
        if (!in_array((string) $messageId, $feedbackIDs, true)) return true;

        $ret = self::checkSpam();
        if (!$ret) {
            if (self::USE_EVENT_LOG == "Y") {
                \CEventLog::Add(array(
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "FEEDBACK_FAIL",
                    "MODULE_ID" => "form",
                    "SITE_ID" => SITE_ID,
                    "ITEM_ID" => "UNKNOWN",
                    "DESCRIPTION" => sprintf(Loc::getMessage('FEEDBACK_FAKE_ERROR_MESSAGE') . "\n%s",
                        var_export($_REQUEST, true)),
                ));
            }

            $arFields["ERROR"] = "Y";
        }
        return $ret;
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
            $APPLICATION->ThrowException(Loc::getMessage('FAKE_ERROR_MESSAGE'), "fake-field");
            return false;
        }
        return true;
    }

    public static function psf_getFakeFieldLabel() {
        return Loc::getMessage('FAKE_FIELD_LABEL');
    }
}