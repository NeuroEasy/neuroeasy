<style> 
.titulo{ padding-bottom:5px; margin-bottom:40px;border-bottom:#E8E5E5 solid 1px;}
</style>

<div class="container container-page">
  <div class="row">
    <div class="col-md-12">
    	
    	<div class="panel panel-default">
		<div class="panel-body">
			<h3 class="titulo"><i class="fa fa-lock"></i> Editar Conta</h3>


            <div class="row">
            <div class="col-md-6">
    		
	    		<?=$this->Form->create("Usuario", array("action" => "editar_conta", "novalidate" => true));?>

	            <div class="row form-group">
	              <label for="login" class="col-sm-2 col-md-4 control-label">Login:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("username",array("label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "login"
	                                    )
	              );?>
	              </div>
	            </div>

	            <div class="row form-group">
	              <label for="senha_atual" class="col-sm-2 col-md-4 control-label">Senha Atual:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("current_password", array("type" => "password",
	              									 "label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "senha_atual"
	                                    )
	              );?>
	              </div>
	            </div>


	            <div class="row form-group">
	              <label for="nova_senha" class="col-sm-2 col-md-4 control-label">Nova Senha:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("new_password", array("type" => "password",
	              									 "label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "nova_senha"
	                                    )
	              );?>
	              </div>
	            </div>


	            <div class="row form-group">
	              <label for="confirmar_senha" class="col-sm-2 col-md-4 control-label">Confirmar Nova Senha:</label>
	              <div class="col-sm-10 col-md-8">
	              <?=$this->Form->input("confirm_password", array("type" => "password",
	              									 "label" => false,
	                                                 "class"=>"form-control",
	                                                 "id" => "confirmar_senha"
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
