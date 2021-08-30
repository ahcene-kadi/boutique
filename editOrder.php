<?php require 'includes/includes.php'; 
  if(!empty($_GET['id'])){
    $order = $DB->query("SELECT * FROM orders INNER JOIN users On user_id = users.id WHERE orders.id=:id AND user_id=:user",
              array('id'=>$_GET['id'],'user'=>$_SESSION['user']['id']));
    if(empty($order)){
       header('location:index.php');
        exit();
    }else{
      $order=$order[0];
      $donnees = (array)(unserialize($order->datas));
    }
  }else{
    header('location:index.php');
    exit();
  }
?>

<?php require 'includes/header.php'; ?>
<!-- Message dans la session -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="message"> <?php echo $_SESSION['message']; ?></div>
    <?php unset( $_SESSION['message']); ?>
  <?php endif ?>
   <?php if (isset($_SESSION['erreur'])): ?>
    <div class="errorMessage"> <?php echo $_SESSION['erreur']; ?></div>
    <?php unset( $_SESSION['erreur']); ?>
  <?php endif ?>

      <h1>Editer une commande </h1>
   
     <p> Commande N° : <?php echo $_GET['id']; ?></p>
     <p>Date de la commande : <?php echo $order->created; ?></p>
     <p>Montant de la commande : <strong><?php echo $order->amount; ?> €</strong ></p>
     <h2>Client</h2>
     <p>Nom : <?php echo $order->nom; ?></p>
     <p>Email : <?php echo $order->email; ?></p>
     <p>Adress : <?php echo $order->adresse; ?></p>
     <p><?php echo $order->codepostale; ?> - <strong><?php echo $order->ville; ?></strong></p>

    <h2>les produits</h2>

    <table>
      <thead>
        <th>id</th>
        <th>Nom du produit</th>
        <th>Qte</th>
        <th>Prix</th>
      </thead>
         <tbody>
         <?php for($i=1;$i<=$donnees['num_cart_items'];$i++) {?>
            <tr>
              <td><?php echo $donnees['item_number'.$i] ?></td>
              <td><?php echo $donnees['item_name'.$i] ?></td>
              <td><?php echo $donnees['quantity'.$i] ?></td>
              <td><?php echo $donnees['mc_gross_'.$i] ?></td>
            </tr>
         <?php }?>
        </tbody>
    </table>
      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>