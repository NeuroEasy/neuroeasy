<div class="subnavbar">

  <div class="subnavbar-inner">
  
    <div class="container">

      <ul class="mainnav">
      
        <li class="dropdown <?=$this->MyHtml->isActive('treinamentos');?>">         
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-database"></i>
            <span>Treinamentos</span>
            <b class="caret"></b>
          </a>  
        
          <ul class="dropdown-menu">
            <li>
            <?=$this->Html->link("<i class=\"fa fa-plus-circle\"></i> Iniciar um novo",array("controller" => "treinamentos", "action" => "novo"),array("class" => "", "escape" => false));?> 
            </li>
            <li>
            <?=$this->Html->link("<i class=\"fa fa-list-ul\"></i> Listar todos",array("controller" => "treinamentos", "action" => "listar"),array("class" => "", "escape" => false));?>
            </li>
          </ul>           
        </li>

        <li class="<?=$this->MyHtml->isActive('pages','doc');?>">         
          <?=$this->Html->link("<i class=\"fa fa-book\"></i><span>Documentação</span>",array("controller" => "pages", "action" => "doc"),array("class" => "","escape" => false));?>                   
        </li>

        <!--
        <li class="active">         
          <a href="guidely.html">
            <i class="fa fa-video-camera"></i>
            <span>App Tour</span>
          </a>                    
        </li>
                
        
        <li class="  dropdown">         
          <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-long-arrow-down"></i>
            <span>Drops</span>
            <b class="caret"></b>
          </a>  
        
          <ul class="dropdown-menu">
                      <li><a href="icons.html">Icons</a></li>
            <li><a href="faq.html">FAQ</a></li>
                        <li><a href="pricing.html">Pricing Plans</a></li>
                        <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="error.html">404</a></li>
                    </ul>           
        </li>
        -->
      
      </ul>

    </div> <!-- /container -->
  
  </div> <!-- /subnavbar-inner -->

</div> <!-- /subnavbar -->