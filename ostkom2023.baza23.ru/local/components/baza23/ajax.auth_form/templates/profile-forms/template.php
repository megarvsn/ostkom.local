<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this -> setFrameMode(true);

CJSCore::Init(['popup']);

$arResult["AJAX"] = "N";

$suff = randString(4);

$rootId = trim($arParams["CSS_ID"]);
if (!$rootId) $rootId = "profile-form-" . $suff;

$obName = 'pf_' . $suff;

?><div id="<?= $rootId ?>" class="<? if ($arParams["CSS_CLASSES"]) { ?> <?= $arParams["CSS_CLASSES"] ?><? } ?>"><?
    include __DIR__ . '/profile-form.php';
?></div><?

?><script><?
    ?>let <?= $obName ?> = new jsc_AJAX_ProfileForm({<?
        ?>SCRIPT_URL: '<?= SITE_TEMPLATE_PATH ?>/js/web-form.min.js',<?
        ?>AJAX_URL:   '<?= $templateFolder ?>/profile-form.php',<?
        ?>FORM_ID:    '<?= $rootId ?>'<?
    ?>});<?
?></script><?