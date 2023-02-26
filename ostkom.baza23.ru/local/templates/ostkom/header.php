<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/include/header.php');
$PageTitle="Y";
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <title><?$APPLICATION->ShowTitle();?></title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/styles/app.bundle.css">
    <script src="<?=SITE_TEMPLATE_PATH?>/scripts/app.bundle.js"></script>
    <script src="<?=SITE_TEMPLATE_PATH?>/__nav.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/scripts/jquery.scrollTo-min.js"></script>
	<?$APPLICATION->ShowHead();?>
  </head>
  <body class="is-disabled-transition<?if($USER->IsAdmin()):?> IsAdmin<?endif;?>">
    <div id="panel">
	    <?$APPLICATION->ShowPanel();?>
    </div>
	<?//if(!$_SESSION['CookieWarning']):?>
	<?if(!$_COOKIE['CookieWarning']):?>
	<div id="cookie_warning">
	   <div class="container">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/cookie_warning.php"),
			Array(),
			Array("MODE"=>"html")
		);?>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/cookie_submit.php"),
			Array(),
			Array("MODE"=>"html")
		);?>		
	   </div>
	</div>
	<?endif;?>	
    <!-- recall-->
    <div class="recall js-recall">
      <div class="recall__inner">
        <!-- title-->
        <div class="recall__title h2">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/callback_title.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 
		</div>
        <!-- desc-->
        <div class="recall__desc">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/callback_note.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		
		</div>	
<?$APPLICATION->IncludeComponent("bitrix:form.result.new", "callback_form", Array(
	    "AJAX_MODE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_SHADOW" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"CACHE_TYPE" => "N",	// Тип кеширования
		"CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
		"CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_URL" => "",	// Страница редактирования результата
		"IGNORE_CUSTOM_TEMPLATE" => "N",	// Игнорировать свой шаблон
		"LIST_URL" => "",	// Страница со списком результатов
		"SEF_MODE" => "N",	// Включить поддержку ЧПУ
		"SUCCESS_URL" => "",	// Страница с сообщением об успешной отправке
		"USE_EXTENDED_ERRORS" => "N",	// Использовать расширенный вывод сообщений об ошибках
		"WEB_FORM_ID" => "1",	// ID веб-формы
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?>	
        <a class="recall__close close js-recall-close">
          <svg class="icon">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035468#icon-close"></use>
          </svg></a>
      </div>
    </div>
    <!-- location-->
    <div class="location" id="location" style="display:<?if($_SESSION['Lcity']['NAME']!=""):?>none<?else:?>block<?endif;?>">
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/location.php"),
			Array(),
			Array("MODE"=>"php")
);?>	
    </div>
    <!-- top panel-->
    <div class="top-panel">
      <div class="container top-panel__inner">
        <!-- item-->
        <div class="top-panel__item">
          <!-- location--><a class="top-panel__location js-toggle" href="" data-target="#location">
            <svg class="icon">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035469#icon-location"></use>
            </svg><span class="top-panel__location-text link link--pseudo"><?if($_SESSION['Lcity']['NAME']!=""):?><?=$_SESSION['Lcity']['NAME']?><?else:?>
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/location_city.php"),
			Array(),
			Array("MODE"=>"php")
);?>			
			<?endif;?></span></a>
        </div>
        <!-- item-->
        <div class="top-panel__item">
          <!-- sections-->	
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/main_menu.php"),
			Array(),
			Array("MODE"=>"php")
);?>		  
        </div>
        <!-- item-->
        <div class="top-panel__item">
          <!-- phone--><div class="top-panel__phone">
            <svg class="icon">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035469#icon-phone"></use>
            </svg>
			<span class="top-panel__phone-text">
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/phone_top.php"),
			Array(),
			Array("MODE"=>"html")
);?>
            </span></div>
          <!-- account-->
          <div class="top-panel__account-wrap">
		    <a class="top-panel__account" href="/<?=$AddLang?>sostoyanie-vashego-scheta/" data---dropdown>
              <svg class="icon">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035469#icon-enter"></use>
              </svg><span class="top-panel__account-text">
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/account_title.php"),
			Array(),
			Array("MODE"=>"html")
);?>			  
			  </span>
			</a>
          <!--  <div class="top-panel__auth" data-dropdown-element>
              <div class="account-form">
                <div class="account-form__title h5">
<?/*$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/account_title.php"),
			Array(),
			Array("MODE"=>"html")
);*/?>
				</div>
				<?/*$APPLICATION->IncludeComponent(
				    "bitrix:system.auth.form", 
					"auth", 
					array(
						"REGISTER_URL" => SITE_DIR.$AddLang."auth/",
						"PROFILE_URL" => SITE_DIR.$AddLang."personal/",
						"SHOW_ERRORS" => "N",
						"FORGOT_PASSWORD_URL" => "",
						"USE_AUTH_Wishlist" => "Y",
						"COMPONENT_TEMPLATE" => "auth"
					),
					false
				);*/
				?>				
              </div>
            </div> -->			
          </div>
          <div class="top-panel__account-wrap">
		    <a class="top-panel__account mymail" href="https://mail.ostkom.lv/" target="_blank">
			  <span class="icon"></span> 
			  <span class="top-panel__account-text">
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/mail_title.php"),
			Array(),
			Array("MODE"=>"html")
);?>			  
			  </span>
			</a>			
          </div>		  
		  
		  
		  
		  
		  
          <ul class="top-panel__lang">
            <li class="top-panel__lang-item"><a id="lang_lv" class="top-panel__lang-link<?if(!$LangRu):?> is-current<?endif;?>"href="<?=$CurPage?>">LV</a></li>
            <li class="top-panel__lang-item"><a id="lang_ru" class="top-panel__lang-link<?if($LangRu):?> is-current<?endif;?>" href="/<?=$Lang_2?><?=$CurPage?>">RU</a></li>
          </ul>
        </div>
      </div>
    </div>
    <header class="header">
      <div class="container header__inner">
        <div class="header__burger"><a class="burger js-burger"><span class="burger__inner"></span></a></div>
        <div class="header__logo">
          <div class="logo"><a class="logo__link" href="<?if($LangRu):?>/<?=$Lang_2?>/<?else:?>/<?endif;?>"><img class="logo__image" src="<?=SITE_TEMPLATE_PATH?>/images/logo.svg" alt="Ostkom"></a></div>
        </div>
        <!-- main nav-->	
<?if($_SESSION['ServiceType']=="business"):?>	
	    <?$APPLICATION->IncludeComponent(
	       "bitrix:menu", 
	       "top_home", 
	       Array(
		    "ALLOW_MULTI_SELECT" => "N",
		    "CHILD_MENU_TYPE" => "",
		    "DELAY" => "N",
		    "MAX_LEVEL" => "2",
		    "MENU_CACHE_GET_VARS" => array(),
		    "MENU_CACHE_TIME" => "3600",
		    "MENU_CACHE_TYPE" => "N",
		    "MENU_CACHE_USE_GROUPS" => "Y",
		    "ROOT_MENU_TYPE" => "top_bus_".$LangId,
		    "USE_EXT" => "Y",
		    "COMPONENT_TEMPLATE" => "top_home"
	       ),
	       false
        );?>
<?else:?>
        <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_home", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "top_".$LangId,
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "top_home"
	),
	false
);?>
<?endif;?>		
        <!-- phone-->
		<div class="header__phone">
          <svg class="icon">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035469#icon-phone"></use>
          </svg>
		  <?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/phone_top.php"),
			Array(),
			Array("MODE"=>"html")
);?>
		  </div>
      </div>
    </header>
<?if($APPLICATION->GetCurPage()=="/" OR $APPLICATION->GetCurPage()=="/".$Lang_2."/"):?> 	
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"slider",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
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
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "-",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "50",
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
		"PROPERTY_CODE" => array(0=>"LINK",1=>"BUT_TEXT",2=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>	
<?endif;?>		
    <!-- main-->
    <main class="main">
      <!-- panel-->
      <div class="panel">
        <div class="panel__inner">
          <!-- sections-->
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/main_menu_mobil.php"),
			Array(),
			Array("MODE"=>"php")
);?>			  
          <!-- nav-->
<?if($_SESSION['ServiceType']=="business"):?>	
	    <?$APPLICATION->IncludeComponent(
	       "bitrix:menu", 
	       "top_home_panel", 
	       Array(
		    "ALLOW_MULTI_SELECT" => "N",
		    "CHILD_MENU_TYPE" => "",
		    "DELAY" => "N",
		    "MAX_LEVEL" => "1",
		    "MENU_CACHE_GET_VARS" => array(),
		    "MENU_CACHE_TIME" => "3600",
		    "MENU_CACHE_TYPE" => "N",
		    "MENU_CACHE_USE_GROUPS" => "Y",
		    "ROOT_MENU_TYPE" => "top_bus_".$LangId,
		    "USE_EXT" => "N",
		    "COMPONENT_TEMPLATE" => "top_home_panel"
	       ),
	       false
        );?>
<?else:?>
        <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_home_panel", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "top_".$LangId,
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "top_home_panel"
	),
	false
);?>
<?endif;?>			  
          <!-- account-->
		  <a class="panel__account" href="/<?=$AddLang?>sostoyanie-vashego-scheta/">
            <svg class="icon">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035470#icon-enter"></use>
            </svg><?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/account_title.php"),
			Array(),
			Array("MODE"=>"html")
);?>
		  </a>
		  <a class="panel__account mymail" href="https://mail.ostkom.lv/" target="_blank">
			 <span class="icon"></span> 
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/mail_title.php"),
			Array(),
			Array("MODE"=>"html")
);?>			  
		  </a>		  
<!--		  
         <div class="account-form">
				<?/*$APPLICATION->IncludeComponent(
				    "bitrix:system.auth.form", 
					"auth", 
					array(
						"REGISTER_URL" => SITE_DIR.$AddLang."auth/",
						"PROFILE_URL" => SITE_DIR.$AddLang."personal/",
						"SHOW_ERRORS" => "N",
						"FORGOT_PASSWORD_URL" => "",
						"USE_AUTH_Wishlist" => "Y",
						"COMPONENT_TEMPLATE" => "auth"
					),
					false
				);*/
				?>				
          </div>
	-->  
        </div>
      </div>	
      <div class="container">
        <!-- content-->
        <div class="content">		
<?if($APPLICATION->GetCurPage()!="/" AND $APPLICATION->GetCurPage()!="/".$Lang_2."/"):?> 
<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"ostkom", 
	array(
		"START_FROM" => "1",
		"PATH" => "",
		"SITE_ID" => "s1",
		"COMPONENT_TEMPLATE" => "ostkom"
	),
	false,
	array(
		"HIDE_ICONS" => "N",
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?//if($APPLICATION->GetCurPage()!="/home/" AND $APPLICATION->GetCurPage()!="/business/" AND $APPLICATION->GetCurPage()!="/ru/home/" AND $APPLICATION->GetCurPage()!="/ru/business/"):?>  
<?if(strpos($APPLICATION->GetCurPage(),"/home/")===false AND strpos($APPLICATION->GetCurPage(),"/business/")===false):?>  
           <h1 class="page-title h1"><?$APPLICATION->ShowTitle(true);?></h1>
<?endif;?>		   
<?endif;?>	   