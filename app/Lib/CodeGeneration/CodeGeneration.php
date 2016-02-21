<?php

/*
* Lib do Cakephp que gera código de rede neural no processo de feedforwrd
* Suporte: CakePHP 2.x
*
* Feito Por: Thomas Kanzig, contato@thomaskanzig.com
*/

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class CodeGeneration{
		
	public function __construct(){

	}

	/*
	 * String $language
	 * array $param = ['weights', 'bias', 'min', 'max', 'topology', 'activation_function'];
	 */

	public function feedforward($language = "php", $params = array()){


		if(@array_key_exists('topology', $params) && @array_key_exists('weights', $params) && @array_key_exists('bias', $params) && @array_key_exists('activation_function', $params) && @array_key_exists('data_max_min', $params)){

			//debug($params["weights"]); die();
			$language = strtolower($language);
			$dir = new Folder(APPLIBS. 'CodeGeneration/codes/feedforward/');
			$response = []; 

			//Case in PHP
			if($language == "php"){
				
				$response["extension"] = "php";	
				
				//Activation Function
				$activation_function = $params["activation_function"];

				//Nodes
				list($inputs, $hiddens, $outputs) = explode("-", $params["topology"]);

				//Bias
				$bias = "[".implode(", ", $params["bias"])."]";

				//Weights
				$hidden_to_input = array();
				foreach($params["weights"]["hidden_to_input"] as $neuron_hidden => $w){

					$hidden_to_input[] = $neuron_hidden." => [".implode(", ", $w)."]";
				}

				$output_to_hidden = array();
				foreach($params["weights"]["output_to_hidden"] as $neuron_output => $w){

					$output_to_hidden[] = $neuron_output." => [".implode(", ", $w)."]";
				}	

				$pesos = "['entrada_para_oculta' => "."[".implode(", ", $hidden_to_input)."],"." 'oculta_para_saida' => [".implode(", ", $output_to_hidden)."]"."]";

				//Max and Min from data
				$data_max_min = $params["data_max_min"];
    			$min = "['inputs' => [".implode(", ", $data_max_min['inputs']['min'])."], 'outputs' => [".implode(", ", $data_max_min['outputs']['min'])."]]";
    			$max = "['inputs' => [".implode(", ", $data_max_min['inputs']['max'])."], 'outputs' => [".implode(", ", $data_max_min['outputs']['max'])."]]";

			}

			$file = new File($dir->pwd() . $language.".php");
			$contents = $file->read();
			$file->close();


			$response["source_code"] = str_replace(["[countInputs]", "[countHiddens]", "[countOutputs]", "[activation_function]","[bias]","[pesos]","[max]","[min]"], [$inputs, $hiddens, $outputs, $activation_function, $bias, $pesos, $max, $min], $contents);

		}else{

			die("That's parameters is incompatible =/");
		}

		return $response;
			
	}

}



?>