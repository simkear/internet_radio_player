<?php

$command = $_GET['command'];
//$num = $_GET['number'];

//file_put_contents ( "command$num.txt" , $command );
$output = shell_exec("mpc add $command ");
echo "<pre>$output</pre>";
header("Location: http://".$_SERVER['HTTP_HOST']."/");
exit;
