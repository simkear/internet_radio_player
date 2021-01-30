<?php

//$number = $_GET['number'];
//$command = file_get_contents("command$number.txt");

$output = shell_exec("python ./shutdown.py");
echo "<pre>$output</pre>";
//echo "<pre>$command</pre>";
?>