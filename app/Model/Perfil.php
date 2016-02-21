<?php


class Perfil extends AppModel{
	
	public $name = "Perfil";	
	public $useTable = "lca_user_perfis";
	
	public $hasOne = array("Usuario" => array("className" => "Usuario",
						                      "foreignKey" => "profile_id",
											  "dependent" => true,
						                      )
						  ); 
	
	public $validate = array("nome_fantasia" => array("campoObrigatorio" => array("rule" => "notBlank",
											                              "message" => "Importante informar o Nome Completo"
																          )
																          						 
											  ),
							"email_contato" => array("campoObrigatorio" => array("rule" => "notBlank",
											                              "message" => "Importante informar o Email"
																          ),
													"emailUnico" => array("rule" => "isUnique",
											                      "message" => "Esse email ja esta em uso, favor informe outro"),
											        'email' => array("rule" => "email",
											                      "message" => "Esse email é inválido"),              			          						 
											  ),
							"primeiro_nome" => array("campoObrigatorio" => array("rule" => "notBlank",
											                              "message" => "Importante informar o primeiro nome"
																          ),
													 "minimoCaracteres" => array("rule" => array('lengthBetween', 5, 30),
																		"message" => "O campo deverá possuir entre 5 á 30 caracteres",
																		)			          			          						 
											  ),		
							"ultimo_nome" => array("campoObrigatorio" => array("rule" => "notBlank",
											                              "message" => "Importante informar o úlitmo nome"
																          ),
													"minimoCaracteres" => array("rule" => array('lengthBetween', 5, 30),
																		"message" => "O campo deverá possuir entre 5 á 30 caracteres",
																		)			          			          						 
											  )													  											  					  			  				  									  
	                      ); 	

 
 
	//Verifica se existe um email (evita a repetição de emails nos perfis)
	public function emailUnique($data)
	{
		
		App::import('Model', 'Usuario');  
		$Usuario = new Usuario;
		$count = $Usuario->find("count", array("conditions" => array("Perfil.email_contato" => $data["email_contato"],"Usuario.deleted IS NULL")));
		
		if($count > 0)
		{
			return FALSE;
		}else{
			return TRUE;
		}
	}

}


?>