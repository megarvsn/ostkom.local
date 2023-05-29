<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_CatalogStickers extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $p_arParams
     * @return mixed
     */
    public function onPrepareComponentParams($p_arParams) {
        if (!isset($p_arParams["CACHE_TYPE"])) $p_arParams["CACHE_TYPE"] = "N";
        if (!isset($p_arParams["CACHE_TIME"])) $p_arParams["CACHE_TIME"] = 0;

        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);

        $p_arParams["LAZY_LOAD_IMAGE"] = (trim($p_arParams["LAZY_LOAD_IMAGE"]) == "Y" ? "Y" : "N");

        $p_arParams["SHOW_DISCOUNT_PERCENT"] = (trim($p_arParams["SHOW_DISCOUNT_PERCENT"]) == "Y" ? "Y" : "N");
        $p_arParams["DISCOUNT_PERCENT"] = trim($p_arParams["DISCOUNT_PERCENT"]);

        $p_arParams["DISCOUNT_PERCENT_POSITION"] = trim($p_arParams["DISCOUNT_PERCENT_POSITION"]);
        return $p_arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {
        if (!$this->_checkModules()) return $this->arResult;

        $this->arParams = $this->onPrepareComponentParams($this->arParams);

        $this->arResult["STICKERS"] = self::psf_stickers_all();

        $this->arResult["STICKER_IDS"] = [
            "IMAGE" => [],
            "DETAIL" => [],
        ];
        foreach ($this->arParams["STICKER_IDS"] as $stickerId) {
            $arSticker = $this->arResult["STICKERS"][$stickerId];
            if ($arSticker) {
                if ($arSticker["IMAGE"]["SHOW"] == "Y"
                        && $arSticker["IMAGE"]["POSITION"]) {
                    $position = $arSticker["IMAGE"]["POSITION"]["VERTICAL"]
                            . '-' . $arSticker["IMAGE"]["POSITION"]["HORIZONTAL"];
                    $this->arResult["STICKER_IDS"]["IMAGE"][$position][] = $stickerId;
                }
                if ($arSticker["DETAIL"]["SHOW"] == 'Y') {
                    $this->arResult["STICKER_IDS"]["DETAIL"][] = $stickerId;
                }
            }
        }

        if ($this->arParams["SHOW_DISCOUNT_PERCENT"] == 'Y') {
            $this->arResult["DISCOUNT_PERCENT"] = [
                "PERCENT"  => '-' . $this->arParams["DISCOUNT_PERCENT"] . '%',
                "POSITION" => self::psf_getPosition(
                        $this->arParams["DISCOUNT_PERCENT_POSITION"], 'bottom-right')
            ];
        }

        $this -> IncludeComponentTemplate();
        return $this;
    }

    public static function psf_getPosition($p_positionText, $p_default = false) {
        $positionText = $p_positionText;
        if (!$p_positionText || $p_positionText == 'none') $positionText = $p_default;

        if (!$positionText) {
            $arRet = [
                "HORIZONTAL" => 'center',
                "VERTICAL"   => 'middle',
            ];

        } else {

            $vertical = '';
            if (strpos($positionText, 'top') !== false) {
                $vertical = 'top';
            } elseif (strpos($positionText, 'bottom') !== false) {
                $vertical = 'bottom';
            } else {
                $vertical = 'middle';
            }

            $horizontal = '';
            if (strpos($positionText, 'left') !== false) {
                $horizontal = 'left';
            } elseif (strpos($positionText, 'right') !== false) {
                $horizontal = 'right';
            } else {
                $horizontal = 'center';
            }

            if ($horizontal && $vertical) {
                $arRet = [
                    "HORIZONTAL" => $horizontal,
                    "VERTICAL"   => $vertical,
                ];
            }
        }
        return $arRet;
    }

    public static function psf_stickers_all() {
        $arElements = \Baza23\DataUtils::psf_getAllElements(
                \Baza23\Site::psf_getIBlockId('product-stickers'),
                false, false,
                ["ID", "CODE", "NAME",
                    "PROPERTY_UP_SHOW_ON_IMAGE",
                    "PROPERTY_UP_IMAGE_ICON", "PROPERTY_UP_IMAGE_ICON_SVG",
                    "PROPERTY_UP_IMAGE_BACKGROUND_COLOR", "PROPERTY_UP_IMAGE_TEXT_COLOR",
                    "PROPERTY_UP_IMAGE_POSITION", "PROPERTY_UP_IMAGE_TEXT",
                    "PROPERTY_UP_SHOW_IN_DETAIL",
                    "PROPERTY_UP_DETAIL_ICON", "PROPERTY_UP_DETAIL_ICON_SVG",
                ]
        );

        $arRet = [];
        foreach ($arElements as $arItem) {
            $arSticker = [
                "ID"                => $arItem["ID"],
                "CODE"              => $arItem["CODE"],
                "NAME"              => $arItem["NAME"],
            ];

            $imageShow = (trim($arItem["PROPERTY_UP_SHOW_ON_IMAGE_VALUE"]) == "Y" ? "Y" : "N");
            if ($imageShow) {
                $position = self::psf_getPosition(
                        $arItem["PROPERTY_UP_IMAGE_POSITION_VALUE"], 'top-left');

                $svg = $arItem["PROPERTY_UP_IMAGE_ICON_SVG_VALUE"];
                if (is_array($svg)) $svg = $svg["TEXT"];

                $text = $arItem["PROPERTY_UP_IMAGE_TEXT_VALUE"];
                if (is_array($text)) $text = $text["TEXT"];

                $arSticker["IMAGE"] = [
                    "SHOW"             => "Y",
                    "ICON"             => \CFile::GetFileArray($arItem["PROPERTY_UP_IMAGE_ICON_VALUE"]),
                    "ICON_SVG"         => $svg,
                    "POSITION"         => $position,
                    "BACKGROUND_COLOR" => trim($arItem["PROPERTY_UP_IMAGE_BACKGROUND_COLOR_VALUE"]),
                    "TEXT_COLOR"       => trim($arItem["PROPERTY_UP_IMAGE_TEXT_COLOR_VALUE"]),
                    "TEXT"             => $text,
                ];
            }

            $detailShow = (trim($arItem["PROPERTY_UP_SHOW_ON_DETAIL_VALUE"]) == "Y" ? "Y" : "N");
            if ($detailShow) {
                $svg = $arItem["PROPERTY_UP_DETAIL_ICON_SVG_VALUE"];
                if (is_array($svg)) $svg = $svg["TEXT"];

                $arSticker["DETAIL"] = [
                    "SHOW"     => "Y",
                    "ICON"     => \CFile::GetFileArray($arItem["PROPERTY_UP_DETAIL_ICON_VALUE"]),
                    "ICON_SVG" => $svg,
                ];
            }

            $arRet[$arItem["ID"]] = $arSticker;
        }
        return $arRet;
    }
}