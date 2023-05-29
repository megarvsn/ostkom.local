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
$cssClasses = $arParams["CSS_CLASSES"];

if ($USER -> IsAuthorized()) {
    if ($arParams["SHOW_NAME"] == "Y") {
        $userName = trim($arResult["USER"]["NAME_FORMATTED"]);
        if (! $userName) $userName = $arParams["~PROFILE_TEXT"];
        if (! $userName) $userName = "Nameless";

        if (!empty($arParams["PROFILE_URL"])) {
            ?><a href="<?= $arParams["PROFILE_URL"] ?>"<?
                 ?> class="user-link user-profile<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
                 ?> rel="nofollow"<?
            ?>><span><?= $userName ?></span></a><?
        }
    }

} elseif ($arParams["SHOW_LOGIN"] == "Y") {
    $profileText = $arParams["~PROFILE_TEXT"];
    if (!$profileText) $profileText = 'Profile';

    if ($arParams["SHOW_LOGIN_IN_MODAL"] == "Y") {
        ?><div class="user-link user-login js-auth-form<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
               ?> data-href="login"<?
               if ($arParams["LOGIN_MODAL_ID"]) { ?> data-type="<?= $arParams["LOGIN_MODAL_ID"] ?>"<? }
               ?> rel="nofollow"<?
        ?>><span><?= $profileText ?></span></div><?

    } elseif ($arParams["LOGIN_URL"]) {
        ?><a href="<?= $arParams["LOGIN_URL"] ?>"<?
             ?>class="user-link user-login<? if ($cssClasses) { echo ' ' . $cssClasses; } ?>"<?
        ?>><span><?= $profileText ?></span></a><?
    }
}