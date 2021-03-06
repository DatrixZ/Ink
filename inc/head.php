<?php
session_start();

require_once("inc/connect.php");

$actual_link = "$_SERVER[REQUEST_URI]";
switch (true) {
	case stristr($actual_link,'/index.php'):
		$actual_link = "Ink";
		break;
	case stristr($actual_link,'/access.php'):
		$actual_link = "Access";
		break;
	case stristr($actual_link,'/Busqueda.php'):
		$actual_link = "Busqueda";
		break;
	case stristr($actual_link,'/Resultado.php'):
		$actual_link = "Resultado";
		break;
	case stristr($actual_link,'/crear_album.php'):
		$actual_link = "Creación album";
		break;
	case stristr($actual_link,'/Detalle_foto.php'):
		$actual_link = "Foto";
		break;
	case stristr($actual_link,'/Insercion.php'):
		$actual_link = "Registro correcto";
		break;
	case stristr($actual_link,'/Perfil.php'):
		$actual_link = "Perfil";
		break;
	case stristr($actual_link,'/Registro.php'):
		$actual_link = "Acceso";
		break;
	case stristr($actual_link,'/Rsolicitar.php'):
		$actual_link = "Solicitud realizada";
		break;
	case stristr($actual_link,'/Solicitar.php'):
		$actual_link = "Solicitar album";
		break;
	default:
		$actual_link = "Ink";
		break;
}
?>
<!DOCTYPE html>

<html lang="es" xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="UTF-8">
	<title><?php echo $actual_link ?></title>
	<meta name="description" content="Ink - Pictures & Images">
	<meta name="author" content="Danny Rivera">
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<link rel="icon" href="img/icon.png">
	<link rel="stylesheet" type="text/css" href="css/swag.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">
	<!--<link rel="stylesheet" type="text/css" href="../css/alternate.css">-->
	<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />

</head>

<?php
	if($error === 1){
		require("inc/error.php");
   		exit; 
	}

	if (!$inkbd->set_charset("utf8")) {
	    printf("Error cargando el conjunto de caracteres utf8: %s\n", $inkbd->error);
	    exit;
	}

	?>

<body>
	