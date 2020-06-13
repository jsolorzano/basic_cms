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
	
	if($(".eliminarSlide").length == 1){
		
		$("#columnasSlide").css({"height":"100px"});
		
	}
	
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


// Editar Item Slide
$(".editarSlide").click(function(){
	
	var idSlide = $(this).parent().attr("id");
	var rutaImagen = $(this).parent().children("img").attr("src");
	var rutaTitulo = $(this).parent().children("h1").html();
	var rutaDescripcion = $(this).parent().children("p").html();
	
	// Reemplazo del contenido actual por el contenido de edición
	$(this).parent().html('<img src="'+rutaImagen+'" class="img-thumbnail"><input type="text" class="form-control" id="enviarTitulo" placeholder="Título" value="'+rutaTitulo+'"><textarea row="5" class="form-control" id="enviarDescripcion" placeholder="Descripción">'+rutaDescripcion+'</textarea><button class="btn btn-info pull-right" id="guardar'+idSlide+'" style="margin:10px">Guardar</button>');
	
	$("#guardar"+idSlide).click(function(){
	
		// Tomamos el contenido del segmento a partir de la posición 4, eliminando el segmento "item"
		var enviarId = idSlide.slice(4);
		
		var enviarTitulo = $("#enviarTitulo").val();
		var enviarDescripcion = $("#enviarDescripcion").val();
		
		var actualizarSlide = new FormData();
		
		actualizarSlide.append("enviarId", enviarId);
		actualizarSlide.append("enviarTitulo", enviarTitulo);
		actualizarSlide.append("enviarDescripcion", enviarDescripcion);
		
		$.ajax({
			url: "views/ajax/gestorSlide.php",
			method: "POST",
			data: actualizarSlide,
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
			
				if(respuesta != "error"){	
					
					$("#guardar"+idSlide).parent().html('<span class="fa fa-pencil" style="background:blue"></span><img src="'+rutaImagen+'" style="float:left; margin-bottom:10px" width="80%"><h1>'+respuesta["titulo"]+'</h1><p>'+respuesta["descripcion"]+'</p>');
					
					swal({
						title: "¡Ok!",
						text: "¡Se han guardado los cambios correctamente!",
						type: "success",
						confirmButtonText: "Cerrar",
						closeOnConfirm: false
					},function(isConfirm){
						if(isConfirm){
							window.location = "slide";
						}
					});
					
				}else{
					
					$("#ordenarTextSlide").before('<div class="alert alert-warning alerta3 text-center">Ocurrio un error en la actualización.</div>');
					
				}
			
			}
		});
	
	});
	
});


// Ordenar Item Slide

var almacenarOrdenId = new Array();
var ordenItem = new Array();

$("#ordenarSlide").click(function(){
	
	$("#ordenarSlide").hide();  // Ocultar botón de ordenar
	$("#guardarSlide").show();  // Mostrar botón de guardar ordenamiento
	
	$("#columnasSlide").css({"cursor":"move"});  // Activar cursor de arrastre
	$("#columnasSlide span").hide();  // Ocultar los iconos de eliminación
	
	$("#columnasSlide").sortable({  // Hacer ordenable el conjunto de imágenes
		revert: true,  // Permitir el reposicionamiento en caso de no ubicar correctamente una imagen
		connectWith: ".bloqueSlide",  // Elementos que servirán de enlace (li)
		handle: ".handleImg",  // Elementos que servirán de agarre (img)}
		stop: function(event){
			
			for(var i=0; i < $("#columnasSlide li").length; i++){
				
				// Almacena el id de cada 'li' en el orden fijado
				almacenarOrdenId[i] = event.target.children[i].id;
				// Almacena el orden de cada id
				ordenItem[i] = i+1;
				
			}
			
		}
	});	
	
});

$("#guardarSlide").click(function(){
	
	$("#guardarSlide").hide();
	$("#ordenarSlide").show();
	
	for(var i=0; i < $("#columnasSlide li").length; i++){
		
		// Capturamos el id y el orden del slide de la iteración actual
		var actualizarOrden = new FormData();
		actualizarOrden.append("actualizarOrdenSlide", almacenarOrdenId[i]);
		actualizarOrden.append("actualizarOrdenItem", ordenItem[i]);
		
		// Guardamos el nuevo orden del slide de la iteración actual
		$.ajax({
			url:"views/ajax/gestorSlide.php",
			method: "POST",
			data: actualizarOrden,
			cache: false,
			contentType: false,
			processData: false,
			success: function(respuesta){
				
				// Cargamos los items con el nuevo orden en la sección de edición
				$("#textoSlide ul").html(respuesta);
				
				swal({
					title: "¡Ok!",
					text: "¡El orden se ha actualizado correctamente!",
					type: "success",
					confirmButtonText: "Cerrar",
					closeOnConfirm: false
				},function(isConfirm){
					if(isConfirm){
						window.location = "slide";
					}
				});
				
			}
		});
		
	}
	
});

