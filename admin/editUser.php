<?php require 'includes/includes.php'; 
  if(!empty($_GET['id'])){
    $id= intval($_GET['id']);

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
  if(empty($_POST['email'])){
    $validate =false;
    $erreur_email = "Le champ email est requis";
  }elseif(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
      $validate =false;
      $erreur_email = "Veuillez entrer une adresse email valide";
  }

  if($validate){
    if(isset($_POST['active'])){
        $active =1;
    }else
      $active =0;

    $data = array(
        'id'=>$_POST['id'],
         'nom'=>$_POST['nom'],
         'email'=>$_POST['email'],
         'active'=>$active,
         'adresse'=>$_POST['adresse'],
         'ville'=>$_POST['ville'],
         'codepostale'=>$_POST['codepostale'],
         'role'=>$_POST['role'],
      );

   $rep =$DB->insert('UPDATE users SET nom=:nom,email=:email,active=:active,adresse=:adresse,ville=:ville,codepostale=:codepostale,role=:role WHERE id=:id',$data);
   if($rep){
      $_SESSION['message'] = "Le client a été mis à jour avec succès";
      header('location:clients.php');
      exit();
   }else{
      $_SESSION['erreur']  =" Un problème est survenu lors de la sauvegarde";
   }
  }else{
     $_SESSION['erreur']  ="Veuillez corriger les érreurs indiquées ci dessous ";
  }
 }


$orders = $DB->query("SELECT * FROM orders WHERE user_id=:id",array('id'=>$id));

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
     <h2>Editer un client</h2>
     <form action ="editUser.php?id=<?php echo $_GET['id']; ?>" method="POST" id="signup">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

        <fieldset>
         <p>
          <label for="nom">Nom </label>
          <input type="text" name="nom" value="<?php echo isset($_POST['nom'])?$_POST['nom']:$user->nom; ?>">
        </p>
        <?php if (!empty($erreur_nom)): ?>
          <div class="error"><?php echo $erreur_nom; ?></div>
        <?php endif ?>

         <p>
          <label for="email">Email </label>
          <input type="text" name="email" value="<?php echo isset($_POST['email'])?$_POST['email']:$user->email; ?>">
        </p>
        <?php if (!empty($erreur_email)): ?>
          <div class="error"><?php echo $erreur_email; ?></div>
        <?php endif ?>

        <p>
          <label for="active">Active </label>
          <input type="checkbox" name="active" <?php echo ($user->active ==1)?'checked="checked" ':'' ?> 
          value="<?php echo $user->active ?>">
        </p>


        </fieldset>
        <fieldset>
           <p>
          <label for="adresse">Adresse </label>
          <input type="text" name="adresse" value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:$user->adresse; ?>">
        </p>
        <?php if (!empty($erreur_adresse)): ?>
          <div class="error"><?php echo $erreur_adresse; ?></div>
        <?php endif ?>

        <p>
          <label for="ville">ville </label>
          <input type="text" name="ville" value="<?php echo isset($_POST['ville'])?$_POST['ville']:$user->ville; ?>">
        </p>
        <?php if (!empty($erreur_ville)): ?>
          <div class="error"><?php echo $erreur_ville; ?></div>
        <?php endif ?>

        <p>
          <label for="codepostale">codepostale </label>
          <input type="text" name="codepostale" value="<?php echo isset($_POST['codepostale'])?$_POST['codepostale']:$user->codepostale; ?>">
        </p>
        <?php if (!empty($erreur_codepostale)): ?>
          <div class="error"><?php echo $erreur_codepostale; ?></div>
        <?php endif ?>

        </fieldset>
         <p>
          <label for="role">role </label>
          <input type="text" name="role" value="<?php echo isset($_POST['role'])?$_POST['role']:$user->role; ?>">
        </p>
        <p>
            <input type="submit" value="Enregistrer">
        </p>
     </form>
     <h2>les achats du client</h2>
    <table>
      <thead>
        <tr>
          <th>N°Commande</th>
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
             <td><?php echo $order->created ?></td>
              <td><?php echo $order->amount ?></td>
               <td><?php echo $order->txn_id ?></td>
                <td>  
                  <a href="editOrder.php?id=<?php echo $order->id ?>" class="edit"></a>
                   <a href="pdfOrder.php?id=<?php echo $order->id ?>" class="pdf"></a>
                </td>

          </tr>
        <?php endforeach ?>
      </tbody>

    </table>
    

      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>