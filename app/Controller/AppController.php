<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $helpers = array("MyHtml");
	public $components = array("Auth"=>array(
											 "authError"=>"Não está logado no sistema ou tempo expirou",
											 "flashElement" => "messages/alert-danger",   
											 "loginAction" => array("controller"=>"pages","action"=>"login"),
											 "loginRedirect" => array("controller" => "treinamentos","action" => "listar"),
											 "logoutRedirect" => array("controller" => "pages","action" => "login"),
											 "authenticate" => array(
																	 'Form' => array(
																					'userModel' => 'Usuario',
																					'scope' => array('Usuario.ativo' => 1) 
																				)		
																	),
											 					
											 "authorize" => array("Controller")
											 ),
											
								"Session","Security","Lca","DebugKit.Toolbar");

	/*
	 * Prepare custom parameters for user allow features in the system. 
	 * This is often the most simple way to authorize users.
	 */
	public function isAuthorized($user)
	{
		$grupo_id = $user["grupo_id"];
		$this->permissoes = $this->Lca->getPermissoes($grupo_id, "sistema");

		$this->set("permissoes", $this->permissoes);
		
		//Bloqueia caso acesse alguma página não privilegiado
		if(!$this->Lca->semaforo($this->permissoes,$this->request->params["controller"], $this->request->params["action"]))
		{
			$this->Session->destroy();
			$this->Session->setFlash("Acesso a página negado! Por favor, efetue o login novamente.","messages/alert-login-danger", array(), "login");
			$this->redirect(array("controller" => "landingPages", "action" => "login"));
		}

		return true;

	}

	public function beforeFilter(){

		$this->set('title_for_layout', 'NeuroEasy - Ambiente de aprendizagem em Redes Neurais');
		$this->set('site_url', Configure::read('site_url'));
		$this->set('sistema_url', Configure::read('sistema_url'));
	}

}
