<?php
    require_once("includes/connection.php");
    $_SESSION=array();
    session_destroy();
    header("location:index.php");
?>