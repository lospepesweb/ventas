var tabla;

//funcion que se ejecuta al inicio
function init(){
	listar();
	//cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function(r){
                $("#idcliente").html(r);
                $('#idcliente').selectpicker('refresh');
    });
}

/*=====================================================
=            funciones con peticiones ajax            =
=====================================================*/

//funcion listar

function listar(){
	// tabla es la variable goblal declarada en la linea 1
	
	var fecha_inicio = $('#fecha_inicio').val();
	var fecha_fin = $('#fecha_fin').val();
	var idcliente  = $('#idcliente').val();

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
			url: '../ajax/consultas.php?op=ventasfechacliente',
			data: {fecha_inicio : fecha_inicio, fecha_fin : fecha_fin, idcliente : idcliente},
			type: 'get',
			dataType: 'json',
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, // paginacion cada 5 registros
		"order": [[1, "asc"]] //orden de la tabla (col 0, descendiente)
	}).DataTable();
}



/*=====  End of funciones con peticiones ajax  ======*/

init();