<?php 

/**
* Auth
*/
class Auth{
	
	public static function islog($db){
		if(isset($_SESSION['user']) && isset($_SESSION['user']['email']) &&  isset($_SESSION['user']['password'])){

			$data =array(
				'email'=>$_SESSION['user']['email'],
				'password'=>$_SESSION['user']['password']
				);

			$sql = 'SELECT * FROM users WHERE email=:email AND password=:password limit 1';
			$req = $db->tquery($sql,$data);

			if(!empty($req)){
				return true;
			}
		}
		return false;
	}


	//public static function hashPassword($pass){

		//return sha1(SALT.md5($pass.SALT).sha1(SALT));
	//}


	public static function isadmin($db){
		if(isset($_SESSION['user']['role']) && (sha1('admin')  == $_SESSION['user']['role'])){
			return true;
		}
		return false;
	}
}