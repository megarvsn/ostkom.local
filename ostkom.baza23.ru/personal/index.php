<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrix/main.profile/lang/".$LangId."/component.php");
$APPLICATION->SetTitle("Informācijas panelis");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.profile", 
	"personal", 
	array(
		"CHECK_RIGHTS" => "N",
		"SEND_INFO" => "N",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(
		),
		"USER_PROPERTY_NAME" => "",
		"COMPONENT_TEMPLATE" => "personal"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>