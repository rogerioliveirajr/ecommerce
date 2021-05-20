<?php

namespace Hcode\Model;
//chamando o banco de dados (sql)
use \Hcode\DB\Sql;
use \Hcode\Model;

class user extends Model {

	const SESSION = "User";

	public static function login($login, $password)
	{
		//validar se o usuario no banco e a hash(senha) é o mesmo 
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));
		//verificando se encontrou o login
		if (count ($results) === 0)
		{		//excessão no escopo principal e não dentro do namspace hcode model (\)
			throw new \Exception("Usuário inexistente ou senha inválida");
		}
		//resultado  do primeiro registro q ele encontrou 
		$data = $results[0];

		//verificando a senha do usuário
		if (password_verify($password, $data["despassword"]) === true)
		{

			$user = new User();

			$user->setData($data);
			
			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		} else {
			throw new \Exception("Usuário inexistente ou senha inválida");
		}

	}

	public static function verifyLogin($inadmin = true)
	{	

		if(
			!isset($_SESSION[User::SESSION]) // verificando se a sessao esta definida  
			||
			!$_SESSION[User::SESSION] // verificando se a sessao for falsa
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 // verificando o id do usuario 
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin  // verificando se o usuario tem acesso a area de admin 	
		) {

			header("Location: /admin/login");
			exit;

		}
	}

	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}
	//listar usuarios do banco
	public static function listAll()
	{

		$sql = new Sql();

		 return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson" );

	}
	//para salvar os dados no banco
	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);

	}

	public function get($iduser)
	{
		$sql = new Sql();  

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(":iduser"=>$iduser
	));  
		 
		$this->setData($results[0]);
	}

	public function update() 
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_userupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);
	}
}

?>