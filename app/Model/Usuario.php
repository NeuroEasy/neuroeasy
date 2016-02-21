<?php


class Usuario extends AppModel{
	
	public $name = "Usuario";	
	public $useTable = "lca_users";
	
	public $belongsTo = array("Grupo" => array("className" => "UsuarioGrupo",
						                   "foreignKey" => "grupo_id",
										   "fields" => array("descricao")
						                  ),
						
						"Perfil" => array("className" => "Perfil",
						                   "foreignKey" => "profile_id",
										   "fields" => array("id","primeiro_nome","ultimo_nome")
						                  )				  				  
						);
						    
	
	public $validate = array("username" => array("campoObrigatorio" => array("rule" => "notBlank",
											                            "message" => "O campo Login não pode estar vazio"
														                ),
												"loginUnico" => array("rule" => "isUnique",
											                            "message" => "Esse login ja esta cadastrado."
														                ),
												"minimoCaracteres" => array("rule" => array('lengthBetween', 5, 30),
																		"message" => "O campo deverá possuir entre 5 á 30 caracteres",
																		),	
												'alphaNumeric' => array(
														                'rule' => 'alphaNumeric',
														                'required' => true,
														                'message' => 'Só pode haver letras e números'
															            )								                 																		
																		
											),
							"new_password" => array("campoObrigatorio" => array("rule" => "notBlank",
											                            "message" => "O campo Senha &eacute; extremamente obrigatorio",
																		"on" => "create"
														                ),
										   "confirmarSenhas" => array("rule" => "matchPasswords",
											                            "message" => "Senhas não combinam"
														                ),
											"minimoCaracteres" => array("rule" => array('lengthBetween', 5, 30),
																		"message" => "O campo deverá possuir entre 5 á 30 caracteres",
																		"allowEmpty" => true
																		)				                								
											),																		
	                      "grupo_id" => array("campoObrigatorio" => array("rule" => "notBlank",
											                              "message" => "Informar o grupo do usuario e extremamente obrigatorio"
																          )						 
											  ),
							"confirm_password" => array("minimoCaracteres" => array("rule" => array('lengthBetween', 5, 30),
																		"message" => "O campo deverá possuir entre 5 á 30 caracteres",
																		"allowEmpty" => true
																		),
										    "campoObrigatorio" => array("rule" => "notBlank",
											                            "message" => "O campo Senha é extremamente obrigatorio",
																		"on" => "create"
														                ),
										   "confirmarSenhas" => array("rule" => "matchPasswordsConfirmarSenha",
											                            "message" => "Senhas não combinam"
														                )  								
											),
							"current_password" => array("campoObrigatorio" => array("rule" => "notBlank",
											                            "message" => "Para que possa realizar as alterações é necessário informar a senha atual",
																		"on" => "update"
																		
														                ),
										   "validarSenha" => array("rule" => "validarSenhaAtual",
											                            "message" => "Senha Errada!",
																		"allowEmpty" => true
														                )  								
											)										  

	                      );	

 

	public function matchPasswords($data)
	{
		if ($data["new_password"] == $this->data["Usuario"]["confirm_password"]) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
						  

	public function matchPasswordsConfirmarSenha($data)
	{
		
		if ($data["confirm_password"] == $this->data["Usuario"]["new_password"]) 
		{
			return true;
		}
		else
		{
			return false;	
		}
		
	}
	
	public function validarSenhaAtual($data)
	{
		
		$senha_hash = Security::hash($data["current_password"],NULL,TRUE);
		$id = $this->data["Usuario"]["id"];
		
		$count = $this->find("count",array("conditions" => array("Usuario.password" => $senha_hash,"Usuario.id" => $id)));
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function beforeSave($options = array())
	{ 
	    
		if(!empty($this->data["Usuario"]["new_password"]))
		{
			$this->data["Usuario"]["password"] = Security::hash($this->data["Usuario"]["new_password"],NULL,TRUE);	
		}
		else
		{
			$id = $this->data["Usuario"]["id"];
			$dados = $this->findById($id);
			
			$this->data["Usuario"]["new_password"] = $dados["Usuario"]["password"];
		}
		return true;
	}

	public function verificarUsuario($login, $senha)
	{
		$senha_hash = Security::hash($senha,NULL,TRUE);
		
		$count = $this->find("count",array("conditions" => array("Usuario.password" => $senha_hash,"Usuario.username" => $login)));
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//Verifica se existe um login (evita a repetição de login)
	public function usernameUnique($data)
	{
		$count = $this->find("count", array("conditions" => array("Usuario.username" => $data["username"],"Usuario.deleted IS NULL")));

		if($count > 0)
		{
			return FALSE;
		}else{
			return TRUE;
		}
	}



}


?>