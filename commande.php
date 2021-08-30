<?php require 'includes/includes.php';
if(!Auth::islog($DB)){
  header('location:login.php');
} ?>
<?php require 'includes/header.php';

  $liste = $panier->getPanier();
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

      <h2>Confirmation </h2>
      <!-- cas d'un panier vide -->
      <?php if (empty($items)): ?>
         <?php header('location:index.php') ;?>
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
          
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produits as $p): ?>
            <tr>
            <td><img src="<?php echo $p->photo ?>" alt="<?php echo $p->name ?>"></td>
            <td class="text"><?php echo $p->name; ?></td>
            <td><?php echo number_format($p->price,2,',',' ') ?> €</td>
            <td> 
              
              <?php echo $panier->getQte($p->id); ?>
             
            </td>
            <td><?php echo number_format($p->price * $panier->getQte($p->id),2,',',' '); ?> €</td>
           
          </tr>
          <?php endforeach ?>
          
          
        </tbody>
      </table>
      <div class="total" >
          <p> <span>Total HT </span> <em><?php echo number_format( $panier->total()/1.196,2,',',' '); ?>  €</em></p>
          <p> <span>TVA(19.6 %) </span> <em>  <?php echo number_format( $panier->total() -($panier->total()/1.196),2,',',' '); ?>  €</em></p>
          <p> <span>TOTAL TTC </span> <em> <?php echo number_format( $panier->total(),2,',',' '); ?> €</em></p>
           <button><a href="panier.php" title="Retour au panier">Retour au panier</a></button>
         
          <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypal">

            <input type='hidden' value="<?php echo $panier->total(); ?>" name="amount" />  <!-- somme -->
            <input name="currency_code" type="hidden" value="EUR" />
            <input name="shipping" type="hidden" value="0.00" /><!-- frais de port -->
            <input name="tax" type="hidden" value="0.00" /> <!-- tax -->
            <!-- script pour succes -->
            <input name="return" type="hidden" value="http://localhost:8888/boutiqueadministrateur/success.php?viderPanier" />
            <!-- script en cas d'annulation -->
            <input name="cancel_return" type="hidden" value="http://localhost:8888/boutiqueadministrateur/panier.php" />
            <!-- url pour IPN notification de paiement -->
            <input name="notify_url" type="hidden" value="http://localhost:8888/boutiqueadministrateur/notif_IPN.php" />
          
            <!-- compte du vendeur -->
            <input name="business" type="hidden" value="ahcene.kadi@laplateforme.io" />

            <input type="hidden" name="upload" value="1" />
            <input type="hidden" name="cmd" value="_cart" />
            <!-- la liste des produits dans le panier -->
            <?php 
            $i=1;
            foreach ($produits as $p): ?>
                <input name="item_name_<?php echo $i ?>" type="hidden" value="<?php echo $p->name; ?>" />
                <input name="item_number_<?php echo $i ?>" type="hidden" value="<?php echo $p->id; ?>" />
                <input name="quantity_<?php echo $i; ?>" type="hidden" value="<?php echo $panier->getQte($p->id); ?>"/>
                <input name="amount_<?php echo $i ?>" type="hidden" value="<?php echo $p->price;?>" />
            <?php 
            $i++;
            endforeach ?>
          

            

            <input name="no_note" type="hidden" value="1" />
            <input name="lc" type="hidden" value="FR" />
            <input name="bn" type="hidden" value="PP-BuyNowBF" />
            <input name="custom" type="hidden" value="user_id=<?php echo $_SESSION['user']['id'] ?>" />
            <input alt="Effectuez vos paiements via PayPal " name="submit"  type="submit" value="Payer"/>
          </form>

      </div>
    <?php endif ?>
      <div class="clearfix"></div>

   <?php require 'includes/footer.php'; ?>
