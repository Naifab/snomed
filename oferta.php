<?php
/**
 *IMPLEMENTACIÓN
 * Para la utilización de este método de búsqueda, debemos respetar el siguiente orden:
 * 1.- Utilizar obtenerofertatextos para realizar la búsqueda de los diagnósticos.
 * 2.- obtenerclasificadorunico, Luego con el DescId obtenemos el CIE-10
 * 3.- obtenerexpresionsnomed, Obtenemos la Expresión SNOMED
 * 4.- informardescripcionconsumida, Informamos al MINSAL que diagnostico estamos utilizando
 * 5.- obtenerconceptxdescid, Obtenemos el concepto del diagnóstico seleccionado
 *
 *
 * Parámetro            Método donde obtenerlo
 * Texto Seleccionado   obtenerofertatextos
 * Descripción ID       obtenerofertatextos
 * Código CIE-10        obtenerclasificadorunico
 * Descripción CIE-10   obtenerclasificadorunico
 **/
	date_default_timezone_set('America/Santiago');require("snomed.php");
			
	$accion='POST';	$host="https://api.minsal.cl";$urlAccessToken="/oauth/token";$API_URL="$host"."$urlAccessToken";
	$key='Authorization: Basic YWEzbXVkcUFVY1hGQTB3eE1BZVA3V1V5cGVJckE3NnM6Z2YzSjFCbEhFUDlQQ0tFdg==';
	$grant_type='grant_type=client_credentials';$contenido= 'Content-Type: application/x-www-form-urlencoded';
	$institucion="48241000999134"; $y='&'; $substr="substring=";$subset="subsetId=";$subsetIdIns='subsetIdInstitucion=';
	
	$tok=new obtenerToken;
	$tok->Token($API_URL, $key,$grant_type,$contenido,$accion);
	$response=obtenerToken::Token($API_URL, $key,$grant_type,$contenido,$accion);
	
		echo '<br>';
	        echo("Token Autorizado ...!!");
		echo('<pre>');

			$chr   = '"';
			$sub_resp=explode($chr,$response);
			$access_token=$sub_resp[3];$expires_in=$sub_resp[7];$token_type=$sub_resp[11];
			$keytoken='Authorization: Bearer '.$access_token;

    //$JSon=json_encode($response);
    //$JSon=$response;
    $bytes=file_put_contents('json/token.json',$response);
    echo '<br>';
    echo('Archivo guardado (token.json)');
    echo '<br>';
	//if(isset($_POST["dom"]) && !empty($_POST["dom"])) {
        //$dom = $_POST['dom'];
        $substring ="HTA" ;//$_POST['substring'];
        $subsetid ="601000999132";// $dom;
        $subsetIdInstitucion = $institucion;
        $accion = 'GET';

        echo '<br>';
        echo('OBTENER OFERTA TEXTO');
        echo '<br>';

        /**  [descriptionId]
         *   [texto]
         */

        $ofertatextos = "/v1/hiba/busquedas/obtenerofertatextos?";
        $text = 'texto=';
        $texto = $substring;
        $API_URL = $host . $ofertatextos;
        $API_URL = $host . $ofertatextos . $text . $substring . $y . $subset . $subsetid . $y . $subsetIdIns . $subsetIdInstitucion;

             echo($API_URL);
             echo '<br>';

        $con = new consumoApi;
        $con->Api($API_URL, $accion, $grant_type, $keytoken, $contenido);
        $response = consumoApi::Api($API_URL, $accion, $grant_type, $keytoken, $contenido);
        $JSon=$response;
        echo '<br>';

        $bytes=file_put_contents('json/ofertatexto.json',$response);
        echo '<br>';
        echo('Archivo guardado (ofertatexto.json)');
        echo '<br>';


        echo('<pre>');
        print_r(json_decode($JSon, true));
        echo('<pre>');
        echo '<br>';








        /*
        for ($a=1;$a<count($JsonParser);$a++) {
            echo $JsonParser . "\n";
        }
        echo"<pre>";
        print_r($JsonParser);
        echo"<pre>";
       */


    //} else {
      //  echo("Falta dominio de busqueda");
    //}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
	</head>
	<body>
	<!--	<form method="POST">
			<br>
			<label>Ingrese DescId (descriptionId)<label/>
			<input type ="text" name ="descId" placeholder="palabra a buscar... " required>
			<button type ="descId">Enviar</button>
		</form>
	-->
	</body>
</html>
