

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1 class="text-white">
        Canales
        <small class="sub-info text-white">Gestión de canales</small>

      </h1>


      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="breadcrumb-item active"><a href="<?=base_url()?>canales">Canales</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- CANALES -->
        <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Canales</h3>

                                
                  <button type="button" class="btn btn-info mb-5" id="btn-canal-agregar" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Canal</span></button>

                </div>
                <div class="box-body">
                  <div class="">

                
                  <!-- TABLA CANALES -->
                  <table style="width:100%" id="tabla-canales" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Logo</th>
                               <th width="12%">Nombre</th>
                               <th width="27%">Descripción Corta</th>
                               <th width="41%">Descripción Larga</th>
                               <th width="8%">Contenido</th>
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
      <!-- CANALES -->



           <!-- CONTENIDOS -->
           <div class="row vista-contenido">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Contenidos</h3> 
                  <small style="font-size: 116%;" id="canal-actual" class="sub-info "> Seleccione un canal</small>

                  <button type="button" class="btn btn-info mb-5" id="btn-contenido-agregar" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Contenido</span></button>

                </div>
                <div class="box-body">
                  <div class="">


                                
                  <!-- TABLA CANAL-CONTENIDO -->
                    <table style="width:100%" id="tabla-contenidos" class="table table-striped table-bordered base-style display table-responsive">
                      <thead>
                        <tr>
                            <th width="10%">Nombre</th>
                            <th width="36%">Descripción</th>
                            <th width="30%">Escena</th>
                            <th width="10%">Clave</th>
                            <th width="8%">Visibilidad</th>
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
      <!-- CONTENIDOS- -->

  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


















