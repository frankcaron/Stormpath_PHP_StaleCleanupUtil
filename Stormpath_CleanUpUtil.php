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
require 'stormpath-sdk-php/Services/Stormpath.php';

//Import credentials file
require_once 'Stormpath_Credentials.php';

//Connection vars
$appHref = "https://" . urlencode($appID) . ":" . urlencode($appSecret) . "@api.stormpath.com/v1/applications/" . $appUID;

//Connect
try{  
	
	//Mission go
	echo "Alright, let's do this, Apollo.<br /><br />";

	$builder = new Services_Stormpath_Client_ClientApplicationBuilder;
	$clientApplication = $builder->setApplicationHref($appHref)->build();
	
	$client = $clientApplication->getClient();
	$application = $clientApplication->getApplication();
	
	echo "Apollo, we have no problem.<br /><br />";
	
} catch (Exception $e) {
	//Log error
	echo "Apollo, we had a problem:<br /><br /> " . $e;
}


?>