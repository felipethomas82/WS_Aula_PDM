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


/**
* 
*/
class Posts 
{
	
	function __construct()
	{
		# code...
	}


	public function retornaPosts() 
	{
		
		$posts = array();

		for ($i=0; $i < 2; $i++) { 

			$post['titulo'] = "Pokemon Go";
			$post['subtitulo'] = "Uma aula de experiência do usuário";
			$post['foto'] = "/WS_Aula_PDM/imgs/pokemon.jpg";
			$post['texto'] = "Pokémon Go também é um ótimo exemplo de UX Design, o tão desejado User Experience Design, que nada mais é do que a busca pela satisfação do usuário por meio de três áreas essenciais: usabilidade, acessibilidade e interatividade. É o desenho, a projeção de tudo o que vai compor a experiência de um usuário com um produto. O jogo de Hanke fez bonito aqui também, pois usa features já existentes de aparelhos e aplicativos bem difundidos. Ou seja, foi desenvolvido para uma interface com a qual os usuários já têm muita familiaridade.";
			$post['usuario'] = 'Pikachu';

			array_push($posts, $post);

			$post['titulo'] = "Chococo";
			$post['subtitulo'] = "Caminhões de lixo no Japão têm sistema para exalar aroma de chocolate";
			$post['foto'] = "/WS_Aula_PDM/imgs/chocolate.jpg";
			$post['texto'] = "Quem nunca ficou perto de um caminhão de lixo e sentiu aquele cheiro horrível de chorume? Uma empresa no Japão, especializada em drenar fossas e coletar resíduos de banheiros químicos recebeu várias reclamações por causa do cheiro de seus veículos. E olha que a indignação dos moradores não passou batida. A empresa Yamamoto Fragrance já possuía um produto chamado Deo Magic desde 2011, usado para anular o cheiro de fraldas. Para os caminhões, eles desenvolveram um sistema com um óleo com fragrância de chocolate que é bombeado enquanto os veículos se locomovem.";
			$post['usuario'] = 'Nerd Master';

			array_push($posts, $post);

		}

		return $posts;

	}
}



?>