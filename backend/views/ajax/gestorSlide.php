<?php
require_once "../../models/gestorSlide.php";

require_once "../../controllers/gestorSlide.php";

// Clase y métodos
// -----------------------------------------------------------------
class Ajax{
	
	// Subir la imagen del slide
	// -----------------------------------------------------------------
	public $nombreImagen;
	public $imagenTemporal;
	
	public function gestorSlideAjax(){
		
		$datos = array(
			"nombreImagen"=>$this->nombreImagen,
			"imagenTemporal"=>$this->imagenTemporal
		);
		
		$respuesta = GestorSlide::mostrarImagenController($datos);
		
		echo $respuesta;
		
	}

}

// Objetos
// -----------------------------------------------------------------
$a = new Ajax();
$a -> nombreImagen = $_FILES["imagen"]["name"];
$a -> imagenTemporal = $_FILES["imagen"]["tmp_name"];
$a -> gestorSlideAjax();
