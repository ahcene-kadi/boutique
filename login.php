<?php require 'includes/includes.php';

 ?>
<?php 

if(isset($_GET['logout'])){
	if(isset($_SESSION['user'])){
		unset($_SESSION['user']);
	}

	$_SESSION['message'] = "Vous êtes maintenant déconnecté.  A bientôt!";
}
	
	if(isset($_POST) && !empty($_POST['password']) && !empty($_POST['email'])){

		$email = htmlspecialchars($_POST['email']);
		// modifier sha1 par Auth::hashPassword();
		$password = sha1($_POST['password']);

		$data = array(
				'email'=>$email,
				'password'=>$password
			);

		$sql = 'SELECT * FROM users WHERE email=:email AND password=:password limit 1';
		$req = $DB->tquery($sql,$data);
		if(!empty($req)){
			// user existe
			if($req[0]['active'] == 1){
				$_SESSION['user'] = $req[0];
				$_SESSION['user']['role'] = sha1($_SESSION['user']['role']);
				$_SESSION['messasge'] = "Bienvenue , Vous êtes maintenant connecté .";
				header('location:index.php');
			}else{
				$_SESSION['erreur'] = "Compte user non actif ,veuillez vérifier votre méssagerie pour activer le compte";
			}
		}
		else{
			$_SESSION['erreur'] = "Votre email et/ou mot de passe sont invalides !.";
		}

	}
 require 'includes/header.php';?>

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

<form action="login.php" method="post" id="login">
	
	<h2>Connexion</h2>

		<p>
			<label for="email" >Email :</label>
			<input type="email" name="email" id ="email" value="<?php echo isset($_POST['email'])?$_POST['email']:''; ?>">
		</p>
		<?php if (!empty($erreur_email)): ?>
			<div class="error"><?php echo $erreur_email; ?></div> 
		<?php endif ?>
		<p>
			<label for="password" >Mot de passe :</label>
			<input type="password" name="password" id ="password">
		</p>
		<?php if (!empty($erreur_password)): ?>
			<div class="error"><?php echo $erreur_password; ?></div> 
		<?php endif ?>
	<p>
		<input type="submit" value ="Se connecter">
	</p>
</form>

 <?php require 'includes/footer.php'; ?>