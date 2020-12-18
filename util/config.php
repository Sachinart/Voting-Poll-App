<?php
/**
* DATABASE related constants
*/
define("HOST", "localhost"); 			// The host you want to connect to. 
define("USER", "sachinar_cam"); 			// The database username. 
define("PASSWORD", "camerarental"); 	// The database password. 
define("DATABASE", "sachinar_vote");             // The database name.

/**
* Who can register and what the default role will be
* Values for who can register under a standard setup can be:
*      any  == anybody can register (default)
*      admin == members must be registered by an administrator
*      root  == only the root user can register members
* 
* Values for default role can be any valid role, but it's hard to see why
* the default 'member' value should be changed under the standard setup.
* However, additional roles can be added and so there's nothing stopping
* anyone from defining a different default.
*/
define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

/**
* Is this a secure connection?  The default is FALSE, but the use of an
* HTTPS connection for logging in is recommended.
* 
* If you are using an HTTPS connection, change this to TRUE
*/

/**
* Session related
*/
define("SECURE", FALSE);    // For development purposes only!!!!

/**
* Other
*/
define ('SITE_ROOT', realpath(dirname(__FILE__)));
define ('SITE_NAME', 'Voting');