<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
   <nav class="panel__nav">
<?
$previousLevel = 0;
foreach($arResult as $arItem):?>
	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<a href="<?=$arItem["LINK"]?>" class="panel__nav-link parent<?if ($arItem["SELECTED"]):?> is-current<?endif?>"><?=$arItem["TEXT"]?></a>
		<?else:?>
			<a href="<?=$arItem["LINK"]?>" class="panel__nav-link parent"><?=$arItem["TEXT"]?></a>
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<a href="<?=$arItem["LINK"]?>" class="panel__nav-link<?if ($arItem["SELECTED"]):?>  is-current<?endif?>"><?=$arItem["TEXT"]?></a>
			<?else:?>
				<a href="<?=$arItem["LINK"]?>" class="panel__nav-link submenu_link<?if ($arItem["SELECTED"]):?>  is-current<?endif?>"><?=$arItem["TEXT"]?></a>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<a href="" class="panel__nav-link<?if ($arItem["SELECTED"]):?> is-current<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
			<?else:?>
				<a href="" class="panel__nav-link denied submenu_link" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>
    </nav> 
<?endif?>