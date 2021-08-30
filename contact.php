<?php require 'includes/includes.php';
require 'includes/header.php';
 ?>

<?php 
	if(isset($_POST['submit']) && $_POST['submit']=='Envoyer'){
		$valide =true ;
		if(empty($_POST['nom'])){
			$erreur_nom ="Veuillez indiquer votre nom.";
		 	$valide =false;
		}

		if(empty($_POST['email'])){
			$erreur_email ="Veuillez indiquer votre email.";
		 	$valide =false;
		}

		if(!empty($_POST['email']) && !(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))){
			$erreur_email ="Veuillez indiquer une adresse email valide.";
		 	$valide =false;
		}

		if(empty($_POST['message'])){
			$erreur_message ="Veuillez indiquer votre méssage.";
		 	$valide =false;
		}

		if($valide){
			// envoi d'email

				$mail_to ="ahcene.kadi@laplateforme.io";
				$mail_subject ="une question d'un client depuis la boutique";
				$headers ="From :".$_POST['email']."\r\n";
				$headers.="Reply-To:".$_POST['email']."\r\n";
				$headers.="MIME-Version 1.0\r\n";
				$headers.="Content-Type: text/html; charset=iso-8859-1 \r\n";
				$mail_body ='voici le méssage du client'.' '.nl2br($_POST['message']);

				if(mail($mail_to,$mail_subject,$mail_body,$headers)){
					$_SESSION['message'] = " votre émail a bien été envoyé ";
					header('location:index.php');
					unset($_POST);

				}else{
					$_SESSION['erreur'] = "Un problème est survenu lors de l'envoi d'email !.";
					header('location:contact.php');
				}
		}

	}
 ?>
 <!-- message de session -->
<?php if (isset($_SESSION['message'])): ?>
  <div class="message"> <?php echo $_SESSION['message']; ?></div>
  <?php unset($_SESSION['message']) ?>
<?php endif ?>
<?php if (isset($_SESSION['erreur'])): ?>
  <div class="errorMessage"> <?php echo $_SESSION['erreur']; ?></div>
  <?php unset($_SESSION['erreur']) ?>
<?php endif ?>

<!-- formulaire d'inscription  -->

<form action="contact.php" method="post" id="contact">
	
	<h2>Nous Contacter</h2>

		<p>
			<label for="nom">Nom :</label>
			<input type="text" name="nom" id ="nom" value="<?php echo isset($_POST['nom'])?$_POST['nom']:''; ?>">
		</p>
		<?php if (!empty($erreur_nom)): ?>
			<div class="error"><?php echo $erreur_nom; ?></div> 
		<?php endif ?>
		<p>
			<label for="email" >Email :</label>
			<input type="email" name="email" id ="email" value="<?php echo isset($_POST['email'])?$_POST['email']:''; ?>">
		</p>
		<?php if (!empty($erreur_email)): ?>
			<div class="error"><?php echo $erreur_email; ?></div> 
		<?php endif ?>
		
		<p>
			<label for="message">Votre méssage:</label>
			<textarea name="message" id="message" ><?php echo isset($_POST['message'])?$_POST['message']:''; ?></textarea>
		</p>
		<?php if (!empty($erreur_message)): ?>
			<div class="error"><?php echo $erreur_message; ?></div> 
		<?php endif ?>

	<p>
		<input type="submit" name="submit" value ="Envoyer">
	</p>
</form>

 <?php require 'includes/footer.php'; ?>