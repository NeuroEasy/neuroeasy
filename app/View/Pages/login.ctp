    <header id="head" class="secondary"></header>

    <!-- container -->
    <div class="container">

        <div class="row">
            
            <!-- Article main content -->
            <article class="col-xs-12 maincontent">
                <header class="page-header">
                    <h1 class="page-title">Login</h1>
                </header>
                
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <center>
                            <?=$this->Html->image("neuro-easy.png", array("class" => "img-responsive","style" => "margin-bottom:10px;","alt" => "NeuroEasy","title" => "NeuroEasy - Ambiente de aprendizagem em Redes Neurais Artificiais"));?>
                            </center>
                            <p class="text-center text-muted">Caso não tenha registro, <?=$this->Html->link("registre-se agora",array("controller" => "pages", "action" => "registrar"),array());?> gratuitamente para usufruir do nosso sistema.</p>

                            <?=$this->Session->flash("login"); ?> 
                            <hr>
                            
                            <?=$this->Form->create("Pages",array("controller"=>"pages","action" => "login"));?>
                                <div class="top-margin">
                                    <label>Login <span class="text-danger">*</span></label>
                                    <?=$this->Form->input("username",array("type" => "text",
                                       "label" => false,
                                       "class"=>"form-control",
                                       "placeholder"=>"Digite seu login",
                                       "autofocus"=>"autofocus"
                                       )
                                    );?>
                                </div>
                                <div class="top-margin">
                                    <label>Senha <span class="text-danger">*</span></label>
                                    <?=$this->Form->input("password",array("type" => "password",
                                                                           "label" => false,
                                                                           "class"=>"form-control",
                                                                           "placeholder"=>"Digite sua senha"
                                                                           )
                                    );?>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <!--<b><a href="">Esqueceu Senha?</a></b>-->
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <button class="btn btn-red-light" type="submit"><i class="fa fa-sign-in"></i> Logar</button>
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