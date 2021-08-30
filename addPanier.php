<?php 
session_start();

// cas ajout par formulaire
if(isset($_POST['id']) && isset($_POST['qte'])){

	if(isset($_POST['action']) && $_POST['action'] == 'update'){
		$_SESSION['panier'][$_POST['id']] = $_POST['qte'];
	}else{

		if(isset($_SESSION['panier'][$_POST['id']])){
			$_SESSION['panier'][$_POST['id']] += $_POST['qte'];
		}else{
			$_SESSION['panier'][$_POST['id']] = $_POST['qte'];
		}
	}

	$_SESSION['message'] = "le produit a bien été ajouté au panier.";
	header('location:panier.php');
}

// cas d'un ajout avec le plus.
if(isset($_GET['id'])){
	if(isset($_SESSION['panier'][$_GET['id']])){
		$_SESSION['panier'][$_GET['id']] += 1;
	}else{
		$_SESSION['panier'][$_GET['id']] = 1;
	}

	$_SESSION['message'] = "le produit a bien été ajouté au panier.";
/*	header('location:panier.php');*/
	?>
	<script type="text/javascript">
		javascript:history.back();
	</script>
	<?php
}
 ?>