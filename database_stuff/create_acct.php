<?php
    session_start();
    $output = NULL;
    
    if(isset($_POST["submit"])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        
        if(empty($username) || empty($password) || empty($full_name) || empty($email)){
            $output = "Please enter all fields.";
        }else{
            $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
            $username = $mysqli->real_escape_string($username);
            $password = $mysqli->real_escape_string($password);
            $full_name = $mysqli->real_escape_string($full_name);
            $email = $mysqli->real_escape_string($email);
            
            $query = $mysqli->query("SELECT id FROM Login_info WHERE email = '$email'");
            $query2 = $mysqli->query("SELECT id FROM Login_info WHERE username = '$username'");
            
            if($query->num_rows == 1){
                $output = "Looks like an account is already registered with this email address. Try logging in.";
            }else if ($query2->num_rows == 1){
                $output = "Username is already in use! Enter another username.";
            }else{
                $sql = "INSERT INTO Login_info (username, password, full_name, email) VALUES ('$username', '$password', '$full_name', '$email')";
                if(mysqli_query($mysqli, $sql)){
                } else{
                }
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['user'] = $full_name;
                header("location: http://localhost/~sjakka/RetirementTool/retirement_user.php");
                exit;
            }
        }
    }
    
    ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<style>
    * {
        box-sizing: border-box
    }

    body,
    html {
        height: 100%;
        margin: 0;
        font-family: Helvetica;
    }

    fieldset {
        margin-top:10%;
        text-align: center;
    }

</style>
</head>
<body style="background-color:#feffba;">
<fieldset>
<div style = "font-size:50; color:black"><strong><?php echo "Create an account! <p/>"; ?></strong></div>
    <div style = "color:black"><?php echo date("m/d/Y") . "<br><br><br>"; ?></div>

    <form method = "POST">
        <div style = "color:black">Enter username <input type = "TEXT" name = "username"/></div>
        </p>
        <div style = "color:black">Enter password <input type = "password" name = "password"/></div>
        </p>
        <div style = "color:black">Enter full name <input type = "full_name" name = "full_name"/></div>
        </p>
        <div style = "color:black">Enter email address <input type = "email" name = "email"/></div>
        </p>
        <input style = "color:black" type = "SUBMIT" name = "submit" value = "Create account"/>
    </form>
    <div style = "color:black"><?php echo $output; ?></div>
<div style = "color:black"><?php echo "Already have an account? <p/>"; ?></div>
<a href="http://localhost/~sjakka/RetirementTool/ret_login.php" style="margin-left:10; color:black;">
<button class="tablink" >Return to login page</button>
</a>
<a href="http://localhost/~sjakka/RetirementTool/retirement_guest.php" style="margin-left:10; color:black;">
<button class="tablink" >Continue as guest.</button>
</a>

</fieldset>
</body>
</html>

