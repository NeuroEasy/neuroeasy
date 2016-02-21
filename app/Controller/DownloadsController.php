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
class DownloadsController extends AppController {


	public $uses = array('NeuralNetworkType','NeuralNetworkLearningRule','NeuralNetworkActivationFunction','TreinamentoResultado','Treinamento');
	public $layout = "empty";
	public $components = array("RedeNeural");

	public function beforeFilter()
	{
		parent::beforeFilter();
	}


	
	public function pesos_bias(){

		$cod = $this->request->params["cod"];

		if(@isset($cod)){

			App::uses('File', 'Utility');

		    //Read the weights fromthe fle
			$file = new File(WWW_ROOT.'files/aprendizagens'.DS. $cod.'.txt');
			$learning = json_decode($file->read(), true);
			$file->close();

			//Prepare file weights/bias for download
			$weights_bias['weights'] = $learning["weights"];
			$weights_bias['bias_of_each_neuron'] = $learning["bias_of_each_neuron"];
			$weights_bias_json = json_encode($weights_bias);	

			// Retrieve the file ready for download
			$file_tmp = new File(WWW_ROOT.'files/tmp/pesos-bias'.DS.$cod.'-weights-bias.json', true, 0644);
			$file_tmp->write($weights_bias_json);	
			$file_tmp->close();

		 	// Run any pre-download logic here.
		 	// Send file as response
			$this->response->file(
				$file_tmp->path,
				array(
					'download' => true,
					'name' => 'weights-bias.json'
				)
			);

		 	return $this->response;
	 	
	 	}else{
	 		$this->Session->setFlash("Erro no download", "messages/alert-top-page-danger", null, 'top-page');
	 		$this->redirect(["controller" => "treinamentos","action" => "listar"]);	
	 	}
	}

	public function codigo_fonte_feedforward(){

		//Set the parameters from the urls
		$linguagem = $this->request->params["linguagem"];
		$cod = $this->request->params["cod"];

		if(@isset($cod) && @isset($linguagem)){	

			//Initializing all lib needs
			App::uses('File', 'Utility');
			App::uses('CodeGeneration', 'Lib/CodeGeneration');
			$Code = new CodeGeneration();

			//Get request database
			$item = $this->Treinamento->findByToken($cod);

		    //Read the weights fromthe fle
			$file = new File(WWW_ROOT.'files/aprendizagens'.DS. $cod.'.txt');
			$learning = json_decode($file->read(), true);
			$file->close();

			
			//Set the parameters necessary for the generate source code
			$params = ["topology" => $item["Treinamento"]["topology"],  
					   "weights" => $learning["weights"], 
					   "bias" => $learning["bias_of_each_neuron"], 
					   "data_max_min" => $learning["data_max_min"],
					   "activation_function" => $item["NeuralNetworkActivationFunction"]["slug"]
					   ];

			$response = $Code->feedforward($linguagem, $params);


			// Retrieve the file ready for download
			$file_tmp = new File(WWW_ROOT.'files/tmp/codigo-fonte-feedforward'.DS.$cod.'-NeuroEasyNN.'.$response['extension'], true, 0644);
			$file_tmp->write($response['source_code']);	
			$file_tmp->close();

		 	// Run any pre-download logic here.
		 	// Send file as response
			$this->response->file(
				$file_tmp->path,
				array(
					'download' => true,
					'name' => 'NeuroEasyNN.'.$response['extension']
				)
			);

			return $this->response;

	 	}else{
	 		$this->Session->setFlash("Erro no download", "messages/alert-top-page-danger", null, 'top-page');
	 		$this->redirect(["controller" => "treinamentos","action" => "listar"]);	
	 	}		
	}
	
}
