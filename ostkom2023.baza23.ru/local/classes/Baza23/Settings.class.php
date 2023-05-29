<?

namespace Baza23;

class Settings {
    const IBLOCK_CODE_SETTINGS = 'settings';

    protected static $s_arIBlockAttrs = array();

	public function __construct() {
	}

    /* Custom parameters ********************************************/

    protected static $s_customParams = array();

    public static function psf_getCustomParam($p_paramName) {
        return self::$s_customParams[$p_paramName];
    }

    public static function psf_setCustomParam($p_paramName, $p_paramValue) {
        self::$s_customParams[$p_paramName] = $p_paramValue;
    }

    /* Seo attrs ********************************************/

    const IBLOCK_CODE_SEO = 'seo';

    public static function psf_seo_getProperty($p_elementCode, $p_code) {
        $arSeo = self::psf_seo_all($p_elementCode);
        return self::psf_attr_get($arSeo, $p_code);
    }

    public static function psf_seo_all($p_elementCode) {
        if (!$p_elementCode) return false;

        if (isset(self::$s_arIBlockAttrs[self::IBLOCK_CODE_SEO][$p_elementCode])) {
            $arRet = self::$s_arIBlockAttrs[self::IBLOCK_CODE_SEO][$p_elementCode];

        } elseif (empty(self::$s_arIBlockAttrs[self::IBLOCK_CODE_SEO])) {
            $arFilter = array(
                "IBLOCK_ID" => \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_SEO),
                "INCLUDE_SUBSECTIONS" => "N",
                "ELEMENT_PROPERTY_CODES" => [
                    "UP_META_TITLE", "UP_META_DESCRIPTION", "UP_META_KEYWORDS",
                    "UP_H1", "UP_BROWSER_TITLE", "UP_BREADCRUMB",
                ],

                "CACHE_TYPE" => "Y",
                "CACHE_TIME" => \Baza23\Site::psf_getCacheTime("seo"),
            );

            global $APPLICATION;
            $arAll = $APPLICATION->IncludeComponent(
                "baza23:utils.page.attrs",
                "",
                $arFilter
            );

            self::$s_arIBlockAttrs[self::IBLOCK_CODE_SEO] = $arAll;
            $arRet = $arAll[$p_elementCode];
        }
        return $arRet;
    }

    /* Forms ********************************************/

    const IBLOCK_CODE_FORMS = 'forms';

    public static function psf_form_getText($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_form_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_form_getImage($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_form_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_form_getImageSrc($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_form_getImage($p_sectionCode, $p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_form_get($p_sectionCode, $p_treeAttrs) {
        $arAttrs = self::psf_form_all($p_sectionCode);
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_form_all($p_sectionCode, $p_arParams = false) {
        if (!$p_sectionCode) return false;

        if (isset(self::$s_arIBlockAttrs[self::IBLOCK_CODE_FORMS][$p_sectionCode])) {
            $arRet = self::$s_arIBlockAttrs[self::IBLOCK_CODE_FORMS][$p_sectionCode];

        } elseif (empty(self::$s_arIBlockAttrs[self::IBLOCK_CODE_FORMS])) {
            global $APPLICATION;
            $arRet = $APPLICATION->IncludeComponent(
                "baza23:utils.form.attrs",
                "",
                array(
                    "IBLOCK_ID" => \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_FORMS, $p_arParams),
                    "SECTION_CODE" => $p_sectionCode,
                    "DEFAULT_SECTION_CODE" => "defaults",
                    "INCLUDE_SUBSECTIONS" => "Y",

                    "CACHE_TYPE" => "Y",
                    "CACHE_TIME" => \Baza23\Site::psf_getCacheTime("forms"),
                )
            );
        }
        return $arRet;
    }

    /* Contacts ********************************************/

    const IBLOCK_CODE_CONTACTS = 'contacts';
    const SECTION_CONTACTS = 'contacts';
    const SECTION_WORKTIME = 'contacts';

    public static function psf_contacts_getAddresses() {
        $arRet = [];
        $index = 1;
        $code = 'address';

        while ($address = self::psf_contacts_getText(self::SECTION_CONTACTS, $code)) {
            $arRet[] = $address;

            $index ++;
            $code = 'address-' . $index;
        }
        return $arRet;
    }

    public static function psf_contacts_getWay() {
        $ret = self::psf_contacts_getText(self::SECTION_CONTACTS, "way");
        return $ret;
    }

    public static function psf_contacts_getPhones() {
        $arRet = [];
        $index = 1;
        $code = 'phone';

        while ($phone = self::psf_contacts_getText(self::SECTION_CONTACTS, $code)) {
            $phoneShort = \Baza23\Utils::psf_clearPhone($phone, true);
            if ($phoneShort) {
                $arRet[$phoneShort] = [
                    "href" => 'tel:' . $phoneShort,
                    "text" => $phone
                ];
            }

            $index ++;
            $code = 'phone-' . $index;
        }
        return $arRet;
    }

    public static function psf_contacts_getEmails() {
        $arRet = [];
        $index = 1;
        $code = 'email';

        while ($email = self::psf_contacts_getText(self::SECTION_CONTACTS, $code)) {
            $arRet[$email] = [
                "href" => 'mailto:' . $email,
                "text" => $email
            ];

            $index ++;
            $code = 'email-' . $index;
        }
        return $arRet;
    }

    public static function psf_contacts_getWorktimes() {
        $arRet = [];
        $index = 1;
        $code = 'time';

        while ($time = self::psf_contacts_getText(self::SECTION_WORKTIME,"worktime")) {
            $arRet[] = $time;

            $index ++;
            $code = 'time-' . $index;
        }
        return $arRet;
    }

    public static function psf_contacts_getMessengers() {
        $arRet = [];

        $whatsapp = self::psf_contacts_getText(self::SECTION_CONTACTS, "whatsapp");
        if ($whatsapp) {
            $whatsappShort = \Baza23\Utils::psf_clearPhone($whatsapp, true);
            if ($whatsappShort) {
                $arRet["whatsapp"] = [
                    "href" => "https://wa.me/" . $whatsappShort,
                    "text" => $whatsapp,
                    "icon" => self::psf_icon_getText("icon-whatsapp")
                ];
            }
        }

        $skype = self::psf_contacts_getText(self::SECTION_CONTACTS, "skype");
        if ($skype) {
            $arRet["skype"] = [
                "href" => $skype,
                "text" => $skype,
                "icon" => self::psf_icon_getText("icon-skype")
            ];
        }

        return $arRet;
    }

    public static function psf_contacts_getText($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_contacts_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_contacts_getImage($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_contacts_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_contacts_getImageSrc($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_contacts_getImage($p_sectionCode, $p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_contacts_get($p_sectionCode, $p_treeAttrs) {
        $arAttrs = self::psf_contacts_all($p_sectionCode);
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_contacts_all($p_sectionCode) {
        if (!$p_sectionCode) return false;
        return self::psf_iblock_all(self::IBLOCK_CODE_CONTACTS, $p_sectionCode);
    }

    /* Icons ********************************************/

    const IBLOCK_CODE_ICONS = 'icons';
    const SECTION_ICONS = false;

    public static function psf_icon_getText($p_code) {
        $arAttr = self::psf_icon_get($p_code);
        return str_replace(["\n","\r"], '', $arAttr["PREVIEW_TEXT"]);
    }

    public static function psf_icon_getImage($p_code) {
        $arAttr = self::psf_icon_get($p_code);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_icon_getImageSrc($p_code) {
        $arAttr = self::psf_icon_getImage($p_code);
        return $arAttr["SRC"];
    }

    public static function psf_icon_get($p_code) {
        return self::psf_iblock_get(self::IBLOCK_CODE_ICONS, self::SECTION_ICONS, $p_code);
    }

    public static function psf_icon_all() {
        return self::psf_iblock_all(self::IBLOCK_CODE_ICONS, self::SECTION_ICONS);
    }

    /* Urls ********************************************/

    const IBLOCK_CODE_URLS = 'urls';
    const SECTION_URLS = false;

    public static function psf_getUrl($p_code) {
        $ret = self::psf_iblock_getText(self::IBLOCK_CODE_URLS, self::SECTION_URLS, $p_code);
        if (!$ret) return "";

        if (strpos($ret, "http") === 0 || strpos($ret, SITE_DIR) === 0) return $ret;
        if (strpos($ret, '/') === 0) $ret = substr($ret, 1);
        return SITE_DIR . $ret;
    }

    public static function psf_getUrls() {
        return self::psf_iblock_all(self::IBLOCK_CODE_URLS, self::SECTION_URLS);
    }

    /* Current page attrs ********************************************/

    public static $s_currentPageName = false;

    public static function psf_page_getText($p_subattrNames) {
        $arAttr = self::psf_page_get($p_subattrNames);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_page_getImage($p_subattrNames) {
        $arAttr = self::psf_page_get($p_subattrNames);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_page_getImageSrc($p_subattrNames) {
        $arAttr = self::psf_page_getImage($p_subattrNames);
        return $arAttr["SRC"];
    }

    public static function psf_page_get($p_subattrNames) {
        return self::psf_settings_get("page--" . self::$s_currentPageName, $p_subattrNames);
    }

    public static function psf_page_all() {
        return self::psf_settings_all("page--" . self::$s_currentPageName);
    }

    /* Default attrs ********************************************/

    const SECTION_DEFAULT = 'default-settings';

    public static function psf_default_getText($p_treeAttrs) {
        $arAttr = self::psf_default_get($p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_default_get($p_treeAttrs) {
        $arAttrs = self::psf_default_all();
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_default_all() {
        return self::psf_settings_all(self::SECTION_DEFAULT);
    }

    /* Header attrs ********************************************/

    const SECTION_HEADER = 'section--header';

    public static function psf_header_getText($p_treeAttrs) {
        $arAttr = self::psf_header_get($p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_header_getImage($p_treeAttrs) {
        $arAttr = self::psf_header_get($p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_header_getImageSrc($p_treeAttrs) {
        $arAttr = self::psf_header_getImage($p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_header_get($p_treeAttrs) {
        $arAttrs = self::psf_header_all();
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_header_all() {
        return self::psf_settings_all(self::SECTION_HEADER);
    }

    /* Footer attrs ********************************************/

    const SECTION_FOOTER = 'section--footer';

    public static function psf_footer_getText($p_treeAttrs) {
        $arAttr = self::psf_footer_get($p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_footer_getImage($p_treeAttrs) {
        $arAttr = self::psf_footer_get($p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_footer_getImageSrc($p_treeAttrs) {
        $arAttr = self::psf_footer_getImage($p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_footer_get($p_treeAttrs) {
        $arAttrs = self::psf_footer_all();
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_footer_all() {
        return self::psf_settings_all(self::SECTION_FOOTER);
    }

    /* Other section attrs ********************************************/

    public static function psf_settings_getText($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_settings_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_settings_getImage($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_settings_get($p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_settings_getImageSrc($p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_settings_getImage($p_sectionCode, $p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_settings_get($p_sectionCode, $p_treeAttrs) {
        $arAttrs = self::psf_settings_all($p_sectionCode);
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_settings_all($p_sectionCode) {
        if (!$p_sectionCode) return false;
        return self::psf_iblock_all(self::IBLOCK_CODE_SETTINGS, $p_sectionCode);
    }

    /* Any iblock section attrs ********************************************/

    public static function psf_iblock_getText($p_iblockCode, $p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_iblock_get($p_iblockCode, $p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_TEXT"];
    }

    public static function psf_iblock_getImage($p_iblockCode, $p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_iblock_get($p_iblockCode, $p_sectionCode, $p_treeAttrs);
        return $arAttr["PREVIEW_PICTURE"];
    }

    public static function psf_iblock_getImageSrc($p_iblockCode, $p_sectionCode, $p_treeAttrs) {
        $arAttr = self::psf_iblock_getImage($p_iblockCode, $p_sectionCode, $p_treeAttrs);
        return $arAttr["SRC"];
    }

    public static function psf_iblock_get($p_iblockCode, $p_sectionCode, $p_treeAttrs) {
        $arAttrs = self::psf_iblock_all($p_iblockCode, $p_sectionCode);
        return self::psf_attr_get($arAttrs, $p_treeAttrs);
    }

    public static function psf_iblock_all($p_iblockCode, $p_sectionCode) {
        if (!$p_iblockCode) return false;

        global $APPLICATION;

        if (!$p_sectionCode) $p_sectionCode = "";

        if (isset(self::$s_arIBlockAttrs[$p_iblockCode][$p_sectionCode])) {
            $arRet = self::$s_arIBlockAttrs[$p_iblockCode][$p_sectionCode];
        } else {
            $arFilter = array(
                "IBLOCK_ID" => \Baza23\Site::psf_getIBlockId($p_iblockCode),
                "INCLUDE_SUBSECTIONS" => "Y",

                "CACHE_TYPE" => "Y",
                "CACHE_TIME" => \Baza23\Site::psf_getCacheTime("settings"),
            );
            if ($p_sectionCode) $arFilter["SECTION_CODE"] = $p_sectionCode;

            $arRet = $APPLICATION->IncludeComponent(
                "baza23:utils.page.attrs",
                "",
                $arFilter
            );

            self::$s_arIBlockAttrs[$p_iblockCode][$p_sectionCode] = $arRet;
        }
        return $arRet;
    }

    /* Utilities ********************************************/

    protected static function psf_attr_get($p_arAttrs, $p_treeAttrs) {
        if (empty($p_arAttrs) || empty($p_treeAttrs)) return false;

        $ret = false;
        if (is_array($p_treeAttrs)) {
            $arAttrs = $p_arAttrs;
            foreach ($p_treeAttrs as $name) {
                if (empty($arAttrs) || empty($name)) {
                    $arAttrs = false;
                    break;
                }
                $arAttrs = $arAttrs[$name];
            }

            $ret = $arAttrs;
        } else {
            $ret = $p_arAttrs[$p_treeAttrs];
        }
        return $ret;
    }

    /* User info ********************************************/

    public static function psf_getUserInfo() {
        $arRet = false;
        global $USER;
        if ($USER->IsAuthorized() && $USER->GetID() > 0) {
            $nameTemplateSite = \CSite::GetNameFormat(false);
            $dbUsers = \Bitrix\Main\UserTable::getList(array(
                    'filter' => array('ID' => $USER->GetID()),
                    'select' => array('NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHONE'),
            ));
            if ($arUser = $dbUsers->fetch()) {
                $arRet = array(
                    'ID'          => $USER->GetID(),
                    'NAME'        => $arUser['NAME'],
                    'LAST_NAME'   => $arUser['LAST_NAME'],
                    'EMAIL'       => $arUser['EMAIL'],
                    'PHONE'       => $arUser['PERSONAL_PHONE'],
                );

                $arRet['FORMATTED_NAME'] = \CUser::FormatName($nameTemplateSite, $arUser, true, false);

                if ($arRet['NAME']) {
                    $arRet['FULL_NAME'] = $arRet['NAME'];

                    if ($arRet['SECOND_NAME']) {
                        $arRet['FIO'] .= $arRet['NAME'] . ' ' . $arRet['SECOND_NAME'];
                    } else {
                        $arRet['FIO'] .= $arRet['NAME'];
                    }

                    if ($arRet['LAST_NAME']) {
                        $arRet['FULL_NAME'] .= ' ' . $arRet['LAST_NAME'];
                        $arRet['FIO'] .= ' ' . $arRet['LAST_NAME'];
                    }
                } elseif ($arRet['LAST_NAME']) {
                    $arRet['FULL_NAME'] = $arRet['LAST_NAME'];
                    $arRet['FIO'] = $arRet['LAST_NAME'];
                }
            }
        }
        return $arRet;
    }
}