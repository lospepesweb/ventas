<?php
require_once '../modelos/Categoria.php';

$categoria = new Categoria();

$idcategoria=isset($_POST['idcategoria'])?limpiarCadena($_POST['idcategoria']):'';
$nombre=isset($_POST['nombre'])?limpiarCadena($_POST['nombre']):'';
$descripcion=isset($_POST['descripcion'])?limpiarCadena($_POST['descripcion']):'';

switch ($_GET['op']) {
	case 'guardaryeditar':
		if(empty($idcategoria)){//el if es para hacer un solo case en lugar de dos, si idcategoria no existe, estamos creando un registro, caso contrario estamos editando un registro
			$rspta = $categoria->insertar($nombre, $descripcion);
			//respuesta toma el valor 1 o 0 de acuerdo a lo que retorne el metodo en el modelo. Si es 1 => Categoria registrada, si no....
			echo $rspta ? 'ok' : 'nok';
		} else {
			$rspta = $categoria->editar($idcategoria, $nombre, $descripcion);
			echo $rspta ? 'oka' : 'noka';
		}
		break;
	case 'desactivar':
		$rspta = $categoria->desactivar($idcategoria);
		echo $rspta ? 'CatDes' : 'NoCatDes';
		break;
	case 'activar':
		$rspta = $categoria->activar($idcategoria);
		echo $rspta ? 'CatHab' : 'NoCatHab';
		break;
	case 'mostrar':
		$rspta = $categoria->mostrar($idcategoria);
		//codificamos usando json
		echo json_encode($rspta);
		break;
	case 'listar':
		$rspta = $categoria->listar();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>($reg->condicion)?'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')" title="Desactivar"><i class="fa fa-close"></i></button></center>':
					'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')" title="Activar"><i class="fa fa-check"></i></button><center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->descripcion.'</center>',
				"3"=>($reg->condicion)?'<center><span class="label bg-green">Activada</span></center>':'<center><span class="label bg-red">Desactivada</span></center>'
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