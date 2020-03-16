// transform cropper dataURI output to a Blob which Dropzone accepts
var dataURItoBlob = function (dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/png'});
};


var modal_archivos = "#modal_archivos";

var dragndrop = null;
var id_dropzone = "#my-Archivos";
var myDropzone = null;

var imagenes_agregados = [];

//Dropzone.autoDiscover = false;
var c = 0;
var MAX_FILE = 12;

var maxImageWidth = 3840, maxImageHeight = 1024;
var minImagenWidth = 320;

Dropzone.autoDiscover = false;

Dropzone.prototype.defaultOptions.dictDefaultMessage = "Arrastra los archivos aquí para subirlos";
Dropzone.prototype.defaultOptions.dictFallbackMessage = "Su navegador no admite la carga de archivos con arrastrar y soltar.";
Dropzone.prototype.defaultOptions.dictFallbackText = "Utilice el formulario de respaldo a continuación para cargar sus archivos como en los días anteriores.";
Dropzone.prototype.defaultOptions.dictFileTooBig = "El archivo es demasiado grande ({{filesize}} MiB). Tamaño máximo de archivo: {{maxFilesize}} MiB.";
Dropzone.prototype.defaultOptions.dictInvalidFileType = "No puedes subir archivos de este tipo.";
Dropzone.prototype.defaultOptions.dictResponseError = "El servidor respondió con el código {{statusCode}} code.";
Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar carga";
Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "¿Estás seguro de que quieres cancelar esta carga?";
Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover archivo";
Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "No puedes subir más archivos.";
Dropzone.prototype.defaultOptions.dictMinFilesExceeded = "Tienes que subir desde 3 archivos";



$(document).ready(function(){

   

    
    $(document).on("hidden.bs.modal", ".modal-recortar", function (event) {
		$("body").addClass("modal-open");
	});
        

    $("#btn-usuario-agregar-contenido").on( 'click', function() {
       $("#modal_contenido").modal('show');
              });


  

    $('.dropzone').each(function () {
        var tipo = parseInt($(this).data("tipo"));
//    var options = $(this).attr('id').split('-');
//    var dropUrl = 'test' + options[1] + '.php';
//    var dropMaxFiles = parseInt(options[2]);
//    var dropParamName = 'file' + options[1];
//    var dropMaxFileSize = parseInt(options[3]);

        $(this).dropzone({
//        url: dropUrl,
//        maxFiles: MAX_FILE,
//        paramName: dropMaxFiles,
//        maxFilesSize: dropMaxFileSize
            dictDefaultMessage: false,
            paramName: "file",
            //maxFilesize: 5,
            parallelUploads:15,
            addRemoveLinks: true,
            uploadMultiple: false,
            //maxFiles: MAX_FILE,
            method: "POST",
            acceptedFiles: "image/jpeg, image/png",
            dictRemoveFile: "<span class='glyphicon glyphicon-trash' data-toggle='tooltip' data-placement='bottom' title='Eliminar'></span>",
            init: function () {
                this.hiddenFileInput.removeAttribute('multiple');
              //  console.log("init",this.hiddenFileInput);
                
           


            this.on("thumbnail", function (file) {
                    // Do the dimension checks you want to do
                    if (file.width < minImagenWidth || file.width > maxImageWidth && file.height > maxImageHeight) {
//                    file.rejectDimensions();
                        file.previewElement.remove();
                        this.removeFile(file);
                        _toastr("error", "Solo está permitido una resolución desde 320 x 1024 hasta 3840 x 1024 (2K)");
                        return false;
                    } else {
                        file.acceptDimensions();
                    }
             });



             $(document).on('click', ".dz-remove",function(e){
                $('.dz-message').remove();
            });
                
            
                myDropzone = this;

          
            

                     
                this.on("addedfile", function(file) { 
                   
               //     console.log("file",file);
                    var dateNow = new Date();

                    if( dragndrop == null || ( (dateNow.getTime() - dragndrop.getTime()) /1000) > 1){

                            dragndrop = dateNow;
                            $(file.previewElement).data("time", Date.now() );
                            var lastpreview = $(".dz-image-preview");

                    }else{
                        myDropzone.removeFile(file);
                    }
                    

                }); 
                


                this.on("sending", function (file, xhr, data) {
                    data.append(token_name, token_hash);
                });

                this.on('success', function (file) {

                    var $button = $('<a href="#" class="js-open-cropper-modal" style="text-align:center; cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="Cambiar" data-tipo="' + tipo + '" data-file-name-origen="' + file.name + '" data-file-name="' + file.dataURL + '"> <span class="glyphicon glyphicon-pencil"></span></a>');
                    
                    $(file.previewElement).append($button);

                    if (file.url) {
                        var a = document.createElement('a');
                        a.setAttribute('href', file.url);
                        a.setAttribute('download', file.name);
                       // a.setAttribute('class', 'dz-remove');
                       // a.setAttribute('class', 'dz-remove-count');
                        a.setAttribute('target', '_blank');
                        a.innerHTML = "<i class='fa fa-download' aria-hidden='true'></i>";
                        file.previewTemplate.appendChild(a);
                    }

                    //$(file.previewElement).append($button2);

                    if (file.lastModified) {
                        $button.click();
                    }
                    

                    $(file.previewElement).data("identificador", file.upload.uuid);


                    var lastpreview = $(".dz-image-preview");

                    // console.log("antes each");
                    $.each(lastpreview,function(key,preview){
							
                    //    console.log("identificador",($(this).data("identificador")));
                    });      

                    // console.log("despues each");
                    //console.log("success",this.hiddenFileInput);
                    this.hiddenFileInput.removeAttribute('multiple');

                });

                this.on("error", function (file, response) {
                    _toastr("error", response, true);
                    file.previewElement.remove();
                });
            }, accept: function (file, done) {
                file.acceptDimensions = done;
//            file.rejectDimensions = function () {
//                done("Solo está permitido hasta una resolución de 2048 x 1024 (2K)");
//            };
            }
        });


    });

  $(id_dropzone).sortable({
        items: '.dz-preview',
        cursor: 'move',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        tolerance: 'pointer'
    });


    $(id_dropzone).on('click', '.js-open-cropper-modal', function (e) {
        e.preventDefault();
        // var tipo = parseInt($(this).data('tipo'));
        var time = $(this).parent().data('time');
       
        var fileName = $(this).data('file-name');
        var nameOrigen = $(this).data('file-name-origen');
        var this_open_cropper_model = this;
        var modalTemplate =
                '<div class="modal fade modal-recortar" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog modal-lg" role="document">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<h4 class="modal-title">Recortar Imagen</h4>' +
                '</div>' +
                '<div class="modal-body">' +
                '<div class="image-container">' +
                '<img id="img-' + ++c + '" src="' + fileName + '">' +
                '</div>' +
                '</div>' +
                '<div class="modal-footer">' +
                '<button type="button" class="btn btn-warning rotate-left"><span class="fa fa-rotate-left"></span></button>' +
                '<button type="button" class="btn btn-warning rotate-right"><span class="fa fa-rotate-right"></span></button>' +
                '<button type="button" class="btn btn-warning scale-x" data-value="-1"><span class="fa fa-arrows-h"></span></button>' +
                '<button type="button" class="btn btn-warning scale-y" data-value="-1"><span class="fa fa-arrows-v"></span></button>' +
                '<button type="button" class="btn btn-warning reset"><span class="fa fa-refresh"></span></button>' +
                '<button type="button" class="btn btn-primary crop-upload">Recorta & Guardar</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

        var $cropperModal = $(modalTemplate);
        $cropperModal.modal({backdrop: 'static', keyboard: false});

        $cropperModal.modal('show').on("shown.bs.modal", function () {
         
               var cropper = new Cropper(document.getElementById('img-' + c), {

                width: 90,
                height: 160,
                minWidth: 256,
                minHeight: 256,
                maxWidth: 4096,
                maxHeight: 4096,
                zoomable: false,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
                autoCropArea: 1,
                aspectRatio: 1 / 2,
                movable: false,
                cropBoxResizable: true,
                rotatable: true
                });
            var $this = $(this);
            $this.on('click', '.crop-upload', function () {
                // get cropped image data
                var blob = cropper.getCroppedCanvas().toDataURL('image/png', 0.1);
                // transform it to Blob object
                var croppedFile = dataURItoBlob(blob);
                croppedFile.name = nameOrigen;


                $.each(myDropzone.getAcceptedFiles(),function(key,file){
                    if ($(file.previewElement).data('time') == time ){

                       // var key = myDropzone.getAcceptedFiles().findIndex(x => x.name === nameOrigen);
                        myDropzone.getAcceptedFiles()[key].dataURL = blob;
                        myDropzone.getAcceptedFiles()[key].size = croppedFile.size;  
                        return false;
                    }
                });
                      

                $(this_open_cropper_model).data('file-name', blob);
                $(this_open_cropper_model).attr('data-file-name', blob);
//                        $(this_open_cropper_model).parent().find('.dz-image')[0].innerHTML.attr("src", blob);
                $(this_open_cropper_model).parent().closest('.dz-preview').find('img').attr('src', blob);
                $(this_open_cropper_model).parent().closest('.dz-preview').find('img').css('height', 'auto');
                $(this_open_cropper_model).parent().closest('.dz-preview').find('img').css('width', 'auto');


                $this.modal('hide');
            })
                    .on('click', '.rotate-right', function () {
                        cropper.rotate(90);
                    })
                    .on('click', '.rotate-left', function () {
                        cropper.rotate(-90);
                    })
                    .on('click', '.reset', function () {
                        cropper.reset();
                    })
                    .on('click', '.scale-x', function () {
                        var $this = $(this);
                        cropper.scaleX($this.data('value'));
                        $this.data('value', -$this.data('value'));
                    })
                    .on('click', '.scale-y', function () {
                        var $this = $(this);
                        cropper.scaleY($this.data('value'));
                        $this.data('value', -$this.data('value'));
                    });
        });
    });











});





