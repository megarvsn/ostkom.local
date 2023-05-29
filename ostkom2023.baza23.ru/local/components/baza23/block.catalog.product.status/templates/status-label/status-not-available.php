<?
use Bitrix\Main\Localization\Loc;

?><span class="item-not-available"><?

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

?></span><? // class="item-not-available"