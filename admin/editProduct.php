<?php require 'includes/includes.php'; 

if(!empty($_GET['id'])){
  $id=intval($_GET['id']);

  $product = $DB->query("SELECT * FROM products WHERE id=:id LIMIT 1",array('id'=>$id));
  if(!empty($product)){
    $product = $product[0];

  }else{
    $_SESSION['erreur'] ="Produit introuvable dans notre base .";
    header('location:produits.php');
    exit();
  }
}
if(!empty($_POST)){
  $validate =true;
  if(empty($_POST['name'])){
    $validate =false;
    $erreur_name="Le nom du produit est requis.";
  }

   if(empty($_POST['description'])){
    $validate =false;
    $erreur_description="La description du produit est requise.";
  }

   if(empty($_POST['price'])){
    $validate =false;
    $erreur_price="Le prix du produit est requis.";
  }

   if(empty($_POST['qte'])){
    $validate =false;
    $erreur_qte="Veuillez entrer la quantité du produit.";
  }

  if(empty($_FILES['photo']['name'])){
    $photo= $_POST['old_photo'];
  }

  if(!empty($_FILES['photo']['name'])){
    $extension = strrchr($_FILES['photo']['name'], '.');
    $dossier =UPLOAD;

    $photo = md5($_FILES['photo']['name'])."$extension";
    if(!move_uploaded_file($_FILES['photo']['tmp_name'], '../'.$dossier.$photo)){
      $validate = false;
      $_SESSION['erreur'] ="Un problème est survenu lors de l'Upload de l'image";
    }else {
        unlink('../'.$_POST['old_photo']);
        $photo = $dossier.$photo;
      }
  }

  // validation
  if($validate){
    $data=array(
        'id'=>$_POST['id'],
        'name'=>$_POST['name'],
         'description'=>$_POST['description'],
        'price'=>$_POST['price'],
         'qte'=>$_POST['qte'],
         'category_id'=>$_POST['categorie'],
         'photo'=>$photo
      );
    $rep = $DB->insert("UPDATE products SET name=:name,description=:description,price=:price,qte=:qte,photo=:photo,category_id=:category_id WHERE id=:id",$data);
    if($rep){
      $_SESSION['message']="Le produit à été mis à jour avec succès";
      header('location:produits.php');
      exit();
    }else{
         $_SESSION['erreur'] ="Un problème est survenu lors de la sauvegarde";
    }
  }else{
       $_SESSION['erreur'] ="Veuillez corriger les érreurs indiquées ci dessous";
  }
}
$categories = $DB->query("SELECT * from categories");
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
     <form action="EditProduct.php?id=<?php echo $_GET['id']; ?>" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
      <p>
        <label for="name">Nom </label>
        <input type="text" name="name" value="<?php echo isset($_POST['name'])?$_POST['name']:$product->name; ?>">
      </p>
      <?php if (!empty($erreur_name)): ?>
        <div class="error"><?php echo $erreur_name; ?></div>
      <?php endif ?>
      <p>
        <label for="description">la déscription du produit</label>
        <textarea name="description" ><?php echo isset($_POST['description'])?$_POST['description']:$product->description; ?></textarea>
      </p>
      <?php if (!empty($erreur_description)): ?>
        <div class="error"><?php echo $erreur_description; ?></div>
      <?php endif ?>
      <p>
        <label for="price">Prix </label>
        <input type="text" name="price" value="<?php echo isset($_POST['price'])?$_POST['price']:$product->price; ?>">
      </p>
      <?php if (!empty($erreur_price)): ?>
        <div class="error"><?php echo $erreur_price; ?></div>
      <?php endif ?>
      <p>
        <label for="qte">Quantité </label>
        <input type="text" name="qte" value="<?php echo isset($_POST['qte'])?$_POST['qte']:$product->qte; ?>">
      </p>
      <?php if (!empty($erreur_qte)): ?>
        <div class="error"><?php echo $erreur_qte; ?></div>
      <?php endif ?>
      <p>
        <label for="old_photo">Photo actuelle </label>
        <img src="../<?php echo $product->photo; ?>" class="thumb">
        <input type="hidden" name="old_photo" value="<?php echo $product->photo; ?>">
      </p>
      <p>
        <label for="photo">Changer la Photo </label>
        <input type="file" name="photo" >
      </p>
      <?php if (!empty($erreur_photo)): ?>
        <div class="error"><?php echo $erreur_photo; ?></div>
      <?php endif ?>

      <label for="categorie"> Choisissez la catégorie</label>
      <select name="categorie" id="categorie">
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c->id ?>" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == $c->id) || $product->category_id == $c->id ?"Selected":""?> > 
              <?php echo $c->name; ?></option>
        <?php endforeach ?>

      </select>

      <p>
        <input type="submit" value="Enregistrer" >
      </p>


     </form>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>