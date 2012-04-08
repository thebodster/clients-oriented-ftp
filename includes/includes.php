<?php
/**
 * Requirements and inclussions of basic system files.
 *
 * @package ProjectSend
 *
 */

/** Basic system constants */
require_once('includes/sys.vars.php');

/** Text strings used on various files */
require_once('includes/vars.php');

/** Basic functions to be accessed from anywhere */
require_once('includes/functions.php');

/** Contains the session and cookies validation functions */
require_once('includes/userlevel_check.php');

/** Template list generator */
require_once('includes/templates.php');

/**
 * Always include this classes to avoid repetition of code
 * on other files.
 *
 */
require_once('includes/classes/actions-clients.php');
require_once('includes/classes/actions-users.php');
require_once('includes/classes/actions-files.php');
?>