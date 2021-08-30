<?php 
require 'config.php'; 
require_once 'Classes/Db.php';
require_once 'Classes/panier.php';
require_once 'Classes/auth.php';
$DB = new Db();
$panier = new Panier($DB);

// cacher les erreurs pour la production
//ini_set('error_reporting',E_ALL);
// afficher les érreurs lors du developpement 
ini_set('error_reporting', E_ALL);
 ?>