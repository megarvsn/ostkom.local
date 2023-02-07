<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
 global $str_name;
 global $LangRu;
 global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/ost/main.feedback/actions_frend/lang/".$LangId."/template.php");
?>

<div id="action_form">
          <div class="h1"><?=GetMessage("MFT_TITLE")?></div>
<?if($arResult["OK_MESSAGE"]):?>
<?$this->SetViewTarget("okmessage");?>
          <div class="order-details module">
            <div class="order-details__title h3"><?=GetMessage("MFT_OK_MESSAGE")?></div>
		  </div>	
<?$this->EndViewTarget();?>		  
<?endif;?>
          <!-- order-->
		  <form action="<?=$APPLICATION->GetCurPage()?>" method="POST" id="order_form">
		  <?=bitrix_sessid_post()?>
		  <input type="hidden" name="section_name" value="<?=$APPLICATION->GetCurPage()?>">
		  <div class="order form module">				
            <!-- body-->
            <div class="order__body section section--fullwidth section--compact">
              <!-- personal data-->
              <div class="form__section">
                <div class="ug-grid">
                  <div class="ug-col ug-col-phablet6">
                    <div class="h3"><?=GetMessage("MFT_PERSON")?></div>
                    <!-- name-->
                    <div class="form__row">
                      <input class="input" type="text" name="user_name"  value="<?=$arResult["AUTHOR_NAME"]?>" placeholder="<?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- lastname-->
                    <div class="form__row">
                      <input class="input" type="text" value="<?=$_POST['COMPANY']?>" name="COMPANY" placeholder="<?=GetMessage("MFT_SNAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("COMPANY", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- code-->
                    <div class="form__row">
                      <input class="input" type="text" name="ABR" value="<?=$_POST['ABR']?>" placeholder="<?=GetMessage("MFT_ABR")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("ABR", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                  </div>
                  <div class="ug-col ug-col-phablet6">
                    <div class="h3"><?=GetMessage("MFT_FREND")?></div>
                    <div class="form__row">				
                      <input class="input" type="text" name="FREND_NAME" value="<?=$arResult["FREND_NAME"]?>" placeholder="<?=GetMessage("MFT_FREND_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("FREND_NAME", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <div class="form__row">				
                      <input class="input" type="text" name="FREND_SNAME" value="<?=$arResult["FREND_SNAME"]?>" placeholder="<?=GetMessage("MFT_FREND_SNAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("FREND_SNAME", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>					
                    <!-- email-->
                    <div class="form__row">				
                      <input class="input" type="text" name="user_email" placeholder="<?=GetMessage("MFT_FREND__TEL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>				
                  </div>
                </div>
              </div>
            </div>
	        <?if($arParams["USE_CAPTCHA"] == "Y"):?>
            <div class="form__row mf-captcha"><br>
                  <div class="ug-grid">
                    <div class="ug-col ug-col-phablet6">
		               <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
		               <img style="margin:0px 10px 0px 0px; float:left;height:46px" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" alt="CAPTCHA">
		               <input type="text" style="width:207px" name="captcha_word"  class="input" value="" placeholder="<?=GetMessage("MFT_CAPTCHA_CODE")?>">
                    </div>
                  </div>
            </div>				
	        <?endif;?>			
            <!-- actions-->
            <div class="order__actions form__actions">
              <!-- terms-->
              <div class="order__terms">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_private.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 			  
			  </div>
              <!-- agree-->
              <div class="order__agree">
                <div class="checkbox checkbox--plain">
                  <label>
                    <input type="checkbox" name="agree" value="да" required><i></i><span class="checkbox__text">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_agree.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 					
					</span>
                  </label>
                </div>
              </div>			  
	          <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
              <!-- submit-->
              <input class="btn btn--primary" name="submit" type="submit" value="<?=GetMessage("MFT_SUBMIT")?>"> 
            </div>
		  </div>	
	        <div class="form_required_f"><font color="red"><span class="form-required starrequired">*</span></font><?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?></div>	  	  
          </form>
          <script src="/bitrix/templates/ostkom/scripts/order.js"></script>			  
</div>	
<script type="text/javascript">
	$('#order_form input[type="submit"]').on('click', function() {
		if (!$('#order_form input[name="agree"').is(':checked')){
		   return false;	
		}
	});		
</script>