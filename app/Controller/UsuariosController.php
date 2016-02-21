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
class UsuariosController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array("Usuario", "Perfil");
	public $layout = "sistema";

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function editar_conta(){
			
		$id = $this->Session->read("Auth.User.id");	

		if($this->request->is("post"))
		{ 
			$this->request->data["Usuario"]["id"] = $id;

		    if($this->Usuario->save($this->request->data))
		    {
				$this->Session->setFlash("Conta atualizado com sucesso","messages/alert-top-page-success", null, 'top-page');
				$this->redirect(["action" => "editar_conta"]);
			}else{

			 	$this->Session->setFlash("Encontramos alguns erros no formulário, por favor verifique abaixo!","messages/flash-message-alert-danger", array(), "pagina");
			}

		}else
		{	
			$this->request->data = $this->Usuario->read(NULL, $id);
		}	

	}


	public function editar_perfil(){

		$id = $this->Session->read("Auth.User.profile_id");	

		if($this->request->is("post"))
		{ 
			$this->request->data["Perfil"]["id"] = $id;

		    if($this->Perfil->save($this->request->data))
		    {
				//Update the name profile in view
				$this->Session->write("Auth.User.Perfil.primeiro_nome", $this->request->data["Perfil"]["primeiro_nome"]);
				$this->Session->write("Auth.User.Perfil.ultimo_nome", $this->request->data["Perfil"]["ultimo_nome"]);

				$this->Session->setFlash("Perfil atualizado com sucesso","messages/alert-top-page-success", null, 'top-page');
				$this->redirect(["action" => "editar_perfil"]);
			}else{

			 	$this->Session->setFlash("Encontramos alguns erros no formulário, por favor verifique abaixo!","messages/flash-message-alert-danger", array(), "pagina");
			}

		}else
		{	
			$this->request->data = $this->Perfil->read(NULL, $id);
		}	

	}	
		
}
