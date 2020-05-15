<?php

// Die Session Variablen werden gelöscht und die Session zerstört damit der User nicht mehr angemeldet ist
session_start();
session_unset();
session_destroy();
header("Location: index.php");

?>