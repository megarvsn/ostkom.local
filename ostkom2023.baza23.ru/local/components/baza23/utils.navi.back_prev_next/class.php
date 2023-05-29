<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PC_UTIL_NaviBackPrevNext extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (!Loader::includeModule('iblock')) {
            $this->arResult["ERROR"] = array('TYPE' => 'MODULE_IBLOCK_IS_NOT_LOADED');
            return false;
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $p_arParams
     * @return mixed
     */
    public function onPrepareComponentParams($p_arParams) {
        $p_arParams["IBLOCK_TYPE"] = trim($p_arParams["IBLOCK_TYPE"]);
        if (strlen($p_arParams["IBLOCK_TYPE"]) <= 0) $p_arParams["IBLOCK_TYPE"] = "news";
        $p_arParams["IBLOCK_ID"] = trim($p_arParams["IBLOCK_ID"]);

        $p_arParams["NEWS_COUNT"] = intval($p_arParams["NEWS_COUNT"]);
        if ($p_arParams["NEWS_COUNT"] <= 0) $p_arParams["NEWS_COUNT"] = 20;

        $p_arParams["SORT_BY1"] = trim($p_arParams["SORT_BY1"]);
        if (strlen($p_arParams["SORT_BY1"]) <= 0) $p_arParams["SORT_BY1"] = "ACTIVE_FROM";
        if ( ! preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $p_arParams["SORT_ORDER1"])) $p_arParams["SORT_ORDER1"] = "DESC";

        if (strlen($p_arParams["SORT_BY2"]) <= 0) $p_arParams["SORT_BY2"] = "SORT";
        if ( ! preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $p_arParams["SORT_ORDER2"])) $p_arParams["SORT_ORDER2"] = "ASC";

        $p_arParams["SECTION_ID"] = intval($p_arParams["SECTION_ID"]);
        $p_arParams["SECTION_CODE"] = trim($p_arParams["SECTION_CODE"]);
        $p_arParams["ELEMENT_ID"] = intval($p_arParams["ELEMENT_ID"]);
        $p_arParams["ELEMENT_CODE"] = trim($p_arParams["ELEMENT_CODE"]);

        $p_arParams["URL_PAGEN_NUMBER"] = IntVal($p_arParams["URL_PAGEN_NUMBER"]);
        if ($p_arParams["URL_PAGEN_NUMBER"] < 1) $p_arParams["URL_PAGEN_NUMBER"] = 1;

        $p_arParams["DETAIL_URL"] = trim($p_arParams["DETAIL_URL"]);

        if ($p_arParams["ADDITIONAL_PAGE_URLS"] && !is_array($p_arParams["ADDITIONAL_PAGE_URLS"])) {
            $p_arParams["ADDITIONAL_PAGE_URLS"] = explode(",", trim($p_arParams["ADDITIONAL_PAGE_URLS"]));
        }
        return $p_arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {
        if (!$this->_checkModules()) return $this->arResult;

        $this->arParams = $this->onPrepareComponentParams($this->arParams);

        $this->arResult = $this->pf_getPaths($this->arParams);
        // в $this->arResult["TORIGHT"] и $this->arResult["TOLEFT"] лежат
        // массивы с информацией о соседних элементах
        // в $this->arResult["BACK"] массив с информацией о текущем элементе
        return $this->arResult;
    }

    protected function pf_createSort($p_arParams) {
        // сортировку берем из параметров компонента
        $arSort = array(
            $p_arParams["SORT_BY1"] => $p_arParams["SORT_ORDER1"],
            $p_arParams["SORT_BY2"] => $p_arParams["SORT_ORDER2"],
        );
        return $arSort;
    }

    protected function pf_createSelect() {
        $arSelect = array("ID", "NAME", "DETAIL_PAGE_URL", "LIST_PAGE_URL", "RANK");
        return $arSelect;
    }

    protected function pf_createFilter($p_arParams) {
        if (strlen($p_arParams["FILTER_NAME"]) <= 0 || ! preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $p_arParams["FILTER_NAME"])) {
            $arrFilter = array();
        } else {
            $arrFilter = $GLOBALS[$p_arParams["FILTER_NAME"]];
            if ( ! is_array($arrFilter)) $arrFilter = array();
        }

        // выбираем активные элементы из нужного инфоблока. Раскомментировав строку можно ограничить секцией
        $arFilter = array(
            "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
            "ACTIVE" => "Y",
            "CHECK_PERMISSIONS" => "Y",
        );

        if ($p_arParams["SECTION_ID"] > 0) {
            $arFilter["SECTION_ID"] = $p_arParams["SECTION_ID"];
        } else if (strlen($p_arParams["SECTION_CODE"]) > 0) {
            $arFilter["SECTION_CODE"] = $p_arParams["SECTION_CODE"];
        }

        if (!empty($arrFilter)) {
            $arFilter = array_merge($arFilter, $arrFilter);
        }
        return $arFilter;
    }

    protected function pf_loadItems($p_arParams) {
        $arSort = $this -> pf_createSort($p_arParams);
        $arFilter = $this -> pf_createFilter($p_arParams);
        $arSelect = $this -> pf_createSelect();

        // выбирать будем по 1 соседу с каждой стороны от текущего
        $arNavParams = array(
            "nPageSize" => 1,
            "nElementID" => $p_arParams["ELEMENT_ID"],
        );

        $arRet = Array();
        $rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
        $rsElement -> SetUrlTemplates($p_arParams["DETAIL_URL"]);
        while ($arElement = $rsElement -> GetNext()) {
            $arRet[] = $arElement;
        }
        return $arRet;
    }

    protected function pf_setElementId(&$p_arParams) {
        if ($p_arParams["ELEMENT_ID"] > 0) return $p_arParams["ELEMENT_ID"];
        elseif (! $p_arParams["ELEMENT_CODE"]) return false;

        $ret = false;
        $dbElement = CIBlockElement::GetList(
            array("SORT" => "ASC"),
            array(
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "CHECK_PERMISSIONS" => "Y",
                "CODE" => $p_arParams["ELEMENT_CODE"]
            ),
            false,
            false,
            array("ID")
        );
        if ($arElement = $dbElement -> Fetch()) {
            $ret = $p_arParams["ELEMENT_ID"] = $arElement["ID"];
        }
        return $ret;
    }

    function pf_getPaths($p_arParams) {
        if ( ! $p_arParams) return array();

        $arRet = array();
        if (isset($_SERVER['HTTP_REFERER'])) {
            if (isset($p_arParams["LIST_PAGE_URL"])) {
                $url = strtolower($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $p_arParams["LIST_PAGE_URL"]);
                $ref = strtolower($_SERVER['HTTP_REFERER']);
                if (strpos($ref, $url) === 0 && (strlen($ref) == strlen($url) || $ref[strlen($url)] == '?')) {
                    $arRet["BACK"] = Array("URL" => $_SERVER['HTTP_REFERER']);
                }
            }
            if (!$arRet["BACK"] && is_array($p_arParams["ADDITIONAL_PAGE_URLS"])) {
                foreach ($p_arParams["ADDITIONAL_PAGE_URLS"] as $pUrl) {
                    $url = strtolower($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . trim($pUrl));
                    $ref = strtolower($_SERVER['HTTP_REFERER']);
                    if (strpos($ref, $url) === 0 && (strlen($ref) == strlen($url) || $ref[strlen($url)] == '?')) {
                        $arRet["BACK"] = Array("URL" => $_SERVER['HTTP_REFERER']);
                        break;
                    }
                }
            }
        }

        if (! $this -> pf_setElementId($p_arParams)) {
            return array();
        }

        if ($arParams["DISPLAY_NEXT_PREV"] == "Y" || !isset($arRet["BACK"])) {
            $arItems = $this -> pf_loadItems($p_arParams);

            $current = false;
            // возвращается от 1го до 3х элементов в зависимости от наличия соседей, обрабатываем эту ситуацию
            if (count($arItems) == 3):
                $arRet["TOLEFT"] = Array("NAME" => $arItems[0]["NAME"], "URL" => $arItems[0]["DETAIL_PAGE_URL"]);
                $current = $arItems[1];
                $arRet["TORIGHT"] = Array("NAME" => $arItems[2]["NAME"], "URL" => $arItems[2]["DETAIL_PAGE_URL"]);

            elseif (count($arItems) == 2):
                if ($arItems[0]["ID"] != $p_arParams["ELEMENT_ID"]) {
                    $arRet["TOLEFT"] = Array("NAME" => $arItems[0]["NAME"], "URL" => $arItems[0]["DETAIL_PAGE_URL"]);
                    $current = $arItems[1];
                } else {
                    $arRet["TORIGHT"] = Array("NAME" => $arItems[1]["NAME"], "URL" => $arItems[1]["DETAIL_PAGE_URL"]);
                    $current = $arItems[0];
                }
            endif;

            if (!isset($arRet["BACK"])) {
                $listPageUrl = ($p_arParams["LIST_PAGE_URL"] ? $p_arParams["LIST_PAGE_URL"] : $current["LIST_PAGE_URL"]);

                if (IntVal($p_arParams["NEWS_COUNT"]) <= 0) {
                    $arRet["BACK"] = Array("NAME" => $current["NAME"], "URL" => $listPageUrl);
                } else {
                    $page = floor((IntVal($current["RANK"]) - 1) / IntVal($p_arParams["NEWS_COUNT"]));
                    if ($page > 0) {
                        $arRet["BACK"] = Array("NAME" => $current["NAME"], "URL" => $listPageUrl . '?PAGEN_' . $p_arParams["URL_PAGEN_NUMBER"] . '=' . ($page + 1));
                    } else {
                        $arRet["BACK"] = Array("NAME" => $current["NAME"], "URL" => $listPageUrl);
                    }
                }
            }
        }
        return $arRet;
    }
}