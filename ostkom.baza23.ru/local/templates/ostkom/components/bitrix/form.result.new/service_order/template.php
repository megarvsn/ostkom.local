<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $sevice_name;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/form.result.new/service_order/lang/".$LangId."/template.php");

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
<div class="form__grid ug-grid ug-block-tablet4">
  <div class="form__col ug-col">
     <input type="<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input"  placeholder="* <?echo GetMessage("COMPANY_NAME")?>" required name="form_<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['company']['STRUCTURE'][0]['ID']?>" value=""/>
  </div>
  <div class="form__col ug-col">
     <input type="<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input"  placeholder="* <?echo GetMessage("USER_NAME")?>" required name="form_<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['user_name']['STRUCTURE'][0]['ID']?>" value=""/>
  </div>
  <div class="form__col ug-col">
     <input type="<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>" class="input"  placeholder="* <?echo GetMessage("USER_PHONE")?>" required name="form_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arResult["QUESTIONS"]['phone']['STRUCTURE'][0]['ID']?>" value=""/>
     <input type="hidden" id="department" name="form_hidden_27" value="<?=$sevice_name?>">
  </div>
</div>
<div class="form__actions u-text-center">
<input type="submit" name="web_form_submit" value="<?echo GetMessage("BUT_TEXT")?>" class="btn btn--primary">
</div>
<div class="form_required_f"><font color="red"><span class="form-required starrequired">*</span></font><?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?></div>
<div id="result_mess">
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?if($arResult["FORM_NOTE"]):?>
<?=$arResult["FORM_NOTE"]?>
<?endif;?>
</div>
<?=$arResult["FORM_FOOTER"]?>