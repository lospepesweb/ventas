<?php
require_once '../modelos/Consultas.php';

$consulta = new Consultas();

switch ($_GET['op']) {
	
	case 'comprasfecha':
		$fecha_inicio = $_REQUEST['fecha_inicio'];
		$fecha_fin = $_REQUEST['fecha_fin'];

		$rspta = $consulta->comprasFecha($fecha_inicio,$fecha_fin);
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center>'.$reg->fecha.'</center>',
				"1"=>'<center>'.$reg->usuario.'</center>',
				"2"=>'<center>'.$reg->proveedor.'</center>',
				"3"=>'<center>'.$reg->tipo_comprobante.'</center>',
				"4"=>'<center>'.$reg->serie_comprobante.' - '.$reg->num_comprobante.'</center>',
				"5"=>'<center>'.$reg->total_compra.'</center>',
				"6"=>'<center>'.$reg->impuesto.'</center>',
				"7"=>($reg->estado == 'Aceptado')?'<center><span class="label bg-green">Aceptada</span></center>':'<center><span class="label bg-red">Anulada</span></center>'
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

	case 'ventasfechacliente':
		$fecha_inicio = $_REQUEST['fecha_inicio'];
		$fecha_fin = $_REQUEST['fecha_fin'];
		$idcliente = $_REQUEST['idcliente'];
		
		$rspta = $consulta->ventasFechaCliente($fecha_inicio,$fecha_fin,$idcliente);
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<center>'.$reg->fecha.'</center>',
				"1"=>'<center>'.$reg->usuario.'</center>',
				"2"=>'<center>'.$reg->cliente.'</center>',
				"3"=>'<center>'.$reg->tipo_comprobante.'</center>',
				"4"=>'<center>'.$reg->serie_comprobante.' - '.$reg->num_comprobante.'</center>',
				"5"=>'<center>'.$reg->total_venta.'</center>',
				"6"=>'<center>'.$reg->impuesto.'</center>',
				"7"=>($reg->estado == 'Aceptado')?'<center><span class="label bg-green">Aceptada</span></center>':'<center><span class="label bg-red">Anulada</span></center>'
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