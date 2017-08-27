<?php 
//Activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1){
  session_start();
}

if(!isset($_SESSION['nombre'])){
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if($_SESSION['ventas'] == 1){ 
  	//incluimos el archivo Factura.php
  	require 'Factura.php';
  	$logo = 'logo.jpg';
  	$ext_log = 'jpg';
  	$empresa = 'Nombre de la empresa';
  	$documento = 'Numero de CUIT';
  	$direccion = 'dirección de la empresa';
  	$telefono = '4-214958';
  	$email = 'mail@delaempresa.com';

  	//obtemos los datos de la cabecera de la venta
  	require_once '../modelos/Venta.php';
  	$venta = new Venta();
  	$rsptav = $venta->ventaCabecera($_GET['id']);
  	//recorremos los valores obtenidos
  	$regv = $rsptav->fetch_object();

  	//Establecemos la configuracion de la factura
  	$pdf = new PDF_Invoice('P', 'mm' , 'A4');
  	$pdf->AddPage();

  	//Enviamos los datos de la empresa al metodo addSociete de la clase Factura
  	$pdf->addSociete(
  		utf8_decode($empresa),
  		$documento."\n" .
  		utf8_decode("Dirección: ". $direccion."\n") .
  		utf8_decode("Teléfono: "). $telefono."\n" .
  		"E-Mail: ". $email,$logo,$ext_log);
  	$pdf->fact_dev("$regv->tipo_comprobante ", "$regv->serie_comprobante-$regv->num_comprobante");
  	$pdf->temporaire('');
  	$pdf->addDate($regv->fecha);

  	//enviamos los datos del cliente al metodo addClientAdresse de la clase Factura
  	$pdf->addClientAdresse(utf8_decode($regv->cliente), 'Domicilio: '.utf8_decode($regv->direccion), $regv->tipo_documento.': '.$regv->num_documento, 'E-Mail: '.$regv->email, utf8_decode('Teléfono: ').$regv->telefono);

  	//establecemos las columnas que va a tener la seccion donde mostramos los detalles de la venta
  	$cols = array('CODIGO'=>23,
  				  'DESCRIPCION'=>78,
  				  'CANTIDAD'=>22,
  				  'P.U'=>25,
  				  'DSCTO'=>20,
  				  'SUBTOTAL'=>22);
  	$pdf->addCols($cols);
  	$cols = array('CODIGO'=>'C',
  				  'DESCRIPCION'=>'C',
  				  'CANTIDAD'=>'C',
  				  'P.U'=>'C',
  				  'DSCTO'=>'C',
  				  'SUBTOTAL'=>'C');
  	$pdf->addLineFormat($cols);
  	$pdf->addLineFormat($cols);

  	//Actualizamos el valor de la coordenada "y", que sera la uvicacion desde donde empezaremos a mostrar los datos
  	$y = 89;

  	//obtenemos todos los detalles de la venta actual
  	$rsptad = $venta->ventaDetalle($_GET['id']);

  	while ($regd = $rsptad->fetch_object()){
  		$line = array('CODIGO'=> "$regd->codigo",
  				  	  'DESCRIPCION'=> utf8_decode("$regd->articulo"),
  				  	  'CANTIDAD'=> "$regd->cantidad",
  				  	  'P.U'=> "$regd->precio_venta",
  				  	  'DSCTO'=> "$regd->descuento",
  				  	  'SUBTOTAL'=> "$regd->subtotal");
  				$size = $pdf->addLine($y,$line);
  				$y += $size + 2;
  	}

  	//convertimos el total en letras
  	require_once 'Letras.php';
  	$V = new EnLetras();
  	$con_letra = strtoupper($V->ValorEnLetras($regv->total_venta, "Pesos"));
  	$pdf->addCadreTVAs("--- ".$con_letra);

  	//Mostramos el impuesto
  	$pdf->addTVAs($regv->impuesto, $regv->total_venta, "$ ");
  	$pdf->addCadreEurosFrancs("IVA". " $regv->impuesto %");
  	$pdf->Output('Reporte de Venta', 'I');

  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>