<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	
/*
* Pages from the System
*/
	Router::connect('/sistema/treinamentos', array('controller' => 'treinamentos', 'action' => 'listar'));
	Router::connect('/sistema/doc', array('controller' => 'pages', 'action' => 'doc'));
	Router::connect('/sistema/logout', array('controller' => 'pages', 'action' => 'logout'));
	Router::connect('/sistema/treinamentos/novo', array('controller' => 'treinamentos', 'action' => 'novo'));
	Router::connect('/sistema/treinamentos/listar', array('controller' => 'treinamentos', 'action' => 'listar'));
	Router::connect('/sistema/treinamentos/mostrar-dados-importacao', array('controller' => 'treinamentos', 'action' => 'mostrar_dados_importacao'));
	Router::connect('/sistema/treinamentos/executar', array('controller' => 'treinamentos', 'action' => 'executar'));
	Router::connect('/sistema/treinamentos/salvar', array('controller' => 'treinamentos', 'action' => 'salvar'));
	Router::connect('/sistema/treinamento/:id/excluir', array('controller' => 'treinamentos', 'action' => 'excluir'));
	Router::connect('/sistema/treinamento/:id/visualizar', array('controller' => 'treinamentos', 'action' => 'visualizar'));
	Router::connect('/sistema/treinamento/:id/aprendizado', array('controller' => 'treinamentos', 'action' => 'pesos'));
	Router::connect('/sistema/usuario/editar-conta/*', array('controller' => 'usuarios', 'action' => 'editar_conta'));
	Router::connect('/sistema/usuario/editar-perfil/*', array('controller' => 'usuarios', 'action' => 'editar_perfil'));

	Router::connect('/sistema/download/:cod/pesos-bias', array('controller' => 'downloads', 'action' => 'pesos_bias'));
	Router::connect('/sistema/download/:cod/:linguagem/codigo-fonte-feedforward', array('controller' => 'downloads', 'action' => 'codigo_fonte_feedforward'));

	Router::connect('/', array('controller' => 'pages', 'action' => 'login'));
	Router::connect('/login', array('controller' => 'pages', 'action' => 'login'));
	Router::connect('/registrar', array('controller' => 'pages', 'action' => 'registrar'));
 
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
