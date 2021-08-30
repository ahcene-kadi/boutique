<?php require 'includes/includes.php';
if(!Auth::islog($DB)){
		header('location:index.php'); 
    exit(); 			
  	}
if(!empty($_POST)){
    $validate = true;

    // valider l'email
    if(empty($_POST['email'])){
        $validate = false;
        $erreur_email = 'Le champ email est requis !';
      }else
          if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $validate = false;
                $erreur_email = "Veuillez entrer une adresse email valide.";
          }
          // VÉRIFIER LE PASSWORD
    if(empty($_POST['password'])){
      $password = $_SESSION['user']['password'];
    }elseif(empty($_POST['confirm_password'])){
        $erreur_password = "Confirmer votre mot de passe";
        $validate =false;
      }elseif ($_POST['confirm_password'] != $_POST['password']){
        $erreur_password = "Le mot de passe et le mot de passe confirmation sont différents.";
        $validate =false;
      }else{
        $password = sha1($_POST['password']);
      }

    if($validate){
       $data = array(
            'id'=>$_SESSION['user']['id'],
            'nom'          =>$_POST['nom'],
            'email'   =>$_POST['email'],
            'adresse'   =>$_POST['adresse'],
            'ville'   =>$_POST['ville'],
            'codepostale'   =>$_POST['codepostale'],
            'password'=>$password
            );
       
        $rep = $DB->insert('UPDATE users SET nom=:nom,email=:email,adresse=:adresse,ville=:ville,codepostale=:codepostale,password=:password WHERE id=:id',$data);
        if($rep){
            $_SESSION['message'] = "Votre profil a été mis à jour avec succès .";
            $_SESSION['user'] = array_merge($_SESSION['user'],$data);
            header('location:profil.php');
            exit();
        }else{
            $_SESSION['erreur'] = "Un problème est survenu lors de la sauvegarde !.";
        }
    }else{
        $_SESSION['erreur'] = "Veuillez corriger les érreurs indiquées ci dessous .";
    }
    
}

$orders  = $DB->query("SELECT * from orders WHERE user_id=:id",array('id'=>$_SESSION['user']['id']));
?>
<?php require 'includes/header.php'; 
	
?>

 <!-- Message dans la session -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="message"> <?php echo $_SESSION['message']; ?></div>
    <?php unset( $_SESSION['message']); ?>
  <?php endif ?>
   <?php if (isset($_SESSION['erreur'])): ?>
    <div class="errorMessage"> <?php echo $_SESSION['erreur']; ?></div>
    <?php unset( $_SESSION['erreur']); ?>
  <?php endif ?>

<h2>Votre profil</h2>
      <form action ="profil.php" method="POST" id="signup" class="profil">
       
            <input type="hidden" name="id" value ="<?php echo $_SESSION['user']['id']; ?>">

      <fieldset>
            <p>
                <label for="nom">Nom </label>
                <input type ="text" name="nom" value="<?php echo isset($_POST['nom'])?$_POST['nom']:$_SESSION['user']['nom']; ?>">
           </p>
             <?php if (!empty($erreur_nom)): ?>
              <div class="error"><?php echo $erreur_nom; ?></div>
            <?php endif ?>
            <p>
                <label for="email">Email</label>
                <input type ="text" name="email" value="<?php echo isset($_POST['email'])?$_POST['email']:$_SESSION['user']['email']; ?>">
           </p>
             <?php if (!empty($erreur_email)): ?>
              <div class="error"><?php echo $erreur_email; ?></div>
            <?php endif ?>
           <p>
                <label for="password">password</label>
                <input type ="text" name="password" >
           </p>
             <?php if (!empty($erreur_password)): ?>
              <div class="error"><?php echo $erreur_password; ?></div>
            <?php endif ?>
            <p>
                <label for="confirm_password">Confirmation de mot de passe</label>
                <input type ="text" name="confirm_password" >
           </p>
             <?php if (!empty($erreur_confirm_password)): ?>
              <div class="error"><?php echo $erreur_confirm_password; ?></div>
            <?php endif ?>
      </fieldset>
       <fieldset>
             <p>
                <label for="adresse">Adresse</label>
                <input type ="text" name="adresse" value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:$_SESSION['user']['adresse']; ?>">
           </p>
          
             <p>
                <label for="ville">Ville</label>
                <input type ="text" name="ville" value="<?php echo isset($_POST['ville'])?$_POST['ville']:$_SESSION['user']['ville']; ?>">
            </p>
            
             <p>
                <label for="codepostale">Code postale</label>
                <input type ="text" name="codepostale" value="<?php echo isset($_POST['codepostale'])?$_POST['codepostale']:$_SESSION['user']['codepostale']; ?>">
            </p>
          
       </fieldset>
        <div class="clearfix"></div>      
        <p>
             <input type="submit" value ="Enregistrer">
         </p>
      </form>

      <h2>Les achats du clients : </h2>
     
        <table>
          <thead>
              <tr>
                  <th>N° commande</th>
                  <th>Date</th>
                  <th>Montant</th>
                  <th>Txn_id</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($orders as $order): ?>
                <tr>
                  <td><?php echo $order->id ?></td>
                  <td><?php echo $order->created; ?></td>
                  <td><?php echo $order->amount; ?></td>
                  <td><?php echo $order->txn_id; ?></td>
                  <td>
                    <a href="editOrder.php?id=<?php echo $order->id; ?>"  class="edit"></a>
                    <a href="pdfOrder.php?id=<?php echo $order->id; ?>"  class="pdf"></a>
                  </td>
              </tr>
              <?php endforeach ?>
          </tbody>
        </table>

<?php require 'includes/footer.php'; ?>