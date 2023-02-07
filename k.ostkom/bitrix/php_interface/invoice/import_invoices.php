<?
define('ENCRYPTION_KEY', 'r5y6g');
global $invoice_dir;
$invoice_dir="/upload/invoices/PDF/";
function check_invoice($login,$pass){	
   $j_file = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/upload/invoices/current-invoices-list.json' ); 
   $data = json_decode($j_file,true);	
   $ArrUsersNew=array();
   foreach($data as $user){   	   
       $arr=array(); 	
	   foreach($user as $id => $item){
		   $arr['login']=$id;
	       foreach($item as $accounts){
			   foreach($accounts as $account => $invoice){
				   $arr['password']=$account;
				   $arr['invoice']=$invoice;
	           }
		   }
		   $ArrUsers[$id]=$arr;
	   }
   }   
   if(!$ArrUsers[$login]){
	   return(0); 
   }	
   elseif($ArrUsers[$login] AND $ArrUsers[$login]['password']!=$pass){
	   return(1);
   }
   else{
	   $ar_pdf=array();
	   foreach($ArrUsers[$login]['invoice'] as $f_pdf){
//		   list($p1,$p2,$p3,$p4,$p5)= split ("-", $f_pdf, 5);
		   list($p1,$p2,$p3,$p4,$p5)= explode("-", $f_pdf, 5);
		   $p5=substr($p5, 0, -4);
		   $ar_pdf[$p5]=encode($f_pdf."/".$arr['password'], ENCRYPTION_KEY);
	   }			  
	   return $ar_pdf;
   }	   
}

 
function encode($unencoded,$key){ 
$string=base64_encode($unencoded); 

$arr=array();
$x=0;
while ($x++< strlen($string)) { 
$arr[$x-1] = md5(md5($key.$string[$x-1]).$key); 
$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2]; 
}
return $newstr; 
}

function decode($encoded, $key){
$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";
$x=0;
while ($x++<= strlen($strofsym)) {
$tmp = md5(md5($key.$strofsym[$x-1]).$key);
$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);
}
return base64_decode($encoded);
}

?>
