<?php require 'includes/includes.php' ?>
<?php require 'includes/header.php'; ?>
<?php 
  if(isset($_GET['id'])){
    $produit = $DB->query('SELECT * FROM products WHERE id=:id',array('id'=>intval($_GET['id'])));
   if(empty($produit)){
      header('location:index.php');
   }
  }else{
    header('location:index.php');
  }
  $produit = $produit[0];

  // les produits de pub - voir aussi
  $autres = $DB->query('SELECT * FROM products WHERE category_id=:cat ORDER BY RAND() LIMIT 5',array('cat'=>$produit->category_id));
 ?>
      <h2>Informations du produit </h2>
      <ul class="produit clearfix">
        <li class="visuel"><img src="<?php echo $produit->photo;?>" alt="<?php echo $produit->name; ?>"></li>
        <li class="infos">
          <h3><?php echo $produit->name; ?></h3>
          <h4><?php echo number_format($produit->price,2,',',' ');?> €</h4>
          <p><?php echo $produit->description; ?></p>
          
          <form action="addPanier.php" id="produit" method="post">
            <label for="qte">Quantité</label>
            <input type="text" name="qte" Value="<?php echo isset($_GET['qte'])?intval($_GET['qte']):1; ?>">
            <?php if (isset($_GET['qte'])): ?>
              <input type="hidden" name="action" value="update">
            <?php endif ?>
            <input type="hidden" name="id" Value="<?php echo $produit->id; ?>">
            <input type="submit" value="Ajouter au panier">
          </form>
        </li>
      </ul>
      <div class="clearfix"></div>
      <h2>Voir aussi</h2>
      <ul class="produits">
       <?php foreach ($autres as $p): ?>
          <li>
          <a href="produit.php?id=<?php echo $p->id; ?>">
            <img src="<?php echo $p->photo; ?>" alt="<?php echo $p->name; ?>">
            <h4><?php echo $p->name; ?></h4>
          </a>
          <div class="prix">
            <?php echo number_format($p->price,2,',',' '); ?> €
          </div>
          <span> <a href="addPanier.php?id=<?php echo $p->id; ?>" title="Ajouter au panier">+</a></span>
        
        </li>
       <?php endforeach ?>
      
        
      </ul>
    
   <?php require 'includes/footer.php'; ?>