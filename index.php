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

$app->run();

 ?>