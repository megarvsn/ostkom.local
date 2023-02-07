<?
require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );
if(!CModule::IncludeModule("iblock")) return; 
$addopt=array();
$res = CIBlockElement::GetList(Array("SORT" => "ASC"), array("IBLOCK_ID"=>33,"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"));
while($ob = $res->GetNextElement()){ 
	$arFields = $ob->GetFields();
	$addopt[$arFields['ID']]['ID']=$arFields['ID'];	
	$addopt[$arFields['ID']]['NAME']=$arFields['NAME'];		
	$addopt[$arFields['ID']]['PHOTO']=CFile::GetFileArray($arFields['PREVIEW_PICTURE']);	
}

if($_GET['section']>0){
	$section_availabel=array();
	$res = CIBlockElement::GetList(Array(), array("ID"=>$_GET['section']), false, false, array("ID","IBLOCK_ID"));
	$ob = $res->GetNextElement();
	$arProps = $ob->GetProperties(); 
	if($arProps['ADDOPT']['VALUE'])
		foreach($arProps['ADDOPT']['VALUE'] as $opt)
			$section_availabel[$opt]['AVAILABLE']="Y";	
}
$res = CIBlockElement::GetList(Array(), array("ID"=>$_GET['id']), false, false, array("ID","IBLOCK_ID"));
$ob = $res->GetNextElement();
$arFields = $ob->GetFields();
$arProps = $ob->GetProperties(); 
if(count($arProps['CHANNELS_E']['VALUE'])>0)
	$arProps['CHANNELS']=$arProps['CHANNELS_E'];
if($_GET['type']==2)
	$arProps['CHANNELS']=$arProps['N_CHANNELS_D'];
if($_GET['type']==3)
	$arProps['CHANNELS']=$arProps['N_CHANNELS_HD'];
if($arProps['ADDOPT']['VALUE']){
	foreach($arProps['ADDOPT']['VALUE'] as $opt){	
		if($_GET['section']>0){
			if($section_availabel[$opt]['AVAILABLE']=="Y")
				$addopt[$opt]['AVAILABLE']="Y";
		}
		else{
			$addopt[$opt]['AVAILABLE']="Y";
		}
	}
}	
if(count($arProps['CHANNELS']['VALUE'])>0){
   $Arr_Section_Chan=Array();
   $rsSect = CIBlockSection::GetList(array('SORT' => 'asc'), array('IBLOCK_ID' => 28, "GLOBAL_ACTIVE"=>"Y", "ACTIVE"=>"Y"),false,array("ID","NAME","UF_NAME_LV"));
   while ($arSect = $rsSect->GetNext())
   {
	   if($_SESSION['LAND']=='lv') $arSect['NAME']=$arSect['UF_NAME_LV'];
	   $Arr_Section_Chan[$arSect['ID']]['NAME']=$arSect['NAME'];
   }
   $res = CIBlockElement::GetList(Array("SORT" => "ASC"), array("IBLOCK_ID"=>28,"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$arProps['CHANNELS']['VALUE']), false, false, array("IBLOCK_SECTION_ID","ID","NAME","PREVIEW_PICTURE","IBLOCK_ID"));
   while($ob = $res->GetNextElement()){ 
    $arFields = $ob->GetFields();  
    $arProps = $ob->GetProperties();	
	$arFields['links']=$arProps['LINKS']['VALUE'];
	$arFields['options']=$arProps['ADDOPTIONS']['VALUE'];
	if(!$arFields['IBLOCK_SECTION_ID'])  $arFields['IBLOCK_SECTION_ID']=0;
	$Arr_Section_Chan[$arFields['IBLOCK_SECTION_ID']]['ITEMS'][]=$arFields;
   } 
}	
?>
	<?foreach($Arr_Section_Chan as $Chan):?>
	  <?if($Chan['ITEMS']):?>
	  <div class="Chan_Item_List">  
	     <div class="name"><?=$Chan['NAME']?></div>
		 <ul id="channels_up">
		 <?foreach($Chan['ITEMS'] as $item):?>
	        <li> 
	           <a target="_blanck" href="<?=$item['links']?>">
				<?
				if($item['options']){
				?>
				<div class="opt">
				<?				
					foreach($item['options'] as $option){
					if($addopt[$option]['AVAILABLE']=="Y"){						
						$arFile = $addopt[$option]['PHOTO'];
				?>
					<img src="<?=$arFile['SRC']?>" alt="<?=$arFile['DESCRIPTION']?>" title="<?=$arFile['DESCRIPTION']?>"/>
				<?
					}
					}
				?>
				</div>
				<?
				}
				?>			   
				<img src="<?=CFile::GetPath($item['PREVIEW_PICTURE'])?>" alt="<?=$item['NAME']?>"/><div><?=$item['NAME']?></div>
			   </a>
	        </li>
	      <?endforeach;?>  
	     </ul>  
	  </div>	  
	  <?endif;?>
	<?endforeach;?>  
 	