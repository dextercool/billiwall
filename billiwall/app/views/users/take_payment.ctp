<?php
if (isset($deactivatedUsers))
    foreach ($deactivatedUsers as $deactivatedUser) echo $deactivatedUser."<br>";
else echo "Никто не деактивирован";
?>
