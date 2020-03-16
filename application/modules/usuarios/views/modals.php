 <div id="modal-usuario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formulario-usuario" class="form-element" autocomplete="off">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 " style="text-align: center">
                                <div class="form-group last centrado">
                                    <label for="fotoproducto">Image</label><br>
                                    <p><div class="fileinput" >
                                        <div>
                                            <img alt="" style="max-width:200px; max-height: 200px;" id="imagen-preview2" >
                                        </div>
                                        <div><br>
                                            <span class="btn btn-outline-secondary btn-sm btn-file" id="form-notificacion-boton-imagen-us-cargar">
                                                <span id="btn-imagen-cargar-nueva">Cargar imagen</span>
                                                <span id="btn-imagen-cambiar">Cambiar imagen</span>
                                                <input type="file" name="form-usuario-foto" id="form-usuario-foto" accept="image/jpeg, image/png"> </span>
                                            <span class="btn btn-outline-secondary btn-sm " id="btn-imagen-aceptar" onclick="terminarCrop('imagen-preview2')">Aceptar</span>
                                            <span class="btn btn-outline-danger btn-sm fileinput-exists" id="btn-imagen-reset" data-dismiss="fileinput">Eliminar</span>
                                        </div>
                                    </div></p>
                                </div>
                            </div>
                        </div>
                        <div class="row  mt-3">

                             <div class="col-sm-4">
                                 <div class="form-group form-custom-icon-left" >
                                    <label for="form-usuario-nombre">Nombre</label>
                                    <span class="form-custom-icon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="nombre" class="form-control" id="form-usuario-nombre"  maxlength="50" required />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-usuario-apaterno">Apellido Paterno</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="apaterno" class="form-control" id="form-usuario-apaterno"  maxlength="50" required />
                                </div>
                            </div>

                             <div class="col-sm-4 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-usuario-amaterno">Apellido Materno</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="amaterno" class="form-control" id="form-usuario-amaterno"  maxlength="50" required />
                                </div>
                            </div>



                            


                        </div>
                        <div class="row  mt-3 mb-3">

                            

                            <div class="col-sm-4">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-usuario-rut">RUT</label>
                                    <span class="form-custom-icon" ><i class="fa fa-id-card"></i></span>
                                    <input type="text" name="rut" class="form-control rutformat" id="form-usuario-rut" maxlength="15" required  />
                                </div>
                            </div>


                            <div class="col-sm-4 form-actions">
                               <div class="form-group form-custom-icon-left" >
                                    <label for="form-usuario-email">Correo</label>
                                    <span class="form-custom-icon" ><i class="fa fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" id="form-usuario-email"  maxlength="100" required />
                                </div>
                            </div>



                            <div class="col-sm-4">
                                <div class="form-group form-custom-icon-left">
                                    <label>Tipo Usuario</label>
                                    <select class="form-control selectpicker select2" id="form-usuario-tipo" data-live-search="true" name="tipo_usuario"></select>
                                </div>
                            </div>

                        </div>



                        <div class="row  mt-3 mb-3">


                           <div class="col-sm-4">
                                    <div class="form-group form-custom-icon-left empresa-selector">
                                        <label>Empresa</label>
                                        <select class="form-control selectpicker select2" id="form-usuario-empresa" data-live-search="true" name="empresa" data-dropup-auto="false"></select>
                                    </div>
                            </div>

                            <div class="col-sm-4 form-actions">
                                    <label for="form-usuario-clave">Contraseña</label>
                                    <span class="form-custom-icon input-clave" data-input="form-usuario-clave" ><i class="fa fa-eye-slash"></i></span>
                                    <input type="password" name="clave" class="form-control" id="form-usuario-clave"  maxlength="255"/>
                            </div>

                           
                        <div class="row mt-3 mb-3">
                     
                            
                         </div>  

                            
                            



                        </div>


                        <div class="row mt-3 mb-3">

                            
                        </div>  

                        
                            


                                                    
                    </div>
                    </div>
                        
                        <div class="row mt-3 mb-3">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                 <input type='hidden' id="form-usuario-id" />    
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
