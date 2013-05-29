<?php

/* ==============
 * Stormpath Clean-up Util
 * Stormpath_CleanUpUtil.php
 * 
 * This utility reads from the specified Stormpath app and
 * provides a report on stale users.
 * 
 * 2013, Frank Caron
 * http://www.frankcaron.com
 */

//Maximum traceability
error_reporting(-1);

//Import Stormpath lib
//require 'stormpath-sdk-php/Services/Stormpath.php';
include('httpful.phar');

//Connection vars

$appHref = "https://" . urlencode($appID) . ":" . urlencode($appSecret) . "@api.stormpath.com/v1/applications/" . $appUID;

//Mission go
echo "Alright, let's do this, monkey.<br /><br />";

//Connect
try{  
	$uri = $appHref . "/accounts";
	
	echo "Gimme " . $uri;
	x
	$response = Request::get($uri)->send();
	
	echo "yup";
	
} catch (Exception $e) {
	//Log error
	echo "Error encountered: " . $e;
}


?>