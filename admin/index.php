<?php require 'includes/includes.php'; 
if(!empty($_GET) && isset($_GET['choix'])){
  switch ($_GET['choix'])
  { 
    case 'day':
        $total = $DB->query('SELECT SUM(amount) as total,count(*) as nbr FROM orders WHERE DATE(created) = CURRENT_DATE() ');
      break;
    case 'month':
        $total = $DB->query('SELECT SUM(amount) as total,count(*) as nbr FROM orders WHERE DATE_FORMAT(created,"%c") = DATE_FORMAT(CURRENT_DATE(),"%c") ');
      break;
    case 'year':
        $total = $DB->query('SELECT SUM(amount) as total,count(*) as nbr FROM orders WHERE DATE_FORMAT(created,"%Y") = DATE_FORMAT(CURRENT_DATE(),"%Y") ');
      break;
    default:
        $total = $DB->query('SELECT SUM(amount) as total,count(*) as nbr FROM orders ');
      break;
  }
}else
  {
  $total = $DB->query('SELECT SUM(amount) as total,count(*) as nbr FROM orders WHERE DATE(created) = CURRENT_DATE() ');
  }
$perpage = 3;
$nbr_pages = ceil($total[0]->nbr /$perpage);

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
      <h1>Bienvenue <?php echo $_SESSION['user']['nom']; ?>, dans l'espace d'administration</h1>
      <p><?php echo "Aujourd'hui le : ". date('Y-m-d'); ?>
        <div>
          <span><a href="index.php?choix=day">Aujourd'hui</a></span>
          <span><a href="index.php?choix=month">Mois</a></span>
          <span><a href="index.php?choix=year">Année</a></span>
          <span><a href="index.php?choix=all">Archives</a></span>
        </div>
     
     <?php 
     if(!empty($_GET) && isset($_GET['choix'])){
        switch ($_GET['choix'])
        { 
          case 'day':
              $orders = $DB->query('SELECT * FROM orders WHERE DATE(created) = CURRENT_DATE() ORDER BY id LIMIT '.$premierPage.','.$perpage.'');
            break;
          case 'month':
              $orders = $DB->query('SELECT * FROM orders WHERE DATE_FORMAT(created,"%c") = DATE_FORMAT(CURRENT_DATE(),"%c") ORDER BY id LIMIT '.$premierPage.','.$perpage.'');
            break;
          case 'year':
               $orders = $DB->query('SELECT * FROM orders WHERE DATE_FORMAT(created,"%Y") = DATE_FORMAT(CURRENT_DATE(),"%Y") ORDER BY id LIMIT '.$premierPage.','.$perpage.'');
            break;
          default:
               $orders = $DB->query('SELECT * FROM orders  ORDER BY id LIMIT '.$premierPage.','.$perpage.'');
            break;
        }
      } else
        $orders = $DB->query('SELECT * FROM orders WHERE DATE(created) = CURRENT_DATE() ORDER BY id LIMIT '.$premierPage.','.$perpage.''); ?>

    <div>
       <h3>Quelques information</h3>
     <ul class="cadre">
      <li><span><?php echo number_format($total[0]->total,2); ?> €</span>Chiffre d'affaires</li>
       <li><span><?php echo count($orders); ?></span>Vente(s)</li>
        <li><span><?php echo (count($orders)>0)?number_format($total[0]->total/count($orders),2):0; ?> €</span>Panier moyen</li>
     </ul>

    </div>

    <table>
      <thead>
        <tr>
          <th>N°Commande</th>
          <th>Date</th>
          <th>Montant</th>
          <th>Txn_id</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo $order->id ?></td>
             <td><?php echo $order->created ?></td>
              <td><?php echo $order->amount ?></td>
               <td><?php echo $order->txn_id ?></td>
                <td>  
                  <a href="editOrder.php?id=<?php echo $order->id ?>" class="edit"></a>
                   <a href="pdfOrder.php?id=<?php echo $order->id ?>" class="pdf"></a>
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
              echo '<li><a href="index.php?page='.$i.'&choix='.$_GET["choix"].'">'.$i.'</a></li>';
            }
          }
         ?> 
        </ul>
      </div>
      <?php endif ?>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>