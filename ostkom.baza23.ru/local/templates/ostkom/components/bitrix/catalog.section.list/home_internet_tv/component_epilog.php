<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->AddChainItem($arResult['SECTION']['NAME']);
if($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_TITLE']!="")
     $APPLICATION->SetPageProperty("title", $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_TITLE']);
else 
     $APPLICATION->SetPageProperty("title", $arResult['SECTION']['NAME']);	
 
if($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_KEYWORDS']!="") 
     $APPLICATION->SetPageProperty("keywords", $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_KEYWORDS']);	
else 
     $APPLICATION->SetPageProperty("keywords", $arResult['SECTION']['NAME']);	
if($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']!="")  
     $APPLICATION->SetPageProperty("description", $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']);
else 
     $APPLICATION->SetPageProperty("description", $arResult['SECTION']['NAME']);
 
unset($_SESSION['address']); 
?>