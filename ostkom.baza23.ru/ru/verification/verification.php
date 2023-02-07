<?
function verification($home_id,$tariff_id){
   CModule::IncludeModule('iblock');
   $res = CIBlockElement::GetList(array(),array("ACTIVE" => "Y","IBLOCK_ID" => 27,"ID" => $home_id),false,false,array("ID","NAME","IBLOCK_ID","PROPERTY_SERVICES","IBLOCK_SECTION_ID"));
   while($ob = $res->GetNextElement()){
       $arProps = $ob->GetProperties();
	   $arFieldsAddress = $ob->GetFields();
   }
   $arrServices=$arProps['SERVICES']['VALUE'];
   $res = CIBlockElement::GetList(Array(), Array("ID"=>$tariff_id), false, false, Array("ID","NAME","IBLOCK_ID")); 
   while($ob = $res->GetNextElement()){ 
      $arFeature = $ob->GetProperties(); 
   }
   $resAddress = CIBlockSection::GetByID($arFieldsAddress['IBLOCK_SECTION_ID']);
   if($ar_address = $resAddress->GetNext()){
		$city=$ar_address['IBLOCK_SECTION_ID'];
   }  
   $arr_result1= in_array($city, $arFeature['CITY']['VALUE']);
  // $arr_result = array_intersect ($arrServices, $arFeature['FEATURE']['VALUE']);
   $array_diff = array_diff($arFeature['FEATURE']['VALUE'],$arrServices);
  if(count($array_diff)<1 AND $arr_result1)  
  // if(count($arr_result)>0 AND $arr_result1)
      return true;
   else
     return false;
}
?>