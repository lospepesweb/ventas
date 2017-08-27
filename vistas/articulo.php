<?php 
//Activamos almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION['nombre'])){
  header('Location: login.html');
} else {
  require 'header.php';
  if($_SESSION['almacen'] == 1){ 
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
                          <h1 class="box-title">Artículos <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button> <a href="../reportes/rptarticulos.php" target="_blank"><button class="btn btn-info">Reporte</button></a></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoRegistros">
                        <table id="tblListado" class="table table-striped table-bordered table-compacted table-hover">
                          <thead>
                            <th><center>Opciones</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Categoría</center></th>
                            <th><center>Código</center></th>
                            <th><center>Stock</center></th>
                            <th><center>Imagen</center></th>
                            <th><center>Estado</center></th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th><center>Opciones</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Categoría</center></th>
                            <th><center>Código</center></th>
                            <th><center>Stock</center></th>
                            <th><center>Imagen</center></th>
                            <th><center>Estado</center></th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioRegistros">
                        <form name="formulario" id="formulario" method="post" >
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="nombre">Nombre(*):</label>
                            <input type="hidden" name="idarticulo" id="idarticulo">
                            <input type="text" name="nombre" id="nombre" maxlength="100" required class="form-control">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="idcategoria">Categoría(*):</label>
                            <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required></select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="stock">Stock(*):</label>
                            <input type="number" class="form-control" name="stock" id="stock" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="descripcion">Descripción:</label>
                            <input type="text" name="descripcion" id="descripcion" maxlength="256" class="form-control">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="imagen">Imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen">
                            <input type="hidden" name="imagenActual" id="imagenActual">
                            <img src="" width="150px" height="120px" id="imagenMuestra">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="codigo">Código:</label>
                            <input type="text" class="form-control" name="codigo" id="codigo">
                            <button class="btn btn-success" type="button" onclick="generarBarCode()">Generar</button>
                            <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                            <div id="print">
                              <svg id="barcode"></svg>
                            </div>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
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
<script src="../public/js/JsBarcode.all.min.js"></script>
<script src="../public/js/jquery.PrintArea.js"></script>
<script src="scripts/articulo.js"></script>
<?php 
}
ob_end_flush();
?>