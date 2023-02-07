<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();
global $LangId;
?>
        </div>
      </div>
      <div class="panel-overlay js-panel-overlay"></div>
    </main>
    <!-- help line-->
    <div class="help-line section">
      <div class="container">
        <!-- title-->
        <div class="help-line__title headline h2">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_title.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		
		</div>
        <!-- list-->
        <ul class="help-line__list">
          <!-- item-->
          <li class="help-line__item"><a class="help-line__item-link" href="" data-target="#office-contacts">
              <div class="help-line__item-icon">
                <svg class="icon">
                  <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035472#icon-office"></use>
                </svg>
              </div>
              <div class="help-line__item-title h5">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_title1.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 			  
			  </div></a></li>
          <!-- item-->
          <li class="help-line__item"><a class="help-line__item-link js-recall-link" href="">
              <div class="help-line__item-icon">
                <svg class="icon">
                  <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035472#icon-phone"></use>
                </svg>
              </div>
              <div class="help-line__item-title h5">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_title2.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 			  
			  </div></a></li>
          <!-- item-->
          <li class="help-line__item">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/support.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </li>
        </ul>
		<div id="help_block_scroll"></div>
      </div>
    </div>
    <!-- office contacts-->
    <div class="office-contacts" id="office-contacts">
      <!-- items-->
      <div class="office-contacts__items container section">
        <!-- item-->
        <div class="office-contacts__item">
          <div class="office-contacts__item-title">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_1.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </div>
          <div class="office-contacts__item-desc">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_2.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </div>
        </div>
        <!-- item-->
        <div class="office-contacts__item">
          <div class="office-contacts__item-title">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_3.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </div>
          <div class="office-contacts__item-desc">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_4.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </div>
        </div>
        <!-- item-->
        <div class="office-contacts__item">
          <div class="office-contacts__item-title">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_5.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </div>
          <div class="office-contacts__item-desc">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/office_6.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 			  
		  </div>
        </div>
        <!-- close--><a class="office-contacts__close close" data-target="#office-contacts">
          <svg class="icon">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035472#icon-close"></use>
          </svg></a>
      </div>
      <!-- map-->
      <div class="office-contacts__map">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/footer_map.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 	  
	  </div>
    </div>
    <!-- contacts line-->
    <div class="contacts-line">
      <div class="container contacts-line__inner">
        <!-- socials-->
        <ul class="contacts-line__socials">
          <li class="contacts-line__socials-item">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/socials-link-facebook.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		 </li>
          <li class="contacts-line__socials-item">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/socials-link-gplus.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </li>
          <li class="contacts-line__socials-item">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/socials-link-instagram.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		  
		  </li>
        </ul>
        <!-- phone-->
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/phone.php"),
			Array(),
			Array("MODE"=>"html")
);?>
      </div>
    </div>
    <!-- footer-->
    <footer class="footer">
      <div class="container footer__inner">
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"bot_menu", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "bot_".$LangId,
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "bot_".$LangId,
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "bot_menu"
	),
	false
);?>
        <div class="footer__copyright">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/copyright.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 
		</div>
      </div>
    </footer>
    <script src="<?=SITE_TEMPLATE_PATH?>/scripts/popup.js"></script>	
<?
//print_r($_SESSION);
if(!$_SESSION['Lcity']['ID']){
   $_SESSION['Lcity']['NAME']="Liepaja";
   $_SESSION['Lcity']['ID']=65; 
}
?>
  </body>
</html>