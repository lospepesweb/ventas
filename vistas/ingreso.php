<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['compras']==1)
{
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
                          <h1 class="box-title">Ingreso <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoRegistros">
                        <table id="tblListado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th><center>Opciones</center></th>
                            <th><center>Fecha</center></th>
                            <th><center>Proveedor</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Documento</center></th>
                            <th><center>Número</center></th>
                            <th><center>Total Compra</center></th>
                            <th><center>Estado</center></th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th><center>Opciones</center></th>
                            <th><center>Fecha</center></th>
                            <th><center>Proveedor</center></th>
                            <th><center>Usuario</center></th>
                            <th><center>Documento</center></th>
                            <th><center>Número</center></th>
                            <th><center>Total Compra</center></th>
                            <th><center>Estado</center></th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioRegistros">
                        <form name="formulario" id="formulario" method="POST">
                          <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <label>Proveedor(*):</label>
                            <input type="hidden" name="idingreso" id="idingreso">
                            <select id="idproveedor" name="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                              
                            </select>
                          </div>
                          <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Fecha(*):</label>
                            <input type="date" class="form-control" name="fecha_hora" id="fecha_hora" required="">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tipo Comprobante(*):</label>
                            <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required="">
                               <option value="Boleta">Boleta</option>
                               <option value="Factura">Factura</option>
                               <option value="Ticket">Ticket</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Número:</label>
                            <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Número" required="">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Impuesto:</label>
                            <input type="text" class="form-control" name="impuesto" id="impuesto" required="">
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <a data-toggle="modal" href="#myModal">           
                              <button id="btnAgregarArt" type="button" class="btn btn-primary"> <span class="fa fa-plus"></span> Agregar Artículos</button>
                            </a>
                          </div>

                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                              <thead style="background-color:#A9D0F5">
                                    <th><center>Opciones</center></th>
                                    <th><center>Artículo</center></th>
                                    <th><center>Cantidad</center></th>
                                    <th><center>Precio Compra</center></th>
                                    <th><center>Precio Venta</center></th>
                                    <th><center>Subtotal</center></th>
                                </thead>
                                <tfoot>
                                    <th><center>TOTAL</center></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><center><h4 id="total">$ 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></center></th> 
                                </tfoot>
                                <tbody>
                                  
                                </tbody>
                            </table>
                          </div>
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button id="btnCancelar" class="btn btn-danger" onclick="cancelarForm()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Artículo</h4>
        </div>
        <div class="modal-body">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th><center>Opciones</center></th>
              <th><center>Nombre</center></th>
              <th><center>Categoría</center></th>
              <th><center>Código</center></th>
              <th><center>Stock</center></th>
              <th><center>Imagen</center></th>
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
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>        
      </div>
    </div>
  </div>  
  <!-- Fin modal -->
<?php
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/ingreso.js"></script>
<?php 
}
ob_end_flush();
?>