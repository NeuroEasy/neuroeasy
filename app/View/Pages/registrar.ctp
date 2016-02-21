    <header id="head" class="secondary"></header>

    <!-- container -->
    <div class="container">

        <div class="row">
            
            <!-- Article main content -->
            <article class="col-xs-12 maincontent">
                <header class="page-header">
                    <h1 class="page-title">Criar Conta</h1>
                </header>
                
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 class="thin text-center">Registrar uma nova conta</h3>
                            <p class="text-center text-muted">Se você já possui uma conta, acesse a página de <?=$this->Html->link("login",array("controller" => "pages", "action" => "login"),array());?> para se logar.</p>
                            <?=$this->Session->flash("message"); ?>
                            <hr>

                            <?=$this->Form->create("Pages", array("controller"=>"pages", "action" => "registrar"));?>

                                <div class="row top-margin">
                                    <div class="col-sm-6">
                                    <label id="">Primeiro Nome <span class="text-danger"><strong>*</strong></span></label>
                                    <?=$this->Form->input("Perfil.primeiro_nome",array("type" => "text",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite seu primeiro nome",
                                                                           "id" => "primeiro_nome"
                                                                           )
                                    );?>
                                    </div>
                                    <div class="col-sm-6">
                                    <label ud="ultimo_nome">Último Nome <span class="text-danger"><strong>*</strong></span></label>
                                    <?=$this->Form->input("Perfil.ultimo_nome",array("type" => "text",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite seu último nome",
                                                                           "id" => "ultimo_nome"
                                                                           )
                                    );?> 
                                    </div>                                   
                                </div>

                                <div class="top-margin">
                                    <label id="email">Email <span class="text-danger"><strong>*</strong></span></label>
                                    <?=$this->Form->input("Perfil.email_contato",array("type" => "email",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite seu email de contato",
                                                                           "id" => "email"
                                                                           )
                                    );?>
                                </div>

                                <div class="top-margin">
                                    <label id="login">Login <span class="text-danger"><strong>*</strong></span></label>
                                    <?=$this->Form->input("Usuario.username",array("type" => "text",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite um login desejado",
                                                                           "id" => "login"
                                                                           )
                                    );?>
                                </div>

                                <div class="row top-margin">
                                    <div class="col-sm-6">
                                        <label id="senha">Senha <span class="text-danger"><strong>*</strong></span></label>
                                        <?=$this->Form->input("Usuario.new_password",array("type" => "password",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite uma senha",
                                                                           "id" => "senha"
                                                                           )
                                        );?>
                                    </div>
                                    <div class="col-sm-6">
                                        <label id="confirmar_senha">Confirmar Senha <span class="text-danger"><strong>*</strong></span></label>
                                        <?=$this->Form->input("Usuario.confirm_password",array("type" => "password",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Confirme a senha",
                                                                           "id" => "confirmar_senha"
                                                                           )
                                        );?>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!--
                                    <div class="col-lg-8">
                                        <label class="checkbox">
                                            <input type="checkbox"> 
                                            I've read the <a href="page_terms.html">Terms and Conditions</a>
                                        </label>                        
                                    </div>
                                    -->
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-red-light" type="submit"><i class="fa fa-user-plus"></i> Register Agora</button>
                                    </div>
                                </div>

                            <?=$this->Form->end(); ?>

                        </div>
                    </div>

                </div>
                
            </article>
            <!-- /Article -->

        </div>
    </div>  <!-- /container -->