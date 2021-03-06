
[![NeuroEasy](http://thomaskanzig.com/dev/neuro-easy/img/neuro-easy.png)](http://thomaskanzig.com/dev/neuro-easy/)

### NeuroEasy - Ambiente de aprendizagem em RNA's

A NeuroEasy é um ferramenta web no qual tem como objetivo principal ao apoio de ensino em redes neurais. Promete simplificar o método de configuração da sua rede neural para se chegar ao aprendizado e generalização dos dados.

Essa ferramenta obtive origem acadêmico de conclusão da graduação do curso de Sistemas de Informação no Instituto Federal de Alagoas (IFAL) do campus Maceió, pelos alunos [Thomas Kanzig](https://www.facebook.com/thomas.kanzig) e [Emerson Gomes](https://www.facebook.com/emersonpgomes) com a orientação do [Profº. Msc. Edison Camilo Morais](https://www.facebook.com/edison.camilo.56). 

Acesse a versão [Demo](http://thomaskanzig.com/dev/neuro-easy/)

#Instalação

##Requisitos

* Apache (com mod_rewrite ativado)
* PHP 5.3 ou acima
* MySQL 4 ou acima

ou instale um pacote no qual contêm todos esses componentes como [xampp](https://www.apachefriends.org/pt_br/index.html) ou [wamp](http://www.wampserver.com/en/)

* Cumprir com as exigências de funcionamento do Framework CakePHP 2.x (visite o site [aqui](http://book.cakephp.org/2.0/en/installation.html#requirements) e verifique todas as exigências necessárias)

##Download

Basta seguir os comandos do git abaixo para baixar a aplicação NeuroEasy ou [clique aqui](https://github.com/NeuroEasy/neuroeasy/archive/master.zip) para baixar o zip:

####Com git clone

	git clone git://github.com/NeuroEasy/neuroeasy.git NeuroEasy

##Configuração

Siga os passos abaixo ordenamente para o funcionamento do NeuroEasy na máquina:

* Importe o modelo do banco de dados ([db_neuroeasy.sql](https://github.com/NeuroEasy/neuroeasy/blob/master/sql/db_neuroeasy.sql)) para o seu servidor;
* No arquivo [database.php](https://github.com/NeuroEasy/neuroeasy/blob/master/app/Config/database.php) inserir adequadamente de acordo com os acessos do seu banco de dados. Veja abaixo o código:

```php
	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'db_neuroeasy',
		'prefix' => '',
		'encoding' => 'utf8',
	);
```

* No arquivo [bootstrap.php](https://github.com/NeuroEasy/neuroeasy/blob/master/app/Config/bootstrap.php) deve-se informar na variável $url o diretório de onde a aplicação NeuroEasy será acessado no browser. Veja abaixo o código:

```php
/**
 * Configure de site_url and sistema_url
 */
$url = "http://localhost/neuro-easy";

Configure::write('site_url', $url.'/');
Configure::write('sistema_url', $url.'/sistema/');
```
* Na diretório [app/webroot/files/](https://github.com/NeuroEasy/neuroeasy/tree/master/app/webroot/files), as pastas devem estar com a permissão liberada com código de permissão 0777

###Opcional
* Caso seja necessário e estiver treinando uma rede grande, precisará aumentar o tempo de execução e de mémoria no PHP. Para facilitar a sua vida basta você configurar essas informações no próprio arquivo do [BackPropagationComponent.php](https://github.com/NeuroEasy/neuroeasy/blob/master/app/Plugin/NeuralNetwork/Controller/Component/BackPropagationComponent.php). Veja abaixo o código:


```php
<?php
ini_set('memory_limit', '1100M'); 
set_time_limit(3600);
```

#Bônus

##Datasets

Separamos alguns exemplos de conjunto de dados do mundo real dentro da pasta [datasets](https://github.com/NeuroEasy/neuroeasy/tree/master/datasets) para realizar alguns testes no NeuroEasy e adquirir o conhecimento de como deve ser o formato do arquivo para a importação dos dados e realizar o treinamento da rede.

Recomendamos o repositório [UCI - Machine Learning Repository](http://archive.ics.uci.edu/ml/) bastante usado na área acadêmica em aprendizagem de máquina, no qual, diversos conjuntos de dados do mundo real são disponibilizados.

