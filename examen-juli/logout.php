<?php
session_start();
session_unset();    // Verwijder alle sessievariabelen
session_destroy();  // Vernietig de sessie

header("Location: index.php"); // Terug naar inlogpagina
exit;
?>
