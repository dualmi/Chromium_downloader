<?php

/**
* Downloading lastest chromium browser build for Windows
**/

if (!php_sapi_name() === 'cli')
	die('This is a console application.');

$arch = '_x64'; // Can be '' (empty) or '_x64'

$last_change = 'https://www.googleapis.com/download/storage/v1/b/chromium-browser-snapshots/o/Win' . $arch . '%2FLAST_CHANGE?alt=media';

$last_version = getUrl($last_change);

if (is_array($last_version)) {
    print_r($last_version);
    exit;
}

echo "\nChromium downloader v0.0.2c\nLast Chromium version: $last_version \n";

echo "Downloading file...\n";

$filename = 'Chromium' . $arch . '_' . $last_version . '.exe';

$binfile = 'https://www.googleapis.com/download/storage/v1/b/chromium-browser-snapshots/o/Win' . $arch . '%2F' . $last_version . '%2Fmini_installer.exe?alt=media';

$bindata = getUrl($binfile);

if (!is_array($bindata)) {
	toFile($filename, $bindata);
} else {
	print_r($bindata);
}

echo "Script execution done. Your file is $filename\n";

function getUrl($url) {
	$res = null;
	$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko');
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $content = curl_exec($ch);
        $hc = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        if ( !$err && !$errmsg && $hc < 400) {
          	$res = $content;
        } else {
        	$res = ['code' => $hc, 'err' => $err, 'errmsg' => $errmsg];
        }
    return $res;
}

function toFile($filename, $data) {
	$res = fopen($filename, 'w');
	fwrite($res, $data);
	fclose($res);
}
