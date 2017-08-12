<?php 
//requerimos la conexion a la base de datos
require '../config/conexion.php';

/**
* Creamos la clacse Categoria con su constructos
*/
Class Categoria
{
	
	public function __construct()
	{
		
	}

	//metodo para insertar registro
	public function insertar($nombre,$descripcion){
		$sql = "INSERT INTO categoria (nombre,descripcion,condicion)
				VALUES ('$nombre','$descripcion','1')";
		return ejecutarConsulta($sql);
	}

	//metodo para editar registros
	public function editar($idcategoria,$nombre,$descripcion){
		$sql = "UPDATE categoria SET nombre='$nombre', descripcion='$descripcion' 
				WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//metodo para desactivar categorias
	public function desactivar($idcategoria){
		$sql = "UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//metodo para activar categorias
	public function activar($idcategoria){
		$sql = "UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar datos de UN registro a modificar
	public function mostrar($idcategoria){
		$sql = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}	

	//metodo para mostrar todos los registros de la tabla
	public function listar(){
		$sql = "SELECT * FROM categoria";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar todos los registros y mostrar en el select 
	public function select(){
		$sql = "SELECT * FROM categoria WHERE condicion = 1";
		return ejecutarConsulta($sql);
	}
}