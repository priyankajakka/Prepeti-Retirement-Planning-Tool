<?php
    session_start();
    $output = NULL;
    
    if(isset($_POST["submit"])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if(empty($username) || empty($password)){
            $output = "Please enter all fields.";
        }else{
            $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
            $username = $mysqli->real_escape_string($username);
            $password = $mysqli->real_escape_string($password);
            
            $query = $mysqli->query("SELECT id FROM Login_info WHERE username = '$username' AND password = '$password'");
            
            if($query->num_rows == 0){
                $output = "Invalid username/password.";
            }else{
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['user'] = $username;
                
                $stmt = $mysqli->prepare("SELECT DISTINCT curr_age FROM Login_Info WHERE username = '$username'");
                $stmt->execute();
                $result = $stmt->get_result();
                $value = $result->fetch_object();
                $_SESSION['curr_age'] = $value->curr_age;
                
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
                
                header("location: http://localhost/~sjakka/RetirementTool/retirement_user.php");
                exit;
                
            }
        }
    }
    
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<style>
    fieldset {
        margin-top:10%;
        text-align: center;
    }

</style>
</head>
<body>
<fieldset>
<div style = "font-size:50; color:black"><strong><?php echo "Sign in! <p/>"; ?></strong></div>
    <div style = "color:black"><?php echo date("m/d/Y") . "<br><br><br>"; ?></div>

    <form method = "POST">
        <div style = "color:black">Username <input type = "TEXT" name = "username"/></div>
        </p>
        <div style = "color:black">Password <input type = "password" name = "password"/></div>
        </p>
        <input style = "color:black" type = "SUBMIT" name = "submit" value = "Sign in"/>
    </form>
    <div style = "color:black"><?php echo $output; ?></div>
<div style = "color:black"><?php echo "Don't have an account yet? Create one! <p/>"; ?></div>
<a href="http://localhost/~sjakka/RetirementTool/create_acct.php" style="margin-left:10; color:black;">
<button class="tablink" >Create Account</button>
</a>
<a href="http://localhost/~sjakka/RetirementTool/retirement_guest.php" style="margin-left:10; color:black;">
<button class="tablink" >Continue as guest.</button>
</a>

</fieldset>
</body>
</html>



