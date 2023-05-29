</main>
<footer>
    <?
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--check_services",
        array(),
        false
    );
    $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "footer--menu_services",
        array(
            "ROOT_MENU_TYPE" => "footer--menu_services",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "",
            "MENU_CACHE_USE_GROUPS" => "N",
            "MENU_CACHE_GET_VARS" => array(""),
            "CACHE_SELECTED_ITEMS" => "N",
            "MAX_LEVEL" => 3,
            "CHILD_MENU_TYPE" => "",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N"
        ),
        false
    );
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--mobile_apps",
        array(),
        false
    );
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--follow_us",
        array(),
        false
    );
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--call",
        array(),
        false
    );
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--copyright",
        array(),
        false
    );
    $APPLICATION->IncludeComponent(
        "baza23:local.empty",
        "footer--legal_information",
        array(),
        false
    );
    ?>
</footer>
</body>

</html>