<?php
require_once '../modelos/Permiso.php';

$permiso = new Permiso();

switch ($_GET['op']) {
	
	case 'listar':
		$rspta = $permiso->listar();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center>'.$reg->nombre.'</center>'
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