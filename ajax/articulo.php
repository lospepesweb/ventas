<?php
require_once '../modelos/Articulo.php';

$articulo = new Articulo();

$idarticulo = isset($_POST['idarticulo'])?limpiarCadena($_POST['idarticulo']):'';
$idcategoria=isset($_POST['idcategoria'])?limpiarCadena($_POST['idcategoria']):'';
$codigo = isset($_POST['codigo'])?limpiarCadena($_POST['codigo']):'';
$nombre=isset($_POST['nombre'])?limpiarCadena($_POST['nombre']):'';
$stock = isset($_POST['stock'])?limpiarCadena($_POST['stock']):'';
$descripcion=isset($_POST['descripcion'])?limpiarCadena($_POST['descripcion']):'';
$imagen = isset($_POST['imagen'])?limpiarCadena($_POST['imagen']):'';


switch ($_GET['op']) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])){
			$imagen=$_POST['imagenActual'];
		} else {
			$ext = explode('.',$_FILES['imagen']['name']);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/articulos/' . $imagen);
			}
		}
		if(empty($idarticulo)){//el if es para hacer un solo case en lugar de dos, si idcategoria no existe, estamos creando un registro, caso contrario estamos editando un registro
			$rspta = $articulo->insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen);
			//respuesta toma el valor 1 o 0 de acuerdo a lo que retorne el metodo en el modelo. Si es 1 => Categoria registrada, si no....
			echo $rspta ? 'ok' : 'nok';
		} else {
			$rspta = $articulo->editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen);
			echo $rspta ? 'oka' : 'noka';
		}
		break;
	case 'desactivar':
		$rspta = $articulo->desactivar($idarticulo);
		echo $rspta ? 'ArtDes' : 'NoArtDes';
		break;
	case 'activar':
		$rspta = $articulo->activar($idarticulo);
		echo $rspta ? 'ArtHab' : 'NoArtHab';
		break;
	case 'mostrar':
		$rspta = $articulo->mostrar($idarticulo);
		//codificamos usando json
		echo json_encode($rspta);
		break;
	case 'listar':
		$rspta = $articulo->listar();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>($reg->condicion)?'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')" title="Desactivar"><i class="fa fa-close"></i></button></center>':
					'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')" title="Activar"><i class="fa fa-check"></i></button></center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->categoria.'</center>',
				"3"=>'<center>'.$reg->codigo.'</center>',
				"4"=>'<center>'.$reg->stock.'</center>',
				"5"=>"<center><img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>",
				"6"=>($reg->condicion)?'<center><span class="label bg-green">Activada</span></center>':'<center><span class="label bg-red">Desactivada</span></center>'
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

		case "selectCategoria":
			require_once '../modelos/Categoria.php';
			$categoria = new Categoria();
			$rspta = $categoria->select();
			while ($reg = $rspta->fetch_object()) {
				echo '<option value=' . $reg->idcategoria . '>' .$reg->nombre . '</option>';
			}
		break;
}
