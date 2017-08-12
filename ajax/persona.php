<?php
require_once '../modelos/Persona.php';

$persona = new Persona();

$idpersona=isset($_POST['idpersona'])?limpiarCadena($_POST['idpersona']):'';
$tipo_persona=isset($_POST['tipo_persona'])?limpiarCadena($_POST['tipo_persona']):'';
$nombre=isset($_POST['nombre'])?limpiarCadena($_POST['nombre']):'';
$tipo_documento=isset($_POST['tipo_documento'])?limpiarCadena($_POST['tipo_documento']):'';
$num_documento=isset($_POST['num_documento'])?limpiarCadena($_POST['num_documento']):'';
$direccion=isset($_POST['direccion'])?limpiarCadena($_POST['direccion']):'';
$telefono=isset($_POST['telefono'])?limpiarCadena($_POST['telefono']):'';
$email=isset($_POST['email'])?limpiarCadena($_POST['email']):'';


switch ($_GET['op']) {
	case 'guardaryeditar':
		if(empty($idpersona)){//el if es para hacer un solo case en lugar de dos, si idcategoria no existe, estamos creando un registro, caso contrario estamos editando un registro
			$rspta = $persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
			//respuesta toma el valor 1 o 0 de acuerdo a lo que retorne el metodo en el modelo. Si es 1 => Categoria registrada, si no....
			echo $rspta ? 'ok' : 'nok';
		} else {
			$rspta = $persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
			echo $rspta ? 'oka' : 'noka';
		}
		break;
	case 'eliminar':
		$rspta = $persona->eliminar($idpersona);
		echo $rspta ? 'PerDel' : 'NoPerDel';
		break;
	case 'mostrar':
		$rspta = $persona->mostrar($idpersona);
		//codificamos usando json
		echo json_encode($rspta);
		break;
	case 'listarp':
		$rspta = $persona->listarp();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')" title="Desactivar"><i class="fa fa-trash"></i></button></center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->tipo_documento.'</center>',
				"3"=>'<center>'.$reg->num_documento.'</center>',
				"4"=>'<center>'.$reg->telefono.'</center>',
				"5"=>'<center>'.$reg->email.'</center>'
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
	case 'listarc':
		$rspta = $persona->listarc();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')" title="Desactivar"><i class="fa fa-trash"></i></button></center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->tipo_documento.'</center>',
				"3"=>'<center>'.$reg->num_cocumento.'</center>',
				"4"=>'<center>'.$reg->telefono.'</center>',
				"5"=>'<center>'.$reg->email.'</center>'
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