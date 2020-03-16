/////////////////////AQUI
let mensaje_preview_default=base_url+'assets/images/default.png';
let default_image='assets/images/canales/default.png';
let mensaje_preview_default_foto = base_url+'assets/images/canales/contenido_galeria/default.png';
let default_image_foto='assets/images/canales/contenido_galeria/default.png';
let cropeo_foto = null;
let recortofoto = false;
let cambiofoto = false;
let imgFotoActual="";
let modalFlag = 0;
var canalActual = 0;
let arrayEliminarAdjuntos = new Array();
$(".vista-contenido").hide()
var cont_img_borr = 0;



let form_canal={
	id:$('#formulario-canal'),
	id_canal:$('#form-canal-id'),
	nombre:$('#form-canal-nombre'),
	foto:$('#form-canal-foto'),
	descripcion_corta:$('#form-canal-descripcion-corta'),
	descripcion_larga:$('#form-canal-descripcion-larga'),
}


let form_contenido={
	id:$('#formulario-contenido'),
	idcontenido:$('#form-contenido-id'),
	titulo:$('#form-contenido-titulo'),
	foto:$('#form-contenido-foto'),
	escena:$('#form-contenido-escena'),
	descripcion:$('#form-contenido-descripcion'),
	contraseña:$('#form-contenido-contraseña'),
	visibilidad:$('#form-contenido-visibilidad'),
	categoria:$('#form-contenido-categoria'),
	
}



$(function(){
	




	let tabla_canales={

		id:'#tabla-canales',
		columnas:[
				{data:'logo'},
				{data:'nombre'},
				{data:'descripcion_corta'},
				{data:'descripcion_larga'},
				{data:'contenido'},
				{data:'opciones'},
				],
		lenguaje: [1, 2, 3, 4, 5],
     	sinorden: [0, 4, 5],
    	invisible: [],
    	data: {tipo: 2},
		url: base_url + "canales/getDataCanales",
		
	}


	let tabla_contenidos={

		id:'#tabla-contenidos',
		columnas:[
				{data:'nombre'},
				{data:'descripcion'},
				{data:'escena'},
				{data:'clave'},
				{data:'visibilidad'},
				{data:'opciones'},
				],
		lenguaje: [1, 2, 3, 4, 5],
     	sinorden: [5],
    	invisible: [],
    	data: {canalActual: canalActual},
		url: base_url + "canales/getDataContenidos",
		
	}


	

	configurarDescargaExcel();
	cargarTabla(tabla_canales);
	//cargarTabla(tabla_contenidos);

	OnChangeImagen('form-canal-foto', 'imagen-preview2');
	resetearImagen();
	eventoInputClaveIcon('#form-contenido-contraseña');
	limpiaModal();

	/*Foto Contenido*/
	OnChangeImagenContenido('form-contenido-foto', 'imagen-preview-foto');
	resetearImagenContenido();
	limpiaModal();
		
	



	$("#btn-canal-agregar").on('click', function(){
		//console.log("agregar");
		modalFlag = 1;
		limpiaModal();
		$("#form-notificacion-boton-imagen-us-cargar").show();
		cargarCanales(1);
		//cargarSelectEmpresas(1);
	});


	// $("#switchClave").on('click', function(){
	// 	if ($("#switch-btn").attr("aria-pressed") == 'false') {
	// 		$("#form-contenido-contraseña").val("")
	// 	}
		
	// });

	
	

	$("#switchClave").on('click', function(){
		if ($("#switchClave").attr('aria-pressed')==="false") {
			// console.log("no borrar");
			

		} else {
			//console.log(" borrar");
			$("#form-contenido-contraseña").val("");
		}
	});


	$( "#form-contenido-contraseña" ).on('input',function() {

		if($( "#form-contenido-contraseña" ).val()){
			//console.log("tiene algo");
			switchClaveOn();
			$("#switchClave").prop('disabled', false);

		}else{
			//console.log("no tiene nada");
			switchClaveOff();
			$("#switchClave").prop('disabled', true);

		}
		
	
	
	});
	// agregar clase para  que se apague el boton

	$('#switchClave').on('click', function (){

		//console.log("switch cambio");
		if ($("#switchClave").attr("aria-pressed") == 'true') {
		
			$("#switchClave").prop('disabled', false);
		} else {

			$("#switchClave").prop('disabled', true);
		}

		
   });



		
	$("#btn-contenido-agregar").on('click', function(){
		modalFlag = 1;
		limpiaModal();
		cargarContenido(1);
		$("#form-notificacion-boton-imagen-us-cargar-foto").show();

		//cargarSelectEmpresas(1);
	});


	form_canal.id.submit(function (e){

		e.preventDefault();

		if (cambiofoto && !recortofoto) {
            _toastr('warning', 'Recorte la imagen para seguir con la operación', true);
            return false;
		}
		
		//console.log('editarrrrrr')
		
		let foto=form_canal.foto.val();
		let id_canal=form_canal.id_canal.val();
		let mensaje="";

		let nombre = form_canal.nombre.val();
		let descripcion_corta = form_canal.descripcion_corta.val();
		let descripcion_larga = form_canal.descripcion_larga.val();

		//console.log("corta:",descripcion_corta," larga:",descripcion_larga)

		//validaciones
		if (nombre.trim().length==0) {
			mensaje+='Falta llenar nombre <br> ';
		}

		if (descripcion_corta.trim().length==0) {
			mensaje+='Falta llenar descripción corta <br> ';
		}

		if (descripcion_larga.trim().length==0) {
			mensaje+='Falta llenar descripción larga <br> ';
		}

	


			if (modalFlag == 1 ){
				// if(clave.trim().length==0){
				// 	mensaje+='Falta llenar clave <br> ';
					
				// }

				

				
			}
			
            if(mensaje.length!=0){
                Swal.fire('Error de validación', mensaje, 'error');
                return;
				}

            //mandar datos al backend
            let formData = new FormData();
            // formData.append('rut', rut);
            // formData.append('nombre', nombre);
            // formData.append('apaterno', apaterno);
            // formData.append('amaterno', amaterno);
			// formData.append('email', email);
			// formData.append('empresa', empresa);
			// formData.append('tipo', tipo);
            //formData.append('foto',  foto);
			formData.append('foto', $("#imagen-preview2").prop('src'));
            // formData.append('clave', clave);
			//modalFlag estados:  2 = Modal adición , 1 = Modal Agregar

			formData.append('nombre', nombre);
			formData.append('descripcion_corta', descripcion_corta);
			formData.append('descripcion_larga', descripcion_larga);
		
			if (modalFlag == 2) { formData.append('id_canal', id_canal);}
			formData.append(token_name, token_hash);

		


            getAjaxFormData(formData, base_url+'canales/guardarCanal').then(function(result){
				//console.log("1",result);
                result=JSON.parse(result);
                if(result.proceso==1){
					$("#modal-canal").modal("hide");
					$("#modal-contenido").modal("hide");
                    recortofoto = false;
                    cambiofoto = false;
                    resetInputClaveIcon('#form-contenido-contraseña');
               

					form_canal.nombre.val('');
					form_canal.descripcion_corta.val('');
					form_canal.descripcion_larga.val('');
					

				
					//console.log(" old: ",oldTipo);
					//console.log(" new: ",tipo);




					 cargarTabla(tabla_canales);
					 //cargarTabla(tabla_contenidos);
					 document.getElementById("canal-actual").innerText = nombre; 

                    if(modalFlag == 2){
                        
                        _toastr("success", "El canal fue actualizado exitosamente", true);
                    }
                    if(modalFlag == 1){
                        _toastr("success", "El canal se agregó exitosamente", true);
                    }
                }else if(modalFlag == 2){
                    if(result.errores.length==0){
                        Swal.fire("Error", "Ocurrio un error en el proceso, intente nuevamente", 'error');
                    }else{
                        let mensaje="";
                        result.errores.map(function(error){
                            mensaje+=error+"\n";
                        });
                        Swal.fire("Error de validación", mensaje, 'error');
                    }
                }else if(modalFlag == 1){
                    //hubo algun error
                    if(result.errores.length!=0){
                            for(let c=0;c<result.errores.length;c++){
                                mensaje+=result.errores[c]+'\n';
                            }
                    Swal.fire('Error de validación', mensaje, 'error');
                    return;
                    }
                }
            });
    
		});
		


		form_contenido.id.submit(function (e){

			e.preventDefault();
	
			if (cambiofoto && !recortofoto) {
				_toastr('warning', 'Recorte la imagen para seguir con la operación', true);
				return false;
			}
	
			

			let idcontenido=form_contenido.idcontenido.val();

			let mensaje="";
			let categoria=form_contenido.categoria.selectpicker('val');
		
			let nombre = form_contenido.titulo.val();
			let escena = form_contenido.escena.val();
			let descripcion = form_contenido.descripcion.val();
			let foto = form_contenido.foto.val();
			let clave = form_contenido.contraseña.val();
			let visibilidad = '1';
			var adjuntos_previos = $('#my-Archivos .dz-image-preview-custom' ).length;
			var processing = $('#my-Archivos .dz-processing' ).length;
			   
			   
			if ($("#switch-btn").attr("aria-pressed") == 'true') {
				
				visibilidad = '1';
			} else {
				
				visibilidad = '0';
			}
			



		
			// console.log("categoriaaaaaaaaaa: ",categoria);
	
			
			//validaciones
			if (nombre.trim().length==0) {
				mensaje+='Falta llenar título <br> ';
			}
	
			if (escena.trim().length==0) {
				mensaje+='Falta llenar escena <br> ';
			}
			if (descripcion.trim().length==0) {
				mensaje+='Falta llenar descripción <br> ';
			}
			if (categoria.length===0) {
				mensaje+='Falta llenar categoria <br> ';
			} 

			
			// adjuntos previos = imágenes previas
			// processing = imágenes nuevas
			if (myDropzone.getAcceptedFiles().length < 3 && (adjuntos_previos+processing) < 3 ) {
				_toastr("error", "Es necesario adjuntar por lo menos 3 imágenes", true);
				return false;
			   }

			if( (adjuntos_previos + processing) > 10){
				_toastr("error", "Está permitido adjuntar un máximo de 10 imágenes", true);
				return false;

			}
			   
		
			
					
			   
		//	console.log("total", (adjuntos_previos + processing));


			if(modalFlag == 1){
				
				var lastpreview = $(".dz-image-preview");
				$.each(lastpreview,function(key,preview){
					var uuid = $(preview).data("identificador");
					// console.log("preview => ",key);
					if(uuid){
						$.each(myDropzone.getAcceptedFiles(),function(key2,file){
							// console.log("file => ",key2);
							if(file.upload.uuid==uuid){
								// console.log("file orden agregado  => ",key);
								file.orden = key+1;
							}
						});
					}
				});

			   
			}
				

				if(mensaje.length!=0){
					Swal.fire('Error de validación', mensaje, 'error');
					return;
					}
	
				//mandar datos al backend
				let formData = new FormData();

				formData.append('foto', $("#imagen-preview-foto").prop('src'));

				formData.append('descripcion', descripcion);
				formData.append('nombre', nombre);
				formData.append('escena', escena);
				formData.append('clave', clave);
				formData.append('canalActual',canalActual);
				formData.append('visibilidad',visibilidad);
				formData.append('categoria',categoria);
				var array_files = $(id_dropzone)[0].dropzone.getAcceptedFiles();
				formData.append('adjuntos_eliminar',JSON.stringify(arrayEliminarAdjuntos));

				
				if(modalFlag == 1){
					
						if (array_files.length > 0) {
							var obj = [];
							
							for (var i = 0; i < array_files.length; i++) {
				
								
		
								var a = {
									name: array_files[i].name,
									orden: array_files[i].orden,
									size: array_files[i].size,
									//idgaleria: array_files[i].id,
									uuid: array_files[i].upload.uuid,
									image: array_files[i].dataURL
									
								};
				
							/*	if (array_files[i].id) {
									a = $.extend({idgaleria: array_files[i].id}, a);
								}*/
				

								obj.push(a);
				
							}

							formData.append('archivo', JSON.stringify(obj));
				//            console.log(obj);
						}	
						
				}

					

				if(modalFlag == 2){

					
						formData.append('idcontenido', idcontenido);
						var lastpreview = $(".dz-image-preview-custom");
						//var actpreview = $(".dz-processing").length;

						var listaOrden = [];



						if(lastpreview.length) {  
							//	$.each(lastpreview,function(key,preview){

									let id_galeria = $(this).data('id');
									
									
									if (array_files.length > 0) {
										var obj = [];

										for (var i = 0; i < array_files.length; i++) {
											
											
											
											var a = {

												name: array_files[i].name,
												orden: (adjuntos_previos+1),	
												size: array_files[i].size,
												uuid: array_files[i].upload.uuid,
												//id: id_galeria,
												image: array_files[i].dataURL
											};

											obj.push(a);
										}
										
										formData.append('archivo', JSON.stringify(obj));
							//            console.log(obj);
									}	
					

									
							//	});
							}


						//Si la galería está vacía (no existe la clase image-preview-custom), se inserta nuevamente
						else {
						
									e.preventDefault();
									var lastpreview = $(".dz-image-preview");
									$.each(lastpreview,function(key,preview){
										var uuid = $(preview).data("identificador");
										// console.log("preview => ",key);
										if(uuid){
											$.each(myDropzone.getAcceptedFiles(),function(key2,file){
												// console.log("file => ",key2);
												if(file.upload.uuid==uuid){
													// console.log("file orden agregado  => ",key);
													file.orden = key+1;
												}
											});
										}
									});

								var array_files = $(id_dropzone)[0].dropzone.getAcceptedFiles();

								if (array_files.length > 0) {
									var obj = [];
									
									for (var i = 0; i < array_files.length; i++) {
						
										
				
										var a = {
											name: array_files[i].name,
											orden: array_files[i].orden,
											size: array_files[i].size,
											//idgaleria: array_files[i].id,
											uuid: array_files[i].upload.uuid,
											image: array_files[i].dataURL
											
										};
						
									/*	if (array_files[i].id) {
											a = $.extend({idgaleria: array_files[i].id}, a);
										}*/
						

										obj.push(a);
						
									}

									formData.append('archivo', JSON.stringify(obj));
						//            console.log(obj);
								}	
						}






						var lastpreviewNuevo = $(".dz-image-preview");


						$.each(lastpreviewNuevo,function(key,preview){
							
							let id_galeria = $(this).data('id');
							
							if(id_galeria){
								listaOrden.push(id_galeria);
							}else{
								if($(this).data('identificador')){
									listaOrden.push($(this).data('identificador'));
								}
							}


							
						});

				}

				formData.append('identificador', JSON.stringify(listaOrden));
				formData.append(token_name, token_hash);
				getAjaxFormData(formData, base_url+'canales/guardarContenido').then(function(result){
					result=JSON.parse(result);
					if(result.proceso==1){
						$("#modal-canal").modal("hide");
						$("#modal-contenido").modal("hide");
					
				
						recortofoto = false;
						cambiofoto = false;
						form_contenido.descripcion.val('');
						form_contenido.titulo.val('');
						form_contenido.contraseña.val('');
						form_contenido.escena.val('');
						form_contenido.categoria.val('').selectpicker('refresh');
						form_contenido.visibilidad.val('0');
						cargarTabla(tabla_contenidos);
					
						if(modalFlag == 2){
							
							_toastr("success", "El contenido fue actualizado exitosamente", true);
						}
						if(modalFlag == 1){
							_toastr("success", "El contenido se agregó exitosamente", true);
						}
					}else if(modalFlag == 2){
						if(result.errores.length==0){
							Swal.fire("Error", "Ocurrio un error en el proceso, intente nuevamente", 'error');
						}else{
							let mensaje="";
							result.errores.map(function(error){
								mensaje+=error+"\n";
							});
							Swal.fire("Error de validación", mensaje, 'error');
						}
					}else if(modalFlag == 1){
						//hubo algun error
						if(result.errores.length!=0){
								for(let c=0;c<result.errores.length;c++){
									mensaje+=result.errores[c]+'\n';
								}
						Swal.fire('Error de validación', mensaje, 'error');
						return;
						}
					}
				});


			
				
				let tabla_contenidos={
					id:'#tabla-contenidos',
					columnas:[
							{data:'nombre'},
							{data:'descripcion'},
							{data:'escena'},
							{data:'clave'},
							{data:'visibilidad'},
							{data:'opciones'},
							],
					lenguaje: [1, 2, 3, 4, 5],
					 sinorden: [5],
					invisible: [],
					data: {canalActual: canalActual},
					url: base_url + "canales/getDataContenidos",
				}



				



			});







	$(document).on('click', ".borrar_canal", function(){
		let id = $(this).attr("data-canal");
		

		Swal.fire({
				title: "Eliminar Canal",
	            text: "¿Está seguro de eliminar al canal?",
	            type: "error",
	            showCancelButton: true,
  				confirmButtonColor: '#3085d6',
	            buttons: {
	            	no:{ 
	            		text:'No',
	            		className:'bg-danger'
	            	},
	                ok:{
	                	text:"Si",
	                	className:'bg-success'
	                } 
	                
	            },
	        }).then(function(result){
	        	//console.log(result)
	        	if(result.value){
	        		let formulario = new FormData();
	        		formulario.append(token_name, token_hash);
					formulario.append('id', id);

					//console.log(id);




					getAjaxFormData(formulario, base_url + 'canales/eliminarCanal').then(function(result){
						result=JSON.parse(result);
						if(result.proceso==0){
							_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
						}else{
							_toastr("success", "El Canal fue eliminado exitosamente", true);
							
							
							
					
							cargarTabla(tabla_canales);
							//cargarTabla(tabla_contenidos);
							
						
						}
					});
			

	        		
	        				
	        			//console.log(result);
					$(".vista-contenido").hide()

				}
	        });
	
	});


	
	

	$(document).on('click', ".borrar_contenido", function(){

		let id = $(this).attr("data-canal");

		//console.log($(this).attr("data-usuario")); comentario

		Swal.fire({
				title: "Eliminar Contenido",
	            text: "¿Está seguro de eliminar el contenido?",
	            type: "error",
	            showCancelButton: true,
  				confirmButtonColor: '#3085d6',
	            buttons: {
	            	no:{ 
	            		text:'No',
	            		className:'bg-danger'
	            	},
	                ok:{
	                	text:"Si",
	                	className:'bg-success'
	                } 
	                
	            },
	        }).then(function(result){
	        	//console.log(result)
	        	if(result.value){
	        		let formulario = new FormData();
	        		formulario.append(token_name, token_hash);
					formulario.append('id', id);

					//console.log(id);




					getAjaxFormData(formulario, base_url + 'canales/eliminarContenido').then(function(result){
						result=JSON.parse(result);
						if(result.proceso==0){
							_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
						}else{
							_toastr("success", "El contenido fue eliminado exitosamente", true);
							
							
							let tabla_contenidos={
								id:'#tabla-contenidos',
								columnas:[
										{data:'nombre'},
										{data:'descripcion'},
										{data:'escena'},
										{data:'clave'},
										{data:'visibilidad'},
										{data:'opciones'},
										],
								lenguaje: [1, 2, 3, 4, 5],
								 sinorden: [5],
								invisible: [],
								data: {canalActual: canalActual},
								url: base_url + "canales/getDataContenidos",
							}
		
		
					
							cargarTabla(tabla_canales);
							cargarTabla(tabla_contenidos);
							
						
						}
					});
			

	        		
	        				
	        			//console.log(result);
	        		
	        	}
	        });
		//console.log($(this).attr('data-usuario'))
	});



	
	




	function defaultImagen(opcion=false) {
	    $("#btn-imagen-cargar-nueva").text("Cargar imagen");
	    if (cropeo_foto != null) {
	        cropeo_foto.destroy();
	    }
	    recortofoto = false;
	    cambiofoto = false;
	    $("#btn-imagen-reset").hide(); // ocultar boton reset
	    $("#btn-imagen-aceptar").hide();
	    $("#btn-imagen-cambiar").hide();
	    $("#btn-imagen-cargar-nueva").show();
	    $("#btn-imagen-cargar-nueva").val("");
	    if(opcion){
	    	var preview = document.getElementById("imagen-preview2");
			preview.src = imgFotoActual;
			
	    }else{
	    	var preview = document.getElementById("imagen-preview2");
			preview.src = mensaje_preview_default;
		
		
		}
	    
		

	}

	function defaultImagenContenido(opcion=false){
		$("#btn-imagen-cargar-nueva-foto").text("Cargar imagen");
		if (cropeo_foto != null) {
	        cropeo_foto.destroy();
	    }
	    recortofoto = false;
		cambiofoto = false;
		$("#btn-imagen-reset-foto").hide(); // ocultar boton reset
	    $("#btn-imagen-aceptar-foto").hide();
	    $("#btn-imagen-cambiar-foto").hide();
	    $("#btn-imagen-cargar-nueva-foto").show();
		$("#btn-imagen-cargar-nueva-foto").val("");
		if(opcion){
			var previewfoto = document.getElementById("imagen-preview-foto");
			previewfoto.src = imgFotoActual;
		}else{
			var previewfoto = document.getElementById("imagen-preview-foto");
			previewfoto.src = mensaje_preview_default_foto;
		}	

	}

	let eventofoto = false;
	function resetearImagen() {
	    $("#btn-imagen-reset").on("click", function () {
	        /*modificado*/
	        eventofoto = true;
			$("#form-notificacion-boton-imagen-us-cargar").show();
	        if (modalFlag == 1){defaultImagen(false);}
			if (modalFlag == 2){defaultImagen(true);}
		});
		
		
	}

	let eventofoto2 = false;
	function resetearImagenContenido(){
		//imagendestacada
		$("#btn-imagen-reset-foto").on("click", function(){
			eventofoto2 = true;
			$("#form-notificacion-boton-imagen-us-cargar-foto").show();
			if (modalFlag == 1){defaultImagenContenido(false);}
			if (modalFlag == 2){defaultImagenContenido(true);}
	});
	}


	function OnChangeImagen(id, img) {
		
	    $("#btn-imagen-aceptar").hide();
	    $("#btn-imagen-cambiar").hide();
	    $("#btn-imagen-reset").hide(); // ocultar boton reset
	    var preview = document.getElementById(img);
	    preview.src = mensaje_preview_default;

	    $("#"+id).change(function () {
	//      errorImagen = true;
	        $("#btn-imagen-cargar").hide();
	        $("#btn-imagen-aceptar").show();
	        var preview = document.getElementById(img);
	        var file = document.getElementById(id).files[0];
	        var reader = new FileReader();
	        reader.onloadend = function () {
	            preview.src = reader.result;
	            $("#"+id).val("");
	            var imagen = document.getElementById(img);
	            cropeo_foto = new Cropper(imagen, {
	                aspectRatio: 1 / 1
	            });
	        }

			$("#form-notificacion-boton-imagen-us-cargar").hide();
	        if (file) {
	            reader.readAsDataURL(file);
	            $("#btn-imagen-reset").show();
	            cambiofoto = true;
	        } else {
	        	
	        	//debugger
	            preview.src = mensaje_preview_default;
	            $("#").hide();
	            $("#btn-imagen-reset").hide(); // ocultar boton reset
	            cambiofoto = false;
	        }
	    });
	}

	function OnChangeImagenContenido(id,img){

		$("#btn-imagen-aceptar-foto").hide();
	    $("#btn-imagen-cambiar-foto").hide();
		$("#btn-imagen-reset-foto").hide(); // ocultar boton reset
		
	    var previewfoto = document.getElementById(img);
	    previewfoto.src = mensaje_preview_default_foto;

	    $("#"+id).change(function () {

			$("#btn-imagen-cargar-foto").hide();
	        $("#btn-imagen-aceptar-foto").show();
	        var previewfoto = document.getElementById(img);
	        var file = document.getElementById(id).files[0];
	        var reader = new FileReader();
	        reader.onloadend = function () {
	            previewfoto.src = reader.result;
	            $("#"+id).val("");
	            var imagen = document.getElementById(img);
	            cropeo_foto = new Cropper(imagen, {			
	                aspectRatio: 2 / 1
	            });
	        }

			$("#form-notificacion-boton-imagen-us-cargar-foto").hide();

	        if (file) {
	            reader.readAsDataURL(file);
				$("#btn-imagen-reset-foto").show();
	            cambiofoto = true;
	        } else {
	        	
	        	//debugger
	            previewfoto.src = mensaje_preview_default_foto;
	            $("#").hide();
				$("#btn-imagen-reset-foto").hide();
	            cambiofoto = false;
	        }
	    });
	}


	function cargarCanales(opcion, id=''){
		//form_canal.tipo.html('').selectpicker('refresh');
		var formulario = new FormData();
	    formulario.append(token_name, token_hash);

			
	    	if(opcion==1){

				defaultImagen();
				$("#modal-canal").modal("show");
				$("#modal-canal").find(".modal-title").text("Nuevo Canal");
				$("#modal-canal").find(".button-title").text("Agregar");

				
	    	}else{
	    		
	    		form_canal.foto.val('');
				$("#modal-canal").modal("show");
				$("#modal-canal").find(".modal-title").text("Editar Canal");
				$("#modal-canal").find(".button-title").text("Editar");
				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				formulario.append('id', id);


				form_canal.id_canal.val(id);

        		getAjaxFormData(formulario, base_url+'canales/getCanal').then(function(result){
				
					result=JSON.parse(result);
        			if(result.errores.length==0){
	        			result.data.map(function(ele, index){
							
							//console.log("ELEEE DE CANAAAL",ele);
							$("#imagen-preview2").attr('src', ele.LOGO);
							imgFotoActual=ele.LOGO;				
							$("#btn-imagen-reset").hide();	
							

						
							//console.log("ele.LOGO:",ele.LOGO);

							form_canal.nombre.val(ele.NOMBRE);
							form_canal.descripcion_corta.val(ele.DESCRIPCION_CORTA);
							form_canal.descripcion_larga.val(ele.DESCRIPCION_LARGA);



							defaultImagen(true);

	        			});

	        			//console.log(result);
        			}
					
        		});

	    	}
	    	
	    	
	    //});
	}



	function cargarContenido(opcion, id=''){
		//form_canal.tipo.html('').selectpicker('refresh');
		var formulario = new FormData();
		formulario.append(token_name, token_hash);
		form_contenido.categoria.html('').selectpicker('refresh');
		

		getAjaxFormData(formulario, base_url + 'canales/getCategoria').then(function(result){
			if (result) {
				result=JSON.parse(result);
			}
			
			
		

			
	    	if(opcion==1){
				
				for(let c=0;c<result.length;c++){
		    		form_contenido.categoria.append('<option value='+result[c].ID_CATEGORIA+'>'+result[c].CAMPO+'</option>');
				}
				
				form_contenido.categoria.val('').selectpicker('refresh');


				//defaultImagen();
				defaultImagenContenido();
				$("#modal-contenido").modal("show");
				$("#modal-contenido").find(".modal-title").text("Nuevo Contenido");
				$("#modal-contenido").find(".button-title").text("Agregar");

				
				
	    	}else{
				for(let c=0;c<result.length;c++){
		    		form_contenido.categoria.append('<option value='+result[c].ID_CATEGORIA+'>'+result[c].CAMPO+'</option>');
				}
				form_contenido.categoria.selectpicker('refresh');
				//console.log("editar");
	    		
	    		form_contenido.foto.val('');
				
				
				$("#modal-contenido").modal("show");
				$("#modal-contenido").find(".modal-title").text("Editar Contenido");
				$("#modal-contenido").find(".button-title").text("Editar");

				$(id_dropzone + " .dz-preview").each(function (index, value) {
					var name = $(this).find('.js-open-cropper-modal').data('file-name-origen');
					var result = $.grep(myDropzone.getAcceptedFiles(), function (e) {
						return e.name == name;
					});
					var i = myDropzone.getAcceptedFiles().findIndex(x => x.name === name);
					myDropzone.getAcceptedFiles()[i].orden = (index + 1);
						
				});

				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				formulario.append('id', id);

				form_contenido.idcontenido.val(id);

        		getAjaxFormData(formulario, base_url+'canales/getContenido').then(function(result){
				
					result=JSON.parse(result);
        			if(result.errores.length==0){
	        			result.data.map(function(ele, index){
						

							var adjuntos = result.galeria;
							adjuntos.forEach(function (adjunto, index) {

							
								var mockFile = {idgaleria: "adj-" + adjunto.id, name: adjunto.name, src: adjunto.src, size: adjunto.peso, url: adjunto.url, orden: adjunto.orden};
								
								myDropzone.options.addedfile.call(myDropzone, mockFile);
		
								myDropzone.options.thumbnail.call(myDropzone, mockFile, adjunto.url);
		
								
								
								$(".dz-preview:last-child").attr('id', "adj-" + adjunto.id);
								$(".dz-preview:last-child").addClass('dz-image-preview-custom');
								//$(".dz-preview:last-child a").removeClass('dz-remove');
								$(".dz-preview:last-child").data('id', adjunto.id);


								$($('#adj-' + adjunto.id).find('.dz-image').find('img')[0]).on("error", function () {
									$($('#adj-' + adjunto.id).find('.dz-image')).css("background", "linear-gradient(to bottom, #eee, #ddd)");
									$(this).css("opacity", "0");
		
								});
				
								$('#adj-' + adjunto.id).find('.dz-remove').remove();
								$('#adj-' + adjunto.id).append('<div class="remove-image-preview"> <i class="glyphicon glyphicon-trash"> </div>');

								myDropzone.options.complete.call(myDropzone, mockFile);
								myDropzone.options.success.call(myDropzone, mockFile);

							});

							let adj = result.imagendestacada[0].url;
							$("#imagen-preview-foto").attr('src', adj);
	        				imgFotoActual=adj;
							$("#btn-imagen-reset-foto").hide();	


							
							form_contenido.descripcion.val(ele.DESCRIPCION);
							form_contenido.titulo.val(ele.NOMBRE);
							form_contenido.escena.val(ele.ESCENA);
							form_contenido.contraseña.val(ele.CLAVE);
							defaultImagenContenido(true);

							getAjaxFormData(formulario, base_url+'canales/getContenidoCat').then(function(result){
								let $ID_CATS=[];
								result=JSON.parse(result);
								
								

								result.forEach(function(element) {
									
									$ID_CATS.push(element.ID_CATEGORIA);
								});
								form_contenido.categoria.selectpicker('val', $ID_CATS);

							})


							//form_contenido.visibilidad.val(ele.VISIBILIDAD);
							//("ele.VISIBILIDAD:",ele.VISIBILIDAD);
							if (ele.VISIBILIDAD=='1') {
								
								$("#switch-btn").attr({
									"class":"btn btn-sm btn-toggle btn-info active",
									"aria-pressed" : "true"
								});
							} 
							if (ele.VISIBILIDAD=='0') {	
								
								$("#switch-btn").attr({
									"class":"btn btn-sm btn-toggle btn-info",
									"aria-pressed" : "false"
								});
							}
							
							if (ele.CLAVE) {
								switchClaveOn();
							}

							

	        			});

	        			//console.log(result);
        			}
					
        		});

	    	}
	    	
	    	
	    });
	}
	


	$(document).on("click",'.editar_canal', function(){


		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-canal");
		cargarCanales(2, id);
		$("#form-notificacion-boton-imagen-us-cargar").show();
		

	       
	});


	
	

	/*Eliminar contenido galería */
	
    $(document).on('click', ".remove-image-preview", function(e){
		
			id = $(this).parent().data("id");		
            dv = $(this).parent();
            e.stopPropagation();
            Swal.fire({
                title: "Eliminar Imagen",
                text: "¿Está seguro de eliminar la imagen previa ?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                buttons: {
                    no:{ 
                        text:'No',
                        className:'bg-danger'
                    },
                    ok:{
                        text:"Si",
                        className:'bg-success'
                    } 
                            
                        },
                    }).then(function(result){

                        //console.log(result)
                        if(result.value){
							
							arrayEliminarAdjuntos.push(id);				
							$(dv).remove();
                        }
					});
					
					// var x = $("#form-contenido-id").val();
				//	console.log("Id de imagen borrada previamente: "+id);

					
			
            });


	

	$(document).on("click",'.editar_contenido', function(){
		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-canal");
		cargarContenido(2, id);	
		$("#form-notificacion-boton-imagen-us-cargar-foto").show();

	});	




	$(document).on("click",'.mostrar_contenido', function mostrarContenido(){

		
		$(".vista-contenido").show();

		modalFlag = 2;
		
		limpiaModal();
		let id = $(this).attr("data-canal");
		let nombre = $(this).attr("data-nombre");
		canalActual=id;

		document.getElementById("canal-actual").innerText = nombre; 


					let tabla_contenidos={
						id:'#tabla-contenidos',
						columnas:[
								{data:'nombre'},
								{data:'descripcion'},
								{data:'escena'},
								{data:'clave'},
								{data:'visibilidad'},
								{data:'opciones'},
								],
						lenguaje: [1, 2, 3, 4, 5],
						 sinorden: [5],
						invisible: [],
						data: {canalActual: canalActual},
						url: base_url + "canales/getDataContenidos",
					}



					cargarTabla(tabla_contenidos);
		
					$("#form-notificacion-boton-imagen-us-cargar").show();
					
				
	        		
	});

});//fin $(function(){})

function limpiaModal(){
	


	form_canal.id.val('');
	form_canal.id_canal.val('');
	form_canal.nombre.val('');
	form_canal.descripcion_corta.val('');
	form_canal.descripcion_larga.val('');

	form_contenido.id.val('');
	form_contenido.idcontenido.val('');
	form_contenido.titulo.val('');
	form_contenido.escena.val('');
	form_contenido.categoria.val('').selectpicker('refresh');
	form_contenido.descripcion.val('');
	form_contenido.contraseña.val('');
	//Dropzone Area
	Dropzone.forElement(id_dropzone).removeAllFiles(true);
	$(".dz-preview").remove();

	arrayEliminarAdjuntos = [];

	//inicializa switch clave en false
	switchClaveOff();
	//inicializa switch de visibiliad en true
	$("#switch-btn").attr({
		"class":"btn btn-sm btn-toggle btn-info",
		"aria-pressed" : "false"
	});

	//inicializa input clave como tipo clave
	$(".input-clave").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
	$("#form-contenido-contraseña").prop("type", "password");
	
}

function terminarCrop(id) {
    if (cropeo_foto) {
        let croppedImg = cropeo_foto.getCroppedCanvas().toDataURL('image/png');
        var preview = document.getElementById(id);
        preview = document.getElementById(id);
        recortofoto = true;
        preview.src = croppedImg;
        cropeo_foto.destroy();
        $("#btn-imagen-cargar").show();
        $("#btn-imagen-cambiar").show();
        $("#btn-imagen-aceptar").hide();
        $("#btn-imagen-cargar-nueva").hide();
        $("#form-notificacion-boton-imagen-us-cargar").show();
        eventofoto = false;

    }
}

function terminarCropContenido(id){
	if(cropeo_foto){
		let croppedImg = cropeo_foto.getCroppedCanvas().toDataURL('image/png');
		var previewfoto = document.getElementById(id);
		previewfoto = document.getElementById(id);
		recortofoto = true;
		previewfoto.src = croppedImg;
		cropeo_foto.destroy();
		$("#btn-imagen-cargar-foto").show();
        $("#btn-imagen-cambiar-foto").show();
        $("#btn-imagen-aceptar-foto").hide();
        $("#btn-imagen-cargar-nueva-foto").hide();
        $("#form-notificacion-boton-imagen-us-cargar-foto").show();
        eventofoto2 = false;
	}
}


function switchClaveOn(){
	$("#switchClave").attr({
		"class":"btn btn-sm btn-toggle btn-info active",
		"aria-pressed" : "true"
	});
}

function switchClaveOff(){
	$("#switchClave").attr({
		"class":"btn btn-sm btn-toggle btn-info",
		"aria-pressed" : "false"
	});
}


function configurarDescargaExcel() {
    $.fn.dataTable.Api.register('buttons.exportData()', function (options) {
    	
	    if (this.context.length) {
			//console.log("holaaaaaaaaaaa");
	        //console.log(this.context.length);
			var varibles_post_excel = this.ajax.params();
			//console.log("varibles_post_excel:",varibles_post_excel);
	        var url_excel = this.ajax.url();
	        // _loading(true,"body","Exportando a excel... Espere por favor.");
	        $.extend(varibles_post_excel, {exportar: true});
	        var jsonResult = $.ajax({
	            url: url_excel,
	            data: varibles_post_excel,
	            type: "POST",
	            success: function (result) {
	                //Do nothing
	            },
	            async: false
	        });
	        // _loading(false,"body");
			jsonResult = $.parseJSON(jsonResult.responseText);
		
	       //	console.log(jsonResult.columns);
			//console.log(jsonResult.data)

        return {body: jsonResult.data, header: jsonResult.columns};
    }
});
}

function cargarTabla(info) {

		// console.log(info);
	var url = info.url;
	var data = info.data;
	data[token_name] = token_hash;


	var table = $(info.id).DataTable({
		"columns": info.columnas,
		"destroy": true,
		"processing": true,
		"serverSide": true,
		"paging": true,
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bInfo": true,
		"bAutoWidth": false,
		"ajax": {
		    url: info.url,
		    type: "POST",
			data: data,
		},
		// "initComplete": function (settings, json) {
		//
		// },
		"columnDefs": [
		    {type: "spanish-string", targets: info.lenguaje},
		    {"orderable": false, "targets": info.sinorden},
			{"bVisible": false, "aTargets": info.invisible},
			//{"visible": false, "targets": 4}
		
		],
		order: [],
		"language": {
		    "processing": '<div style="color:#ff3d00;font-size:14px;font-weight: bold;padding-top:40px;"><i  class="fa fa-spinner fa-spin fa-lg fa-fw"></i>Cargando Datos...</div>',
		    "sSearch": "",
		    "sLengthMenu": "Mostrar _MENU_ &nbsp;&nbsp;&nbsp;&nbsp",
		    "emptyTable": "No hay resultados disponibles",
		    "info": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
		    "infoEmpty": "",
		    "infoFiltered": "(filtrado de _MAX_ resultados totales)", /*tambien cambiar*/
		    "sZeroRecords": "No hay resultados",
		    "oPaginate": {
		        "sNext": ">>",
		        "sPrevious": "<<"
		    }
		},
		"lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
		"dom": '<"top"Bfrt> <"bottom" <"row" <"col-4" l><"col-4 text-center" i>  <"col-4" p>>>',
		"buttons": [
		    {
		        "extend": 'excel',
		        "text": '<i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Exportar a Excel',


		    }
		]
	}
	

	);

	$('.dataTables_filter input').css("margin-right", "5px");
   $('.dataTables_processing').css("height", "50px");
   $('.dataTables_processing').css("z-index", "1");
  // $('.buttons-excel').removeClass("btn-green").addClass('');
  $('.buttons-excel').addClass('btn-primary btn');
   $('.dataTables_filter input').attr("placeholder", "Buscar");
   $('.dataTables_filter input').attr("class", "form-control");



}

function eventoInputClaveIcon(id) {
    $(".input-clave").on("click", function () {
		//debugger

        var input = $(id);
        switch (input.prop("type")) {
            case "text":
                $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
                input.prop("type", "password");
                break;
            case "password":
                $(this).find("i").removeClass("fa-eye-slash").addClass("fa-eye");
                input.prop("type", "text");
                break;
        }
    });
}

function resetInputClaveIcon(id) {
    var input_clave = $(".input-clave");
    var input = $(id);
    input_clave.find("i").removeClass("fa-eye").addClass("fa-eye-slash");
    input.prop("type", "password");
}



