<?php require 'includes/includes.php'; ?>
<?php require 'includes/header.php'; ?>

<?php 

if(!empty($_GET) && isset($_GET['token']) && isset($_GET['email'])){

	$token = $_GET['token'];
	$email = $_GET['email'];

	$q = array(
		'email'=>$email,
		'token'=>$token
		);

	$sql = 'SELECT email,token FROM users WHERE email=:email AND token=:token';
	$reponse = $DB->query($sql,$q);

	if(!empty($reponse)){
		$q =array(
				'email'=>$email,
				'active'=>'1',
				'token'=>$token
			);

		$sql = 'SELECT email,active FROM users WHERE email=:email AND token=:token AND active=:active';
		$rep =$DB->query($sql,$q);

		if($rep){
			// déja actif
			$_SESSION['erreur'] = "Utilisateur déja actif !";
		}else{
			//activer le compte 
			
			$sql= 'UPDATE users set active=:active WHERE email=:email AND token=:token';
			$DB->insert($sql,$q);
			$_SESSION['message'] = " Votre compte est activé avec succés";
		}
	}
	else{
		// user inconnu
		$_SESSION['erruer'] =" Utilisateur inconnu dans notre base !.";
	}	
}
else{
	header('location:index.php');
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
<p>
	<a href="index.php">Retour a l'accueil du site</a>
</p>
<?php require 'includes/footer.php'; ?>