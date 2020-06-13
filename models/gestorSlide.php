<?php

require_once "backend/models/conexion.php";

class SlideModel{
	
	// Seleccionar orden de las imágenes
	// -----------------------------------------------------------------
	public function seleccionarSlideModel($tabla){
		
		$stmt = Conexion::conectar()->prepare("SELECT id, ruta, titulo, descripcion FROM $tabla ORDER BY orden ASC");
		
		$stmt->execute();
			
		return $stmt->fetchAll();
		
		$stmt->close();
		
	}
	
}

