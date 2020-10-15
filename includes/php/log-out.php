<?php
session_start();
unset($_SESSION['email']);
unset($_SESSION['userId']);

echo '<script>
        sessionStorage.clear();
        window.location = "/index.html";
    </script>';
?>
