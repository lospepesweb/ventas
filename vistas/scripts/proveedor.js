var tabla;

//funcion que se ejecuta al inicio
function init(){
	mostrarForm(false);
	listar();

	$("#formulario").on("submit", function(e){
		guardarYeditar(e);
	})
}

//funcion limpiar
function limpiar(){
	$('#nombre').val('');
	$('#num_documento').val('');
	$('#direccion').val('');
	$('#telefono').val('');
	$('#email').val('');
	$('#idpersona').val('');
}

//funcion mostrar formulario
function mostrarForm(flag){
	limpiar();
	if(flag) {
		$('#listadoRegistros').hide();
		$('#formularioRegistros').show();
		$('#btnGuardar').prop("disabled", false);
		$('#btnAgregar').hide();
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
			url: '../ajax/persona.php?op=listarp',
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

//funcion para guardar o editar
function guardarYeditar(e){
	e.preventDefault();//No se activerá la accion predeterminada del evento
	$('#btnGuardar').prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		//se podria usar sweet alert.
		// el parametro "datos" es la respuesta (string) que viene desde el archivo en la carpeta ajax
		success: function(datos){
			switch (datos){
				case 'ok':
					swal(
					  'Buen trabajo!',
					  'Proveedor registrado',
					  'success'
					);
				break;
				case 'oka':
					swal(
					  'Buen trabajo!',
					  'Proveedor actualizado',
					  'success'
					);
				break;
				case 'nok':
					swal(
					  'Ups...',
					  'No se puedo registrar el proveedor',
					  'error'
					);
				break;
				case 'noka':
					swal(
					  'Ups...',
					  'No se puedo actualizar el proveedor',
					  'error'
					);
				break;
			}
			mostrarForm(false);
			tabla.ajax.reload();
		},
	});
	limpiar();
};
//funcion editar
function mostrar(idpersona){
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona}, function(data, status){
		data = JSON.parse(data);
		mostrarForm(true);

		$('#nombre').val(data.nombre);
		$('#tipo_documento').val(data.tipo_documento);
		$('#tipo_documento').selectpicker('refresh');
		$('#num_documento').val(data.num_documento);
		$('#direccion').val(data.direccion);
		$('#telefono').val(data.telefono);
		$('#email').val(data.email);
		$('#idpersona').val(data.idpersona);
	});	
};

//funcion eliminar persona
function eliminar(idpersona){
	swal({
	  title: '¿Está seguro que desea eliminar el proveedor?',
	  text: "No podrá revertir esta acción.",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, eliminarlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/persona.php?op=eliminar",{idpersona : idpersona}, function(e){
	  	if(e == 'PerDel'){
	  		swal(
			    'Eliminado!',
			    'El proveedor fue eliminado.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'El proveedor no se pudo eliminar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

/*=====  End of funciones con peticiones ajax  ======*/

init();