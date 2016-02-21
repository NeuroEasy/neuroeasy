<style>
.table{ margin-top:15px;}
.table-bordered th{ font-size:13px;}
.titulo-listagem{ font-weight: bold; border-bottom: 1px solid #E6E4E4;padding-bottom: 20px;}
</style>

<div class="container container-page">
  <div class="row">
    <div class="col-md-12">

    	<div class="panel panel-default">
		  <div class="panel-body">
		  	
		  	<h3 class="titulo-listagem"><i class="fa fa-list-ul"></i> Listagem dos Treinamentos</h3>
	        
            <?php if(count($itens) > 0){ ?>   
            <div class="row">
                <div class="col-sm-12 col-md-8"></div>  
                <div class="col-sm-12 col-md-4">
                  <?php echo $this->Form->create("Treinamento", array("action" => "buscar","role" => "search"));?> 
                  <div class="input-group">

                      <?php echo $this->Form->input("busca", array("type" => "text",
                                                                   "value" => $busca,    
                                                                   "class" => "form-control",
                                                                   "placeholder" => "Procurar por nome",
                                                                   "label" => false
                                                                   )
                                                    ); 
                      ?>

                      <div class="input-group-btn">
                          <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                      </div> 
                  </div>
                  <?php echo $this->Form->end(); ?> 
                </div> 
            </div>

	        <table class="table table-bordered table-hover table-striped" id="datasets">
                
                <thead>
                <tr id="titles">	
                	<th>ID</th>
                	<th>Nome</th>
                	<th>Data</th>
                	<th style="width:30%;">Ações</th>
                </tr>
                <thead>

                <tbody>
                
                <?php foreach($itens as $item): ?>	
                <tr>
                	<td><?=$item["Treinamento"]["id"];?></td>
                	<td><?=$item["Treinamento"]["name"];?></td>
                	<td><?=MyDate::Show($item["Treinamento"]["created"], "d/m/Y H:s");?></td>
                	<td>
                		
						        <?=$this->Html->link("<i class=\"fa fa-eye\"></i> Visualizar", ["action" => "visualizar", "id" => $item["Treinamento"]["id"]], ["class" =>"btn btn-sm btn-default", "role" => "button", "escape" => false]);?>

                		<?=$this->Html->link("<i class=\"fa fa-trash\"></i> Excluir", ["action" => "excluir", "id" => $item["Treinamento"]["id"]], ["class" =>"btn btn-sm btn-danger", "role" => "button", "escape" => false], "Deseja realmente excluir esse treinamento?");?>
                	</td>
                </tr>
            	<?php endforeach;?>

                </tbody>  

            </table>

            <?=$this->element("paginacao");?>

            <?php }else{?>
            <br/>
            <?=$mensagem;?> <br/><?=$this->Html->link("Comece agora clicando aqui", ["action" =>"novo"]);?>
            <br/><br/> 
            <?php }?>

            

		  </div>
		</div>

    </div> 
  </div>
</div>