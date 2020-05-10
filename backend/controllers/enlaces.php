<?php

class Enlaces{
	
	// Carga de la página elegida desde el menú
	public function enlacesController(){
	
		if(isset($_GET['action'])){
		
			$enlaces = $_GET['action'];
		
		}else{
		
			$enlaces = "ingreso";
		
		}
		
		$respuesta = EnlacesModels::enlacesModel($enlaces);
		
		include $respuesta;
	
	}
	
}

