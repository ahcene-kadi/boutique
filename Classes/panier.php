<?php 
/**
* Panier
*/
class Panier
{
	private $DB;
	
	function __construct($db)
	{
		$this->DB = $db;

		if(!isset($_SESSION)){
			session_start();
		}

		if(!isset($_SESSION['panier'])){
			$_SESSION['panier'] = array();
		}

		if(isset($_GET['delPanier'])){
			$this->del($_GET['delPanier']);
		}
		if(isset($_GET['moinsPanier'])){
			$this->moins($_GET['moinsPanier']);
		}
		if(isset($_GET['plusPanier'])){
			$this->plus($_GET['plusPanier']);
		}
		if(isset($_GET['viderPanier'])){
			$this->vider();
		}

	}


	public function getPanier(){
		if(!empty($_SESSION['panier'])){
			return array_keys($_SESSION['panier']);
		}
	} 

	public function getQte($key){
		return $_SESSION['panier'][$key];
	}

	public function total(){
		$total = 0;
		if(!empty($_SESSION['panier'])){
			$liste = $this->getPanier();
			if(empty($liste)){
				$produits = array();
			}else
			$produits = $this->DB->query('SELECT id,price from products where id in ('.implode(',',$liste).')'); 
			
			foreach ($produits as $produit) {
				$total += $produit->price * $_SESSION['panier'][$produit->id];
			}
		}
		return $total;
	}

	public function count(){
		return array_sum($_SESSION['panier']);
	}

	public function del($key){
		if(isset($_SESSION['panier'][$key])){
			unset($_SESSION['panier'][$key]);
		}
	}

	public function moins($key){
		if(isset($_SESSION['panier'][$key]) && $_SESSION['panier'][$key] >0 )
			$_SESSION['panier'][$key] --;
		if(isset($_SESSION['panier'][$key]) && $_SESSION['panier'][$key] == 0){
			unset($_SESSION['panier'][$key]);
		}
	}

	public function plus($key){
		if(isset($_SESSION['panier'][$key]))
			$_SESSION['panier'][$key] ++;
	}

	public function vider(){
		$_SESSION['panier'] = array();
	}

}