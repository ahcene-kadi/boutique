<?php require 'includes/includes.php'; 

if(!empty($_POST)){
  $validate =true;
  if(empty($_POST['name'])){
    $validate =false;
    $erreur_name="Le nom du produit est requis.";
  }

  // validation
  if($validate){
    $data=array(
        'name'=>$_POST['name'],
      );
    $rep = $DB->insert("INSERT INTO categories (name) VALUES (:name) ",$data);
    if($rep){
      $_SESSION['message']="La catégorie à été ajoutée avec succès";
      header('location:categories.php');
      exit();
    }else{
         $_SESSION['erreur'] ="Un problème est survenu lors de la sauvegarde";
    }
  }else{
       $_SESSION['erreur'] ="Veuillez corriger les érreurs indiquées ci dessous";
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
    <h2>Ajouter une catégorie </h2>
     <form action="addCat.php" method="POST">
      <p>
        <label for="name">Nom </label>
        <input type="text" name="name" value="<?php echo isset($_POST['name'])?$_POST['name']:''; ?>">
      </p>
      <?php if (!empty($erreur_name)): ?>
        <div class="error"><?php echo $erreur_name; ?></div>
      <?php endif ?>

      <p>
        <input type="submit" value="Enregistrer" >
      </p>


     </form>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>