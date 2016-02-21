<?php

class NeuroEasyNN{

	public $qtsXEntradas;
	public $qtsNeuroniosOcultos;
	public $qtsNeuroniosSaidas;		
	public $pesos;
	public $bias;
	public $min, $max;
	private $fativacao;

	public function __construct(){

		//As variáveis abaixo são adaptados de acordo com o aprendizado do treinamento
		
		//Topologia adotada na rede neural
		$this->qtsXEntradas = [countInputs];
		$this->qtsNeuroniosOcultos = [countHiddens];
		$this->qtsNeuroniosSaidas = [countOutputs];	

		//Pesos gerados pelo treinamento
		$this->pesos = [pesos];

		//Bias gerados pelo treinamento
    	$this->bias = [bias];

    	//Mínimo e máximo dos valores (entradas e saidas)
    	$this->min = [min];
    	$this->max = [max];

    	$this->fativacao = "[activation_function]";

	}

	public function testar($entradas = array()){

		//Valida as entradas de acordo com o padrão desta rede neural
		$this->validarEntradas($entradas); 

		//Executar o Feed Forward
		$y = $this->feedforward($entradas); 

		//Desnormalizando as saidas
		$saidas = array(); $num_y = 0;
		for ($neuronio_saida= $this->qtsNeuroniosOcultos;$neuronio_saida< $this->qtsNeuroniosOcultos + $this->qtsNeuroniosSaidas; $neuronio_saida++){

			$saidas[] = $this->normalizar($y[$neuronio_saida], $this->max['outputs'][$num_y], $this->min['outputs'][$num_y], true);
			$num_y++;
		}
		return $saidas;
	} 


	private function feedforward($entradas = array())
	{
		$y = array();

		// Camada de entrada e oculta
		for ($neuronio_oculta=0;$neuronio_oculta< $this->qtsNeuroniosOcultos; $neuronio_oculta++){
		  $Somatorio = 0;

		  for ($ent=0; $ent < $this->qtsXEntradas; $ent++){
		        $Somatorio += $print_n = $this->normalizar($entradas[$ent], $this->max['inputs'][$ent], $this->min['inputs'][$ent]) * $this->pesos['entrada_para_oculta'][$neuronio_oculta][$ent];
			     
		  }
		  $y[$neuronio_oculta] = $this->funcaoAtivacao($Somatorio + $this->bias[$neuronio_oculta]);
		}	

		// Camada oculta e saida
		for ($neuronio_saida= $this->qtsNeuroniosOcultos;$neuronio_saida < $this->qtsNeuroniosOcultos + $this->qtsNeuroniosSaidas; $neuronio_saida++){
		  $Somatorio = 0;

		   for ($neuronio_oculta=0; $neuronio_oculta < $this->qtsNeuroniosOcultos;$neuronio_oculta++){
		        $Somatorio += $print_n = $y[$neuronio_oculta] * $this->pesos['oculta_para_saida'][$neuronio_saida][$neuronio_oculta]; // aqui a entrada é a saida do neuronio anterior

		  }

		  $y[$neuronio_saida] = $this->funcaoAtivacao($Somatorio + $this->bias[$neuronio_saida]);
		}

		return $y;
		
	}

	private function funcaoAtivacao($exponent = null){

		if($this->fativacao == "tangente-hiperbolica"){

			$response = (tanh($exponent)); //Tangente Hiperbólica
		}

		if($this->fativacao == "sigmoide"){

			$response = (1.0/(1 + exp( (-1)* $exponent ) )); //Sigmóide 
		}

		return $response;
	} 


	public function normalizar($numero, $max, $min, $reverso = false){
		
		$response = 0;

		if(!$reverso){
		
			//normalize
			$response = (float) @(($numero-$min)/($max-$min));
		}else{

			//desnormalize
			$response = (float) @($min+(($max-$min)*$numero));
		}	

		return $response;

	}	

	/*
	* Só uma precaução para nada dar errado durante a execução da rede neural	
	*/
	public function validarEntradas($entradas = array()){
		
		if(!@is_array($entradas)){
			die("Parâmetro precisa ser um array");
		}

		if(count($entradas) != $this->qtsXEntradas){
			die("Não corresponde ao número de entradas que foram treinadas");
		}		
	
	}

}

//Cria objeto
$RedeNeural = new NeuroEasyNN();

/* Informe nos parâmetros abaixo as suas entradas. 
 * Ex: Duas entradas, nesse caso: $RedeNeural->testar([123, 40]);
 */
$saidas = $RedeNeural->testar([]);

var_dump($saidas);

?>