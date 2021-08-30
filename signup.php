<?php require 'includes/includes.php';
require 'includes/header.php';
 ?>

<?php 
	if(!empty($_POST)){

		if(!empty($_POST['password']) && ( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) ) ){

			$email= htmlspecialchars($_POST['email']);
			$password = sha1($_POST['password']);
			$token = sha1(uniqid(rand()));

			$nom=htmlspecialchars($_POST['nom']);
			$adresse = htmlspecialchars($_POST['adresse']);
			$ville = htmlspecialchars($_POST['ville']);
			$codepostale =htmlspecialchars($_POST['codepostale']);

			$reponse = $DB->uniqueEmail($email);

			if($reponse !=0){
				$_SESSION['erreur'] = "Adresse email déja utilisée par un membre.";
			}else{

				$data = array(
						'nom'=>$nom,
						'email'=>$email,
						'password'=>$password,
						'token'=>$token,
						'adresse'=>$adresse,
						'ville'=>$ville,
						'codepostale'=>$codepostale
					);
				$sql ='INSERT INTO users (nom,email,password,token,adresse,ville,codepostale) VALUES(:nom,:email,:password,:token,:adresse,:ville,:codepostale)';
				$req= $DB->insert($sql,$data);
				//envoi d'email.
				var_dump($email);
				$mail_to = "kapelloj4@hotmail.fr";
				$mail_subject  = "Validation de votre compte !!";
				$headers = "From : BoutiqueZ&A\r\n";
				$headers.= "Reply-To: ahcene.kadi@laplateforme.io \r\n";
				$headers.= "MIME-Version 1.0\r\n";
				$headers.= "Content-Type: text/html; charset=iso-8859-1 \r\n";//"Content-type: text/html;charset=utf-8\r\n"
				$mail_body = 'Bonjour <br/> Veuillez ciquer sur <a href="http://localhost:8888/boutiqueadministrateur/activate.php?token='.$token.'&email='.$email.'"> le lien </a>pour activer votre compte';

				if(mail($mail_to,$mail_subject,$mail_body,$headers)){
					$_SESSION['message'] = "Un émail a été envoyé a votre méssagerie avec des instructions pour activer votre compte ";
					unset($_POST);
				}else{
					$_SESSION['erreur'] = "Un problème est survenu lors de l'envoi d'email !.";
				}
			}

		}else{
			if(empty($_POST['password'])){
				$erreur_password  ='Un mot de passe est requis !.';
				$_SESSION['erreur'] = ' Veuillez corriger les érreurs .';
			}

			if(empty($_POST['email'])){
				$erreur_email  ='Le champs email est requis !';
				$_SESSION['erreur'] = ' Veuillez corriger les érreurs .';
			}else
				if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) ){
				$erreur_email  ='Adresse Email non valide !';
				$_SESSION['erreur'] = ' Veuillez corriger les érreurs .';
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

<form action="signup.php" method="post" id="signup">
	
	<h2>Inscription</h2>
	<fieldset>
		<p>
			<label for="nom">Nom :</label>
			<input type="text" name="nom" id ="nom" value="<?php echo isset($_POST['nom'])?$_POST['nom']:''; ?>">
		</p>
		<p>
			<label for="email" class="required">Email :</label>
			<input type="email" name="email" id ="email" value="<?php echo isset($_POST['email'])?$_POST['email']:''; ?>">
		</p>
		<?php if (!empty($erreur_email)): ?>
			<div class="error"><?php echo $erreur_email; ?></div> 
		<?php endif ?>
		<p>
			<label for="password"  class="required">Mot de passe :</label>
			<input type="password" name="password" id ="password">
		</p>
		<?php if (!empty($erreur_password)): ?>
			<div class="error"><?php echo $erreur_password; ?></div> 
		<?php endif ?>


	</fieldset>
	<fieldset>
		<p>
			<label for="adresse">Adresse :</label>
			<input type="text" name="adresse" id ="adresse" value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:''; ?>">
		</p>
		<p>
			<label for="ville">Ville :</label>
			<input type="text" name="ville" id ="ville" value="<?php echo isset($_POST['ville'])?$_POST['ville']:''; ?>">
		</p>
		<p>
			<label for="codepostale">Code postale :</label>
			<input type="text" name="codepostale" id ="codepostale" value="<?php echo isset($_POST['codepostale'])?$_POST['codepostale']:''; ?>">
		</p>
		

	</fieldset>
	<div class="clearfix"></div>
	<p class="information">* Champs requis</p>
	<p>
		<input type="submit" value ="S'inscrire">
	</p>
</form>

 <?php require 'includes/footer.php'; ?>
