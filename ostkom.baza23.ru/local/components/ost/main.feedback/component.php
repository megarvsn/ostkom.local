<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
if($arParams["EVENT_NAME"] == '')

$arParams["EVENT_TEL"] = trim($arParams["EVENT_TEL"]);
if($arParams["EVENT_TEL"] == '')

$arParams["EVENT_NAME"] = "USER_ORDER";
$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] <> '' && (!isset($_POST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_POST["PARAMS_HASH"]))
{
	$arResult["ERROR_MESSAGE"] = array();
	if(check_bitrix_sessid())
	{
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"]))
		{
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_name"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_NAME");		
				
						if((empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_tel"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_TEL");	
					
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_email"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_EMAIL");
				
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["MESSAGE"]) <= 3)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");
		}
		if(strlen($_POST["user_email"]) > 1 && !check_email($_POST["user_email"]))
			$arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");
		if($arParams["USE_CAPTCHA"] == "Y")
		{
			include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
			$captcha_code = $_POST["captcha_sid"];
			$captcha_word = $_POST["captcha_word"];
			$cpt = new CCaptcha();
			$captchaPass = COption::GetOptionString("main", "captcha_password", "");
			if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0)
			{
				if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
					$arResult["ERROR_MESSAGE"][] = "captcha code error";
			}
			else
				$arResult["ERROR_MESSAGE"][] = "captcha code error";

		}			
		if(empty($arResult["ERROR_MESSAGE"]))
		{		
    $res = CIBlockElement::GetByID($_POST["home"]);
    if($ar_res = $res->GetNext()){
	  $home=$ar_res["NAME"];
    }			
			$arFields = Array(
				"AUTHOR" => $_POST["user_name"],
				"AUTHOR_TEL" => $_POST["user_tel"],
				"AUTHOR_PHONE" => $_POST["user_tel"],				
				"AUTHOR_EMAIL" => $_POST["user_email"],
				"EMAIL_TO" => $arParams["EMAIL_TO"],
				"CITY" => $_POST["city"],
				"STREET" => $_POST["street"], 
				"HOME" => $home, 	
				"TEXT" => $_POST["MESSAGE"],
				"SITE" => $_POST["SITE"],
				"ICQ" => $_POST["ICQ"],
				"COMPANY" => $_POST["COMPANY"],				
				"FLAT" => $_POST["flat"],
			    "SECTION" => $_POST["section_name"],
			    "TARIFF_NAME" => $_POST["tariff_name"],
			    "TARIFF_PRICE" => ($_POST["tariff_price"])? $_POST["tariff_price"]."&nbsp;€/мес.":"не подключен",
		        "IP" => ($_POST["ip"])? $_POST["ip"]."&nbsp;€/мес.":"не подключен",
			    "CONNECT_NAME" => $_POST["connect_name"],
				"TARIFF_PACKAGE" => ($_POST["tariff_packages"])? $_POST["tariff_packages"]."&nbsp;€/мес.":"не подключен",
				"NAME_PACKAGE" => $_POST["name_packages"],				
			    "CONNECT_PRICE" => ($_POST["connect_price"])? $_POST["connect_price"]."&nbsp;€":"нет",
                "SUMM_1" => ($_POST["summ_1"])? $_POST["summ_1"]:"0",
                "SUMM_2" => ($_POST["summ_2"])? $_POST["summ_2"]:"0",				
				"ABR" => $_POST["ABR"],
				"FREND_NAME" => $_POST["FREND_NAME"],	
				"FREND_SNAME" => $_POST["FREND_SNAME"],					
				"PRODUCT" => $_POST["PRODUCT"],
				"AUD" => $_POST["AUD"],
				"ADD_TEXT" => $_POST["ADD_TEXT"],
				"NISHA" => $_POST["NISHA"],
				"RADIO1" => $_POST["radio-1"],
				"RADIO2" => $_POST["radio-2"],
				"KORPSTIL" => $_POST["KORPSTIL"],
				"STR" => $_POST["STR"],
				"DEL" => $_POST["DEL"],
				"RAZV" => $_POST["RAZV"],
				"CONS" => $_POST["CONS"],
				"SOVR" => $_POST["SOVR"],
				"STAT" => $_POST["STAT"],
				"DIN" => $_POST["DIN"],
				"DR" => $_POST["DR"],
				"BCOL" => $_POST["BCOL"],
				"NCOL" => $_POST["NCOL"],
				"PASTEL" => $_POST["PASTEL"],
				"MAGK" => $_POST["MAGK"],
				"KONTR" => $_POST["KONTR"],
				"CHIST" => $_POST["CHIST"],
				"JARK" => $_POST["JARK"],
				"MONOCH" => $_POST["MONOCH"],
				"LIKE" => $_POST["LIKE"],
				"NLIKE" => $_POST["NLIKE"],
				"NISP" => $_POST["NISP"],
				"AGREE" => $_POST["agree"],				
				"LOGO" => $_POST["LOGO"],
				"VISITKA" => $_POST["VISITKA"],
				"FB" => $_POST["FB"],
				"KONV" => $_POST["KONV"],
				"PAPKI" => $_POST["PAPKI"],
				"KAL" => $_POST["KAL"],
				"BROSH" => $_POST["BROSH"],
				"SLOGAN" => $_POST["SLOGAN"],
				"PECHAT" => $_POST["PECHAT"],
				"PAKET" => $_POST["PAKET"],
				"BUKL" => $_POST["BUKL"],
				"PR_LIST" => $_POST["PR_LIST"],
				"DEVICES" => $_POST["devices"],
				"NRECL" => $_POST["NRECL"],
				"FLAG" => $_POST["FLAG"],
				"ADDEL" => $_POST["ADDEL"],
				"KONCEP" => $_POST["KONCEP"],
				"radio-3" => $_POST["radio-3"],
				"radio-4" => $_POST["radio-4"]
			);
			
			if(!empty($arParams["EVENT_MESSAGE_ID"]))
			{
				foreach($arParams["EVENT_MESSAGE_ID"] as $v)
					if(IntVal($v) > 0)
						CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v));
			}
			else
				CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields);
			$_SESSION["MF_NAME"] = htmlspecialcharsbx($_POST["user_name"]);
			$_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
            CModule::IncludeModule('iblock'); 	   
            $el = new CIBlockElement;
            $PROP = array();
			$PROP[36] = htmlspecialcharsbx($_POST['user_name']); 
			$PROP[37] = htmlspecialcharsbx($_POST['COMPANY']);  	
			$PROP[38] = htmlspecialcharsbx($_POST['ABR']);  
			$PROP[39] = htmlspecialcharsbx($_POST['user_tel']); 			
            $PROP[40] = htmlspecialcharsbx($_POST['user_email']);  
            $PROP[41] = htmlspecialcharsbx($_POST['city']);  
            $PROP[42] = htmlspecialcharsbx($_POST['street']);  			
            $PROP[43] = htmlspecialcharsbx($home);  
            $PROP[44] = htmlspecialcharsbx($_POST['flat']);  		
            $PROP[45] = htmlspecialcharsbx($_POST['radio-1']);  			
            $PROP[46] = htmlspecialcharsbx($_POST['STAT']);  
            $PROP[47] = htmlspecialcharsbx($_POST['NISP']);  
            $PROP[48] = htmlspecialcharsbx($_POST['section_name']); 			
            $PROP[49] = htmlspecialcharsbx($_POST["tariff_name"]);
            $PROP[50] = htmlspecialcharsbx(str_replace("<br>", ";\n",$_POST['devices'])); 	
            $PROP[51] = htmlspecialcharsbx($_POST['ip']); 
            $PROP[52] = htmlspecialcharsbx($_POST['connect_name']); 
			$PROP[56] = htmlspecialcharsbx($_POST['connect_price']); 
            $PROP[53] = htmlspecialcharsbx($_POST['summ_1']); 
            $PROP[54] = htmlspecialcharsbx($_POST['summ_2']); 			
            $PROP[55] = htmlspecialcharsbx(($_POST["tariff_price"])? $_POST["tariff_price"] : "не подключен"); 
            $PROP[63] = htmlspecialcharsbx($_POST["tariff_packages"]); 
			$PROP[64] = htmlspecialcharsbx($_POST["name_packages"]); 	
			$PROP[153] = htmlspecialcharsbx($_POST["FREND_NAME"]); 			
			$PROP[154] = htmlspecialcharsbx($_POST["FREND_SNAME"]); 				
            $arLoadProductArray = Array(
                "MODIFIED_BY"    => $USER->GetID(), 
                "IBLOCK_SECTION_ID" => false,  
                "IBLOCK_ID"      => 22,
                "PROPERTY_VALUES"=> $PROP,
                "NAME"           => htmlspecialcharsbx($_POST['section_name']),
                "ACTIVE"         => "Y",    
            );
            $el->Add($arLoadProductArray);
			LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));	
		}
		$arResult["MESSAGE"] = htmlspecialcharsbx($_POST["MESSAGE"]);
		$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_POST["user_name"]);
		$arResult["AUTHOR_TEL"] = htmlspecialcharsbx($_POST["user_tel"]);
		$arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($_POST["user_tel"]);			
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_POST["user_email"]);
		$arResult["SITE"] = htmlspecialcharsbx($_POST["SITE"]);
		$arResult["ICQ"] = htmlspecialcharsbx($_POST["ICQ"]);
		$arResult["COMPANY"] = htmlspecialcharsbx($_POST["COMPANY"]);
		$arResult["SECTION"] = htmlspecialcharsbx($_POST["section_name"]);
		$arResult["TARIFF_NAME"] = htmlspecialcharsbx($_POST["tariff_name"]);
		$arResult["TARIFF_PRICE"] = htmlspecialcharsbx($_POST["tariff_price"]);
		$arResult["TARIFF_PACKAGE"] = htmlspecialcharsbx($_POST["tariff_packages"]);
		$arResult["NAME_PACKAGE"] = htmlspecialcharsbx($_POST["name_packages"]);	
		$arResult["DEVICES"] = htmlspecialcharsbx($_POST["devices"]);
		$arResult["IP"] = htmlspecialcharsbx($_POST["ip"]);
		$arResult["CONNECT_NAME"] = htmlspecialcharsbx($_POST["connect_name"]);
		$arResult["CONNECT_PRICE"] = htmlspecialcharsbx($_POST["connect_price"]);
        $arResult["SUMM_1"] = htmlspecialcharsbx($_POST["summ_1"]);
        $arResult["SUMM_2"] = htmlspecialcharsbx($_POST["summ_2"]);
		$arResult["FLAT"] = htmlspecialcharsbx($_POST["flat"]);		
		$arResult["CITY"] = htmlspecialcharsbx($arParams["city"]);
		$arResult["STREET"] = htmlspecialcharsbx($_POST["street"]);
		$arResult["HOME"] = htmlspecialcharsbx( $_POST["home"]);
		$arResult["AGREE"] = htmlspecialcharsbx($_POST["agree"]);		
		$arResult["ABR"] = htmlspecialcharsbx($_POST["ABR"]);
		$arResult["FREND_NAME"] = htmlspecialcharsbx($_POST["FREND_NAME"]);
		$arResult["FREND_SNAME"] = htmlspecialcharsbx($_POST["FREND_SNAME"]);			
		$arResult["PRODUCT"] = htmlspecialcharsbx($_POST["PRODUCT"]);
		$arResult["AUD"] = htmlspecialcharsbx($_POST["AUD"]);
		$arResult["ADD_TEXT"] = htmlspecialcharsbx($_POST["ADD_TEXT"]);
		$arResult["NISHA"] = htmlspecialcharsbx($_POST["NISHA"]);
		$arResult["radio-1"] = htmlspecialcharsbx($_POST["radio-1"]);
		$arResult["radio-2"] = htmlspecialcharsbx($_POST["radio-2"]);
		$arResult["KORPSTIL"] = htmlspecialcharsbx($_POST["KORPSTIL"]);
		$arResult["STR"] = htmlspecialcharsbx($_POST["STR"]);
		$arResult["DEL"] = htmlspecialcharsbx($_POST["DEL"]);
		$arResult["RAZV"] = htmlspecialcharsbx($_POST["RAZV"]);
		$arResult["CONS"] = htmlspecialcharsbx($_POST["CONS"]);
		$arResult["SOVR"] = htmlspecialcharsbx($_POST["SOVR"]);
		$arResult["STAT"] = htmlspecialcharsbx($_POST["STAT"]);
		$arResult["DIN"] = htmlspecialcharsbx($_POST["DIN"]);
		$arResult["DR"] = htmlspecialcharsbx($_POST["DR"]);
		$arResult["BCOL"] = htmlspecialcharsbx($_POST["BCOL"]);
		$arResult["NCOL"] = htmlspecialcharsbx($_POST["NCOL"]);
		$arResult["PASTEL"] = htmlspecialcharsbx($_POST["PASTEL"]);
		$arResult["MAGK"] = htmlspecialcharsbx($_POST["MAGK"]);
		$arResult["KONTR"] = htmlspecialcharsbx($_POST["KONTR"]);
		$arResult["CHIST"] = htmlspecialcharsbx($_POST["CHIST"]);
		$arResult["JARK"] = htmlspecialcharsbx($_POST["JARK"]);
		$arResult["MONOCH"] = htmlspecialcharsbx($_POST["MONOCH"]);
		$arResult["LIKE"] = htmlspecialcharsbx($_POST["LIKE"]);
		$arResult["NLIKE"] = htmlspecialcharsbx($_POST["NLIKE"]);
		$arResult["NISP"] = htmlspecialcharsbx($_POST["NISP"]);
		$arResult["LOGO"] = htmlspecialcharsbx($_POST["LOGO"]);
		$arResult["VISITKA"] = htmlspecialcharsbx($_POST["VISITKA"]);
		$arResult["FB"] = htmlspecialcharsbx($_POST["FB"]);
		$arResult["KONV"] = htmlspecialcharsbx($_POST["KONV"]);
		$arResult["PAPKI"] = htmlspecialcharsbx($_POST["PAPKI"]);
		$arResult["KAL"] = htmlspecialcharsbx($_POST["KAL"]);
		$arResult["BROSH"] = htmlspecialcharsbx($_POST["BROSH"]);
		$arResult["SLOGAN"] = htmlspecialcharsbx($_POST["SLOGAN"]);
		$arResult["PECHAT"] = htmlspecialcharsbx($_POST["PECHAT"]);
		$arResult["PAKET"] = htmlspecialcharsbx($_POST["PAKET"]);
		$arResult["BUKL"] = htmlspecialcharsbx($_POST["BUKL"]);
		$arResult["PR_LIST"] = htmlspecialcharsbx($_POST["PR_LIST"]);
		$arResult["NRECL"] = htmlspecialcharsbx($_POST["NRECL"]);
		$arResult["FLAG"] = htmlspecialcharsbx($_POST["FLAG"]);
		$arResult["ADDEL"] = htmlspecialcharsbx($_POST["ADDEL"]);
		$arResult["KONCEP"] = htmlspecialcharsbx($_POST["KONCEP"]);
		$arResult["radio-3"] = htmlspecialcharsbx($_POST["radio-3"]);
		$arResult["radio-4"] = htmlspecialcharsbx($_POST["radio-4"]);		
	}
	else
		$arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}

if(empty($arResult["ERROR_MESSAGE"]))
{
	if($USER->IsAuthorized())
	{
		$arResult["AUTHOR_NAME"] = $USER->GetFormattedName(false);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($USER->GetEmail());
	}
	else
	{
		if(strlen($_SESSION["MF_NAME"]) > 0)
			$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_NAME"]);
					if(strlen($_SESSION["MF_TEL"]) > 0)
			$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_TEL"]);
		if(strlen($_SESSION["MF_EMAIL"]) > 0)
			$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_SESSION["MF_EMAIL"]);
	}
}

if($arParams["USE_CAPTCHA"] == "Y")
	$arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
$this->IncludeComponentTemplate();
