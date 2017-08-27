var tabla;

//funcion que se ejecuta al inicio
function init(){
	mostrarForm(false);
	listar();

	$("#formulario").on("submit", function(e){
		guardarYeditar(e);
	})

	$('#imagenMuestra').hide();
	//mostramos los permisos del usuario
	$.post('../ajax/usuario.php?op=permisos&id=', function(r){
		$('#permisos').html(r);
	});
}

//funcion limpiar
function limpiar(){
	$('#nombre').val('');
	$('#num_documento').val('');
	$('#direccion').val('');
	$('#telefono').val('');
	$('#email').val('');
	$('#cargo').val('');
	$('#login').val('');
	$('#clave').val('');
	// $('#imagen').val('');
	$('#imagenMuestra').attr("src","");
	$('#imagenActual').val('');
	$('#idusuario').val('');
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
			url: '../ajax/usuario.php?op=listar',
			type: 'get',
			dataType: 'json',
			error: function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 10, // paginacion cada 5 registros
		"order": [[1, "asc"]] //orden de la tabla (col 0, descendiente)
	}).DataTable();
}

//funcion para guardar o editar
function guardarYeditar(e){
	e.preventDefault();//No se activerá la accion predeterminada del evento
	$('#btnGuardar').prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
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
					  'Usuario registrado',
					  'success'
					);
				break;
				case 'oka':
					swal(
					  'Buen trabajo!',
					  'Usuario actualizado',
					  'success'
					);
				break;
				case 'nok':
					swal(
					  'Ups...',
					  'No se pudieron registrar todos los datos del usuario',
					  'error'
					);
				break;
				case 'noka':
					swal(
					  'Ups...',
					  'No se puedo actualizar el usuario',
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
function mostrar(idusuario){
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status){
		data = JSON.parse(data);
		mostrarForm(true);

		$('#nombre').val(data.nombre);
		$('#tipo_documento').val(data.tipo_documento);
		$('#tipo_documento').selectpicker('refresh');
		$('#num_documento').val(data.num_documento);
		$('#direccion').val(data.direccion);
		$('#telefono').val(data.telefono);
		$('#email').val(data.email);
		$('#cargo').val(data.cargo);
		$('#login').val(data.login);
		$('#clave').val('');
		$('#claveActual').val('Puedo usar este input para desencriptar la clave actual, copiarla y pegarla en campo "clave"');
		$('#imagen').val('');
		$('#imagenMuestra').show();
		$('#imagenMuestra').attr("src","../files/usuarios/" + data.imagen);
		$('#imagenActual').val(data.imagen);
		$('#idusuario').val(data.idusuario);
	});

	$.post("../ajax/usuario.php?op=permisos&id="+idusuario, function(r){
		$('#permisos').html(r);
	});	
}

//funcion desactivar categoria
function desactivar(idusuario){
	swal({
	  title: '¿Está seguro que desea desactivar el usuario?',
	  text: "Podrá activarlo posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, desactivarlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/usuario.php?op=desactivar",{idusuario : idusuario}, function(e){
	  	if(e == 'UsuDes'){
	  		swal(
			    'Desactivado!',
			    'El usuario fue desactivado.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'El usuario no pudo ser desactivado.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

//funcion activar categoria
function activar(idusuario){
	swal({
	  title: '¿Está seguro que desea activar el usuario?',
	  text: "Podrá desactivarlo posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, activarlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/usuario.php?op=activar",{idusuario : idusuario}, function(e){
	  	if(e == 'UsuHab'){
	  		swal(
			    'Activado!',
			    'El usuario fue activado.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'El usuario no se pudo activar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

/*=====  End of funciones con peticiones ajax  ======*/

init();