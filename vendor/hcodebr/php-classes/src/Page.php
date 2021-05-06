<?php

namespace Hcode;

use Rain\Tpl;

class Page {

	private $tpl;
	private $options = [];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];

	//método mágico construtor - CONSTRUINDO TEMPLATE
	public function __construct($opts = array(), $tpl_dir = "/views/") {

		$this->options = array_merge($this->defaults, $opts);

			// config DOCUMENT_ROOT TRÁS O DIRETÓRIO ROOT DO SEU AMBIENTE, ONDE ESTÁ A PASTA
		$config = array(
						"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
						"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
						"debug"         => false // set to false to improve the speed
					   );

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);

		if ($this->options["header"] === true) $this->tpl->draw("header");

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

		if ($this->options["footer"] === true) $this->tpl->draw("footer");

	}

}

?>