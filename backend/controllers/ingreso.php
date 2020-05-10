<?php

class Ingreso{
	
	// Controla el ingreso de los usuarios al sistema back-office
	public function ingresoController(){
		
		if(isset($_POST['usuarioIngreso'])){
		
			if(preg_match('/^[a-zA-Z0-9]+$/', $_POST['usuarioIngreso']) && preg_match('/^[a-zA-Z0-9]+$/', $_POST['passwordIngreso'])){
				
				//~ $encriptar = crypt($_POST['passwordIngreso'], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
				
				$datosController = array(
					"user" => $_POST['usuarioIngreso'],
					"password" => $_POST['passwordIngreso']
				);
				
				$respuesta = IngresoModels::ingresoModel($datosController, 'users');
				
				$intentos = $respuesta["intentos"];
				$usuarioActual = $_POST['usuarioIngreso'];
				$maximoIntentos = 3;
				
				if($intentos < $maximoIntentos){
					
					if($respuesta['user'] == $_POST['usuarioIngreso'] && $respuesta['password'] == $_POST['passwordIngreso']){
						
						$intentos = 0;
						
						$datosController = array(
							"usuarioActual"=>$usuarioActual,
							"actualizarIntentos"=>$intentos
						);
						
						$respuestaActualizarIntentos = IngresoModels::intentosModel($datosController, 'users');
						
						// Iniciamos sesión y creamos una varible de sesión
						session_start();
						
						$_SESSION["validar"] = true;
						$_SESSION["usuario"] = $respuesta['user'];
						
						header("location:inicio");
						
					}else{
						
						$intentos++;
						
						$datosController = array(
							"usuarioActual"=>$usuarioActual,
							"actualizarIntentos"=>$intentos
						);
						
						$respuestaActualizarIntentos = IngresoModels::intentosModel($datosController, 'users');
						
						echo "<div class='alert alert-danger'>Error al ingresar</div>";
					
					}
					
				}else{
					
					$intentos = 0;
						
					$datosController = array(
						"usuarioActual"=>$usuarioActual,
						"actualizarIntentos"=>$intentos
					);
					
					$respuestaActualizarIntentos = IngresoModels::intentosModel($datosController, 'users');
					
					echo "<div class='alert alert-danger'>Ha fallado 3 veces, demuestre que no es un robot</div>";
					
				}
			}
			
		}
	
	}
	
}

