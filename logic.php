<?php
error_reporting(E_ALL); // Error engine - always TRUE!
ini_set('ignore_repeated_errors', TRUE); // always TRUE
ini_set('display_errors', FALSE); // Error display - FALSE only in production environment or real server
ini_set('log_errors', TRUE); // Error logging engine
ini_set('error_log', 'errors.log'); // Logging file path
ini_set('log_errors_max_len', 1024); // Logging file size
ini_set('always_populate_raw_post_data','-1');
function curl($url, $request = 'GET'){
    $ch = curl_init();
    $curlopt = array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $request,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'facebook-php-2.0',
    );
    curl_setopt_array($ch, $curlopt);
    $response = curl_exec($ch);
    if($response === false)
        trigger_error(curl_error($ch));
    curl_close($ch);
    return $response;
}
function fb_api($url, $access_token = false, $request = 'GET'){
    $url = 'https://graph.facebook.com/'.$url;
    if($access_token)
        $url .= (strstr($url, '?') ? '&' : '?').'access_token='.$access_token;
    return json_decode(curl($url, $request), true);
}
include __DIR__.'\db.php';
$req = json_decode(file_get_contents( "php://input" ), true);
if ((array_key_exists('start', $req)) && isset($_COOKIE["id"]) && isset($_COOKIE["auth"])) {
	$uid=$_COOKIE["id"];
	$auth=$_COOKIE["auth"];
	$d=getdata($uid);
	echo ($d['cookie']==$auth) ? 'ok' : 'fail';
}
if (array_key_exists('city', $req)) {
	$d=getmem($req['city']);
	echo json_encode($d);
}
if (array_key_exists('poll', $req)) {
	$uid=$_COOKIE["id"];
	$auth=$_COOKIE["auth"];
	$d=getdata($uid);
	if ($d['cookie']==$auth) {
		$p=getpoll($req['poll']);
		if (array_key_exists($p['location'].$p['project'], $d)) echo 'fail';
		else{
			$d[$p['location'].$p['project']]=$p['id'];
			writedata($uid,$d);
			setpoll($p['id']);
			echo 'ok';
		}
	} else{
		echo 'unauth';
	}
}
else if (array_key_exists('login', $req)) {
	$uid=$req['login']['authResponse']['userID'];
	if (fb_api('me', $req['login']['authResponse']['accessToken'])['id']==$uid){
		$d=getdata($uid);
		$d['cookie']=md5($uid.mt_rand(100000,999999));
		setcookie("auth", $d['cookie']);
		setcookie("id", $uid);
		echo 'ok';
		writedata($uid,$d);
	} else echo 'fail';
}
?>