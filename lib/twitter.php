<?php
function postTweet($text)
{
    $tweet = $text;
    $data_array = array('status' => $tweet);
    $postfields = http_build_query($data_array);

    $url = "https://api.twitter.com/1.1/statuses/update.json";

    $oauth_access_token = "111340166-RgRkRq35zwpKeNgPps6O7OPQIqT0rAghB0q9bzT1";
    $oauth_access_token_secret = getenv("ACCESS_TOKEN_SECRET");
    $consumer_key = "ucE1c2KU33Tlzsp5CYIQ";
    $consumer_secret = "04hubUqfRfMwR9VH7nhcUBYpbpEdXQuAPSNVEvpmXY";

    $oauth = array( 'oauth_consumer_key' => $consumer_key,
                    'oauth_nonce' => rand() . rand(),
                    'oauth_signature_method' => 'HMAC-SHA1',
                    'oauth_token' => $oauth_access_token,
                    'oauth_timestamp' => time(),
                    'oauth_version' => '1.0',
                    'status' => $tweet);

    $base_info = buildBaseString($url, 'POST', $oauth);
    $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    $header = array(buildAuthorizationHeader($oauth));

    // Make Requests

    $options = array( CURLOPT_POST => 1,
                      CURLOPT_POSTFIELDS => $postfields,
                      CURLOPT_HEADER => false,
                      CURLOPT_HTTPHEADER => $header,
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false);
    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    return $json;
}

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
    {
        if (substr($key, 0, 5) == "oauth")
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
    }
    $r .= implode(', ', $values);
    return $r;
}
