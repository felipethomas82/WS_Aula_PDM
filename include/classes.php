<?php

class Usuario
{
	private $nomeUsuario;
	private $senha;


	function __construct($nome, $senha="")
	{
		$this->nomeUsuario = $nome;
		$this->senha = $senha;
	}

	//método que verifica se o login do usuário é válido
	public function verificaLogin()
	{
			if($this->nomeUsuario == "pokemon" && $this->senha == "1234") {

		          	return 1;
		      }

		//caso passe pelo while sem o return 1 é porque nao encontrou o user e senha, então retorna 0
		return 0;
	}

}



?>