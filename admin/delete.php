<?php require 'includes/includes.php'; 
  // supprimer le produit
	
	if(!empty($_GET['product'])){
		$id =intval($_GET['product']);

		$product = $DB->query('SELECT id,photo FROM products WHERE id=:id',array('id'=>$id));

		if(empty($product)){
			$_SESSION['erreur'] = "Produit introuvable dans notre base .";
		}else{
			$nb= $DB->insert('DELETE FROM products WHERE id=:id',array('id'=>$id));
			if($nb){
				unlink('../'.$product[0]->photo);
				$_SESSION['message'] = "Produit  supprimé avec succès";
			}else{
				$_SESSION['erreur'] = "Un problème est survenu lors de la suppression du produit.";
			}
		}

		echo '<script language ="Javascript">
		<!--
		document.location.replace("produits.php");
		-->;
		</script>';
	}

	// supprimer la catégorie
	
	if(!empty($_GET['cat'])){
		$id =intval($_GET['cat']);

		$cat = $DB->query('SELECT id FROM categories WHERE id=:id',array('id'=>$id));

		if(empty($cat)){
			$_SESSION['erreur'] = "Catégorie introuvable dans notre base .";
		}else{
			$nb= $DB->insert('DELETE FROM categories WHERE id=:id',array('id'=>$id));
			if($nb){
				$_SESSION['message'] = "Catégorie  supprimée avec succès";
			}else{
				$_SESSION['erreur'] = "Un problème est survenu lors de la suppression de la catégorie.";
			}
		}

		echo '<script language ="Javascript">
		<!--
		document.location.replace("categories.php");
		-->;
		</script>';
	}

	// supprimer un client
	
	if(!empty($_GET['user'])){
		$id =intval($_GET['user']);

		$user = $DB->query('SELECT id FROM users WHERE id=:id',array('id'=>$id));

		if(empty($user)){
			$_SESSION['erreur'] = "Client introuvable dans notre base .";
		}else{
			$nb= $DB->insert('DELETE FROM users WHERE id=:id',array('id'=>$id));
			if($nb){
				$_SESSION['message'] = "Client  supprimé avec succès";
			}else{
				$_SESSION['erreur'] = "Un problème est survenu lors de la suppression du Client.";
			}
		}

		echo '<script language ="Javascript">
		<!--
		document.location.replace("clients.php");
		-->;
		</script>';
	}


	echo '<script language ="Javascript">
		<!--
		document.location.replace("index.php");
		-->;
		</script>';
?>