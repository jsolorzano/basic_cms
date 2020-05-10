<?php

require_once "conexion.php";

class IngresoModels{
	
	// Controla el ingreso de los usuarios al sistema back-office
	public function ingresoModel($datosModel, $tabla){
	
		$stmt = Conexion::conectar()->prepare("SELECT user, password, intentos FROM $tabla WHERE user = :user");
		
		$stmt -> bindParam(":user", $datosModel['user'], PDO::PARAM_STR);
		
		$stmt -> execute();
		
		return $stmt -> fetch();
		
		$stmt -> close();
	
	}
	
	// Controla el ingreso de los usuarios al sistema back-office
	public function intentosModel($datosModel, $tabla){
		
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET intentos = :intentos WHERE user = :user");
		
		$stmt -> bindParam(":intentos", $datosModel['actualizarIntentos'], PDO::PARAM_INT);
		$stmt -> bindParam(":user", $datosModel['usuarioActual'], PDO::PARAM_STR);
		
		if($stmt->execute()){
			
			return "ok";
			
		}else{
		
			return "error";
		
		}
		
		$stmt->close();  // Cerrar conexión
		
	}
	
}

