<?php session_start(); 
require  '../config.php';
require_once '../Classes/Db.php';
require_once '../Classes/auth.php';
$DB = new Db();

if(!Auth::isadmin($DB)){
	header('location:../index.php');
	$_SESSION['erreur'] = "Espace réservé aux administrateurs";
	exit();
}
 ?>