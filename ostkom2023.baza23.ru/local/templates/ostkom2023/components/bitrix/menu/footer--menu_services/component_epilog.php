<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */
if (!empty($templateData['TEMPLATE_LIBRARY'])) {
    CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
}