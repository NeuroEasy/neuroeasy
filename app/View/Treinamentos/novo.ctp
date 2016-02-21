<?=$this->Html->script('sistema/jquery-steps/jquery.steps.min');?>
<?=$this->Html->script('sistema/bootstrap-filestyle.min');?>
<?=$this->Html->script('sistema/jquery.form.min');?>
<?=$this->Html->script('sistema/jquery.maskMoney.min');?>

<?=$this->Html->css('sistema/jquery-steps/jquery.steps');?>

<!-- Load javascript from NeuralNetwork Plugin -->
<?=$this->Html->script('/NeuralNetwork/js/highcharts/highcharts');?>
<?=$this->Html->script('/NeuralNetwork/js/highcharts/modules/data');?>
<?=$this->Html->script('/NeuralNetwork/js/highcharts/modules/exporting');?>

<!-- Load javascript from neural network topology drawing -->
<?=$this->Html->script('sistema/neural-network-drawing/cytoscape.min');?>
<?=$this->Html->script('sistema/neural-network-drawing/dagre.min');?>
<?=$this->Html->script('sistema/neural-network-drawing/cytoscape-dagre');?>

<style>
.wizard > .steps > ul > li{ width: 25%;} /* Definiting for the amount of steps and divide in percent. Ex: 25% then are 4 steps*/
.wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active{ background: #FDC0BA; }
.wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active { background: #FF7F74; }
.wizard > .actions a{ background: #FF7F74; }
.wizard > .actions a:hover, .wizard > .actions a:active { background: #F75749; }
.wizard > .content {background: none; margin-top:30px;height: 520px;}
.wizard > .content > .body {padding:0px; width: 100%;}
.wizard > .content > .body label {text-align: right;}
.table-bordered th{font-size:12px;}
#confirmacao{ margin-bottom:20px;}
#confirmacao li span{ font-weight: bold;}
#show-datasets, #show-datasets-in-confirmation{overflow-y: auto; height: 200px}
#col-input-file{margin-bottom:30px;}

legend {border:none; font-weight: bold;}
.form-control{margin-bottom:10px;}

#form-group-field-momentum, #form-group-field-fix-weight, #form-group-field-value-begin, #form-group-field-value-end, #form-group-field-costum-file{ display: none;}

.passo-titulo{ padding-bottom: 20px; }

#neural-network-drawing, #neural-network-drawing-confirm{ width: 100%; height: 100%; position: absolute; left: 0; top: 0; z-index: 999; margin-top:20px;
}

#neural-network-drawing-confirm{ top:-80px;}

.form-control{font-size: 12px;padding: 3px 4px;}
.buttonText{ font-size:13px;}

</style>

<?=$this->element('javascript-php/treinamento-novo'); ?>

<div class="container container-page">
  <div class="row">
    <div class="col-md-12">

    	<div class="panel panel-default" id="passo-a-passo">
  		  <div class="panel-body">

           <?=$this->Form->create("Treinamento",array("action" => "novo","id" => "from-treinamento","class" => "form-horizonta","enctype" => "multipart/form-data"));?>
           
              <h3><br/>Modelo da Rede Neural</h3>
              <fieldset>
                
              <legend class="passo-titulo">Configuração da rede neural</legend>

              <div class="row">
              <div class="col-md-6">

                <div class="row form-group">
                  <label for="tipo-rede-neural" class="col-sm-2 col-md-4 control-label">Tipo de Rede Neural:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("tipo_rede_neural",array("options" => $types,
                                                     "label" => false,
                                                     "class"=>"form-control",
                                                     "id" => "tipo_rede_neural"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="quantas-entradas" class="col-sm-2 col-md-4 control-label">Dados de Entrada:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("qts_entradas",array("label" => false,
                                                     "class"=>"form-control js_number",
                                                     "id" => "qts_entradas"
                                        )
                  );?>
                  </div>
                </div>

                <div class="row form-group">
                  <label for="quantas-saidas" class="col-sm-2 col-md-4 control-label">Dados de Saída:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("qts_saidas",array("label" => false,
                                                     "class"=>"form-control js_number",
                                                     "id" => "qts_saidas"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="neuronios-na-camanda-oculta" class="col-sm-2 col-md-4 control-label">Qts. neurônios na camada oculta:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("neuronios_camada_oculta",array("label" => false,
                                                     "class"=>"form-control js_number",
                                                     "id" => "neuronios_camada_oculta"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="funcao-ativacao" class="col-sm-2 col-md-4 control-label">Função de Ativação:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("funcao_ativacao",array("options" => $activation_functions,
                                                     "label" => false,
                                                     "class"=>"form-control",
                                                     "id" => "funcao_ativacao"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="usar_bias" class="col-sm-2 col-md-4 control-label">Pesos e Bias Iniciais:</label>
                  <div class="col-sm-10 col-md-8">
                    <?=$this->Form->input("pesos_iniciais",array("options" => array(
                                                                 "" => "Escolher..", 
                                                                 "fixos" => "Valor Fixo", 
                                                                 "aleatorios" => "Aleatórios",
                                                                 "importar" => "Importar"),
                                                       "label" => false,
                                                       "class"=>"form-control",
                                                       "id" => "pesos_iniciais"
                                          )
                    );?>
                  </div>
                </div>  

                <!-- The three follow are fields corresponds for weights --> 
                <div class="row form-group" id="form-group-field-fix-weight">
                  <label for="peso_fixo" class="col-sm-2 col-md-4 control-label">Fixo:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("peso_fixo",array("label" => false,
                                                     "class"=>"form-control js_number",
                                                     "id" => "peso_fixo"
                                        )
                  );?>
                  </div>
                </div>  



                <div class="row form-group" id="form-group-field-value-begin">
                  <label for="peso_inicial" class="col-sm-2 col-md-4 control-label">Mínimo:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("peso_inicial",array("label" => false,
                                                     "class"=>"form-control js_numberWithNegative",
                                                     "id" => "peso_inicial"
                                        )
                  );?>
                  </div>
                </div> 

                <div class="row form-group" id="form-group-field-value-end">
                  <label for="peso_final" class="col-sm-2 col-md-4 control-label">Máximo:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input("peso_final",array("label" => false,
                                                     "class"=>"form-control js_numberWithNegative",
                                                     "id" => "peso_final"
                                        )
                  );?>
                  </div>
                </div> 

                <div class="row form-group" id="form-group-field-costum-file">
                  <label for="pesos_importacao" class="col-sm-2 col-md-4 control-label">Importar:</label>
                  <div class="col-sm-10 col-md-8">
                  <?=$this->Form->input('pesos_importacao', array('type' => 'file','class' => 'filestyle', 'data-iconName' => 'fa fa-cloud-upload','data-input' => 'false', 'data-buttonText' =>  'Importar pesos e bias .json clicando aqui','label' => false,"id" => "pesos_importacao","accept" => ".json,.txt"));?>
                  <div id="pesos-importacao-mensagem"></div>
                  </div>

                </div> 

              </div>

              <div class="col-md-6" style="height:360px;">

                <div id="neural-network-drawing-loading" style="width:100%; height:100%; text-align:center;"></div>
                <div id="neural-network-drawing"></div>

              </div>

              </div>
                
              </fieldset>
           
              <h3><br/>Inserção dos Dados</h3>
              <fieldset>
                  <legend class="passo-titulo">Inserção dos Dados para Treinamento (entradas e saídas)</legend>
                  
                  <div class="row">
                    <div class="col-md-12" id="show-datasets-message">
                    </div> 
                  </div>
                   
                  <div class="row form-group">
                    <center>  
                    <div class="col-md-12" id="col-input-file">
                    <?=$this->Form->input('dados_importacao', array('type' => 'file','class' => 'filestyle', 'data-iconName' => 'fa fa-cloud-upload','data-input' => 'false', 'data-buttonText' =>  'Importar arquivo .csv clicando aqui','label' => false,"id" => "dados_importacao","accept" => ".csv,.txt"));?>
                    </div>

                    <div class="col-md-12" id="loading-datasets">
                    </div>

                    </center> 

                    <div class="col-md-12" id="show-datasets">
                      <table class="table table-bordered table-hover table-striped" id="datasets">
                        
                        <thead>
                        <tr id="titles">
                        </tr>
                        <thead>

                        <tbody>
                        </tbody>  

                      </table>
                    </div> 

                  </div> 

              <div class="col-md-6 col-md-offset-3"> 
              <center>  

                  <div class="row form-group">

                    <div class="col-md-6 col-sm-12">
                      <div class="pull-right"><strong>Normalizar os dados?</strong></div>
                    </div>
                      
                    <div class="col-md-6 col-sm-12"> 
                      <div class="pull-left">
                      <label class="radio-inline">
                        <input type="radio" name="normalizar" value="1" checked> Sim
                      </label>

                      <label class="radio-inline">
                        <input type="radio" name="normalizar" value="0"> Não
                      </label>
                      </div>
                    </div>

                  </div>


                  <div class="row form-group">
                    <label for="porc_treinamento" class="col-sm-2 col-md-6 control-label">% para Treinamento:</label>
                    <div class="col-sm-10 col-md-6">
                    <?=$this->Form->input("porc_treinamento",array("label" => false,
                                                       "class"=>"form-control js_percentage",
                                                       "id" => "porc_treinamento",
                                                       "value" => 100
                                          )
                    );?>                  
                    </div>
                  </div>                   


                  <div class="row form-group">
                    <label for="porc_validacao" class="col-sm-2 col-md-6 control-label">% para Validação (Teste):</label>
                    <div class="col-sm-10 col-md-6">
                    <?=$this->Form->input("porc_validacao",array("label" => false,
                                                       "class"=>"form-control js_percentage",
                                                       "id" => "porc_validacao",
                                                       "value" => 0
                                          )
                    );?>                  
                    </div>
                  </div> 
              </center>    
              </div>   

              </fieldset>
          
           
              <h3><br/>Parâmetros de Aprendizagem</h3>
              <fieldset>
                  <legend class="passo-titulo">Parâmetros de aprendizado da rede neural</legend>

                <div class="row form-group">
                    <label for="regra-aprendizagem" class="col-sm-2 control-label">Regra de Aprendizagem:</label>
                    <div class="col-sm-10 col-md-4">
                    <?=$this->Form->input("regra_aprendizagem",array("options" => $learning_rules,
                                                       "label" => false,
                                                       "class"=>"form-control",
                                                       "id" => "regra_aprendizagem"
                                          )
                    );?>
                    </div>
                </div> 

                
                <div class="row form-group" id="form-group-field-momentum">
                  <label for="regra-aprendizagem" class="col-sm-2 control-label">Taxa Momentum:</label>
                  <div class="col-sm-10 col-md-4">
                  <?=$this->Form->input("taxa_momentum",array(
                                                     "label" => false,
                                                     "class"=>"form-control js_momentum",
                                                     "id" => "taxa_momentum"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="funcao-ativacao" class="col-sm-2 control-label">Taxa de Aprendizado:</label>
                  <div class="col-sm-10 col-md-4">
                  <?=$this->Form->input("taxa_aprendizado",array("label" => false,
                                                     "class"=>"form-control js_rate_learning",
                                                     "id" => "taxa_aprendizado"
                                        )
                  );?>
                  </div>
                </div>


                <div class="row form-group">
                  <label for="funcao-ativacao" class="col-sm-2 control-label">Épocas:</label>
                  <div class="col-sm-10 col-md-4">
                  <?=$this->Form->input("epocas",array("label" => false,
                                                     "class"=>"form-control js_number",
                                                     "id" => "epocas"
                                        )
                  );?>
                  </div>
                </div>

              </fieldset>
           
           
              <h3><br/>Confirmar Treinamento</h3>
              <fieldset>
                  <legend class="passo-titulo">Confirmação</legend>
                  <div class="row form-group">
                      <div class="col-md-6">
                          <ul id="confirmacao">
                          </ul>
                      </div>

                      <div class="col-md-6" style="height:300px;">
                          <div id="neural-network-drawing-confirm"></div>
                      </div> 

                      <div class="col-md-12">
                          <div id="show-datasets-in-confirmation">
                          </div>                      
                      </div> 
                                            
                  </div>  
              </fieldset>
           
          <?=$this->Form->end(); ?>
        

        </div>
		  </div>

      <div class="panel panel-default">
        <div class="panel-body">
           
           <div class="row">
              <div class="col-md-12" id="col-graphical-training">


              </div> 

              <div class="col-md-12" id="col-debug">
              </div>  
           </div> 

        </div>
      </div>    

    </div> 
  </div>
</div>