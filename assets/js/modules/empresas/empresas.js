let mensaje_preview_default=base_url+'assets/images/default.png';
let default_image='assets/images/empresas/default.png';
let cropeo_foto = null;
let recortofoto = false;
let cambiofoto = false;
let imgFotoActual="";
let modalFlag = 0;
let form_empresas={
	id:$('#formulario-empresas'),
	idempresas:$('#form-empresas-id'),
	foto:$('#form-empresas-foto'),
	nombre:$('#form-empresas-nombre'),
}

$(function(){
	
	let tabla_empresas={
		id:'#tabla-empresas',
		columnas:[
				{data:'foto'},
				{data:'empresa'},
				{data:'opciones'},
				],
		lenguaje: [1],
     	sinorden: [0,2],
    	invisible: [],
    	data: {tipo: 1},
    	url: base_url + "empresas/getDataEmpresas"
	}
	
	configurarDescargaExcel();
	cargarTabla(tabla_empresas);
	OnChangeImagen('form-empresas-foto', 'imagen-preview2');
	resetearImagen();
	limpiaModal();


	$("#btn-empresas-agregar-empresas").on('click', function(){

		modalFlag = 1;
		limpiaModal();
		$("#form-notificacion-boton-imagen-us-cargar").show();
		mostrarModal(1);

	});

	form_empresas.id.submit(function (e){

		e.preventDefault();

		if (cambiofoto && !recortofoto) {
            _toastr('warning', 'Recorte la imagen para seguir con la operación', true);
            return false;
		}
		
		//console.log('editarrrrrr')
		let nombre=form_empresas.nombre.val();
		let foto=form_empresas.foto.val();
		let idempresas=form_empresas.idempresas.val();
		let mensaje="";
		
		//validaciones

		if(nombre.trim().length==0){
			mensaje+='Falta llenar empresa <br> ';
			}
			if(nombre==null){
                mensaje+="Falta elegir empresa <br> ";
				}
            if(mensaje.length!=0){
                Swal.fire('Error de validación', mensaje, 'error');
                return;
				}

            //mandar datos al backend
            let formData = new FormData();
            formData.append('nombre', nombre);
            //formData.append('foto',  foto);
			formData.append('foto', $("#imagen-preview2").prop('src'));
			//modalFlag estados:  2 = Modal adición , 1 = Modal Agregar
		
			if (modalFlag == 2) { formData.append('idempresas', idempresas);}
			formData.append(token_name, token_hash);
            getAjaxFormData(formData, base_url+'empresas/guardarEmpresas').then(function(result){
                result=JSON.parse(result);
                if(result.proceso==1){
                    $("#modal-empresas").modal("hide");
                    recortofoto = false;
                    cambiofoto = false;
                    form_empresas.nombre.val('');
                    form_empresas.idempresas.val('');
                    cargarTabla(tabla_empresas);
                    if(modalFlag == 2){
                        
                        _toastr("success", "La empresa fue actualizada exitosamente", true);
                    }
                    if(modalFlag == 1){
                        _toastr("success", "La empresa se agregó exitosamente", true);
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

	$(document).on('click', ".borrar_empresas", function(){
		let id = $(this).attr("data-empresas");
		Swal.fire({
				title: "Eliminar Empresa",
	            text: "¿Está seguro de eliminar la empresa?",
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
	        		getAjaxFormData(formulario, base_url + 'empresas/eliminarEmpresas').then(function(result){
	        			result=JSON.parse(result);
	        			if(result.proceso==0){
	        				_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
	        			}else{
	        				_toastr("success", "La empresa fue eliminada exitosamente", true);
	        				cargarTabla(tabla_empresas);
	        				
	        			}
	        			//console.log(result);
	        		});
	        	}
	        });
	});

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



	$(document).on("click",'.editar_empresas', function(){
		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-empresas");
			$("#form-notificacion-boton-imagen-us-cargar").show();
			mostrarModal(2, id);

	});

});//fin $(function(){})


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

function limpiaModal(){
	
	form_empresas.nombre.val('');
	form_empresas.idempresas.val('');

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
	//                console.log(this.context);
	        var varibles_post_excel = this.ajax.params();
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
	//                console.log(jsonResult.columns);
		//console.log(jsonResult)
        return {body: jsonResult.data, header: jsonResult.columns};
    }
});
}


function mostrarModal(opcion, id=''){

	if(opcion==1){		
		
			defaultImagen();
			$("#modal-empresas").modal("show");
			$("#modal-empresas").find(".modal-title").text("Nueva Empresa");
			$("#modal-empresas").find(".button-title").text("Agregar");			
		}else{
			form_empresas.foto.val('');
			$("#modal-empresas").modal("show");
			$("#modal-empresas").find(".modal-title").text("Editar Empresa");
			$("#modal-empresas").find(".button-title").text("Editar");
			let formulario = new FormData();

			formulario.append(token_name, token_hash);
			formulario.append('id', id);
			getAjaxFormData(formulario, base_url+'empresas/getEmpresas').then(function(result){
				
				result=JSON.parse(result);
				if(result.errores.length==0){
					result.data.map(function(ele, index){
						$("#imagen-preview2").attr('src', ele.LOGO);
						imgFotoActual=ele.LOGO;
						$("#btn-imagen-reset").hide();	
						form_empresas.idempresas.val(ele.ID);
						form_empresas.nombre.val(ele.EMPRESA_NOMBRE);
						defaultImagen(true);

					});

					//console.log(result);
				}
				
			});

		}
		
		
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
		    {"bVisible": false, "aTargets": info.invisible}
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
	});

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
}
