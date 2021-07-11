<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    header("location: http://localhost/~sjakka/RetirementToolwUI/ret_login.php");
    exit;
    ?>
