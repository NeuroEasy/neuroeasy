    <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top headroom" >
        <div class="container">
            <div class="navbar-header">
                <!-- Button for smallest screens -->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="<?=$site_url;?>">
                    <?=$this->Html->image("logo-neuro-easy-white.png", array("class" => "logo-header","alt" => "NeuroEasy","title" => "NeuroEasy - Ambiente de aprendizagem em Redes Neurais"));?>
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav pull-right">
                    <li>
                    <?=$this->Html->link("<i class=\"fa fa-home\"></i> Home",array("controller" => "landingPages", "action" => "home"),array("class" => "","escape" => false));?>
                    </li>
                    <li class="active">
                    <?=$this->Html->link("<i class=\"fa fa-coffee\"></i> Sobre Nós",array("controller" => "landingPages", "action" => "sobrenos"),array("class" => "","escape" => false));?>
                    </li>
                    <!--
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">More Pages <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="sidebar-left.html">Left Sidebar</a></li>
                            <li><a href="sidebar-right.html">Right Sidebar</a></li>
                        </ul>
                    </li>
                    -->
                    <li>
                        <?=$this->Html->link("<i class=\"fa fa-envelope\"></i> Contato",array("controller" => "landingPages", "action" => "contato"),array("class" => "","escape" => false));?>
                    </li>
                    
                    <li>
                    <?=$this->Html->link("Entrar / Registrar",array("controller" => "landingPages", "action" => "login"),array("class" => "btn"));?>    
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div> 
    <!-- /.navbar -->