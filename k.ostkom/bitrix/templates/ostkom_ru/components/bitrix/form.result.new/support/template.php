<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/form.result.new/support/lang/".$LangId."/template.php");

$useFakeField = true;
?>
<?=$arResult["FORM_HEADER"]?>
<?
if ($useFakeField) {
    $cssStyles = 'position: absolute;width: 1px;height: 1px;margin: -1px;border: 0;padding: 0;clip: rect(0 0 0 0);overflow: hidden;';
    ?><div class="form-group fake-field" style="<?= $cssStyles ?>"><?
        $fakeFieldName = \Baza23\WebForms::FAKE_FIELD_NAME;
        $fakeFieldLabel = "";
        $fakeFieldValue = $arResult["arrVALUES"][$fakeFieldName];
        $fakeFieldRequired = false;
        $suff = randString(4);

        ?><label class="form-label" id="<?= $fakeFieldName ?>-<?= $suff ?>-label" for="<?= $fakeFieldName ?>-<?= $suff ?>"><?= $label ?></label><?
        ?><input class="form-control" type="text" name="<?= $fakeFieldName ?>" placeholder="<?= $fakeFieldLabel ?>" value="<?= $fakeFieldValue ?>" maxlength="100" id="<?= $fakeFieldName ?>-<?= $suff ?>" aria-labelledby="<?= $fakeFieldName ?>-<?= $suff ?>-label"<? if ($fakeFieldRequired) { ?> required="required"<? } ?>><?
    ?></div><?
}
?>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
		{
			echo $arQuestion["HTML_CODE"];
		}
	}
	?>
          <div class="support form module" action="">
            <ul class="support__tabs">
              <li class="support__tabs-item"><a class="support__tabs-link is-current" href="1"><?echo GetMessage("TABS_SERVICES")?></a></li>
              <li class="support__tabs-item"><a class="support__tabs-link" href="2"><?echo GetMessage("TABS_TECH")?></a></li>
              <li class="support__tabs-item"><a class="support__tabs-link" href="3"><?echo GetMessage("TABS_QUESCH")?></a></li>
            </ul>
            <!-- body-->
            <div class="support__body section section--fullwidth section--compact">
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?if($arResult["FORM_NOTE"]):?>
<div class="form_note">
<?=$arResult["FORM_NOTE"]?>
</div>
<?endif;?>
              <!-- type-->
              <div class="support__type form__section">
			  <?foreach($arResult["QUESTIONS"]['type']['STRUCTURE'] as $k=>$item):?>
                <div class="radio radio--plain">
                  <label>
                    <input type="radio" name="form_radio_type" id="<?=$item['ID']?>" value="<?=$item['ID']?>" <?=$item['FIELD_PARAM']?>><i></i><span class="radio__text"><?echo GetMessage("FORM_TYPE_".$item['ID'])?></span>
                  </label>
                </div>
              <?endforeach;?>
              </div>
              <!-- fields-->
              <div class="ug-grid">
                <div class="ug-col ug-col-phablet6">
                  <div class="h3"><?echo GetMessage("FORM_TITLE1")?></div>
                  <!-- address-->
                  <div class="form__row">
				    <input type="<?=$arResult["QUESTIONS"]['address']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_ADDRESS")?><?if($arResult["QUESTIONS"]['address']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['address']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['address']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['address']['STRUCTURE'][0]['ID']?>" value="" size="0">
                  </div>
                  <!-- message-->
                  <div class="form__row">
                    <textarea name="form_<?=$arResult["QUESTIONS"]['message']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['message']['STRUCTURE'][0]['ID']?>" cols="40" rows="5" class="support__message input" placeholder="<?echo GetMessage("FORM_MESSAGE")?><?if($arResult["QUESTIONS"]['message']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['message']['REQUIRED']=="Y"):?> required=""<?endif;?>></textarea>
                  </div>
                </div>
                <div class="ug-col ug-col-phablet6">
                  <div class="h3"><?echo GetMessage("FORM_TITLE2")?></div>
                  <!-- name-->
                  <div class="form__row">
				    <input type="<?=$arResult["QUESTIONS"]['name']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_NAME")?><?if($arResult["QUESTIONS"]['name']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['name']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['name']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['name']['STRUCTURE'][0]['ID']?>" value="" size="0">
                  </div>
                  <!-- phone-->
                  <div class="form__row">
				    <input type="<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_PHONE")?><?if($arResult["QUESTIONS"]['phone']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['phone']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['ID']?>" value="" size="0">
                  </div>
                  <!-- email-->
                  <div class="form__row">
				    <input type="<?=$arResult["QUESTIONS"]['email']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("FORM_EMAIL")?><?if($arResult["QUESTIONS"]['email']['REQUIRED']=="Y"):?>*<?endif;?>"<?if($arResult["QUESTIONS"]['email']['REQUIRED']=="Y"):?> required=""<?endif;?> name="form_<?=$arResult["QUESTIONS"]['email']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['email']['STRUCTURE'][0]['ID']?>" value="" size="0">
                  </div>
                  <!-- subscriber-->
                  <div class="form__row">
                    <div class="checkbox checkbox--plain">
                      <label>
					  <input type="checkbox" id="20" name="form_checkbox_subscriber[]" value="20">
                     <i></i><span class="checkbox__text"><?=GetMessage("FORM_subscriber")?></span>
                      </label>
                    </div>
                  </div>
<?
if($arResult["isUseCaptcha"] == "Y")
{
?>

			      <div class="form__row" id="captcha">
			        <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
					<!--<div><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></div>-->
					<input class="input inputtext" type="text" name="captcha_word" value=""/>
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="46" />
				  </div>

<?
}
?>
                </div>
              </div>
            </div>
            <!-- actions-->
            <div class="support__actions form__actions">
              <!-- terms-->
              <div class="support__terms">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_private.php"),
			Array(),
			Array("MODE"=>"html")
		);?>
			  </div>
              <!-- agree-->
              <div class="support__agree">
                <div class="checkbox checkbox--plain">
                  <label>
                    <input type="checkbox" required="" id="21" name="form_checkbox_agree" value="21"><i></i><span class="checkbox__text">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_agree.php"),
			Array(),
			Array("MODE"=>"html")
		);?>
					</span>
                  </label>
                </div>
              </div>
              <!-- submit-->
			  <input class="btn btn--primary" type="submit" name="web_form_submit" value="<?=GetMessage("FORM_SUBMIT")?>"/>
            </div>
	<div class="form_required_f"><?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?></div>
	<?=$arResult["FORM_FOOTER"]?>
          </div>
<script type="text/javascript">
    $('.support__tabs li a').on('click', function() {
	    $('.support__tabs li a.is-current').removeClass("is-current");
        $(this).addClass("is-current");
		$('#id_department').val($(this).attr('href'));
		$('#support_department').val($(this).html());
		return false;
    });
	$('form[name="SIMPLE_FORM_SUPPORT"] input[type="submit"]').on('click', function() {
		if (!$('form[name="SIMPLE_FORM_SUPPORT"] input[name="form_checkbox_agree"]').is(':checked')){
		   return false;
		}
	});
</script>