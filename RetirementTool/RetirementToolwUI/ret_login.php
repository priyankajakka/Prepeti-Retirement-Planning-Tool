<?php
session_start();
$output = NULL;

if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $output = "Please fill all fields.";
    } else {
        $mysqli = new MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
        $username = $mysqli->real_escape_string($username);
        $password = $mysqli->real_escape_string($password);

        $query = $mysqli->query("SELECT id FROM Login_info WHERE username = '$username' AND password = '$password'");

        if ($query->num_rows == 0) {
            $output = "Invalid username/password.";
        } else {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user'] = $username;

            $stmt = $mysqli->prepare("SELECT DISTINCT birthday FROM Login_Info WHERE username = '$username'");
            $stmt->execute();
            $result = $stmt->get_result();
            $value = $result->fetch_object();
            $_SESSION['birthday'] = $value->birthday;

            $diff = date_diff(date_create($_SESSION['birthday']), date_create($today));
            $_SESSION['curr_age'] =  (int) floor($diff->days / 365.25);

            $sql = "UPDATE Login_info SET
                curr_age = $_SESSION[curr_age]
                WHERE username='$username'";

            mysqli_query($mysqli, $sql);


            //print $_SESSION['curr_age'];

            $stmt = $mysqli->prepare("SELECT DISTINCT ret_age FROM Login_Info WHERE username = '$username'");
            $stmt->execute();
            $result = $stmt->get_result();
            $value = $result->fetch_object();
            $_SESSION['ret_age'] = $value->ret_age;

            $stmt = $mysqli->prepare("SELECT DISTINCT life FROM Login_Info WHERE username = '$username'");
            $stmt->execute();
            $result = $stmt->get_result();
            $value = $result->fetch_object();
            $_SESSION['life'] = $value->life;

            $_SESSION['create_account'] = FALSE;

            header("location: http://localhost/~sjakka/RetirementTool/RetirementToolwUI/updateInfo.php");
            exit;
        }
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="css/create_acct2.css">

    <style>
        fieldset {
            margin-top: 0px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="cont">
    <img style="margin-left:10px; margin-right:0;margin-top:10; margin-bottom:0;width:80px" src="pictures/UMsitting.png">
        <div class="form">
            <div class="row">
                <div class="col-md-12 mx-0">
                    <form method="POST" id="msform">
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Sign in</h2>
                                <div style="font-size:11px">Don't have an account yet? <strong><a style="color:#72E9A5" href="http://localhost/~sjakka/RetirementTool/RetirementToolwUI/create_acct.php">Sign up.</a></strong><br>
                                    Continue as <strong><a style="color:#72E9A5" href="http://localhost/~sjakka/RetirementTool/RetirementToolwUI/retirement_guest.php">Guest</a></strong><br>
                                </div>

                                <label style="float:left" for="username" class="control-label">Username</label>
                                <input style = "color:white; text-align:left" class="form-control" type="text" id="username" name="username" placeholder="Enter your username" />

                                <label style="float:left" for="password" class="control-label">Password</label>
                                <input style = "color:white; text-align:left" class="form-control" type="password" id="password" name="password" placeholder="............." />

                                <br>
                                <div style="color:white"><?php echo $output; ?></div>

                            </div>
                            <input class="ButtonNeon" type="SUBMIT" name="submit" value="Sign in" />

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="sub-cont2">
            <img style="margin-left:-105%; margin-right:0;margin-top:35%;width:450px" src="pictures/create_acct_pic.png">
        </div>
    </div>


    <!--<fieldset>
        <div style="font-size:50; color:black"><strong>Sign in</strong></div>

        <form method="POST">
            <div style="color:black">Username <input type="TEXT" name="username" /></div>
            </p>
            <div style="color:black">Password <input type="password" name="password" /></div>
            </p>
            <input style="color:black" type="SUBMIT" name="submit" value="Sign in" />
        </form>
        

    </fieldset>-->
</body>

</html>