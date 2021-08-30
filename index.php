<?php require 'includes/includes.php'; ?>
<?php require 'includes/header.php'; ?>
<!-- message de session -->
<?php if (isset($_SESSION['message'])): ?>
  <div class="message"> <?php echo $_SESSION['message']; ?></div>
  <?php unset($_SESSION['message']) ?>
<?php endif ?>
<?php if (isset($_SESSION['erreur'])): ?>
  <div class="errorMessage"> <?php echo $_SESSION['erreur']; ?></div>
  <?php unset($_SESSION['erreur']) ?>
<?php endif ?>
      <h1>Bienvenue ...</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <h2>Les Nouveautés</h2>
      <?php 
        $produits = $DB->query('SELECT * FROM products ORDER BY id DESC LIMIT 5');
       ?>
      <ul class="produits">
        <?php foreach ($produits as $produit): ?>
          <li>
          <a href="produit.php?id=<?php echo $produit->id ;?>">
            <img src="<?php echo $produit->photo ;?>" alt="<?php echo $produit->name; ?>">
            <h4><?php echo $produit->name; ?></h4>
          </a>
          <div class="prix">
            <?php echo number_format($produit->price,2,',',' '); ?> €
          </div>
          <span> <a href="addPanier.php?id=<?php echo $produit->id ?>" title="Ajouter au panier">+</a></span>
        
        </li>
  
        <?php endforeach ?>
        
        
      </ul>
      <div class="col left charte">
       
          <h3>Notre charte</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <button><a href="" title="">Lire la suite</a></button>
       
      </div>
      <div class="col right livraison">

          <h3>Nos livraisons</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <button><a href="" title="">Voir les conditions</a></button>

      </div>

      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>