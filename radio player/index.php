<?php
?>
<body style="background-color:grey">
<?php
$output = shell_exec("mpc playlist");
$arr = explode('128k', $output);
$num = 0;
for ($i = 0; $i < count($arr); $i++) {
     if (trim($arr[$i]) === '') {
         break;
          }
          if (empty(trim($arr[$i]))) {
          break;
          }
    $num = $i + 1;
    echo "<form action='/execute_command.php' method='get'>
                <p>
                    Radio $num :  <strong>$arr[$i]</strong>
            </p>
            <input type='hidden' name='number' value='$num'>
            
            <input type='submit' value='Pusti radio stanicu $num'>
            </form>
            <br>
            <form action='/clear_command.php' method='get'>
                <input type='hidden' name='number' value='$num'>
                <input type='submit' value='Obrisi'>
            </form>
            <br>
            <br>";
}
?>

<form action="/turnoff_command.php" method="get">
     <input type="submit" value="Iskljuci RPI" background-color="red">
</form>

<form action="/change_command.php" method="get">
    <input type="hidden" name="number" value="1">
    <input type="text" id="command1" name="command"> <input type="submit" value="Dodaj URL u Play Listu">
</form>


