
<?php
	date_default_timezone_set('America/Santiago');require("snomed.php");
	
	$accion='POST';	$host="https://api.minsal.cl";$urlAccessToken="/oauth/token";$API_URL="$host"."$urlAccessToken";
	$key='Authorization: Basic YWEzbXVkcUFVY1hGQTB3eE1BZVA3V1V5cGVJckE3NnM6Z2YzSjFCbEhFUDlQQ0tFdg==';
	$grant_type='grant_type=client_credentials';$contenido= 'Content-Type: application/x-www-form-urlencoded';
	
	$tok=new obtenerToken;
	$tok->Token($API_URL, $key,$grant_type,$contenido,$accion);
	
	$response=obtenerToken::Token($API_URL, $key,$grant_type,$contenido,$accion);
	
	print_r("Token =>".$response);
	echo '<br>';
	$chr   = '"';
		$sub_resp=explode($chr,$response);
		$access_token=$sub_resp[3];$expires_in=$sub_resp[7];$token_type=$sub_resp[11];
	
	#***************************************************************************
	/**v1/hiba/busquedas/obtenerprompting
	 * Parametros (Autocompletar)
	 * substring: texto que ingresa el usuario.
	 * subsetid: subset del dominio problemas de salud o del dominio de procedimientos.($dom)
	 * subsetIdInstitucion: ID del software o institución.
	 * OBS: trabaja con el servicio de Obtener Código Tesauro, se debe obtener le descId para el registro clínico
	 * descId, es el Id de la descripción , clave primaria para laidentificación de las descripciones y es el Id que
	 * hay que persistir en el registro clinico.
	 *
	 * Retorna: Arreglo de 5 descripciones
	 **/
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
	#***************************************************************************	

	/** /v1/hiba/busquedas/obtenercodigotesauro
	 * Parámetros termino, subsetId, subsetIdInstitucion
	 * termino: string que el usuario eligio en el obtenerprompting, $termino
	 * subsetId: si en obtenerprompting se utilizó Dominio de Problemas de salud ($dom),$subsetId
	 * se debe utiliza el mismo para obtenercodigotesauro.
	 * subsetIdInstitucion: Id del software o Institución.
	 * OBS: Devuelve el descId de la Descripción que es el que se debe persistir en registro clínico
	 *
	 * Retorna: Devuelve el descId que identifica a esa descripcion, el que debe ser ingresado en el registro
	 * clinico Electrónico
	 **/
	 
	echo '<br>';
	echo('OBTENER CODIGO TESAURO');
	echo '<br>';
	$codigotesauro="/v1/hiba/busquedas/obtenercodigotesauro?";$termin="termino="; $termino=$substring;$subset="subsetId=";
	$API_URL=$host.$codigotesauro.$termin.$termino.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
	#$accion='POST';
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	
	echo '<br>';
	echo ($API_URL);
	echo '<br>';
	print_r("Obtener Codigo Tesauro =>".$response);
	echo '<br>';

#***************************************************************************	

	$chr   = '"';
		$sub_resp=explode($chr,$response);
		$descId=$sub_resp[49];
	
	echo '<br>';
	echo '<br>';
	echo('INFOMRACIÓN CONSUMIDA');
	echo '<br>';
	
	
	/** /v1/hiba/busquedas/informardescripcionconsumida
	 * Parámetros descId, subsetIdInstitucion
	 * $descId: id de la descripción elegida por el usuario, $subsetIdInstitucion: Id del SW o institución.
	 * OBS: Registrar en el server terminológico la descripción que fue utilizada por el cliente
	 * a fin de incrementar su frecuencia de uso. Esto se ve reflejado en el obtenerprompting.
	 * Retorna el descId que fue reportado, no es necesario guardar registro.
	 **/
	$infomacionconsumida="/v1/hiba/busquedas/informardescripcionconsumida?";$descI="descId=";#$descId="576921000999111";
	$API_URL=$host.$infomacionconsumida.$descI.$descId.$y.$subsetIdIns.$subsetIdInstitucion;
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	echo '<br>';
	echo ($API_URL);
	echo '<br>';
	print_r("Informacion Consumida =>".$response);
	echo '<br>';

#***************************************************************************

	/** v1/hiba/busquedas/obtenerofertatextos 
	 *Parámetros; texto: string de texto que ingresa el usuario para la búsqueda.
	 * subsetId: casos de usos habituales son el subset del dominio $dom1 en los campos diagnósticos o
	 * antecedentes y el subset $dom2 para el registro de campos como cirugía realizada, procedimiento
	 * realizado o antecedentes quirúrgicos, subsetIdInstitucion: Id del SW o institución.
	 * OBS: Este servicio puede utilizarse cuando el usuario no pudo elegir una opción de obtenerprompting.
	 * Se debe utilizar un botón buscar, para presentar el resultado al usuario.
	 * Retorno:
	 * entrada (MO Modelado, NV No Valido, NR No Reconocido),
	 * refinicaionobligatoria,(entrada=MO y cuenta con un código SNOMED CT.),
	 * multiplicidad (es reconocido por el server de terminología, pero hay 2 o más entidades diferentes)
	 * textopreferido (texto reconocido por server y puede o no ser el texto predefinido para nombrar a esa
	 * entidad clínica, cuando son iguales los textos el sistema debe registrar el ingreso, tal cual se hace
	 * al obtenerprompting, de lo contrario se debe mostrar el título y presentar el texto predefinido
	 * y presentar el texto que describe mejor la entidad)
	 * título (texto breve que explica la respuesta del server. Se debe presentar al usuario).
	 * explicación, explica la respuesta del server. Se puede presentar como tooltip.
	 * idDescripcion, descid texto ingresado en la búsqueda.
	 * idDescripcionPredefinido, descid de la descripción del textoPreferido. Si texto ingresado = textoPredifinido
	 * ingresar texto en la ficha, de lo contrariose debe presentar el textopredefinido para que el usuario regitre
	 * este en la ficha.
	 * legible, S= texto correcto, sin errores de ningún tipo.
	 * ofertatextodetalle, opcion que el server entrega para que el usuario elija un término válido y correctamente
	 * escrito. Cada texto ofertado cuenta con un concepid (optativo del registro), descriptionid (obligatorio) y el
	 * texto que permite presentarlo en la interfaz.
	 *
	 * CASO DE USO: Término MODELADO donde el término buscado es sinónimo de un término preferido.(HTA)
	 **/
	echo '<br>'; 
	echo('<h2>OBTENER OFERTA TEXTO</h2>');
	$ofertatextos="/v1/hiba/busquedas/obtenerofertatextos?";$text='texto=';$texto=$substring;
	$API_URL=$host.$ofertatextos;
	$API_URL=$host.$ofertatextos.$text.$substring.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
	echo($API_URL);
	echo '<br>';
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	echo '<br>';
	print_r("<h3>Obtener Oferta Texto =></h3>".$response);
	echo '<br>';
	
	
	/**#ObtenerClasificadorOfcicial
	 *Parametros subsetId,mapSetId para CIE-10 debe ser 101054, subsetIdInstitucion
	 **/
	echo '<br>'; 
	echo('<h1>OBTENER CLASIFICADOR</h1>');
	echo '<br>';
	$mapset="mapSetId=";$mapSetId="101054";$descI="descId=";
	$obtenerclasificador="/v1/hiba/busquedas/obtenerclasificador?";
	$API_URL=$host.$obtenerclasificador.$descI.$descId.$y.$mapset.$mapSetId.$y.$subsetIdIns.$subsetIdInstitucion;
		
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	
	print_r("<h5>Clasificador Oficial =></h5>".$response);
	echo '<br>';
	echo '<br>';

	/**#ObtenerClasificadorOfcicial
	 *Parametros subsetId,mapSetId para CIE-10 debe ser 101054, subsetIdInstitucion
	 **/
	echo '<br>'; 
	echo('<h1>OBTENER CLASIFICADOR ÚNICO</h1>');
	echo '<br>';
	$mapset="mapSetId=";$mapSetId="101054";$descI="descId=";
	$obtenerclasificadorunico="/v1/hiba/busquedas/obtenerclasificadorunico?";
	$API_URL=$host.$obtenerclasificadorunico.$descI.$descId.$y.$mapset.$mapSetId.$y.$subsetIdIns.$subsetIdInstitucion;
		
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	
	print_r("<h5>Clasificador Oficial =></h5>".$response);
	echo '<br>';
	echo '<br>';
	
	
	echo('<h1>OBTENER EXPRESIÓN SNOMED</h1>');
	/**#ObtenerExpresionSnomed
	 * Parametros descId, subsetIdInstitucion
	 **/
	echo '<br>';
	$ObtenerExpresionSnomed="/v1/hiba/busquedas/obtenerexpresionsnomed?";
	$API_URL=$host.$ObtenerExpresionSnomed.$descI.$descId.$y.$subsetIdIns.$subsetIdInstitucion;
	echo($API_URL);
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	print_r("<h5>Expresión SNOMED =></h5>".$response);
	echo '<br>';
	echo '<br>';
	
	
	echo('<h1>OBTENER MEMBERS COHORTE</h1>');
	/**#obtenerMembersCohorte
	 * Parametros subsetId, subsetIdInstitucion
	 **/
	echo '<br>';
	$MembersCohorte="/v1/hiba/busquedas/obtenermemberscohorte?";
	$API_URL=$host.$MembersCohorte.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
	echo($API_URL);
	echo '<br>';
	$con=new consumoApi;
	$con->Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	$response=consumoApi::Api($API_URL, $accion,$grant_type,$keytoken,$contenido);
	print_r("<h5>OBTENER MEMBERS COHORTE =></h5>".$response);
	echo '<br>';
	echo '<br>';
?>