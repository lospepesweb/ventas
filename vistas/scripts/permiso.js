var tabla;

//funcion que se ejecuta al inicio
function init(){
	mostrarForm(false);
	listar();
}

//funcion mostrar formulario
function mostrarForm(flag){
	// limpiar();
	if(flag) {
		$('#listadoRegistros').hide();
		$('#formularioRegistros').show();
		$('#btnGuardar').prop("disabled", false);
		$('#btnAgregar').hide();
	} else {
		$('#listadoRegistros').show();
		$('#formularioRegistros').hide();
		$('#btnAgregar').hide();
	}
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
			url: '../ajax/permiso.php?op=listar',
			type: 'get',
			dataType: 'json',
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5, // paginacion cada 5 registros
		"order": [[0, "asc"]] //orden de la tabla (col 0, descendiente)
	}).DataTable();
}

/*=====  End of funciones con peticiones ajax  ======*/

init();