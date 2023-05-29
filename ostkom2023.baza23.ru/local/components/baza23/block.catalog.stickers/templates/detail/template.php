<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$cssClasses = $arParams["CSS_CLASSES"];

if (!empty($arResult["STICKER_IDS"]["DETAIL"])) {
    ?><div class="product-sticker-list<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"><?

    foreach ($arResult["STICKER_IDS"]["DETAIL"] as $id) {
        if (!($arSticker = $arResult["STICKERS"][$id])) continue;

        $arAttrs = $arSticker["DETAIL"];
        if (!$arAttrs) continue;

        $cssClasses = 'sticker-' . strToLower($arSticker["CODE"]);

        $isImage = (!$arAttrs["ICON_SVG"] && $arAttrs["ICON"]["SRC"]);
        if ($isImage) {
            $cssClasses .= ' sticker-bg-image';
            if ($arParams["LAZY_LOAD_IMAGE"] == 'Y') $cssClasses .= ' lazy-img-bg';
        }

        ?><div class="item-sticker <?= $cssClasses ?>"<?
               if ($isImage) {
                   if ($arParams["LAZY_LOAD_IMAGE"] == 'Y') {
                       echo ' data-src="url(' . $arAttrs["ICON"]["SRC"] . ')"';
                   } else {
                       echo ' style="background-image: url(' . $arAttrs["ICON"]["SRC"] . ');"';
                   }
               }
        ?>><?
            if ($arAttrs["ICON_SVG"]) {
                ?><span class="sticker-svg"><?= $arAttrs["ICON_SVG"] ?></span><?
            }
        ?></div><? // class="item-sticker"
    }

    ?></div><? // class="product-sticker-list"
}