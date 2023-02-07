<?
spl_autoload_register(function ($class_name) {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/' . str_replace('\\', '/', $class_name) . '.class.php';
    if (file_exists($file)) require_once $file;
});

if (file_exists(__DIR__ . "/include/handlers.php")) {
    include(__DIR__ . "/include/handlers.php");
}

	AddEventHandler('form', 'onAfterResultAdd', Array("MyClass","onAfterResultAddHandler"));
	class MyClass
	{
		function onAfterResultAddHandler($WEB_FORM_ID, $RESULT_ID){

		    if ($WEB_FORM_ID == 1)
		    {
		       $arAnswer = CFormResult::GetDataByID($RESULT_ID, array(),$arResult,$arAnswer2);
			   $tpl=32;
			   if($arAnswer['new_field_82235'][0]['USER_TEXT']=="technical"){
				  $tpl=32;
			   }
			   if($arAnswer['new_field_82235'][0]['USER_TEXT']=="customer"){
				  $tpl=29;
			   }
               CFormResult::Mail($RESULT_ID,$tpl);
		    }
		    if ($WEB_FORM_ID == 2)
		    {
		       $arAnswer = CFormResult::GetDataByID($RESULT_ID, array(),$arResult,$arAnswer2);
			   $tpl=33;
			   if($arAnswer['id_department'][0]['USER_TEXT']=="1"){
				  $tpl=33;
			   }
			   if($arAnswer['id_department'][0]['USER_TEXT']=="2"){
				  $tpl=34;
			   }
			   if($arAnswer['id_department'][0]['USER_TEXT']=="3"){
				  $tpl=35;
			   }
               CFormResult::Mail($RESULT_ID,$tpl);
		    }
		}
	}
class MyCurledType extends CUserTypeString
{
   static function GetUserTypeDescription()
   {
      return array(
         "USER_TYPE_ID" => "c_string",
         "CLASS_NAME" => "MyCurledType",
         "DESCRIPTION" => "html текст",
         "BASE_TYPE" => "string",
      );
   }
//Этот метод вызывается для показа значений в списке
   function GetAdminListViewHTML($arUserField, $arHtmlControl)
   {
      if(strlen($arHtmlControl["VALUE"])>0)
         return "{".$arHtmlControl["VALUE"]."}";
      else
         return 'html текст';
   }

	function GetEditFormHTML($arUserField, $arHtmlControl)
	{
 //echo"<pre>";	print_r($arUserField); 	echo"</pre>";
		if($arUserField["ENTITY_VALUE_ID"]<1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"])>0)
			$arHtmlControl["VALUE"] = htmlspecialcharsbx($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
		if($arUserField["SETTINGS"]["ROWS"] < 2)
		{
			$arHtmlControl["VALIGN"] = "middle";
			return '<input type="text" '.
				'name="'.$arHtmlControl["NAME"].'" '.
				'size="'.$arUserField["SETTINGS"]["SIZE"].'" '.
				($arUserField["SETTINGS"]["MAX_LENGTH"]>0? 'maxlength="'.$arUserField["SETTINGS"]["MAX_LENGTH"].'" ': '').
				'value="'.$arHtmlControl["VALUE"].'" '.
				($arUserField["EDIT_IN_LIST"]!="Y"? 'disabled="disabled" ': '').
				'>';
		}
		else
		{
		    if (!CModule::IncludeModule("fileman")) return GetMessage("IBLOCK_PROP_HTML_NOFILEMAN_ERROR");

		ob_start();
		//echo '<input type="hidden" name="'.$strHTMLControlName["VALUE"].'[TYPE]" value="html">';
		echo'<style>div.bxlhe-frame{overflow:visible;} iframe body{width:100%!important;}</style>';
		$LHE = new CLightHTMLEditor;
		$LHE->Show(array(
			'id' => $arHtmlControl["NAME"],
			'width' => '100%',
			'height' => '250px',
			'inputName' => $arHtmlControl["NAME"],
			'content' => $arUserField['VALUE'],
			'jsObjName' => "oLHE",
            'bUseFileDialogs' => false,
            'bFloatingToolbar' => false,
            'bArisingToolbar' => false,
			'toolbarConfig' => array(
      'Source', 'Bold', 'Italic', 'Underline', 'RemoveFormat','Video', 'Html',
        'CreateLink', 'DeleteLink', 'Image', 'Video',
        'BackColor', 'ForeColor',
        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull',
        'InsertOrderedList', 'InsertUnorderedList', 'Outdent', 'Indent',
        'StyleList', 'HeaderList',
        'FontList', 'FontSizeList',
			),
		));
		$s = ob_get_contents();
		ob_end_clean();
		return  $s;
		}
	}

}
AddEventHandler("main", "OnUserTypeBuildList", array("MyCurledType", "GetUserTypeDescription"));
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CUserTypeSectionLink", "GetUserTypeDescriptionI"));

class CUserTypeSectionLink
{

    private static $Script_included = false;

    function GetUserTypeDescriptionI()
    {
        return array(
            "USER_TYPE_ID" => "G",
            "CLASS_NAME" => "CUserTypeSectionLink",
            "DESCRIPTION" => "Города",
            "BASE_TYPE" => "G",
            "PROPERTY_TYPE" => "G",
            "USER_TYPE" => "city",
            "GetPublicViewHTML" => array("CUserTypeSectionLink","GetPublicViewHTML"),
            "GetPropertyFieldHtmlMulty" => array("CUserTypeSectionLink","GetPropertyFieldHtml"),
        );
    }

    public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
         return $value['VALUE'];
    }

    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
		$sectionsList=array();
		foreach($value as $k => $v){
			$sectionsList[$v['VALUE']]=$v['VALUE'];
		}
		//echo"<pre>";  print_r($sectionsList);   echo"</pre>";
         $db_list = CIBlockSection::GetList(Array("NAME" => "ASC"), Array('IBLOCK_ID'=>27, 'GLOBAL_ACTIVE'=>'Y', 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1), false);
		 $optionsHTML='<option value=""> - не установлено - </option>';
         while($s = $db_list->GetNext())
         {
            $optionsHTML .= '<option value="'.$s["ID"].'"'.( $sectionsList[$s['ID']]==$s['ID'] ? ' selected' : '' ).'>'.str_repeat(" ", $s["DEPTH_LEVEL"])." ".$s['NAME'].' ['.$s['ID'].']'.'</option>';
         }
        return  '<select multiple="multiple" name="'.$strHTMLControlName["VALUE"].'[]>'.$optionsHTML.'</select>';
    }
}
?>
