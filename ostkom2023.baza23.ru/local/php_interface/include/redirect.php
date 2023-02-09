<? if (!CModule::IncludeModule("iblock")) return;

$redirect = array();

$requestUri = $_SERVER['REQUEST_URI'];
if ($requestUri != '/') {
    $dbElement = CIBlockElement::GetList(
        Array(),
        Array(
            "IBLOCK_CODE" => LANGUAGE_ID . "-redirect",
            "ACTIVE" => "Y",
            "PROPERTY_UP_URL_SOURCE" => $requestUri
        ),
        false,
        false,
        Array("NAME", "PROPERTY_UP_URL_SOURCE")
    );
    while ($arElement = $dbElement->fetch()) {
        $arElement["NAME"] = trim($arElement["NAME"]);
        $redirect[$arElement["PROPERTY_UP_URL_SOURCE_VALUE"]] = $arElement["NAME"];
    }
}

if (isset($redirect[$requestUri])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $redirect[$requestUri]);
    exit();
}