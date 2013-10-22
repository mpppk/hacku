<?php 
require_once(dirname(__FILE__) . "/php/config.php");
require_once(dirname(__FILE__) . "/php/functions.php");

$stamprally = new StampRally((int)$_POST["id"]);
$stamprally->remove();
echo "finished";
?>




