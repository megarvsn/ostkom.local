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

<section class="mobile_apps">
    <h2>Мобильные приложения</h2>
    <a href="#"><? require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/img/app-store-badge_ru.svg"); ?></a>
    <a href="#"><? require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/img/google-play-badge_ru.svg"); ?></a>
</section>