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
	
	// Eliminar item slide
	// -----------------------------------------------------------------
	public $idSlide;
	public $rutaSlide;
	
	public function eliminarSlideAjax(){
		
		$datos = array("idSlide"=>$this->idSlide, "rutaSlide"=>$this->rutaSlide);
		
		$respuesta = GestorSlide::eliminarSlideController($datos);
		
		echo $respuesta;
		
	}

}

// Objetos
// -----------------------------------------------------------------
if(isset($_FILES["imagen"]["name"])){
	$a = new Ajax();
	$a -> nombreImagen = $_FILES["imagen"]["name"];
	$a -> imagenTemporal = $_FILES["imagen"]["tmp_name"];
	$a -> gestorSlideAjax();
}

if(isset($_POST["idSlide"])){
	$a = new Ajax();
	$a -> idSlide = $_POST["idSlide"];
	$a -> rutaSlide = $_POST["rutaSlide"];
	$a -> eliminarSlideAjax();
}
