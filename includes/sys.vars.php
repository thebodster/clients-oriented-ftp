<?php
// Enter your database info here

define('DB_NAME', 'cftp1'); // database name
define('DB_HOST', 'localhost'); // database host (most of the times its localhost)
define('DB_USER', 'root'); // user related to cftp's database
define('DB_PASSWORD', ''); // the password for that user

define('MIN_USER_CHARS', 6);
define('MAX_USER_CHARS', 16);
define('MIN_PASS_CHARS', 6);
define('MAX_PASS_CHARS', 16);

require_once('db_class.php');

// this settings are temporally located here. will be located elsewhere on following udpates
$logo_thumbnail_folder = '../img/custom/thumbs/';
$user_thumbs_folder = '../upload/thumbs/';
?>