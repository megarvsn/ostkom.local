<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
global $LangRu;
?>
          <div class="categories module">
             <div class="categories__list ug-grid ug-block-mobile6">
<?
if (0 < $arResult["SECTIONS_COUNT"])
{
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
//echo"<pre>";	print_r($arSection);	echo"</pre>";	
if(!$LangRu){
	$arSection['NAME']=$arSection['UF_NAME_LV']; 
	$arSection['UF_ADD_TEXT']=$arSection['UF_ANONS_TITLE_LV'];
	$arSection['UF_ANONS']=$arSection['UF_ANONS_TEXT_LV'];
}		
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
				?>
              <div class="ug-col" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
                <!-- item-->
				<a class="categories__item" href="<?=$arSection['SECTION_PAGE_URL']?>">
                  <!-- image-->
                  <div class="categories__item-image"><img src="<?=CFile::GetPath($arSection['UF_IMAGE'])?>" alt=""></div>
                  <!-- body-->
                  <div class="categories__item-body">
                    <!-- title-->
                    <div class="categories__item-title h3"><?=$arSection['NAME']?></div>
                    <!-- subtitle-->
                    <div class="categories__item-subtitle h5 u-highlight"><?=$arSection['UF_ADD_TEXT']?></div>
                    <!-- description-->
                    <div class="categories__item-desc">
                      <p>
					  <?=$arSection['UF_ANONS']?>
					  </p>
                    </div>
                  </div>
				</a>
              </div>				
<?
			}
}
?>
             </div>
          </div>