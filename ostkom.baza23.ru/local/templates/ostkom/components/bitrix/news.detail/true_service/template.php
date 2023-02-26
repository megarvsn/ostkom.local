<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $LangRu;

if(!$LangRu){
	$arResult['PROPERTIES']['TITLE']= $arResult['PROPERTIES']['TITLE_LV'];
	$arResult['~PREVIEW_TEXT']= $arResult['~DETAIL_TEXT'];
    $arResult['PROPERTIES']['ELEMENT']=$arResult['PROPERTIES']['ELEMENT_LV'];
    $arResult['PROPERTIES']['SVG'] = $arResult['PROPERTIES']['SVG_LV'];	
}
?>
<?//echo"<pre>";   print_r($arResult);   echo"</pre>";?>
          <div class="true-service module">
		  <div class="before" style="background-image: url(<?=$arResult['PREVIEW_PICTURE']['SRC']?>)"></div>
            <!-- title-->
            <div class="true-service__title h2">
               <?=$arResult['PROPERTIES']['TITLE']['~VALUE']?> 			
			</div>
            <!-- subtitle-->
            <div class="true-service__subtitle h3">
               <?=$arResult['~PREVIEW_TEXT']?> 					
			</div>
            <!-- features-->
            <ul class="true-service__features features ug-grid ug-block-mobile6 ug-block-desktop3">
              <!-- item-->	  
	<?foreach($arResult['PROPERTIES']['ELEMENT']['VALUE'] as $k => $el):?>		  
              <li class="features__item ug-col">
                <div class="features__item-icon">
				    <div class="icon">
                    <img src="<?=CFile::GetPath($el)?>" alt=""/>	
                    </div>					
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
                       <?=$arResult['PROPERTIES']['ELEMENT']['DESCRIPTION'][$k]?>					
					</p>
                  </div>
                </div>
              </li>
	<?endforeach;?>	
	<?foreach($arResult['PROPERTIES']['SVG']['~VALUE'] as $k => $el):?>	
              <li class="features__item ug-col">
                <div class="features__item-icon">
                   <?=$el?>			
                </div>
                <div class="features__item-body">
                  <div class="features__item-desc">
                    <p>
                       <?=$arResult['PROPERTIES']['SVG']['~DESCRIPTION'][$k]?>							
					</p>
                  </div>
                </div>
              </li>
	<?endforeach;?>		
            </ul>
          </div>