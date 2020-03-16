 <div id="modal-usuario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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

                            <div class="col-lg-6 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-canal-descripcion-corta">Categoría</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="amaterno" class="form-control" id="form-categoria-campo"  maxlength="50" required />
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




<style>
    
</style>
