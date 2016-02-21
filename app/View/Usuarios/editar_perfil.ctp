<style> 
.titulo{ padding-bottom:5px; margin-bottom:40px;border-bottom:#E8E5E5 solid 1px;}
</style>

<div class="container container-page">
  <div class="row">
    <div class="col-md-12">
    	
    	<div class="panel panel-default">
		<div class="panel-body">
			<h3 class="titulo"><i class="fa fa-user"></i> Editar Perfil</h3>


            <div class="row">
            <div class="col-md-6">
    		
	    		<?=$this->Form->create("Usuario", array("action" => "editar_perfil"));?>

	            <div class="row form-group">
	              <label for="primeiro_nome" class="col-sm-2 col-md-4 control-label">Primeiro Nome:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("Perfil.primeiro_nome",array("type" => "text","label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "primeiro_nome"
	                                    )
	              );?>
	              </div>
	            </div>

	            <div class="row form-group">
	              <label for="ultimo_nome" class="col-sm-2 col-md-4 control-label">Último Nome:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("Perfil.ultimo_nome",array("type" => "text","label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "ultimo_nome"
	                                    )
	              );?>
	              </div>
	            </div>

	            <div class="row form-group">
	              <label for="email_contato" class="col-sm-2 col-md-4 control-label">Email Contato:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("Perfil.email_contato",array("type" => "email","label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "email_contato"
	                                    )
	              );?>
	              </div>
	            </div>


	            <div class="row form-group">
	            	<div class="col-sm-10 col-md-12 text-right">

              			<button type="submit" class="btn btn-red-light"><i class="fa fa-floppy-o"></i> Salvar Alterações</button>

	            	</div>
	            </div>

	    		<?=$this->Form->end(); ?>

    		</div>
    		</div>

    	</div>
    	</div>	

    </div>
  </div>
</div>    
