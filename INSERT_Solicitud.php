<?php 
	require_once("inc/head.php"); 
	require_once("inc/header_logged.php"); 
	require_once("inc/validation.php");

	$host = $_SERVER["HTTP_HOST"];
	$uri  = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");

	if(!isset($_POST["name"])){
		$host = $_SERVER["HTTP_HOST"];
		$uri  = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
		header("Location: http://$host$uri/Solicitar.php");
		exit;
	}

	$name = $talbum = $destinatario = $adicional = $direccion = $color = $copias = $res = $album = $date = $acolor = "";
	
	//$name = 		validate_input($_POST["name"])." ".validate_input($_POST["surname"]);

	$name = 		validate_input($_POST["name"]);
	$surname = 		validate_input($_POST["surname"]);
	$talbum = 		validate_input($_POST["talbum"]);
	$destinatario = validate_input($_POST["destinatario"]);
	$adicional = 	validate_input($_POST["adicional"]);

	$direccion = 	validate_input($_POST["direccion"]);
	$direccion2 = 	validate_input($_POST["direccion2"]);
	//$direccion = 	validate_input($_POST["direccion"])." ".validate_input($_POST["direccion2"]).", <br>".validate_input($_POST["ciudad"]).", ".validate_input($_POST["provincia"])." ".validate_input($_POST["cp"])."<br>".validate_input($_POST["pais"]);
	$pais = 		validate_input($_POST["pais"]);
	$ciudad = 		validate_input($_POST["ciudad"]);
	$provincia = 	validate_input($_POST["provincia"]);
	$cp = 			validate_input($_POST["cp"]);
	$color = 		validate_input($_POST["color"]);
	$copias = 		intval(validate_input($_POST["copias"]));
	$res = 			intval(validate_input($_POST["res"]));
	$album = 		intval(validate_input($_POST["album"]));
	$date = 		validate_input($_POST["date"]);
	$acolor = 		intval(validate_input($_POST["acolor"]));

	$datosYerrores = array(
		0 => array($name, ""),			//Nombre
		1 => array($surname, ""),		//Nombre 2
		2 => array($talbum, ""),		//Titulo album
		3 => array($destinatario, ""),	//Destinatario
		4 => array($adicional, ""),		//Comentario
		5 => array($direccion, ""),		//Direccion
		6 => array($direccion2, ""),	//Direccin 2
		7 => array($pais, ""),			//Pais
		8 => array($ciudad, ""),		//Ciudad
		9 => array($provincia, ""),		//Provincia
	   10 => array($cp, ""),			//Codigo postal
	   11 => array($color, ""),			//Color
	   12 => array($copias, ""),		//Numero de copias
	   13 => array($res, ""),			//Resolucion
	   14 => array($album, ""),			//Album
	   15 => array($date, ""),			//Fecha de recepcion
	   16 => array($acolor, "")			//A color?
	);
	
	$fail_detector = false;

	$datosYerrores[0][1] = validate_realname($name);
	if (!empty($surname))
		$datosYerrores[0][1] = $datosYerrores[0][1] ? $datosYerrores[0][1] : validate_realname($surname, 0);
	$datosYerrores[3][1] = validate_email($destinatario, 0);
	$datosYerrores[7][1] = validate_pais($pais);
	$datosYerrores[8][1] = validate_city($ciudad);
	$datosYerrores[9][1] = validate_city($provincia) ? "La provincia solo puede contener letras" : "";
   $datosYerrores[10][1] = validate_cp($cp);
   $datosYerrores[11][1] = validate_color($color);

   	if($copias < 1)
   		$datosYerrores[12][1] = "Elige un valor válido.";
   	if($res != 150 && $res != 300 && $res != 450 && $res != 600 && $res != 750 && $res != 900)
   		$datosYerrores[13][1] = "Elige un valor válido.";

   	$datosYerrores[14][1] = validate_album($album);
   	$datosYerrores[15][1] = validate_date2($date);
   	if($acolor != 0 && $acolor != 1)
   		$datosYerrores[16][1] = "Elige un valor válido.";

	$_SESSION['datosYerrores'] = $datosYerrores;

	if($fail_detector){
		header("Location: http://$host$uri/Solicitar.php");
		exit;
	}

//$direccion = 	validate_input($_POST["direccion"])." ".validate_input($_POST["direccion2"]).", <br>".validate_input($_POST["ciudad"]).", ".validate_input($_POST["provincia"])." ".validate_input($_POST["cp"])."<br>".validate_input($_POST["pais"]);
	

	$paginas=15;
	$precio=0;
	switch ($res) {
		case '150':
			$precio+=0.8*$paginas;
			if($acolor=="Color")
				$precio+=1;
			else
				$precio+=0.7;
			break;
		case '300':
			$precio+=1.2*$paginas;
			if($acolor=="Color")
				$precio+=2.5;
			else
				$precio+=2.7;
			break;
		case '450':
			$precio+=1.6*$paginas;
			if($acolor=="Color")
				$precio+=4;
			else
				$precio+=3.5;
			break;
		case '600':
			$precio+=2.4*$paginas;
			if($acolor=="Color")
				$precio+=8;
			else
				$precio+=7.3;
			break;
		case '750':
			$precio+=3.2*$paginas;
			if($acolor=="Color")
				$precio+=12;
			else
				$precio+=10;
			break;
		case '900':
			$precio+=4*$paginas;
			if($acolor=="Color")
				$precio+=15;
			else
				$precio+=12.5;
			break;
	}

	$adicional = $adicional ? $adicional : "-";

	$datos = "SELECT NomPais, Titulo FROM `paises`, `albumes` WHERE IdPais='".$pais."' AND IdAlbum =".$album;
	if(!($resultado = $inkbd->query($datos))) { 
		echo "<p>Error al ejecutar la sentencia <b>$datos</b>: " . $inkbd->error; 
		echo "</p>"; 
		exit;
	}
	$datos = $resultado->fetch_assoc();

	$name = $name." ".$surname;
	$direccion = $direccion." ". $direccion2 . ", <br>" . $ciudad . "," . $provincia . " " . $cp . "<br>" . $datos['NomPais'];

	$insert ="INSERT INTO `solicitudes`(`Album`, `Nombre`, `Titulo`, `Descripcion`, `Email`, `Direccion`, `Color`, `Copias`, `Resolucion`, `Fecha`, `IColor`, `Coste`) VALUES (".$album.",'".$name."','".$talbum."','".$adicional."','".$destinatario."','".$direccion."','".$color."',".$copias.",".$res.",'".$date."',".$acolor.",".$precio.")";
	if(!($inkbd->query($insert))) { 
		echo "<p>Error al ejecutar la sentencia <b>$insert</b>: " . $inkbd->error; 
		$_SESSION["error"] = "Hubo un error al procesar la solicitud. Inténtelo de nuevo.";
	   	header("Location: http://$host$uri/Solicitar.php");
		exit;
	}
	else{
		header("Location: http://$host$uri/Insercion_Solicitud.php?id=".$inkbd->insert_id);
		exit;
	}
?>