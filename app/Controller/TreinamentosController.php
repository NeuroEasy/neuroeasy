<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TreinamentosController extends AppController {


	public $uses = array('NeuralNetworkType','NeuralNetworkLearningRule','NeuralNetworkActivationFunction','TreinamentoResultado','Treinamento');
	public $layout = "sistema";
	public $components = array("NeuralNetwork.BackPropagation","RedeNeural");

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Security->unlockedActions = array("mostrar_dados_importacao","executar","salvar","buscar"); //Unlocked action in Security component's
		$this->Auth->allow(array("buscar"));
	}


	public function novo(){

		$types = $this->NeuralNetworkType->find('list', ['conditions' => ['active' => true]]);
		$activation_functions = $this->NeuralNetworkActivationFunction->find('list', ['fields' => ['slug','name'], 'conditions' => ['active' => true]]);
		$learning_rules = $this->NeuralNetworkLearningRule->find('list', ['conditions' => ['active' => true]]);

		$this->set(compact('types', 'activation_functions', 'learning_rules'));

	}

	public function listar(){

		//Verify if exist some training from the user
		$countTraining = $this->Treinamento->find('count', ['conditions' => ['Treinamento.user_id' => $this->Session->read("Auth.User.id")]]);
		if($countTraining == 0){
			$this->redirect(["action" => "novo"]); 
		}

		App::uses('MyDate', 'Lib');

	 	$busca = "";
	 	$mensagem = "";
		$condicao_busca = $rota_paginacao = [];
		$condicao_principal = ['Treinamento.active' => true,
	 						   'Treinamento.deleted' => '',
	 						   'Treinamento.user_id' => $this->Session->read("Auth.User.id"),
	 						   ];

	    //Case executa some search in the page list from training
	    if(!@empty($this->request->params["named"]['busca']))
		{
			$busca = $this->request->params["named"]['busca'];
			if(@is_numeric($busca)){
				$condicao_busca[] = "Treinamento.id = '{$busca}'";
			}else{
				$condicao_busca[] = "Treinamento.name LIKE '%{$busca}%'";			 	
			}	 		 

	 		$rota_paginacao = array('controller' => 'treinamentos', 
	 		 						'action' => 'listar',
	 		 						'busca' => $this->request->params["named"]['busca']
	 		 						);
		}	
		//				   


		$this->paginate = ["Treinamento" => ["fields" => ["id", "name","created"],
											 "limit" => 15,
											 "conditions" => ["AND" => [$condicao_principal, implode(" OR ",$condicao_busca)]],
											 "recursive" => -1,
											 "order" => ["id" => "DESC"]
											]
						  ];

		$itens = $this->paginate("Treinamento");

		//Case did find any training
		if(count($itens) == 0 && !@empty($busca)){
			$mensagem = "Não foi encontrado nenhum treinamento buscando por <strong>\"{$busca}\"</strong> =(";
		}elseif(count($itens) == 0 && @empty($busca)){
			$mensagem = "Não têm nenhum treinamento em ativo!";
		}
		//

		$this->set(compact("itens","busca","rota_paginacao","mensagem"));			  
	}

	public function mostrar_dados_importacao(){

		$num_neurons_valid = TRUE;
		$message_valid_datasets = "<span style='color:#3c763d;'>Importação dos dados realizado com sucesso.</span>";
		$datasets_values = (object) array();
		$stop = "";

		if(@array_key_exists("tmp_name", $this->request->data["Treinamento"]["dados_importacao"])){

			//Read the datasets file
		    $arquivo = fopen($this->request->data["Treinamento"]["dados_importacao"]["tmp_name"], "r");
		    $datasets_values = array();
		    $datasets = array();

		    while(!feof($arquivo)){

		    		$value = fgets($arquivo);
		    		if($value != ""){
		    			$datasets_values[] = trim($value);
		    		}
		    	
		    }
		    fclose($arquivo);

		    //Make verification from datasets
		    $sum_neurons = $this->request->data["Treinamento"]["qts_entradas"] + $this->request->data["Treinamento"]["qts_saidas"];
			foreach($datasets_values as $key => $string){
				
				//Verify number from colums
				$values_col = explode(";", $string);
				$count_col = count($values_col);

				if($count_col != $sum_neurons){

					$message_valid_datasets = "<div class=\"alert alert-danger\" role=\"alert\">O números de entradas e saídas não correspondem aos números dos dados inseridos.</div>";
					$num_neurons_valid = FALSE;
					break;
				}

				//Verify the values if are numbers format
				if($key > 0){
					
					//Values format
					foreach($values_col as $column => $value){
						
						if(@!is_numeric($value)){
							
							$message_valid_datasets = "<div class=\"alert alert-danger\" role=\"alert\">Encontramos um formato inválido dos dados. Só é válido os valores em números</div>";
							$num_neurons_valid = FALSE;
							$stop = $value;
							break;
						}

						$datasets["values"][$key][$column+1] = $value; 	
					}

					//Add a new column to show the sample number pertence
					$datasets["values"][$key][0] = $key."ª"; 

				}else{

					//Titles format
					foreach($values_col as $column => $value){

						if($this->request->data["Treinamento"]["qts_entradas"] > $column){
							$value = $value." <span style='text-transform: lowercase;'>(entrada ".($column+1).")</span>";
						}else{
							$value = $value." <span style='text-transform: lowercase;'>(saída ".(($column-$this->request->data["Treinamento"]["qts_entradas"])+1).")</span>";
						}

						$datasets["titles"][$key][$column+1] = $value; 	
					}

					//Add a new column to show the sample number pertence
					$datasets["titles"][$key][0] = "Amostras"; 					
				}
				
			}

			
		}else{
			$message_valid_datasets = "<div class=\"alert alert-danger\" role=\"alert\">Não foi encontrado nenhum arquivo com os dados</div>";
			$num_neurons_valid = FALSE;
		}
	    //debug($datasets); die();
		$response["valid"] = $num_neurons_valid;
		$response["message"] = $message_valid_datasets;
		$response["datasets"] = $datasets;
		$response["stop"] = $stop;

		header('Content-Type: application/json');
		echo json_encode($response);
		die();
	}	

	public function executar(){

		$errors = $dataOutputsCompare = array();	

		if($this->request->is("post")){

			$datasets_values = Util::prepareDataSetsImport($this->request->data['Treinamento']['dados_importacao']);

			$request = array();
			$request['data']['inputs-and-outputs']['values'] = $datasets_values;
			$request['topology'] = $this->request->data['Treinamento']['qts_entradas']."-".$this->request->data['Treinamento']['neuronios_camada_oculta']."-".$this->request->data['Treinamento']['qts_saidas'];
			$request['countNeuronsHiddenLayer'] = $this->request->data['Treinamento']['neuronios_camada_oculta'];
			$request['learningRate'] = $this->request->data['Treinamento']['taxa_aprendizado']; 
			$request['epochs'] = $this->request->data['Treinamento']['epocas'];
			$request['fativacao'] = $this->request->data['Treinamento']['funcao_ativacao']; 
			$request['momentum'] = $this->request->data['Treinamento']['taxa_momentum']; 
			$request['separatorData'] = ";"; 
			$request['data']['inputs-and-outputs']['topology'] = $request['topology']; 
			$request['percentageToTrain'] = intval($this->request->data['Treinamento']['porc_treinamento']);
			$request['percentageToValidation'] = intval($this->request->data['Treinamento']['porc_validacao']); 
			
			if($this->request->data['normalizar']){ $request['normalizeDataSets'] = true; }

			//debug($this->request->data); die();

			//Define correctly the weights
			if($this->request->data['Treinamento']['pesos_iniciais'] == "fixos"){

				$request['weightRandom'] = (boolean) false; 
				$request['weightInit']['fixed'] = (float) $this->request->data['Treinamento']['peso_fixo'];	
			}

			if($this->request->data['Treinamento']['pesos_iniciais'] == "aleatorios"){

				$request['weightRandom'] = (boolean) true; 
				$request['weightInit']['smaller'] = (float) $this->request->data['Treinamento']['peso_inicial'];
				$request['weightInit']['larger'] = (float) $this->request->data['Treinamento']['peso_final'];						
			}
			
			if($this->request->data['Treinamento']['pesos_iniciais'] == "importar"){
				
				$request['weightInit']['costum'] = Util::getWeightsImport($this->request->data["Treinamento"]["pesos_importacao"], $request['topology']);	
			}

			//debug($request); //die();
			/* 
			 * Neural Network Type: Multple Layers Perceptron, Lerning Rule: Backpropagation/Backpropagation with Momentum
			 */
			
			if($this->request->data['Treinamento']['tipo_rede_neural'] == 1 && ($this->request->data['Treinamento']['regra_aprendizagem'] == 1 || $this->request->data['Treinamento']['regra_aprendizagem'] == 2)){

				$funcao_ativacao_id = $this->NeuralNetworkActivationFunction->findBySlug($this->request->data['Treinamento']['funcao_ativacao'], ['id']);
				$this->BackPropagation->training($request);
				$configInfo = $this->BackPropagation->getConfigInfo();
				$errorMeasures = $this->BackPropagation->getErrorMeasures();
				$dataOutputsCompare = $this->BackPropagation->getDataOutputsTrainCompare();
				$timeDuration = $this->BackPropagation->getTimeDuration();
				$weightsFinal = $this->BackPropagation->getWeightsFinal();

				$jsonStepByStep = $this->BackPropagation->getDataJsonStepByStep(); //Extract in data json all calculcation envolved by this neural network

				$name = 'Treinamento - '.date('d/m/Y').' ás '.date('H:i:s');

				$posts = ['name' => $name, 
						  'user_id' => $this->Session->read('Auth.User.id'),
						  'nn_type_id' => $this->request->data['Treinamento']['tipo_rede_neural'],
						  'nn_learning_rule_id' => $this->request->data['Treinamento']['regra_aprendizagem'],
						  'nn_activation_function_id' => $funcao_ativacao_id['NeuralNetworkActivationFunction']['id'],
						  'topology' => $request['topology'],
						  'learning_rate' => $request['learningRate'],
						  'momentum' => $request['momentum'],
						  'max_epochs' => $request['epochs'],
						  'max_error' => null,
						  'tmse_result' => $errorMeasures['eqm-total']['train'][$configInfo['epochs_executed']]
						  ];

				foreach($dataOutputsCompare['titles'] as $output => $title){

					$resultados[$output]['name_output'] = $title;	
					$resultados[$output]['rquad'] = $errorMeasures["rquad"]['train']["normalized"][$output]; //$errors["train"]["rquad"][$key];
					$resultados[$output]['rquada'] = $errorMeasures["rquad-adjusted"]['train']["normalized"][$output];		
				}
				
				//
				$urlPost = Configure::read('sistema_url')."treinamentos/salvar";


				$dataInputs = $this->BackPropagation->dataInputs;
				$dataOutputs = $this->BackPropagation->dataOutputs;

				//
				$dataMaxMin = ['inputs' => ['max' => $dataInputs['value_max']['train'], 'min' => $dataInputs['value_min']['train']],
							   'outputs' => ['max' => $dataOutputs['value_max']['train'], 'min' => $dataOutputs['value_min']['train']]
							  ];
			}
		}
		
		$this->set(compact('configInfo','errorMeasures','dataOutputsCompare','timeDuration','posts','weightsFinal','resultados','urlPost','dataMaxMin','jsonStepByStep'));

		$this->layout = "NeuralNetwork.empty";
		$this->render('NeuralNetwork./Elements/graphical-training');

	}
	
	public function salvar(){

		$json = ['message' => 'Nada aconteceu','data' => [], 'url_redirect' => [], 'success' => false];

		if($this->request->is("post")){		

			$posts = json_decode($this->request->data['Treinamento']['posts'], true);
			
			if($this->Treinamento->save(['Treinamento' => $posts]))
			{
				App::uses('File', 'Utility');

				$token = Security::hash($posts['name']." - ".$this->Treinamento->id, 'sha1');
				$this->Treinamento->saveField('token', $token);
				$this->Treinamento->saveField('name', $this->request->data['Treinamento']['nome']);
				$this->Treinamento->saveField('description', $this->request->data['Treinamento']['descricao']);

				//Save training results
				$results = json_decode($this->request->data['Treinamento']['resultados'], true);
				foreach($results as $key => $value){ $results[$key]['training_id'] = $this->Treinamento->id; }
				$this->TreinamentoResultado->saveAll($results); //Safe all results from outputs

				//Save weights from training whitin a file
				/*
				 * More information about that Utility's, access here: 
				 * http://book.cakephp.org/2.0/en/core-utility-libraries/file-folder.html
				 */
				$file = new File(WWW_ROOT.'files/aprendizagens'.DS. $token.'.txt', true);
				$learning = json_decode($file->read(), true);			

				//Prepare the correct format to save the learning
				$pesos = json_decode($this->request->data['Treinamento']['pesos'], true);
				$learning['weights']['hidden_to_input'] = $pesos['hidden_to_input'];
				$learning['weights']['output_to_hidden'] = $pesos['output_to_hidden'];
				$learning['bias_of_each_neuron'] = $pesos['bias_of_each_neuron'];
				$learning['neural_network_topology'] = $posts['topology'];
				$learning['data_max_min']  = json_decode($this->request->data['Treinamento']['data_max_min'], true);

		   		$file->write(json_encode($learning));
		   		$file->close();

		   		//Save all the calculation executed in this neural network
		   		$file_calculation = new File(WWW_ROOT.'files/calculos'.DS. $token.'.txt', true);
		   		$file_calculation->write($this->request->data['Treinamento']['json_step_by_step']);
		   		$file_calculation->close();

		   		$message = "Parabéns! O aprendizado da rede neural foi salvo com sucesso.";
		   		
		   		$this->Session->setFlash($message, "messages/alert-top-page-success", null, 'top-page');
		   		$json = ['message' => $message, 'url_redirect' => Router::url('/sistema/treinamentos/listar', true),'success' => true];
			}else{
				
				$json = ['message' => 'Erro ao salvar', 'data' => $this->request->data];
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($json); die();
	}	

	public function excluir(){
	
		$id = $this->request->params["id"];

		if($id > 0)
		{
		   $this->Treinamento->id = $id;

		   if($this->Treinamento->save(["deleted" => date("Y-m-d H:i:s"), "active" => false]))
		   {
		   		$read = $this->Treinamento->read('name');
		   		$this->Session->setFlash("Treinamento com nome <strong>\"".$read["Treinamento"]["name"]."\"</strong>, foi excluído com sucesso!", "messages/alert-top-page-success", null, 'top-page');
		   }else
		   {
		   		$this->Session->setFlash("Não foi possível excluir o treinamento devido algum erro interno do sistema.", "messages/alert-top-page-danger", null, 'top-page');
		   }

	   } 

	   $this->redirect(["action" => "listar"]);		
	}

	public function visualizar(){ 


		$id = $this->request->params["id"];
		$item = $this->Treinamento->findById($id);

		if(!@$item)
		{
			$this->Session->setFlash("Não foi encontrado nenhum treinamento :(", "messages/alert-top-page-danger", null, 'top-page');	
	    	$this->redirect(["action" => "listar"]);	
	    }
	    
		App::uses('File', 'Utility');
	    
	    //Read the weights fromthe fle
		$file = new File(WWW_ROOT.'files/aprendizagens'.DS. $item["Treinamento"]["token"].'.txt');
		$learning = json_decode($file->read(), true);
		$file->close();

		//Read the calculcation executed by the training
		$file_calc = new File(WWW_ROOT.'files/calculos'.DS. $item["Treinamento"]["token"].'.txt');
		$calculations = json_decode($file_calc->read(), true);
		$file->close();

		//debug($calculations); die();

		//Load weights
		$weights_bias['weights'] = $learning["weights"];
		$weights_bias['bias_of_each_neuron'] = $learning["bias_of_each_neuron"];

		//Variable to help draw the neural network in the page
		$cytoscape = $this->RedeNeural->preparaParaDesenho($learning, $item["Treinamento"]["topology"]);


	    $this->set(compact("item", "weights_bias", "cytoscape", "calculations"));
	    
	}


	public function buscar(){

		$this->redirect(array('action'  => 'listar','busca' => $this->request->data["Treinamento"]["busca"]));  
	}	

}
