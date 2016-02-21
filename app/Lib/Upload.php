<?php

/*
* Lib do Cakephp que auxilia nos uploads para a sua necessidade do seu projeto
* Suporte: CakePHP 2.x
*
* Feito Por: Thomas Kanzig, contato@thomaskanzig.com
*/

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class Upload{
	
	public $uploadDir = 'uploads';

	/*
	 * Metodo que realizará o upload do seu arquivo
	 * array $check, Ex: array("filename" => $this->request->data["Tarquivo"])
	 * String $uploadDir, determinado o diretorio especifico. Levando em quuestão que será dentro do seu app/webroot 
	 */
	public function processUpload($check=array(), $uploadDir = null) {
		// deal with uploaded file
		if (!empty($check['filename']['tmp_name'])) {

			if(@$uploadDir){
				$this->uploadDir = $uploadDir;	
			}

			// verifica se um arquivo de upload
			if (!is_uploaded_file($check['filename']['tmp_name'])) {
				return FALSE;
			}

			// constroi a url completa
			$filename = WWW_ROOT . $this->uploadDir . DS . Inflector::slug(pathinfo($check['filename']['name'], PATHINFO_FILENAME)).'.'.pathinfo($check['filename']['name'], PATHINFO_EXTENSION);


			// tenta mover o arquivo para o diretorio
			if (!move_uploaded_file($check['filename']['tmp_name'], $filename)) {
				return FALSE;
			} 
		}

		return TRUE;
	}

	/*
	* Método para criar uma pasta dentro do seu app/webroot
	*/
	public function createFolder($folder, $permission = 0755){

		$dir = new Folder(WWW_ROOT.$folder.DS, true, $permission);
		return $dir;
	}


}



?>