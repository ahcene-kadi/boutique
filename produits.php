<?php require 'includes/includes.php' ?>
<?php require 'includes/header.php'; ?>
  <?php 
      if(!empty($_POST['categorie']) && $_POST['categorie'] >0){
          $categorie = intval($_POST['categorie']);

          $cond= array('category_id'=>$categorie);

          $nbr = $DB->query('SELECT count(*) as nbr FROM products WHERE category_id=:category_id ORDER BY id DESC',$cond);
         
        }else{
          $nbr = $DB->query('SELECT count(*) as nbr  FROM products ORDER BY id DESC');
          }
        
        $perpage = 15;
        $nbr_pages = ceil($nbr[0]->nbr/$perpage);

        if(isset($_GET['page'])){
          $page = intval($_GET['page']);
           if($page>$nbr_pages){
            $page = $nbr_pages;
            }
        }else{
          $page = 1;
        }

        $premierPage = ($page -1) * $perpage;

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

      <h2>La liste des produits 
        <?php 
        $categories = $DB->query('SELECT * FROM categories');
         ?>
        <form id="filtre" action="produits.php" method="post">
          <select name="categorie" id="categorie">
            <option value="0">Tous</option>
           <?php foreach ($categories as $c): ?>
              <option value="<?php echo $c->id ?>"><?php echo $c->name; ?></option>
    
           <?php endforeach ?>
          </select>
          <input type="submit" value="Filtrer">
        </form>
      </h2>

     <?php 
      if(!empty($_POST['categorie']) && $_POST['categorie'] >0){
          $categorie = intval($_POST['categorie']);

          $cond= array('category_id'=>$categorie);

          $produits = $DB->query('SELECT  * FROM products WHERE category_id=:category_id ORDER BY id DESC  LIMIT '.$premierPage.','.$perpage.'',$cond);
         
        }else{
          $produits = $DB->query('SELECT * FROM products ORDER BY id DESC   LIMIT '.$premierPage.','.$perpage.'');
          
        }

      ?>

      <?php 
   /*   $produits = $DB->query('SELECT * FROM products ORDER BY id DESC  LIMIT 15');*/
       ?>
      <ul class="produits">
      <?php foreach ($produits as $produit): ?>
          <li>
          <a href="produit.php?id=<?php echo $produit->id ?>">
            <img src="<?php echo $produit->photo ;?>" alt="<?php echo $produit->name; ?>">
            <h4><?php echo $produit->name; ?></h4>
          </a>
          <div class="prix">
            <?php echo number_format($produit->price,2,',',' ');?> €
          </div>
          <span> <a href="addPanier.php?id=<?php echo $produit->id; ?>" title="Ajouter au panier">+</a></span>
        
        </li>
      <?php endforeach ?>

        
      </ul>
      <?php if($nbr_pages >1): ?>
      <div class="pagination">
        <ul>
        <?php 
       
          for ($i=1; $i<= $nbr_pages; $i++){
            if($i == $page){
              echo '<li class="active"><a href="">'.$i.'</a></li>';
            }else{
              echo '<li><a href="produits.php?page='.$i.'">'.$i.'</a></li>';
            }
          }
         ?> 
        </ul>
      </div>
      <?php endif ?>
<?php require 'includes/footer.php'; ?>