

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1 class="text-white">
        Publicidad
        <small class="sub-info text-white">Gestión de publicidad</small>

      </h1>


      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="breadcrumb-item active"><a href="<?=base_url()?>publicidad">Publicidad</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- TIPO PUBLICIDAD -->
            <div class="row">
                <div class="col-sm-12 col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Tipo de publicidad</h3>
                      <!-- <button type="button" class="btn btn-info" id="btn-agregar-tipo" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Tipo</span></button> -->
                    </div>
                    <div class="box-body">
                      <div class="">
                        <table id="tabla-tipo-publicaciones" class="table table-striped table-bordered base-style display table-responsive">
                          <thead>
                              <tr>
                                  <th width="88%">Tipo</th>
                                  <th width="12%">Publicidades disponibles</th>
                              </tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>
                      </div>
                    </div>
                  </div>

                </div>
            </div>
        <!-- TIPO PUBLICIDAD -->

        <!-- PUBLICIDAD -->
        <div class="row vista-publicidad">

        
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Publicidad</h3>
                  <small style="font-size: 116%;" id="publicidad-actual" class="sub-info "> Seleccione una publicidad</small>
                                
                  <button type="button" class="btn btn-info mb-5" id="btn-publicidad-agregar" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Publicidad</span></button>

                </div>
                <div class="box-body">
                  <div class="">


                
                  <!-- TABLA PUBLICIDAD -->
                  <table style="width:100%" id="tabla-publicidad" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Imagen</th>
                               <th width="8%">Título</th>
                               <th width="16%">Cuerpo</th>
                               <th width="7%">Usuario</th>
                               <th width="7%">Tipo</th>                               
                               <th width="8%">Moneda</th>
                               <th width="4%">Monto</th>
                               <th width="7%">Canal</th>
                               <th width="7%">Contenido</th>
                               <th width="12%">Fecha Inicio</th>
                               <th width="12%">Fecha Fín</th>
                               <th width="4%">Opciones</th>
                           </tr>
                       </thead>
                       <tbody>

                       </tbody>
                  </table>

      
                        
                  </div>
                </div>
              </div>

            </div>
        </div>
      <!--  FIN PUBLICIDAD -->


  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


















