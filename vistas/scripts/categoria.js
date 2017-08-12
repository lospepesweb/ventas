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
	$('#idcategoria').val('');
	$('#nombre').val('');
	$('#descripcion').val('');
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
			url: '../ajax/categoria.php?op=listar',
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
		url: "../ajax/categoria.php?op=guardaryeditar",
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
					  'Categoría registrada',
					  'success'
					);
				break;
				case 'oka':
					swal(
					  'Buen trabajo!',
					  'Categoría actualizada',
					  'success'
					);
				break;
				case 'nok':
					swal(
					  'Ups...',
					  'No se puedo registrar la categoría',
					  'error'
					);
				break;
				case 'noka':
					swal(
					  'Ups...',
					  'No se puedo actualizar la categoría',
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
function mostrar(idcategoria){
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria}, function(data, status){
		data = JSON.parse(data);
		mostrarForm(true);

		$('#nombre').val(data.nombre);
		$('#descripcion').val(data.descripcion);
		$('#idcategoria').val(data.idcategoria);

	});	
};

//funcion desactivar categoria
function desactivar(idcategoria){
	swal({
	  title: '¿Está seguro que desea desactivar la categoría?',
	  text: "Podrá activarla posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, desactivarla!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/categoria.php?op=desactivar",{idcategoria : idcategoria}, function(e){
	  	if(e == 'CatDes'){
	  		swal(
			    'Desactivada!',
			    'La categoría fue desactivada.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'La categoría no se pudo desactivar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

//funcion activar categoria
function activar(idcategoria){
	swal({
	  title: '¿Está seguro que desea activar la categoría?',
	  text: "Podrá desactivarla posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, activarla!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/categoria.php?op=activar",{idcategoria : idcategoria}, function(e){
	  	if(e == 'CatHab'){
	  		swal(
			    'Activada!',
			    'La categoría fue activada.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'La categoría no se pudo activar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}


/*=====  End of funciones con peticiones ajax  ======*/

init();