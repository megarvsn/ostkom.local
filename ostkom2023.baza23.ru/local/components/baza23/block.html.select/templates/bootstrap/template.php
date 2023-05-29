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
$cssClass = $arParams["CSS_CLASSES"];
$cssId = $arParams["CSS_ID"];
$labelId = $arParams["LABEL_ID"];

$required = ($arParams["ATTRIBUTE_REQUIRED"] == "Y");
$placeholder = $arParams["~ATTRIBUTE_PLACEHOLDER"];
$name = $arParams["ATTRIBUTE_NAME"];
$ariaLabel = $arParams["~ARIA_LABEL"];
$params = $arParams["~HTML_PARAMS"];

$showUserDefault = ($arParams["SKIP_DEFAULT"] != "Y"
        && $arResult["DEFAULT"]["USER_DEFAULT"] == "Y");

?><select class="select-2<? if ($cssClass) { ?> <?= $cssClass ?><? } ?>"<?
        ?> name="<?= $name ?>"<?
        if ($cssId) { ?> id="<?= $cssId ?>"<? }
        if ($labelId) { ?> aria-labelledby="<?= $labelId ?>"<? }
        if ($ariaLabel) { ?> aria-label="<?= $ariaLabel ?>"<? }
        if ($arParams["HTML_SIZE"] > 0) { ?> size="<?= $arParams["HTML_SIZE"] ?>"<? }
        if ($arParams["MULTI_SELECT"] == "Y") {
            ?> multiple="multiple"<?
            ?> data-selected-text-format=""<? // "count > 2"
            ?> data-actions-box="true"<?
            if ($arParams["~BTN_SELECT_ALL"]) { ?> data-select-all-text="<?= $arParams["~BTN_SELECT_ALL"] ?>"<? }
            if ($arParams["~BTN_DESELECT_ALL"]) { ?> data-deselect-all-text="<?= $arParams["~BTN_DESELECT_ALL"] ?>"<? }
        }
        if ($required) { ?> required=""<? }
        if ($placeholder) { ?> title="<?= $placeholder ?>"<? }
        if ($params) { ?> <?= $params ?><? }
?>><?

    if ($showUserDefault) {
        ?><option value="<?= $arResult["DEFAULT"]["VALUE"] ?>"<?
                  ?> class="option-default"<?
                  if ($placeholder) { ?> title="<?= $placeholder ?>"<? }
                  if ($arResult["DEFAULT"]["HTML_PARAMS"]) { echo ' ' . $arResult["DEFAULT"]["HTML_PARAMS"]; }
                  if (!$arResult["SELECTED_COUNT"]) { ?> selected=""<? }
        ?>><?= $arResult["DEFAULT"]["LABEL"] ?></option><?
    }

    $index = 1;
    foreach ($arResult["OPTIONS"] as $key => $arItem) {
        ?><option value="<?= $arItem["VALUE"] ?>"<?
                  if ($arItem["DEFAULT"] == "Y") { ?> class="option-default"<? }
                  if ($arItem["HTML_PARAMS"]) { echo ' ' . $arItem["HTML_PARAMS"]; }
                  if ($arItem["SELECTED"] == "Y") { ?> selected=""<? }
        ?>><?= $arItem["LABEL"] ?></option><?

        $index ++;
    }

?></select><?