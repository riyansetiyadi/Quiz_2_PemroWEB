<?php
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    setcookie("number_of_visitor", "", time()-3600);
    setcookie("id", "", time()-3600);

    header("location: index.php");
    exit;