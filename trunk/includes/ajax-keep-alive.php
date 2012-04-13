<?php
/**
 * File that keeps alive the session when uploading files.
 * Prevents the following case from happening:
 * If "remember me" is not selected, after finishing uploading
 * a big file, the user is returned to the log in form since the
 * session has expired.
 * Used on upload-from-computer.php.
 *
 * @package ProjectSend
 */
$random = rand(1,1000000);
echo $_GET['timestamp'].'-'.$random;
?>