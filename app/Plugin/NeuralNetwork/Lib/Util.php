<?php

/*
* Classe Util
*/

class Util{ 

	//static $showDescription=false;
	public static $showDescription = false;

    public static function normalize($values, $max, $min, $reverse = false, $showDescription = false){
		
		$html = "";

    	self::$showDescription = $showDescription;

		//Se for um conjunto de valores organizado em um array
		if(is_array($values)){

			$response = array();

			foreach($values as $key => $value){

				$response[$key] = (float) 0;

				if(!$reverse){
					
					//normalize
					$response[$key] = (float) @(($value-$min)/($max-$min));

					if(self::$showDescription){ 
					   	$html .= "{$key} - (".$value."-".$min.")/(".$max."-".$min.") = ".$response[$key]."<br/>";
					}	
				}else{

					//desnormalize
					$response[$key] = (float) @$min+(($max-$min)*$value);

					if(self::$showDescription){ 
					   	$html .= "{$key} - ".$min."+((".$max."-".$min.")*".$value.") = ".$response[$key]."<br/>";
					}

				}		
			}

		//Se for apenas um valor só	
		}else
		{
			$response = (float) 0;	
			$value = $values;

			if(!$reverse){
			
				//normalize
				$response = (float) @(($value-$min)/($max-$min));

				if(self::$showDescription){ 
				   	$html .= "(".$value."-".$min.")/(".$max."-".$min.") = ".$response."<br/>";
				}					
			}else{

				//desnormalize
				$response = (float) @($min+(($max-$min)*$value));
				//echo "<br/>";
				//echo $response ." = ".$min."+((".$max."-".$min.")*".$value.");";
				//echo "<br/>";
				if(self::$showDescription){ 
				   	$html .= "".$min."+((".$max."-".$min.")*".$value.") = ".$response."<br/>";
				}				
			}			
		}	


		PrintCalculation::setHtml($html, 'fase-1', 'passo-3');

		return $response;
	}

	public static function getMinAndMax($values = array()){

		$response = array();

		$response["min"] = min($values);
		$response["max"] = max($values); 

		return $response;
	}

	/*
	* Método auxiliar que ajuda a selecionar aleatoriamente sem repetição
	*/
	public static function randomGen($min, $max, $quantity){

		$numbers = range($min, $max);
		shuffle($numbers);
		return array_slice($numbers, 0, $quantity);
	}	

	/*
	* Método...
	*/
	public static function formatDataInputsAndOutputs($data, $eliminate_duplications = false, $separator = ";", $has_titles = true){

		$response = false;

		//If existing only one datasets, where is include input and output for once
		if(!empty($data["inputs-and-outputs"])){
			
			//var_dump($data); die();
			list($ninput, $nhidden, $noutput) = explode("-", $data["inputs-and-outputs"]["topology"]);
			
			$posts["number_inputs"] = $ninput;
			$posts["number_hiddens"] = $nhidden;
			$posts["number_outputs"] = $noutput;

			$sample = 0;	
			foreach($data["inputs-and-outputs"]["values"] as $row){
				
				$values = explode($separator, $row);
				
				for($n=0; $n<$ninput; $n++){
					$vinputs[$sample][] = $values[$n];
				}
				$data["inputs"][$sample] = implode($separator, $vinputs[$sample]);

				for($n=$ninput; $n<$noutput+$ninput; $n++){
					$voutputs[$sample][] = $values[$n];
				}

				$data["outputs"][$sample] = implode($separator, $voutputs[$sample]);

				$sample++;	
			}
		}
		//var_dump($data);

		if($data){


			//Resgate the titles - inicio
			if($has_titles){

				$titles["inputs"] = explode($separator, trim($data["inputs"][0])); unset($data["inputs"][0]);
				$titles["outputs"] = explode($separator, trim($data["outputs"][0])); unset($data["outputs"][0]);
			}else{
				
				$count_inputs = count(explode($separator, trim($data["inputs"][0])));
				$count_outputs = count(explode($separator, trim($data["outputs"][0])));

				for($n=0; $n<$count_inputs; $n++){

					$default_inputs[] = "x".($n+1);
				}
				for($n=0; $n<$count_outputs; $n++){

					$default_outputs[] = "y".($n+1);
				}

				$titles["inputs"] = $default_inputs;
				$titles["outputs"] = $default_outputs;
			}

			foreach($titles["inputs"] as $title){

				$posts["inputs"]["titles"][] = $title;
			}

			foreach($titles["outputs"] as $title){

				$posts["outputs"]["titles"][] = $title;
			}
			//Resgate the titles - fim

			//Trata os dados - inicio
			foreach($data["inputs"] as $key => $value){

				$data["inputs"][$key] = trim($value);
			}

			foreach($data["outputs"] as $key => $value){

				$data["outputs"][$key] = trim($value);
			}
			//Trata os dados - fim

			//Let Inputs and Outputs as unique - inicio
			if($eliminate_duplications){

				$tmp_arrays = array();
				foreach($data["inputs"] as $key => $value){

					$tmp_arrays[$key] = $value.$data["outputs"][$key];
				}		
				$keys_stays = array_unique($tmp_arrays);

				foreach($keys_stays as $key => $value){
					$data["new_inputs"][] = $data["inputs"][$key];
					$data["new_outputs"][] = $data["outputs"][$key];
				}

				$data["inputs"] = $data["new_inputs"];
				$data["outputs"] = $data["new_outputs"];

				unset($data["new_inputs"]);
				unset($data["new_outputs"]);
			}
			//Let Inputs and Outputs as unique - fim


			foreach($data["inputs"] as $num_input => $handle){	
				
				$linha = explode($separator, str_replace(array('"',' '),array('',''),$handle));

				foreach($linha as $coluna => $value){

					//$values["entradas"][$coluna][] = preg_replace("/[^0-9]/", "", $value);
					$posts["inputs"]["values"][$coluna][] = $value;
				}
			}


			foreach($data["outputs"] as $handle){	
				
				$linha = explode($separator, str_replace(array('"',' '),array('',''),$handle));

				foreach($linha as $coluna => $value){

					//$values["saidas"][$coluna][] = preg_replace("/\n/", "", $value);
					$posts["outputs"]["values"][$coluna][] = $value;
				}
			}


		}
		
		//debug($posts); //die();
		return $posts;
	}	


	public static function ordersInputsAcordingOutput($entradas, $saidas){
				
			$response = FALSE;

			asort($saidas); //ordenar o array, permanecendo os indices originais
			$storage_order_key = array();
			
			$cont = 0;
			foreach($saidas as $key => $value){

				$storage_order_key[$key] = $cont;
				$cont++;
			}
			

			$novas_saidas = array();
			foreach($saidas as $key => $value){
				$novas_saidas[$storage_order_key[$key]] = $value;
			}

			$novas_entradas = array();
			foreach($entradas as $key => $value){
				$novas_entradas[$storage_order_key[$key]] = $value;
			}			

			ksort($novas_saidas);
			ksort($novas_entradas);

			return $response = array("outputs" => $novas_saidas, "inputs" => $novas_entradas);
	}

	//Calculate media from numbers array
	public static function calculateMedia(array $values){

		$media = (array_sum($values)/count($values));
		return $media;
	}

	//Method is for save some information in a file (txt, cvs..)
	public static function saveDataExport($string = null,  $my_configs = array()){

		$config = array("extension" => "txt");
		foreach($my_configs as $key => $value){

			$config[$key] = $value; 
		}

		//Save the file in the path
		if($string){

			$file = @fopen("arquivos/rotinas/".date("Y-m-d H-i-s").".".$config["extension"], "w");
			@fwrite($file, $string);
			@fclose($file);
		}
		

	}

	public static function prepareDataSetsImport($file){

	    $arquivo = fopen($file["tmp_name"], "r");
	    $linha_entradas_e_saidas = array();

	    while(!feof($arquivo)){

	        $value = fgets($arquivo);
	        if($value != ""){
	            $linha_entradas_e_saidas[] = trim($value);
	        }        
	    }

	    //unset($linha_entradas[0]); //Exclui a primeira (titulos)
	    fclose($arquivo);

	    return $linha_entradas_e_saidas;	
	    	
	}

	public static function acesso(){

		return "Acessando UTIL";
	}

	public static function getContentImport($file){
	    
	    $tmp_file = fopen($file["tmp_name"], "r");
	    $content = "";

	    while(!feof($tmp_file)){

	        $value = fgets($tmp_file);
	        if($value != ""){
	            $content .= trim($value);
	        }        
	    }

	    //unset($linha_entradas[0]); //Exclui a primeira (titulos)
	    fclose($tmp_file);

	    return $content;

	}

	public static function getWeightsImport($file, $topology = array()){

		$learning = json_decode(self::getContentImport($file), true);
		
		//Validate the weights acording with the topology from neural network
		if($topology){

			//Topolgy Ex: 2-4-1

			//$layers[0] => layer input, $layers[1] => layer hidden, $layers[2] => layer output 
			$layers = explode("-", $topology); 
			$validate = TRUE;

			//Verifying the inputs
			foreach($learning['weights']['hidden_to_input'] as $hidden_neurons){

				if(count($hidden_neurons) != $layers[0]){

					$validate = FALSE; break;
				}
			}

			//Verifying the hidden neurons
			if(count($learning['weights']['hidden_to_input']) != $layers[1]){

				$validate = FALSE;
			}

			//Verifying the bias
			if(count($learning['bias_of_each_neuron']) != ($layers[1]+$layers[2])){

				$validate = FALSE;
			}

			if(!@$validate){ 
				echo "Verifying something incorrect in the file import of weights"; die(); 
			}else{ 
				$learning = array('hidden_to_input' => $learning['weights']['hidden_to_input'], 'output_to_hidden' => $learning['weights']['output_to_hidden'], 'bias_of_each_neuron' => $learning['bias_of_each_neuron']); 
			}
		}

		return $learning;
	}	
}
?>