
<?php

include("actions.php");
$db = new DB;
$db->OpenConnection();
$db->Install();
$db->CloseConnection();

?>