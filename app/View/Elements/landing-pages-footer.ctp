    <footer id="footer">

        <div class="footer1">
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-3 widget">
                        <h3 class="widget-title">Contato</h3>
                        <div class="widget-body">
                            <p>+55 82 99670.5133<br>
                                <a href="mailto:#">contato@neuroeasy.com</a><br>
                                <br>
                                Porto Grande 4742, Marechal Deodoro, Alagoas, Brasil
                            </p>    
                        </div>
                    </div>

                    <div class="col-md-3 widget">
                        <h3 class="widget-title">Siga-nos</h3>
                        <div class="widget-body">
                            <p class="follow-me-icons clearfix">
                                <a href="http://twitter.com/thomaskanzig" target="_blank"><i class="fa fa-twitter fa-2"></i></a>
                                <a href="" target="_blank"><i class="fa fa-github fa-2"></i></a>
                                <a href="https://www.facebook.com/thomas.kanzig" target="_blank"><i class="fa fa-facebook fa-2"></i></a>
                            </p>    
                        </div>
                    </div>

                    <div class="col-md-6 widget">
                        <h3 class="widget-title">Sobre à NeuroEasy</h3>
                        <div class="widget-body">
                            <p>A NeuroEasy é um ferramenta web no qual tem como objetivo principal ao apoio de ensino em redes neurais. Promete simplificar o método de configuração da sua rede neural para se chegar ao aprendizado e generalização dos dados.</p>
                            <p>Essa ferramenta obtive origem acadêmico de conclusão da graduação do curso de Sistemas de Informação no Instituto Federal de Alagoas (IFAL) do campus Maceió, pelos alunos Thomas Kanzig e Emerson Gomes com a orientação do Profº Edison Camilo Morais.</p>
                        </div>
                    </div>

                </div> <!-- /row of widgets -->
            </div>
        </div>

        <div class="footer2">
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-6 widget">
                        <div class="widget-body">
                            <p class="simplenav">
                                <!--<a href="#">Home</a> | 
                                <a href="about.html">About</a> |
                                <a href="sidebar-right.html">Sidebar</a> |
                                <a href="contact.html">Contact</a> |-->
                                <b>
                                <?=$this->Html->link("Login",array("controller" => "landingPages", "action" => "login"),array("class" => ""));?>
                                </b>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 widget">
                        <div class="widget-body">
                            <p class="text-right">
                                &copy; <?=date("Y")?>, NeuroEasy. Produzido por <a href="http://thomaskanzig.com/" target="_blank">thomaskanzig.com</a> 
                            </p>
                        </div>
                    </div>

                </div> <!-- /row of widgets -->
            </div>
        </div>
    </footer>   