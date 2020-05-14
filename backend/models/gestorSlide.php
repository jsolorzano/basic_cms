<?php

require_once "conexion.php";

class GestorSlideModel{
	
	// Registrar ruta de la imagen
	// -----------------------------------------------------------------
	public function subirImagenSlideModel($datos, $tabla){
		
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (ruta) VALUES (:ruta)");
		
		$stmt->bindParam(":ruta", $datos, PDO::PARAM_STR);
		
		if($stmt->execute()){
			
			return "ok";
			
		}else{
			
			return "error";
			
		}
		
		$stmt->close();
	}

	// Seleccionar ruta de una imagen
	// -----------------------------------------------------------------
	public function mostrarImagenSlideModel($datos, $tabla){
		
		$stmt = Conexion::conectar()->prepare("SELECT id, ruta, titulo, descripcion FROM $tabla WHERE ruta = :ruta");
		
		$stmt -> bindParam(":ruta", $datos, PDO::PARAM_STR);
		
		$stmt -> execute();
			
		return $stmt -> fetch();
		
		$stmt -> close();
	}
	
	// Listar imágenes en la vista
	// -----------------------------------------------------------------
	public function mostrarImagenVistaModel($tabla){
		
		$stmt = Conexion::conectar()->prepare("SELECT id, ruta, titulo, descripcion FROM $tabla ORDER BY orden ASC");
		
		$stmt->execute();
			
		return $stmt->fetchAll();
		
		$stmt->close();
		
	}
	
	// Eliminar item del slide
	// -----------------------------------------------------------------
	public function eliminarSlideModel($datos, $tabla){
		
		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
		
		$stmt -> bindParam(":id", $datos["idSlide"], PDO::PARAM_INT);
		
		if($stmt->execute()){
			
			return "ok";
			
		}else{
			
			return "error";
			
		}
		
		$stmt->close();
		
	}
	
}

