<?php 
//requerimos la conexion a la base de datos
require '../config/conexion.php';

/**
* Creamos la clacse Categoria con su constructos
*/
Class Articulo
{
	//implementamos el constructor
	public function __construct()
	{
		
	}

	//metodo para insertar registro
	public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen){
		$sql = "INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,imagen,condicion)
				VALUES ('$idcategoria','$codigo','$nombre','$stock','$descripcion','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	//metodo para editar registros
	public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$imagen){
		$sql = "UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',stock='$stock',descripcion='$descripcion',imagen='$imagen' 
				WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//metodo para desactivar categorias
	public function desactivar($idarticulo){
		$sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//metodo para activar categorias
	public function activar($idarticulo){
		$sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar datos de UN registro a modificar
	public function mostrar($idarticulo){
		$sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}	

	//metodo para mostrar todos los registros de la tabla
	public function listar(){
		$sql = "SELECT a.idarticulo, a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria = c.idcategoria";
		return ejecutarConsulta($sql);
	}
}