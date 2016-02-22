<?php

ini_set('memory_limit', '1100M'); 
set_time_limit(3600);

/*
* Classe Backpropagation feito pelo autor Thomas Kanzig (TK) no qual toda operação MLP 
* e do algoritmo backpropagation está escrito aqui. Dúvidas? Mande seu contato
* nesse email: thomas.kanzig@gmail.com
*
* Class Backpropagation made by the author Thomas Kanzig (TK) in which all MLP operation 
* and backpropagation algorithm is written here. Questions? Send your contact in
* this email: thomas.kanzig@gmail.com
*
*/

App::uses('PrintCalculation', 'NeuralNetwork.Lib'); 
App::uses('Util', 'NeuralNetwork.Lib');
App::uses('ConvertData', 'NeuralNetwork.Lib'); 

  
class BackPropagationComponent extends Component {

	public $entradaOri,$esperadoOri;
	public $countNeuronsInputLayer;
	public $countNeuronsHiddenLayer = 2;
	public $countNeuronsOutputLayer = 1;

	//initialize output
	private $y;
	private $w;
	private $wEntrada;
	private $wOculta;

	//initializing errors
	private $delta =array();

	//initializing bias
	private $b =array();

	//learning rate - taxa de aprendizagem
	private $learningRate=0.2;
	private $count = 0;
	private $epochs = 100000; //quantidade de épocas (iterações)
	private $loop = true;
	private $bias;
	private $deltaDaSaida;
	private $erro;
	private $erroInt;
	private $entrada;
	private $esperado;
	private $erroEstimado;
	private $erroTotal;
	private $provided;

	private $x_min,$x_max;
	private $new_training = false;
	private $fativacao = "sigmoide";
	public $errorResults = array();

	private $percentageToTrain = 100; //Valor corresponde por %
	private $percentageToValidation = 0; //Valor corresponde por %

	private $momentum=0; //could between 0,6 < β < 0,9.

	private $keys_selected = array(); //Variaval utilizado para guardar os índices das entradas utilizados

	private $inputOriAll;
	private $outputOriAll;
	private $countCalculateDelta = 0;
	private $countCalculateY = 0;

	private $countTotalSamples=array("train" => 0, "validation" => 0);
	private $outputsResults=array();
	private $showDescription=false;
	private $weightInit=0.9;
	private $weightRandom=false; 
	private $deltaEntradaSum=array();
	private $deltaOcultaSum=array();
	private $deltaBiaSum=array();
	private $errorMinimum=6;
	private $orderOutputs=false;
	private $eliminateDuplications=true;
	private $errorsTraining=array();
	private $initializingAutoWeight=true;
	private $weights = array();
	private $errors = array("train" => array(), "validation" => array());
	private $showEachNewWeights = true;
	private $separatorData = ";";
	private $topology;
	private $hasTitlesDataSets = true;
	public $dataInputs = array();
	public $dataOutputs = array();
	public $timeDuration = "";
	public $weightsFinal = array();
	//public $PrintCalculation;
	public $countNeuronsTotal = 0;
	private $orderStep = 0;
	private $deltaDaSaidaNovo = array();
	private $deltaNovo = array();
	public $normalizeDataSets = false;
	public $siteUrl = "/";
	
	/*
	* In this attribute store the follow error types:
	* 
	* stq => Soma Total de Quadrados
	* sqe => Soma dos Quadrados Explicada
	* sqreg => Soma dos Quadrados de REGressão
	*/
	public $errorMeasures = array();



    public function __construct(){

    	//Main configuration from neural network

    	$this->showDescription = true;
    	$this->showEachNewWeights = false;  
    	$this->errorMinimum = 6;
 		//$this->orderOutputs = false; //Let this ever 'false', because I use many outputs
		$this->eliminateDuplications = false;  
		$this->hasTitlesDataSets = true; //If in the datasets don't have titles, than please set this attribute as true
		$this->siteUrl = Configure::read("site_url");	

    }


    /*
	* Initializing traintement
    */
	public function training(array $config){
    	
    	//$this->PrintCalculation = new PrintCalculation();	

    	//Start the time counter 
    	$time_start = new DateTime('now');
    	 
		//Prepare the attributes from this class
		$this->prepare_attributes($config);

    	//Select input according for the percentage to train in this neural network
    	$this->select_inputs_distribute();

    	//Normalizing datas
		$this->normalizing_all_datas();

    	//Initializing weights (weight and bias)
    	$this->initializing_weights();

    	$epochs = $this->epochs;
		$store_epoch=0;

		$total_samples_training = $this->countTotalSamples["train"];
		$total_samples_validation = $this->countTotalSamples["validation"];
    
    	while($this->loop){
			
    		for($epoch=1; $epoch<=$epochs; $epoch++){

    			//When only print the calulcation in first and last epoch by this algorithm
    			if($epoch == 1 || $epoch == $epochs){ $this->showDescription = true; }else{ $this->showDescription = false; }

				for($sample=0; $sample<$total_samples_training; $sample++){

					$this->feedforward($epoch, $sample, "train");
					$this->feedbackward($epoch, $sample, "train");
				}

				$this->calculateErrors($epoch);
			
    		}

    		$this->loop = false; //Para as iterações

    		if(!$this->loop){
    			$this->calculateRegression($epoch);
    		}
    	}

    	$this->epochs_executed = ($epoch-1); //Store which epochs was executed

    	//Stop the time counter
    	$time_finish = new DateTime('now');

		$interval = $time_start->diff($time_finish);
		$this->timeDuration = $interval->format("%H:%I:%S");    

		$this->weightsFinal = ["hidden_to_input" => $this->wEntrada, "output_to_hidden" => $this->wOculta, "bias_of_each_neuron" => $this->bias];	

	}	

    /*
	* All feed-forward process included here
    */
    public function feedforward($epoch, $sample, $type_data){	

		$this->calculateY($sample, $epoch, $type_data);
	}

    /*
	* All feed-backward process included here
    */
    public function feedbackward($epoch, $sample, $type_data){
	   	
	   	if($type_data == "train"){	
			
			$this->calculateDelta($sample, $epoch, $type_data);
			$this->calculateWeightChange($sample, $epoch, $type_data);
		} 

	}

    /*
	* Initializing weights
    */
	public function initializing_weights(){
	    
	    $html = "";
		if($this->showDescription){ 
			$html .= "<div class='panel panel-primary panel-red'><div class='panel-heading'><strong>Valores iniciais dos Pesos/Bias Iniciais</strong></div><div style='padding-bottom:0px;' class='panel-body'><div class='row'>";
			
			$html .= "<div class='col-md-12'>";
			if(@array_key_exists("fixed", $this->weightInit)){
				$html .= "Os pesos e bias iniciais tiveram todos um valor fixo <strong>".$this->weightInit['fixed']."</strong><br/><br/>";
			}elseif(@array_key_exists("smaller", $this->weightInit) && @array_key_exists("larger", $this->weightInit)){
				$html .= "Os pesos e bias iniciais tiveram os valores randômicos entre <strong>".$this->weightInit['smaller']."</strong> e <strong>".$this->weightInit['larger']."</strong><br/><br/>";
			}elseif(@array_key_exists("costum", $this->weightInit)){
				$html .= "Os pesos e bias iniciais tiveram os valores contido no arquivo de importação<br/><br/>";
			}
			$html .= "</div>";
		}

		if(@!array_key_exists("costum", $this->weightInit)){

			$w = 1;

			if($this->showDescription){ $html .= "<div class='col-md-4'>"; }
		     // Camada de Entrada
		   //  echo '<br><b>Gerando os pesos aleatórios da camada de entrada ... </b><br>' ;
		      for ($i = 0; $i < $this->countNeuronsHiddenLayer; $i++) {
		        for ($j = 0; $j < $this->countNeuronsInputLayer; $j++) {
		        	
		        	if($this->weightRandom){
		             	$this->wEntrada[$i][$j] = rand(($this->weightInit['smaller']*20),($this->weightInit['larger']*20))/20;     // gera numero aleatório de -2 a 2;
		             	//echo  number_format($w[$i][$j],2) , ' Peso gerado';
		         	}else{
		         	 	$this->wEntrada[$i][$j] = $this->weightInit['fixed'];		
		         	}

		         	if($this->showDescription){$html .= "Entrada ".($j+1)." -> Neurônio ".($i+1)." = ".$this->wEntrada[$i][$j]." (w{$w})<br/>"; }

		         $w++;		
		         }
		     }
		     if($this->showDescription){ $html .= "<br/></div><div class='col-md-4'>"; }
		     // Camada Oculta
		   //  echo '<br><b>Gerando os pesos aleatórios da camada oculta ... </b><br>' ;
		     for ($i = $this->countNeuronsHiddenLayer; $i < $this->countNeuronsOutputLayer + $this->countNeuronsHiddenLayer; $i++) {
		        for ($j = 0; $j < $this->countNeuronsHiddenLayer; $j++) {
		             
		             if($this->weightRandom){
		             	$this->wOculta[$i][$j] = rand(($this->weightInit['smaller']*20),($this->weightInit['larger']*20))/20;     // gera numero aleatório de -2 a 2;
		//             echo  number_format($w[$i][$j],2) . ' ';
		             }else{
		         	 	$this->wOculta[$i][$j] = $this->weightInit['fixed'];		
		         	}

		         	if($this->showDescription){ $html .= "Neurônio ".($j+1)." -> Neurônio ".($i+1)." = ".$this->wOculta[$i][$j]." (w{$w})<br/>"; }
		        $w++; 	
		        }
		     }
		     if($this->showDescription){ $html .= "<br/></div><div class='col-md-4'>"; }
		     $b = 1;
		     // Inicializa Bias
		//     echo '<br><b>Gerando os bias aleatórios ... </b><br>' ;
		     for ($j = 0; $j < $this->countNeuronsHiddenLayer + $this->countNeuronsOutputLayer; $j++){
		        
		        if($this->weightRandom){
		        	$this->bias[$j] = rand(($this->weightInit['smaller']*20),($this->weightInit['larger']*20))/20;
		//        echo  $bias[$j] . ' ';
		        }else{
		         	$this->bias[$j] = $this->weightInit['fixed'];		
		        }

		        if($this->showDescription){  $html .= "Neurônio ".($j+1)."  = ".$this->bias[$j]." (b{$b})<br/>"; }
		     $b++;   
		     }

		     if($this->showDescription){  $html .= "<br/></div>"; }

		}else{

			$this->wEntrada = $this->weightInit["costum"]["hidden_to_input"];
			$this->wOculta  = $this->weightInit["costum"]["output_to_hidden"];
			$this->bias     = $this->weightInit["costum"]["bias_of_each_neuron"];

		}     
		
		if($this->showDescription){ $html .= "</div></div></div>"; }
		if($this->showDescription){  PrintCalculation::setHtml($html, "fase-1", "passo-2"); }
		
	}		

	/*
    * 
	*/
	private function select_inputs_distribute(){

		//Verify if that procentage is correct distribuid
		if(($this->percentageToTrain + $this->percentageToValidation) > 100){
			echo "Percentage from train and validation number inputs is exceeded over 100 percent. Please verify this distribuite again."; die();
		}elseif($this->percentageToTrain == 0){
			echo "You must obtain some percentage number by the training"; die();
		}

		$this->keys_selected["no"] = $this->keys_selected["yes"] = array();
		
		$count_total = count($this->dataOutputs["values"]["all"]["desnormalized"][0]);

		//Select random the keys from inputs/outputs for train - Start
		//echo (($count_total/100)*$this->percentageToTrain); echo "-";
		$count_select_train = round((($count_total/100)*$this->percentageToTrain), 0);
		$count_not_select_train = $count_total-$count_select_train;

		$keys = Util::randomGen(0, $count_total-1, $count_select_train);
		sort($keys);
		$this->keys_selected["yes"] = $keys;
		

		for($key=0; $key<$count_total; $key++){

			if(!in_array($key ,$this->keys_selected["yes"])){
				$this->keys_selected["no"][] = $key;
			}
		}
		//Select random the keys from inputs/outputs for train - End
	
		
		//A partir daqui a variavel $this->keys_selected já contem as chaves(keys) das entradas que vai ser treinados e não	
		
		//Tirando os dados esperado do treinamento	
		$linha = 0;
		foreach($this->keys_selected["yes"] as $key_yes){
			
			//$this->dataOutputs["values"]["train"]["desnormalized"][$linha] = $this->dataOutputs["values"]["all"]["desnormalized"][$key_yes];

			foreach($this->dataOutputs["values"]["all"]["desnormalized"] as $coluna => $value){

				$this->dataOutputs["values"]["train"]["desnormalized"][$coluna][$linha] = $this->dataOutputs["values"]["all"]["desnormalized"][$coluna][$key_yes];	
			}


			foreach($this->dataInputs["values"]["all"]["desnormalized"] as $coluna => $value){

				$this->dataInputs["values"]["train"]["desnormalized"][$coluna][$linha] = $this->dataInputs["values"]["all"]["desnormalized"][$coluna][$key_yes];	
			}

			$linha++;	
		}

    	//Count Neurons in input layer
    	$this->countNeuronsInputLayer = count($this->dataInputs["values"]["train"]["desnormalized"]);
    	$this->countNeuronsOutputLayer = count($this->dataOutputs["values"]["train"]["desnormalized"]);

    	//Count total of outputs
    	$this->countTotalSamples["train"] = $count_select_train;	


    	if($this->percentageToValidation > 0)
    	{
    		//echo "Antes:"; var_dump($this->keys_selected); echo "<br/>";

			//Select random the keys from inputs/outputs for valitation - Start
			//echo (($count_total/100)*$this->percentageToValidation); echo "-";
			$count_select_validation = round((($count_total/100)*$this->percentageToValidation), 0);
			$count_rest = count($this->keys_selected["no"]);

			$keys_validation = Util::randomGen(0, $count_rest-1, $count_select_validation);
			sort($keys_validation);
			//echo "Select Valid:"; var_dump($keys_validation); echo "<br/>";
			
			//Create params from outputs 'validation' - Start
			$linha = 0;
			foreach($keys_validation as $key_no){
				
				$key_output = $this->keys_selected["no"][$key_no];

				//$this->dataOutputs["values"]["validation"]["desnormalized"][$linha] = $this->dataOutputs["values"]["all"]["desnormalized"][$key_output];

				foreach($this->dataOutputs["values"]["all"]["desnormalized"] as $coluna => $value){

					$this->dataOutputs["values"]["validation"]["desnormalized"][$coluna][$linha] = $this->dataOutputs["values"]["all"]["desnormalized"][$coluna][$key_output];	
				}

				foreach($this->dataInputs["values"]["all"]["desnormalized"] as $coluna => $value){

					$this->dataInputs["values"]["validation"]["desnormalized"][$coluna][$linha] = $this->dataInputs["values"]["all"]["desnormalized"][$coluna][$key_output];	
				}

				$linha++;

				//Update the keys selected and not selected
				$this->keys_selected["yes"][] = $key_output;
				unset($this->keys_selected["no"][$key_no]);	
			}
			//Create params from outputs 'validation' - End

			//Select random the keys from inputs/outputs for valitation - End  
			$this->countTotalSamples["validation"] = $count_select_validation;	
		}	
		
		//Count total of neurons in this neural network topology
		$this->countNeuronsTotal = ($this->countNeuronsHiddenLayer+$this->countNeuronsOutputLayer);

		PrintCalculation::setHtml(PrintCalculation::helperOverviewDataDistribuited($this->dataInputs, $this->dataOutputs), "fase-1", "passo-1");

		//Verify
		if(($this->countTotalSamples["validation"] + $this->countTotalSamples["train"]) !=  $count_total){
			echo "A distribuição dos dados de treinamento e validação obteve um pequeno problma. Por favor, insira outros valores!";
			die();
		}

	}


    /*
	* Normalizing all datas (input, outputs and etc) needs for this neural network, in addition make calculate the min and max of datasets
	* media from outputs. 
    */
	public function normalizing_all_datas(){

		//$html = "";
		$normalize = $this->normalizeDataSets;

		//This is for data training - start
		if($this->countTotalSamples["train"] > 0){

			if($this->showDescription){ 
			   	PrintCalculation::setHtml("<div class='panel panel-primary panel-red'><div class='panel-heading'><strong>Normalização dos Dados de Treinamento</strong></div><div style='padding-bottom:0px;' class='panel-body'><div class='row'><div class='col-md-6'>", "fase-1", "passo-3");
				if(!$normalize){ PrintCalculation::setHtml("Não foi realizado nenhuma normalização dos dados.<br/>", "fase-1", "passo-3"); }
			}

			//Calculate value max and min of inputs datas
			foreach($this->dataInputs["values"]["train"]["desnormalized"] as $num => $values){

				if($this->showDescription && $normalize){ 
					PrintCalculation::setHtml("<br/><strong> Entrada ".($num+1).":</strong><br/>", "fase-1", "passo-3");
				}

				$values_max_min = Util::getMinAndMax($values);
				$this->dataInputs["value_max"]["train"][$num] = $values_max_min["max"];
				$this->dataInputs["value_min"]["train"][$num] = $values_max_min["min"];

				if($normalize){
				$this->dataInputs["values"]["train"]["normalized"][$num] = Util::normalize($this->dataInputs["values"]["train"]["desnormalized"][$num], $this->dataInputs["value_max"]["train"][$num], $this->dataInputs["value_min"]["train"][$num], false, $this->showDescription);
				}else{
				$this->dataInputs["values"]["train"]["normalized"][$num] = $this->dataInputs["values"]["train"]["desnormalized"][$num];	
				}
			}

			//Calculate value max and min of outputs datas
			foreach($this->dataOutputs["values"]["train"]["desnormalized"] as $num => $values){

				if($this->showDescription && $normalize){ 
					PrintCalculation::setHtml("<br/><strong> Saída ".($num+1).":</strong><br/>", "fase-1", "passo-3");
				}

				$values_max_min = Util::getMinAndMax($values);
				$this->dataOutputs["value_max"]["train"][$num] = $values_max_min["max"];
				$this->dataOutputs["value_min"]["train"][$num] = $values_max_min["min"];

				if($normalize){
				$this->dataOutputs["values"]["train"]["normalized"][$num] = Util::normalize($this->dataOutputs["values"]["train"]["desnormalized"][$num], $this->dataOutputs["value_max"]["train"][$num], $this->dataOutputs["value_min"]["train"][$num], false, $this->showDescription);
				}else{
				$this->dataOutputs["values"]["train"]["normalized"][$num] = $this->dataOutputs["values"]["train"]["desnormalized"][$num];
				}

				$value_media_output = $this->dataOutputs["value_media"]["train"]["normalized"][$num] = Util::calculateMedia($this->dataOutputs["values"]["train"]["normalized"][$num]);					
				
				$STQ = 0;
				foreach($this->dataOutputs["values"]["train"]["normalized"][$num] as $sample => $output){
					$STQ += pow($output-$value_media_output,2);
				}
				$this->dataOutputs["stq"]["train"]["normalized"][$num] = $STQ;

			}

			if($this->showDescription){  
				PrintCalculation::setHtml("</div> <div class='col-md-6'><br/><strong>Fórmula para Normalização:</strong><br/><br/> <img src='".$this->siteUrl."/img/sistema/formulas/normalization.jpg' alt='Fórmula de Normalização' title=''/></div> </div>", "fase-1", "passo-3");
			}
		}
		//This is for data training - end

		//This is for data validation - start
		if($this->countTotalSamples["validation"] > 0){

			if($this->showDescription){ 
				PrintCalculation::setHtml("<br/><span style='background: #000;color: #FFF;padding: 2px;font-weight:bold;'>Etapa de Normalização dos dados de Validação:</span><div class='row'><div class='col-md-6'><br/>", "fase-1", "passo-3");
			}

			//Calculate value max and min of inputs datas
			foreach($this->dataInputs["values"]["validation"]["desnormalized"] as $num => $values){

				if($this->showDescription){ 
					PrintCalculation::setHtml("<br/><strong> Entrada ".($num+1).":</strong><br/>", "fase-1", "passo-3");
				}

				$values_max_min = Util::getMinAndMax($values);
				$this->dataInputs["value_max"]["validation"][$num] = $values_max_min["max"];
				$this->dataInputs["value_min"]["validation"][$num] = $values_max_min["min"];

				if($normalize){
				$this->dataInputs["values"]["validation"]["normalized"][$num] = Util::normalize($this->dataInputs["values"]["validation"]["desnormalized"][$num], $this->dataInputs["value_max"]["validation"][$num], $this->dataInputs["value_min"]["validation"][$num], false, $this->showDescription);
				}else{
				$this->dataInputs["values"]["validation"]["normalized"][$num] = $this->dataInputs["values"]["validation"]["desnormalized"][$num];
				}
			}

			//Calculate value max and min of inputs datas
			foreach($this->dataOutputs["values"]["validation"]["desnormalized"] as $num => $values){

				if($this->showDescription){ 
					PrintCalculation::setHtml("<br/><strong> Saída ".($num+1).":</strong><br/>", "fase-1", "passo-3");
				}

				$values_max_min = Util::getMinAndMax($values);
				$this->dataOutputs["value_max"]["validation"][$num] = $values_max_min["max"];
				$this->dataOutputs["value_min"]["validation"][$num] = $values_max_min["min"];

				if($normalize){
				$this->dataOutputs["values"]["validation"]["normalized"][$num] = Util::normalize($this->dataOutputs["values"]["validation"]["desnormalized"][$num], $this->dataOutputs["value_max"]["validation"][$num], $this->dataOutputs["value_min"]["validation"][$num], false, $this->showDescription);
				}else{
				$this->dataOutputs["values"]["validation"]["normalized"][$num] = $this->dataOutputs["values"]["validation"]["desnormalized"][$num];					
				}
				$this->dataOutputs["value_media"]["validation"]["normalized"][$num] = Util::calculateMedia($this->dataOutputs["values"]["validation"]["normalized"][$num]);
			
				$value_media_output = $this->dataOutputs["value_media"]["validation"]["normalized"][$num] = Util::calculateMedia($this->dataOutputs["values"]["validation"]["normalized"][$num]);					
				
				$STQ = 0;
				foreach($this->dataOutputs["values"]["validation"]["normalized"][$num] as $sample => $output){
					$STQ += pow($output-$value_media_output,2);
				}
				$this->dataOutputs["stq"]["validation"]["normalized"][$num] = $STQ;

			}

			if($this->showDescription){  
				PrintCalculation::setHtml("</div> <div class='col-md-6'><br/><strong>Fórmulas:</strong><br/> </div> </div>", "fase-1", "passo-3");
			}		
		}
		//This is for data validation - end

		if($this->showDescription){ 
		   	PrintCalculation::setHtml("<br/></div></div>", "fase-1", "passo-3");
		}
					
	}	


	public function calculateY($sample, $epoch, $type_data = "train"){
	   
	   //The variables bellow its used only for help the print in ths screen
	   $print_n = 0; 
	   $print_soma = array();
	   $html = "";

	   if($this->showDescription){ 
			$html .= "<div class='panel panel-primary'><div class='panel-heading'><strong>{$epoch}ª Epoca (Etapa Forward) - ".($sample+1)."ª Amostra (".$type_data.")</strong></div><div style='padding-bottom:0px;' class='panel-body'>";
	   }

	   for ($neuron=0;$neuron< $this->countNeuronsHiddenLayer; $neuron++){

	    if($this->showDescription){ 
	   	   $html .= "<div class='panel panel-default'><div class='panel-heading' role='tab' id='heading-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'><a role='button' data-toggle='collapse' data-parent='#accordion' href='#collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."' aria-expanded='true' aria-controls='collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'>Cálculo do valor N".($neuron+1)."</a></div><div id='collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='heading-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'><div class='panel-body'><div class='row'><div class='col-md-8'><strong>Mutliplicação entrada(x) x peso(w):</strong><br/>"; 
	    }

	      $Somatorio = 0;
	      for ($neuron_input=0; $neuron_input < $this->countNeuronsInputLayer;$neuron_input++){

				if($this->showDescription){
					$html .= "(X".($neuron_input+1).") x (peso X".($neuron_input+1)."->N".($neuron+1)."):<br/>";
				}

	            $Somatorio += $print_n = $this->dataInputs["values"][$type_data]["normalized"][$neuron_input][$sample] * $this->wEntrada[$neuron][$neuron_input];
	            if($this->showDescription){ $html .= $this->dataInputs["values"][$type_data]["normalized"][$neuron_input][$sample]." x ".$this->wEntrada[$neuron][$neuron_input]." = ".$print_n."<br/>"; $print_soma[] = $print_n; }
	      }

	      if($this->showDescription){ $print_soma[] = $this->bias[$neuron];}

		  if($this->showDescription){ 
		   	 $html .= "<br/><strong>Saída do Neurônio (N".($neuron+1).") com F(x) = (y)</strong><br/>"; 
		  }

	      $fx = $this->functionActivation($Somatorio + $this->bias[$neuron], implode(" + ", $print_soma));
	      $this->y[$type_data][$sample][$neuron] = $fx["num"];
	      if($this->showDescription){  $html .= $fx["print"]."</div> <div class='col-md-4'><strong>Fórmula da Soma:</strong><br/> <img src='".$this->siteUrl."/img/sistema/formulas/soma.jpg' alt='Soma' title=''/><br/> <strong>Fórmula da Função de Ativação:</strong><br/> <img src='".$this->siteUrl."/img/sistema/formulas/funcao-ativacao-".$this->fativacao.".jpg' alt='Função de Ativação' title=''/></div> </div> </div></div></div>"; $print_soma = array(); }
	      
	      
	   		
	   }

	   
	   // Camada oculta e saida
	   for ($neuron= $this->countNeuronsHiddenLayer;$neuron< $this->countNeuronsHiddenLayer + $this->countNeuronsOutputLayer; $neuron++){

		   if($this->showDescription){ 
		   		$html .= "<div class='panel panel-default'><div class='panel-heading' role='tab' id='heading-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'><a role='button' data-toggle='collapse' data-parent='#accordion' href='#collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."' aria-expanded='true' aria-controls='collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'>Cálculo do valor N".($neuron+1)."</a></div><div id='collapse-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='heading-neuron-".($neuron+1)."-sample-".$sample."-epoca".$epoch."'><div class='panel-body'><div class='row'><div class='col-md-8'><strong>Mutliplicação N(oculta) x peso(w):</strong><br/>"; 
		   }

	      $Somatorio = 0;
	      for ($neuron_hidden=0; $neuron_hidden < $this->countNeuronsHiddenLayer;$neuron_hidden++){
	            
				if($this->showDescription){
					$html .= "(N".($neuron_hidden+1).") x (peso N".($neuron_hidden+1)."->N".($neuron+1)."):<br/>";
				}

	            $Somatorio += $print_n = $this->y[$type_data][$sample][$neuron_hidden] * $this->wOculta[$neuron][$neuron_hidden]; // aqui a entrada é a saida do neuronio anterior
	      		
	      		if($this->showDescription){ $html .= $this->y[$type_data][$sample][$neuron_hidden]." x ".$this->wOculta[$neuron][$neuron_hidden]." = ".$print_n."<br/>"; $print_soma[] = $print_n; }
	      }

	      if($this->showDescription){ $print_soma[] = $this->bias[$neuron];}

		  if($this->showDescription){ 
		   	 $html .= "<br/><strong>Saída do Neurônio (N".($neuron+1).") com F(x) = (y)</strong><br/>"; 
		  }

	      $fx = $this->functionActivation($Somatorio + $this->bias[$neuron], implode(" + ", $print_soma));
	   	  $this->y[$type_data][$sample][$neuron] = $fx["num"];
	      if($this->showDescription){  $html .= $fx["print"]."</div> <div class='col-md-4'><strong>Fórmula da Soma:</strong><br/> <img src='".$this->siteUrl."/img/sistema/formulas/soma.jpg' alt='Soma' title=''/><br/> <strong>Fórmula da Função de Ativação:</strong><br/> <img src='".$this->siteUrl."/img/sistema/formulas/funcao-ativacao-".$this->fativacao.".jpg' alt='Função de Ativação' title=''/></div> </div> </div></div></div>"; $print_soma = array(); }
	   }

	   if($this->showDescription){ 
	   		$html .= "</div></div>";
	   		PrintCalculation::setHtml($html, "fase-2", "passo-1", "epoca-{$epoch}-amostra-".($sample+1)."-".$type_data,$this->orderStep);
	   		$this->orderStep++;	
	   }		   

	   //echo $html;
	}

	private function functionActivation($exponent = null, $print_exponent = null){

		$response= array();

		if($this->fativacao == "tangente-hiperbolica"){

			$response["num"] = tanh($exponent); //tanghiperb
			if($this->showDescription){ $response["print"] = "tanh(".$print_exponent.") = ".$response["num"]."<br/>"; }

		}else{

			$response["num"] = (1.0/(1 + exp( (-1)* $exponent ) )); //sigmoid 
			if($this->showDescription){ $response["print"] = "1.0/(1 + exp((-1) x ".$print_exponent.")) = ".$response["num"]."<br/>"; }
		}

		return $response;
	} 
	
	
	private function derivateFunctionActivation($y = null){

		$response= array();
		if($this->fativacao == "tangente-hiperbolica"){

			$response["num"] = (1-pow(tanh($y),2));
			$response["print"] = "(1- tanh(".$y.") x tanh(".$y."))"; 
		}elseif($this->fativacao == "sigmoide"){

			$response["num"] = $y*(1-$y);
			$response["print"] = $y." x (1-".$y.")";
		}

		return $response;
	} 
	

	 public function getDataOutputsTrainCompare(){

	 	$data = array();

	 	$data["provided"] = $this->provided["train"];
	 	$data["desired"] = $this->dataOutputs["values"]["train"]["desnormalized"];
	 	$data["count"] = $this->countNeuronsOutputLayer;
	 	$data["total_samples"] = $this->countTotalSamples["train"];
	 	$data["titles"] = $this->dataOutputs["titles"];

	 	return $data;

	 }

	 public function verifiyIncreaseErrorPrediction($epoch, $type_data = "train"){

	 	$increased = false;

	 	if($epoch > 1){
	 		if($this->errors[$type_data]["normalized"][$epoch]["mse"] > $this->errors[$type_data]["normalized"][$epoch-1]["mse"]){

	 			$increased = true;
	 		}
	 	}

	 	return $increased;
	 }

	 public function prepare_attributes(array $config){

    	//var_dump($config); die();
    	$campos = array("countNeuronsHiddenLayer","learningRate","epochs","fativacao","weightRandom","weightInit","momentum","separatorData","topology","percentageToTrain","percentageToValidation","normalizeDataSets"); 
    	foreach($campos as $campo){
	 		if(@array_key_exists($campo, $config)){
	    	 	if(!@empty($config[$campo])){
	    	 		$this->$campo = $config[$campo];
	    	 	}
	    	}   	 	
    	}

    	//echo $this->fativacao; die();
    	$config["data"] = Util::formatDataInputsAndOutputs($config["data"], $this->eliminateDuplications, $this->separatorData, $this->hasTitlesDataSets);
    	if(@!empty($config["data"]["number_hiddens"])){ $this->countNeuronsHiddenLayer = $config["data"]["number_hiddens"]; }
    	//var_dump($config["data"]); die();


    	$this->dataInputs["values"]["all"]["desnormalized"] = $config["data"]["inputs"]["values"];
    	$this->dataInputs["titles"] = $config["data"]["inputs"]["titles"];

    	$this->dataOutputs["values"]["all"]["desnormalized"] = $config["data"]["outputs"]["values"];
    	$this->dataOutputs["titles"] = $config["data"]["outputs"]["titles"];

    	//var_dump($this->dataInputs); die();

	 }

	 /*
	 * Getters Methods follow below
	 */

	 public function getTimeDuration(){

	 	return $this->timeDuration;
	 }

	 public function getWeightsFinal(){

	 	return $this->weightsFinal;
	 }

	 public function getErrorMeasures(){

	 	return $this->errorMeasures;
	 }

	 public function getConfigInfo(){
	 	$response = array();

	 	$response['type_data']['validation'] = array('percentage' => $this->percentageToValidation, 'count_samples' => $this->countTotalSamples["validation"]);
	 	$response['type_data']['train'] = array('percentage' => $this->percentageToTrain, 'count_samples' => $this->countTotalSamples["train"]);
	 	$response['epochs_executed'] = $this->epochs_executed;
	 	return $response;
	 }

	 public function getDataJsonStepByStep(){

	 	return PrintCalculation::generateDataJsonStepByStep();
	 }



	private function calculateDelta($sample, $epoch, $type_data = "train"){
	   
	   $html = "";
	   $html .= "<div class='panel panel-primary panel-purple'><div class='panel-heading'><strong>{$epoch}ª Epoca (Etapa Backward) - ".($sample+1)."ª Amostra (".$type_data.")</strong></div><div style='padding-bottom:0px;' class='panel-body'><div class='row'><div class='col-md-8'><br/>";

	   if($this->showDescription){ $html .= "<strong>Calculando Delta:</strong>"; }

	   //Delta Output Neurons
	   for ($neuron= $this->countNeuronsHiddenLayer;$neuron< $this->countNeuronsHiddenLayer + $this->countNeuronsOutputLayer; $neuron++){

	   		$derivateFa = $this->derivateFunctionActivation($this->y[$type_data][$sample][$neuron]);
	   	  	$this->deltaDaSaidaNovo[$sample][$neuron-$this->countNeuronsHiddenLayer] = $derivateFa["num"]*($this->dataOutputs["values"][$type_data]["normalized"][$neuron-$this->countNeuronsHiddenLayer][$sample] - $this->y[$type_data][$sample][$neuron]);
	   	  	   	  	
	   	  	if($this->showDescription){
	   	  	$html .= "<br/>";
	   	  	$html .= $derivateFa["print"]." x (".$this->dataOutputs["values"][$type_data]["normalized"][$neuron-$this->countNeuronsHiddenLayer][$sample]." - ".$this->y[$type_data][$sample][$neuron].") = ".$this->deltaDaSaidaNovo[$sample][$neuron-$this->countNeuronsHiddenLayer];
	  		}
	   }

	   $html .= "<br/>";

	   $sum = 0;

	   //Delta Hidden Neurons
	   for($neuron_hidden=0; $neuron_hidden < $this->countNeuronsHiddenLayer;$neuron_hidden++)
	   {
	   		for($neuron= $this->countNeuronsHiddenLayer;$neuron< $this->countNeuronsHiddenLayer + $this->countNeuronsOutputLayer; $neuron++)
	   		{
	     		$sum += $this->wOculta[$neuron][$neuron_hidden] * $this->deltaDaSaidaNovo[$sample][$neuron-$this->countNeuronsHiddenLayer] * $this->momentum;
	   		}

	   		$derivateFa = $this->derivateFunctionActivation($this->y[$type_data][$sample][$neuron_hidden]);
			$this->deltaNovo[$sample][$neuron_hidden] =  $derivateFa["num"] * $sum;
			
			if($this->showDescription){	
	   		$html .= "<br/>";
	   		$html .= $derivateFa["print"]." x ".$sum." = ".$this->deltaNovo[$sample][$neuron_hidden];
	   		}
	   }

		if($this->showDescription){ 
			PrintCalculation::setHtml($html, "fase-2", "passo-2", "epoca-{$epoch}-amostra-".($sample+1)."-".$type_data,$this->orderStep);
		}

	}

	private function calculateWeightChange($sample, $epoch, $type_data = "train"){
		
	    $html = "";
	    if($this->showDescription){ $html .= "<br/><br/><strong>Ajuste dos Pesos/Bias:</strong>"; }
		$wEntrada = $wOculta = $bias = array();

	
		if($this->showDescription){ $html .= "<br/><br/><strong>Entre a camada de entrada e camada oculta:</strong>"; }
		foreach($this->wEntrada as $neuron_hidden => $weights){
			foreach($weights as $neuron_input => $weight){

				$wEntrada[$neuron_hidden][$neuron_input] = ($weight + (($this->learningRate*$this->deltaNovo[$sample][$neuron_hidden])*$this->dataInputs["values"]["train"]["normalized"][$neuron_input][$sample]));
				if($this->showDescription){
				$html .= "<br/>";
				$html .= "(".$weight." + ((".$this->learningRate." x ".$this->deltaNovo[$sample][$neuron_hidden].") x ".$this->dataInputs["values"]["train"]["normalized"][$neuron_input][$sample].")) = ".$wEntrada[$neuron_hidden][$neuron_input];
				}
			}
		}

		//debug($this->wOculta);

		if($this->showDescription){ $html .= "<br/><br/><strong>Entre a camada oculta e camada de saída:</strong>";}
		foreach($this->wOculta as $neuron_output => $weights){
			foreach($weights as $neuron_hidden => $weight){
				
				$wOculta[$neuron_output][$neuron_hidden] = ($weight + (($this->learningRate*$this->deltaDaSaidaNovo[$sample][$neuron_output-$this->countNeuronsHiddenLayer])*$this->y[$type_data][$sample][$neuron_hidden]));	
				//$html .= "<br/> deltaDaSaidaNovo[".$sample."][".$neuron_output."-".$this->countNeuronsHiddenLayer."] <br/>";
				if($this->showDescription){
				$html .= "<br/>(".$weight." + ((".$this->learningRate." x ".$this->deltaDaSaidaNovo[$sample][$neuron_output-$this->countNeuronsHiddenLayer].") x ".$this->y[$type_data][$sample][$neuron_hidden].")) = ".$wOculta[$neuron_output][$neuron_hidden];
				}
			}
		}

		if($this->showDescription){ $html .= "<br/><br/><strong>Bias de cada neurônio da rede:</strong>";}
		foreach($this->bias as $neuron_bias => $bia){
				
				if($neuron_bias < $this->countNeuronsHiddenLayer){

					$bias[$neuron_bias] = ($bia + (1*($this->learningRate*$this->deltaNovo[$sample][$neuron_bias])));
					if($this->showDescription){
					$html .= "<br/>";
					$html .= "(".$bia." + (1 x (".$this->learningRate." x ".$this->deltaNovo[$sample][$neuron_bias]."))) = ".$bias[$neuron_bias];
					}
				}else{

					$bias[$neuron_bias] = ($bia + (1*($this->learningRate*$this->deltaDaSaidaNovo[$sample][$neuron_bias-$this->countNeuronsHiddenLayer])));
					if($this->showDescription){
					$html .= "<br/>";
					$html .= "(".$bia." + (1 x (".$this->learningRate." x ".$this->deltaDaSaidaNovo[$sample][$neuron_bias-$this->countNeuronsHiddenLayer]."))) = ".$bias[$neuron_bias];
					}
				}
				
		}

		$this->wEntrada = $wEntrada;
		$this->wOculta = $wOculta;
		$this->bias = $bias;
		
 		if($this->showDescription){
 			$html .= "</div> 
 			          <div class='col-md-4'>
 						<br/><strong>Fórmula do ajuste dos pesos da camada de saída com derivada f(x):</strong><br/> 
 						<img src='".$this->siteUrl."/img/sistema/formulas/weight-change-of-output-layer-with-derivation-".$this->fativacao.".jpg' alt='Erro da Rede Neural' title=''/><br/>
 						<br/><strong>Fórmula do ajuste dos pesos da camada oculta com derivada f(x):</strong><br/> 
 						<img src='".$this->siteUrl."/img/sistema/formulas/weight-change-of-hidden-layers-with-derivation-".$this->fativacao.".jpg' alt='Erro da Rede Neural' title=''/><br/>
 					  </div> 
 					  </div></div></div>"; 
			PrintCalculation::setHtml($html, "fase-2", "passo-2", "epoca-{$epoch}-amostra-".($sample+1)."-".$type_data, $this->orderStep);
			$this->orderStep++;	
		}

	}


	private function calculateErrors($epoch){

		$html = "";
		if($this->showDescription){ 
			$html .= "<div class='panel panel-primary panel-green-blue'><div class='panel-heading'><strong>{$epoch}ª Epoca - Erro da Rede Neural:</strong></div><div style='padding-bottom:0px;' class='panel-body'>"; 
			$html .= "<div class='row'><div class='col-md-8'>";
		}


		foreach($this->countTotalSamples as $type_data => $total_samples){	

			if($total_samples > 0){
			
			$showDescCurrent = $this->showDescription;
			$this->showDescription = false;

			for($sample=0; $sample<$total_samples; $sample++){

				$this->calculateY($sample, $epoch, $type_data);
			}

			if($showDescCurrent){ $this->showDescription = true; }
		

	        $sumMeanSquaredError = 0;
	        $meanSquaredErrorArray = array();

	        //Loop for each output of network
		    for($neuron = $this->countNeuronsHiddenLayer; $neuron < $this->countNeuronsOutputLayer + $this->countNeuronsHiddenLayer; $neuron++) {
		       	
		       	$num_output = ($neuron-$this->countNeuronsHiddenLayer);	
				$erroOutput = $squaredError = 0;

		        if($this->showDescription){ 
			   	  	$html .= "<br/><strong>Saída ".($num_output+1)." - Erro Quadrático Médio</strong><br/>"; 
			       	//$html .= $erroOutput."^2 = ".$squaredError."<br/>";
			    }

		        for($sample=0; $sample<$total_samples; $sample++){

		        
		           $erroOutput += $tmp_erroOutput = ($this->dataOutputs["values"][$type_data]["normalized"][$num_output][$sample]-$this->y[$type_data][$sample][$neuron]);
				   $squaredError += $tmp_squaredError = pow($tmp_erroOutput,2); //(t-o)

				   if($this->showDescription){
				   $html .= ($sample+1)."ª Amostra - (".$this->dataOutputs["values"][$type_data]["normalized"][$num_output][$sample]." - (".$this->y[$type_data][$sample][$neuron]."))^2 = ".$tmp_squaredError."<br/>";
				   $meanSquaredErrorArray[] = $tmp_squaredError;
				   }

		           if($this->normalizeDataSets){
		           		$this->provided[$type_data][$num_output][$sample]  = number_format(Util::normalize($this->y[$type_data][$sample][$neuron], $this->dataOutputs["value_max"][$type_data][$num_output], $this->dataOutputs["value_min"][$type_data][$num_output], true), 2, '.', '');
					}else{
		           		$this->provided[$type_data][$num_output][$sample] = $this->y[$type_data][$sample][$neuron]; 
		           }	

		        }

		        $this->errorMeasures['sum(y-y^)'][$type_data]['normalized'][$num_output] = $erroOutput; //Sum from errors 
		        $this->errorMeasures['sqe'][$type_data]['normalized'][$num_output] = $squaredError; //Results from SQE
		        $sumMeanSquaredError += $squaredError;


		      
			}
			//$this->errorMeasures['eqm-total'][$type_data][$epoch] = sqrt($sumMeanSquaredError/$total_samples); //RMS Error
			$this->errorMeasures['eqm-total'][$type_data][$epoch] = ($sumMeanSquaredError/$total_samples); //MSE

			if($this->showDescription){
			$html .= "<br/><strong>Total de Erro Quadrático Médio:</strong><br/>"; 
			$html .= implode(" + ",$meanSquaredErrorArray)." = ".$sumMeanSquaredError."<br/>";
			$html .= "1/{$total_samples} x (".$sumMeanSquaredError.") = ".$this->errorMeasures['eqm-total'][$type_data][$epoch]."<br/><br/>";
			}

			}
		}
		
		if($this->showDescription){ 
			$html .= "</div>
					  <div class='col-md-4'><br/><strong>Fórmula do Erro Quadrático Médio:</strong><br/><br/>
					  <img src='".$this->siteUrl."/img/sistema/formulas/error-network.jpg' alt='Erro da Rede Neural' title='' width='240'/>
					  </div></div></div></div>";
	 		PrintCalculation::setHtml($html, "fase-2", "passo-3","epoca-{$epoch}",$this->orderStep);
	 		$this->orderStep++;
	 	}	
	}

	private function calculateRegression($last_epoch){

		//TRAINING
		$samples = $this->countTotalSamples["train"];
		$k = $this->countNeuronsInputLayer; //number of
		$html = "";

		if($samples > 0){

			if($this->showDescription){ 
				$html .= "<div class='panel panel-primary panel-red'><div class='panel-heading'><strong>Calculando R ao quadrado do Treinamento: </strong></div><div style='padding-bottom:0px;' class='panel-body'>"; 
				$html .= "<div class='row'><div class='col-md-8'>";
			}

			for($output=0;$output<$this->countNeuronsOutputLayer;$output++){

				$sqreg = ($this->dataOutputs["stq"]["train"]["normalized"][$output] - $this->errorMeasures['sqe']['train']['normalized'][$output]);
				$this->errorMeasures['rquad']['train']['normalized'][$output] = number_format(($sqreg/$this->dataOutputs["stq"]["train"]["normalized"][$output]),3);
				$this->errorMeasures['rquad-adjusted']['train']['normalized'][$output] = number_format((1-((($samples-1)/($samples-($k)))*(1-$this->errorMeasures['rquad']['train']['normalized'][$output]))), 3);
				
				if($this->showDescription){ 
					$html .= "<strong>Saída ".($output+1).": </strong>";
					$html .= "<br/>SQE: ".$this->errorMeasures['sqe']['train']['normalized'][$output];
					$html .= "<br/>STQ: ".$this->dataOutputs["stq"]["train"]["normalized"][$output];
					$html .= "<br/>SQREG: ".$this->dataOutputs["stq"]["train"]["normalized"][$output]." - ".$this->errorMeasures['sqe']['train']['normalized'][$output]." = ".$sqreg;
					$html .= "<br/><strong>R^2:</strong> ".$sqreg."/".$this->dataOutputs["stq"]["train"]["normalized"][$output]." = ".$this->errorMeasures['rquad']['train']['normalized'][$output];
					$html .= "<br/><strong>R^2 Ajustado:</strong> 1-(((".$samples."-1)/(".$samples."-(".$k."))) x (1-".$this->errorMeasures['rquad']['train']['normalized'][$output].")) = ".$this->errorMeasures['rquad-adjusted']['train']['normalized'][$output];
					$html .= "<br/><br/>";
				}
			}

			if($this->showDescription){ 
				$html .= "</div>
				<div class='col-md-4'>
				<strong>Fórmula da Soma Total dos Quadrados:</strong><br/><br/>
				<img src='".$this->siteUrl."/img/sistema/formulas/stq.jpg' width='200' alt='STQ' title=''/><br/><br/>
				<strong>Fórmula do R ao quadrado:</strong><br/><br/>
				<img src='".$this->siteUrl."/img/sistema/formulas/r-ao-quadrado.jpg' width='170' alt='R ao Quadrado' title=''/><br/>
				</div>  
				</div></div></div>";
		 		PrintCalculation::setHtml($html, "fase-2", "passo-4", "epoca-{$last_epoch}-train",$this->orderStep);
		 		$this->orderStep++;
		 	}

	 	}

	 	//VALIDATION
		$samples = $this->countTotalSamples["validation"];
		$k = $this->countNeuronsInputLayer; //number of
		//$html = "";

		if($samples > 0){

			if($this->showDescription){ 
				$html .= "<div class='panel panel-primary panel-red'><div class='panel-heading'><strong>Calculando R ao quadrado da Validação: </strong></div><div style='padding-bottom:0px;' class='panel-body'>"; 
				$html .= "<div class='row'><div class='col-md-8'>";
			}

			for($output=0;$output<$this->countNeuronsOutputLayer;$output++){

				$sqreg = ($this->dataOutputs["stq"]["validation"]["normalized"][$output] - $this->errorMeasures['sqe']['validation']['normalized'][$output]);
				$this->errorMeasures['rquad']['validation']['normalized'][$output] = number_format(($sqreg/$this->dataOutputs["stq"]["validation"]["normalized"][$output]),3);
				$this->errorMeasures['rquad-adjusted']['validation']['normalized'][$output] = number_format((1-((($samples-1)/($samples-($k)))*(1-$this->errorMeasures['rquad']['validation']['normalized'][$output]))), 3);
				
				if($this->showDescription){ 
					$html .= "<strong>Saída ".($output+1).": </strong>";
					$html .= "<br/>SQE: ".$this->errorMeasures['sqe']['validation']['normalized'][$output];
					$html .= "<br/>STQ: ".$this->dataOutputs["stq"]["validation"]["normalized"][$output];
					$html .= "<br/>SQREG: ".$this->dataOutputs["stq"]["validation"]["normalized"][$output]." - ".$this->errorMeasures['sqe']['validation']['normalized'][$output]." = ".$sqreg;
					$html .= "<br/><strong>R^2:</strong> ".$sqreg."/".$this->dataOutputs["stq"]["validation"]["normalized"][$output]." = ".$this->errorMeasures['rquad']['validation']['normalized'][$output];
					$html .= "<br/><strong>R^2 Ajustado:</strong> 1-(((".$samples."-1)/(".$samples."-(".$k."))) x (1-".$this->errorMeasures['rquad']['validation']['normalized'][$output].")) = ".$this->errorMeasures['rquad-adjusted']['validation']['normalized'][$output];
					$html .= "<br/><br/>";
				}
			}

			if($this->showDescription){ 
				$html .= "</div>
				<div class='col-md-4'>
				<strong>Fórmula da Soma Total dos Quadrados:</strong><br/><br/>
				<img src='".$this->siteUrl."/img/sistema/formulas/stq.jpg' width='200' alt='STQ' title=''/><br/><br/>
				<strong>Fórmula do R ao quadrado:</strong><br/><br/>
				<img src='".$this->siteUrl."/img/sistema/formulas/r-ao-quadrado.jpg' width='170' alt='R ao Quadrado' title=''/><br/>
				</div>  
				</div></div></div>";
		 		PrintCalculation::setHtml($html, "fase-2", "passo-4", "epoca-{$last_epoch}-train",$this->orderStep);
		 		$this->orderStep++;
		 	}
	 		
	 	}

	}

}
?>
