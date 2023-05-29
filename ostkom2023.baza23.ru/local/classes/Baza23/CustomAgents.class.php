<?
namespace Baza23;

use \Bitrix\Main\Loader;

class CustomAgents {
    const C_MOYSKLAD_USERNAME = 'admin@feed_79';
    const C_MOYSKLAD_PASSWORD = 'dtnhtyfzgjujlf2021';
    const C_MOYSKLAD_PROPERTY_NALICHIE_CODE = '9705f132-ed13-11ea-0a80-09e00045f3d1';
    const C_IBLOCK_PROPERTY_NALICHIE_ID = 105;

    public static function psf_setPrice() {
        if (Loader::includeModule("iblock") && Loader::includeModule("catalog")) {
            $PRICE_TYPE_ID = 2;
            $iblock = \Baza23\Site::psf_getIBlockId("catalog-kits");

            $arOfferList = [];
            $dbOffers = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $iblock],
                false,
                false,
                ["ID", "IBLOCK_ID", "NAME", "PROPERTY_KIT.ID", "PROPERTY_CML2_LINK.ID"]
            );
            while ($arOffer = $dbOffers -> fetch()) {
                $arOfferList[$arOffer["ID"]][] = $arOffer['PROPERTY_KIT_ID'];
                $arOfferList[$arOffer["ID"]]['base'] = $arOffer['PROPERTY_CML2_LINK_ID'];
            }
            if (!empty($arOfferList)) {
                foreach ($arOfferList as $key => $arOffer) {
                    $total = 0;

                    if (!empty($arOffer)) {
                        foreach ($arOffer as $el) {
                            $arPrice = \CCatalogProduct::GetOptimalPrice($el, 1, [1], "N", false, "s1");
                            $PRICE_TYPE_ID = $arPrice['RESULT_PRICE']['PRICE_TYPE_ID'];
                            $CURRENCY = $arPrice['RESULT_PRICE']['CURRENCY'];
                            $price = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
                            $total = $total + $price;
                        }
                    }

                    $total = round($total);

                    $arPrice = \CCatalogProduct::GetOptimalPrice($key, 1, [1], "N", false, "s1");
                    if (isset($arPrice['PRICE']['ID'])
                            && $arPrice['PRICE']['ID'] > 0
                            && $arPrice['PRICE']['PRICE'] != $total
                            && $total > 0) {
                        $arPriceFields = [
                            "PRODUCT_ID"       => $key,
                            "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
                            "PRICE"            => $total,
                            "CURRENCY"         => $CURRENCY,
                        ];
                        \CPrice::Update($arPrice['PRICE']['ID'], $arPriceFields);
                    }
                }
            }
        }
        return '\Baza23\CustomAgents::psf_setPrice();';
    }

    public static function psf_setMinPrice() {
        if (Loader::includeModule("iblock")) {
            $iblock = \Baza23\Site::psf_getIBlockId("catalog");

            $resElem = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $iblock, "ACTIVE" => "Y"],
                false,
                false,
                ["ID"]
            );
            while ($obElem = $resElem->Fetch()) {
                $arPrice = \CCatalogProduct::GetOptimalPrice($obElem["ID"], 1, [1], "N", false, "s1");
                if (isset($arPrice['PRICE']['ID'])) {
                    \CIBlockElement::SetPropertyValues($obElem["ID"], $iblock, $arPrice["DISCOUNT_PRICE"], "MINIMUM_PRICE");
                }
            }
        }
        return '\Baza23\CustomAgents::psf_setMinPrice();';
    }

    public static function psf_sklad_init($url) {
        $username = self::C_MOYSKLAD_USERNAME;
        $password = self::C_MOYSKLAD_PASSWORD;

        $arItems = [];
        $offset = 0;
        $limit = 1000;
        $end = false;

        while (!$end) {
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, $url . '?limit=' . $limit . '&offset=' . $offset);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
                $out = curl_exec($curl);
                curl_close($curl);

                $result = json_decode($out, true);
                $offset = $result['meta']['offset'] + $limit;

                if (!empty($result['rows'])) {
                    foreach ($result['rows'] as $item) {
                        $arItems[] = $item;
                    }
                } else {
                    $end = true;
                }
            }
        }
        return $arItems;
    }

    public static function psf_sklad_availability() {
        $attribute = self::C_MOYSKLAD_PROPERTY_NALICHIE_CODE;  //код дополнительного поля "Наличие" на складе
        $api_link = [
            0 => "https://online.moysklad.ru/api/remap/1.2/entity/product",
            1 => "https://online.moysklad.ru/api/remap/1.2/entity/bundle",
        ];

        $iblock = \Baza23\Site::psf_getIBlockId("catalog");

        $arItems = [];
        foreach ($api_link as $link) {
            $arSkladItems = self::psf_sklad_init($link);
            if (!empty($arSkladItems)) {
                foreach ($arSkladItems as $item) {
                    if (is_array($item['attributes'])) {
                        foreach ($item['attributes'] as $attr) {
                            if ($attr['id'] == $attribute) {
                                $arItems[$item['externalCode']] = $attr['value']['name'];
                            }
                        }
                    }

                    if (!$arItems[$item['externalCode']]) {
                        $arItems[$item['externalCode']] = "NO";
                    }
                }
            }
        }

        require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

        if (Loader::includeModule("iblock")) {
            $arPropertyEnums = [];
            $dbEnum = \Bitrix\Iblock\PropertyEnumerationTable::getList(
                    ['filter' => ['PROPERTY_ID' => self::C_IBLOCK_PROPERTY_NALICHIE_ID],]
            );
            while ($arEnum = $dbEnum->fetch()) {
                $arPropertyEnums[$arEnum['VALUE']] = $arEnum;
            }

            $dbElement = \CIBlockElement::GetList(
                    [],
                    ["IBLOCK_ID" => $iblock],
                    false,
                    false,
                    ["ID", "XML_ID", "QUANTITY"]
            );
            while ($arElement = $dbElement->fetch()) {
                if ($arElement['QUANTITY'] > 0) {
                    $val = 43; //chf в наличии
                } else {
                    if ($arItems[$arElement['XML_ID']]) {
                        $val = $arPropertyEnums[$arItems[$arElement['XML_ID']]]['ID'];
                        if (!$val || intVal($val) == 0) $val = 39;
                    }
                }
                \CIBlockElement::SetPropertyValues($arElement['ID'], $iblock, $val, "NALICHIE");
            }
        }
        return '\Baza23\CustomAgents::psf_sklad_availability();';
    }

    public static function psf_deleteOldBasket() {
        Loader::includeModule('catalog');

        $arFUserIds = [];
        $obBasket = \Bitrix\Sale\Basket::getList(
            [
                'select'  => [
                    'FUSER_ID'
                ],
                'filter' => [
                    'ORDER_ID' => 'NULL',
                    '<DATE_INSERT' => date('d.m.Y', time() - 86400 * 31)
                ],
            ]
        );
        while ($bItem = $obBasket -> Fetch()) {
            $arFUserIds[] = $bItem['FUSER_ID'];
        }

        $arUniqueFUserIds = array_unique($arFUserIds);

        foreach ($arUniqueFUserIds as $fuserId) {
            \CSaleBasket::DeleteAll($fuserId, false);
        }

        return ('\Baza23\CustomAgents::psf_deleteOldBasket();');
    }

    public static function psf_deleteInactive() {
        global $DB;

        $arIblocks = [];
        if (Loader::includeModule('iblock')) {
            $items = \Bitrix\Iblock\ElementTable::getList([
                "filter" => [
                    "IBLOCK_ID" => $arIblocks,
                    'ACTIVE'    => 'N'
                ],
                "select" => ["ID", "IBLOCK_ID"],
                "limit"  => 3000
            ])->fetchAll();
        }
        if (!empty($items)) {
            foreach ($items as $arItem) {
                $DB->StartTransaction();
                if (!\CIBlockElement::Delete($arItem['ID'])) {
                    \CEventLog::Add([
                            'SEVERITY'      => 'ERROR',
                            'AUDIT_TYPE_ID' => 'SV_CLEAR',
                            'MODULE_ID'     => 'iblock',
                            'ITEM_ID'       => $arItem['ID'],
                            'DESCRIPTION'   => '/local/classes/CustomAgents.php: Error deleting product: '
                                    . $arItem['ID'] . 'Infoblock: ' . $arItem['IBLOCK_ID'],
                    ]);
                    $DB->Rollback();
                } else {
                    $DB->Commit();
                }
            }
            return ('\Baza23\CustomAgents::deleteInactive();');
        }
        return '';
    }
}