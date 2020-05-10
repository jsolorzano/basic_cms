<?php

class EnlacesModels{
	
	// Carga de la página elegida desde el menú
	public function enlacesModel($enlaces){
	
		$permitidos = array(
			'inicio',
			'slide',
			'articulos',
			'galeria',
			'videos',
			'suscriptores',
			'mensajes',
			'perfil',
			'salir'
		);  // Lista blanca
		
		if(in_array($enlaces, $permitidos)){
		
			$module = "views/modules/".$enlaces.".php";
		
		}else{
		
			$module = "views/modules/ingreso.php";
		
		}
		
		return $module;
	
	}
	
}

