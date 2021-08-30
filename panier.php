<?php require 'includes/includes.php'; ?>
<?php require 'includes/header.php';

  $liste = $panier->getPanier();
  
  // test si le panier est  vide
  $items=null;
  if(!empty($liste))
    $items = implode(',',$liste);
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

      <h2>Votre Panier </h2>
      <!-- cas d'un panier vide -->
      <?php if (empty($items)): ?>
         <div>
          <p>Votre panier est vide !</p>
          <button><a href="index.php" title="Faire des achats">Faire des achats</a></button>
        </div> 
      <?php else: ?>
    
      <!-- cas d'un panier avec des produits -->
      <?php 
      $produits = $DB->query('SELECT * FROM products WHERE id in ('.$items.')');

       ?>
      <table>
        <thead>
          <tr>
            <th></th>
            <th class="text">Produit</th>
            <th>Prix Unit.</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produits as $p): ?>
            <tr>
            <td><img src="<?php echo $p->photo ?>" alt="<?php echo $p->name ?>"></td>
            <td class="text"><a href="produit.php?id=<?php echo $p->id ?>&qte=<?php echo  $panier->getQte($p->id);?>" title="Mac book pro"><?php echo $p->name; ?></a></td>
            <td><?php echo number_format($p->price,2,',',' ') ?> €</td>
            <td> 
              <span><a href="panier.php?moinsPanier=<?php echo $p->id ?>" class="moins"></a></span>
              <?php echo $panier->getQte($p->id); ?>
              <span><a href="panier.php?plusPanier=<?php echo $p->id ?>" class="plus"></a></span>
            </td>
            <td><?php echo number_format($p->price * $panier->getQte($p->id),2,',',' '); ?> €</td>
            <td> 
              <a href="produit.php?id=<?php echo $p->id ?>&qte=<?php echo $panier->getQte($p->id); ?>" class="edit"></a>
              <a href="panier.php?delPanier=<?php echo $p->id ?>" class="delete"></a>
            </td>
          </tr>
          <?php endforeach ?>
          
          
        </tbody>
      </table>
      <div class="total" >
          <p> <span>Total HT </span> <em><?php echo number_format( $panier->total()/1.196,2,',',' '); ?>  €</em></p>
          <p> <span>TVA(19.6 %) </span> <em>  <?php echo number_format( $panier->total() -($panier->total()/1.196),2,',',' '); ?>  €</em></p>
          <p> <span>TOTAL TTC </span> <em> <?php echo number_format( $panier->total(),2,',',' '); ?> €</em></p>
           <button><a href="produits.php" title="Continuer mes achats">Continuer mes achats</a></button>
          <button class="commande"><a href="commande.php" title="Passer la commande" >Passer la commande</a></button>
      </div>
    <?php endif ?>
      <div class="clearfix"></div>

   <?php require 'includes/footer.php'; ?>
