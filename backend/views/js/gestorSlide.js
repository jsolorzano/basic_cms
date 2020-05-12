// Redimensionar altura del bloque 'columnasSlide' (Área de arrastre de imágenes) cuando esté vacío

if($("#columnasSlide").html() == 0){
	
	$("#columnasSlide").css({"height":"100px"});
	
}else{

	$("#columnasSlide").css({"height":"auto"});

}


// Impedir acción por defecto al arrastrar una imagen al bloque destinado para ello

$("#columnasSlide").on("dragover", function(e){
	
	e.preventDefault();
	e.stopPropagation();
	
	// Cambiar fondo sobre el área al poner la imagen encima
	$("#columnasSlide").css({"background":"#e5e5e5"});
	
});


// Acciones luego de arrastrar y soltar una imagen al bloque destinado para ello

$("#columnasSlide").on("drop", function(e){
	
	e.preventDefault();
	e.stopPropagation();
	
	// Cambiar fondo sobre el área al poner la imagen encima
	$("#columnasSlide").css({"background":"#ffffff"});
	
	// Captura de imagen puesta encima
	var archivo = e.originalEvent.dataTransfer.files;
	var imagen = archivo[0];
	
	// Obtención de datos (tamaño) de la imagen
	var imagenSize = imagen.size;
	console.log('imagenSize', imagenSize);
	
	// Alertar cuando la imagen pese más de lo permitido
	if(Number(imagenSize) > 2000000){
		
		$("#columnasSlide").before('<div class="alert alert-warning alerta text-center">El archivo excede el peso permitido, 200kb.</div>');
		
	}else{
		
		$(".alerta").remove();
		
	}
	
	// Obtención de datos (tipo) de la imagen
	var imagenType = imagen.type;
	console.log('imagenType', imagenType);
	
	// Alertar cuando la imagen tenga un formato no permitido
	if(imagenType == "image/jpeg" || imagenType == "image/png"){
		
		$(".alerta2").remove();
		
	}else{
		
		$("#columnasSlide").before('<div class="alert alert-warning alerta2 text-center">El archivo debe tener formato JPG o PNG.</div>');
		
	}
	
	// Subir imagen al servidor
	if(Number(imagenSize) < 2000000 && (imagenType == "image/jpeg" || imagenType == "image/png")){
		
		var datos = new FormData();
		
		datos.append("imagen", imagen);
		
		$.ajax({
			url: "views/ajax/gestorSlide.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){
				
				$("#columnasSlide").before("<img src='views/images/status.gif' id='status'>");
				
			},
			success: function(respuesta){
				
				console.log('respuesta', respuesta);
			
				if(respuesta == 0){
					
					$("#columnasSlide").before('<div class="alert alert-warning alerta3 text-center">La imagen es inferior a 1600px * 600px.</div>');
					
				}else{
					
					$("#columnasSlide").append('<li class="bloqueSlide"><span class="fa fa-times"></span><img src="" class="handleImg"></li>');
					
				}
			
			}
		});
		
	}
	
});
