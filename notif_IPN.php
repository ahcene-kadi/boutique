<?php

// PHP 4.1
// fonction pour vérifier l'unicité de txn_id
function VerifIXNID($txn_id){
			file_put_contents('log','Vérification txn_id ');
			$db = new PDO("mysql:host=localhost;dbname=base-de-donnees","root","root");
         	$nbreponse = $db->query("SELECT COUNT(*) FROM orders WHERE txn_id=".$txn_id)->fetchColumn();

           if($nbreponse > 0 ){
               return 1;
           }else{
              return 0;
          }
      }
// 


$emailto = $_POST['payer_email'];
$emailFrom = "ahcene.kadi@laplateforme.io";
$sujetprefix = "[PAYPAL]";
$charset  ="utf-8";

$email_account = "ahcene.kadi@laplateforme.io

";

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.sandbox.paypal.com\r\n";  // www.paypal.com for a live site
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];


$num_cart_items = intval($_POST['num_cart_items']);

parse_str($_POST['custom'],$custom);


if (!$fp) {
// HTTP ERROR
} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			// check the payment_status is Completed			
			// check that receiver_email is your Primary PayPal email
			// check that txn_id has not been previously processed			
			// check that payment_amount/payment_currency are correct
			if($payment_status == "Completed"){
				file_put_contents('log','status completed');

				// verifier l'id de la tansaction
				if($email_account == $receiver_email){
					file_put_contents('log','email confirmée');

					$uid = $custom['user_id'];

					$data = serialize($_POST);

					$db = new PDO("mysql:host=localhost;dbname=base-de-donnees","root","root");

					$db->query("INSERT INTO orders (user_id,amount,created,datas,txn_id) VALUES (".$uid.",".$payment_amount.",NOW(),'".$data."','".$txn_id."')");

					// envoi d'email
					$mail_TO = $emailto;
					$mail_Subject = "Votre commande sur notre boutique";

					$entetes = "From: ".$emailFrom."\r\n";
					$entetes .= "Reply-To: ".$emailFrom."\r\n";
					$entetes .= "MIME-Version: 1.0 \r\n";
					$entetes .= "Content-Transfer-Encoding: 8bit \n";
					$entetes .= "Content-Type:text/html; charset=".$charset."\r\n";

					$mail_body = "Bonjour ,<br/> Merci d'avoir choisi notre site pour faire vos achats,votre commande partira dans les plus brèfs délais.";

					$mail($mail_TO,$mail_Subject,$mail_body,$entetes);

				}else{
					file_put_contents('log',"le paiement ne correspond a aucune offre");
				}
				
			}
			else{
				file_put_contents('log',"il y'a un problème sur le status de paiment.");
			}
			// process payment
			exit();
		}
		else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
			file_put_contents('log',"Transaction invalide.");
		}
	}
	fclose ($fp);
}
?>





