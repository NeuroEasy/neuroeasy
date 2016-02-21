    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
       
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand navbar-logo" href="#" style="margin:0px;padding:0px;">        
            <?=$this->Html->image("logo-neuro-easy-white.png", array("class" => "","style" => "padding-top:8px; padding-right:10px; height:40px;","alt" => "NeuroEasy","title" => "NeuroEasy - Ambiente de aprendizagem em Redes Neurais"));?>
          </a>
        </div>

        <div id="bs-example-navbar-collapse-2" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i> <?=$this->Session->read('Auth.User.Perfil.primeiro_nome');?> <?=$this->Session->read('Auth.User.Perfil.ultimo_nome');?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li>
                  <?=$this->Html->link("<i class=\"fa fa-user\"></i> Editar Perfil",array("controller" => "usuarios", "action" => "editar_perfil"),array("class" => "","escape" => false));?>
                  </li>
                  <li>
                  <?=$this->Html->link("<i class=\"fa fa-lock\"></i> Editar Conta",array("controller" => "usuarios", "action" => "editar_conta"),array("class" => "","escape" => false));?>
                  </li>
                  <li class="divider"></li>
                  <li>
                    <?=$this->Html->link("<i class=\"fa fa-sign-out\"></i> Sair",array("controller" => "pages", "action" => "logout"),array("class" => "","escape" => false));?>
                  </li>
                </ul>
              </li>

            </ul>
            <!--
          <form class="navbar-form navbar-right">
            <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="q">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            </div>
            </div>
          </form>
        -->
        </div><!--/.nav-collapse -->
      </div>
    </nav>