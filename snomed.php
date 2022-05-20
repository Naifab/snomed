<?php
class obtenerToken{
	public static function Token($API_URL, $key,$grant_type,$contenido,$accion){
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
		curl_close($curl);
	return $response;
	}
}

class consumoApi{
	public static function Api ($API_URL, $accion,$grant_type,$keytoken,$contenido){
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
	return $response;
	}
}
?>