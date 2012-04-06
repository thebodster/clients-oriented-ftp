<?php
// Enter your database info here
define('DB_NAME', 'database'); // database name
define('DB_HOST', 'localhost'); // database host (usually localhost)
define('DB_USER', 'username'); // user related to the database
define('DB_PASSWORD', 'password'); // the password for that user

// define the site language (this value corresponds to the name of the file (without extension) to be loaded
define('SITE_LANG','en');

define('MAX_FILESIZE',2048); // define a maximum size (in mb) per file to upload
define('EMAIL_ENCODING', 'utf-8') // encoding to use on emails sent to new clients, users, etc.
?>