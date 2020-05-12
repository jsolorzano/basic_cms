<?php

class GestorSlide{

	public function mostrarImagenController($datos){
		
		// La función 'getimagesize()' de php permite capturar la dimensiones de la imagen
		list($ancho, $alto) = getimagesize($datos["imagenTemporal"]);
		
		//~ echo $ancho;
		//~ echo $alto;
		
		if((int)$ancho < 1600 || (int)$alto < 600){
			
			echo 0;
		
		}else{
			
			$aleatorio = mt_rand(100, 999);
		
			$ruta = "../../views/images/slide/slide".$aleatorio.".jpg";  // Ruta física para guardar la imagen
			
			// Crear la imagen con php a partir de la que llega desde ajax
			$origen = imagecreatefromjpeg($datos["imagenTemporal"]);
			
			// Enviar imagen a la ruta especificada
			imagejpeg($origen, $ruta);
			
			// Tanto imagecreatefromjpeg() como imagejpeg() son funciones nativas de php
			
			GestorSlideModel::subirImagenSlideModel($ruta, "slide");
			
			$respuesta = GestorSlideModel::mostrarImagenSlideModel($ruta, "slide");
			
			$enviarDatos = array("ruta"=>$respuesta['ruta']);
			
			echo json_encode($enviarDatos);
			
		}
		
	}
	
}
