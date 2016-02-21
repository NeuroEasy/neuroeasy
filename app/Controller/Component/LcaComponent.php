<?php

/*
* LCA Componente - LISTA DE CONTROLE DE ACESSO
* autor: Thomas Kanzig - thomas.kanzig@gmail.com
*
* 1 - Administrativo  (Tudo)
* 2 - Operador        (Somente alguns)

*
*/


class LcaComponent extends Component{
	
	function getPermissoes($grupo_id = null, $slug_grupo = null)
	{
		if(!empty($grupo_id))
		{
			
			App::import('Model', 'Lca');  
			$lca = new Lca;

			$where = array();
			$where[] = "Controller.id = Actions.controller_id";
			$where[] = "Actions.id = PermissoesRels.action_id";
			$where[] = "PermissoesRels.grupo_id = ".$grupo_id;

			$from = array();
			$from[] = "lca_controllers Controller";
			$from[] = "lca_controller_actions Actions";
			$from[] = "lca_permissoes_rels PermissoesRels";


			if(!empty($slug_grupo))
			{
				$where[] = "Controller.grupo_id = ControllerGrupo.id";
				$where[] = "ControllerGrupo.slug = '".$slug_grupo."'";
				$from[] = "lca_controller_grupos ControllerGrupo";
			}

          $sql = "SELECT 
				 Controller.descricao,
				 Actions.descricao 
				 FROM 
				 ".implode(",",$from)."
				 WHERE 
				 ".implode(" AND ",$where)." 
				 ORDER BY Controller.descricao ASC";
									
			$registros = $lca->query($sql); 
			
			foreach($registros as $registro)
			{
				$permissoes[$registro["Controller"]["descricao"]][$registro["Actions"]["descricao"]] = TRUE;
			}
		
		}
		else
		{
			$permissoes = NULL;
		}
		
		return $permissoes;
	}
	
	function semaforo($permissoes,$controller,$action)
	{
		if(!empty($permissoes[$controller][$action]))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}		
	}	
}

?>