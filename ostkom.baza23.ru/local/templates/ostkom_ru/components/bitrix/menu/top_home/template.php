<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
<div class="header__nav">
   <nav class="nav">
<?
$previousLevel = 0;
foreach($arResult as $arItem):?>
	<?if ($previousLevel && ($arItem["DEPTH_LEVEL"] < $previousLevel)):?>
		<?=str_repeat("</div>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>
	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<div class="navlink nav__link parent"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?> is-current<?endif?>"><?=$arItem["TEXT"]?></a>
		<?else:?>
			<div class="navlink nav__link parent"><a href="<?=$arItem["LINK"]?>" class=""><?=$arItem["TEXT"]?></a>
		<?endif?>
		     <div class="submenu">

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<div class="navlink nav__link"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>  is-current<?endif?>"><?=$arItem["TEXT"]?></a></div>
			<?else:?>
				<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<div class="navlink nav__link"><a href="" class="nav__link<?if ($arItem["SELECTED"]):?> is-current<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></div>
			<?else:?>
				<a href="" class="nav__link denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
			<?endif?>

		<?endif?>

	<?endif?>
    <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
<?endforeach?>
<?if ($previousLevel > 1):?>
	<?=str_repeat("</div>", ($previousLevel-1) );?>
<?endif?>
    </nav>
</div>		  
<?endif?>