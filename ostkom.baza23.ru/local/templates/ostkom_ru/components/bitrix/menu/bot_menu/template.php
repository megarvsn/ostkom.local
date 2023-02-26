<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
        <ul class="footer__nav">
<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
          <li class="footer__nav-item"><a class="footer__nav-link is-current" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>	
	<?else:?>
		  <li class="footer__nav-item"><a class="footer__nav-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	
<?endforeach?>
        </ul>
<?endif?>