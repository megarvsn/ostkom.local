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
CJSCore::Init(['popup']);

$obName = 'mwf_' . randString(4);

?><script><?
    ?>let <?= $obName ?> = new jsc_AJAX_ModalWebForm({<?
        ?>SCRIPT_URL: '<?= SITE_TEMPLATE_PATH ?>/js/web-form.min.js',<?
        ?>AJAX_URL:   '<?= $templateFolder ?>/modal-web-form.php',<?
        ?>MODAL_ID:    '<?= \Baza23\WebForms::WF_MODAL_CSS_ID ?>'<?
    ?>});<?
?></script><?