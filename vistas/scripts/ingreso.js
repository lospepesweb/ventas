var tabla;

//funcion que se ejecuta al inicio
function init(){
	mostrarForm(false);
	listar();

	$("#formulario").on("submit", function(e){
		guardarYeditar(e);
	});

	//cargamos los items al select proveedor (los sacamos desde el archivo en ajax)
	$.post('../ajax/ingreso.php?op=selectProveedor', function(r){
		$('#idproveedor').html(r);
		$('#idproveedor').selectpicker('refresh');
	});

}

//funcion limpiar
function limpiar(){
	$('#idproveedor').val('');
	$('#proveedor').val('');
	$('#serie_comprobante').val('');
	$('#num_comprobante').val('');
	$('#fecha_hora').val('');
	$('#impuesto').val('');

	$('#total_compra').val('');
	$('.filas').remove();
	$('#total').html('0');

	//obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + now.getMonth() + 1).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day);
	$('#fecha_hora').val(today);

	//marcamos el primer tipo_documento
	$('#tipo_documento').val('Boleta');
	$('#tipo_documento').selectpicker('refresh');


} 

//funcion mostrar formulario
function mostrarForm(flag){
	limpiar();
	if(flag) {
		$('#listadoRegistros').hide();
		$('#formularioRegistros').show();
		$('#btnAgregar').hide();
		listarArticulos();
		
				
		$('#btnGuardar').hide();	
		$('#btnCancelar').show();
		detalles = 0;	
		$('#btnAgregarArt').show();	

	} else {
		$('#listadoRegistros').show();
		$('#formularioRegistros').hide();
		$('#btnAgregar').show();
	}
}

//funcion cancelar formulario
function cancelarForm(){
	limpiar();
	mostrarForm(false);
}

/*=====================================================
=            funciones con peticiones ajax            =
=====================================================*/

//funcion listar
function listar(){
	// tabla es la variable goblal declarada en la linea 1
	tabla=$('#tblListado').dataTable({
		"aProcessing": true, //Activamos el procesamiento del datatables
		"aServerSide": true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		//agregamos botones para exportar el dataTable
		responsive: true,
		autoWidth: false,
		buttons: [ 
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax": {
			url: '../ajax/ingreso.php?op=listar',
			type: 'get',
			dataType: 'json',
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10, // paginacion cada 5 registros
		"order": [[0, "desc"]] //orden de la tabla (col 0, descendiente)
	}).DataTable();
}

//funcion listar articulos para mostrar en el formulario modal
function listarArticulos(){
	// tabla es la variable goblal declarada en la linea 1
	tabla=$('#tblarticulos').dataTable({
		"aProcessing": true, //Activamos el procesamiento del datatables
		"aServerSide": true, //Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip', //Definimos los elementos del control de tabla
		//agregamos botones para exportar el dataTable
		responsive: true,
		autoWidth: false,
		buttons: [],
		"ajax": {
			url: '../ajax/ingreso.php?op=listarArticulos',
			type: 'get',
			dataType: 'json',
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, // paginacion cada 5 registros
		"order": [[0, "desc"]] //orden de la tabla (col 0, descendiente)
	}).DataTable();
}

//funcion para guardar o editar
function guardarYeditar(e){
	e.preventDefault();//No se activerá la accion predeterminada del evento
	// $('#btnGuardar').prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/ingreso.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		// el parametro "datos" es la respuesta (string) que viene desde el archivo en la carpeta ajax
		success: function(datos){
			switch (datos){
				case 'ok':
					swal(
					  'Buen trabajo!',
					  'Compra registrada',
					  'success'
					);
				break;
				case 'nok':
					swal(
					  'Ups...',
					  'No se puedo registrar la compra',
					  'error'
					);
				break;
			}
			mostrarForm(false);
			listar(); //esto en vez de tabla.ajax.reload pq no sabria que table actualizar, si la de articulos o la de ingresos
		}
	});
	limpiar();
};
//funcion editar
function mostrar(idingreso){
	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso}, function(data, status){
		data = JSON.parse(data);
		mostrarForm(true);

		$('#idproveedor').val(data.idproveedor);
		$('#idproveedor').selectpicker('refresh');
		$('#tipo_comprobante').val(data.tipo_comprobante);
		$('#tipo_comprobante').selectpicker('refresh');
		$('#serie_comprobante').val(data.serie_comprobante);
		$('#num_comprobante').val(data.num_comprobante);
		$('#fecha_hora').val(data.fecha);
		$('#impuesto').val(data.impuesto);
		$('#idingreso').val(data.idingreso);
		
		//Ocultar y mostrar los botones
		$('#btnGuardar').hide();	
		$('#btnCancelar').show();		
		$('#btnAgregarArt').hide();	
	});

	$.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso, function(r){
		$('#detalles').html(r);
	});

};

//funcion anular ingreso
function anular(idingreso){
	swal({
	  title: '¿Está seguro que desea anular la compra?',
	  text: "No Podrá revertir está acción",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, anularlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/ingreso.php?op=anular",{idingreso : idingreso}, function(e){
	  	if(e == 'IngAnu'){
	  		swal(
			    'Anulada!',
			    'La compra fue anulada.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'La compra no se pudo anular.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

//declaracion de variables necesarias para trabajar con las compras y sus detalles

var impuesto = 21;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
  {
  	var tipo_comprobante = $("#tipo_comprobante option:selected").text();
  	if (tipo_comprobante=='Factura') {
        $("#impuesto").val(impuesto); 
    } else {
        $("#impuesto").val("0"); 
    }
  }

function agregarDetalle(idarticulo, articulo) {
  	var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 1;

    if (idarticulo != "") {
    	var subtotal = cantidad * precio_compra;
    	var fila = '<tr class="filas" id="fila'+cont+'">'+
    	'<td><center><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')" title="Quitar artículo">X</button></center></td>'+
    	'<td><center><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</center></td>'+
    	'<td><center><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" onblur="modificarSubtotales()"></center></td>'+
    	'<td><center><input type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'" onblur="modificarSubtotales()"></center></td>'+
    	'<td><center><input type="number" name="precio_venta[]" value="'+precio_venta+'"></center></td>'+
    	'<td><center><span name="subtotal" id="subtotal'+cont+'">$ '+subtotal+'</span></center></td>'+
    	'<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles++;
    	$('#detalles').append(fila);
    	modificarSubtotales();
    } else {
    	alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubtotales(){
	var cant = document.getElementsByName("cantidad[]");
	var prec = document.getElementsByName("precio_compra[]");
	var sub = document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpP = prec[i];
		var inpS = sub[i];

		inpS.value = inpC.value * inpP.value;
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
	}
	
	calcularTotales();

}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;
	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}

	$('#total').html("$ " + total);
	$('#total_compra').val(total);

	evaluar();
}

function evaluar(){
	if (detalles > 0){
		$('#btnGuardar').show();	
	} else {
		$('#btnGuardar').hide();	
		cont = 0;
	}
}

function eliminarDetalle(indice){
	$('#fila' + indice).remove();
	calcularTotales();
	detalles--;
	evaluar();
}

/*====  End of funciones con peticiones ajax  ======*/

init();