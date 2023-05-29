<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PC_BLOCK_UserInfo extends CBitrixComponent {
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
        $p_arParams["SHOW_NAME"] = (trim($p_arParams["SHOW_NAME"]) == "Y" ? "Y" : "N");
        $p_arParams["NAME_TEMPLATE_SHOW_LOGIN"] = (trim($p_arParams["NAME_TEMPLATE_SHOW_LOGIN"]) == "Y" ? "Y" : "N");

        $p_arParams["SHOW_LOGOUT"] = (trim($p_arParams["SHOW_LOGOUT"]) == "Y" ? "Y" : "N");
        $p_arParams["LOGOUT_URL"] = trim($p_arParams["LOGOUT_URL"]);
        if (! $p_arParams["LOGOUT_URL"]) $p_arParams["LOGOUT_URL"] = '?logout=yes';

        $p_arParams["SHOW_LOGIN"] = (trim($p_arParams["SHOW_LOGIN"]) == "Y" ? "Y" : "N");
        $p_arParams["SHOW_LOGIN_IN_MODAL"] = (trim($p_arParams["SHOW_LOGIN_IN_MODAL"]) == "Y" ? "Y" : "N");
        $p_arParams["LOGIN_MODAL_ID"] = trim($p_arParams["LOGIN_MODAL_ID"]);
        $p_arParams["LOGIN_URL"] = trim($p_arParams["LOGIN_URL"]);
        if (! $p_arParams["LOGIN_URL"]) $p_arParams["LOGIN_URL"] = SITE_DIR . '?login=yes';

        $p_arParams["SHOW_REGISTER"] = (trim($p_arParams["SHOW_REGISTER"]) == "Y" ? "Y" : "N");
        $p_arParams["SHOW_REGISTER_IN_MODAL"] = (trim($p_arParams["SHOW_REGISTER_IN_MODAL"]) == "Y" ? "Y" : "N");
        $p_arParams["REGISTER_MODAL_ID"] = trim($p_arParams["REGISTER_MODAL_ID"]);
        $p_arParams["REGISTER_URL"] = trim($p_arParams["REGISTER_URL"]);
        if (! $p_arParams["REGISTER_URL"]) $p_arParams["REGISTER_URL"] = SITE_DIR . '?register=yes';

        $p_arParams["SHOW_PROFILE"] = (trim($p_arParams["SHOW_PROFILE"]) == "Y" ? "Y" : "N");
        $p_arParams["PROFILE_URL"] = trim($p_arParams["PROFILE_URL"]);
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

        global $USER;

        if ($this->arParams["SHOW_NAME"] == "Y" && $USER -> IsAuthorized()) {
            $arResult["USER"] = array();
            $arResult["USER"]["ID"] = intval($USER->GetID());

            $bUseLogin = $this->arParams['NAME_TEMPLATE_SHOW_LOGIN'] == "Y" ? true : false;

            $dbUsers = CUser::GetList(
                ($by = "ID"),
                ($order = "desc"),
                Array("ID" => $arResult["USER"]["ID"]),
                Array("NAME", "LAST_NAME", "SECOND_NAME", "LOGIN")
            );
            if ($arUser = $dbUsers -> Fetch()) {
                $arResult["USER"]["NAME"] = $arUser["NAME"];
                $arResult["USER"]["LAST_NAME"] = $arUser["LAST_NAME"];
                $arResult["USER"]["SECOND_NAME"] = $arUser["SECOND_NAME"];
                $arResult["USER"]["LOGIN"] = $arUser["LOGIN"];

                $arResult["USER"]["NAME_FORMATTED"] = CUser::FormatName($this->arParams['NAME_TEMPLATE'], $arResult["USER"], $bUseLogin);
                $pos = strpos($arResult["USER"]["NAME_FORMATTED"], '@');
                if ($pos > 0) $arResult["USER"]["NAME_FORMATTED"] = substr($arResult["USER"]["NAME_FORMATTED"], 0, $pos);
            }

            $this->arResult = $arResult;
        }

        $this -> IncludeComponentTemplate();
        return $this;
    }
}