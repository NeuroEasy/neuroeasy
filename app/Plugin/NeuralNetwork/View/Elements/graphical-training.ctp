<?=$this->Html->script('NeuralNetwork.modal');?>
<?=$this->Html->css('NeuralNetwork.print-calculation.css?v=0.6');?>

<?php

//Preparar dados para o grafico 'Comparação de Saidas' 
$samples = array();
for($n=1; $n<$dataOutputsCompare["total_samples"]; $n++){
	$samples[] = $n;
}


//Preparar dados do gráfico de Erro Médio Quadrático (novo)
$meanSquaredError = $errors = array();
foreach($errorMeasures['eqm-total'] as $type_data => $errors){

    if($type_data == "train"){
        $data_name = "Treinamento (".$configInfo['type_data'][$type_data]['percentage']."% - ".$configInfo['type_data'][$type_data]['count_samples']." amostras)";
    }else if($type_data == "validation"){
        $data_name = "Validação (".$configInfo['type_data'][$type_data]['percentage']."%) - ".$configInfo['type_data'][$type_data]['count_samples']." amostras)";
    }
    
    foreach($errors as $epoch => $error){

        $meanSquaredError["epochs"][] = "'{$epoch}'";
    }
    
    $meanSquaredError["data"][] = "{name: '".$data_name."', data:[".implode(", ", $errors)."]}";
}


?>

<style>

	#grafico-erro-quadratico{ margin-top:120px; margin-bottom:60px;}
    .show-error-prediction{ margin-bottom:50px;}

</style>

<?php echo "<strong>Tempo de duração:</strong> ".$timeDuration;?>


<script type="text/javascript">
$(function () {
    
    $('#grafico-erro-quadratico-medio-total').highcharts({
        title: {
            text: 'Total de Erro Médio Quadrático',
            x: -20 //center
        },
        subtitle: {
            text: 'Fonte: autor',
            x: -20
        },
        xAxis: {
            categories: [<?php echo implode(", ", $meanSquaredError['epochs']);?>],
            title: {text: 'Épocas'}
        },
        yAxis: {
            title: {
                text: 'TEQM'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        <?php if(!empty($errorMeasures["mean-squared-error"]["validation"])){ ?>
        tooltip: {
        formatter: function(){
            return '<span style=\"' + this.points[1].series.color + '\"></span>' + this.points[1].series.name + ': <b>' + this.points[1].y + '</b><br>' + '<span style=\"' + this.points[0].series.color + '\"></span>' + this.points[0].series.name + ': <b>' + this.points[0].y + '</b>' + '<br><b>Erro:</b> ' + Math.abs((this.points[1].y - this.points[0].y)).toFixed(2);
        },
        shared: true
        }, 
        <?php } ?>        
        series: [<?php echo  implode(", ", $meanSquaredError["data"]);?>]
    });
});
</script>

<div id="grafico-erro-quadratico-medio-total" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php if($configInfo['type_data']['train']['count_samples'] > 0){
echo "<strong>Treinamento:</strong> ".$errorMeasures['eqm-total']['train'][$configInfo['epochs_executed']];    
}?>
<br/>
<?php if($configInfo['type_data']['validation']['count_samples'] > 0){
echo "<strong>Validação:</strong> ".$errorMeasures['eqm-total']['validation'][$configInfo['epochs_executed']];    
}?>




<?php for($n=0; $n<$dataOutputsCompare["count"]; $n++){ ?>    
    <script>
    $(function () {
        
        $('#compare-outputs-<?=$n;?>').highcharts({
            title: {
                text: 'Obtido vs. Desejado (<?=$dataOutputsCompare["titles"][$n];?>)',
                x: -20 //center
            },
            subtitle: {
                text: 'Fonte: autor',
                x: -20
            },
            xAxis: {
                categories: [<?php echo implode(", ",$samples);?>],
                title: {text: 'Nº de Resultados Analisados'}
            },
            yAxis: {
                title: {
                    text: 'Valores (<?=$dataOutputsCompare["titles"][$n];?>)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            tooltip: {
            formatter: function(){
                return '<span style=\"' + this.points[1].series.color + '\"></span>' + this.points[1].series.name + ': <b>' + this.points[1].y + '</b><br>' + '<span style=\"' + this.points[0].series.color + '\"></span>' + this.points[0].series.name + ': <b>' + this.points[0].y + '</b>' + '<br><b>Erro:</b> ' + Math.abs((this.points[1].y - this.points[0].y)).toFixed(2);
            },
            shared: true
            },        
            series: [{
                name: 'Desejado',
                data: [<?php echo implode(", ",$dataOutputsCompare['desired'][$n]);?>]
            },
            {
                name: 'Obtido',
                data: [<?php echo implode(", ",$dataOutputsCompare['provided'][$n]);?>]            
            }]
        });
    });
    </script>    
    <div id="compare-outputs-<?=$n;?>" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <div class="show-error-prediction">
        <?php if($configInfo['type_data']['train']['count_samples'] > 0){ ?>
        <strong>Treinamento - R^2:</strong> <?=$errorMeasures["rquad"]['train']["normalized"][$n];?>, 
        <strong>R^2 Ajustado:</strong> <?=$errorMeasures["rquad-adjusted"]['train']["normalized"][$n];?>
        <?php } ?>
        <br/>
        <?php if($configInfo['type_data']['validation']['count_samples'] > 0){ ?>
        <strong>Validação - R^2:</strong> <?=$errorMeasures["rquad"]['validation']["normalized"][$n];?>, 
        <strong>R^2 Ajustado:</strong> <?=$errorMeasures["rquad-adjusted"]['validation']["normalized"][$n];?>
        <?php } ?>
    </div>
<?php } ?>
    
    <div class="row">
    <div class="col-md-6">
    <p>
    O <strong><i>"coeficiente de determinação R^2"</i></strong> é uma medida que diz quão bem a
reta de regressão da amostra se ajusta aos dados. O valor numérico do
coeficiente varia entre zero e um.

    </p>
    </div>
    </div>


<script>
$(function () {

   var options = { 
      url: '<?=$urlPost;?>',
      dataType:  'json',        // 'xml', 'script', or 'json' (expected server response type)
      //data: { nome_treinamento: $('input[name="nome_treinamento_from_modal"]').val() },
      beforeSubmit: function (formData, jqForm, options) { 

        $("#erro-treinamentos-salvar").html('<center><?=$this->Html->image("sistema/loader-modelo01.GIF", array("alt" => "", "title" => "Carregando..."));?></center>');
        //console.log(formData); return false;
        
      },
      success: function(response) { 
        
        console.log(response);
        
        if(response.success){
            alert(response.message);
            window.location = response.url_redirect;
        }

        return false;
      },
      error: function(data) { 

        $("#erro-treinamentos-salvar").html(data.status +" <br/> "+ data.statusText +" <br/> "+ data.responseText); 
      }

    };
    $("#save_training").ajaxForm(options);

    $('#submit-salvar-treinamento').click(function(){

        $('#save_training').submit();    
    });  

    $("#btn-toogle-modal").click(function(){
        $("#myModal").modal('show');
    });


    $('input[name="nome_treinamento_from_modal"]').bind("change", function(){

         $('input[name="data[Treinamento][nome]"]').val($(this).val());  
    });

    $('textarea[name="descricao_treinamento_from_modal"]').bind("change", function(){

         $('input[name="data[Treinamento][descricao]"]').val($(this).val());  
    });

});
</script> 

<div class="row">
<div class="col-md-12">

<?=$this->Form->create("Treinamento",['id' => 'save_training','class' => 'pull-right', 'style' => 'margin-top:20px;']);?>
<?=$this->Form->hidden("posts", array("value" => json_encode($posts)));?>
<?=$this->Form->hidden("pesos", array("value" => json_encode($weightsFinal)));?>
<?=$this->Form->hidden("resultados", array("value" => json_encode($resultados)));?>
<?=$this->Form->hidden("data_max_min", array("value" => json_encode($dataMaxMin)));?>
<?=$this->Form->hidden("json_step_by_step", array("value" => $jsonStepByStep));?>
<?=$this->Form->hidden("nome", ["id" => "nome_treinamento"]);?>
<?=$this->Form->hidden("descricao", ["id" => "descricao_treinamento"]);?>
<?=$this->Form->end();?>

<a role="button" class="btn btn-success pull-right" id="btn-toogle-modal"><i class='fa fa-floppy-o'></i> Salvar Treinamento</a>

</div>
</div>

<div class="row">
<div class="col-md-12" id="erro-treinamentos-salvar">
</div>
</div>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Informações sobre o treinamento da rede neural</h4>
      </div>
      <div class="modal-body">
        <p>
       
          <div class="form-group">
            <label for="nome_t">Nome:</label>
            <input type="email" class="form-control" id="nome_t" placeholder="Digite algum nome para a identificação" name="nome_treinamento_from_modal">
          </div>

          <div class="form-group">
            <label for="descricao_t">Descrição:</label>
            <textarea class="form-control" rows="5" id="descricao_t" placeholder="Digite alguma descrição breve sobre o treinamento" name="descricao_treinamento_from_modal"></textarea>
          </div>

        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        <button type="button" class="btn btn-sm btn-success" id="submit-salvar-treinamento"><i class='fa fa-floppy-o'></i> Salvar Agora</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->