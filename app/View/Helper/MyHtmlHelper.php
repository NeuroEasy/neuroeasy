<?php

App::uses('HtmlHelper', 'View/Helper');

/*
* This helper class envolved others functions for my html page
* Thomas Kanzig :: thomas.kanzig@gmail.com
*/



class MyHtmlHelper extends HtmlHelper{
	

	public function isActive($controller, $action = null){

		$class = "";

		if(!empty($controller) && empty($action)){
		
			if($this->params["controller"] == $controller){
				$class = "active";
			}

		}else if(!empty($controller) && !empty($action))
		{
			if($this->params["controller"] == $controller && $this->params["action"] == $action){
				$class = "active";
			}
		}

		return $class;
	}	

}



?>