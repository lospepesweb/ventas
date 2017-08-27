<?php 

if (strlen(session_id()) < 1){
    session_start();
}
 
require_once '../modelos/Venta.php';
 
$venta = new Venta();
 
$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
 
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idventa)){
            $rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_venta,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
            echo $rspta ? 'ok' : 'nok';
        }
        else {
        }
    break;
 
    case 'anular':
        $rspta=$venta->anular($idventa);
        echo $rspta ? 'VenAnu' : 'NoVenAnu';
    break;
 
    case 'mostrar':
        $rspta=$venta->mostrar($idventa);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
    break;
 
    case 'listarDetalle':
        //Recibimos el idingreso
        $id=$_GET['id'];
 
        $rspta = $venta->listarDetalle($id);
        $total=0;
        echo '<thead style="background-color:#A9D0F5">
                                    <th><center>Opciones</center></th>
                                    <th><center>Artículo</center></th>
                                    <th><center>Cantidad</center></th>
                                    <th><center>Precio Venta</center></th>
                                    <th><center>Descuento</center></th>
                                    <th><center>Subtotal</center></th>
                                </thead>';
 
        while ($reg = $rspta->fetch_object())
                {
                    echo '<tr class="filas"><td></td><td><center>'.$reg->nombre.'</center></td><td><center>'.$reg->cantidad.'</center></td><td><center>'.$reg->precio_venta.'</center></td><td><center>'.$reg->descuento.'</center></td><td><center>'.$reg->subtotal.'</center></td></tr>';
                    $total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
                }
        echo '<tfoot>
                                    <th><center>TOTAL</center></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><center><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></center></th> 
                                </tfoot>';
    break;
 
    case 'listar':
        $rspta=$venta->listar();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            if($reg->tipo_comprobante == 'Ticket'){
                $url = '../reportes/exTicket.php?id=';
            } else {
                $url = '../reportes/exFactura.php?id=';
            }

            $data[]=array(
                "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-primary" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-primary" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').
                    ' <a target="_blank" href="'.$url.$reg->idventa.'"><button class="btn btn-info"><i class="fa fa-file"></i></button></a></center>',
                "1"=>'<center>'.$reg->fecha.'</center>',
                "2"=>'<center>'.$reg->cliente.'</center>',
                "3"=>'<center>'.$reg->usuario.'</center>',
                "4"=>'<center>'.$reg->tipo_comprobante.'</center>',
                "5"=>'<center>'.$reg->serie_comprobante.' - '.$reg->num_comprobante.'</center>',
                "6"=>'<center>'.$reg->total_venta.'</center>',
                "7"=>($reg->estado=='Aceptado') ? '<center><span class="label bg-green">Aceptada</span></center>' : '<center><span class="label bg-red">Anulada</span></center>'
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;           
 
    case 'selectCliente':
        require_once '../modelos/Persona.php';
        $persona = new Persona();
 
        $rspta = $persona->listarc();
 
        while ($reg = $rspta->fetch_object()){
            echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
        }
    break;
 
    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo=new Articulo();
 
        $rspta=$articulo->listarActivosVenta();
        //Vamos a declarar un array
        $data= Array();
 
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<center><button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button></center>',
                "1"=>'<center>'.$reg->nombre.'</center>',
                "2"=>'<center>'.$reg->categoria.'</center>',
                "3"=>'<center>'.$reg->codigo.'</center>',
                "4"=>'<center>'.$reg->stock.'</center>',
                "5"=>'<center>'.$reg->precio_venta.'</center>',
                "6"=>"<center><img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' ></center>"
                );
        }
        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
        echo json_encode($results);
    break;
}