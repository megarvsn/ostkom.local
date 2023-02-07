<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Jūsu konta statuss");
?>
<form action="" method="post">
 <div class="box_form_contract">
	 <table cellspacing="0" cellpadding="0">
	  <tbody>
	   <tr>
	    <td>Ievadiet personas kodu</td>
	   </tr>
	   <tr>
	    <td><input type="text" name="personalcode" class="box_input" autocomplete="off" value="<?=$_POST['personalcode']?>"></td>
	   </tr>
	   <tr>
	    <td>Ievadiet līguma Nr.</td>
	   </tr>
	   <tr>
	    <td><input type="password" name="contract" class="box_input" autocomplete="off" value="<?=$_POST['contract']?>"></td>
	   </tr>
	   <tr>
	    <td><div class="box_submit"><input type="submit" value="Sūtīt"></div></td>
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
	     Ierakstīts nederīgs personas kods
	   <?elseif($content==1):?>
	   	 Nepareizs līguma numurs nav ievadīts
	   <?elseif(is_array($content)):?>	
	     <b>Atrasts:</b>
	      <?foreach($content as $invoice):?>
<?$account= explode(".", $invoice);?>
		     <div><a href="/sostoyanie-vashego-scheta/invoice.php?f=<?=$invoice?>">Lejupielādējiet kā PDF</a></div>
		  <?endforeach;?>
       <?endif;?>  	   
   </div>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>