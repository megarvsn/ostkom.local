<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/system.auth.forgotpasswd/.default/lang/".$LangId."/template.php");
if($arParams['AUTH_RESULT']['TYPE']=="OK")
	 echo GetMessage("RESULT_OR");
if($arParams['AUTH_RESULT']['TYPE']=="ERROR") 
	echo GetMessage("RESULT_ERROR");
//echo"<pre>";   print_r($arResult);    echo"</pre>";
?>
<div class="account-form">
<form class="auth_form_bx" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>
    <div class="account-form__title h5"><?=GetMessage("AUTH_GET_CHECK_STRING")?></div>
    <div class="form__row">
	    <label><?=GetMessage("AUTH_LOGIN")?></label>
		<input class="input" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<?=GetMessage("AUTH_OR")?>
	</div>
    <div class="form__row">
		<label><?=GetMessage("AUTH_EMAIL")?></label>
		<input class="input" type="text" name="USER_EMAIL" maxlength="255" />
	</div>
	<?if($arResult["USE_CAPTCHA"]):?>
    <div class="form__row">
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
		<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br>
		<label><?echo GetMessage("system_auth_captcha")?></label>
		<input class="input"type="text" name="captcha_word" maxlength="50" value="" />
	</div>
	<?endif?>
    <div class="account-form__actions">
		<input class="btn btn--primary" type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" style="margin-left:60px"/>
    </div>
    <div class="form__row"><br>
        <a href="<?=$arResult["AUTH_AUTH_URL"]?>"> <?=GetMessage("AUTH_AUTH")?></a><br><br>
    </div>
</form>
</div>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
