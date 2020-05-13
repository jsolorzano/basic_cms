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
			
			// Tanto imagecreatefromjpeg() como imagejpeg() son funciones de la extensión 'gd' de php
			
			// Registramos en base de datos la ruta de la imagen
			GestorSlideModel::subirImagenSlideModel($ruta, "slide");
			
			// Consultamos la ruta registrada y armamos un json con ella
			$respuesta = GestorSlideModel::mostrarImagenSlideModel($ruta, "slide");
			
			$enviarDatos = array(
				"ruta"=>$respuesta['ruta'],
				"titulo"=>$respuesta['titulo'],
				"descripcion"=>$respuesta['descripcion']
			);
			
			echo json_encode($enviarDatos);
			
		}
		
	}
	
	// Listar imágenes en la vista
	// -----------------------------------------------------------------
	public function mostrarImagenVistaController(){
		
		$respuesta = GestorSlideModel::mostrarImagenVistaModel("slide");
		
		foreach($respuesta as $key => $item){
			
			echo '<li class="bloqueSlide"><span class="fa fa-times"></span><img src="'.substr($item['ruta'], 6).'" class="handleImg"></li>';
			
		}
		
	}
	
	// Listar imágenes en el editor
	// -----------------------------------------------------------------
	public function editorSlideController(){
		
		$respuesta = GestorSlideModel::mostrarImagenVistaModel("slide");
		
		foreach($respuesta as $key => $item){
			
			echo '<li>
					<span class="fa fa-pencil" style="background:blue"></span>
					<img src="'.substr($item['ruta'], 6).'" style="float:left; margin-bottom:10px" width="80%">
					<h1>'.$item['titulo'].'</h1>
					<p>'.$item['descripcion'].'</p>
				</li>';
			
		}
		
	}
	
	
}
