<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    
    session_destroy();

    header("Location: ../index.php?action=formLogin&erro=2");

    exit();
}
?>