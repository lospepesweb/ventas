var tabla;

//funcion que se ejecuta al inicio
function init(){
	mostrarForm(false);
	listar();

	$("#formulario").on("submit", function(e){
		guardarYeditar(e);
	})

	//cargamos los items al select categoria
	$.post("../ajax/articulo.php?op=selectCategoria", function(r){
		$("#idcategoria").html(r);
		$("#idcategoria").selectpicker('refresh');
	});

	$('#imagenMuestra').hide();
}

//funcion limpiar
function limpiar(){
	$('#idarticulo').val('');
	$('#codigo').val('');
	$('#nombre').val('');
	$('#descripcion').val('');
	$('#stock').val('');
	$('#imagen').val('');
	$('#imagenMuestra').attr("src","");
	$('#imagenActual').val('');
	$('#print').hide();
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
			url: '../ajax/articulo.php?op=listar',
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
		url: "../ajax/articulo.php?op=guardaryeditar",
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
					  'Artículo registrado',
					  'success'
					);
				break;
				case 'oka':
					swal(
					  'Buen trabajo!',
					  'Artículo actualizado',
					  'success'
					);
				break;
				case 'nok':
					swal(
					  'Ups...',
					  'No se puedo registrar el artículo',
					  'error'
					);
				break;
				case 'noka':
					swal(
					  'Ups...',
					  'No se puedo actualizar el artículo',
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
function mostrar(idarticulo){
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo}, function(data, status){
		data = JSON.parse(data);
		mostrarForm(true);

		$('#idcategoria').val(data.idcategoria);
		$('#idcategoria').selectpicker('refresh');
		$('#codigo').val(data.codigo);
		$('#nombre').val(data.nombre);
		$('#stock').val(data.stock);
		$('#descripcion').val(data.descripcion);
		$('#imagen').val('');
		$('#imagenMuestra').show();
		$('#imagenMuestra').attr("src","../files/articulos/" + data.imagen);
		$('#imagenActual').val(data.imagen);
		$('#idarticulo').val(data.idarticulo);
		generarBarCode();
	});	
};

//funcion desactivar categoria
function desactivar(idarticulo){
	swal({
	  title: '¿Está seguro que desea desactivar el artículo?',
	  text: "Podrá activarlo posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, desactivarlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/articulo.php?op=desactivar",{idarticulo : idarticulo}, function(e){
	  	if(e == 'ArtDes'){
	  		swal(
			    'Desactivado!',
			    'El artículo fue desactivado.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'El artículo no se pudo desactivar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

//funcion activar categoria
function activar(idarticulo){
	swal({
	  title: '¿Está seguro que desea activar el artículo?',
	  text: "Podrá desactivarlo posteriormente",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, activarlo!',
	  cancelButtonText: 'Cancelar'
	}).then(function () {
	  $.post("../ajax/articulo.php?op=activar",{idarticulo : idarticulo}, function(e){
	  	if(e == 'ArtHab'){
	  		swal(
			    'Activado!',
			    'El artículo fue activado.',
			    'success'
	  		)
	  	} else {
	  		swal(
			    'Ups...',
			    'El artículo no se pudo activar.',
			    'error'
	  		)
	  	}
	  	tabla.ajax.reload();
	  });
	})
}

//funcion generar codigo de barras
function generarBarCode(){
	codigo = $('#codigo').val()
	JsBarcode('#barcode', codigo);
	$('#print').show();
}

//funcion imprimir codigo de barras
function imprimir(){
	$('#print').printArea();
}


/*=====  End of funciones con peticiones ajax  ======*/

init();