<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/include/header.php');
global $LangRu;
if(!$LangRu){
	$street_n="Iela";
    $home_n="Mājas numurs";		
}
else{
	$street_n="Улица";	
	$home_n="Номер дома";
}
CModule::IncludeModule('iblock');
if($_GET['t']=="s"){
   $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $_GET['id']),false,array("ID","NAME"));  
?>
     <option value=""><?=$street_n?>*</option>
<?   
   while ($arSect = $rsSect->GetNext())
   {
?>
     <option id="<?=$arSect['ID']?>" value="<?=$arSect['NAME']?>"><?=$arSect['NAME']?></option>
<? 
   }
}
elseif($_GET['t']=="h"){
   $res = CIBlockElement::GetList(array('SORT' =>'asc'),array("ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $_GET['id']),false,false,array("ID","NAME"));	
?>
     <option value=""><?=$home_n?>*</option>
<?   
   while($ob = $res->GetNextElement())
   {
      $arFields = $ob->GetFields();
?>
     <option value="<?=$arFields['ID']?>"><?=$arFields['NAME']?></option>
<?
   }
}
?>