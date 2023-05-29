<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult)) {
    $arOldResult = $arResult;
    $arResult = [];

    $skipLevel = 0;
    foreach ($arOldResult as $key => $arItem) {
        $depthLevel = IntVal($arItem["DEPTH_LEVEL"]);

        if ($skipLevel > 0 && $skipLevel < $depthLevel) {
            continue;
        }

        $skipLevel = 0;

        if ($depthLevel >= 3
                && $arItem["PARAMS"]["PROPERTIES"]["UF_SHOW_IN_HEADER_MENU_MAIN"] == "N") {
            $skipLevel = $depthLevel;
            continue;
        }

        if ($arItem["PARAMS"]["PROPERTIES"]["UF_SHOW_IN_HEADER_MENU_MAIN"] == "N") {
            $skipLevel = $depthLevel;
            continue;
        }

        $arResult[] = $arItem;
    }

    $previousLevel = 0;
    for ($i = count($arResult) - 1; $i >= 0; $i --) {
        $currentLevel = $arResult[$i]["DEPTH_LEVEL"];
        $arResult[$i]["IS_PARENT"] = ($currentLevel < $previousLevel);
        $previousLevel = $currentLevel;
    }

    if (!empty($arResult) && $arParams["MAX_LEVEL"] > 1) {
        $previousLevel = 0;
        $arLastParents = array();

        foreach ($arResult as $key => $arItem) {
            if ($arItem["DEPTH_LEVEL"] > $arParams["MAX_LEVEL"]) continue;

            if ($arItem["DEPTH_LEVEL"] < $previousLevel) {
                for ($depth = $arItem["DEPTH_LEVEL"]; $depth < $previousLevel; $depth ++) {
                    unset($arLastParents[$depth]);
                }
            }

            if ($arItem["SELECTED"]) {
                for ($depth = 1; $depth < $arItem["DEPTH_LEVEL"]; $depth ++) {
                    if (!$arLastParents[$depth]["PARAMS"]["CHILD_SELECTED"]) {
                        $arResult[$arLastParents[$depth]]["PARAMS"]["CHILD_SELECTED"] = $arItem["DEPTH_LEVEL"];
                    }
                }
            }

            $isParent = ($arItem["IS_PARENT"] && $arParams["MAX_LEVEL"] != $arItem["DEPTH_LEVEL"]);
            if ($isParent) $arLastParents[$arItem["DEPTH_LEVEL"]] = $key;

            $previousLevel = $arItem["DEPTH_LEVEL"];
        }
    }
}