<?php

namespace Hcode;

use Rain\Tpl;

class Page {

	private $tpl;
	private $options = [];
	private $defaults = [
		"data"=>[]
	];

	//método mágico construtor - CONSTRUINDO TEMPLATE
<<<<<<< HEAD
	public function __construct($opts = array(), $tpl_dir = "/views/") {
=======
	public function __construct($opts = array()) {
>>>>>>> a7021f02f8c9695df8daacf0b2411bba3f2da8ed

		$this->options = array_merge($this->defaults, $opts);

			// config
		$config = array(				//DOCUMENT_ROOT TRÁS O DIRETÓRIO ROOT DO SEU AMBIENTE, ONDE ESTÁ A PASTA
<<<<<<< HEAD
						"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
=======
						"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
>>>>>>> a7021f02f8c9695df8daacf0b2411bba3f2da8ed
						"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
						"debug"         => false // set to false to improve the speed
					   );

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);

		$this->tpl->draw("header");

	}

	private function setData($data = array())
	{

		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}

	}

	public function setTpl($name, $data = array(), $returnHTML = false)
	{

		//chamando o método setData
		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);

	}

	//método mágico destrutor
	public function __destruct() {

		$this->tpl->draw("footer");

	}

}

?>