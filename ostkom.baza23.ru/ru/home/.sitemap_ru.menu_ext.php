<? 
  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION;  
$aMenuLinksExt = Array(
	Array(
		"Интернет+ТВ", 
		"/ru/home/home_internet_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Интернет", 
		"/ru/home/home_internet/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Телевидение", 
		"/ru/home/home_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Телефония", 
		"/ru/home/home_telephony/", 
		Array(), 
		Array(), 
		"" 
	)
);
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
?>
  