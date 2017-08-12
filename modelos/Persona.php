<?php 
//requerimos la conexion a la base de datos
require '../config/conexion.php';

/**
* Creamos la clacse Categoria con su constructos
*/
Class Persona
{
	
	public function __construct()
	{
		
	}

	//metodo para insertar registro
	public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email){
		$sql = "INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email)
				VALUES ('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email')";
		return ejecutarConsulta($sql);
	}

	//metodo para editar registros
	public function editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email){
		$sql = "UPDATE persona SET tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email' 
				WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//metodo para eliminar registros
	public function eliminar($idpersona){
		$sql = "DELETE FROM persona WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar datos de UN registro a modificar
	public function mostrar($idpersona){
		$sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
		return ejecutarConsultaSimpleFila($sql);
	}	

	//metodo para mostrar todos los PROVEEDORES de la tabla
	public function listarp(){
		$sql = "SELECT * FROM persona WHERE tipo_persona='Proveedor'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar todos los CLIENTES de la tabla
	public function listarc(){
		$sql = "SELECT * FROM persona WHERE tipo_persona='Cliente'";
		return ejecutarConsulta($sql);
	}

}