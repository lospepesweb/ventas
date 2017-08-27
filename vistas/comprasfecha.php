<?php 
//Activamos almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION['nombre'])){
  header('Location: login.html');
} else {
  require 'header.php';

  if($_SESSION['consultac'] == 1){
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Consulta de compras por fecha</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoRegistros">
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="fecha_inicio">Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="fecha_fin">Fecha Inicio</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <table id="tblListado" class="table table-striped table-bordered table-compacted table-hover">
                          <thead>
                            <th><center>Fecha</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Proveedor</center></th>
                            <th><center>Comprobante</center></th>
                            <th><center>Número</center></th>
                            <th><center>Total Compra</center></th>
                            <th><center>Impuesto</center></th>
                            <th><center>Estado</center></th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th><center>Fecha</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Proveedor</center></th>
                            <th><center>Comprobante</center></th>
                            <th><center>Número</center></th>
                            <th><center>Total Compra</center></th>
                            <th><center>Impuesto</center></th>
                            <th><center>Estado</center></th>
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
require 'footer.php'; 
?>
<script src="scripts/comprasfecha.js"></script>
<?php 
} 
ob_end_flush();
?>