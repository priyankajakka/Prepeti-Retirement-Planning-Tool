<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    header("location: http://localhost/~sjakka/RetirementTool/landingPage/index.html");
    exit;
    ?>
