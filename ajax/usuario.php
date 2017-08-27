<?php
session_start();
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$claveActual=isset($_POST["claveActual"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET['op']) {
	case 'guardaryeditar':
 
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                $imagen=$_POST["imagenActual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png"){
                    $imagen = round(microtime(true)) . '.' . end($ext);
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }
        //Hash SHA256 en la contraseña
        $clavehash=hash("SHA256",$clave);
 
        if (empty($idusuario)){
            $rspta=$usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
            echo $rspta ? 'ok' : 'nok';
        } else {
            $rspta=$usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
            echo $rspta ? 'oka' : 'noka';
        }
    break;

	case 'desactivar':
		$rspta = $usuario->desactivar($idusuario);
		echo $rspta ? 'UsuDes' : 'NoUsuDes';
	break;

	case 'activar':
		$rspta = $usuario->activar($idusuario);
		echo $rspta ? 'UsuHab' : 'NoUsuHab';
	break;

	case 'mostrar':
		$rspta = $usuario->mostrar($idusuario);
		//codificamos usando json
		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta = $usuario->listar();
		//declaramos array donde se van a mostrar todos los registros de la tabla
		$data = Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>($reg->condicion)?'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')" title="Desactivar"><i class="fa fa-close"></i></button></center>':
					'<center><button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
					' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')" title="Activar"><i class="fa fa-check"></i></button></center>',
				"1"=>'<center>'.$reg->nombre.'</center>',
				"2"=>'<center>'.$reg->tipo_documento.'</center>',
				"3"=>'<center>'.$reg->num_documento.'</center>',
				"4"=>'<center>'.$reg->telefono.'</center>',
				"5"=>'<center>'.$reg->email.'</center>',
				"6"=>'<center>'.$reg->login.'</center>',
				"7"=>"<center><img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
				"8"=>($reg->condicion)?'<center><span class="label bg-green">Activada</span></center>':'<center><span class="label bg-red">Desactivada</span></center>'
		    );
	    }
		$results = array(
			"sEcho"=>1, //Informacion para el datatable
			"iTotalRecords"=>count($data), //enviamos el total de registros al datatables
			"iTotalDisplayRecords"=>count($data),//enviamos total de registros a visualizar
			"aaData"=>$data
		);
		echo json_encode($results);
	break;
		
	case 'permisos':
        //Obtenemos todos los permisos de la tabla permisos
        require_once "../modelos/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        //Obtener los permisos asignados al usuario
        $id=$_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores=array();

        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object())
            {
                array_push($valores, $per->idpermiso);
            }

        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object())
                {
                    $sw=in_array($reg->idpermiso,$valores)?'checked':'';
                    echo '<li> <input type="checkbox" '.$sw.'  name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
                }
    break;
   
    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];

         //Hash SHA256 en la contraseña
        $clavehash = hash('SHA256', $clavea);
        $rspta = $usuario->verificar($logina, $clavehash);

        $fetch = $rspta->fetch_object();

        if (isset($fetch)){
           //Declaramos las variables de sesión
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;

            //Obtenemos los permisos del usuario
            $marcados = $usuario->listarMarcados($fetch->idusuario);
            
            //Declaramos el array para almacenar todos los permisos marcados
            $valores = array();

            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }

            //determinamos los accesos del usuario
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;            
            in_array(3, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;            
            in_array(4, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;            
            in_array(5, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;            
            in_array(6, $valores) ? $_SESSION['consultac'] = 1 : $_SESSION['consultac'] = 0;            
            in_array(7, $valores) ? $_SESSION['consultav'] = 1 : $_SESSION['consultav'] = 0;            
        }
        echo json_encode($fetch);

    break;

    case 'salir':
        //limpiamos las variables de sesión
        session_unset();
        //destruimos la sesison
        session_destroy();
        //redireccionamos al login
        header('Location: ../index.php');
    break;
}