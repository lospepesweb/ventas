<?php 
//requerimos la conexion a la base de datos
require '../config/conexion.php';

/**
* Creamos la clacse Categoria con su constructos
*/
Class Permiso
{
	
	public function __construct()
	{
		
	}

	//metodo para mostrar todos los registros de la tabla
	public function listar(){
		$sql = "SELECT * FROM permiso";
		return ejecutarConsulta($sql);
	}

}