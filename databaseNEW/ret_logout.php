<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    header("location: http://localhost/~sjakka/RetirementTool/ret_login.php");
    exit;
    ?>
