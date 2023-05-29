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
$suff = RandString(4);

$cssClass = $arParams["CSS_CLASSES"];
$cssId = $arParams["CSS_ID"];
if (!$cssId) $cssId = 'select-css-' . $suff;
$labelId = $arParams["LABEL_ID"];

$required = ($arParams["ATTRIBUTE_REQUIRED"] == "Y");
$placeholder = $arParams["~ATTRIBUTE_PLACEHOLDER"];
$name = $arParams["ATTRIBUTE_NAME"];
if (!$name) $name = $cssId;
$ariaLabel = $arParams["~ARIA_LABEL"];
$params = $arParams["~HTML_PARAMS"];

$showUserDefault = ($arParams["SKIP_DEFAULT"] != "Y"
        && $arResult["DEFAULT"]["USER_DEFAULT"] == "Y");
$defaultLabel = ($showUserDefault ? $arResult["DEFAULT"]["LABEL"] : '');

$selectedText = $defaultLabel;
if (!empty($arResult["SELECTED_KEYS"])) {
    $firstKey = reset($arResult["SELECTED_KEYS"]);
    $arFirstSelected = $arResult["OPTIONS"][$firstKey];
    if (!empty($arFirstSelected)) $selectedText = $arFirstSelected["LABEL"];
}

?><div class="form--select<?
              if ($cssClass) { echo ' ' . $cssClass; } ?>"<?
       if ($cssId) { ?> id="<?= $cssId ?>"<? }
       if ($labelId) { ?> aria-labelledby="<?= $labelId ?>"<? }
       if ($ariaLabel) { ?> aria-label="<?= $ariaLabel ?>"<? }
       if ($params) { ?> <?= $params ?><? }
       if ($placeholder) { ?> title="<?= $placeholder ?>"<? }
?>><?

    ?><div class="select-title"<?
        if ($showUserDefault) {
            if ($defaultLabel) { ?> data-default="<?= $defaultLabel ?>"<? }
        } elseif ($placeholder) {
            ?> data-default="<?= $placeholder ?>"<?
        }
    ?>><span><?= $selectedText ?></span></div><? // class="select-title"

    ?><div class="select-content<?
                  if ($arParams["HTML_SIZE"] > 0) { echo ' content-size-' . $arParams["HTML_SIZE"]; } ?>"<?
    ?>><?

    $type = ($arParams["MULTI_SELECT"] == "Y" ? 'checkbox' : 'radio');

    if ($showUserDefault) {
        $cssOptId = $arResult["DEFAULT"]["CSS_ID"];
        if (!$cssOptId) $cssOptId = 'option-default-' . $suff;
        ?><input id="<?= $cssOptId ?>"<?
                ?> type="<?= $type ?>"<?
                ?> name="<?= $name ?>"<?
                ?> class="option-default"<?
                ?> value="<?= $arResult["DEFAULT"]["VALUE"] ?>"<?
                ?> style="display: none;"<?
                if ($arResult["DEFAULT"]["HTML_PARAMS"]) { echo ' ' . $arResult["DEFAULT"]["HTML_PARAMS"]; }
                if (!$arResult["SELECTED_COUNT"]) { ?> checked<? }
        ?>><?
        ?><label for="<?= $cssOptId ?>"<?
                 if ($arResult["DEFAULT"]["LABEL_CSS_ID"]) { echo ' id="' . $arResult["DEFAULT"]["LABEL_CSS_ID"] . '"'; }
                 if ($arResult["DEFAULT"]["LABEL_CSS_CLASSES"]) { echo ' class="' . $arResult["DEFAULT"]["LABEL_CSS_CLASSES"] . '"'; }
                 if ($arResult["DEFAULT"]["LABEL_HTML_PARAMS"]) { echo ' ' . $arResult["DEFAULT"]["LABEL_HTML_PARAMS"]; }
        ?>><?
            ?><?= $arResult["DEFAULT"]["LABEL"] ?><?
        ?></label><?
    }

    $index = 1;
    foreach ($arResult["OPTIONS"] as $key => $arItem) {
        $cssOptId = $arItem["CSS_ID"];
        if (!$cssOptId) $cssOptId = 'option-' . $index . '-' . $suff;
        ?><input id="<?= $cssOptId ?>"<?
                ?> type="<?= $type ?>"<?
                ?> name="<?= $name ?>"<?
                if ($arItem["DEFAULT"] == "Y") { ?> class="option-default"<? }
                ?> value="<?= $arItem["VALUE"] ?>"<?
                ?> style="display: none;"<?
                if ($arItem["HTML_PARAMS"]) { echo ' ' . $arItem["HTML_PARAMS"]; }
                if ($arItem["SELECTED"] == "Y") { ?> checked<? }
        ?>><?
        ?><label for="<?= $cssOptId ?>"<?
                 if ($arItem["LABEL_CSS_ID"]) { echo ' id="' . $arItem["LABEL_CSS_ID"] . '"'; }
                 if ($arItem["LABEL_CSS_CLASSES"]) { echo ' class="' . $arItem["LABEL_CSS_CLASSES"] . '"'; }
                 if ($arItem["LABEL_HTML_PARAMS"]) { echo ' ' . $arItem["LABEL_HTML_PARAMS"]; }
        ?>><?
            ?><?= $arItem["LABEL"] ?><?
        ?></label><?

        $index ++;
    }

    ?></div><? // class="select-content"
?></div><? // class="form--select"

?><script><?
    ?>if (typeof (jso_html_selectCss) === 'undefined') {<?
        ?>jso_utilities.jsf_loadScript('<?= $templateFolder ?>/script.js', {async: false}).done(function(p_script, p_textStatus, jqXHR) {});<?
    ?>}<?

    ?>if (typeof (jso_html_selectCss) !== 'undefined') {<?
        ?>jso_html_selectCss.jsf_init('#<?= $cssId ?>');<?
    ?>}<?
?></script><?