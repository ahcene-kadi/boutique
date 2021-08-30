<?php require 'includes/includes.php'; 

    $nb =$DB->query('SELECT count(*) as nbr FROM categories');

$perpage = 2;
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
    <h2>Gestion des catégories       </h2>
      <?php 
 
          $cats =$DB->query('SELECT * FROM categories  ORDER By id 
          DESC LIMIT '.$premierPage.','.$perpage.'');
       ?>
       <button><a href="addCat.php">Ajouter une catégorie</a></button>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom de la catégorie</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cats as $cat): ?>
          <tr>
            <td><?php echo $cat->id ?></td>
             <td><?php echo $cat->name ?></td>
                <td>  
                  <a href="editCat.php?id=<?php echo $cat->id ?>" class="edit"></a>
                   <a href="delete.php?cat=<?php echo $cat->id ?>" class="del"></a>
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
              echo '<li><a href="categories.php?page='.$i.'">'.$i.'</a></li>';
            }
          }
         ?> 
        </ul>
      </div>
      <?php endif ?>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>