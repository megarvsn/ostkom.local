<?require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <?$APPLICATION->ShowHeadStrings()?> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>
<b>Binding technologies to an address</b><br><br>
<?
set_time_limit(3600);
if(!CModule::IncludeModule("iblock")) return; 

$arr_city=array();
$arr_city_id=array();
$rsSections = CIBlockSection::GetList(array('NAME' => 'ASC'), array("IBLOCK_ID"=>27,"ACTIVE"=>"Y","SECTION_ID"=>false));
while ($arSection = $rsSections->Fetch())
{
    $arr_city[$arSection['ID']]=$arSection['NAME'];
	$arr_city_id[]=$arSection['ID'];
}
$arr_strit=array();
$city=($_POST['city']!="")? $_POST['city'] : false;
$rsSections = CIBlockSection::GetList(array('NAME' => 'ASC'), array("IBLOCK_ID"=>27,"ACTIVE"=>"Y","SECTION_ID"=>$city, "DEPTH_LEVEL"=>2));
while ($arSection = $rsSections->Fetch())
{
    $arr_strit[$arSection['ID']]=$arSection['NAME'];
}
$arr_tech=array();
$res = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>29,"ACTIVE"=>"Y"), false, false, array("ID","IBLOCK_ID","NAME"));
while($ob = $res->GetNext()){ 
   $arr_tech[$ob['ID']]=$ob['NAME'];
}   
if($_POST['type']!="" AND $_POST['tech']!="" AND $_POST['city']!=""){
		//echo"post <pre>";  print_r($_POST);  echo"</pre>"; 
	$section=($_POST['strit']>0)? $_POST['strit'] : (($_POST['city']>0)? $_POST['city'] : 0);
	$filter=array("IBLOCK_ID"=>27,"ACTIVE"=>"Y","INCLUDE_SUBSECTIONS"=>"Y","SECTION_ID"=> $section);
		//echo"filter <pre>";  print_r($filter);  echo"</pre>";
	$res = CIBlockElement::GetList(Array(), $filter, false, false, array("ID","IBLOCK_ID","NAME","IBLOCK_SECTION_ID"));
	while($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();
	    if($_POST['type']==1 AND !in_array($_POST['tech'], $arProps['SERVICES']['VALUE'])){
			$arProps['SERVICES']['VALUE'][]=$_POST['tech'];
			//echo"home <pre>";  print_r($arProps['SERVICES']['VALUE']);  echo"</pre>";
			CIBlockElement::SetPropertyValueCode($arFields['ID'], "SERVICES", $arProps['SERVICES']['VALUE']);
            echo"Add to B/".$arFields['NAME']."<br>";			
		}
	    if($_POST['type']==2 AND in_array($_POST['tech'], $arProps['SERVICES']['VALUE'])){
			foreach($arProps['SERVICES']['VALUE'] as $key=>$val){
				if($val==$_POST['tech'])
					unset($arProps['SERVICES']['VALUE'][$key]);
			}
			//echo"home <pre>";  print_r($arProps['SERVICES']['VALUE']);  echo"</pre>";
			CIBlockElement::SetPropertyValueCode($arFields['ID'], "SERVICES", $arProps['SERVICES']['VALUE']);
            echo"Remove from B/".$arFields['NAME']."<br>";			
		}		
	}
}
else{
	echo"<br><br><span style='color:red'>Choice City and Technology and Type of transaction</span><br></br>";
}
?>
<br><br>
<form action="/set.php" method="POST">
   City: 
   <select name="city">
	<option value="">choice</option>
   <?foreach($arr_city as $k=>$s):?>
	<option value="<?=$k?>"<?if($_POST['city']==$k):?> selected<?endif;?>><?=$s?></option>
   <?endforeach;?>
   </select>
 <?if($_POST['city']!=""):?>  
	Street: 
   <select name="strit">
   <option value="">all</option>
   <?foreach($arr_strit as $k=>$s):?>
	<option value="<?=$k?>"<?if($_POST['strit']==$k):?> selected<?endif;?>><?=$s?></option>
   <?endforeach;?>
   </select>
  <?endif;?><br><br>
   Technology: 
   <select name="tech">
     <option value="">choice</option>
   <?foreach($arr_tech as $k=>$s):?>
	<option value="<?=$k?>"<?if($_POST['tech']==$k):?> selected<?endif;?>><?=$s?></option>
   <?endforeach;?>
   </select><br><br>
   Type of transaction: 
   <select name="type">
    <option value="">choice</option>
	<option value="1">Add</option>
	<option value="2">Remove</option>	
   </select>
  <br><br>
  <input type="submit">
</form>
<script>
jQuery(document).ready(function(){
	jQuery('select[name="city"]').change(function(){
		jQuery('form').submit();
	});
});
</script>
</body>
</html>