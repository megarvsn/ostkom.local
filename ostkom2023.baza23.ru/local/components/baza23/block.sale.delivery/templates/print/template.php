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
$cssId = $arParams["CSS_ID"];
$cssClasses = $arParams["CSS_CLASSES"];

$showName = ($arParams["SHOW_NAME"] == "Y");
$showText = ($arParams["SHOW_TEXT"] == "Y");
$showImage = ($arParams["SHOW_IMAGE"] == "Y");

if ($arResult["DELIVERY"]) {
    if ($showText) {
        $showText = false;
        foreach ($arResult["DELIVERY"] as $id => $arDelivery) {
            if ($arDelivery["DESCRIPTION"]) {
                $showText = true;
                break;
            }
        }
    }

    ?><div class="delivery-list<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
           if ($cssId) { echo ' id="' . $cssId . '"'; }
    ?>><?

    foreach ($arResult["DELIVERY"] as $id => $arDelivery) {
        ?><div class="delivery-item"><?
            ?><div class="item-title"><?
                if ($showImage) {
                    ?><div class="item-icon"><?
                        if ($arDelivery["IMAGE"]["SRC"]) {
                            ?><img src="<?= $arDelivery["IMAGE"]["SRC"] ?>" alt=""><?
                        }
                    ?></div><?
                }
                if ($showName) {
                    ?><div class="item-head"><?= $arDelivery["NAME"] ?></div><?
                }
            ?></div><? // class="item-title"

            if ($showText) {
                ?><div class="item-descr"><?= $arDelivery["DESCRIPTION"] ?></div><?
            }
        ?></div><? // class="delivery-item"
    }

    ?></div><? // class="delivery-list
}