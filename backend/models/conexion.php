<?php

class Conexion{
	
	// Conecta con la base de datos
	public function conectar(){
	
		$link = new PDO("mysql:host=localhost;dbname=cms", "root", "123456");
		
		return $link;
	
	}
	
}

