<?php

/*
*
*/

class RedeNeuralComponent extends Component{
	

	public function preparaParaDesenho($learning = array(), $topologia_nn = null)
	{

		list($ninputs, $nhiddens, $noutputs) = explode("-", $topologia_nn);

		$nodes = $edges = array();
		$qts_hiddens = count($learning['weights']['hidden_to_input']);
		$qts_outpus = count($learning['weights']['output_to_hidden']);

		//Utilizing the weights from json file for make the edges in drawing
		foreach($learning['weights']['hidden_to_input'] as $neuron_hidden => $hidden_to_input){
		    
		    foreach($hidden_to_input as $neuron_input => $weight){
		        
		        $edges[] = "{ data: { source: 'n".$neuron_input."', target: 'n".($neuron_hidden+count($hidden_to_input))."', value:'".number_format($weight,4,",","")."' } }";
		    }

		    $qts_inputs = count($hidden_to_input); //Insert qts neuron inputs
		}

		foreach($learning['weights']['output_to_hidden'] as $neuron_output => $output_to_hidden){
		    
		    foreach($output_to_hidden as $neuron_hidden => $weight){
		        
		        $edges[] = "{ data: { source: 'n".($neuron_hidden+count($hidden_to_input))."', target: 'n".($neuron_output+count($hidden_to_input))."', value:'".number_format($weight,4,",","")."' } }";
		    }
		}

		for($i=0; $i<$qts_inputs; $i++){
		    $edges[] = "{ data: { source: 'i".$i."', target: 'n".$i."' }, style:{ 'target-arrow-shape': 'none'} }";
		    $nodes[] = "{ data: { id: 'i".$i."', value:'Entrada ".($i+1)."' }, style:{ 'height': 10,'width': 10,'shape':'rectangle','background-color':'#000','font-weight':'bold'} }";
		}

		for($o=0; $o<$qts_outpus; $o++){
		    $edges[] = "{ data: { source: 'n".($qts_inputs+$qts_hiddens+$o)."', target: 'o".$o."' }, style:{ 'target-arrow-shape': 'none'} }";
		    $nodes[] = "{ data: { id: 'o".$o."', value:'Saida ".($o+1)."' }, style:{ 'height': 10,'width': 10,'shape':'rectangle','background-color':'#000','font-weight':'bold'} }";
		}

		//Total of neurons in the network
		$total_neurons = ($qts_inputs+$qts_hiddens+$qts_outpus);

		$content_value = ""; $style_value = "";
		for($n=0; $n<$total_neurons; $n++){
			
			if($n < $qts_inputs){ 
				$content_value = 'X'.($n+1); 
				$style_value = "{'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'10px','height': 28,'width': 28,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#65EC5C'}"; 
			}elseif($n >= $qts_inputs && ($qts_hiddens+$qts_inputs) > $n){ 
				$content_value = 'N'.(($n-$qts_inputs)+1); 
				$style_value = "{'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'12px','height': 42,'width': 42,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#579CE2'}";
			}else{
				$style_value = "{'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'12px','height': 42,'width': 42,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#FF6356'}";
			}

		    $nodes[] = "{ data: { id: 'n".$n."', value:'".$content_value."' },style:".$style_value." }";
		}

		return ['nodes' => $nodes, 'edges' => $edges];
	}	
}

?>