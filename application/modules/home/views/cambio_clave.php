
<body class="hold-transition  theme-oceansky bg-gradient-oceansky">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">	

            <div class="col-12">
                <div class="row justify-content-center no-gutters">
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="content-top-agile p-10">
                            <img src="<?=base_url()?>assets/images/impact-blanco.png" />					
                        </div>
                        <div class="p-30 rounded30 box-shadowed b-2 b-dashed">
                        <p class="login-box-msg">Es necesario realizar un cambio de clave</p>

                            <form id="formulario-cambio-clave" methos="POST">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                            <span class="input-group-text  bg-transparent text-white " ><i data-campo="cambio_clave" class="clave-visualizar fa fa-eye-slash"></i></span>                                        
                                        </div>
                                        <input type="text" data-tipo="clave" data-nombre="Contraseña" id="cambio_clave" name="clave" class="form-control" placeholder="Contraseña" autocomplete="off" required="">                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text  bg-transparent text-white " ><i data-campo="cambio_clave_confirmar" class="clave-visualizar fa fa-eye-slash"></i></span>                                        
                                        </div>
                                        <input type="text" data-tipo="clave" data-nombre="Confirmar contraseña" id="cambio_clave_confirmar" name="clave_confirmar" class="form-control password" placeholder="Confirmar contraseña" autocomplete="off" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 offset-md-2 text-center">
                                        <button type="submit" id="login-form_btn-submit" class="btn btn-info btn-block  mt-10">Actualizar</button>
                                    </div>
                                </div>
                            </form>														




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





