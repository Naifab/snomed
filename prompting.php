<?php
	
	date_default_timezone_set('America/Santiago');require("snomed.php");
			
	$accion='POST';	$host="https://api.minsal.cl";$urlAccessToken="/oauth/token";$API_URL="$host"."$urlAccessToken";
	$key='Authorization: Basic YWEzbXVkcUFVY1hGQTB3eE1BZVA3V1V5cGVJckE3NnM6Z2YzSjFCbEhFUDlQQ0tFdg==';
	$grant_type='grant_type=client_credentials';$contenido= 'Content-Type: application/x-www-form-urlencoded';
	$institucion="48241000999134"; 
	
	$tok=new obtenerToken;
	$tok->Token($API_URL, $key,$grant_type,$contenido,$accion);
	$response=obtenerToken::Token($API_URL, $key,$grant_type,$contenido,$accion);
	echo '<br>';
	echo('<pre>');		
	print_r("Token =>".$response);
	echo('<pre>');
	
	$chr   = '"';
		$sub_resp=explode($chr,$response);
		$access_token=$sub_resp[3];$expires_in=$sub_resp[7];$token_type=$sub_resp[11];
			
	$keytoken='Authorization: Bearer '.$access_token;
	$substr="substring=";$subset="subsetId=";$subsetIdIns='subsetIdInstitucion=';$y='&';
			
		$dom=$_POST['dom'];
		$substring=$_POST['substring'];
		$subsetid=$dom;$subsetIdInstitucion=$institucion;$accion='GET';
	
	
	
	echo '<br>';
	echo('OBTENER PROMPTING');
	echo '<br>';
	
	$institucion="48241000999134"; $promptin="/v1/hiba/busquedas/obtenerprompting?";
	$keytoken='Authorization: Bearer '.$access_token;
	$substr="substring=";$subset="subsetid=";$subsetIdIns='subsetIdInstitucion=';$y='&';
	$dom=$_POST['dom'];
	$substring=$_POST['substring'];
	$subsetid=$dom;$subsetIdInstitucion=$institucion;$accion='GET';
	$API_URL=$host.$promptin.$substr.$substring.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
	
	$prom=new consumoApi;
	$prom->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	echo '<br>';
	echo ($API_URL);
	echo '<br>';
	print_r("Obtener Prompting =>".$response);
	echo '<br>';
	/*
	echo '<br>'; 
	echo('OBTENER OFERTA TEXTO  ...... de paso');echo '<br>'; 
	$ofertatextos="/v1/hiba/busquedas/obtenerofertatextos?";$text='texto=';$texto=$substring;
	$API_URL=$host.$ofertatextos;
	$API_URL=$host.$ofertatextos.$text.$substring.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
	echo($API_URL);
	echo '<br>';
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	echo '<br>';
	echo('<pre>');
	var_dump(json_decode($response, true));
	echo('<pre>');
	echo '<br>';
	
	*/
	