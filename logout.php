<?php 
session_start();
header("Cache-control: private");
session_unset();
session_destroy();
header("location:index.php");
?>