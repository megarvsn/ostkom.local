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
    ?><span class="item-available"><?
        $icon1 = $arStatus1["icon"];
        if (!$text1) $text1 = Loc::getMessage('STATUS_' . $code1 . '_ICON');

        $text1 = $arStatus1["text"];
        if (!$text1) $text1 = Loc::getMessage('STATUS_' . $code1 . '_TEXT');

        $color1 = $arStatus1["color"];

        ?><span class="item-icon item-checked status-<?= $code1 ?>"<?
                if ($color1) { echo ' style="color: ' . $color1 . ';"'; }
        ?>><?
            if ($icon1) {
                echo $icon1;
            } else {
                ?><span class="icon--svg icon--svg-<?= $code1 ?>"<?
                        if ($color1) { echo ' style="background-color: ' . $color1 . ';"'; }
                ?>></span><?
                /*
                ?><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><?
                    ?><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/><?
                ?></svg><?
                */
            }
        ?></span><?
        ?><span class="item-text item-checked status-<?= $code1 ?>"<?
                if ($color1) { echo ' style="color: ' . $color1 . ';"'; }
        ?>><?= $text1 ?></span><?

} else {
    ?><span class="item-available"><?
}

    $code = $arResult["CURRENT_STATUS_CODE"];

    $icon = $arStatus["icon"];
    if (!$text) $text = Loc::getMessage('STATUS_' . $code . '_ICON');

    $text = $arStatus["text"];
    if (!$text) $text = Loc::getMessage('STATUS_' . $code . '_TEXT');

    $color = $arStatus1["color"];

    ?><span class="item-icon status-<?= $code ?>"<?
            if ($color) { echo ' style="color: ' . $color . ';"'; }
    ?>><?
        if ($icon) {
            echo $icon;
        } else {
            ?><span class="icon--svg icon--svg-<?= $code ?>"<?
                    if ($color) { echo ' style="background-color: ' . $color . ';"'; }
            ?>></span><?
            /*
            ?><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><?
                ?><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/><?
            ?></svg><?
            */
        }
    ?></span><?
    ?><span class="item-text status-<?= $code ?>"<?
            if ($color) { echo ' style="color: ' . $color . ';"'; }
    ?>><?= $text ?></span><?

?></span><? // class="item-available"