let modalFlag = 0;
let form_tipo={
	id:$('#formulario-tipo-publicidad'),
	idtipo:$('#form-tipo-publicidad-id'),
	nombre:$('#form-tipo-publicidad'),
}

$(function(){
	
	let tabla_tipo={
		id:'#tabla-tipo',
		columnas:[
				{data:'tipo'},
				{data:'opciones'},
				],
		lenguaje: [1],
     	sinorden: [0,1],
    	invisible: [],
    	data: {tipo: 1},
    	url: base_url + "publicidad/getDataTipoPublicidad"
	}
	
	configurarDescargaExcel();
	cargarTabla(tabla_tipo);
	limpiaModal();


	$("#btn-agregar-tipo").on('click', function(){

		modalFlag = 1;
		limpiaModal();
		mostrarModal(1);

	});

	form_tipo.id.submit(function (e){

		e.preventDefault();

		//e('editarrrrrr')
		let nombre=form_tipo.nombre.val();
		let idtipo=form_tipo.idtipo.val();
		let mensaje="";
		
		//validaciones

		if(nombre.trim().length==0){
			mensaje+='Falta llenar el tipo de publicidad <br> ';
			}
			if(nombre==null){
                mensaje+="Falta elegir el tipo de publicidad <br> ";
				}
            if(mensaje.length!=0){
                Swal.fire('Error de validación', mensaje, 'error');
                return;
				}

            //mandar datos al backend
            let formData = new FormData();
            formData.append('nombre', nombre);
			//modalFlag estados:  2 = Modal adición , 1 = Modal Agregar
		
			if (modalFlag == 2) { formData.append('idtipopublicidad', idtipo);}
			formData.append(token_name, token_hash);
            getAjaxFormData(formData, base_url+'publicidad/guardarTipoPublicidad').then(function(result){
                result=JSON.parse(result);
                if(result.proceso==1){
                    $("#modal-tipo-publicidad").modal("hide");
                    form_tipo.nombre.val('');
                    form_tipo.idtipo.val('');
					cargarTabla(tabla_tipo);
					document.getElementById("publicidad-actual").innerText = nombre;
                    if(modalFlag == 2){
                        _toastr("success", "El tipo de publicidad fue actualizado exitosamente", true);
                    }
                    if(modalFlag == 1){
                        _toastr("success", "El tipo de publicidad se agregó exitosamente", true);
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

	$(document).on('click', ".borrar_tipo_publicidad", function(){
		let id = $(this).attr("data-tipo-publicidad");
		Swal.fire({
				title: "Eliminar Tipo de Publicidad",
	            text: "¿Está seguro de eliminar el tipo de publicidad?",
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
	        		getAjaxFormData(formulario, base_url + 'publicidad/eliminarTipoPublicidad').then(function(result){
	        			result=JSON.parse(result);
	        			if(result.proceso==0){
	        				_toastr("success", "Ocurrio un error en el proceso, intente nuevamente", true);
	        			}else{
	        				_toastr("success", "El tipo de publicidad fue eliminado exitosamente", true);
	        				cargarTabla(tabla_tipo);
	        				
	        			}
	        			//console.log(result);
	        		});
	        	}
	        });
	});

	$(document).on("click",'.editar_tipo_publicidad', function(){
		modalFlag = 2;
		limpiaModal();
		let id = $(this).attr("data-tipo-publicidad");
			mostrarModal(2, id);

	});

});//fin $(function(){})



function limpiaModal(){
	
	form_tipo.nombre.val('');
	form_tipo.idtipo.val('');

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
		
			$("#modal-tipo-publicidad").modal("show");
			$("#modal-tipo-publicidad").find(".modal-title").text("Nuevo Tipo de Publicidad");
			$("#modal-tipo-publicidad").find(".button-title").text("Agregar");			
		}else{
			$("#modal-tipo-publicidad").modal("show");
			$("#modal-tipo-publicidad").find(".modal-title").text("Editar Tipo de Publicidad");
			$("#modal-tipo-publicidad").find(".button-title").text("Editar");
			let formulario = new FormData();

			formulario.append(token_name, token_hash);
			formulario.append('id', id);
			getAjaxFormData(formulario, base_url+'publicidad/getTipoPublicidad').then(function(result){
				
				result=JSON.parse(result);
				if(result.errores.length==0){
					result.data.map(function(ele, index){
						
						
						form_tipo.idtipo.val(ele.ID);
						form_tipo.nombre.val(ele.TIPO);

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
