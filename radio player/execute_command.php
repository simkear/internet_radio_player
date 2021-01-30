<?php

$number = $_GET['number'];
//$command = file_get_contents("command$number.txt");

$output = shell_exec("mpc play '$number'");
echo "<pre>$output</pre>";
//echo "<pre>$command</pre>";
header("Location: http://".$_SERVER['HTTP_HOST']."/");
?>
