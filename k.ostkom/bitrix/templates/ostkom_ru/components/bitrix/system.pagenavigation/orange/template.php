<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<!-- pagination-->
<div class="pagination">
   <ul class="pagination__list">
<?if($arResult["bDescPageNumbering"] === true):?>
	<?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
	    <li class="pagination__item">
		<?if($arResult["bSavePage"]):?>
			<a class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
                    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
			</a>
		<?else:?>
			<?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
				<a  class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">
				    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
				</a>
			<?else:?>
				<a  class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
                    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
				</a>	
			<?endif?>
		<?endif?>
		</li>
	<?endif?>
	<?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
	    <li class="pagination__item"><a class="pagination__link" href="">
		<?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>
		<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
			<a class="pagination__link is-current" href="<?=$arResult["sUrlPath"]?>"><?=$NavRecordGroupPrint?></a>
		<?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
			<a class="pagination__link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
		<?else:?>
			<a class="pagination__link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
		<?endif?>
		<?$arResult["nStartPage"]--?>
		</li>
	<?endwhile?>
	<?if ($arResult["NavPageNomer"] > 1):?>
		<li class="pagination__item">
		   <a class="pagination__link pagination__link--next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">
				<svg class="icon">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                </svg> 
		   </a>
		</li>
	<?endif?>
<?else:?>
	<?if ($arResult["NavPageNomer"] > 1):?>
        <li class="pagination__item">
		<?if($arResult["bSavePage"]):?>
			<a class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">
                    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
			</a>
		<?else:?>
			<?if ($arResult["NavPageNomer"] > 2):?>
				<a class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">
                    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
				</a>
			<?else:?>
				<a class="pagination__link pagination__link--prev" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">
				    <svg class="icon">
                      <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-left"></use>
                    </svg> 
				</a>
			<?endif?>
		<?endif?>
		</li>
	<?endif?>
	<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
        <li class="pagination__item">
		<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
			<a class="pagination__link is-current" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
		<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
			<a class="pagination__link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
		<?else:?>
			<a class="pagination__link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
		<?endif?>
		<?$arResult["nStartPage"]++?>
		</li>
	<?endwhile?>
	<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
	    <li class="pagination__item">
		   <a class="pagination__link pagination__link--next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
                <svg class="icon">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037034802#icon-arrow-right"></use>
                </svg>
		   </a>
		</li>
	<?endif?>
<?endif?>
<?if ($arResult["bShowAll"]):?>
	    <li class="pagination__item">
	<noindex>
	<?if ($arResult["NavShowAll"]):?>
		  <a class="pagination__link is-current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
	<?else:?>
		  <a class="pagination__link is-current" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=GetMessage("nav_all")?></a>
	<?endif?>
	</noindex>
	    </li>
<?endif?>
   </ul>
</div>