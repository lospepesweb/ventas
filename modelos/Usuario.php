<?php 
//requerimos la conexion a la base de datos
require '../config/conexion.php';

/**
* Creamos la clacse Usuario con su constructos
*/
Class Usuario {
	
	public function __construct(){
		
	}

	//metodo para insertar registro
	public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos){
		$sql = "INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,condicion)
				VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1')";
		// return ejecutarConsulta($sql);
		$idUsuarioNew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($permisos)) {
			$sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idUsuarioNew', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos = $num_elementos + 1; 
		}

		return $sw;
	}

	//metodo para editar registros
	public function editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$imagen,$permisos){
		$sql = "UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen'
				WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql);

		//eliminamos todos los permisos que tenga el usuario y cargamos los nuevos
		$sqldel = "DELETE FROM usuario_permiso WHERE idusuario = '$idusuario'";
		ejecutarConsulta($sqldel);

		//cargamos los nuevos permisos asignados
		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($permisos)) {
			$sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idusuario', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos = $num_elementos + 1; 
		}

		return $sw;

	}

	//metodo para desactivar categorias
	public function desactivar($idusuario){
		$sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//metodo para activar categorias
	public function activar($idusuario){
		$sql = "UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar datos de UN registro a modificar
	public function mostrar($idusuario){
		$sql = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}	

	//metodo para mostrar todos los registros de la tabla
	public function listar(){
		$sql = "SELECT * FROM usuario";
		return ejecutarConsulta($sql);
	}

	//implementar un método para listar los permios marcados
	public function listarMarcados($idusuario){
		$sql = "SELECT * FROM usuario_permiso WHERE idusuario = '$idusuario'";
		return ejecutarConsulta($sql);	
	}

	//verificar si el usuario tiene acceso al sistema
	public function verificar($login, $clave){
		$sql = "SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login FROM usuario WHERE login='$login' AND clave = '$clave' AND condicion = '1'";
		return ejecutarConsulta($sql);
	}

}