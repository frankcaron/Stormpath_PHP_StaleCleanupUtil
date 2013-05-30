Stormpath Disabled User Clean-up Utility
=================

This is a simple PHP utility which will return a report of disabled
users in a specified Stormpath application and provide a control to bulk enable them. 

This is an example of using the Stormpath PHP SDK for the purposes of manipulating
a cloud-based, federated user store enabled by Stormpath's solution.

Graciously borrows the prettify CSS from http://meyerweb.com/eric/tools/css/reset/  

Prereqs
--------------
In order to make this script work for you, you'll need to add a php file to the root called 
"Stormpath_credentials" with the following contents:

    <?php
    //Credentials for the Stormpath app
    //Do not include in git
    $appID = "your_api_id";
    $appSecret = "your_api_secret";
    $appUID = 'your_app_id';
    ?>

Contributions
================

2013, Frank Caron  
http://www.frankcaron.com