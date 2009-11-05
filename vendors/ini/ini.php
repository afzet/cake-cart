<?php
require_once ('packages/sys/Ini.class.php');

$ini = new Ini('editplus.ini');
$ini->parse();

echo "<pre>";
print_r($ini->get());
echo "</pre>";

?>