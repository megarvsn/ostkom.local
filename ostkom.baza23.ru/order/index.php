<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Pieteikuma noformesana");
$verification=true;
if($_POST['city'] AND $_POST['home']!=""){	
	$res = CIBlockElement::GetByID($_POST["home"]);
	if($ar_res = $res->GetNext()){
		$home_id= $_POST["home"];	
		//$_POST["home"]=$ar_res["NAME"];
	}
   $id_tariff=($_POST['tariff_id'])? $_POST['tariff_id'] : $_POST['tariff']; 
   $home_id=$_POST["home"];
   require($_SERVER["DOCUMENT_ROOT"]."/verification/verification.php");
   $verification=verification($home_id,$id_tariff);	
}
?>
<?if($verification==false):?> 
	<div id="NO_SERVICE">Diemžēl šajā adresē šis pakalpojums nav pieejams.</div>	
	<div class="verification_but"><a href="/verification/">Pārbaudīt pakalpojuma pieejamību</a></div>
<?elseif($verification==true):?> 
<?
if($_POST['name_package']!="")
	$n_form=36;
else
	$n_form=41;
?>
<?$APPLICATION->IncludeComponent(
	"ost:main.feedback", 
	"order", 
	array(
		"COMPONENT_TEMPLATE" => "order",
		"EMAIL_TO" => "",
		"EVENT_MESSAGE_ID" => array(
			0 => "36",
		),
		"OK_TEXT" => "Paldies, jusu zinojums ir pienemts.",
		"REQUIRED_FIELDS" => array(
			0 => "NAME",
			1 => "EMAIL",
		),
		"USE_CAPTCHA" => "Y"
	),
	false
);?> 
<script src="/bitrix/templates/ostkom/scripts/order.js"></script> <?else:?> <!-- order fail-->
<div class="order-fail module">
	 <!-- title-->
	<div class="order-fail__title h3 u-highlight">
		 <?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/order_fail_title.php"),
			Array(),
			Array("MODE"=>"html")
		);?>
	</div>
	 <!-- desc-->
	<div class="order-fail__desc u-size-h5">
		 <?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/order_fail_text.php"),
			Array(),
			Array("MODE"=>"html")
		);?>
	</div>
	 <!-- actions-->
	<ul class="order-fail__actions ug-grid ug-block-wide4">
		<li class="order-fail__actions-item ug-col"><a class="order-fail__actions-link btn btn--secondary" href="/">Izvelieties citu pakalpojumu</a></li>
		<li class="order-fail__actions-item ug-col"><a class="order-fail__actions-link btn btn--secondary" href="/verification/">Parbaudiet pakalpojuma pieejamibu vietne</a></li>
		<li class="order-fail__actions-item ug-col"><a class="order-fail__actions-link btn btn--secondary" href="/support/">Nosutit pieteikumu par tehnisku iespeju</a></li>
	</ul>
</div>
<?endif;?>	
<?
if($_SESSION['Lcity']['ID']>0){
   global $arrFilter_A;
   $arrFilter_A=Array("PROPERTY_CITY" => $_SESSION['Lcity']['ID']);
}
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"actions", 
	array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "actions",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "arrFilter_A",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "actions",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "2",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "NAME_LV",
			2 => "ANONS_LV",
			3 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>