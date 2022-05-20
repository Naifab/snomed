<!DOCTYPE html>
<html lang="es">
</html>

<?php
date_default_timezone_set('America/Santiago');
$host="https://api.minsal.cl";$urlAccessToken="/oauth/token";$API_URL="$host"."$urlAccessToken";$accion='POST';
$key='Authorization: Basic YWEzbXVkcUFVY1hGQTB3eE1BZVA3V1V5cGVJckE3NnM6Z2YzSjFCbEhFUDlQQ0tFdg==';
$grant_type='grant_type=client_credentials';$contenido= 'Content-Type: application/x-www-form-urlencoded';

/** $dom1: Dominio Problemas de Salud. $Dom2: Dominio de Procedimientos.
 *  $dom1="6010000999132"; $dom2="63110000999139";
 **/

$dom1="6010000999132"; $dom2="63110000999139";$institucion="48241000999134";
#***************************************************************************
#Access Token
echo 'OBTENER TOKEN';
echo '<br>';
echo($API_URL);
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$key,$contenido],));
$response = curl_exec($curl);
$chr   = '"';
$sub_resp=explode($chr,$response);
$access_token=$sub_resp[3];$expires_in=$sub_resp[7];$token_type=$sub_resp[11];
curl_close($curl);
echo '<br>';
echo 'access_token:= '.$access_token;
echo '<br>';
echo 'expires_in:= '.$expires_in;
echo '<br>';
echo 'token_type:= '.$token_type;echo '<br>';
print_r ('RESPONSE:= '.$response);
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';

#/v1/hiba/busquedas/obtenerprompting
echo 'OBTENER PROMPTING';
/**Parametros substring: texto que ingresa el usuario, subsetid: subset del dominio problemas
 * de salud ($dom1) o del dominio de procedimientos ($dom2), subsetIdInstitucion: ID del software
 * o institución.
 * OBS: trabaja con el servicio de Obtener Código Tesauro, se debe obtener le descId para el
 * registro clínico
 **/
echo '<br>';
$promptin="/v1/hiba/busquedas/obtenerprompting?";$keytoken='Authorization: Bearer '.$access_token;
$substr="substring=";$subset="subsetid=";$subsetIdIns='subsetIdInstitucion=';$y='&';
$substring="DIABETES";$subsetid=$dom1;$subsetIdInstitucion=$institucion;$accion='GET';
$API_URL=$host.$promptin.$substr.$substring.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;

echo ($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],]);
$response = curl_exec($curl);
curl_close($curl);
print_r ($response);
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************

echo('OBTENER CÓDIGO TESAURO');
echo '<br>';
/** Parámetros termino, subsetId, subsetIdInstitucion
 * termino: string que el usuario eligio en el obtenerprompting, $termino
 * subsetId: si en obtenerprompting se utilizó Dominio de Problemas de salud ($dom1),$subsetId
 * se debe utiliza el mismo para obtenercodigotesauro.
 * subsetIdInstitucion: Id del software o Institución.
 * OBS: Devuelve el descId de la Descripción que es el que se debe persistir en registro clínico
 **/
$codigotesauro="/v1/hiba/busquedas/obtenercodigotesauro?";$termin="termino="; $termino=$substring;
$API_URL=$host.$codigotesauro.$termin.$termino.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL =>$API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************

echo('INFOMRACIÓN CONSUMIDA');
echo '<br>';
/**Parámetros descId, subsetIdInstitucion
 * $descId: id de la descripción elegida por el usuario, $subsetIdInstitucion: Id del SW o institución.
 * OBS: Registrar en el server terminológico la descripción que fue utilizada por el cliente
 * a fin de incrementar su frecuencia de uso. Esto se ve reflejado en el obtenerprompting.
 * Retorna el descId que fue reportado, no es necesario guardar registro
 **/
$infomacionconsumida="/v1/hiba/busquedas/informardescripcionconsumida?";$descI="descId=";$descId="576921000999111";
$API_URL=$host.$infomacionconsumida.$descI.$descId.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL =>$API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************

echo('OBTENER OFERTA TEXTO');
/** Parámetros; texto: string de texto que ingresa el usuario para la búsqueda.
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
 **/
echo '<br>';
$ofertatextos="/v1/hiba/busquedas/obtenerofertatextos?";
$API_URL=$host.$ofertatextos;
$API_URL=$host.$ofertatextos.$substr.$substring.$y.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL =>$API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************




echo('OBTENER SUB SETS POR DESCRIPCIÓN ID');
echo '<br>';
/** Parámetros descId, flag=1, subsetIdInstitucion; Dominios de busqueda.
 * $dom1: Dominio Problemas de Salud. $Dom2: Dominio de Procedimientos.
 * $dom1="6010000999132"; $dom2="63110000999139";
 **/
$ubsetsxdescid="/v1/hiba/busquedas/obtenersubsetsxdescid?";$descI="descId=";$flag="flag=1";
$API_URL=$host.$ubsetsxdescid.$descI.$descId.$y.$flag.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl=curl_init();
curl_setopt_array($curl,array(
    CURLOPT_URL=>$API_URL,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_ENCODING=>'',
    CURLOPT_MAXREDIRS=>10,
    CURLOPT_TIMEOUT=>0,
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST=>$accion,
    CURLOPT_POSTFIELDS=>$grant_type,
    CURLOPT_HTTPHEADER=> [$keytoken,$contenido],));

$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************

echo('OBTENER CLASIFICADOR');
/**#Parametros subsetId,mapSetId, subsetIdInstitucion
 * $descId: Clave primaria para la identificación de las descripciones.
 * mapSetId: Id que indica el mapeo a utilizar (101054, CIE10; 101051, CIE10 MAPEO OFICIAL OMS)
 **/
echo '<br>';
$mapset="mapSetId=";$mapSetId="101054";$descI="descId=";
$obtenerclasificador="/v1/hiba/busquedas/obtenerclasificador?";
$API_URL=$host.$obtenerclasificador.$descI.$descId.$y.$mapset.$mapSetId.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
#***************************************************************************

#ObtenerClasificadorUnico
echo('OBTENER CLASIFICADOR ÚNICO');
/**
 * Parametros subsetId,mapSetId, subsetIdInstitucion
 **/
echo '<br>';
$mapset="mapSetId=";$mapSetId="101054";$descI="descId=";
$obtenerclasificadorunico="/v1/hiba/busquedas/obtenerclasificadorunico?";
$API_URL=$host.$obtenerclasificadorunico.$descI.$descId.$y.$mapset.$mapSetId.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';
echo '<br>';
#***************************************************************************

#ObtenerClasificadorOfcicial
echo('OBTENER CLASIFICADOR OFICIAL');
/**
 *Parametros subsetId,mapSetId, subsetIdInstitucion
 **/

echo '<br>';
$obtenerclasificadoroficial="/v1/hiba/busquedas/obtenerclasificadoroficial?";
$API_URL=$host.$obtenerclasificadoroficial.$descI.$descId.$y.$mapset.$mapSetId.$y.$subsetIdIns.$subsetIdInstitucion;

echo($API_URL);
echo '<br>';
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => [$keytoken,$contenido],));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
echo '<br>';
echo '<br>';
echo'***************************************************************************';
echo '<br>';
echo '<br>';

#ObtenerExpresionSnomed
echo('OBTENER EXPRESIÓN SNOMED');
/**
 * Parametros descId, subsetIdInstitucion
 **/
echo '<br>';
$descI="descId=";
$ObtenerExpresionSnomed="/v1/hiba/busquedas/obtenerexpresionsnomed?";
$API_URL=$host.$ObtenerExpresionSnomed.$descI.$descId.$y.$subsetIdIns.$subsetIdInstitucion;
echo($API_URL);
echo '<br>';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => array($keytoken,$contenido),));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
echo '<br>';
echo '<br>';


#obtenerMembersCohorte
echo('OBTENER MEMBERS COHORTE');
/**
 * Parametros subsetId, subsetIdInstitucion
 **/
echo '<br>';
$subset="subsetId=";$subsetid="29621000999131";
$MembersCohorte="/v1/hiba/busquedas/obtenermemberscohorte?";
$descI="descId=";
$ObtenerExpresionSnomed="/v1/hiba/busquedas/obtenerexpresionsnomed?";
$API_URL=$host.$MembersCohorte.$subset.$subsetid.$y.$subsetIdIns.$subsetIdInstitucion;
echo($API_URL);
echo '<br>';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $API_URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $accion,
    CURLOPT_POSTFIELDS => $grant_type,
    CURLOPT_HTTPHEADER => array($keytoken,$contenido),));

$response = curl_exec($curl);

curl_close($curl);
echo $response;



/**v1/hiba/busquedas/obtenersubsetsxdescid
 * Parametros
 * obtenersubsetsxdescid:
 * descId
 * flag
 * subsetIdInstitucion
 */

$obtenersubsetsxdescid="/v1/hiba/busquedas/obtenersubsetsxdescid?";$fla='flag=';$flag=1;
$API_URL=$host.$obtenersubsetsxdescid.$descI.$descId.$y.$flag.$y.$subsetIdIns.$subsetIdInstitucion;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.minsal.cl/v1/hiba/busquedas/obtenersubsetsxdescid?descId=576921000999111&flag=1&subsetIdInstitucion=48241000999134',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer nW3aKOcfLmshFCdI43VEJvPcEnAX',
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

