<?php

# Basic cURL HTTP request to fetch remote HTML data from URL, with a file_get_contents method as fallback.
# First choice is Curl since it's much faster and flexible yet we only use simple settings for the HTTP call. 

function grab_file_content($rawurl, $response = false) {

	$url = htmlspecialchars($rawurl, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

    if(filter_var($url, FILTER_VALIDATE_URL)) {
        $file_headers = @get_headers($url);
        if(!$file_headers || $file_headers[0] === 'HTTP/1.1 404 Not Found') { return $response; }
    }

	if(is_callable('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		curl_close($ch);
	} 

	if(empty($response) || !is_callable('curl_init')) {
		$opts = array('http'=>array('header' => 'Connection: close'));
		$context = stream_context_create($opts);
		$headers = get_headers($url);
		$httprequest = substr($headers[0], 9, 3);
		if($httprequest == '200') {
			$response = @file_get_contents($url, false, $context);
		}
	}
	return $response;
}

$urlcontent = grab_file_content("https://spunr.com");
echo $urlcontent ? "Data was fetched." : "Couldn't fetch data."; 

?>