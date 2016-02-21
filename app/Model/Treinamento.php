<?php


class Treinamento extends AppModel{
	
	public $name = "Treinamento";	
	public $useTable = "trainings";
	
	public $belongsTo = ["NeuralNetworkType" => ["className" => "NeuralNetworkType",
						                   				   "foreignKey" => "nn_type_id",
										   				   "fields" => ["name"]
						                                   ],
						      "NeuralNetworkLearningRule" => ["className" => "NeuralNetworkLearningRule",
						                   						   "foreignKey" => "nn_learning_rule_id",
										                           "fields" => ["name"]
						                                      ],
						      "NeuralNetworkActivationFunction" => ["className" => "NeuralNetworkActivationFunction",
						                   								 "foreignKey" => "nn_activation_function_id",
										                                 "fields" => ["name","slug"]
						                  						 	],
						      "Usuario" => ["className" => "Usuario",
						                  		 "foreignKey" => "user_id",
										   		 "fields" => ["username"]
						                  		 ],            	            				  				  
						];

	public $hasMany = ["TreinamentoResultado" => ["className" => "TreinamentoResultado",
												  "foreignKey" => "training_id",
												  "dependent" => false
												  ]
					  ];					
						    
	
	public $validate = [];	


}


?>