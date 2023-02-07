<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Состояние Вашего счета");
?>
<form action="" method="post">
 <div class="box_form_contract">
	 <table cellspacing="0" cellpadding="0">
	  <tbody>
	   <tr>
	    <td>Введите Персональный код</td>
	   </tr>
	   <tr>
	    <td><input type="text" name="personalcode" class="box_input" autocomplete="off" value="<?=$_POST['personalcode']?>"></td>
	   </tr>
	   <tr>
	    <td>Введите № Договора</td>
	   </tr>
	   <tr>
	    <td><input type="password" name="contract" class="box_input" autocomplete="off" value="<?=$_POST['contract']?>"></td>
	   </tr>
	   <tr>
	    <td><div class="box_submit"><input type="submit" value="Отправить"></div></td>
	   </tr>
	   <tr>
	    <td>
	    </td>
	   </tr>
	  </tbody>
	 </table>
 </div>
 <div class="box_form_second"></div>
 <div class="box_form_third"></div>
</form>
<?if($_POST):?>
<? 
   require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/invoice/import_invoices.php" );
   global $invoice_dir;
   $content=check_invoice($_POST['personalcode'],$_POST['contract']);
?>   
   <div class="contract_result">
       <?if($content==0):?>
	     Введен неверный Персональный Код
	   <?elseif($content==1):?>
	   	 Введен неверный № Договора
	   <?elseif(is_array($content)):?>	
	     <b>Найдено:</b>
<?//echo"<pre>"; print_r($content);  echo"</pre>";	?>		 
	      <?foreach($content as $account => $invoice):?>
		     <div><a href="/sostoyanie-vashego-scheta/invoice.php?f=<?=$invoice?>">Скачать в PDF</a></div>
		  <?endforeach;?>
       <?endif;?>  	   
   </div>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>