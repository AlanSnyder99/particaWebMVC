<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function kill($data='Test'){
	echo "<pre>\n";
	print_r($data);
	echo "\n</pre>";
	die();
}

function vkill($data='Test'){
	echo "<pre>\n";
	var_dump($data);
	echo "\n</pre>";	
	die();
}

function qkill($that){
	print_r($that->db->last_query());
	die();
}

function jsonify($data, $ajaxStatus = null){
	if($ajaxStatus !== null) {
		$response = new stdClass();
		$response->status = $ajaxStatus;
		$response->message = $data;
	} else {
		$response = $data;
	}
	header('Content-Type: application/json');
	echo json_encode($response);
	die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

  $response = curl_exec($ch);

  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if($_SESSION['user_is_logged'])
    $headers[] = 'Authorization: Bearer ' . $_SESSION['user_is_logged'];

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

function createSoundcloudIframe($soundcloudLink){
	$resolveUrl = "https://api.soundcloud.com/resolve.json?url=".$soundcloudLink."&client_id=d438c4a17e1716c6db0c5fbefc2c8876";

	$response = apiRequest($resolveUrl);

	$iframeCode = '<iframe width="100%" height="150px" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url='. urlencode($response->uri) .'&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>';

	return $iframeCode;
}


?>