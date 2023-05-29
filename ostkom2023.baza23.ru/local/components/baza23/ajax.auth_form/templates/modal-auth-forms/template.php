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

$obName = 'maf_' . randString(4);

?><script><?
    ?>let <?= $obName ?> = new jsc_AJAX_ModalAuthForm({<?
        ?>SCRIPT_URL: '<?= SITE_TEMPLATE_PATH ?>/js/web-form.min.js',<?
        ?>AJAX_URL:   '<?= $templateFolder ?>/modal-auth-form.php',<?
        ?>MODAL_ID:    '<?= \Baza23\AuthForms::AF_MODAL_CSS_ID ?>'<?
    ?>});<?

    if ($_REQUEST['login'] == 'yes') {
        ?><?= $obName ?>.jsf_run({href: "LOGIN"});<?

    } elseif ($_REQUEST['register'] == 'yes') {
        ?><?= $obName ?>.jsf_run({href: "REGISTER"});<?

    } elseif ($_REQUEST['change_password'] == 'yes') {
        ?><?= $obName ?>.jsf_run({href: "CHANGE_PASS"});<?

    } elseif ($_REQUEST['confirm_registration'] == 'yes') {
        ?><?= $obName ?>.jsf_run({href: "CONFIRM_REG"});<?
    }
?></script><?