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

if ($arParams["PRODUCT_ID"]) {
    $arStatus = false;
    if ($arResult["CURRENT_STATUS_CODE"]) {
        $arStatus = $arParams["STATUS_LIST"][$arResult["CURRENT_STATUS_CODE"]];
    }

    if (!empty($arStatus)) {
        $cssClasses = $arParams["CSS_CLASSES"];
        if ($arResult["CSS_CLASSES"]) {
            if ($cssClasses) $cssClasses .= ' ';
            $cssClasses .= $arResult["CSS_CLASSES"];
        }

        $cssId = $arParams["CSS_ID"];
        $htmlParams = $arParams["~HTML_PARAMS"];

        $canBuy = ($arResult["PRODUCT_CAN_BUY"] == "Y");

        ?><div class="product-item-status<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
               if ($cssId) { echo ' id="' . $cssId . '"'; }
               if ($htmlParams) { echo ' ' . $htmlParams; }
        ?>><?

            if ($canBuy) {
                include __DIR__ . '/status-available.php';
            } else {
                include __DIR__ . '/status-not-available.php';
            }
        ?></div><? // class="product-item-status"
    }
}