<?php

/*
* Classe PrintCalculations for help print all the iterations in a neural network
* By: Thomas Kanzig, thomas.kanzig@gmail.com
* CakePHP 2.x
*/

class PrintCalculation{ 

	private static $html = array();
	private static $prehtml = "";

    public static function helperOverviewEpoch($epoch = 1, $errorMeasures = array(), $y = array(), $deltaOutputs, $deltaNeurons, $wInputToHidden, $wHiddenToOutput, $wBias, $nodes){
		
    	$countNeuronsTotal = ($nodes["hidden"]+$nodes["output"]); //count neurons total
    	$countSamplesTotal = count($y['train']); //count samples total
    	$countEstimativasErroAmostra = (($nodes["hidden"]*$nodes["output"])+$nodes["output"]);
    	
    	$countWeightsInputToHidden = ($nodes["input"]*$nodes["hidden"]);
    	$countWeightsHiddenToOutput = ($nodes["hidden"]*$nodes["output"]);

    	$html = "";
		/*
		*
		* Info from the variables
		*
		* Accumulated Erro => $errors['train']['normalized'][$epoch]['sum'];
		* Erro Square Medio => $errors['train']['normalized'][$epoch]['mse'];	
		* All results from Activation => $y['train'];
		* Estimative Erro => $deltaOutputs, $deltaNeurons;
		* Correction values from weights => $deltaEntradaSum[$epoch], $deltaOcultaSum[$epoch] and $deltaBiaSum[$epoch];
		* Weights adjusted => $wInputToHidden, $wHiddenToOutput and $wBias; 
		*
		*/

		//Show resume error measures
		$html .= "<table class='print'>
			<thead>
				<tr>
				<th colspan='3'>Mediação de Erros</th>
				</tr>
				<tr>
					<th>Saída</th>
					<th>Soma de Erro Estimado</th>
					<th>Erro Médio Quadrático</th>
				</tr>
			</thead>
			<tbody>
		<tr>";
		for($output=0; $output<$nodes["output"]; $output++){
		
			$html .= "<td>".($output+1)."</td>
				  <td>".array_sum($errorMeasures['abs(y-y^)']['train']['normalized'][$epoch][$output])."</td>
				  <td>".$errorMeasures['mean-squared-error']['train']['normalized'][$epoch][$output]."</td>";
					
		}
		$html .= "</tr></tbody></table>";			  
			  
		//Show Activation F(x) in each neuron
		$html .= "<table class='print'><thead>";
		$html .= "<tr><th colspan=".($countNeuronsTotal+1).">Resultados da Função de Ativação f(x) em cada neurônio</th></tr>";
		$html .= "<tr><th>Amostra</th>";
		for($n=1; $n<=$countNeuronsTotal; $n++){
			$html .= "<th>N{$n} f(x)</th>";
		}
		$html .= "<tr></thead><tbody>";
					  
		foreach($y['train'] as $sample => $fx){

			$html .= "<tr><td>".($sample+1)."ª</td>";
			foreach($fx as $n => $value){

				$html .= "<td>{$value}</td>";
			}
			$html .= "</tr>";
		}

		$html .= "</tbody></table>";	


		/* Estimative Error
		 * $wXErroSaida => w x ErroSaida
		 * $sDsO => Saida Desejado - Saida Obtida
		 */

		$html .= "<table class='print'><thead><tr><th colspan='".($countEstimativasErroAmostra+1)."'>Estimativa dos Erros</th></tr>";
		$html .= "<tr><th>Amostra</th>";

		for($w=$countWeightsInputToHidden+1; $w<=$countWeightsInputToHidden+$countWeightsHiddenToOutput;$w++){

			$html .= "<th>w{$w}</th>";
		}

		for($s=1;$s<=$nodes["output"];$s++){

			$html .= "<th>Saída {$s}</th>";
		}	

		$html .= "</tr></thead><tbody>";
		for($sample=0; $sample<$countSamplesTotal; $sample++){

			$html .= "<tr>";
			$html .= "<td>".($sample+1)."ª</td>";

			foreach($deltaNeurons[$sample] as $wXErroSaida => $estimativaErro){

				$html .= "<td>{$estimativaErro}</td>";
			}
			foreach($deltaOutputs[$sample] as $sDsO => $estimativaErro){

				$html .= "<td>{$estimativaErro}</td>";
			}			
			$html .= "</tr>";
		}
		$html .= "</tbody></table>";

		//Show new weights (input-to-hidden and hidden-to-output)
		
		$w_values = array();
		foreach($wInputToHidden as $n => $value){

			$w_values[$n] = $value;
		}
		foreach($wHiddenToOutput as $n => $value){

			$w_values[$n] = $value;
		}
		foreach($wBias as $n => $value){

			$w_values[$n][] = $value;
		}	

		$html .= "<table class='print'><thead>";
		$html .= "<tr><th colspan='4'>Novos pesos após aprendizado da {$epoch}ª época</th></tr>";
		$html .= "<tr><th></th><th>Wa</th><th>Wb</th><th>Bias</th></tr>";
		$html .= "</thead><tbody>";
		foreach($w_values as $neuron => $ws){
			$html .= "<tr><td><strong>N".($neuron+1)."</strong></td>";
			foreach($ws as $w){
				$html .= "<td>{$w}</td>";
			}
			$html .= "</tr>";
		}

		$html .= "</tbody></table>";

		return $html;
		
	}

	public static function helperOverviewDataDistribuited($inputs, $outputs){

		//debug($inputs); die();

		$count_total 	  = @count($inputs['values']['all']['desnormalized'][0]);
		$count_train 	  = @count($inputs['values']['train']['desnormalized'][0]);
		$count_validation = @count($inputs['values']['validation']['desnormalized'][0]);
		$types = array("train" => $count_train, "validation" => $count_validation); 

		$porc_train = ($count_train*100)/$count_total;
		$porc_validation = ($count_validation*100)/$count_total;

		$html = array();

		
		foreach($types as $type => $count){

			$html[$type] = "<div style='height:300px;'><center><h4>Não foi distribuído nenhum dado</h4></center></div>";

			if($count > 0){

				//<table> from data training
				$html[$type] = "<table class='table table-bordered table-hover table-striped'><thead><tr><th>Amostras</th>";

				foreach($inputs['titles'] as $key => $title):
					$html[$type] .= "<th>".$title."</th>";
				endforeach;					

				foreach($outputs['titles'] as $key => $title):
					$html[$type] .= "<th>".$title."</th>";
				endforeach;		

				$html[$type] .= "</tr></thead><tbody>";

				//Extract the data for training
				for($sample=0; $sample<$count; $sample++):
						
					$html[$type] .= "<tr><td>".($sample+1)."ª</td>";

					foreach($inputs['values'][$type]['desnormalized'] as $column => $values):
						$html[$type] .= "<td>".$values[$sample]."</td>";
					endforeach;	

					foreach($outputs['values'][$type]['desnormalized'] as $column => $values):
						$html[$type] .= "<td>".$values[$sample]."</td>";
					endforeach;	

					$html[$type] .= "</tr>";

				endfor;

				$html[$type] .= "</tbody></table>";

			}
		}
		
		return "<div class='panel panel-primary panel-red'><div class='panel-heading'><strong>Distribuição das Entradas s Saídas</strong></div><div style='padding-bottom:0px;' class='panel-body'><div id='distribuicao'><ul class='nav nav-tabs' role='tablist'><li role='presentation' class='active' style='width:50%;'><a href='#dados-treinamento' aria-controls='dados-treinamento' role='tab' data-toggle='tab'><strong>Dados para Treinamento - {$porc_train}%</strong></a></li><li role='presentation' style='width:50%;'><a href='#dados-teste' aria-controls='dados-teste' role='tab' data-toggle='tab'><strong>Dados para Validação - {$porc_validation}%</strong></a></li></ul><div class='tab-content'><div role='tabpanel' class='tab-pane active' id='dados-treinamento'>{$html['train']}</div><div role='tabpanel' class='tab-pane' id='dados-teste'>{$html['validation']}</div></div></div></div></div>";

	}

	/*
	public static function helperOverviewWeightsAndBias($wEntrada, $wOculta, $bias){

		$html = "";
		$html .= "<div class='panel panel-primary panel-red'>
				  	<div class='panel-heading'><strong>Valores iniciais dos Pesos/Bias Iniciais</strong></div>
				  	<div style='padding-bottom:0px;' class='panel-body'>";
		

		$html .= print_r($wEntrada);
		$html .= print_r($wOculta);
		$html .= print_r($bias);

		$html .= "</div></div>";

		return $html;
	}*/

	public static function setHtml($html, $fase, $passo, $epoca_amostra = false, $orderStep = 0){

		if($fase == 'fase-2'){
			@self::$html[$fase][$orderStep][$passo][$epoca_amostra] .= $html;
		}elseif($fase == 'fase-1'){
			@self::$html[$fase][$passo] .= $html;
		}

		self::$prehtml .= $html;
	}	

	public static function generateDataJsonStepByStep($filename = null){

		//debug($this->html['fase-2']); die();
		$ghtml = array();
		
		//Variable (fase1, fase2) was create below because, in the future can be setted a other name  
		$fase1 = 'fase-1';
		$fase2 = 'fase-2';

		$json = '';

		//Fase 1
		foreach(self::$html[$fase1] as $step => $value){

			$ghtml[$fase1][$step]['id'] = $fase1."-".$step;
			$ghtml[$fase1][$step]['content'] = $value;		
		}

		//Fase 2
		$count = 0;
		foreach(self::$html[$fase2] as $orderStep => $step){

			foreach($step as $step => $epoch_samples){
				
				foreach($epoch_samples as $epoch_sample => $value){

					$ghtml[$fase2]['onlyIds'][$step][] = $epoch_sample;
					$ghtml[$fase2]['contents'][$orderStep]['id'] = $fase2."-".$step."-".$epoch_sample;
					$ghtml[$fase2]['contents'][$orderStep]['value'] = $value;
				}

				//Let this with unique values, because it's necessary 
				$ghtml[$fase2]['onlyIds'][$step] = array_unique($ghtml[$fase2]['onlyIds'][$step]);

			}
		}

		$json = json_encode($ghtml);
		
		return $json;
	}

	public static function getPreHtml(){

		return self::$prehtml;
	}
}

?>