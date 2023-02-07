<? 
  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION; 
$aMenuLinksExt = Array(
	Array(
		"Интернет", 
		"/ru/business/business_internet/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Телевидение", 
		"/ru/business/business_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Телефония", 
		"/ru/business/business_telephony/", 
		Array(), 
		Array(), 
		"" 
	)
); 
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
?>