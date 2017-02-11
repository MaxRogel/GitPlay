<?php


require_once 'config.php';
require_once 'funcs.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$nloc = RequestedLocus($_POST["loc"], $_POST["req"]);


$loc_json = GetLocusInfo($nloc);

echo $loc_json;


?>
