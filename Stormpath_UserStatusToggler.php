<?php

/* ==============
 * 2013, Frank Caron
 * http://www.frankcaron.com
 */

/* -----------------
 * GLOBAL VARS
 * ----------------
*/

//Stormpath Includes
require 'stormpath-sdk-php/Services/Stormpath.php';
require_once 'Stormpath_Credentials.php';

//Vars
$appHref = "https://" . urlencode($appID) . ":" . urlencode($appSecret) . "@api.stormpath.com/v1/applications/" . $appUID; //API url
$accountCount = 0; //account counter for form

/* -----------------
 * FUNCTIONS
 * ----------------
 */

/* Stormpathification */
//Retrieves all the directories for the current tenant
function retrieveDirectories($client) {
	$tenant = $client->getCurrentTenant();
	$directories = $tenant->getDirectories();
	return $directories;
}

//Toggle account state for the specified username
function toggleAccountState($account_username) {
	global $appHref;
	
	$builder = new Services_Stormpath_Client_ClientApplicationBuilder;
	$clientApplication = $builder->setApplicationHref($appHref)->build();
	$app_directories = retrieveDirectories($clientApplication->getClient());
	
	foreach ($app_directories as $directory) {
		$accounts = $directory->getAccounts();
		foreach ($accounts as $account) {
			if ($account->getUsername() == $account_username) {
				if ($account->getStatus() == Services_Stormpath::ENABLED) {
					$account->setStatus(Services_Stormpath::DISABLED);
				} else {
					$account->setStatus(Services_Stormpath::ENABLED); 
				}
				$account->save();
			}
		}	
	}
}

/* Script Logic */
//Enables accounts on form post-back
function handlePostback() {
	//If the page has been submitted...
	if(isset($_POST['submit']))
	{
		//Iterate through to find users to toggle
		foreach($_POST as $key => $value)
		{
			if ($value == 'on') {
				//We found a match. Parse checkbox value and toggle state.
				$usernameOfUserToToggle = substr($key, strpos($key, '_') + 1);
				toggleAccountState($usernameOfUserToToggle);
			}
		}
	} 
}

/* Beautifcation */
//Page Header
function printPageHeader() {
	echo "<html><head><link rel='stylesheet' type='text/css' href='Stormpath_CleanUpCSS.css'></head><body>";
	echo "<div style='padding: 20px;'>";
	echo "<form method='post' action='Stormpath_CleanUpUtil.php'>";
	echo "<h1>Simple Stormpath Disabled User Activator Utility</h1>";
	echo "<h3>Written with love by <a href='http://www.frankcaron.com' target='blank'>Frank Caron</a></h3>";
	echo "<p><em>This simple utility will print out a table of all the users across directories and give you the ability to toggle the states on each or all by selecting them and hitting submit.</em></p>";
	echo "<br /><hr /><br />";
}
//Page Footer
function printPageFooter() {
	echo "</table><div style='width: 100%; text-align: right;'><input type='submit' value='Toggle Enabled State' name='submit'></div>";
	echo "</form></div></body></html>";
}
//Table Header
function printTableStruct(){
	echo "<h3>Aggregated User List</h3>";
	echo "<table border='1'>";
	echo "<tr><th>Directory</th><th>Username</th><th>Status</th><th width='10'>Edit</th></tr>";
}

/* -----------------
 * MAIN APPLICATION LOGIC
 * ----------------
 */

//Main App Logic
try{  
	
	//Page Struct
	printPageHeader();
	
	//Post back handler
	handlePostback();
	
	//Connect 
	$builder = new Services_Stormpath_Client_ClientApplicationBuilder;
	$app_directories = retrieveDirectories($builder->setApplicationHref($appHref)->build()->getClient());
	
	//Table Struct
	printTableStruct();
	
	//Find Users
	foreach ($app_directories as $directory) {
		
		//Get the directory's accounts
		$accounts = $directory->getAccounts();
		
		//Print out the accounts individually
		if ($accounts->getIterator()->count() > 0) {
			foreach ($accounts as $account) {
				echo "<tr><td>" . $directory->getName() . "</td>";
				echo "<td>" . $account->getUsername() . "</td><td>" . $account->getStatus() . "</td><td style='text-align: center;'><input type='checkbox' name='" . $accountCount . "_" . $account->getUsername() . "'> ";
				echo "</tr>";
				$accountCount++;
			}
		}
	}
	
	//Page Struct
	printPageFooter();
	
} catch (Services_Stormpath_Resource_ResourceError $re)
{
    //Error Handling
	echo 'Message: ' . $re->getMessage();
    echo 'HTTP Status: ' . strval($re->getStatus()); 
    echo 'Developer Message: ' . $re->getDeveloperMessage();
    echo 'More Information: ' . $re->getMoreInfo();
    echo 'Error Code: ' . strval($re->getErrorCode());
}

?>
