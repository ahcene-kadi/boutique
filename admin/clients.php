<?php require 'includes/includes.php'; 

    $nb =$DB->query('SELECT count(*) as nbr FROM users');

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
    <h2>Gestion des clients       </h2>
      <?php 
 
          $users =$DB->query('SELECT * FROM users  ORDER By id 
          DESC LIMIT '.$premierPage.','.$perpage.'');
       ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom </th>
          <th>Email</th>
          <th>Active ?</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?php echo $user->id ?></td>
             <td><?php echo $user->nom ?></td>
              <td><?php echo $user->email ?></td>
               <td><?php echo $user->active ?></td>
                <td>  
                  <a href="editUser.php?id=<?php echo $user->id ?>" class="edit"></a>
                  <a href="ecrire.php?user=<?php echo $user->id ?>" class="email"></a>
                  <?php if ($user->active == 0): ?>
                    <a href="sync.php?user=<?php echo $user->id ?>" class="sync"></a>
                  <?php endif ?>
                    <a href="delete.php?user=<?php echo $user->id ?>" class="del"></a>
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
              echo '<li><a href="clients.php?page='.$i.'">'.$i.'</a></li>';
            }
          }
         ?> 
        </ul>
      </div>
      <?php endif ?>


      <div class="clearfix"></div>
  
<?php require 'includes/footer.php'; ?>