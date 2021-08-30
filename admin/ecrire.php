<?php require 'includes/includes.php'; 
  if(!empty($_GET['user'])){
    $id= intval($_GET['user']);

    $user = $DB->query("SELECT * FROM users WHERE id=:id LIMIT 1",array('id'=>$id));
    if(!empty($user)){
      $user=$user[0];
    }else{
      $_SESSION['erreur'] = "Client ou utilisateur introuvable dans notre base";
      header('location:clients.php');
      exit();
    }
  }

 if(!empty($_POST)){

  $validate =true;
  if(empty($_POST['titre'])){
    $validate =false;
    $erreur_titre = "Le champ Objet est requis";
  }

 if(empty($_POST['message'])){
    $validate =false;
    $erreur_message = "Veuillez entrer votre message";
  }

  if($validate){

    $mail_to = "ahcene.kadi@laplateforme.io";
    $mail_Subject = $_POST['titre'];
    $headers = "From : Mon site \r\n";
    $headers.= "Reply-To :votre email\r\n";
    $headers.="MIME-Version: 1.0 \r\n";
    $headers = "Content-type:text/html;charset=urf-8\r\n";

    $mail_body = nl2br($_POST['message']);

    if(mail($mail_to,$mail_Subject,$mail_body,$headers)){
       $_SESSION['message'] = "Email envoyé avec succès";
    }else{
       $_SESSION['erreur']  =" Un problème est survenu lors de l'envoi d'email";
    }

    header('location:clients.php');
    exit();
  }else{
     $_SESSION['erreur']  ="Veuillez corriger les érreurs indiquées ci dessous ";
  }
 }


 require 'includes/header.php'; ?>
<!-- message de session -->
<?php if (isset($_SESSION['message'])): ?>
  <div class="message"> <?php echo $_SESSION['message']; ?></div>
  <?php unset($_SESSION['message']) ?>
<?php endif ?>
<?php if (isset($_SESSION['erreur'])): ?>
  <div class="errorMessage"> <?php echo $_SESSION['erreur']; ?></div>
  <?php unset($_SESSION['erreur']) ?>
<?php endif ?>
     <h2>Ecrire pour le  client</h2>

     <p>Nom : <?php echo $user->nom; ?></p>
      <p>Email : <?php echo $user->email; ?></p>

     <form action ="ecrire.php?user=<?php echo $_GET['user']; ?>" method="POST" id="signup">
        <input type="hidden" name="id" value="<?php echo $_GET['user']; ?>">


         <p>
          <label for="titre">Objet </label>
          <input type="text" name="titre" value="<?php echo isset($_POST['titre'])?$_POST['titre']:''; ?>">
        </p>
        <?php if (!empty($erreur_titre)): ?>
          <div class="error"><?php echo $erreur_titre; ?></div>
        <?php endif ?>

         <p>
          <label for="message">Message </label>
          <textarea name="message"> <?php echo isset($_POST['message'])?$_POST['message']:''; ?></textarea>
        </p>
        <?php if (!empty($erreur_message)): ?>
          <div class="error"><?php echo $erreur_message; ?></div>
        <?php endif ?>

       
        <p>
            <input type="submit" value="Envoyer">
        </p>
     </form>

      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>