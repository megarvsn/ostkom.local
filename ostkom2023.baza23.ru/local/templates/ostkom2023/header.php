<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

global $APPLICATION, $USER;

Loc::loadMessages(__FILE__);
$asset = Asset::getInstance();

if (file_exists($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/variable_definition.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/variable_definition.php');
}

// Last Modified
$filename = __FILE__;
$LastModified_unix = filemtime($filename); // время последнего изменения страницы

$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
$IfModifiedSince = false;
if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
    $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
    $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
    exit;
}
header('Last-Modified: ' . $LastModified);

$asset->addString('<meta charset="' . LANG_CHARSET . '">', true);
$asset->addString('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">', true);
$asset->addString('<meta http-equiv="X-UA-Compatible" content="ie=edge">', true);

$APPLICATION->ShowMeta("robots", false);
$APPLICATION->ShowMeta("keywords");
$APPLICATION->ShowMeta("description");

// Canonical
$asset->addString('<link rel="canonical" href="' . $currentURL . '" />');

// Favicon icons
$asset->addString('<link rel="apple-touch-icon" sizes="57x57" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-57x57.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="60x60" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-60x60.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="72x72" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-72x72.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="76x76" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-76x76.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="114x114" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-114x114.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="120x120" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-120x120.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="144x144" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-144x144.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="152x152" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-152x152.png">', true);
$asset->addString('<link rel="apple-touch-icon" sizes="180x180" href="' . SITE_TEMPLATE_PATH . '/favicon/apple-icon-180x180.png">', true);
$asset->addString('<link rel="icon" type="image/png" sizes="192x192" href="' . SITE_TEMPLATE_PATH . '/favicon/android-icon-192x192.png">', true);
$asset->addString('<link rel="icon" type="image/png" sizes="96x96" href="' . SITE_TEMPLATE_PATH . '/favicon/favicon-96x96.png">', true);
$asset->addString('<link rel="icon" type="image/png" sizes="32x32" href="' . SITE_TEMPLATE_PATH . '/favicon/favicon-32x32.png">', true);
$asset->addString('<link rel="icon" type="image/png" sizes="16x16" href="' . SITE_TEMPLATE_PATH . '/favicon/favicon-16x16.png">', true);
$asset->addString('<link rel="manifest" href="' . SITE_TEMPLATE_PATH . '/favicon/manifest.json">', true);
$asset->addString('<link rel="shortcut icon" href="' . SITE_TEMPLATE_PATH . '/favicon/favicon.ico">', true);
$asset->addString('<meta name="msapplication-TileColor" content="#ffffff">', true);
$asset->addString('<meta name="msapplication-TileImage" content="' . SITE_TEMPLATE_PATH . '/favicon/ms-icon-144x144.png">', true);
$asset->addString('<meta name="msapplication-config" content="' . SITE_TEMPLATE_PATH . '/favicon/browserconfig.xml">', true);

// CSS
$asset->addCss(SITE_TEMPLATE_PATH . '/css/styles.min.css');

// Scripts & Styles
$APPLICATION->ShowCSS(true, false);
$APPLICATION->ShowHeadStrings();
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">

<head>
    <?
    // Запрет на индексирование страниц сайта, указанных в файле robots.txt
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-robots.php')) {
        include_once($_SERVER['DOCUMENT_ROOT'] . '/d-robots.php');
        $dRobots = dRobots::fromFile();
        $noindex = $dRobots->checkUrl($_SERVER['REQUEST_URI']) ? '<meta name="googlebot" content="noindex">' . PHP_EOL : '';
    } else
        $noindex = '';
    echo $noindex;
    ?>
</head>

<body>
    <header>
        It's header
    </header>
    <main>
        It's main