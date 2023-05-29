<?

namespace Baza23;

use Bitrix\Main\Page\Asset,
    Bitrix\Main\EventManager,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Recaptcha {
    const RECAPTCHA_SITE_KEY = '6LeooFoaAAAAABj-Hs76LNhv6l2yLGuP2Ro1bGQi';
    const RECAPTCHA_SECRET_KEY = '6LeooFoaAAAAAGRWovMArx3JnkLdOFvxxVWcAn0a';
    const PERMISSIBLE_SCORE = 0.25;

    const HIDE_BADGE = 'Y';

    const WEB_FORM_IDS = "";//"2,3,4,5,6,7,8,9,10,12,14,15,16,17,18,19,21,22";
    const FEEDBACK_FORM_POST_IDS = "";
    const IBLOCK_IDS = "";
    const REGISTRATION_ENABLED = "N";
    const FORGOT_PASS_ENABLED = "N";
    const SALE_ORDER_ENABLED = "N";

    public function __construct() {
    }

    /**
     * Подключаем JS скрипты для reCaptcha v3
     */
    public function initJS() {
        $Asset = Asset::getInstance();
        $siteKey = self::RECAPTCHA_SITE_KEY;
        $hideBadge = self::HIDE_BADGE;

        if (empty($siteKey)) return true;

        $Asset->addString('<script src="https://www.google.com/recaptcha/api.js?render=' . $siteKey . '"></script>');
        $Asset->addString('<script>jso_plugins.recaptcha.i_siteKey = "' . $siteKey . '";jso_plugins.recaptcha.jsf_init();</script>');

        if ($hideBadge == 'Y') $Asset->addString('<style>.grecaptcha-badge {display: none;}</style>');
    }

    /**
     * Подключаем проверку на спам
     */
    public function initCheckSpam() {
        $secretKey = self::RECAPTCHA_SECRET_KEY;
        if (empty($secretKey)) return true;

        $EventManager = EventManager::getInstance();
        if (!empty(self::WEB_FORM_IDS)) {
            $EventManager->addEventHandler('form', 'onBeforeResultAdd', ['Baza23\Recaptcha', 'checkWebForm']);
        }
        if (self::REGISTRATION_ENABLED == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserRegister', ['Baza23\Recaptcha', 'checkRegistration']);
        }
        if (!empty(self::FEEDBACK_FORM_POST_IDS)) {
            $EventManager->addEventHandler('main', 'OnBeforeEventAdd', ['Baza23\Recaptcha', 'checkFeedback']);
        }
        if (self::FORGOT_PASS_ENABLED == "Y") {
            $EventManager->addEventHandler('main', 'OnBeforeUserSendPassword', ["Baza23\Recaptcha", "checkForgotPass"]);
        }
        if (!empty(self::IBLOCK_IDS)) {
            $EventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', ['Baza23\Recaptcha', 'checkIBlock']);
        }
        if (self::SALE_ORDER_ENABLED == "Y") {
            $EventManager->addEventHandler('sale', 'OnBeforeOrderAdd', ['Baza23\Recaptcha', 'checkSaleOrder']);
        }
    }

    /**
     * Проверка форм из модуля веб форм
     */
    public static function checkWebForm($p_WEB_FORM_ID, &$p_arFields, &$p_arValues) {
        if ($p_arFields["ERROR"] == "Y") return false;
        if ($p_arFields['RECAPTCHA_DISABLED']) return true;

        $webformIDs = self::WEB_FORM_IDS;
        if (empty($webformIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем капчу
        $webformIDs = explode(',', $webformIDs);
        if (!in_array($p_WEB_FORM_ID, $webformIDs, true)) return true;

        $result = self::psf_checkSpam();
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
     * Проверка при восстановлении пароля пользователя
     */
    public static function checkForgotPass(&$arArgs) {
        $forgotPassEnable = self::FORGOT_PASS_ENABLED;
        if ($forgotPassEnable != 'Y') return true;

        return self::psf_checkSpam();
    }

    /**
     * Проверка при оформлении заказа
     */
    public static function checkSaleOrder(&$arFields) {
        if ($arFields['RECAPTCHA_DISABLED']) return true;

        $saleOrderEnable = self::SALE_ORDER_ENABLED;
        if ($saleOrderEnable != 'Y') return true;

        return self::psf_checkSpam();
    }

    /**
     * Проверка при отправки формы обратной связи main.feedback
     */
    public static function checkFeedback(&$event, &$lid, &$arFields, &$messageId, &$files, &$languageId) {
        if ($arFields['RECAPTCHA_DISABLED']) return true;

        $feedbackIDs = self::FEEDBACK_FORM_POST_IDS;
        if (empty($feedbackIDs)) return true;

        // если не из списка проверяемых форм пришли данные, то не проверяем капчу
        $feedbackIDs = explode(',', $feedbackIDs);
        if (!in_array((string) $messageId, $feedbackIDs, true)) return true;

        return self::psf_checkSpam();
    }

    /**
     * Проверка при добавлении в инфоблок
     */
    public static function checkIBlock(&$arParams) {
        if ($arParams['RECAPTCHA_DISABLED']) return true;

        $iblockIDs = self::IBLOCK_IDS;
        if (empty($iblockIDs)) return true;

        // если не из списка проверяемых инфоблоков пришли данные, то не проверяем капчу
        $iblockIDs = explode(',', $iblockIDs);
        if (!in_array((string) $arParams['IBLOCK_ID'], $iblockIDs, true)) return true;

        return self::psf_checkSpam();
    }

    /**
     * Основной метод проверки капчи
     */
    public static function psf_checkSpam() {
        global $APPLICATION;

        // если мы добавляем данные из админки, то не проверяем
        if (preg_match('/^\/bitrix\/admin\/.*$/i', $APPLICATION->GetCurPage())) return true;

        $isError = false;
        $RECAPTCHA_token = $_REQUEST['recaptcha_token'];
        if (isset($RECAPTCHA_token) && !empty($RECAPTCHA_token)) {
            $secretKey = self::RECAPTCHA_SECRET_KEY;
            $permissibleScore = (float) self::PERMISSIBLE_SCORE;

            $recaptcha = new RecaptchaVerifier($secretKey, $permissibleScore);
            $response = $recaptcha->pf_verify($RECAPTCHA_token);

            if (!$response['isSuccess']) $isError = true;
        } else {
            $isError = true;
        }

        if ($isError) {
            $errorMessage = Loc::getMessage('RECAPTCHA_ERROR_MESSAGE');
            $APPLICATION->ThrowException($errorMessage, "recaptcha");
            return false;
        }
        return true;
    }
}