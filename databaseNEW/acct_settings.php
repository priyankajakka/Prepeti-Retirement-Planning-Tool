<?php
    session_start();
    $output = NULL;
    $curr_username = $_SESSION["user"];

    if(isset($_POST["submit"])){
        $username = $_POST['username'];
        $curr_age = (int)$_POST['curr_age'];
        $ret_age = (int)$_POST['ret_age'];
        $life = (int)$_POST['life'];
        
        if(empty($username) || empty($curr_age) || empty($ret_age) || empty($life)){
            $output = "Please enter all fields. If there are any fields you do not wish to change, enter the existing values for them.";
        }else{
            $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
            
            $query = $mysqli->query("SELECT id FROM Login_info WHERE username = '$username'");
            
            if ($query->num_rows == 1){
                $output = "Username is already in use! Enter another username.";
            }else{
                
                $sql = "UPDATE Login_info SET
                curr_age=$curr_age,
                ret_age=$ret_age ,
                life=$life,
                WHERE username='$curr_username'";

                if(mysqli_query($mysqli, $sql)){
                    echo("YAY");
                }
                
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['user'] = $username;
                $_SESSION['curr_age'] = $curr_age;
                $_SESSION['ret_age'] = $ret_age;
                $_SESSION['life'] = $life;

                exit;
            }
        }
    }
    
?>

<!DOCTYPE html>

<html>
<div id="wrapper" style="text-align:center">

  <head>
    <a href="http://localhost/~sjakka/RetirementTool/retirement_user.php" style="margin-left:10; color:white;">
    <button class="tablink" >Return to main</button>
    </a> 

    <title>Account Settings</title>

    <div>
        <h1>Account settings for <b><?php echo htmlspecialchars($_SESSION["user"]); ?></b>!</h1>
    </div>

    <br>
    <br>

  </head>

  <body style="background-color: #7392B7; color: black;" >
        <div id = "og_settings">        
            Username: <?php echo htmlspecialchars($_SESSION["user"]); ?><br>
            Current Age: <?php echo htmlspecialchars($_SESSION["curr_age"]); ?><br>
            Retirement Age: <?php echo htmlspecialchars($_SESSION["ret_age"]); ?><br>
            Life expectancy: <?php echo htmlspecialchars($_SESSION["life"]); ?><br>
        </div>

        <div id = "showSettings"></div>

        <button id = "ogSettings" onclick="changeSettings()">Change Settings</button>
        <form method = "POST">
            <input style = "color:black" id = "updatedSettings" hidden = "hidden" type = "SUBMIT" name = "submit" value = "Confirm new settings"/>
        </form>

  </body>

  <script>

      function changeSettings(){
            var settings_data = ['Username', 'Current Age', 'Retirement Age', 'Life expectancy'];
            var values = ['username', 'curr_age', 'ret_age', 'life'];
            var button1 = document.getElementById("ogSettings");
            button1.hidden = "hidden";
            var button2 = document.getElementById("updatedSettings");
            button2.hidden = "";

            for (let i = 0; i < settings_data.length; i++) {
                document.getElementById("showSettings").innerHTML += ("New " + settings_data[i] + ": ");
                var input = document.createElement('input');
                input.id = values[i];
                input.name = values[i];
                if(settings_data[i] == 'Username'){
                    input.type = "text";
                }else{
                    input.type = "number";
                }

                document.getElementById("showSettings").appendChild(input);
                document.getElementById("showSettings").appendChild(document.createElement("br"));
            }
            document.getElementById("showSettings").appendChild(document.createElement("br"));
      }

    </script>
</div>

</html>
