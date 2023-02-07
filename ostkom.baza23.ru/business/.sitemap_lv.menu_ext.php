<? 
  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION; 
$aMenuLinksExt = Array(
	Array(
		"Internets", 
		"/business/business_internet/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Televīzija", 
		"/business/business_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Telefonija", 
		"/business/business_telephony/", 
		Array(), 
		Array(), 
		"" 
	)
); 
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
?>