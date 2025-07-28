<?php
if(!isset($_SESSION)) {
    session_start();
}

if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
$GLOBALS['servername'] = "db5005350964.hosting-data.io";
$GLOBALS['username'] = "dbu1340768";
$GLOBALS['password'] = "!Pl3magmag6969!!";
$GLOBALS['databasename'] = 'dbs4487140';
?>