<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/form.result.new/feedback_form/lang/".$LangId."/template.php");
?>

            <form class="more-info__form form" action="">
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>	
<?if($arResult["FORM_NOTE"]):?>
<div class="form_note">
<?=$arResult["FORM_NOTE"]?>
</div>	
<?endif;?>		
              <div class="form__grid ug-grid ug-block-tablet4">
                <!-- company-->
                <div class="form__col ug-col">
				 <input type="<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_COMPANY")?><?if($arResult["QUESTIONS"]['company']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['company']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['ID']?>" value="" size="0">				
                </div>
                <!-- name-->
                <div class="form__col ug-col">
				 <input type="<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_NAME")?><?if($arResult["QUESTIONS"]['user_name']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['user_name']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['ID']?>" value="" size="0">								  
                </div>
                <!-- phone-->
                <div class="form__col ug-col">
				 <input type="<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_PHONE")?><?if($arResult["QUESTIONS"]['phone']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['phone']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['ID']?>" value="" size="0">					
				  <?=str_replace("<br>", "",$arResult["QUESTIONS"]['phone']['HTML_CODE'])?>
                </div>
              </div>			  
              <!-- actions-->
              <div class="form__actions u-text-center">
			     <input class="btn btn--primary" type="submit" name="web_form_submit" value="<?echo GetMessage("FORM_SUBMIT")?>"/>		
              </div>
            </form>	