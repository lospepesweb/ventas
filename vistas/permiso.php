<?php 
//Activamos almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION['nombre'])){
  header('Location: login.html');
} else {
  if($_SESSION['acceso'] == 1){
    require 'header.php'; ?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Permisos <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoRegistros">
                        <table id="tblListado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead> 
                            <th><center>Nombre</center></th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th><center>Nombre</center></th>
                          </tfoot>
                        </table>
                    </div>
                  
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php 
  } else {
    require 'noacceso.php';
  }
require 'footer.php'; ?>
<script src="scripts/permiso.js"></script>
<?php 
} 

ob_end_flush();

?>