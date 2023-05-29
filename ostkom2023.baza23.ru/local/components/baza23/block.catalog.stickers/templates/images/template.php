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
if (!empty($arResult["STICKER_IDS"]["IMAGE"])) {
    $cssClasses = $arParams["CSS_CLASSES"];
    $lazyLoad = ($arParams["LAZY_LOAD_IMAGE"] == 'Y');

    foreach ($arResult["STICKER_IDS"]["IMAGE"] as $position => $arIds) {
        $arPosition = explode('-', $position);
        if (count($arPosition) != 2) continue;

        $cssBlockClasses = ' vp-' . $arPosition[0] . ' hp-' . $arPosition[1];
        ?><div class="product-sticker-block<?
                      echo $cssBlockClasses;
                      if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
        ?>><?

        foreach ($arIds as $id) {
            if (!($arSticker = $arResult["STICKERS"][$id])) continue;
            if (!($arAttrs = $arSticker["IMAGE"])) continue;

            $cssClasses = 'sticker-' . strToLower($arSticker["CODE"]);

            $isImage = (!$arAttrs["ICON_SVG"] && $arAttrs["ICON"]["SRC"]);
            $isImageBg = ($isImage && $arAttrs["TEXT"]);

            if ($isImageBg) {
                $cssClasses .= ' sticker-bg-image';
                if ($lazyLoad) $cssClasses .= ' lazy-img-bg';
            } elseif ($isImage) {
                $cssClasses .= ' sticker-image';
            }
            if ($arAttrs["TEXT"]) {
                $cssClasses .= ' sticker-text';
            }

            $style = '';
            if ($arAttrs["BACKGROUND_COLOR"] || $arAttrs["TEXT_COLOR"]) {
                if ($arAttrs["BACKGROUND_COLOR"]) {
                    $style .= 'background-color: ' . $arAttrs["BACKGROUND_COLOR"] . ';';
                }
                if ($arAttrs["TEXT_COLOR"]) {
                    $style .= 'color: ' . $arAttrs["TEXT_COLOR"] . ';';
                }
            }

            ?><div class="item-sticker <?= $cssClasses ?>"<?
                   if ($isImageBg) {
                       if ($lazyLoad) {
                           echo ' data-src="url(' . $arAttrs["ICON"]["SRC"] . ')"';
                           if ($style) echo ' style="' . $style . '"';

                       } else {
                           echo ' style="background-image: url('
                                . $arAttrs["ICON"]["SRC"] . ');';
                           if ($style) echo $style;
                           echo '"';
                       }

                   } elseif ($style) {
                       echo ' style="' . $style . '"';
                   }
            ?>><?
                if ($arAttrs["ICON_SVG"]) {
                    ?><span class="item-svg"><?= $arAttrs["ICON_SVG"] ?></span><?

                } elseif (!$isImageBg && $arAttrs["ICON"]["SRC"]) {
                    ?><img class="<? if ($lazyLoad) { echo ' lazy-img'; } ?>"<?
                           ?> alt="<?= $arAttrs["ICON"]["ALT"] ?>"<?
                           ?> width="<?= $arAttrs["ICON"]["WIDTH"] ?>"<?
                           ?> height="<?= $arAttrs["ICON"]["HEIGHT"] ?>"<?
                           if ($lazyLoad) echo ' data-src="' . $arAttrs["ICON"]["SRC"] . '"';
                           else echo ' src="' . $arAttrs["ICON"]["SRC"] . '"';
                    ?>><?
                }
                if ($arAttrs["TEXT"]) {
                    ?><span class="item-text"><?= $arAttrs["TEXT"] ?></span><?
                }
            ?></div><? // class="item-sticker"
        }

        ?></div><? // class="product-sticker-block"
    }
}

if ($arParams["SHOW_DISCOUNT_PERCENT"] == "Y"
        && count($arResult["DISCOUNT_PERCENT"]["POSITION"]) == 2
        && $arResult["DISCOUNT_PERCENT"]["PERCENT"]) {

    $cssClasses = 'product-sticker-discount-percent'
             . ' vp-' . $arResult["DISCOUNT_PERCENT"]["POSITION"]["VERTICAL"]
             . ' hp-' . $arResult["DISCOUNT_PERCENT"]["POSITION"]["HORIZONTAL"];

    if ($arParams["CSS_CLASSES"]) $cssClasses .= ' ' . $arParams["CSS_CLASSES"];

    ?><div class="<?= $cssClasses ?>"><?
        ?><span><?= $arResult["DISCOUNT_PERCENT"]["PERCENT"] ?></span><?
    ?></div><?
}