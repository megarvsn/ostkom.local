<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader;

class ReceiptNotify {
    const IBLOCK_CODE_CATALOG = 'catalog';

    const CATALOG_IBLOCK_PROPERTY_CODE = 'NALICHIE';
    const CATALOG_IBLOCK_PROPERTY_XML_ID = '1';

    const WEB_FORM_ID = 19;
    const MAIL_EVENT_TYPE = 'CATALOG_PRODUCT_SUBSCRIBE_NOTIFY';
    const MAIL_EVENT_MESSAGE_ID = 66;

    const DEBUG = "N";

    public function initCheckElement() {
        $EventManager = EventManager::getInstance();
        $EventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate',
                ["\Baza23\ReceiptNotify", "OnBeforeIBlockElementUpdateHandler"]
        );

        $EventManager->addEventHandler('iblock', 'OnBeforeIBlockPropertyUpdate',
                ["\Baza23\ReceiptNotify", "OnBeforeIBlockPropertyUpdateHandler"]
        );

        $EventManager->addEventHandler('iblock', 'OnIBlockElementSetPropertyValues',
                ["\Baza23\ReceiptNotify", "OnIBlockElementSetPropertyValuesHandler"]
        );

        $EventManager->addEventHandler('iblock', 'OnIBlockElementSetPropertyValuesEx',
                ["\Baza23\ReceiptNotify", "OnIBlockElementSetPropertyValuesExHandler"]
        );
    }

    static function OnBeforeIBlockElementUpdateHandler(&$arFields) {
        $iblockId = \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_CATALOG);
        if ($iblockId != $arFields["IBLOCK_ID"]) return true;

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$arFields], date("m.d.y H:i:s ") . "OnBeforeIBlockElementUpdateHandler 1", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        $arProperty = self::psf_getPropertyValue($iblockId);
        if (empty($arProperty)) return true;

        $value = false;
        if (isset($arFields["PROPERTY_VALUES"][$arProperty["PROPERTY_ID"]])) {
            $value = self::psf_getValue($arFields["PROPERTY_VALUES"][$arProperty["PROPERTY_ID"]]);

        } elseif (isset($arFields["PROPERTY_VALUES"][self::CATALOG_IBLOCK_PROPERTY_CODE])) {
            $value = self::psf_getValue($arFields["PROPERTY_VALUES"][self::CATALOG_IBLOCK_PROPERTY_CODE]);

        } else {
            return true;
        }

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$value, $arProperty], date("m.d.y H:i:s ") . "OnBeforeIBlockElementUpdateHandler 2", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if ($value != $arProperty["ID"]) return true;

        $productId = $arFields["ID"];
        $arProduct = self::psf_getProduct($iblockId, $productId);

        if (empty($arProduct)
                || $arProduct["U_PROPERTY_VALUE"] == $value
                || $arProduct["QUANTITY"] <= 0) return true;

        self::psf_notify($arProduct);
        return true;
    }

    static function OnBeforeIBlockPropertyUpdateHandler(&$arFields) {
        $iblockId = \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_CATALOG);
        if ($iblockId != $arFields["IBLOCK_ID"]) return true;

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$arFields], date("m.d.y H:i:s ") . "OnBeforeIBlockPropertyUpdateHandler 1", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        $arProperty = self::psf_getPropertyValue($iblockId);
        if (empty($arProperty)) return true;

        $value = false;
        if (isset($arFields["PROPERTY_VALUES"][$arProperty["PROPERTY_ID"]])) {
            $value = self::psf_getValue($arFields["PROPERTY_VALUES"][$arProperty["PROPERTY_ID"]]);

        } elseif (isset($arFields["PROPERTY_VALUES"][self::CATALOG_IBLOCK_PROPERTY_CODE])) {
            $value = self::psf_getValue($arFields["PROPERTY_VALUES"][self::CATALOG_IBLOCK_PROPERTY_CODE]);

        } else {
            return true;
        }

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$value, $arProperty], date("m.d.y H:i:s ") . "OnBeforeIBlockPropertyUpdateHandler 2", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if ($value != $arProperty["ID"]) return true;

        $productId = $arFields["ID"];
        $arProduct = self::psf_getProduct($iblockId, $productId);
        if (empty($arProduct)
                || $arProduct["U_PROPERTY_VALUE"] == $value
                || $arProduct["QUANTITY"] <= 0) return true;

        self::psf_notify($arProduct);
        return true;
    }

    static function OnIBlockElementSetPropertyValuesHandler($ELEMENT_ID, $IBLOCK_ID, $PROPERTY_VALUES, $PROPERTY_CODE, $ar_prop, $arDBProps) {
        $iblockId = \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_CATALOG);
        if ($iblockId != $IBLOCK_ID) return true;

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$ELEMENT_ID, $IBLOCK_ID, $PROPERTY_VALUES, $PROPERTY_CODE, $ar_prop, $arDBProps], date("m.d.y H:i:s ") . "OnIBlockElementSetPropertyValuesHandler 1", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        $arProperty = self::psf_getPropertyValue($iblockId);
        if (empty($arProperty)) return true;

        $value = false;
        if ($PROPERTY_CODE == self::CATALOG_IBLOCK_PROPERTY_CODE) {
            $value = self::psf_getValue($PROPERTY_VALUES);

        } elseif (isset($PROPERTY_VALUES[$arProperty["PROPERTY_ID"]])) {
            $value = self::psf_getValue($PROPERTY_VALUES[$arProperty["PROPERTY_ID"]]);

        } elseif (isset($PROPERTY_VALUES[self::CATALOG_IBLOCK_PROPERTY_CODE])) {
            $value = self::psf_getValue($PROPERTY_VALUES[self::CATALOG_IBLOCK_PROPERTY_CODE]);

        } else {
            return true;
        }

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$value, $arProperty], date("m.d.y H:i:s ") . "OnIBlockElementSetPropertyValuesHandler 2", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if ($value != $arProperty["ID"]) return true;

        $productId = $ELEMENT_ID;
        $arProduct = self::psf_getProduct($iblockId, $productId);
        if (empty($arProduct)
                || $arProduct["U_PROPERTY_VALUE"] == $value
                || $arProduct["QUANTITY"] <= 0) return true;

        self::psf_notify($arProduct);
        return true;
    }

    static function OnIBlockElementSetPropertyValuesExHandler($ELEMENT_ID, $IBLOCK_ID, $PROPERTY_VALUES, $propertyList, $arDBProps) {
        $iblockId = \Baza23\Site::psf_getIBlockId(self::IBLOCK_CODE_CATALOG);
        if ($iblockId != $IBLOCK_ID) return true;

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$ELEMENT_ID, $IBLOCK_ID, $PROPERTY_VALUES, $propertyList, $arDBProps], date("m.d.y H:i:s ") . "OnIBlockElementSetPropertyValuesExHandler 1", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        $arProperty = self::psf_getPropertyValue($iblockId);
        if (empty($arProperty)) return true;

        $value = false;
        if (isset($PROPERTY_VALUES[$arProperty["PROPERTY_ID"]])) {
            $value = self::psf_getValue($PROPERTY_VALUES[$arProperty["PROPERTY_ID"]]);

        } elseif (isset($PROPERTY_VALUES[self::CATALOG_IBLOCK_PROPERTY_CODE])) {
            $value = self::psf_getValue($PROPERTY_VALUES[self::CATALOG_IBLOCK_PROPERTY_CODE]);

        } else {
            return true;
        }

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$value, $arProperty], date("m.d.y H:i:s ") . "OnIBlockElementSetPropertyValuesExHandler 2", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if ($value != $arProperty["ID"]) return true;

        $productId = $ELEMENT_ID;
        $arProduct = self::psf_getProduct($iblockId, $productId);
        if (empty($arProduct)
                || $arProduct["U_PROPERTY_VALUE"] == $value
                || $arProduct["QUANTITY"] <= 0) return true;

        self::psf_notify($arProduct);
        return true;
    }

    static function psf_getValue($p_value) {
        $ret = false;

        if (is_array($p_value)) {
            if (isset($p_value["VALUE"])) {
                $ret = $p_value["VALUE"];

            } else {
                $p_value = reset($p_value);
                if (is_array($p_value)) {
                    if (isset($p_value["VALUE"])) {
                        $ret = $p_value["VALUE"];

                    } else {
                        $ret = reset($p_value);
                    }

                } else {
                    $ret = $p_value;
                }
            }

        } else {
            $ret = $p_value;
        }

        if (!$ret) $ret = self::CATALOG_IBLOCK_PROPERTY_NEW_VALUE;
        return $ret;
    }

    static function psf_getPropertyValue($p_iblockId) {
        if (!Loader::IncludeModule("iblock")) return false;

        $arEnum = \Baza23\DataUtils::psf_getPropertyEnum(
                $p_iblockId, self::CATALOG_IBLOCK_PROPERTY_CODE);

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([self::CATALOG_IBLOCK_PROPERTY_CODE, self::CATALOG_IBLOCK_PROPERTY_XML_ID, $arEnum], date("m.d.y H:i:s ") . "psf_getPropertyValue", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if (empty($arEnum)) return false;

        $arRet = $arEnum[self::CATALOG_IBLOCK_PROPERTY_XML_ID];
        return $arRet;
    }

    static function psf_getProduct($p_iblockId, $p_productId) {
        if (!Loader::IncludeModule("iblock")
                || !Loader::IncludeModule("catalog")) return true;

        $db_res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $p_iblockId,
                    "ACTIVE" => "Y",
                    "ID" => $p_productId
                ],
                false,
                ["nTopCount" => 1],
                ["IBLOCK_ID", "ID", "NAME", "PRICE_1", "QUANTITY", "SUBSCRIBE",
                    "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL",
                    "PROPERTY_UP_SHORT_URL",
                    "PROPERTY_" . self::CATALOG_IBLOCK_PROPERTY_CODE
                ]
        );
        if ($arElement = $db_res->GetNext(false, false)) {
            $arProduct = [
                "ID"        => $arElement["ID"],
                "NAME"      => $arElement["NAME"],
                "PHOTO_URL" => '',
                "FULL_URL"  => \Baza23\Site::psf_getFullUrl($arElement["DETAIL_PAGE_URL"]),
                "URL"       => $arElement["DETAIL_PAGE_URL"],
                "SHORT_URL" => $arElement["PROPERTY_UP_SHORT_URL_VALUE"],
                "QUANTITY"  => $arElement["QUANTITY"],
                "PRICE"     => $arElement["PRICE_1"],
                "DISCOUNT_PRICE" => '',

                "U_PROPERTY_VALUE" => $arElement["PROPERTY_" . self::CATALOG_IBLOCK_PROPERTY_CODE . "_ENUM_ID"],
            ];

            $arOptimalPrice = \CCatalogProduct::GetOptimalPrice($arElement["ID"], 1,
                    [2], 'N', [], SITE_ID, []);
            if ($arOptimalPrice['DISCOUNT_PRICE']) {
                $arProduct["DISCOUNT_PRICE"] = $arOptimalPrice['DISCOUNT_PRICE'];
            }

            $pictureId = ($arElement["PREVIEW_PICTURE"]
                    ? $arElement["PREVIEW_PICTURE"]
                    : $arElement["DETAIL_PICTURE"]);
            $arFile = \CFile::GetFileArray($pictureId);
            if ($arFile) {
                $arNewFile = \CFile::ResizeImageGet($arFile,
                        array('width' => 80, 'height' => 80),
                        BX_RESIZE_IMAGE_PROPORTIONAL, true);
                if ($arNewFile['src']) $arProduct["PHOTO_URL"] = \Baza23\Site::psf_getFullUrl($arNewFile['src']);
            }
        }

        if ($arProduct["FULL_URL"]) {
            $shortPageUrl = $arProduct["SHORT_URL"];
            if (strpos($shortPageUrl, 'http:///catalog/') === 0) $shortPageUrl = false;
            if (!$shortPageUrl) {
                $shortPageUrl = file_get_contents("https://clck.ru/--?url=" . $arProduct["FULL_URL"]);
                if ($shortPageUrl) {
                    \CIBlockElement::SetPropertyValuesEx($arProduct["PRODUCT_ID"],
                            $p_iblockId, ["UP_SHORT_URL" => $shortPageUrl]);
                }
            }
        }

        if ($shortPageUrl) $arProduct["SHORT_URL"] = $shortPageUrl;
        else $arProduct["SHORT_URL"] = $arProduct["FULL_URL"];
        return $arProduct;
    }

    static function psf_getShortDetailPageUrl($p_iblockId, $p_productId) {
        if (!Loader::IncludeModule("iblock")) return true;

        $db_res = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $p_iblockId,
                    "ACTIVE" => "Y",
                    "ID" => $p_productId
                ],
                false,
                ["nTopCount" => 1],
                ["IBLOCK_ID", "ID", "DETAIL_PAGE_URL", "PROPERTY_UP_SHORT_URL"]
        );
        if ($arElement = $db_res->GetNext()) {
            $detailPageUrl = $arElement["DETAIL_PAGE_URL"];
            $shortPageUrl = $arElement["PROPERTY_UP_SHORT_URL_VALUE"];
            if (strpos($shortPageUrl, 'http:///catalog/') === 0) $shortPageUrl = false;
        }

        if (!$shortPageUrl && $detailPageUrl) {
            $detailPageUrl = self::HOME_URL . $detailPageUrl;
            $shortPageUrl = file_get_contents("https://clck.ru/--?url=" . $detailPageUrl);
            if ($shortPageUrl) {
                \CIBlockElement::SetPropertyValuesEx($p_productId,
                        $p_iblockId, ["UP_SHORT_URL" => $shortPageUrl]);
            }
        }
        return $shortPageUrl;
    }

    static function psf_notify($p_arProduct) {
        if (empty($p_arProduct || $p_arProduct["QUANTITY"] <= 0)) return true;
        if (!Loader::IncludeModule("form")) return true;

        $arResultIds = [];
        $arFilter["FIELDS"][] = [
            "CODE"              => "product_id",         // код поля по которому фильтруем
            "FILTER_TYPE"       => "text",               // фильтруем по числовому полю
            "PARAMETER_NAME"    => "USER",               // по значению введенному с клавиатуры
            "VALUE"             => $p_arProduct["ID"],   // значение по которому фильтруем
            "PART"              => 0,                    // прямое совпадение со значением (не интервал)
            "EXACT_MATCH"       => "N"
        ];

        $rsResults = \CFormResult::GetList(
            self::WEB_FORM_ID,
            ($by="s_timestamp"),
            ($order="desc"),
            $arFilter,
            $is_filtered,
            "N"
        );
        while ($arResult = $rsResults -> Fetch()) {
            $arResultIds[] = $arResult["ID"];
        }

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$p_arProduct, $arFilter, $arResultIds], date("m.d.y H:i:s ") . "psf_notify 1", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        if (empty($arResultIds)) return true;

        $arUsers = [];

        $arFields = [
            "site_id",
            "email",
            "phone",
        ];
        foreach ($arResultIds as $RESULT_ID) {
            $arAnswer = \CFormResult::GetDataByID($RESULT_ID,
                    $arFields, $arResult, $arAnswer2);

            $arUsers[$RESULT_ID] = [
                "RESULT_ID"    => $RESULT_ID,
                "SITE_ID"      => trim($arAnswer["site_id"][0]["USER_TEXT"]),
                "EMAIL"        => trim($arAnswer["email"][0]["USER_TEXT"]),
                "PHONE"        => trim($arAnswer["phone"][0]["USER_TEXT"]),
                "SUBSCRIBE_ID" => ''
            ];
        }

        $oldPice = "";
        if ($p_arProduct['DISCOUNT_PRICE']) {
            $price = $p_arProduct['DISCOUNT_PRICE'];
            if ($p_arProduct['DISCOUNT_PRICE'] < $p_arProduct['PRICE']) {
                $oldPice = $p_arProduct['PRICE'];
            }
        } else {
            $price = $p_arProduct['PRICE'];
        }

        $arSubscribeIds = [];
        $arEventFields = [
            "email"             => '',
            "phone"             => '',

            "product_id"        => $p_arProduct['ID'],
            "product_name"      => $p_arProduct['NAME'],
            "product_price"     => $price,
            "product_old_price" => $oldPice,
            "product_url"       => $p_arProduct['FULL_URL'],
            "product_short_url" => $p_arProduct['SHORT_URL'],
            "product_picture_src" => $p_arProduct['PHOTO_URL'],

            "CHECKOUT_URL"      => $p_arProduct['FULL_URL'] . "?action=BUY&id=" . $p_arProduct['ID']
        ];

        $arEventFields["SITE_LOGO"] = \Baza23\Settings::psf_contacts_getImageSrc('email', 'logo');
        $arEventFields["SITE_NAME"] = \Baza23\Settings::psf_contacts_getText('email', 'company-name');

        $url = \Baza23\Settings::psf_contacts_getText('email', 'site-url');
        $urlText = \Baza23\Settings::psf_contacts_getText('email', 'site-url-text');
        $strUrl = '<a href="' . $url . '" style="color:inherit;">' . $urlText . '</a>';
        $arEventFields["SITE_URL"] = $strUrl;

        $email = \Baza23\Settings::psf_contacts_getText('email', 'email');
        $strEmail = '<a href="' . $email . '" style="color:inherit;">' . $email . '</a>';
        $arEventFields["SITE_EMAIL"] = $strEmail;

        $phone = \Baza23\Settings::psf_contacts_getText('email', 'phone');
        $phoneHref = \Baza23\Utils::psf_clearPhone($phone, true);
        $strPhone = '<a href="tel:' . $phoneHref . '" style="color:inherit;">' . $phone . '</a>';
        $arEventFields["SITE_PHONE"] = $strPhone;

        if (self::DEBUG == "Y") {
            \Bitrix\Main\Diag\Debug::writeToFile([$arUsers, $arEventFields], date("m.d.y H:i:s ") . "psf_notify 2", "/local/log/" . date("y_m_d_") . "result_log.txt");
        }

        foreach ($arUsers as $user) {
            if (empty($user['EMAIL'])) continue;

            $arEventFields["email"] = $user['EMAIL'];
            $arEventFields["phone"] = $user['PHONE'];

            if (self::DEBUG == "Y") {
                \Bitrix\Main\Diag\Debug::writeToFile([SITE_ID, $user, $arEventFields], date("m.d.y H:i:s ") . "psf_notify 3", "/local/log/" . date("y_m_d_") . "result_log.txt");
            }

            \CEvent::Send(self::MAIL_EVENT_TYPE, $user["SITE_ID"],
                    $arEventFields, "Y", self::MAIL_EVENT_MESSAGE_ID);
            \CFormResult::Delete($user['RESULT_ID']);

            if ($user["SUBSCRIBE_ID"]) $arSubscribeIds[] = $user["SUBSCRIBE_ID"];
        }

        if (!empty($arSubscribeIds)) {
            $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
            $subscribeManager -> deleteManySubscriptions($arSubscribeIds);
        }
        return true;
    }
}