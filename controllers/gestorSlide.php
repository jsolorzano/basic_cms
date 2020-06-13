<?php

class Slide{

	// Listar imágenes en la vista
	// -----------------------------------------------------------------
	public function seleccionarSlideController(){
		
		$respuesta = SlideModel::seleccionarSlideModel("slide");
		
		foreach($respuesta as $key => $item){
			
			echo '<li>
					<img src="backend/'.substr($item['ruta'], 6).'">
					<div class="slideCaption">
						<h3>'.$item['titulo'].'</h3>
						<p>'.$item['descripcion'].'</p>
					</div>
				   </li>';
			
		}
		
	}
	
	
}
