

<!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1 class="text-white">
      Categorías
        <small class="sub-info text-white">Gestión de categorías</small>

      </h1>


      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="breadcrumb-item active"><a href="<?=base_url()?>categorias">Categorías</a></li>
        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Categorías</h3>
                  <button type="button" class="btn btn-info mb-5" id="btn-usuario-agregar-categoria" style="float:right;padding: 6;"><i class="fa fa-plus"></i> Agregar <span class="sub-info">Categoría</span></button>

                </div>
                <div class="box-body">
                  <div class="">
                  <!-- TABLA SUPERADMIN -->
                    <table id="tabla-categorias" class="table table-striped table-bordered base-style display table-responsive">
                       <thead>
                           <tr>
                               <th width="8%">Logo</th>
                               <th width="100%">Categoría</th>
                               <th width="25%">Opciones</th>
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
