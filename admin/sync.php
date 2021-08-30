<?php require 'includes/includes.php'; 
$user= $DB->query('SELECT * FROM users WHERE id=:id',array('id'=>$_GET['user']));

if(!empty($user)){
      $user=$user[0];
    }else{
      $_SESSION['erreur'] = "Client ou utilisateur introuvable dans notre base";
      header('location:clients.php');
      exit();
    }

 $mail_to =$user->email;
$mail_subject  =" Validation de votre compte d'inscription!";
$headers = "From : Mon site.fr\r\n";
$headers.="Reply-To: ahcene.kadi@lapalteforme.io \r\n";
$headers.="MIME-Version 1.0\r\n";
$headers.="Content-type: text/html;charset=utf-8\r\n";
$mail_body ='Bonjour <br/> Veuillez ciquer sur <a href="http://localhost/Eshop/activate.php?token='.$user->token.'&email='.$user->email.'"> le lien </a>pour activer votre compte ';

if(mail($mail_to,$mail_subject,$mail_body,$headers)){
	$_SESSION['message'] = " Un émail a été envoyé pour le client ";
}else{
	$_SESSION['erreur'] = "Un problème est survenu lors de l'envoi d'email !.";
}

header('location:clients.php');
exit();
