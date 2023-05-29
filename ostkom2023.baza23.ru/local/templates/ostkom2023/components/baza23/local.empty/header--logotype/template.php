<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<section class="logotype">
    <?
    echo $APPLICATION->GetCurDir() != SITE_DIR ? '<a href="' . SITE_DIR . '">' : '<span>';
    require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/img/ostkom.svg");
    echo $APPLICATION->GetCurDir() != SITE_DIR ? '</a>' : '</span>';
    ?>
</section>