<!-- script from nerual network drawing -->
<?=$this->Html->script('sistema/neural-network-drawing/cytoscape.min');?>
<?=$this->Html->script('sistema/neural-network-drawing/dagre.min');?>
<?=$this->Html->script('sistema/neural-network-drawing/cytoscape-dagre');?>

<!-- script for step by step screen -->
<?=$this->Html->script('sistema/jquery-steps/jquery.steps.min');?>
<?=$this->Html->script('sistema/bootstrap-filestyle.min');?>
<?=$this->Html->script('sistema/scrollspy');?>

<?=$this->Html->css('sistema/jquery-steps/jquery.steps');?>

<style>
.categoria{ margin-top:20px;}
ul{padding:0px; margin:0px;}
li{list-style: none;}
.sub-titulo{background: #FF7F74;padding: 2px;color: #FFF;font-weight: bold; margin-bottom:10px;}
.titulo{ padding-bottom:12px; border-bottom:#F3F3F3 1px solid;}
.texto{ margin:15px 0px;}
#neural-network-drawing {width: 100%;height: 100%;position: absolute;left: 0;top: 0;z-index: 999;margin-top:20px;}
</style>


<script>
    $(function(){

        $("#neural-network-drawing-loading").html('<?=$this->Html->image("sistema/loader-small.gif", array("alt" => "", "title" => "Carregando..."));?>');  

        var cy = window.cy = cytoscape({
            container: document.getElementById('neural-network-drawing'),

  boxSelectionEnabled: false,
  autounselectify: true,

            layout: {
                name: 'dagre'
            },

            style: [
                {
                    selector: 'node',
                    style: {
                        'content': 'data(value)',
                        'text-valign': 'top',
                        'text-halign': 'center',
                        'background-color': '#FF6356',
                        'font-size':'12px',
                        'color': '#464646',
                        'font-family':'Open Sans'
                    }
                },

                {
                    selector: 'edge',
                    style: {
                        'content': 'data(value)',
                        'font-size':'12px',
                        'font-weight':'bold',
                        'width': 4,
                        'target-arrow-shape': 'triangle',
                        'line-color': '#DCDCDC',
                        'target-arrow-color': '#000',
                        'font-family':'Open Sans'
                    }
                }
            ],

            elements: {
                nodes: [
                    <?=implode(",", $cytoscape['nodes']);?>
                ],
                edges: [
                    <?=implode(",", $cytoscape['edges']);?>
                ]
            },
            ready: function(){
                $("#neural-network-drawing-loading").html('');       
            } // on layoutready
        });


       $("#col-step-by-step").steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            enableFinishButton: false,
            labels: {
                cancel: "Cancelar <i class=\"fa fa-ban\"></i>",
                current: "etapa atual:",
                pagination: "Paginação",
                finish: "Próximo <i class=\"fa fa-check-circle\"></i>",
                next: "Próximo <i class=\"fa fa-arrow-circle-right\"></i>",
                previous: "<i class=\"fa fa-arrow-circle-left\"></i> Anterior",
                loading: "Carregando..."
            },
            titleTemplate: '<span class="number"><strong>#index#º Passo:</strong></span> #title#',
            /* See data from de <form> for confirmation */
            onStepChanged: function (event, currentIndex, priorIndex)
            { 
              

              return true;  
              
            },
            onFinished: function()
            {
              
       
              // !!! Important !!! 
              // always return false to prevent standard browser submit and page navigation 
              return false; 
            },
            onStepChanging: function(event, currentIndex, newIndex){
              
              

              return true;
            }
        });

    });
</script>

<div class="container container-page">
  <div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
          <div class="panel-body">
            
            <div class="row">
            <div class="col-md-12">
                <h3 class="titulo"><strong>Treinamento: <?=$item["Treinamento"]["name"];?></strong></h3>
            </div>
            </div>

            <div class="row">
            <div class="col-md-12">
                
                <?=$this->Html->link("<i class=\"fa fa-trash\"></i> Excluir Treinamento", ["action" => "excluir", "id" => $item["Treinamento"]["id"]], ["class" =>"btn btn-sm btn-danger pull-right", "role" => "button", "escape" => false], "Deseja realmente excluir esse treinamento?");?>
                
            </div>
            </div>

            <div class="row">
            <div class="col-md-6">

                <?php if(!@empty($item["Treinamento"]["description"])): ?>
                <ul class="categoria">
                    <li class="sub-titulo">Descrição:</li>
                    <li><?=nl2br($item["Treinamento"]["description"]);?></li>
                </ul>
                <?php endif;?>

                <ul class="categoria">
                    <li class="sub-titulo">Modelo da Rede Neural:</li>
                    <li><strong>Tipo de Rede Neural:</strong> <?=$item["NeuralNetworkType"]["name"];?></li>
                    <li><strong>Topologia:</strong> <?=$item["Treinamento"]["topology"];?></li>
                    <li><strong>Função de Ativação:</strong> <?=$item["NeuralNetworkActivationFunction"]["name"];?></li>
                </ul>

                <ul class="categoria">
                    <li class="sub-titulo">Parâmetros de Aprendizagem:</li>
                    <li><strong>Tipo do Algoritmo:</strong> <?=$item["NeuralNetworkLearningRule"]["name"];?></li>
                    <li><strong>Taxa de Aprendizagem:</strong> <?=$item["Treinamento"]["learning_rate"];?></li>

                    <?php if(strlen($item["Treinamento"]["momentum"]) > 0){ 
                        echo "<li><strong>Taxa Momentum:</strong> ".$item["Treinamento"]["momentum"]."</li>"; 
                    } ?>

                    <?php if(strlen($item["Treinamento"]["max_epochs"]) > 0){ 
                        echo "<li><strong>Épocas:</strong> ".$item["Treinamento"]["max_epochs"]."</li>"; 
                    } ?>

                    <?php if(strlen($item["Treinamento"]["max_error"]) > 0){ 
                        echo "<li><strong>Erro Máximo:</strong> ".$item["Treinamento"]["max_error"]."</li>"; 
                    } ?>

                </ul>

                <ul class="categoria">
                    <li class="sub-titulo">Resultados:</li>
                    
                    <div class="row">
                        
                        <?php if(strlen($item["Treinamento"]["tmse_result"]) > 0){ ?>
                        <ul class="col-md-12"><li> <strong>Total de Erro Médio Quadrático:</strong> <?=$item["Treinamento"]["tmse_result"];?></li></ul>
                        <?php } ?>

                        <?php foreach($item['TreinamentoResultado'] as $result):?>
                        <ul class="col-md-6">
                        <li><strong>Nome:</strong> <?=$result['name_output'];?> </li>
                        <li><strong>R^2:</strong> <?=$result['rquad'];?></li>    
                        <li><strong>R^2 Ajustado:</strong> <?=$result['rquada'];?></li> 
                        </ul>                
                        <?php endforeach;?>

                    </div>

                </ul>


                <ul class="categoria">
                    <!--<li style="border-top: #EFEBE4 solid 2px; height: 15px;"></li>-->
                    <li class="sub-titulo">Pesos/Bias (aprendizado da rede neural):</li>
                    <li class="texto">Tem-se a possibilidade de exportar os pesos/bias gerados que seria o formato de aprendizagem adquirida durante o processo de treinamento.</li>
                    <li style="margin-bottom:10px; text-align:right;"> 

                        <div class="btn-group" role="group" aria-label="...">
                        
                        <?=$this->Html->link("<i class=\"fa fa-download\"></i> Exportar pesos/bias", ["controller" => "downloads", "action" => "pesos_bias", "cod" => $item["Treinamento"]["token"]], ["class" =>"btn btn-sm btn-success", "role" => "button", "escape" => false]);?>

                    </li>

                </ul>


                <ul class="categoria">
                    <!--<li style="border-top: #EFEBE4 solid 2px; height: 15px;"></li>-->
                    <li class="sub-titulo">Gerador do código-fonte Feedforward:</li>
                    <li class="texto">É um gerador de código-fonte de um algoritmo da rede neural de feedforward, de acordo com a topologia adotado no treinamento, com o propósito do usuário aprender o processo de ida (feedforward) e integrar com algum projeto.</li>
                    <li style="margin-bottom:10px; text-align:right;"> 

                        <div class="btn-group" role="group" aria-label="...">
                        
                        <?=$this->Html->link("<i class=\"fa fa-download\"></i> Download do código-fonte PHP", ["controller" => "downloads", "action" => "codigo_fonte_feedforward", "cod" => $item["Treinamento"]["token"], "linguagem" => "php"], ["class" =>"btn btn-sm btn-success", "role" => "button", "escape" => false]);?>

                    </li>
                </ul>


            </div>

            <div class="col-md-6" style="height:450px;">
                <div id="neural-network-drawing-loading" style="width:100%; height:100%; text-align:center;"></div>
                <div id="neural-network-drawing">

                </div>

            </div> 
            </div>              

          </div>
        </div>

      <!-- This feature below, just do not open when the screen is too small -->  
      <div class="panel panel-default hidden-xs">
        <div class="panel-body">

            <div class="row row-step-by-step">
                
                <style>
                /*Screen Step By Step*/
                .wizard > .steps > ul > li{ width: 50%;} /* Definiting for the amount of steps and divide in percent. Ex: 25% then are 4 steps*/
                .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active{ background: #eee; color: #aaa;}
                .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active { background: #FF7F74; }
                .wizard > .actions a{ background: #FF7F74; }
                .wizard > .actions a:hover, .wizard > .actions a:active { background: #F75749; }
                .wizard > .content {background: none; margin-top:8px;height: 400px; /*border:solid #CCC 1px;*/ }
                .wizard > .content > .body {padding:0px; width: 100%;}
                .wizard > .content > .body label {text-align: right;}
                legend {border:none; font-weight: bold;}
                .passo-titulo{ padding-bottom: 20px; }

                #col-step-by-step fieldset{ position: relative;}
                .scrollspy-example {position: relative;height: 400px;margin-top: 5px;overflow: auto;}
                .scrollspy-example div.step{/*min-height: 400px;*/ padding-top:10px;}
                #col-step-by-step .navbar-default{ background:none;border: none;}
                #col-step-by-step .navbar-default .navbar-nav>li>a {text-align:center;background: #eee;color: #aaa !important; font-weight: bold;}

                /*Active Default*/
                #col-step-by-step .navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{background: #FF7F74;color: #FFF !important;}

                /*Active Blue*/
                #col-step-by-step .navbar-default .navbar-nav>.dropdown-blue.active>a, .navbar-default .navbar-nav>.dropdown-blue.active>a:focus, .navbar-default .navbar-nav>.dropdown-blue.active>a:hover{background: #337ab7;color: #FFF !important;}

                /*Active Purple*/
                #col-step-by-step .navbar-default .navbar-nav>.dropdown-purple.active>a, .navbar-default .navbar-nav>.dropdown-purple.active>a:focus, .navbar-default .navbar-nav>.dropdown-purple.active>a:hover{background: #B7339B;color: #FFF !important;}

                /*Active Gree Blue*/
                #col-step-by-step .navbar-default .navbar-nav>.dropdown-green-blue.active>a, .navbar-default .navbar-nav>.dropdown-green-blue.active>a:focus, .navbar-default .navbar-nav>.dropdown-green-blue.active>a:hover{background: #33B780;color: #FFF !important;}

                /*Active Red*/
                #col-step-by-step .navbar-default .navbar-nav>.dropdown-red.active>a, .navbar-default .navbar-nav>.dropdown-red.active>a:focus, .navbar-default .navbar-nav>.dropdown-red.active>a:hover{background: #EA5555;color: #FFF !important;}

                /*Screen Step By Step (Fase 1)*/
                #col-step-by-step #navbar-fase-1 .navbar-nav{ width: 100%;}
                #col-step-by-step #navbar-fase-1 .navbar-nav>li{ width: 33.3%;}

                /*Screen Step By Step (Fase 1)*/
                #col-step-by-step #navbar-fase-2 .navbar-nav{ width: 100%;}
                #col-step-by-step #navbar-fase-2 .navbar-nav>li{ width: 25%;}
                </style>

                <div class="col-md-12">
                    <h3 class="titulo"><strong>Passo á Passo do treinamento da Rede Neural:</strong></h3>
                </div>
                
                <div class="col-md-12" id="col-step-by-step">
                   
                      <h3><br/>Pré-Processamento dos Dados</h3>
                      <fieldset id="fase-1"><!-- Begin -->
                            



                        <div class="bs-example" data-example-id="embedded-scrollspy"> 
                            <nav id="navbar-fase-1" class="navbar navbar-default navbar-static">
                                <ul class="nav navbar-nav"> 
                                    
                                    <li class="dropdown"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">1º Distribuição das Entradas e Saídas
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            
                                            <li class=""><a href="#<?=$calculations['fase-1']['passo-1']['id'];?>"></a></li>  
                                        </ul> 
                                    </li>

                                    <li class="dropdown"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">2º Valores dos Pesos/Bias Iniciais
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <li class=""><a href="#<?=$calculations['fase-1']['passo-2']['id'];?>"></a></li> 
                                        </ul> 
                                    </li>

                                    <li class="dropdown"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">3º Normalização dos Dados
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <li class=""><a href="#<?=$calculations['fase-1']['passo-3']['id'];?>"></a></li> 
                                        </ul> 
                                    </li>                                                                        

                                </ul>
                            </nav>

                            <div data-spy="scroll" data-target="#navbar-fase-1" data-offset="150" class="scrollspy-example">    

                                <div class="step" id="<?=$calculations['fase-1']['passo-1']['id'];?>">
                                    <?=$calculations['fase-1']['passo-1']['content'];?>
                                </div>

                                <div class="step" id="<?=$calculations['fase-1']['passo-2']['id'];?>">
                                    <?=$calculations['fase-1']['passo-2']['content'];?>
                                </div>

                                <div class="step" id="<?=$calculations['fase-1']['passo-3']['id'];?>">
                                    <?=$calculations['fase-1']['passo-3']['content'];?>
                                </div>

                            </div> 
                        </div>
                            
                      </fieldset><!-- End -->
                  
                   
                      <h3><br/>Rotina de uma época</h3>
                      <fieldset id="fase-2">
                          

                       <div class="bs-example" data-example-id="embedded-scrollspy"> 
                            <nav id="navbar-fase-2" class="navbar navbar-default navbar-static">
                                <ul class="nav navbar-nav"> 
                                    
                                    <li class="dropdown dropdown-blue"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">1º Propagar para Frente
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <?php 
                                            foreach($calculations['fase-2']['onlyIds']['passo-1'] as $tag):

                                                echo "<li class=''><a href='#fase-2-passo-1-".$tag."'></a></li>";
                                            endforeach; 
                                            ?>

                                        </ul> 
                                    </li>

                                    <li class="dropdown dropdown-purple"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">2º Propagar para Trás
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <?php 
                                            foreach($calculations['fase-2']['onlyIds']['passo-2'] as $tag):

                                                echo "<li class=''><a href='#fase-2-passo-2-".$tag."'></a></li>";
                                            endforeach; 
                                            ?>

                                        </ul> 
                                    </li>

                                    <li class="dropdown dropdown-green-blue"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">3º Erro da Rede Neural
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <?php 
                                            foreach($calculations['fase-2']['onlyIds']['passo-3'] as $tag):

                                                echo "<li class=''><a href='#fase-2-passo-3-".$tag."'></a></li>";
                                            endforeach; 
                                            ?>

                                        </ul> 
                                    </li>

                                    <li class="dropdown dropdown-red"> 
                                        <a href="#" id="navbarDrop1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">4º Cálculo do R ao quadrado
                                        </a> 
                                        <ul class="dropdown-menu hidden" aria-labelledby="navbarDrop1"> 
                                            
                                            <?php 
                                            foreach($calculations['fase-2']['onlyIds']['passo-4'] as $tag):

                                                echo "<li class=''><a href='#fase-2-passo-4-".$tag."'></a></li>";
                                            endforeach; 
                                            ?>

                                        </ul> 
                                    </li>

                                    
                                </ul>
                            </nav>

                            <div data-spy="scroll" data-target="#navbar-fase-2" data-offset="150" class="scrollspy-example">    
                                <?php 
                                foreach($calculations['fase-2']['contents'] as $item):

                                    echo "<div class='step' id='".$item['id']."'>".$item['value']."</div>";
                                endforeach; 
                                ?>                                



                            </div>

                        </div>


                      </fieldset>
                   
                </div><!-- col end --> 

            </div> 

            <!-- example from scrollspy.js -->
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
            <!-- end example from scrollspy.js --> 

        </div>
      </div> 


    </div> 
  </div>
</div>