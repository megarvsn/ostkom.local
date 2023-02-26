<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );
CModule::IncludeModule('iblock');
$rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => 0),false,array("ID","NAME"));
$arr_city=array();
while ($arSect = $rsSect->GetNext())
{
	$arr_city[substr($arSect['NAME'], 0, 1)][]=$arSect; 
}
?>
      <div class="container location__inner">
        <div class="location__title">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/location_title.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 		
		</div>
        <div class="location__list">
		<?foreach($arr_city as $key => $cites):?>
          <!-- item-->
          <div class="location__item">
            <div class="location__letter"><?=$key?></div>
            <ul class="location__geo">
			<?foreach($cites as $cite):?>
              <li class="location__geo-item"><a class="location__geo-link link" date="<?=$cite['ID']?>"><?=$cite['NAME']?></a></li>
			<?endforeach;?>  
            </ul>
          </div>
		<?endforeach;?>  
        </div>
        <!-- close--><a class="location__close close js-toggle" data-target="#location">
          <svg class="icon">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035468#icon-close"></use>
          </svg></a>
      </div>