<? 
  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
  global $APPLICATION;  
$aMenuLinksExt = Array(
	Array(
		"Internets+tv", 
		"/home/home_internet_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Internets", 
		"/home/home_internet/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Televīzija", 
		"/home/home_tv/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Telefonija", 
		"/home/home_telephony/", 
		Array(), 
		Array(), 
		"" 
	)
);
  $aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks); 
?>