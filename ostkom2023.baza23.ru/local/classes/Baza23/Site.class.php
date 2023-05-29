<?

namespace Baza23;

use \Bitrix\Main\Application;

class Site {
    const MODAL_FORM_SUCCESS_ID = 'modal--success-form';
    const MODAL_FORM_ERROR_ID = 'modal--error-form';

	public function __construct() {
	}

    const C_BASE_SITE = 's1';
    const C_ALL_SITES = array('s1');
    const C_IBLOCK_IDS = array(
        "s1" => array(
            'catalog'             => 2,
            'catalog-kits'        => 14,
            'special-offer'       => 17,
            'special-offer-items' => 18,
            'manufacturers'       => 8,
            'equipment'           => 21,
            'pool-shapes'         => 32,
            'colors'              => 34,
            'quick-links'         => 31,
            'filter-types'        => 35,

            'product-stickers'      => 49,
            'product-properties'    => 50,
            'product-sorting-types' => 61,

            'content-texts'        => 45,
            'content-images'       => 46,
            'content-video'        => 52,
            'content-catalogs'     => 55,
            'content-instructions' => 56,
            'content-infographics' => 57,
            'content-garantee'     => 58,
            'content-montage'      => 59,

            'home-slider'          => 51,
            'home-icon-texts'      => 62,
            'home-grid'            => 63,
            'home-tile'            => 64,
            'billboard-top'        => 60,
            'billboard-left-side'  => 53,

            'settings'            => 39,
            'contacts'            => 44,
            'seo'                 => 40,
            'menu'                => 41,
            'forms'               => 42,
            'icons'               => 47,
            'urls'                => 48,

            'redirect'            => 43,
        ),
    );

    const C_PAGE_CACHE_TIME = array(
        'default'         => 31536000,
        'seo'             => 31536000,
        'settings'        => 31536000,
        'forms'           => 31536000,
        'menu'            => 31536000,

        'catalog'         => 7200,
    );

    const C_IBLOCK_CODE_PREFIX = array(
        "s1" => "",
    );

    const C_IBLOCK_TYPE_PREFIX = array(
        "s1" => "",
    );

    const C_IBLOCK_HOME_FOLDER = array(
        "s1" => "/",
    );

    /* $p_arParams keys: SITE_ID, SITE_ID, BASE_LANG_IBLOCK_TYPE */
    public static function psf_getIBlockId($p_baseLangIBlockCode, $p_arParams = false) {
        if (!$p_baseLangIBlockCode) return false;

        $ret = false;
        if ($p_arParams['SITE_ID']) $ret = self::C_IBLOCK_IDS[$p_arParams['SITE_ID']][$p_baseLangIBlockCode];
        if (!$ret) $ret = self::C_IBLOCK_IDS[SITE_ID][$p_baseLangIBlockCode];
        if (!$ret) $ret = self::C_IBLOCK_IDS[self::C_BASE_SITE][$p_baseLangIBlockCode];
        return $ret;
    }

    /* $p_arParams keys: SITE_ID, SITE_ID, BASE_LANG_IBLOCK_TYPE */
    public static function psf_getIBlockCode($p_baseLangIBlockCode, $p_arParams = false) {
        $ret = false;
        if ($p_arParams['SITE_ID']) $ret = self::C_IBLOCK_CODE_PREFIX[$p_arParams['SITE_ID']] . $p_baseLangIBlockCode;
        if (!$ret) $ret = self::C_IBLOCK_CODE_PREFIX[SITE_ID] . $p_baseLangIBlockCode;
        if (!$ret) $ret = $p_baseLangIBlockCode;
        return $ret;
    }

    /* $p_arParams keys: SITE_ID, SITE_ID */
    public static function psf_getIBlockType($p_baseLangIBlockType, $p_arParams = false) {
        $ret = false;
        if ($p_arParams['SITE_ID']) $ret = self::C_IBLOCK_TYPE_PREFIX[$p_arParams['SITE_ID']] . $p_baseLangIBlockType;
        if (!$ret) $ret = self::C_IBLOCK_TYPE_PREFIX[SITE_ID] . $p_baseLangIBlockType;
        if (!$ret) $ret = $p_baseLangIBlockType;
        return $ret;
    }

    /* $p_arParams keys: SITE_ID, SITE_ID, BASE_LANG_IBLOCK_TYPE */
    public static function psf_getCacheTime($p_pageCode, $p_arParams = false) {
        if (!$p_pageCode) return self::C_PAGE_CACHE_TIME["default"];

        $ret = self::C_PAGE_CACHE_TIME[$p_pageCode];
        if (!$ret) $ret = self::C_PAGE_CACHE_TIME["default"];
        return $ret;
    }

    public static function psf_checkSite($p_siteId) {
        if (!$p_siteId) return false;

        return in_array($p_siteId, self::C_ALL_SITES);
    }

    /* $p_arParams keys: LANGUAGE_ID, SITE_ID */
    public static function psf_getSiteFolder($p_curDir, $p_arParams = false) {
        $curSiteFolder = self::C_IBLOCK_HOME_FOLDER[SITE_ID];
        if (!empty($curSiteFolder) && strpos($p_curDir, $curSiteFolder) === 0) {
            $curDir = substr($p_curDir, strlen($curSiteFolder));
        } else {
            $curDir = $p_curDir;
        }

        $siteId = $p_arParams["SITE_ID"];
        if (!$siteId) {
            $languageId = $p_arParams["LANGUAGE_ID"];
            $siteId = self::psf_getSiteIdForLanguage($languageId);
        }

        if (!isset(self::C_IBLOCK_HOME_FOLDER[$siteId])) return $p_curDir;

        return self::C_IBLOCK_HOME_FOLDER[$siteId];
        /*
        $arPass = \Baza23\Utils::psf_parseDir($curDir);
        if (empty($arPass)) return self::C_IBLOCK_HOME_FOLDER[$siteId];

        $ret = '';

        $startIndex = 0;
        if ($arPass[$startIndex] == 'about'
                || $arPass[$startIndex] == 'news'
                || $arPass[$startIndex] == 'insights'
                || $arPass[$startIndex] == 'search') {
            $ret .= $arPass[$startIndex] . '/';
            if (isset($arPass[$startIndex + 1])) $ret .= $arPass[$startIndex + 1] . '/';

        } elseif (self::psf_getIBlockId($arPass[$startIndex])) {
            $ret .= $arPass[$startIndex] . '/';
        }

        if (self::C_IBLOCK_HOME_FOLDER[$siteId]) {
            $ret = self::C_IBLOCK_HOME_FOLDER[$siteId] . $ret;
        }
        return $ret;
        */
    }

    public static function psf_getAjaxPathForSite($p_siteId = SITE_ID) {
        $ret = SITE_TEMPLATE_PATH . '/include/';
        return $ret;
    }

    const C_COOKIE_TIME = 60 * 60 * 24 * 60;

    public static function psf_saveCookie($p_cookieName, $p_value) {
        $value = ($p_value ? serialize($p_value) : '');
        $application = Application::getInstance();
        $context = $application->getContext();

        $cookie = new \Bitrix\Main\Web\Cookie($p_cookieName, $value, time() + self::C_COOKIE_TIME);
        $cookie->setDomain($context->getServer()->getServerName());
        $cookie->setHttpOnly(false);

        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");
    }

    public static function psf_loadCookie($p_cookieName) {
        $application = Application::getInstance();
        $context = $application->getContext();

        $ret = $context->getRequest()->getCookie($p_cookieName);
        return unserialize($ret);
    }

    protected static $s_isMobile = false;

    public static function psf_isMobile() {
        if (!self::$s_isMobile) {
            //require_once $_SERVER["DOCUMENT_ROOT"] . '/local/classes/Mobile_Detect.php';
            $detect = new \Mobile_Detect;
            self::$s_isMobile = ($detect->isMobile() ? "Y" : "N");
        }
        return (self::$s_isMobile == "Y");
    }

    public static function psf_getSiteUrl() {
        global $APPLICATION;
        return ($APPLICATION->IsHTTPS() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];
    }

    public static function psf_getFullUrl($p_url) {
        if (!$p_url) return "";

        $ret = (strpos($p_url, 'http') === 0 ? $p_url : self::psf_getSiteUrl() . $p_url);
        return $ret;
    }

    public static function psf_getCurrentUrl($p_includeParams = false) {
        global $APPLICATION;
        if ($p_includeParams) {
            $ret = self::psf_getSiteUrl() . $APPLICATION->GetCurUri();
        } else {
            $ret = self::psf_getSiteUrl() . $APPLICATION->GetCurDir();
        }
        return $ret;
    }

    public static function psf_getCurrentUrlUtm() {
        $arUTM = [];
        if ($_REQUEST["utm_source"]) $arUTM["utm_source"] = $_REQUEST["utm_source"];
        if ($_REQUEST["utm_medium"]) $arUTM["utm_medium"] = $_REQUEST["utm_medium"];
        if ($_REQUEST["utm_campaign"]) $arUTM["utm_campaign"] = $_REQUEST["utm_campaign"];
        if ($_REQUEST["utm_content"]) $arUTM["utm_content"] = $_REQUEST["utm_content"];
        if ($_REQUEST["utm_term"]) $arUTM["utm_term"] = $_REQUEST["utm_term"];

        if ($_REQUEST["gclid"]) $arUTM["gclid"] = $_REQUEST["gclid"];
        if ($_REQUEST["yclid"]) $arUTM["yclid"] = $_REQUEST["yclid"];
        if ($_REQUEST["fbclid"]) $arUTM["fbclid"] = $_REQUEST["fbclid"];
        return $arUTM;
    }

    const C_COOKIE_UTM = "UTM";

    public static function psf_saveUtm($p_utm) {
        self::psf_saveCookie(self::C_COOKIE_UTM, $p_utm);
    }

    public static function psf_loadUtm() {
        return self::psf_loadCookie(self::C_COOKIE_UTM);
    }

    public static $s_arEUCountries = array(
            "EU", "AT", "BE", "BG", "HU", "GR", "DE", "DK", "IT",
            "IE", "ES", "CY", "LU", "LV", "LT", "MT", "NL", "PT", "PL",
            "RO", "SI", "SK", "FR", "FI", "HR", "CZ", "SE", "EE",
    );

    public static function psf_ip_isEU($p_countryCode) {
        if (!$p_countryCode) return false;

        $ret = in_array(strtoupper(trim($p_countryCode)), self::$s_arEUCountries);
        return $ret;
    }

    public static function psf_ip_getUserCountryCode() {
        // получим ip клиента
        $ip = self::psf_ip_getIp();
        if ($ip) {
            $arCity = self::psf_ip_getCity($ip);
            if ($arCity['country']['iso']) $ret = strtolower($arCity['country']['iso']);
        }
        return $ret;
    }

    public static function psf_ip_getUserRegionCode() {
        // получим ip клиента
        $ip = self::psf_ip_getIp();
        if ($ip) {
            $arCity = self::psf_ip_getCity($ip);
            if ($arCity['region']['iso']) $ret = strtolower($arCity['region']['iso']);
        }
        return $ret;
    }

    public static function psf_ip_getCity($p_ip) {
        if (isset($_SESSION['U_IP_CITY'][$p_ip])) {
            $arCity = $_SESSION['U_IP_CITY'][$p_ip];

        } else {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/local/ipGeo/SxGeo.php';

            /**
             *  создадим объект SxGeo
             *
             * 1 аргумент – имя файла базы данных,
             * 2 аргумент – режим работы:
             *       SXGEO_FILE (по умолчанию),
             *       SXGEO_BATCH  (пакетная обработка, увеличивает скорость при обработке множества IP за раз), SXGEO_MEMORY (кэширование БД в памяти, еще увеличивает скорость пакетной обработки, но требует больше памяти, для загрузки всей базы в память).
             */
            $SxGeo = new \SxGeo($_SERVER['DOCUMENT_ROOT'] . '/local/ipGeo/SxGeoCity.dat', SXGEO_BATCH);
            // получаем двухзначный ISO-код страны (RU, UA и др.)
            $arCity = $SxGeo->getCityFull($p_ip);
            $arCity['ip'] = $p_ip;

            $_SESSION['U_IP_CITY'][$p_ip] = $arCity;

            unset($SxGeo);
        }
        return $arCity;
    }

    public static function psf_ip_getIp() {
        $ret = $_SERVER['REMOTE_ADDR'];
        return $ret;
    }

    /**
     * Change system JQuery.
     */
    public function pf_initRegisterJQuery() {
        $EventManager = \Bitrix\Main\EventManager::getInstance();
        $EventManager->addEventHandler("main", "OnBeforeProlog", ["\Baza23\Site", "psf_registerJQuery"]);
    }

    public static function psf_registerJQuery() {
        //Hack: when init first extension - bitrix register standart extensions
        $emptyHack = [
            'css'       => "",
            'skip_core' => true,
        ];
        \CJSCore::RegisterExt('emptyHack', $emptyHack);
        \CJSCore::Init('emptyHack');

        $jquery = [
            'js'        => "/local/vendors/jquery/jquery-3.6.1.min.js",
            'skip_core' => true,
        ];
        \CJSCore::RegisterExt('jquery', $jquery);
    }

    public function pf_registerVendors() {
        $arJsConfig = array(
            'user-utilities' => array(
                'js'        => '/local/js/utilities.min.js',
                'css'       => [],
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-tab-pane' => array(
                'js'        => '/local/js/tab-pane.min.js',
                'css'       => [],
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-compare' => array(
                'js'        => '/local/components/baza23/block.catalog.compare.item/ext/catalog-compare.min.js',
                'css'       => '/local/components/baza23/block.catalog.compare.item/ext/catalog-compare.min.css',
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-favourites' => array(
                'js'        => '/local/components/baza23/block.catalog.favourites.item/ext/catalog-favourites.min.js',
                'css'       => '/local/components/baza23/block.catalog.favourites.item/ext/catalog-favourites.min.css',
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-stickers' => array(
                'js'        => [],
                'css'       => [
                    '/local/components/baza23/block.catalog.stickers/ext/catalog-stickers-image.min.css',
                    '/local/components/baza23/block.catalog.stickers/ext/catalog-stickers-detail.min.css',
                ],
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-quantity' => array(
                'js'        => [],
                'css'       => '/local/components/baza23/block.catalog.quantity.item/ext/catalog-quantity.min.css',
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-product-status' => array(
                'js'        => '/local/components/baza23/block.catalog.product.status/ext/catalog-product-status.min.js',
                'css'       => '/local/components/baza23/block.catalog.product.status/ext/catalog-product-status.min.css',
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'user-catalog-equipment' => array(
                'js'        => [],
                'css'       => '/local/components/baza23/local.catalog.equipment/ext/catalog-equipment.min.css',
                'rel'       => array("jquery"),
                'skip_core' => true
            ),

            'bootstrap' => array(
                'js'        => '/local/vendors/bootstrap5/bootstrap.bundle.min.js',
                'css'       => '/local/vendors/bootstrap5/bootstrap.min.css',
                'rel'       => [],
                'skip_core' => true
            ),
            'owl-carousel' => array(
                'js'        => [
                    '/local/vendors/owl-carousel/owl.carousel.min.js',
                    '/local/vendors/owl-carousel/template_script.min.js',
                ],
                'css'       => [
                    '/local/vendors/owl-carousel/owl.carousel.min.css',
                    '/local/vendors/owl-carousel/template_styles.min.css',
                ],
                'rel'       => [],
                'skip_core' => true
            ),
            'perfect-scrollbar' => array(
                'js'        => [
                    '/local/vendors/perfect-scrollbar/perfect-scrollbar.min.js',
                    '/local/vendors/perfect-scrollbar/template_script.min.js',
                ],
                'css'       => [
                    '/local/vendors/perfect-scrollbar/perfect-scrollbar.min.css',
                    '/local/vendors/perfect-scrollbar/template_styles.min.css',
                ],
                'rel'       => [],
                'skip_core' => true
            ),
            'jquery-iframetracker' => array(
                'js'        => [
                    '/local/vendors/jquery-iframetracker/jquery.iframetracker.min.js',
                ],
                'css'       => [],
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
            'jquery-print' => array(
                'js'        => [
                    //'/local/vendors/jquery-print/jQuery.print.min.js',
                    '/local/vendors/jquery-print/template_script.min.js',
                ],
                'css'       => [],
                'rel'       => array("jquery"),
                'skip_core' => true
            ),
        );

        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
    }
}