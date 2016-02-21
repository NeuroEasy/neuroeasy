<?php


App::uses('NeuralNetworkAppController', 'NeuralNetwork.Controller');

class TrainingController extends NeuralNetworkAppController {

	public $name = "Training";
	
	public function save(){

		echo json_encode(['message' => 'This training is saving sucessfull']); die();
	}

}
