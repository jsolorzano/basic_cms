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
			dataType: "json",
			beforeSend: function(){
				
				$("#columnasSlide").before("<img src='views/images/status.gif' id='status'>");
				
			},
			success: function(respuesta){
				
				console.log('respuesta', respuesta);
				
				$("#status").remove();
			
				if(respuesta == 0){
					
					$("#columnasSlide").before('<div class="alert alert-warning alerta3 text-center">La imagen es inferior a 1600px * 600px.</div>');
					
				}else{
					
					$("#columnasSlide").css({"height":"auto"});
					
					$("#columnasSlide").append('<li id="'+respuesta["id"]+'" class="bloqueSlide"><span class="fa fa-times eliminarSlide"></span><img src="'+respuesta["ruta"].slice(6)+'" class="handleImg"></li>');
					
					$("#ordenarTextSlide").append('<li id="item'+respuesta["id"]+'"><span class="fa fa-pencil" style="background:blue"></span><img src="'+respuesta["ruta"].slice(6)+'" style="float:left; margin-bottom:10px" width="80%"><h1>'+respuesta["titulo"]+'</h1><p>'+respuesta["descripcion"]+'</p></li>');
					
					// Esto se hace para recargar los registros de la base de datos
					// con todos los atributos y pueda funcionar la acción de eliminar.
					// Lo hacemos usando la librería SweetAlert para que la recarga no
					// se note tanto para el usuario.
					//~ window.location.reload();
					swal({
						title: "¡Ok!",
						text: "¡La imagen se subió correctamente!",
						type: "success",
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					},function(isConfirm){
						if(isConfirm){
							window.location = "slide";
						}
					});
					
				}
			
			}
		});
		
	}
	
});


// Eliminar Item Slide
$(".eliminarSlide").click(function(){
	
	var idSlide = $(this).parent().attr("id");
	var rutaSlide = $(this).attr("ruta");
	
	console.log("idSlide", idSlide);
	
	$(this).parent().remove();  // Remover visualmente en la caja de carga
	
	$("#item"+idSlide).remove();  // Remover visualmente en la caja de edición
	
	var borrarItem = new FormData();
	
	borrarItem.append("idSlide", idSlide);
	borrarItem.append("rutaSlide", rutaSlide);
	
	$.ajax({
		url: "views/ajax/gestorSlide.php",
		method: "POST",
		data: borrarItem,
		cache: false,
		contentType: false,
		processData: false,
		//~ dataType: "json",
		success: function(respuesta){}
	});
	
});
