/////////////////////AQUI
let mensaje_preview_default=base_url+'assets/images/default.png';
let default_image='assets/images/categorias/default.png';
let cropeo_foto = null;
let recortofoto = false;
let cambiofoto = false;
let imgFotoActual="";
let modalFlag = 0;


// let max_description=100;
// 	d_larga=document.getElementById("tabla-canales").rows[1].childNodes[2].innerHTML;
// 	if (d_larga.length>max_description){
// 		document.getElementById("tabla-canales").rows[1].childNodes[2].innerHTML=d_larga.substring(0,max_description-3).concat('...');
// 	} 

// setTimeout(function() { 
// 	let max_description=100;
// 	d_larga=document.getElementById("tabla-canales").rows[1].childNodes[2].innerHTML;
// 	if (d_larga.length>max_description){
// 		document.getElementById("tabla-canales").rows[1].childNodes[2].innerHTML=d_larga.substring(0,max_description-3).concat('...');
// 	} 
// 	console.log("hola")
// }, 5000);


let form_usuario={
	id:$('#formulario-canal'),
	idusuario:$('#form-canal-id'),
	foto:$('#form-canal-foto'),
	campo:$('#form-categoria-campo'),
}
$(function(){
	
	let tabla_categorias={

		id:'#tabla-categorias',
		columnas:[
				{data:'logo'},
				{data:'campo'},
				{data:'opciones'},
				],
		lenguaje: [1, 2],
     	sinorden: [0,2],
    	invisible: [],
    	data: {tipo: 1},
		url: base_url + "categorias/getDataCategorias",
		
	}


	

	configurarDescargaExcel();
	cargarTabla(tabla_categorias);

	OnChangeImagen('form-canal-foto', 'imagen-preview2');
	resetearImagen();
	//eventoInputClaveIcon('#form-usuario-clave');
	limpiaModal();

	


	$("#btn-usuario-agregar-categoria").on('click', function(){
		//console.log("agregar");
		modalFlag = 1;
		limpiaModal();
		$("#form-notificacion-boton-imagen-us-cargar").show();
		cargarSelectCargos(1);
		//cargarSelectEmpresas(1);
	});


	form_usuario.id.submit(function (e){

		e.preventDefault();

		if (cambiofoto && !recortofoto) {
            _toastr('warning', 'Recorte la imagen para seguir con la operación', true);
            return false;
		}
		
		//console.log('editarrrrrr')
		
		let foto=form_usuario.foto.val();
		let idusuario=form_usuario.idusuario.val();
		//console.log("idusuario: ",idusuario)
		let mensaje="";

		let campo = form_usuario.campo.val();



		//validaciones
		if (campo.trim().length==0) {
			mensaje+='Falta llenar categoría <br> ';
		}

	

		// if(rut.trim().length==0){
		// 	mensaje+='Falta llenar rut <br> ';
		// 	}
		// if(nombre.trim().length==0){
		// 	mensaje+='Falta llenar nombre <br> ';
		// 	}
		// if(apaterno.trim().length==0){
		// 	mensaje+='Falta llenar apellido paterno <br>';
		// 	}
		/*if(amaterno.trim().length==0){
			mensaje+='Falta llenar apellido materno\n';
			}*/
            // if(email.trim().length==0){
			// 	mensaje+='Falta llenar email<br> ';
			// 	//console.log(clave.trim().length);
            //     }
            // if(tipo==null){
            //     mensaje+="Falta elegir tipo <br> ";
			// 	}
			// if(empresa==null){
            //     mensaje+="Falta elegir empresa <br> ";
			// 	}

			// 	if(clave.trim().length!==0 && (clave.trim().length<6 || clave.trim().length>12)){
			// 		mensaje+='La clave debe poseer de 6 a 12 elementos <br> ';
			// 	}


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

		
			formData.append('campo', campo);

		
			if (modalFlag == 2) { formData.append('idusuario', idusuario);}
			formData.append(token_name, token_hash);

			

			// let formulario = new FormData();
	        // 		formulario.append(token_name, token_hash);
			// 		formulario.append('id', idusuario);
			// 		console.log("id y cargo",idusuario);

			// getAjaxFormData(formulario, base_url+'canales/getUsuario').then(function(result){
			// 	result=JSON.parse(result);
			// 	console.log(result.data[0].CARGO); 
			// 	let checkTipo = result.data[0].CARGO;
			// });
				
			//console.log("/////////////////////////////\n"); 
			//for (var pair of formData.entries()) {
			//	console.log(pair[0]+ ', ' + pair[1]); 
			//}
			//console.log("/////////////////////////////\n"); 


            getAjaxFormData(formData, base_url+'categorias/guardarCategoria').then(function(result){
				//console.log("1",result);
                result=JSON.parse(result);
                if(result.proceso==1){
                    $("#modal-usuario").modal("hide");
                    recortofoto = false;
                    cambiofoto = false;
                    // resetInputClaveIcon('#form-usuario-clave');
                    // form_usuario.rut.val('');
                    // form_usuario.apaterno.val('');
                    // form_usuario.amaterno.val('');
                    // form_usuario.nombre.val('');
                    // form_usuario.email.val('');
                    // form_usuario.idusuario.val('');
                    // form_usuario.clave.val('');
					// form_usuario.tipo.val('').selectpicker('refresh');
					// form_usuario.empresa.val('').selectpicker('refresh');

					form_usuario.campo.val('');

					

				
					//console.log(" old: ",oldTipo);
					//console.log(" new: ",tipo);




					 cargarTabla(tabla_categorias);
				
                    if(modalFlag == 2){
                        
                        _toastr("success", "La categoría fue actualizada exitosamente", true);
                    }
                    if(modalFlag == 1){
                        _toastr("success", "La categoría se agregó exitosamente", true);
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

	$(document).on('click', ".borrar_usuario", function(){
		let id = $(this).attr("data-usuario");
		//console.log($(this).attr("data-usuario")); comentario

		Swal.fire({
				title: "Eliminar categoría",
	            text: "¿Está seguro de eliminar esta categoría?",
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




					getAjaxFormData(formulario, base_url + 'categorias/eliminarCategoria').then(function(result){
						result=JSON.parse(result);
						if(result.proceso==0){
							_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
						}else{
							_toastr("success", "La categoría fue eliminada exitosamente", true);
							
							
							
					
							cargarTabla(tabla_categorias);
							
						
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


	function cargarSelectCargos(opcion, id=''){
		//form_usuario.tipo.html('').selectpicker('refresh');
		var formulario = new FormData();
	    formulario.append(token_name, token_hash);
	    //getAjaxFormData(formulario, base_url + 'canales/getTipoCanal').then(function(result){
	    	//result=JSON.parse(result);
			//console.log("hola",result);
			
	    	if(opcion==1){
	    		//for(let c=0;c<result.length;c++){
		    		//console.log(result[c]);
		    	//	form_usuario.tipo.append('<option value='+result[c].ID_TIPO+'>'+result[c].CAMPO+'</option>');
				//}
				
		    	//form_usuario.tipo.val('')
				//form_usuario.tipo.selectpicker('refresh');
				defaultImagen();
				$("#modal-usuario").modal("show");
				$("#modal-usuario").find(".modal-title").text("Nueva Categoría");
				$("#modal-usuario").find(".button-title").text("Agregar");

				
	    	}else{
	    		
	    		form_usuario.foto.val('');
	    		// for(let c=0;c<result.length;c++){
		    	// 	//console.log(result[c]);
		    	// 	form_usuario.tipo.append('<option value='+result[c].ID_TIPO+'>'+result[c].CAMPO+'</option>');
		    	// }
		    	//form_usuario.tipo.selectpicker('refresh');
				$("#modal-usuario").modal("show");
				$("#modal-usuario").find(".modal-title").text("Editar Categoría");
				$("#modal-usuario").find(".button-title").text("Editar");
				let formulario = new FormData();

        		formulario.append(token_name, token_hash);
				formulario.append('id', id);


				form_usuario.idusuario.val(id);

        		getAjaxFormData(formulario, base_url+'categorias/getCategoria').then(function(result){
				
        			//console.log("1");
					result=JSON.parse(result);
        			if(result.errores.length==0){
						//console.log("2");
	        			result.data.map(function(ele, index){
						//	console.log("3");
							
							//console.log(ele);
	        				$("#imagen-preview2").attr('src', ele.LOGO);
	        				imgFotoActual=ele.LOGO;
	        				$("#btn-imagen-reset").hide();	
	        				//debugger
	        				//console.log(imgFotoActual, 'imagen actrual')
	        				//debugger
	        				//let nombre=ele.NOMBRE.split(' ');
	        				//form_usuario.foto.val(ele.FOTO);
	        				// form_usuario.rut.val(ele.RUT);
							// form_usuario.apaterno.val(ele.APELLIDOP);
							// form_usuario.amaterno.val(ele.APELLIDOM);
							// form_usuario.nombre.val(nombre[0]);
	        				// form_usuario.email.val(ele.EMAIL);
	        				// form_usuario.idusuario.val(ele.ID);
							// form_usuario.tipo.val(ele.IDCARGO).selectpicker('refresh');
							// form_usuario.empresa.val(ele.EMPRESA_ID).selectpicker('refresh');
							// oldTipo	=ele.IDCARGO;
							//console.log(ele);
							//console.log(ele.DESCRIPCION_CORTA);
							//console.log(ele.DESCRIPCION_LARGA);

							form_usuario.campo.val(ele.CAMPO);
						



							defaultImagen(true);

	        			});

	        			//console.log(result);
        			}
					
        		});

	    	}
	    	
	    	
	    //});
	}




	$(document).on("click",'.editar_usuario', function(){
	
		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-usuario");
		/*Swal.fire({
			title: "Editar Usuario",
	            text: "¿Está seguro de editar al usuario?",
	            icon: "warning",
	            buttons: {
	                ok: "Si",
	                no: 'No'
	            },
	        }).then(function(result){
	        	if(result=='ok'){*/
					cargarSelectCargos(2, id);
					//cargarSelectEmpresas(2, id);
	        		$("#form-notificacion-boton-imagen-us-cargar").show();
	        		//debugger
	        	/*}
			});*/
	});

});//fin $(function(){})

function limpiaModal(){
	
	// form_usuario.rut.val('');
	// form_usuario.apaterno.val('');
	// form_usuario.amaterno.val('');
	// form_usuario.nombre.val('');
	// form_usuario.email.val('');
	// form_usuario.idusuario.val('');
	// form_usuario.clave.val('');
	// form_usuario.empresa.val('');
	// form_usuario.tipo.val('').selectpicker('refresh');

	form_usuario.campo.val('');

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
		    data: data

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

		              /*  exportOptions: {
		                    action:
		                        function (options) {
		                            if (table.context.length) {
		                                var varibles_post_excel = data;
		                                var url_excel = info.url;
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
		                                return {body: jsonResult.data, header: jsonResult.columns};
		                            }
		                       }

		               }*/

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

	/*$('.dataTables_filter input').css("margin-right", "5px");
	$('.dataTables_processing').css("height", "50px");
	$('.dataTables_filter input').attr("placeholder", "Buscar");
	$('.dataTables_filter input').attr("class", "form-control");

	// $('.dt-buttons a').removeClass('dt-button buttons-excel buttons-html5').addClass('btn botonesgestion');
	// $('.dt-buttons').addClass('ajustebotonacivas');
	$('.dt-buttons button').removeClass('dt-button buttons-excel buttons-html5').addClass('btn bg-olive');*/

	// setTimeout(function() { 

	// 	let max_description=100;

	// 	for (let index = 1; index < document.getElementById("tabla-canales").rows.length; index++) {
	// 		d_larga=document.getElementById("tabla-canales").rows[index].childNodes[2].innerHTML;
	// 		if (d_larga.length>max_description){
	// 			document.getElementById("tabla-canales").rows[index].childNodes[2].innerHTML=d_larga.substring(0,max_description-3).concat('...');
	// 		} 
	// 	}


	// 	console.log("hola")
	// }, 200);

}

// function eventoInputClaveIcon(id) {
//     $(".input-clave").on("click", function () {
//     	//debugger
//         var input = $(id);
//         switch (input.prop("type")) {
//             case "text":
//                 $(this).find("i").removeClass("fa-eye").addClass("fa-eye-slash");
//                 input.prop("type", "password");
//                 break;
//             case "password":
//                 $(this).find("i").removeClass("fa-eye-slash").addClass("fa-eye");
//                 input.prop("type", "text");
//                 break;
//         }
//     });
// }

// function resetInputClaveIcon(id) {
//     var input_clave = $(".input-clave");
//     var input = $(id);
//     input_clave.find("i").removeClass("fa-eye").addClass("fa-eye-slash");
//     input.prop("type", "password");
// }



