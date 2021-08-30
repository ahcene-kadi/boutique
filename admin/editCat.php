<?php require 'includes/includes.php'; 

if(!empty($_GET['id'])){
  $id=intval($_GET['id']);

  $cat = $DB->query("SELECT * FROM categories WHERE id=:id LIMIT 1",array('id'=>$id));
  if(!empty($cat)){
    $cat = $cat[0];

  }else{
    $_SESSION['erreur'] ="Catégorie introuvable dans notre base .";
    header('location:categories.php');
    exit();
  }
}
if(!empty($_POST)){
  $validate =true;
  if(empty($_POST['name'])){
    $validate =false;
    $erreur_name="Le nom de la catégorie est requis.";
  }

  
  // validation
  if($validate){
    $data=array(
        'id'=>$_POST['id'],
        'name'=>$_POST['name'],
      );
    $rep = $DB->insert("UPDATE categories SET name=:name WHERE id=:id",$data);
    if($rep){
      $_SESSION['message']="La catégories à été mise à jour avec succès";
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
    <h2>Editer un produit </h2>
     <form action="editCat.php?id=<?php echo $_GET['id']; ?>" method="POST" >
          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
      <p>
        <label for="name">Nom de la catégorie</label>
        <input type="text" name="name" value="<?php echo isset($_POST['name'])?$_POST['name']:$cat->name; ?>">
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