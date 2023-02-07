<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
$res = CIBlockSection::GetByID($_GET['c']);
if($ar_res = $res->GetNext()){
   $_SESSION['Lcity']['NAME']=$ar_res['NAME'];
   $_SESSION['Lcity']['ID']=$ar_res['ID']; 
}
?>