

<!-- Modal de Publicidad-->
 <div id="modal-publicidad" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="formulario-publicidad" class="form-element" autocomplete="off">
                <div class="modal-body">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-12 " style="text-align: center">
                                <div class="form-group last centrado">
                                    <label for="fotoproducto">Imagen</label><br>
                                    <p><div class="fileinput" >
                                        <div>
                                            <img alt="" style="max-width:200px; max-height: 200px;" id="imagen-preview2" >
                                        </div>
                                        <div><br>
                                            <span class="btn btn-outline-secondary btn-sm btn-file" id="form-notificacion-boton-imagen-us-cargar">
                                                <span id="btn-imagen-cargar-nueva">Cargar imagen</span>
                                                <span id="btn-imagen-cambiar">Cambiar imagen</span>
                                                <input type="file" name="form-publicidad-foto" id="form-publicidad-foto" accept="image/jpeg, image/png"> </span>
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
                                    <label for="form-publicidad-titulo">Título</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span>
                                    <input type="text" name="tituloPublicidad" class="form-control" id="form-publicidad-titulo"  maxlength="50" required />
                                    
                                
                                </div>
                            </div>

                            <div class="col-sm-6 ">
                                        <div class="form-group form-custom-icon-left " >
                                            <label for="form-minuta-fecha">Fecha </label>
                                            <span class="form-custom-icon"><i class="fa fa-calendar"></i></span>
                                            <small style="margin-left:3px;"><b><i>(Fecha inicio - Fecha fín)</i></b></small>
                                            <input type="text" data-toggle="daterangepicker" class="form-control form-custom-input readonly" id="selector-fecha"  placeholder="Seleccionar una fecha" autocomplete="off">
                                        </div>
                            </div>
                        </div> 

                        <div class="row mt-3 mb-3">
                            <div class="col-sm-4 form-actions">
                                        <div class="form-group form-custom-icon-left categoria-selector">
                                            <label>Usuario</label>
                                            <select class="form-control selectpicker select" id="form-publicidad-usuario" multiple  data-live-search="true" name="usuario" data-max-options="1" data-dropup-auto="false"></select>
                                        </div>
                            </div>

                            <div class="col-sm-4 form-actions">
                                        <div class="form-group form-custom-icon-left categoria-selector">
                                            <label>Tipo de Moneda</label>
                                            <select class="form-control selectpicker select" id="form-publicidad-moneda" multiple  data-live-search="true" name="MONEDA" data-max-options = "1" data-dropup-auto="false">
                                                
                                            </select>
                                        </div>
                            </div>

                            <div class="col-sm-4 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-publicidad-monto">Monto</label>
                                    <span class="form-custom-icon" ><i class="	fa fa-money"></i></span>
                                    <input type="text"  name="montoPublicidad" class="form-control" id="form-publicidad-monto"  />
                                    
                                    
                                </div>
                            </div>

                           

                            
                        </div>

                        <div class="row mt-3 mb-3">


                                <div class="col-sm-4 form-actions">
                                        <div class="form-group form-custom-icon-left categoria-selector">
                                            <label>Tipo de Publicidad</label>
                                            <select class="form-control selectpicker select" id="form-publicidad-tipo" multiple  data-live-search="true" name="categoria" data-max-options="1" data-dropup-auto="false"></select>
                                        </div>
                                </div>

                                <div class="col-sm-4 form-actions">
                                        <div class="form-group form-custom-icon-left categoria-selector">
                                            <label>Canal</label>
                                            <select class="form-control selectpicker select" id="form-publicidad-canal" multiple  data-live-search="true" name="categoria" data-max-options="1" data-dropup-auto="false"></select>
                                        </div>
                                </div>

                            
        
                                <div class="col-sm-4 form-actions">
                                        <div class="form-group form-custom-icon-left categoria-selector">
                                            <label>Contenido</label>
                                            <select class="form-control selectpicker select" id="form-publicidad-contenido" multiple  data-live-search="true" data-max-options="1" name="categoria" data-dropup-auto="false"></select>
                                        </div>
                                </div>

                               

                        </div>




                        <div class="row mt-3 mb-3">

                            <div class="col-sm-12 form-actions">
                                <div class="form-group form-custom-icon-left" >
                                    <label for="form-publicidad-cuerpo">Cuerpo</label>
                                    <span class="form-custom-icon" ><i class="fa fa-pencil"></i></span> 
                                    <textarea  name="textarea" rows="10" style=" width: 100%; " id="form-publicidad-cuerpo"></textarea>
                                </div>
                            </div>
                        </div>  



                        <div class="row mt-3 mb-3">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                 <input type='hidden' id="form-publicidad-id" />    
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



<script>




</script>