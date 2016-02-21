<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow(array("logout","login","registrar")); //This mean everything here are allowed
	}


	public function logout(){

		$this->redirect($this->Auth->logout()); 
	}

	public function doc(){

		$this->layout = "sistema";
	}

	public function login(){
		
		$this->layout = "auth";

		if($this->Auth->loggedIn()){

			$this->redirect($this->Auth->redirectUrl());
		}

		if($this->request->is("post"))
		{
			$this->Auth->request->data["Usuario"] = $this->request->data['Pages'];

			if($this->Auth->login())
			{
				$this->redirect($this->Auth->redirectUrl());
			}
			else
			{	
				$this->Session->setFlash("Login ou Senha Incorreta","messages/alert-login-danger", array(), "login");

			}
		}

	}

	public function registrar(){
		
		$this->layout = "auth";

		if($this->request->is("post"))
		{
			$this->loadModel('Usuario');
			$this->request->data["Usuario"]["grupo_id"] = 1; //Set user group 

			$this->Usuario->create(); 
			if($this->Usuario->saveAll($this->request->data))
			{
				//Set the necessary variables for execute the first login
				$newuser = ["username" => $this->request->data["Usuario"]["username"], "password" => $this->request->data["Usuario"]["new_password"]];
				$this->Auth->request->data["Usuario"] = $newuser;
				$this->Auth->login(); //Execute the login

				$this->Session->setFlash("<strong>Parabéns!</strong> A sua conta foi registrado com sucesso. Faça já o seu primeiro treinamento com redes neurais.","messages/alert-top-page-success", null, "top-page");
				//$this->redirect(array("controller" => "treinamentos", "action" => "novo"));
				$this->redirect($this->Auth->redirectUrl());
			}else{

				$this->Session->setFlash("Foi identificado alguma invalidez nos campos abaixo.","messages/alert-danger", array(), "message");
			}
		}			
	}		
}
