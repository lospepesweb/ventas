<?php //Activamos almacenamiento en el buffer
ob_start();
session_start();

if(!isset($_SESSION['nombre'])){
  header('Location: login.html');
} else {
  if($_SESSION['acceso'] == 1){
    require 'header.php';
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
                          <h1 class="box-title">Usuarios <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
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
                            <th><center>Documento</center></th>
                            <th><center>Número</center></th>
                            <th><center>Teléfono</center></th>
                            <th><center>Correo electrónico</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Foto</center></th>
                            <th><center>Estado</center></th>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                            <th><center>Opciones</center></th>
                            <th><center>Nombre</center></th>
                            <th><center>Documento</center></th>
                            <th><center>Número</center></th>
                            <th><center>Teléfono</center></th>
                            <th><center>Correo electrónico</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Foto</center></th>
                            <th><center>Estado</center></th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" id="formularioRegistros">
                        <form name="formulario" id="formulario" method="post" >
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre">Nombre (*):</label>
                            <input type="hidden" name="idusuario" id="idusuario">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" required >
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="tipo_documento">Tipo documento (*):</label>
                            <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
                              <option value="DNI">DNI</option>
                              <option value="CUIT">CUIT</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="num_documento">Número (*):</label>
                            <input type="text" class="form-control" name="num_documento" id="num_documento" maxlength="20" required >
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="70" >
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" name="telefono" id="telefono" maxlength="20" class="form-control">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="email">Correo electrónico:</label>
                            <input type="email" name="email" id="email" maxlength="50" class="form-control">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="cargo">Cargo:</label>
                            <input type="text" name="cargo" id="cargo" maxlength="20" class="form-control">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="login">Usuario (*):</label>
                            <input type="text" name="login" id="login" maxlength="20" class="form-control" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="clave">Clave (*):</label>
                            <input type="password" name="clave" id="clave" maxlength="64" class="form-control" required>
                            <input type="hidden" id="claveActual" name="claveActual">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="permisos">Permisos:</label>
                            <ul style="list-style: none;" id="permisos">
                               
                            </ul>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="imagen">Imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen">
                            <input type="hidden" name="imagenActual" id="imagenActual">
                            <img src="" width="150px" height="120px" id="imagenMuestra">
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
<script src="scripts/usuario.js"></script>
<?php 
} 

ob_end_flush();

?>