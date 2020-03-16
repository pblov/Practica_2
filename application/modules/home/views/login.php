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
                            <form action="../index.html" method="post" id="login-form">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent text-white"><i class="fa fa-envelope"></i></span>
                                        </div>
                                        <input type="email" data-tipo="correo" data-nombre="correo" id="correo" name="correo" class="form-control" placeholder="Correo" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text  bg-transparent text-white " ><i data-campo="clave" class="clave-visualizar fa fa-eye-slash"></i></span>
                                            
                                        </div>
                                        <input type="password" minlength="6" maxlength="12" data-tipo="clave" data-nombre="Contraseña" id="clave" name="clave" class="form-control" placeholder="Contraseña" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="checkbox text-white">
                                            <input type="checkbox" id="basic_checkbox_1" class="chk-col-warning" >
                                            <label for="basic_checkbox_1">Recordar</label>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-8">
                                        <div class="fog-pwd text-right mtrec">
                                            <a href="javascript:void(0)" id="olvido_clave" class="text-white hover-info"><i class="ion ion-locked"></i> Recuperar contraseña</a><br>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-12  text-center">
                                        <button type="submit" id="login-form_btn-submit" class="btn btn-info btn-block  mt-10">Iniciar sesión</button>
                                    </div>
                            
                            </form>                     
                                    <!-- /.col -->
                                       <!-- GOOGLE BUTTON -->
                                    <div class="col-md-12  text-center">
                                         <div id="google-button"class="g-signin2 btn-light mt-3" onclick="ClickLogin()" data-width="0" data-onsuccess="onSignIn" data-prompt="select_account"></div>
                                    </div>
                                    <!-- /.col  -->
                                
                                    <!-- Facebook Button -->
                                    <div class="col-md-12 text-center mt-2">
                                        <button  onclick="javascript:login(event);" class="btn btn-face btn-block  mt-10">
                                            <i class="fa fa-facebook-official icon" aria-hidden="true"></i>    
                                            <p class="text-fa"> Acceder con Facebook </p>
                                        </button>
                                    <div>
                                    <!-- /.col -->
                                </div>
                            														

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>