<?php require 'includes/includes.php'; 
$categorie = 0;
if(!empty($_GET['categorie']) ){
    $categorie = $_GET['categorie'];
    $cond=array('categorie'=>$categorie);
    $nb =$DB->query('SELECT count(*) as nbr FROM products WHERE category_id=:categorie',$cond);
}elseif (!empty($_POST['categorie']) && $_POST['categorie']!=null ) {
  $categorie = $_POST['categorie'];
  $cond=array('categorie'=>$categorie);
  $nb =$DB->query('SELECT count(*) as nbr FROM products WHERE category_id=:categorie',$cond);
}else
    $nb =$DB->query('SELECT count(*) as nbr FROM products');

$perpage = 12;
$nbr_pages = ceil($nb[0]->nbr /$perpage);

if(isset($_GET['page'])){
  $page = intval($_GET['page']);
  if($page>$nbr_pages){
    $page = $nbr_pages;
  } 
}else{
  $page =1;
}

$premierPage = ($page - 1)* $perpage;

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
    <h2>Gestion des produits 
        <?php 
        $categories = $DB->query('SELECT * FROM categories');
         ?>
        <form id="filtre" action="produits.php" method="post">
          <select name="categorie" id="categorie">
           <option value="0">Tous les produits</option>
           <?php foreach ($categories as $c): ?>
              <option value="<?php echo $c->id ?>"><?php echo $c->name; ?></option>
    
           <?php endforeach ?>
          </select>
          <input type="submit" value="Filtrer">
        </form>
      </h2>
      <?php 
      if(!empty($_GET['categorie'])){
        $products =$DB->query('SELECT * FROM products WHERE category_id=:categorie ORDER By id 
          DESC LIMIT '.$premierPage.','.$perpage.'',$cond);
      }elseif (!empty($_POST['categorie'])) {
          $products =$DB->query('SELECT * FROM products WHERE category_id=:categorie ORDER By id 
          DESC LIMIT '.$premierPage.','.$perpage.'',$cond);
      }
       else {  
           $products =$DB->query('SELECT * FROM products  ORDER By id 
          DESC LIMIT '.$premierPage.','.$perpage.'');
           }
       ?>
       <button><a href="addProduct.php">Ajouter un produit</a></button>
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Nom du produit</th>
          <th>Prix</th>
          <th>Qte</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><img src="../<?php echo $product->photo ?>"></td>
             <td><?php echo $product->name ?></td>
              <td><?php echo $product->price ?></td>
               <td><?php echo $product->qte ?></td>
                <td>  
                  <a href="editProduct.php?id=<?php echo $product->id ?>" class="edit"></a>
                   <a href="delete.php?product=<?php echo $product->id ?>" class="del"></a>
                </td>

          </tr>
        <?php endforeach ?>
      </tbody>

    </table>
    <?php if($nbr_pages >1): ?>
      <div class="pagination">
        <ul>
        <?php 
       
          for ($i=1; $i<= $nbr_pages; $i++){
            if($i == $page){
              echo '<li class="active"><a href="">'.$i.'</a></li>';
            }else{
              echo '<li><a href="produits.php?page='.$i.'&categorie='.$categorie.'">'.$i.'</a></li>';
            }
          }
         ?> 
        </ul>
      </div>
      <?php endif ?>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>