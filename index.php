<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});
//rota da página de admin
$app->get('/admin/', function() {

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login', function() {

	$page = new PageAdmin([
		//desabilitando o chamamento automático do header
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});    

//setando a rota do login e resgatando os dados do input método post
$app->post('/admin/login', function() {

	User::login($_POST["login"], $_POST["password"]);

	//Apos logar, redirecionando para a home page
	header("Location: /admin");
	exit;
});

//tela q lista todos usuários
$app->get("/admin/users", function () { 
	
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));
});

//Rota de tela para criar usuário
$app->get("/admin/users/create", function () { 
	
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

//rota para deletar usuário
$app->get("/admin/users/:iduser/delete", function ($iduser) {
	User::verifyLogin();

	$user = new User();

	$user->get((int) $iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

//tela de editar (update)
$app->get("/admin/users/:iduser", function ($iduser) { 
	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);    

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getvalues()
	));
});

//rota pra salvar os dados do formulário
$app->post("/admin/users/create", function () {
	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
});

//para salvar a edição
$app->post("/admin/users/:iduser", function ($iduser) {

	User::verifyLogin();

	$user = new user();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});

//rota esqueceu a senha
$app->get("/admin/forgot", function() {

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");
});

//rota para enviar o codigo de recuperação de senha pegando o email via post

$app->post("/admin/forgot", function() {

	$user = User::getForgot($_POST['email']);

	header("Location: /admin/forgot/sent");

});

$app->get("/admin/forgot/sent", function() {

	$page = new PageAdmin([
	"header"=>false,
	"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

$app->run();

 ?>