 
<script>
    $(function ()
    {
        //Global variables or objects
        var gValidate = {fileImportData:false};

        $("#from-treinamento").steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            labels: {
                cancel: "Cancelar <i class=\"fa fa-ban\"></i>",
                current: "etapa atual:",
                pagination: "Paginação",
                finish: "Pronto para Treinar <i class=\"fa fa-check-circle\"></i>",
                next: "Próximo <i class=\"fa fa-arrow-circle-right\"></i>",
                previous: "<i class=\"fa fa-arrow-circle-left\"></i> Anterior",
                loading: "Carregando..."
            },
            titleTemplate: '<span class="number"><strong>#index#º Passo:</strong></span> #title#',
            /* See data from de <form> for confirmation */
            onStepChanged: function (event, currentIndex, priorIndex)
            { 
              if(currentIndex == 3){ //The number 3 (three) mean the last step. (Ex: 0 => First Step, 1 => Second Step)
                
                 var data = new Array();
                 data[0] = {key: "Tipo da Rede Neural", nome: $("select#tipo_rede_neural option:selected").text()};
                 data[1] = {key: "Regra de Aprendizagem", nome: $("select#regra_aprendizagem option:selected").text()};
                 data[2] = {key: "Qts. Entradas", nome: $("input#qts_entradas").val()};
                 data[3] = {key: "Qts. Saídas", nome: $("input#qts_saidas").val()};
                 data[4] = {key: "Qts. neurônios na camada oculta", nome: $("input#neuronios_camada_oculta").val()};
                 data[5] = {key: "Função de Ativação", nome: $("select#funcao_ativacao option:selected").text()};
                 data[6] = {key: "Épocas", nome: $("input#epocas").val()};
                 data[7] = {key: "Taxa de Aprendizado", nome: $("input#taxa_aprendizado").val()};
                 data[8] = {key: "Taxa Momentum", nome: $("input#taxa_momentum").val()};
                 
                 //Show some fields               
                 $("ul#confirmacao").html(''); //Empty content
                 $.each( data, function( key, value ) {

                      if(value.nome != 0 || value.nome != ""){
                        $("ul#confirmacao").append("<li><span>"+ value.key +": </span>"+ value.nome +"</li>");
                      }
                      //console.log(value.key + ':' + value.nome); 
                  });

                  //Show weight choiced
                  if($("#pesos_iniciais").val() != ""){
                    
                    var html_pesos_valores;
                    var html_pesos_titulo = "Pesos e Bias Iniciais";

                    if($("#peso_fixo").val() != 0){

                      html_pesos_valores = "<li><span>"+ html_pesos_titulo +": </span>"+ $("#pesos_iniciais option:selected").text() +" ("+ $("#peso_fixo").val() +")</li>";

                    }else if($("#peso_inicial").val() != 0 && $("#peso_final").val() != 0){

                       html_pesos_valores = "<li><span>"+ html_pesos_titulo +": </span>"+ $("#pesos_iniciais option:selected").text() +" ("+ $("#peso_inicial").val() +" á "+ $("#peso_final").val() +")</li>";

                    }else if($("#pesos_importacao").val() != 0){

                       html_pesos_valores = "<li><span>"+ html_pesos_titulo +": </span>"+ $("#pesos_iniciais option:selected").text() +"<br/>"+ $("#pesos_importacao").val() +"</li>"; 
                    }

                    $("ul#confirmacao").append(html_pesos_valores);

                  }

                  //Show Datasets
                  if($("#dados_importacao").val() != ""){
                    $("ul#confirmacao").append("<li><span>Inserção dos Dados: </span>" + $("#show-datasets-message").html()+ "</li>");

                    $("ul#confirmacao").append("<li>% para Treinar: "+ $("input#porc_treinamento").val() +"</li>");
                    $("ul#confirmacao").append("<li>% para Validar: "+ $("input#porc_validacao").val() +"</li>");
                  }

                  verificarTopologiaRedeNeural("neural-network-drawing-confirm"); //Show the topology by confirm

              }

              return true;  
              
            },
            onFinished: function()
            {
              $("#col-graphical-training").html('<center><?=$this->Html->image("sistema/loader-modelo01.GIF", array("alt" => "", "title" => "Carregando..."));?></center>');

              var get_training_options = { 
              url: '<?=$sistema_url?>treinamentos/executar',
              //dataType:  'script',        // 'xml', 'script', or 'json' (expected server response type)
              success:   function(response){

                $("#col-graphical-training").html(response); 
                //console.log(response);
              },
              error: function(data) { 

                $("#col-graphical-training").html(data.status +" <br/> "+ data.statusText +"<br/>"+ data.responseText); 
              }

              };

              $("#from-treinamento").ajaxSubmit(get_training_options); 
       
              // !!! Important !!! 
              // always return false to prevent standard browser submit and page navigation 
              return true; 
            },
            onStepChanging: function(event, currentIndex, newIndex){
              
              var allow = true;

              //If user get in new step, then make a verification   
              if(newIndex > currentIndex){

                //All rules in the first step
                if(currentIndex == 0){

                  var verify = verificarTopologia();
                  var epochs = $("#epocas").val();
                  var pesos_iniciais = $("#pesos_iniciais").val();
                  
                  if(!verify.allow){
                    alert(verify.message);
                    allow = verify.allow;
                  }

                  if(pesos_iniciais == ""){

                    alert('Informe o tipo de inicialização dos pesos/bias');
                    allow = false;
                  }

                }

                //All rules in the second step
                if(currentIndex == 1){

                  var porc_treinamento = parseInt($("#porc_treinamento").val());
                  var porc_validacao = parseInt($("#porc_validacao").val());
                  var dados_importacao = $("#dados_importacao").val();

                  if((porc_treinamento+porc_validacao) > 100){

                    alert('A soma das duas porcentagens não pode resultar acima de 100%');
                    allow = false;
                  }

                  if(dados_importacao == ""){

                    alert('Selecione o arquivo de importação para inserção dos dados a serem treinados');
                    allow = false;
                  }

                  if(!gValidate.fileImportData){

                    alert('O arquivo de importação para inserção dos dados é inválido');
                    allow = false;
                  }

                }

                //All rules in the threed step
                if(currentIndex == 2){

                  var epocas = $("#epocas").val();
                  
                  if(epocas > 10000){
                    alert('Infelizmente o número máxima de épocas é de apenas 10000');
                    allow = false;
                  }

                  if(epocas <= 0){
                    alert('Precisa informar um valor de épocas a serem executados no treinamento');
                    allow = false;
                  }
                }

              }
              return allow;
            }
        });
        
        /*
        * This javascript code bellow, is all reference from form.jquery.js
        */
       
        //Options used in the form.jquery
        var send_datasets_options = { 
          url: '<?=$sistema_url?>treinamentos/mostrar-dados-importacao',
          dataType:  'json',        // 'xml', 'script', or 'json' (expected server response type)
          success: function(response) { 
              // 'responseXML' is the XML document returned by the server; we use
              
              gValidate.fileImportData = response.valid; //Set info valid file import

              $("#show-datasets-message").html(response.message);

              if(response.valid){

                //Titles
                $.each(response.datasets.titles[0], function( key, value ) {
                  $("tr#titles").append("<th>"+ value +"</th>");
                }); 

                //Values
                $.each(response.datasets.values, function( key_values, values ) {
                   
                   $("tbody").append("<tr class='"+key_values+"-row-values'></tr>");

                   $.each(values, function( key_value, value ) {
                      //console.log(value);
                      $("tr."+key_values+"-row-values").append("<td>"+ value +"</td>");
                   });

                });

              }
              $('#loading-datasets').html('');

              $("#show-datasets-in-confirmation").html($("#show-datasets").html());
              //console.log(response);
          },
          error: function(data) { 

            $("#show-datasets-in-confirmation").html(data.status +" <br/> "+ data.statusText +"<br/>"+ data.responseText); 
            //console.log(data);
          }

        };

        //
        $("#from-treinamento").ajaxForm(send_datasets_options);

        $("#dados_importacao").bind("change", function(){

              $("tr#titles").html("");
              $("tbody").html("");
              $('#loading-datasets').html('<?=$this->Html->image("sistema/loader-modelo01.GIF", array("alt" => "", "title" => "Carregando..."));?>');
              
              $("#from-treinamento").submit();
            
        }); 

        /*
        * End from this code form.jquery
        */ 

        /*
        * Code bellow rules and validate and trate some rule field
        */
        var input_aprendizagem = $("#regra_aprendizagem");

        input_aprendizagem.bind("change", function(){

          if(input_aprendizagem.val() == 2){
            $("#form-group-field-momentum").slideDown();
          }else{
            $("#form-group-field-momentum").slideUp();
            $("#form-group-field-momentum input#taxa_momentum").val('0');
          }

        }); 


        var input_pesos_iniciais = $("#pesos_iniciais");

        input_pesos_iniciais.bind("change", function(){

          //All since, the fields is empty and gone visibly
          $("#form-group-field-fix-weight").slideUp();
          $("#form-group-field-value-begin").slideUp();
          $("#form-group-field-value-end").slideUp();
          $("#form-group-field-costum-file").slideUp();

          $("#peso_fixo").val(0);
          $("#peso_inicial").val(0);
          $("#peso_final").val(0);
          $("#peso_importacao").val(0);

          if(input_pesos_iniciais.val() == "fixos"){

            $("#form-group-field-fix-weight").slideDown();

          }else if(input_pesos_iniciais.val() == "aleatorios"){

            $("#form-group-field-value-begin").slideDown();
            $("#form-group-field-value-end").slideDown();

          }else if(input_pesos_iniciais.val() == "importar"){

            $("#form-group-field-costum-file").slideDown();
          }

        });  


        var pesos_importacao = $("#pesos_importacao");

        pesos_importacao.bind("change", function(){ 

          if(pesos_importacao.val() != ""){ $("#pesos-importacao-mensagem").html("<span style='color: #0FD06E;font-weight: bold;'>Arquivo recebido!</span>"); }else{ $("#pesos-importacao-mensagem").html("<span style='color: #DE1D1D;font-weight: bold;'>Arquivo não recebido!</span>"); }
        });


        $(".js_percentage").maskMoney({thousands:'', decimal:'',allowZero:true,precision:0, suffix: ' %'});
        $(".js_number").maskMoney({thousands:'', decimal:'',allowZero:false,precision:0});
        $(".js_rate_learning").maskMoney({thousands:'', decimal:'',allowZero:true,precision:2, prefix: '0.'});
        $(".js_momentum").maskMoney({thousands:'', decimal:'',allowZero:true,precision:2, prefix: '0.'});
        $(".js_numberWithNegative").maskMoney({thousands:'', decimal:'',allowZero:true,allowNegative:true,precision:0});



        /*
        * Code end
        */

      /*
       * Code bellow is from neural network drawing 
       */ 

        function verificarTopologia(){

          var qts_entradas = $("#qts_entradas").val();
          var qts_saidas = $("#qts_saidas").val();
          var qts_oculta = $("#neuronios_camada_oculta").val();
          var response = {allow:true, message:""};


          if(qts_entradas <= 0 || qts_oculta <= 0 || qts_saidas <= 0){
            response.allow = false;
            response.message = "Os campos não podem estar vazios ou zeradas";
          }

          if(qts_entradas > 30 || qts_oculta > 30 || qts_saidas > 30){
            response.allow = false;
            response.message = "Só são permitidos no máximo 30 neurônios em cada camada da rede neural";
          }

          return response;
        }

        function verificarTopologiaRedeNeural(elementId){

          var response = verificarTopologia();
          if(response.allow){

            qts_entradas = parseInt($("#qts_entradas").val()); 
            qts_oculta = parseInt($("#neuronios_camada_oculta").val()); 
            qts_saidas = parseInt($("#qts_saidas").val()); 

            //Begining drawing
            $("#neural-network-drawing-loading").html('<?=$this->Html->image("sistema/loader-small.gif", array("alt" => "", "title" => "Carregando..."));?>');   
            desenharTopologiaNN(elementId, qts_entradas, qts_oculta, qts_saidas);

          }
        
        }

        $("#qts_entradas").bind("change", function(){

          verificarTopologiaRedeNeural("neural-network-drawing");
        });
     
        $("#qts_saidas").bind("change", function(){

          verificarTopologiaRedeNeural("neural-network-drawing");
        });

        $("#neuronios_camada_oculta").bind("change", function(){

          verificarTopologiaRedeNeural("neural-network-drawing");
        });

        function desenharTopologiaNN(elementId, inputs, hiddens, outputs){

          var total_neurons = inputs+hiddens+outputs;

          //Construct nodes and edges
          var nodes_values = [];
          var edges_values = [];
          var nodes_keys = 0;
          var edges_keys = 0;


          for (var i = 0; i < inputs; i++) { 
              nodes_values[nodes_keys] = {data: {id: 'i'+i, value:'Entrada '+(i+1)}, style: {'height': 10,'width': 10, 'shape': 'rectangle','background-color':'#000','font-weight':'bold'}};
              nodes_keys++;

              edges_values[edges_keys] = {data: { source: 'i'+i, target: 'n'+i }, style:{ 'target-arrow-shape': 'none'} };
              edges_keys++;
          }
          for (var o = 0; o < outputs; o++) { 
              nodes_values[nodes_keys] = {data: {id: 'o'+o, value:'Saída '+(o+1)}, style: {'height': 10,'width': 10, 'shape': 'rectangle','background-color':'#000','font-weight':'bold'}};
              nodes_keys++;
              edges_values[edges_keys] = { data: { source: 'n'+(inputs+hiddens+o), target: 'o'+o }, style:{ 'target-arrow-shape': 'none'}};
              edges_keys++;              
          }

          var content_value = '';
          var style_nodes = '';
          for(var n = 0; n < total_neurons; n++){

              if(n < inputs){ 
                content_value = 'X'+(n+1); 
                style_nodes = {'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'10px','height': 28,'width': 28,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#65EC5C'}; 
        
              }else if(n >= inputs && (hiddens+inputs) > n){ 
                content_value = 'N'+((n-inputs)+1); 
                style_nodes = {'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'12px','height': 42,'width': 42,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#579CE2'};

              }else{
                content_value = 'N'+((n-inputs)+1); 
                style_nodes = {'text-valign': 'center','color': '#FFF','font-weight':'bold','font-size':'12px','height': 42,'width': 42,'border-style':'solid','border-width':2,'border-color':'#000','background-color':'#FF6356'};  

              }

              nodes_values[nodes_keys] = {data: {id: 'n'+n, value:content_value}, style:style_nodes};
              nodes_keys++;

          }

          //input to hidden
          var w_num = 1;
          for(var n_hidden=0; n_hidden < hiddens; n_hidden++){

            for(var n_input=0; n_input < inputs; n_input++){
              
              edges_values[edges_keys] = { data: { source: 'n'+n_input, target: 'n'+(n_hidden+inputs), value:'w'+w_num } };
              edges_keys++;  
              w_num++;
            }
          }

          //hidden to output
          for(var n_output=0; n_output < outputs; n_output++){

            for(var n_hidden=0; n_hidden < hiddens; n_hidden++){
              
              edges_values[edges_keys] = { data: { source: 'n'+(n_hidden+inputs), target: 'n'+(n_output+hiddens+inputs), value:'w'+w_num } };
              edges_keys++; 
              w_num++; 
            }
          }


          /*
          console.log(nodes_values);
          console.log(edges_values);
          $("#col-debug").html(JSON.stringify(edges_values_test));
          */

          var cy = window.cy = cytoscape({
              container: document.getElementById(elementId),
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
                          'font-size':'13px',
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
                  nodes: nodes_values,
                  edges: edges_values
              },
              ready: function(){
                  $("#neural-network-drawing-loading").html('');       
              } // on layoutready
          });
        }
        /*
        * Code end
        */

    }); //End function

    $(":file").filestyle({});

</script>    