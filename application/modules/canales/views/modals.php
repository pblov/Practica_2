 <!-- modal canal y contenido  -->


 <div id="modal-canal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formulario-canal" class="form-element" autocomplete="off">
                <div class="modal-body">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12 " style="text-align: center">
                                <div class="form-group last centrado">
                                    <label for="fotoproducto">Logo</label><br>
                                    <p><div class="fileinput" >
                                        <div>
                                            <img alt="" style="max-width:200px; max-height: 200px;" id="imagen-preview2" >
                                        </div>
                                        <div><br>
                                            <span class="btn btn-outline-secondary btn-sm btn-file" id="form-notificacion-boton-imagen-us-cargar">
                                                <span id="btn-imagen-cargar-nueva">Cargar imagen</span>
                                                <span id="btn-imagen-cambiar">Cambiar imagen</span>
                                                <input type="file" name="form-canal-foto" id="form-canal-foto" accept="image/jpeg, image/png"> </span>
                                            <span class="btn btn-outline-secondary btn-sm " id="btn-imagen-aceptar" onclick="terminarCrop('imagen-preview2')">Aceptar</span>
                                            <span class="btn btn-outline-danger btn-sm fileinput-exists" id="btn-imagen-reset" data-dismiss="fileinput">Eliminar</span>
                                        </div>
                                    </div></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 mb-3">

                            <div class="col-sm-6 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-canal-nombre">Nombre</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="nombreCanal" class="form-control" id="form-canal-nombre"  maxlength="50" required />
                                    
                                
                                </div>
                            </div>
                        </div> 


                         
                        <div class="row mt-3 mb-3">

                            <div class="col-sm-6 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-canal-descripcion-corta">Descripción Corta</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="descripcion-corta" class="form-control" id="form-canal-descripcion-corta"  maxlength="50" required />
                                </div>
                            </div>
                        </div> 

                        <div class="row mt-3 mb-3">

                            <div class="col-sm-12 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-canal-descripcion-larga">Descripción Larga</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span> 
                                    <textarea  name="textarea" rows="10" style=" width: 100%; " id="form-canal-descripcion-larga"></textarea>

                                </div>
                            </div>
                        </div>  



                        <div class="row mt-3 mb-3">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                 <input type='hidden' id="form-canal-id" />    
                                 <button type="submit" class="btn btn-info btn-block mt-4 button-title"></button>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>




                    </div>
                </div>
             </form>   
        </div>
    </div>
</div> 









<!-- MODAL CONTENIDO -->
 <div id="modal-contenido" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                
                
                    

                    <h4 class="modal-title" style="width: 200px; "></h4>



                        <div class="container">
                            <div class="row">
                                <div class="col text-right">
                                    <form>
                                        <label class="" for="switch-btn">¿Visible el contenido?</label>
                                        <button id="switch-btn"type="button" class="btn btn-sm btn-toggle btn-info active" data-toggle="button" aria-pressed="true" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>


                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                
               
                    
               
            
            </div>

          

            
            <form id="formulario-contenido" class="form-element" autocomplete="off">
                <div class="modal-body">
                    <div class="col-md-12">

        

                         
                        <div class="row mt-3 mb-3">

                            <div class="col-sm-4 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-contenido-titulo">Título</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="titulo" class="form-control" id="form-contenido-titulo"  maxlength="50" required />
                                </div>
                            </div>


                            <div class="col-sm-4 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-contenido-escena">Escena</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="escena" class="form-control" id="form-contenido-escena"  maxlength="50" required />
                                </div>
                            </div>

                         
    
                            <div class="col-sm-4 form-actions">
                                    <div class="form-group form-custom-icon-left categoria-selector">
                                        <label>Categoría</label>
                                        <select class="form-control selectpicker select" id="form-contenido-categoria" multiple  data-live-search="true" name="categoria" data-dropup-auto="false"></select>
                                    </div>
                            </div>



                        </div> 

                        <div class="row mt-3 mb-3">
                            <div class="col-sm-12 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-contenido-descripcion">Descripción</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span> 
                                    <textarea  name="textarea" rows="10" style=" width: 100%; " id="form-contenido-descripcion"></textarea>

                                </div>
                            </div>
                        </div>  

                        

                        <div class="row mt-3 mb-3">

                            <div class="col-sm-4 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-contenido-contraseña">Contraseña</label>
                                    
                                    <span class="form-custom-icon input-clave" ><i class="fa fa-eye-slash"></i></span>

                                    <button id="switchClave"type="button" class="btn btn-sm btn-toggle btn-info active" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                    </button>

                                    <input type="password" name="clave" class="form-control" id="form-contenido-contraseña"  maxlength="20" />
                                    
                                  
                                    
                                    
                                
                                </div>
                            </div>


                            <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                            </label>


                        </div> 

                        <div class="row mt-3 mb-3">
                            <div class="col-md-12 " style="text-align:left">
                                <div class="form-group form-custom-icon-left">
                                   <label for="form-contenido-adjuntar">Imagen Destacada</label>
                                   <span class="form-custom-icon" ><i class="fa fa-image"></i></span>
                                    <p><div class="fileinput" >
                                            <img alt="" style="max-width:512px; max-height: 400px; border: 1px solid #ccc;" id="imagen-preview-foto" >
                                        <div style="text-align:center;"><br>
                                            <span class="btn btn-outline-secondary btn-sm btn-file" id="form-notificacion-boton-imagen-us-cargar-foto">
                                                <span id="btn-imagen-cargar-nueva-foto">Cargar imagen</span>
                                                <span id="btn-imagen-cambiar-foto">Cambiar imagen</span>
                                                <input type="file" name="form-contenido-foto" id="form-contenido-foto" accept="image/jpeg, image/png"> </span>
                                            <span class="btn btn-outline-secondary btn-sm " id="btn-imagen-aceptar-foto" onclick="terminarCropContenido('imagen-preview-foto')">Aceptar</span>
                                            <span class="btn btn-outline-danger btn-sm fileinput-exists" id="btn-imagen-reset-foto" data-dismiss="fileinput">Eliminar</span>
                                        </div>
                                    </div></p>
                                </div>
                            </div>
                        </div>



                    </form>   

                        <!-- Sección dropzone -->
                    
                        <div class="row mt-3 mb-3">       
                            <div class="col-sm-12 form-actions">
                                <div class="form-group form-custom-icon-left">
                                    <label for="form-contenido-adjuntar">Adjuntar Contenido</label>
                                    <span class="form-custom-icon" ><i class="fa fa-image"></i></span>
                                    <br>
                                    <small><i>Haga clic en el espacio blanco para subir una imagen.</i></small>
                                    <form action="#" class="dropzone dropzone-area" id="my-Archivos" data-tipo="1"> 
                                        <div class="dz-message">Haga clic para subir imagenes</div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /. fin sección dropzone-->
                        
                        <div class="row mt-3 mb-3">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                 <input type='hidden' id="form-contenido-id" />    
                                 <button type="submit" class="btn btn-info btn-block mt-4 button-title"></button>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>




                    </div>
                </div>
             </form>   
        </div>
    </div>
</div> 



<!-- MODAL CONTENIDO -->




<style>
    
</style>
