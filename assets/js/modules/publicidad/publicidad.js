/////////////////////AQUI
let mensaje_preview_default=base_url+'assets/images/default.png';
let default_image='assets/images/publicidad/default.png';
let cropeo_foto = null;
let recortofoto = false;
let cambiofoto = false;
let imgFotoActual="";
let modalFlag = 0;
var pub_actual = 0;
let cont_publicidad = 0;
let startDate;
let endDate;
let usuarioActual;
let monedaActual;
let canalActual;
let contenidoActual;
let tipoActual;



$(".vista-publicidad").hide();


let form_publicidad={
	id:$('#formulario-publicidad'),
	id_publicidad:$('#form-publicidad-id'),
	foto:$('#form-publicidad-foto'),
	titulo:$('#form-publicidad-titulo'),
	monto:$('#form-publicidad-monto'),
	moneda:$('#form-publicidad-moneda'),
	usuario:$('#form-publicidad-usuario'),
	tipo:$('#form-publicidad-tipo'),
	canal:$('#form-publicidad-canal'),
	contenido:$('#form-publicidad-contenido'),
	cuerpo:$('#form-publicidad-cuerpo'),
	fecha: $('#selector-fecha')
}

$(function(){
	
	
	let tabla_tipo = {
		id:'#tabla-tipo-publicaciones',
		columnas:[
				{data:'tipo'},
				{data:'publicidad'},
		],
		lenguaje:[1],
		sinorden:[1],
		invisible: [],
		data: {tipo:2},
		url: base_url +"publicidad/getDataTipoPublicidadVista",
	}	

	let tabla_publicidad={

		id:'#tabla-publicidad',
		columnas:[
				{data:'foto'},
				{data:'titulo'},
				{data:'cuerpo'},
				{data:'usuario'},
				{data:'tipo'},
				{data:'moneda'},
				{data:'monto'},
				{data:'canal'},
				{data:'contenido'},		
				{data:'fechainicio'},
				{data:'fechafin'},
				{data:'opciones'},
				],
		lenguaje: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
     	sinorden: [0, 11],
    	invisible: [],
    	data: {pub_actual: pub_actual},
		url: base_url + "publicidad/getDataPublicidades",
		
	}


	
	al_seleccionar_canal();
	al_cambiar_monto();
	configurarDescargaExcel();
	cargarTabla(tabla_tipo);
	//cargarTabla(tabla_publicidad);
	OnChangeImagen('form-publicidad-foto', 'imagen-preview2');
	resetearImagen();
	limpiaModal();
	cargarInputFechas();
  

	

	

	$("#btn-publicidad-agregar").on('click', function(){
		//console.log("agregar");
		modalFlag = 1;
		limpiaModal();
		//limpiarSelect(1);
		limpiarSelect(2);
		$("#form-notificacion-boton-imagen-us-cargar").show();
		cargarPublicidad(1);
		cargarSelectUsuarios(1);
		cargarSelectMoneda(1);
		cargarSelectCanal(1);	
		cargarSelectTipoPublicidad(1);
	});

	form_publicidad.id.submit(function (e){

		e.preventDefault();
		
		//console.log('editarrrrrr')
		if (cambiofoto && !recortofoto) {
            _toastr('warning', 'Recorte la imagen para seguir con la operación', true);
            return false;
		}

		form_publicidad.fecha.val('');
		let foto=form_publicidad.foto.val();
		let id_publicidad=form_publicidad.id_publicidad.val();
		let mensaje="";
		let titulo = form_publicidad.titulo.val();
		let monto = form_publicidad.monto.val();
		let moneda=form_publicidad.moneda.selectpicker('val');
		let tipo=form_publicidad.tipo.selectpicker('val');
		let canal=form_publicidad.canal.selectpicker('val');
		let contenido=form_publicidad.contenido.selectpicker('val');
		let usuario=form_publicidad.usuario.selectpicker('val');
		let cuerpo = form_publicidad.cuerpo.val();
		let fechainicio = $('#selector-fecha').data('daterangepicker').startDate.format('YYYY-MM-DD');
		let fechafin = $('#selector-fecha').data('daterangepicker').endDate.format('YYYY-MM-DD');

		
			
			
			
			//El monto de las monedas ( moneda == 2 -> Dolar, moneda == 1 -> CLP), se manda al backend formateado de la forma XXXXX,XX(Para el dolar)
			// r XXXXXXX(Para el CLP).
			if(moneda == 1){
				monto = monto.split(".").join("");
				//console.log("formateado: "+monto);
			}
				
			

			if(moneda == 2){
				if(monto=="," || monto == ",." || monto == ".,"){
					mensaje+='No se puede enviar un monto con ese formato, porfavor ingresar un monto válido (formato válido: XXXX,XX). <br>';
				}
		
				if(mensaje.length!=0){
					Swal.fire('Error de validación', mensaje, 'error');
					return;
				}
				
				if (monto.indexOf(',') != -1){
						// console.log("existe coma");

						if(monto == '0,0' || monto == '0,00'){
							monto == '0';
						}else{
							monto = monto.split(".").join(",");
							let replacement = '.';
							monto = monto.replace(/,([^,]*)$/, replacement + '$1'); 
							// console.log("Antes formatear accounting: "+monto);
							monto = accounting.unformat(monto);
							// console.log("formateado con accounting: "+monto);
						}
	
					}
					else{
						// console.log("no existe coma");
						monto = monto.split(".").join("");
						// console.log("formateado (sin coma): "+monto);
					}

				
			//	console.log(monto);
			}
	
		if(moneda == 2 && monto == '' || moneda == 1 && monto == ''){
			mensaje+='El monto no puede ser vacío o contener el formato ingresado. (Formato válido: XXXX,XX) <br>';
		}


 		
		if(mensaje.length!=0){
			Swal.fire('Error de validación', mensaje, 'error');
			return;
		}

		//return false;
		/* console.log(monto);
		return false; */
		//validaciones
		
		if (titulo.trim().length==0) {
			mensaje+='Falta llenar el título <br> ';
		}

/* 		if (monto.trim().length==0) {
			mensaje+='Falta llenar el campo monto <br> ';
		} */

		if (cuerpo.trim().length==0) {
			mensaje+='Falta llenar el campo cuerpo <br> ';
		}

/* 		if (moneda!=null && monto == null) {
			mensaje+='Falta escoger un monto <br> ';
		} */

		if (usuario.length===0) {
			mensaje+='Falta elegir un usuario <br> ';
		} 

		if(mensaje.length!=0){
			Swal.fire('Error de validación', mensaje, 'error');
			return;
		}


		if (tipo.length===0) {
			mensaje+='Falta elegir un tipo de publicidad <br> ';
		} 
		

		if(mensaje.length!=0){
			Swal.fire('Error de validación', mensaje, 'error');
			return;
			}

		
		

		/* if(moneda.length!=0 && monto.length == null){
			mensaje+='Necesitas '
		} */
		
/* 		if (canal == null) {
			mensaje+='Falta elegir un canal <br> ';
		}
		if (contenido == null) {
			mensaje+='Falta elegir un contenido <br> ';
		} */

            if(mensaje.length!=0){
                Swal.fire('Error de validación', mensaje, 'error');
                return;
				}
				

			
            //mandar datos al backend
            let formData = new FormData();

			formData.append('foto', $("#imagen-preview2").prop('src'));
			//modalFlag estados:  2 = Modal adición , 1 = Modal Agregar


			formData.append('titulo', titulo);
			formData.append('cuerpo', cuerpo);
			formData.append('moneda', moneda);
			formData.append('monto', monto);
			formData.append('idusuario', usuario);
			formData.append('idcanal', canal);
			formData.append('idcontenido', contenido);
			formData.append('pub_actual', tipo);
			formData.append('fechainicio',fechainicio);
			formData.append('fechafin',fechafin);
			//formData.append('fecha',fecha)

					
			if (modalFlag == 2) { formData.append('idpublicidad', id_publicidad);}
			formData.append(token_name, token_hash);

		


            getAjaxFormData(formData, base_url+'publicidad/guardarPublicidad').then(function(result){
				//console.log("1",result);
                result=JSON.parse(result);
                if(result.proceso==1){
					$("#modal-publicidad").modal("hide");
					//$("#modal-contenido").modal("hide");
                    recortofoto = false;
                    cambiofoto = false;
                    //resetInputClaveIcon('#form-contenido-contraseña');



					
					form_publicidad.titulo.val('');
					form_publicidad.cuerpo.val('');
					form_publicidad.monto.val('');
					form_publicidad.moneda.val('');
					form_publicidad.fecha.fechainicio;
					form_publicidad.fecha.fechafin;
					

				/* 	console.log("Tipo actual: "+tipoActual);
					console.log("Tipo a enviar: "+tipo);
 */
					
					 cargarTabla(tabla_publicidad);
					 //cargarTabla(tabla_contenidos);

				//	 document.getElementById("canal-actual").innerText = nombre; 

                    if(modalFlag == 2){
                        
						_toastr("success", "La publicidad fue actualizada exitosamente", true);
							if(tipo != tipoActual){
								cargarTabla(tabla_tipo);
							}

                    }
                    if(modalFlag == 1){
						_toastr("success", "La publicidad se agregó exitosamente", true);
						cargarTabla(tabla_tipo);

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
			



			let tabla_publicidad={

				id:'#tabla-publicidad',
				columnas:[
						{data:'foto'},
						{data:'titulo'},
						{data:'cuerpo'},
						{data:'usuario'},
						{data:'tipo'},		
						{data:'moneda'},
						{data:'monto'},
						{data:'canal'},
						{data:'contenido'},		
						{data:'fechainicio'},
						{data:'fechafin'},
						{data:'opciones'},
						],
				lenguaje: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
				 sinorden: [0, 11],
				invisible: [],
				data: {pub_actual: pub_actual},
				url: base_url + "publicidad/getDataPublicidades",
			}


			
    
		});
		


	$(document).on('click', ".borrar_publicidad", function(){
		let id = $(this).attr("data-publicidad");
		

		Swal.fire({
				title: "Eliminar Publicidad",
	            text: "¿Está seguro de eliminar la publicidad?",
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




					getAjaxFormData(formulario, base_url + 'publicidad/eliminarPublicidad').then(function(result){
						result=JSON.parse(result);
						if(result.proceso==0){
							_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
						}else{
							_toastr("success", "La publicidad fue eliminada exitosamente", true);

							let tabla_publicidad={

								id:'#tabla-publicidad',
								columnas:[
										{data:'foto'},
										{data:'titulo'},
										{data:'cuerpo'},
										{data:'usuario'},
										{data:'tipo'},
										
										{data:'moneda'},
										{data:'monto'},
										{data:'canal'},
										{data:'contenido'},		
										{data:'fechainicio'},
										{data:'fechafin'},
										{data:'opciones'},
										],
								lenguaje: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
								 sinorden: [0, 11],
								invisible: [],
								data: {pub_actual: pub_actual},
								url: base_url + "publicidad/getDataPublicidades",
								
								
							}

							cargarTabla(tabla_publicidad);
							cargarTabla(tabla_tipo);

							//cargarTabla(tabla_contenidos);
							
						
						}
					});
			


				}
	        });
	
	});


	function defaultImagen(opcion=false) {
	    $("#btn-imagen-cargar-nueva").text("Cargar imagen");
	    if (cropeo_foto != null) {
	        cropeo_foto.destroy();
	    }
	    recortofoto = false;
	    cambiofoto = false;
	    //console.log(eventofoto);
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

	let eventofoto = false;
	function resetearImagen() {
	    $("#btn-imagen-reset").on("click", function () {
	        /*modificado*/
	        eventofoto = true;
	//      console.log(eventofoto);
			$("#form-notificacion-boton-imagen-us-cargar").show();
	        if (modalFlag == 1){defaultImagen(false);}
			if (modalFlag == 2){defaultImagen(true);}
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

	function cargarPublicidad(opcion, id=''){

		var formulario = new FormData();
	    formulario.append(token_name, token_hash);

			
	    	if(opcion==1){

				defaultImagen();
				$("#modal-publicidad").modal("show");
				$("#modal-publicidad").find(".modal-title").text("Nueva Publicidad");
				$("#modal-publicidad").find(".button-title").text("Agregar");

				
	    	}else{
	    		
	    		form_publicidad.foto.val('');
				$("#modal-publicidad").modal("show");
				$("#modal-publicidad").find(".modal-title").text("Editar Publicidad");
				$("#modal-publicidad").find(".button-title").text("Editar");
				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				formulario.append('id', id);


				form_publicidad.id_publicidad.val(id);

        		getAjaxFormData(formulario, base_url+'publicidad/getPublicidad').then(function(result){
				
					result=JSON.parse(result);

        			if(result.errores.length==0){
	        			result.data.map(function(ele, index){
						
						
							$("#imagen-preview2").attr('src', ele.FOTO);
							imgFotoActual=ele.FOTO;				
							$("#btn-imagen-reset").hide();	
							
							form_publicidad.titulo.val(ele.TITULO);
							form_publicidad.cuerpo.val(ele.CUERPO);
							//form_publicidad.monto.val(ele.MONTO);
							form_publicidad.moneda.val(ele.MONEDA);
							form_publicidad.fecha.val(ele.FECHA_INICIO  +' - '+ ele.FECHA_FIN);
							usuarioActual = ele.ID_USUARIO;
							monedaActual = ele.MONEDA;
							canalActual = ele.ID_CANAL;
							contenidoActual = ele.ID_CONTENIDO;
							tipoActual = ele.ID_TIPO;


							switch(monedaActual){
								case '1':
									ele.MONTO = accounting.formatNumber(ele.MONTO,0,".");								
									form_publicidad.monto.val(ele.MONTO);
									break;
								case '2':
									ele.MONTO = accounting.formatNumber(ele.MONTO,2,".",",");								
									form_publicidad.monto.val(ele.MONTO);
									break;
							}
							
							/* console.log(ele.MONTO);
							return false; */
							


							//form_publicidad.usuario.val(ele.ID_USUARIO).selectpicker('refresh');

							//form_publicidad.fechainicio.val(ele.FECHA_INICIO);
							//form_publicidad.fechafin.val(ele.FECHA_FIN); 

							defaultImagen(true);

	        			});

        			}
					
        		});

	    	}
	    	
	}

	



	$('#form-publicidad-moneda').on('change', function(e) {


		switch(this.value){
			case '1':
					//alert("CLP");
					$("#form-publicidad-monto").unbind();
					$("#form-publicidad-monto").removeAttr('maxlength');
					$("#form-publicidad-monto").attr('maxlength',15);
					$("#form-publicidad-monto").keyup(function(e){
						
						valorCLP(this);	
						//	console.log("value: "+this.value);
					});
					$('#form-publicidad-monto').addClass('input-number');
					$('.input-number').on('input', function () { 
						this.value = this.value.replace(/[^0-9]/g,'');
					});
					
					break;
			case '2':
					//alert("DOLAR");
					$("#form-publicidad-monto").unbind();	
					$("#form-publicidad-monto").removeAttr('maxlength');	
					$("#form-publicidad-monto").attr('maxlength',12);
		/* 			
					$('#form-publicidad-monto').keyup(function(e){
						valorDolar(this.value);
						this.value = this.value.split(",").join(".");
						let replacement = ',';
						this.value = this.value.replace(/.([^.]*)$/, replacement + '$1'); 
						
					});	
 */
					$("#form-publicidad-monto").keyup(function(e){
						e.stopPropagation();
						$(this).val(valorDolar($(this).val()));
						this.value = this.value.replace(',,' , '');
						this.value = this.value.replace('.,' , '');
						this.value = this.value.replace(',.' , '');


					});

					$('#form-publicidad-monto').addClass('input-number');
					$('.input-number').on('input', function () { 
						this.value = this.value.replace(/[^,{1}0-9]/g,'');
						
					});
					break;
		}
	
	  });

	  function cargarSelectCanal(opcion, id=''){





		var formulario = new FormData();
		formulario.append(token_name,token_hash);
		form_publicidad.canal.html('').selectpicker('refresh');
		getAjaxFormData(formulario, base_url + 'publicidad/getCanal').then(function(result){

			result = JSON.parse(result);
			if(opcion == 1){
				for ( let c=0 ; c<result.length ; c++){
		    		form_publicidad.canal.append('<option value='+result[c].ID_CANAL+'>'+result[c].NOMBRE+'</option>');
				}

				form_publicidad.canal.val('').selectpicker('refresh');


			}else{

				/* console.log("length" +result.length);
				console.log("RESULT 0: "+result[0].ID_CANAL);
				console.log("RESULT 0: "+result[0].NOMBRE);
 */

				for(let c=0 ; c<result.length ; c++){
		    		//console.log(result[c]);
		    		form_publicidad.canal.append('<option value='+result[c].ID_CANAL+'>'+result[c].NOMBRE+'</option>');
				}

				
				form_publicidad.foto.val('');

				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				form_publicidad.canal.val(id);
				//form_publicidad.canal.selectpicker('val', canalActual);
				form_publicidad.canal.val(canalActual).selectpicker('refresh');
				
			} 
		});
	}

	function cargarSelectContenido(opcion, id=''){


		var formulario = new FormData();
		formulario.append(token_name,token_hash);
		form_publicidad.contenido.html('').selectpicker('refresh');
		getAjaxFormData(formulario, base_url + 'publicidad/getContenido').then(function(result){

			result = JSON.parse(result);
			if(opcion == 1){
				for ( let c=0 ; c<result.length ; c++){
					form_publicidad.contenido.append('<option value='+result[c].ID_CONTENIDO+'>'+result[c].NOMBRE+'</option>');
				}

				form_publicidad.contenido.val('').selectpicker('refresh');


			}else{
				for(let c=0 ; c<result.length ; c++){
		    		//console.log(result[c]);
					form_publicidad.contenido.append('<option value='+result[c].ID_CONTENIDO+'>'+result[c].NOMBRE+'</option>');
				}

				
				form_publicidad.foto.val('');

				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				form_publicidad.contenido.val(id);
				//form_publicidad.contenido.selectpicker('val', contenidoActual);
				form_publicidad.contenido.val(contenidoActual).selectpicker('refresh');
			} 
		});
	}

	function cargarSelectTipoPublicidad(opcion, id=''){
		var formulario = new FormData();
		formulario.append(token_name,token_hash);
		form_publicidad.tipo.html('').selectpicker('refresh');
		getAjaxFormData(formulario, base_url + 'publicidad/getPublicidadTipo').then(function(result){
			result = JSON.parse(result);
			if(opcion == 1){
				for ( var d=0 ; d<result.length ; d++){
					form_publicidad.tipo.append('<option value='+result[d].ID_TIPO+'>'+result[d].CAMPO+'</option>');
				}

				form_publicidad.tipo.val('').selectpicker('refresh');


			}else{
				for(var d=0 ; d<result.length ; d++){
		    		//console.log(result[c]);
		    		form_publicidad.tipo.append('<option value='+result[d].ID_TIPO+'>'+result[d].CAMPO+'</option>');
				}

				

				/* let formulario = new FormData();

        		formulario.append(token_name, token_hash); */
			//	form_publicidad.tipo.val(id);
				form_publicidad.tipo.selectpicker('val', tipoActual);
				form_publicidad.tipo.selectpicker('refresh');
        			
	        	/* 		result.map(function(ele, index){
	
							form_publicidad.tipo.val(ele.ID_TIPO).selectpicker('refresh');

	        			}); */

        			
				
			}
		});
	}

	function cargarSelectMoneda(opcion, id=''){
		var formulario = new FormData();
		formulario.append(token_name,token_hash);
		form_publicidad.moneda.html('').selectpicker('refresh');
		getAjaxFormData(formulario, base_url + 'publicidad/getMoneda').then(function(result){
			result = JSON.parse(result);
			
			if (opcion == 1){
				for ( let c=0 ; c<result.moneda.length ; c++){

					form_publicidad.moneda.append('<option value='+result.moneda[c].ID+'>'+result.moneda[c].TEXTO+' ('+result.moneda[c].ABREVIACION+') </option>');
				}


					
				form_publicidad.moneda.val('').selectpicker('refresh');
			}else{
				for ( let c=0 ; c<result.moneda.length ; c++){

					form_publicidad.moneda.append('<option value='+result.moneda[c].ID+'>'+result.moneda[c].TEXTO+' ('+result.moneda[c].ABREVIACION+') </option>');				}

				
					

				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				formulario.append('id', id);
				
				form_publicidad.id_publicidad.val(id);
				

				form_publicidad.moneda.selectpicker('val', monedaActual);
				form_publicidad.moneda.selectpicker('refresh');
						
			
				switch(monedaActual){
					case '1':
									$("#form-publicidad-monto").unbind();
									$("#form-publicidad-monto").removeAttr('maxlength');
									$("#form-publicidad-monto").attr('maxlength',15);
									$("#form-publicidad-monto").keyup(function(e){
										
										valorCLP(this);	
									});
									$('#form-publicidad-monto').addClass('input-number');
									$('.input-number').on('input', function () { 
										this.value = this.value.replace(/[^0-9]/g,'');
									});
								break;
					case '2':

									$("#form-publicidad-monto").unbind();	
									$("#form-publicidad-monto").removeAttr('maxlength');	
									$("#form-publicidad-monto").attr('maxlength',15);
						/* 			
									$('#form-publicidad-monto').keyup(function(e){
										valorDolar(this.value);
										this.value = this.value.split(",").join(".");
										let replacement = ',';
										this.value = this.value.replace(/.([^.]*)$/, replacement + '$1'); 
										
									});	
				*/
									$("#form-publicidad-monto").keyup(function(e){
										e.stopPropagation();
										$(this).val(valorDolar($(this).val()));
										this.value = this.value.replace(',,' , '');
										this.value = this.value.replace('.,' , '');
										this.value = this.value.replace(',.' , '');

									});
				
									$('#form-publicidad-monto').addClass('input-number');
									$('.input-number').on('input', function () { 
										this.value = this.value.replace(/[^,{1}0-9]/g,'');
										
									});
								break;
				}
				
			}
		});

	}


	function cargarSelectUsuarios(opcion, id=''){
		
		var formulario = new FormData();
		formulario.append(token_name,token_hash);
		form_publicidad.usuario.html('').selectpicker('refresh');
		getAjaxFormData(formulario, base_url + 'publicidad/getUsuario').then(function(result){

			result = JSON.parse(result);
			if(opcion == 1){
				for ( let c=0 ; c<result.length ; c++){
					form_publicidad.usuario.append('<option value='+result[c].ID_USUARIO+'>'+result[c].NOMBRE+' '+result[c].APELLIDOP+' '+result[c].APELLIDOM+'</option>');
				}

				form_publicidad.usuario.val('').selectpicker('refresh');


			}else{
				for(let c=0 ; c<result.length ; c++){
		    		//console.log(result[c]);
					form_publicidad.usuario.append('<option value='+result[c].ID_USUARIO+'>'+result[c].NOMBRE+' '+result[c].APELLIDOP+' '+result[c].APELLIDOM+'</option>');
				}

				
				form_publicidad.foto.val('');

				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				form_publicidad.usuario.val(id);
				form_publicidad.usuario.selectpicker('val', usuarioActual);
				form_publicidad.usuario.selectpicker('refresh');

				
				
			} 
		});
	}




	$( ".drp-selected" ).before( "<small>Fechas elegidas:</small>" );
	$("button.applyBtn").attr('id', 'btn_seleccionar_fecha');
	$("#btn_seleccionar_fecha").removeClass("btn-primary");




// Se activa el selector de Contenido.
	function al_seleccionar_canal(){
		$(form_publicidad.canal).on('change',function(e){

			//limpiarSelect(1);
			cargarSelectContenido(1);

		});
	}

	function al_cambiar_monto(){
		$(form_publicidad.moneda).on('change',function(){
			limpiarSelect(2);
		});
	}

	//1: Limpia el selector de contenido, una vez activado el de "Canales", se habilita.
	//2: campo Monto se habilita cuando está seleccionado un tipo de moneda, de lo contrario se deshabilita.
	function limpiarSelect(proceso){
		switch(proceso){
			case 1:
				
					if($(form_publicidad.canal).val() == ""){$(form_publicidad.contenido).prop("disabled", true);}
					else{
						$(form_publicidad.contenido).prop("disabled", false);
					}
					
					form_publicidad.contenido.selectpicker("refresh");
				
				break;
			case 2:
					if($(form_publicidad.moneda).val() == ""){
						$(form_publicidad.monto).prop("disabled",true);
					}else{
						$(form_publicidad.monto).prop("disabled",false);

					}
					form_publicidad.monto.val('');
					
				break;



		}
	}



	$(document).on("click",'.editar_publicidad', function(){
		modalFlag = 2;
		limpiaModal();
		$("#form-publicidad-monto").unbind();
		let id = $(this).attr("data-publicidad");
		
		//console.log("id de contenido al editar : ",id);
		cargarPublicidad(2,id);
		cargarSelectUsuarios(2, id);
		cargarSelectCanal(2, id);	
		cargarSelectMoneda(2,id);
		cargarSelectContenido(2,id);
		cargarSelectTipoPublicidad(2, id);		
		$("#form-notificacion-boton-imagen-us-cargar").show();
	        	
	});


	$(document).on("click",'.mostrar_publicidad', function (e){

		e.preventDefault();
		$(".vista-publicidad").show();

		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-tipo-publicidad");
		let nombre = $(this).attr("data-nombre");
		pub_actual=id;
	
		document.getElementById("publicidad-actual").innerText = nombre; 


		let tabla_publicidad={

			id:'#tabla-publicidad',
			columnas:[
					{data:'foto'},
					{data:'titulo'},
					{data:'cuerpo'},
					{data:'usuario'},
					{data:'tipo'},
					
					{data:'moneda'},
					{data:'monto'},
					{data:'canal'},
					{data:'contenido'},		
					{data:'fechainicio'},
					{data:'fechafin'},
					{data:'opciones'},
					],
			lenguaje: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
			 sinorden: [0, 11],
			invisible: [],
			data: {pub_actual:pub_actual},
			url: base_url + "publicidad/getDataPublicidades",
			
		}

		

		cargarTabla(tabla_publicidad);		
	        		
	});

});//fin $(function(){})

function limpiaModal(){
	


	form_publicidad.id.val('');
	form_publicidad.id_publicidad.val('');
	form_publicidad.titulo.val('');	
	form_publicidad.monto.val('');
	form_publicidad.cuerpo.val('');
	form_publicidad.moneda.val('');
	form_publicidad.usuario.val('').selectpicker('refresh');
	form_publicidad.tipo.val('').selectpicker('refresh');
	form_publicidad.contenido.val('').selectpicker('refresh');
	form_publicidad.canal.val('').selectpicker('refresh');
	form_publicidad.fecha.val('');

	
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


function valorCLP(input){
	var num = input.value.replace(/\./g,'');
	if(!isNaN(num)){
	num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
	num = num.split('').reverse().join('').replace(/^[\.]/,'');
	input.value = num;
	}
	
	else{ 
	input.value = input.value.replace(/[^\d\.]*/g,'');
	}
}



function valorDolar(num){
	var str = num.toString(), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(",") > 0) {
		parts = str.split(",");
		str = parts[0];
	}
	
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ".") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(".");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return(formatted + ((parts) ? "," + parts[1].substr(0, 2) : ""));
};


function cargarTabla(info) {

		// console.log(info);
	var url = info.url;
	var data = info.data;
	data[token_name] = token_hash;

	// console.log("token_name",token_name);
	// console.log("token_hash",token_hash);
	// console.log("data",data);
	/*modificar tabla en todas las demas*/
	//console.log(info);

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






        

function cargarInputFechas(){

	
	$(form_publicidad.fecha).daterangepicker({
		
		opens: 'center',
		drops: 'down',
		locale: {
			format: 'DD/MM/YYYY',
			cancelLabel: 'Cerrar',
			applyLabel: 'Seleccionar',
			   "daysOfWeek": [
				  "Do",
				  "Lu",
				  "Ma",
				  "Mi",
				  "Ju",
				  "Vi",
				  "Sa"
			  ],
			  "monthNames": [
				  "Enero",
				  "Febrero",
				  "Marzo",
				  "Abril",
				  "Mayo",
				  "Junio",
				  "Julio",
				  "Agosto",
				  "Septiembre",
				  "Octubre",
				  "Noviembre",
				  "Diciembre"
			  ],
			  "firstDay": 1
		  },
		  parentEl: "#modal-publicidad .modal-body"
	  }, function(start, end, label) {
		//console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		startDate = start;
		endDate = end;

		
	
	});

	
	/* $(form_publicidad.fecha).daterangepicker({
		parentEl: "#modal-publicidad .modal-body"            
	}) */

	/* $("#modal-publicidad").scroll(function(){
		$("#selector-fecha").daterangepicker('place');
	}); */

}