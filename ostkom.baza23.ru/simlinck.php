<?
$simlinks=array(
"/order/get_address.php" => "/ru/order/get_address.php",
"/verification/services.php" => "/ru/verification/services.php",
"/verification/verification.php" => "/ru/verification/verification.php",
"/verification/verification.php" => "/ru/verification/verification.php",
"/bitrix/templates/ostkom/__nav.js" => "/bitrix/templates/ostkom_ru/__nav.js",
"/bitrix/templates/ostkom/fonts" => "/bitrix/templates/ostkom_ru/fonts",
"/bitrix/templates/ostkom/footer.php" => "/bitrix/templates/ostkom_ru/footer.php",
"/bitrix/templates/ostkom/header.php" => "/bitrix/templates/ostkom_ru/header.php",
"/bitrix/templates/ostkom/images" => "/bitrix/templates/ostkom_ru/images",
"/bitrix/templates/ostkom/scripts" => "/bitrix/templates/ostkom_ru/scripts",
"/bitrix/templates/ostkom/sprite" => "/bitrix/templates/ostkom_ru/sprite",
"/bitrix/templates/ostkom/styles" => "/bitrix/templates/ostkom_ru/styles",
"/bitrix/templates/ostkom/template_styles.css" => "/bitrix/templates/ostkom_ru/template_styles.css",
"/bitrix/templates/ostkom/include_areas/copyright.php" => "/bitrix/templates/ostkom_ru/include_areas/copyright.php",
"/bitrix/templates/ostkom/include_areas/footer_map.php" => "/bitrix/templates/ostkom_ru/include_areas/footer_map.php",
"/bitrix/templates/ostkom/include_areas/location.php" => "/bitrix/templates/ostkom_ru/include_areas/location.php",
"/bitrix/templates/ostkom/include_areas/office_5.php" => "/bitrix/templates/ostkom_ru/include_areas/office_5.php",
"/bitrix/templates/ostkom/include_areas/office_6.php" => "/bitrix/templates/ostkom_ru/include_areas/office_6.php",
"/bitrix/templates/ostkom/include_areas/phone.php" => "/bitrix/templates/ostkom_ru/include_areas/phone.php",
"/bitrix/templates/ostkom/include_areas/phone_top.php" => "/bitrix/templates/ostkom_ru/include_areas/phone_top.php",
"/bitrix/templates/ostkom/include_areas/socials-link-facebook.php" => "/bitrix/templates/ostkom_ru/include_areas/socials-link-facebook.php",
"/bitrix/templates/ostkom/include_areas/socials-link-gplus.php" => "/bitrix/templates/ostkom_ru/include_areas/socials-link-gplus.php",
"/bitrix/templates/ostkom/include_areas/socials-link-instagram.php" => "/bitrix/templates/ostkom_ru/include_areas/socials-link-instagram.php",
"/bitrix/templates/ostkom/include_areas/verification_form.php" => "/bitrix/templates/ostkom_ru/include_areas/verification_form.php",
"/bitrix/templates/ostkom/components" => "/bitrix/templates/ostkom_ru/components",
);
foreach($simlinks as $target => $link ){
symlink($_SERVER['DOCUMENT_ROOT'].$target, $_SERVER['DOCUMENT_ROOT'].$link);
}
?>