<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/form.result.new/callback_form/lang/".$LangId."/template.php");

$useFakeField = true;
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?=$arResult["FORM_HEADER"]?>
    <div id="callback_form" class="recall__form form">
          <div class="form__row">
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
		else
		{
	?>
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
				<span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
			<?endif;?>
			<?if($FIELD_SID=="new_field_43969"):?>
			    <input type="<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>" class="input" placeholder="<?echo GetMessage("AUTH_PHONE")?>" required name="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>" value=""/>
			<?else:?>
			<?=$arQuestion["HTML_CODE"]?>
			<?endif;?>
	<?
		}
	}
	?>
          </div>
<?
if($arResult["isUseCaptcha"] == "Y")
{
?>
          <div class="form__row">
			 <b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b><br>
			<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
            <?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?>
			<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
          </div>
<?
}
?>
          <div class="form__actions">
            <input type="submit" name="web_form_submit" value="<?echo GetMessage("AUTH_ABONENT")?>" class="btn btn--primary customer">
			<input type="submit" name="web_form_submit" value="<?echo GetMessage("AUTH_TECHNICAL")?>" class="btn btn--primary technical">
		  </div>
<?=$arResult["FORM_FOOTER"]?>
          <div id="result_mess">
<?=$arResult["FORM_NOTE"]?>
          </div>
    </div>
<script type="text/javascript">
$(document).ready(function(){
    $('form[name="SIMPLE_FORM_CALLBACK"] .btn').on('click', function() {
		$(this).parents("form").find("#department").val($(this).val());
		if($(this).hasClass("customer"))
		   $(this).parents("form").find("#id_temp").val("customer");
        if($(this).hasClass("technical"))
		   $(this).parents("form").find("#id_temp").val("technical");
    });
});
</script>