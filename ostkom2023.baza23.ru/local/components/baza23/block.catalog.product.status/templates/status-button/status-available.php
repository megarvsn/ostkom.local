<?
use Bitrix\Main\Localization\Loc;

$arStatus1 = false;
$useStatusInBasket = ($arParams["USE_STATUS_IN_BASKET"] == "Y");
if ($useStatusInBasket) {
    $code1 = $arParams["STATUS_IN_BASKET_CODE"];
    if (!$code1) $code1 = "in-basket";

    $arStatus1 = $arParams["STATUS_LIST"][$code1];
}

$containerCssId = $arStatus["BUTTON_CONTAINER_ID"];

if (!empty($arStatus1)) {
    ?><div class="button-container"<?
           if ($containerCssId) { echo ' id="' . $containerCssId . '"'; }
    ?>><?

        $text1 = $arStatus1["button"];
        if (!$text1) $text1 = Loc::getMessage('STATUS_' . $code1 . '_BUTTON');

        $color1 = $arStatus1["color"];
        $url1 = $arStatus1["url"];
        $btnCssId1 = $arStatus1['BUTTON_ID'];

        if ($url1) {
            ?><a class="btn--all btn-basket item-checked status-<?= $code1 ?>"<?
                 if ($btnCssId1) { echo ' id="' . $btnCssId1 . '"'; }
                 if ($color1) { echo ' style="background-color: ' . $color1 . ';"'; }
                 ?> href="<?= $url1 ?>"<?
                 ?> rel="nofollow"<?
            ?>><?= $text1 ?></a><?

        } else {
            ?><span class="btn--all btn-basket item-checked status-<?= $code1 ?>"<?
                 if ($btnCssId1) { echo ' id="' . $btnCssId1 . '"'; }
                 if ($color1) { echo ' style="background-color: ' . $color1 . ';"'; }
            ?>><?= $text1 ?></span><?
        }

} else {
    ?><div class="button-container"<?
           if ($containerCssId) { echo ' id="' . $containerCssId . '"'; }
    ?>><?
}

    $code = $arResult["CURRENT_STATUS_CODE"];

    $text = $arStatus["button"];
    if (!$text) $text = Loc::getMessage('STATUS_' . $code . '_BUTTON');

    $color = $arStatus["color"];
    $url = $arStatus["url"];
    $btnCssId = $arStatus['BUTTON_ID'];

    if ($url) {
        ?><a class="btn--all btn-add-to-basket status-<?= $code ?>"<?
             if ($btnCssId) { echo ' id="' . $btnCssId . '"'; }
             if ($color) { echo ' style="background-color: ' . $color . ';"'; }
             ?> href="<?= $url ?>"<?
             ?> rel="nofollow"<?
        ?>><?= $text ?></a><?

    } else {
        ?><span class="btn--all btn-add-to-basket status-<?= $code ?>"<?
             if ($btnCssId) { echo ' id="' . $btnCssId . '"'; }
             if ($color) { echo ' style="background-color: ' . $color . ';"'; }
        ?>><?= $text ?></span><?
    }

    if ($arParams["SHOW_BUTTON_BUY_1_CLICK"] == "Y") {
        $productId = $arParams["PRODUCT_ID"];
        $productPrice = $arParams["~PRODUCT_PRICE"];
        $productOldPrice = $arParams["~PRODUCT_OLD_PRICE"];

        $htmlParams = '';
        if ($productId) $htmlParams .= ' data-PRODUCT_ID="' . $productId . '"';
        if ($productPrice) $htmlParams .= ' data-PRODUCT_PRICE="' . $productPrice . '"';
        if ($productOldPrice) $htmlParams .= ' data-PRODUCT_OLD_PRICE="' . $productOldPrice . '"';

        $btnBuy1Click = $arParams["BUTTON_BUY_1_CLICK"];
        if (!$btnBuy1Click) $btnBuy1Click = Loc::getMessage('BUTTON_BUY_1_CLICK');

        ?><div class="btn-buy-1-click js-form-modal"<?
               ?> data-href="buy-1-click"<?
               ?> data-type="mwf-modal"<?
               if ($htmlParams) { echo $htmlParams; }
        ?>><?
            echo $btnBuy1Click;
        ?></div><?
    }

?></div><? // class="button-container"