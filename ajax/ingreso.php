<?php

if (strlen(session_id()) < 1){
	session_start();
}

require_once '../modelos/Ingreso.php';

$ingreso = new Ingreso();

$idingreso = isset($_POST['idingreso']) ? limpiarCadena($_POST['idingreso']) : '';
$idproveedor = isset($_POST['idproveedor']) ? limpiarCadena($_POST['idproveedor']) : '';
$idusuario = $_SESSION['idusuario'];
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? limpiarCadena($_POST['tipo_comprobante']) : '';
$serie_comprobante = isset($_POST['serie_comprobante']) ? limpiarCadena($_POST['serie_comprobante']) : '';
$num_comprobante = isset($_POST['num_comprobante']) ? limpiarCadena($_POST['num_comprobante']) : '';
$fecha_hora = isset($_POST['fecha_hora']) ? limpiarCadena($_POST['fecha_hora']) : '';
$impuesto = isset($_POST['impuesto']) ? limpiarCadena($_POST['impuesto']) : '';
$total_compra = isset($_POST['total_compra']) ? limpiarCadena($_POST['total_compra']) : '';

switch ($_GET['op']) {
	case 'guardaryeditar':
		if(empty($idingreso)){//el if es para hacer un solo case en lugar de dos, si idcategoria no existe, estamos creando un registro, caso contrario estamos editando un registro
			$rspta = $ingreso->insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $_POST['idarticulo'], $_POST['cantidad'], $_POST['precio_compra'], $_POST['precio_venta']);
			//respuesta toma el valor 1 o 0 de acuerdo a lo que retorne el metodo en el modelo. Si es 1 => Categoria registrada, si no....
			echo $rspta ? 'ok' : 'nok';
		} else {
		}
	break;
	
	case 'anular':
		$rspta = $ingreso->anular($idingreso);
		echo $rspta ? 'IngAnu' : 'NoIngAnu';
	break;
	
	case 'mostrar':
		$rspta = $ingreso->mostrar($idingreso);
		//codificamos usando json
		echo json_encode($rspta);
	break;

	case 'listarDetalle':

		//recibimos el idingreso
		$id = $_GET['id'];
		$rspta = $ingreso->listarDetalle($id);
		$total = 0;
		echo '<thead style="background-color:#A9D0F5">
                <th><center>Opciones</center></th>
                <th><center>Artículo</center></th>
                <th><center>Cantidad</center></th>
                <th><center>Precio Compra</center></th>
                <th><center>Precio Venta</center></th>
                <th><center>Subtotal</center></th>
            </thead>';

		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas"><td></td><td><center>'.$reg->nombre.'</center></td><td><center>'.$reg->cantidad.'</center></td><td><center>'.$reg->precio_compra.'</center></td><td><center>'.$reg->precio_venta.'</center></td><td><center>'.$reg->cantidad * $reg->precio_compra.'</center></td></tr>';
			$total = $total + ($reg->cantidad * $reg->precio_compra);
		}

		echo '<tfoot>
                <th><center>TOTAL</center></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><center><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></center></th> 
            </tfoot>';
	break;	

	case 'listar':
		$rspta = $ingreso->listar();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>($reg->estado == 'Aceptado') ? '<center><button class="btn btn-primary" onclick="mostrar('.$reg->idingreso.')" title="Ver detalle"><i class="fa fa-eye"></i></button>'.
					' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')" title="Anular"><i class="fa fa-close"></i></button></center>' :
					'<center><button class="btn btn-primary" onclick="mostrar('.$reg->idingreso.')" title="Ver detalle"><i class="fa fa-eye"></i></button></center>',
					//fecha es un alias del campo fecha_hora en el modelo, igual que proveedor y usuario
				"1"=>'<center>'.$reg->fecha.'</center>',
				"2"=>'<center>'.$reg->proveedor.'</center>',
				"3"=>'<center>'.$reg->usuario.'</center>',
				"4"=>'<center>'.$reg->tipo_comprobante.'</center>',
				"5"=>'<center>'.$reg->serie_comprobante.' - '.$reg->num_comprobante.'</center>',
				"6"=>'<center>'.$reg->total_compra.'</center>',
				"7"=>($reg->estado == 'Aceptado') ? '<center><span class="label bg-green">Aceptada</span></center>':'<center><span class="label bg-red">Anulada</span></center>'
			);
		}
		$results = array(
			"sEcho"=>1, //Informacion para el datatable
			"iTotalRecords"=>count($data), //enviamos el total de registros al datatables
			"iTotalDisplayRecords"=>count($data),//enviamos total de registros a visualizar
			"aaData"=>$data
		);
		echo json_encode($results);
	break;

	case 'selectProveedor':
		require_once '../modelos/Persona.php';
		$persona = new Persona();

		$rspta = $persona->listarp();

		while ($reg = $rspta->fetch_object()){
			echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
		}
	break;

	case 'listarArticulos':

		require_once '../modelos/Articulo.php';
		$articulo = new Articulo();

		$rspta = $articulo->listarActivos();
		//declaramos array donde se van a mostrar los registros de la tabla que esten activos
		$data = Array();
		while ($reg = $rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center><button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button></center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->categoria.'</center>',
				"3"=>'<center>'.$reg->codigo.'</center>',
				"4"=>'<center>'.$reg->stock.'</center>',
				"5"=>"<center><img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
			);
		}
		
		$results = array(
			"sEcho"=>1, //Informacion para el datatable
			"iTotalRecords"=>count($data), //enviamos el total de registros al datatables
			"iTotalDisplayRecords"=>count($data),//enviamos total de registros a visualizar
			"aaData"=>$data
		);
		echo json_encode($results);
	break;

}