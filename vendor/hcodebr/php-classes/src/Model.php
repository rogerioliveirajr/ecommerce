<?php

namespace Hcode;

class Model {

	//values pega os dados existentes dentro do objeto (No caso do usuario, os dados do usuario, id, nome...)
	private $values = [];
	//método mágico para todas as vezes que o método for chamado
	public function __call($name, $args)
	{
		//para saber se é um metodo get ou set, tras o campo 0, 1, 2
		$method = substr($name, 0, 3);
		//descobrir o nome do campo que foi chamado, descartando os 3 primeiros e pegando o restante
		$fieldName = substr($name, 3, strlen($name));

		switch ($method)
		{
			case "get":
				return $this->values[$fieldName];
			break;

			case "set":
				$this->values[$fieldName] = $args[0];
			break;

		}
	}

	public function setData($data = array())
	{

		foreach ($data as $key => $value) {
			
			$this->{"set".$key}($value);

		}

	}

	public function getValues()
	{

		$this->values;

	}

}

?>