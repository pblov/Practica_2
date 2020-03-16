

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1 class="text-white">
        Usuarios
        <small class="sub-info text-white">Gestión de personal</small>

      </h1>


      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="breadcrumb-item active"><a href="<?=base_url()?>usuarios">Usuarios</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Superadmin</h3>
                  <button type="button" data-id="1" class="btn btn-info btn-usuario-agregar-usuario" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Usuario</span></button>

                </div>
                <div class="box-body">
                  <div class="">
                  <!-- TABLA SUPERADMIN -->
                    <table id="tabla-superadmin" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Foto</th>
                               <th width="12%">RUT</th>
                               <th width="18%">Correo</th>
                               <th width="22%">Nombre</th>
                               <th width="18%">Empresa</th>
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

      <!-- TABLA ADMIN -->
      <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Admin </h3>
                  <button type="button" data-id="2" class="btn btn-info btn-usuario-agregar-usuario" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Usuario</span></button>

                </div>
                <div class="box-body">
                  <div class="">
                    <table id="tabla-admin" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Foto</th>
                               <th width="12%">RUT</th>
                               <th width="18%">Correo</th>
                               <th width="22%">Nombre</th>
                               <th width="18%">Empresa</th>
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

      <!-- TABLA EMPRESA -->
      <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Empresa </h3>
                  <button type="button" data-id="3" class="btn btn-info btn-usuario-agregar-usuario" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Usuario</span></button>

                </div>
                <div class="box-body">
                  <div class="">
                    <table id="tabla-empresa" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Foto</th>
                               <th width="12%">RUT</th>
                               <th width="18%">Correo</th>
                               <th width="22%">Nombre</th>
                               <th width="18%">Empresa</th>
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


  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
