<?php

/*
* Classe ConvertData (convert in some formats from inputs and outputs data in various programs for everyone necessity)
*/
class ConvertData{ 

	public static function SaveFileMBP($inputs = array(), $outputs = array(), $titles = false, $my_configs = array()){

		$config = array("filename" => "save_format_datasets","extension" => "csv","type_values" => "desnormalized");

		foreach($my_configs as $key => $value){

			$config[$key] = $value; 
		}


		$rows_data = array(); //Array which contain all data

		//Enable to show the titles in the file
		if($titles){

			//Row from titles (first row)
			$row_titles = array();
			foreach($inputs["titles"] as $title_input){

				$row_titles[] = $title_input;
			}
			foreach($outputs["titles"] as $title_output){

				$row_titles[] = $title_output;
			}
			$rows_data[] = $row_titles;
		}

		//Verifiy quantity of outputs
		$count_outputs = count($outputs["values"]["train"][$config["type_values"]][0]);
		
		//Main code, because programing data to generate format correctly 	
		for($row=0; $row<$count_outputs; $row++){
			
			$row_values = array();

			foreach($inputs["values"]["train"][$config["type_values"]] as $key => $value){
				
				$row_values[] = $value[$row];
			}
			
			
			foreach($outputs["values"]["train"][$config["type_values"]] as $key => $value){
				
				$row_values[] = $value[$row];
			}

			//$row_values[] = $outputs["values"]["train"]["desnormalized"][$row];

			$rows_data[] = $row_values;  	
		}
		

		//Save the file in the path
		$file = @fopen($config["filename"].".".$config["extension"], "w");
		foreach($rows_data as $row){

			//echo implode(";", $row); echo "<br/>";
			@fwrite($file, implode(";", $row)."\r\n");
		}
		@fclose($file);

	}	


	public static function ShowWeightsJSON(array $weights){

		/*
		   Model in JSON format from export the weights:

			{"weights":{
						   "hidden_to_input":{"0":[0.6,1.2],
						   					  "1":[1.35,1.4],
						   					  "2":[-0.3,-0.8],
						   					  "3":[-0.65,1.8]
											 },

						   "output_to_hidden":{"4":[0.5,0.95,
						   							-1.55,0.55
						   						   ]
						   					  },

						   "bias_of_each_neuron":{[-0.75,
						   					  -0.8,
						   					  -1.95,
						   					  1.55,
						   					  -1.55
						   					  ]
						   					}
						}
			} 

		*/

		$arr = array();

		if(@array_key_exists("wEntrada", $weights) && @array_key_exists("wOculta", $weights) && @array_key_exists("bias", $weights))
		{

			$arr["weights"]["hidden_to_input"] = $weights["wEntrada"];
			$arr["weights"]["output_to_hidden"] = $weights["wOculta"];		
			$arr["weights"]["bias_of_each_neuron"] = $weights["bias"];
		}

		return json_encode($arr);	

	}
}

?>