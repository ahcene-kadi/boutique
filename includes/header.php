
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Boutique Z&A</title>
  <meta name="description" content="">

  <meta name="viewport" content="width=device-width">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">

  <script src="js/libs/modernizr-2.5.0.min.js"></script>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <div id="topheader">
    
    <div class="container">
      
      <ul>
        <?php if (isset($_SESSION['user'])): ?>
          <li><a href="profil.php">Bienvenue, <?php echo $_SESSION['user']['email'] ?></a></li>
          <?php if (Auth::isadmin($DB)): ?>
            <li><a href="admin/">Admin</a></li>
          <?php endif ?>
        <li><a href="login.php?logout">Déconnexion</a></li>
        <?php else: ?>
        <li><a href="signup.php">S'inscrire</a></li>
        <li><a href="login.php">Se connecter</a></li>
      <?php endif?>

      </ul>
    </div>
  </div>
  <header>
    <div class="container">
      <div id="header">
       <div class="panier">
          <a href="panier.php"> Votre Panier :<em><?php echo $panier->count(); ?> Article(s)</em></a> 
          <span>  <em><?php echo number_format($panier->total(),2,',',' '); ?> €</em></span>
        </div>
      </div>
      </div>
    </div>
  </header>
  <nav>
    <ul class="container">
      <li><a href="index.php" title="Accueil">Accueil</a></li>
      <li><a href="produits.php" title="Produits">Produits</a>
          <ul>
            <li><a href="" title="Ecrans">Ecrans</a></li>
            <li><a href="" title="Ordinateurs">Ordinateurs</a></li> 
            <li><a href="" title="Tablettes">Tablettes</a></li>
            <li><a href="" title="Accessoires">Accessoires</a></li>
          </ul>
      </li>
      <li><a href="contact.php" title="Contact">Contact</a></li>  
    </ul>
  </nav>
  <div class="main">
    <div class="container">