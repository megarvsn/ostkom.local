<?
use Bitrix\Main\Localization\Loc;

$containerCssId = $arStatus["BUTTON_CONTAINER_ID"];

?><div class="button-container"<?
       if ($containerCssId) { echo ' id="' . $containerCssId . '"'; }
?>><?

    $code = $arResult["CURRENT_STATUS_CODE"];

    $text = $arStatus["button"];
    if (!$text) $text = Loc::getMessage('STATUS_' . $code . '_BUTTON');

    $color = $arStatus["color"];
    $url = $arStatus["url"];
    $btnCssId = $arStatus['BUTTON_ID'];
    $modalCode = $arStatus["modal-code"];

    $productId = $arParams["PRODUCT_ID"];
    $productPrice = $arParams["~PRODUCT_PRICE"];
    $productOldPrice = $arParams["~PRODUCT_OLD_PRICE"];

    $htmlParams = '';
    if ($productId) $htmlParams .= ' data-PRODUCT_ID="' . $productId . '"';
    if ($productPrice) $htmlParams .= ' data-PRODUCT_PRICE="' . $productPrice . '"';
    if ($productOldPrice) $htmlParams .= ' data-PRODUCT_OLD_PRICE="' . $productOldPrice . '"';

    if ($url) {
        ?><a class="btn--all btn-receipt status-<?= $code ?>"<?
             if ($btnCssId) { echo ' id="' . $btnCssId . '"'; }
             if ($color) { echo ' style="background-color: ' . $color . ';"'; }
             if ($htmlParams) { echo $htmlParams; }
             ?> href="<?= $url ?>"<?
             ?> rel="nofollow"<?
        ?>><?= $text ?></a><?

    } elseif ($arStatus["show-modal"] == "Y" && $modalCode) {
        ?><span class="btn--all btn-receipt js-form-modal status-<?= $code ?>"<?
                ?> data-href="<?= $modalCode ?>"<?
                ?> data-type="mwf-modal"<?
                if ($btnCssId) { echo ' id="' . $btnCssId . '"'; }
                if ($color) { echo ' style="background-color: ' . $color . ';"'; }
                if ($htmlParams) { echo $htmlParams; }
        ?>><?= $text ?></span><?

    } else {
        ?><span class="btn--all btn-receipt status-<?= $code ?>"<?
             if ($btnCssId) { echo ' id="' . $btnCssId . '"'; }
             if ($color) { echo ' style="background-color: ' . $color . ';"'; }
             if ($htmlParams) { echo $htmlParams; }
        ?>><?= $text ?></span><?
    }

?></div><? // class="button-container"