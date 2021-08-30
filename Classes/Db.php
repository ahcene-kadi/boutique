<?php 

/**
* Gestion de la base de données
*/
class Db{
	
	private $host=HOST;
	private $name=DBNAME;
	private $user=USER;
	private $pass=PWD;

	private $connexion;

	function __construct($host=null,$name=null,$user=null,$pass=null){
		
		if($host != null){
			$this->host = $host;
			$this->name = $name;
			$this->user = $user;
			$this->pass = $pass;
		}

		try{

			$this->connexion = new PDO('mysql:host='.$this->host.';dbname='.$this->name,
				$this->user,$this->pass,array(
					1002 =>'SET NAMES UTF8',
					PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
					));
			$this->connexion->exec('SET NAMES utf8');
//PDO::MYSQL_ATTR_INIT_COMMAND 
		}catch (PDOException $e){
			//echo 'Erreur : Impossible de se connecter  à la BD !';die();
			echo $e->getMessage();
		}
	}

	/* requete */

	public function query($sql , $data=array()){
		$req = $this->connexion->prepare($sql);
		$req->execute($data);
		return $req->fetchAll(PDO::FETCH_OBJ); 
	}

	public function tquery($sql , $data=array()){
		$req = $this->connexion->prepare($sql);
		$req->execute($data);
		return $req->fetchAll(PDO::FETCH_ASSOC); 
	}


	public function insert($sql , $data=array()){
		$req = $this->connexion->prepare($sql);
		$nb=$req->execute($data);
		return $nb;
	}

	public function uniqueEmail($email){
		$req = $this->connexion->prepare('SELECT count(*) as nbre from users WHERE email=:email limit 1');
		$req->execute(array('email'=>$email));

		$reponse = $req->fetchAll(PDO::FETCH_ASSOC);
		return $reponse[0]['nbre'];

	}

}