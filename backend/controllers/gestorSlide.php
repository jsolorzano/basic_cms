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
			
			// imagecrop() - Recorta una imagen usando las coordenadas, el tamaño, 
			// x, y, ancho y alto dados
			$destino = imagecrop($origen, ["x"=>0, "y"=>0, "width"=>1600, "height"=>600]);
			// En este caso indicamos que el recorte comience desde la esquina superior 
			// izquierda y se tome un rectángulo de 1600px de ancho y 600px de alto
			
			// Enviar imagen a la ruta especificada
			imagejpeg($destino, $ruta);
			
			// Tanto imagecreatefromjpeg() como imagejpeg() son funciones de la extensión 'gd' de php
			
			// Registramos en base de datos la ruta de la imagen
			GestorSlideModel::subirImagenSlideModel($ruta, "slide");
			
			// Consultamos la ruta registrada y armamos un json con ella
			$respuesta = GestorSlideModel::mostrarImagenSlideModel($ruta, "slide");
			
			$enviarDatos = array(
				"id"=>$respuesta['id'],
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
			
			echo '<li id="'.$item['id'].'" class="bloqueSlide"><span class="fa fa-times eliminarSlide" ruta="'.$item['ruta'].'"></span><img src="'.substr($item['ruta'], 6).'" class="handleImg"></li>';
			
		}
		
	}
	
	// Listar imágenes en el editor
	// -----------------------------------------------------------------
	public function editorSlideController(){
		
		$respuesta = GestorSlideModel::mostrarImagenVistaModel("slide");
		
		foreach($respuesta as $key => $item){
			
			echo '<li id="item'.$item["id"].'">
					<span class="fa fa-pencil editarSlide" style="background:blue"></span>
					<img src="'.substr($item['ruta'], 6).'" style="float:left; margin-bottom:10px" width="80%">
					<h1>'.$item['titulo'].'</h1>
					<p>'.$item['descripcion'].'</p>
				</li>';
			
		}
		
	}
	
	// Eliminar item del slide
	// -----------------------------------------------------------------
	public function eliminarSlideController($datos){
		
		// Eliminamos en base de datos la ruta perteneciente al id del slide indicado
		$respuesta = GestorSlideModel::eliminarSlideModel($datos, "slide");
		
		// Eliminamos el archivo físicamente
		unlink($datos["rutaSlide"]);
		
		echo $respuesta;
		
	}
	
	// Actualizar item del slide
	// -----------------------------------------------------------------
	public function actualizarSlideController($datos){
		
		// Actualizamos en base de datos el título y la descripción perteneciente al id del slide indicado
		$respuesta = GestorSlideModel::actualizarSlideModel($datos, "slide");
		
		if($respuesta == "ok"){
			
			$respuesta2 = GestorSlideModel::seleccionarActualizacionSlideModel($datos, "slide");
		
			echo json_encode($respuesta2);
			
		}else{
			
			//~ echo json_encode(array("respuesta" => $respuesta));
			echo $respuesta;
			
		}
		
	}
	
	// Actualizar orden slides
	// -----------------------------------------------------------------
	public function actualizarOrdenController($datos){
		
		// Actualizamos en base de datos el título y la descripción perteneciente al id del slide indicado
		GestorSlideModel::actualizarOrdenModel($datos, "slide");
		
		$respuesta = GestorSlideModel::seleccionarOrdenModel("slide");
		
		foreach($respuesta as $row => $item){
			
			echo '<li id="item'.$item["id"].'">
					<span class="fa fa-pencil editarSlide" style="background:blue"></span>
					<img src="'.substr($item['ruta'], 6).'" style="float:left; margin-bottom:10px" width="80%">
					<h1>'.$item['titulo'].'</h1>
					<p>'.$item['descripcion'].'</p>
				</li>';
			
		}
		
	}
	
	
}
