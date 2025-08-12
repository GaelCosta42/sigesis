<?php
require_once('bitly.php');

$client_id = '2f4b1eb5beb9b615ac70b16e34daf4bc6b899112';
$client_secret = '149e58cfb3f5d508386822be40afc8ce75ca1c26';
$user_access_token = 'b0e2fb888963b287637222a69e7e100240f67a8b';
$user_login = 'o_1g7nedo51q';
$user_api_key = 'R_3f5984ceff0f44cdae9017e908f4452c'; 
 // <-- you can obtain this for testing your app
// via the bit.ly API dashboard ( https://bitly.com/a/oauth_apps );
// confirm your password and click 'generate token'...then copy/paste here -->

$params = array();
$params['access_token'] = $user_access_token;
$params['longUrl'] = 'https://controle.sigesis.com.br';
$results = bitly_get('shorten', $params);

echo "<br/>------------<br/>";
print_r($results);
echo "<br/>------------<br/>";
echo $results['status_code'];
echo "<br/>------------<br/>";
echo $results['data']['url'];
echo "<br/>------------<br/>";
?>