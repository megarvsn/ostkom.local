<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class UserConsent {
    const WEB_FORM_IDS = "16,17,18,19,20,21"; // user consent
    const WEB_FORM_IDS_2 = ""; // user consent 2
    const WEB_FORM_IDS_3 = ""; // user consent 3

    const FEEDBACK_FORM_POST_IDS = "";
    const REGISTRATION_ENABLED = "Y";
    const SALE_ORDER_ENABLED = "N";

    const USER_CONSENT_INPUT_NAME = "user_consent";
    const USER_CONSENT_INPUT_NAME_2 = "user_consent_2";
    const USER_CONSENT_INPUT_NAME_3 = "user_consent_3";

    public function __construct() {
    }

    /**
     * Подключаем проверку согласия
     */
    public function initCheckConsent() {
        $EventManager = EventManager::getInstance();
        if (!empty(self::WEB_FORM_IDS)) {
            $EventManager->addEventHandler('form', 'onBeforeResultAdd', ['Baza23\UserConsent', 'checkWebForm']);
        }
        if (self::REGISTRATION_ENABLED == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserRegister', ['Baza23\UserConsent', 'checkRegistration']);
        }
        if (!empty(self::FEEDBACK_FORM_POST_IDS)) {
            $EventManager->addEventHandler('main', 'OnBeforeEventAdd', ['Baza23\UserConsent', 'checkFeedback']);
        }
        if (self::SALE_ORDER_ENABLED == "Y") {
            $EventManager->addEventHandler('sale', 'OnBeforeOrderAdd', ['Baza23\UserConsent', 'checkSaleOrder']);
        }
    }

    /**
     * Проверка форм из модуля веб форм
     */
    public static function checkWebForm($p_WEB_FORM_ID, &$p_arFields, &$p_arValues) {
        if ($p_arFields["ERROR"] == "Y") return false;

        $checkConsent_1 = false;
        $webformIDs = self::WEB_FORM_IDS;
        if (!empty($webformIDs)) {
            $webformIDs = explode(',', $webformIDs);
            $checkConsent_1 = in_array($p_WEB_FORM_ID, $webformIDs, true);
        }

        $checkConsent_2 = false;
        $webformIDs = self::WEB_FORM_IDS_2;
        if (!empty($webformIDs)) {
            $webformIDs = explode(',', $webformIDs);
            $checkConsent_2 = in_array($p_WEB_FORM_ID, $webformIDs, true);
        }

        $checkConsent_3 = false;
        $webformIDs = self::WEB_FORM_IDS_3;
        if (!empty($webformIDs)) {
            $webformIDs = explode(',', $webformIDs);
            $checkConsent_3 = in_array($p_WEB_FORM_ID, $webformIDs, true);
        }

        // если не из списка проверяемых форм пришли данные, то не проверяем
        if (! ($checkConsent_1 || $checkConsent_2 || $checkConsent_3)) return true;

        $result = self::psf_checkSpam($checkConsent_1, $checkConsent_2, $checkConsent_3);
        if (!$result) $p_arFields["ERROR"] = "Y";
        return $result;
    }

    /**
     * Проверка при регистрации пользователя
     */
    public static function checkRegistration(&$arArgs) {
        $registrationEnable = self::REGISTRATION_ENABLED;
        if ($registrationEnable != 'Y') return true;

        return self::psf_checkSpam();
    }

    /**
     * Проверка при оформлении заказа
     */
    public static function checkSaleOrder(&$arFields) {
        $saleOrderEnable = self::SALE_ORDER_ENABLED;
        if ($saleOrderEnable != 'Y') return true;

        return self::psf_checkSpam();
    }

    /**
     * Проверка при отправке формы обратной связи main.feedback
     */
    public static function checkFeedback(&$event, &$lid, &$arFields, &$messageId, &$files, &$languageId) {
        $feedbackIDs = self::FEEDBACK_FORM_POST_IDS;
        if (empty($feedbackIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем
        $feedbackIDs = explode(',', $feedbackIDs);
        if (!in_array((string) $messageId, $feedbackIDs, true)) return true;

        return self::psf_checkSpam();
    }

    /**
     * Основной метод проверки согласия
     */
    public static function psf_checkSpam($p_checkConsent = true, $p_checkConsent_2 = false, $p_checkConsent_3 = false) {
        $isError_1 = false;
        if ($p_checkConsent) $isError_1 = ($_REQUEST[self::USER_CONSENT_INPUT_NAME] != "Y");

        $isError_2 = false;
        if ($p_checkConsent_2) $isError_2 = ($_REQUEST[self::USER_CONSENT_INPUT_NAME_2] != "Y");

        $isError_3 = false;
        if ($p_checkConsent_3) $isError_3 = ($_REQUEST[self::USER_CONSENT_INPUT_NAME_3] != "Y");

        global $APPLICATION;
        if ($isError_1) {
            $errorText = \Baza23\WebForms::psf_error_text(
                    self::USER_CONSENT_INPUT_NAME, "user-consent");
            $APPLICATION->ThrowException($errorText);

            //$errorMessage1 = Loc::getMessage('USER_CONSENT_ERROR_MESSAGE');
            //$APPLICATION->ThrowException($errorMessage1, "user-consent");
            return false;

        } elseif ($isError_2) {
            $errorText = \Baza23\WebForms::psf_error_text(
                    self::USER_CONSENT_INPUT_NAME_2, "user-consent");
            $APPLICATION->ThrowException($errorText);

            //$errorMessage2 = Loc::getMessage('USER_CONSENT_ERROR_MESSAGE_2');
            //$APPLICATION->ThrowException($errorMessage2, "user-consent-2");
            return false;

        } elseif ($isError_3) {
            $errorText = \Baza23\WebForms::psf_error_text(
                    self::USER_CONSENT_INPUT_NAME_3, "user-consent");
            $APPLICATION->ThrowException($errorText);

            $errorMessage3 = Loc::getMessage('USER_CONSENT_ERROR_MESSAGE_3');
            $APPLICATION->ThrowException($errorMessage3, "user-consent-3");
            return false;
        }
        return true;
    }
}