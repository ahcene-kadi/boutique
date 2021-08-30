<?php
file_put_contents('log', print_r($_POST,true));

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
$email_account="ahcene.kadi@laplateforme.io
";

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}

// post back to PayPal system to validate
// renvoyer a paypal pour validation

$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.sandbox.paypal.com\r\n";  // www.paypal.com for a live site
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
// Affecter les variables postées aux variables locales

$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

// numero de transaction
$txn_id = $_POST['txn_id'];

//	parser la variable custom
parse_str($_POST['custom'],$custom);
/* $custom['user_id] = 1*/

if (!$fp) {
// HTTP ERROR

} else {	
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			
			// check the payment_status is Completed
			if($payment_status == "Completed"){
			 file_put_contents('log','status completed');
			 	// check that receiver_email is your Primary PayPal email
				if($email_account == $receiver_email){
					file_put_contents('log','email confirmée ');
						// check that txn_id has not been previously processed

						// if (VerifIXNID($txn_id) == 0) {

							file_put_contents('log','txn_id vérifier ');
							// check that payment_amount/payment_currency are correct

							// process payment						 
							 $uid = $custom['user_id'];

							// sauvegarder la commande  
							$data=serialize($_POST);

							/*sauvegarde des infos dans un fichier log*/
							file_put_contents('log_'.$txn_id, print_r($_POST,true));

							// connexion a la base de données
							$db = new PDO("mysql:host=localhost;dbname=base-de-donnees","root","root");
												
							$db->query("INSERT INTO orders (user_id,amount,created,datas,txn_id) VALUES(".$uid.",".$payment_amount.",NOW(),'".$data."','".$txn_id."')");

							file_put_contents('log','le paiement a bien été confirmé');

					 	// }else{
					 	// 	file_put_contents('log','Transaction déja éffectué ');
					 	// }

					}else{
				 		file_put_contents('log','le paiement ne correspond a aucune offre');
				 	}			
			}else{
				// problème sur le statut de paiement
				file_put_contents('log','Il y a un problème sur le statut de paiement');
			}
			exit();		
		}
		else if (strcmp ($res, "INVALID") == 0) {
				// log for manual investigation
				// transaction invalide
			file_put_contents('log','Transaction invalide');
		}
	}
fclose ($fp);
}
?>