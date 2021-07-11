<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: http://localhost/~sjakka/RetirementToolwUI/retirement_guest.php");
        exit;
    }
    
    $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    $curr_username = $_SESSION['user'];

    if(isset($_POST["updateAcctInfo"])) {
        $new_full_name = $_POST['name_change'];
        $new_email = $_POST['email_change'];
        $new_curr_age = (int) $_POST['curr_age_change'];

        $sql = "UPDATE Login_info SET
            full_name = '$new_full_name',
            email = '$new_email',
            curr_age = '$new_curr_age'
        WHERE username='$curr_username'";

        if(mysqli_query($mysqli, $sql)){
            $acct_output = "Successfully updated records!";
        }else{
            printf("Error message: %s\n", $mysqli->error);
        }
      }

      if(isset($_POST["updatePwdInfo"])) {
        $old_pwd = $_POST['old_pwd'];
        $new_pwd = $_POST['new_pwd'];
        $confirm_new_pwd = $_POST['confirm_new_pwd'];
        print $old_pwd;
        print $new_pwd;

        if ($old_pwd === $new_pwd){
            $pwd_output = "Choose a password that hasn't been in use for the past year.";
        }else if ($new_pwd != $confirm_new_pwd){
            $pwd_output = "Passwords do not match. Please re-enter new password.";
        }
        else{
            $sql = "UPDATE Login_info SET
                password = '$new_pwd',
            WHERE username='$curr_username'";

            if(mysqli_query($mysqli, $sql)){
                $pwd_output = "Successfully updated records!";
            }else{
                printf("Error message: %s\n", $mysqli->error);
            }
        }
      }

      if(isset($_POST["updateRetInfo"])) {
        $new_ret_age = (int) $_POST['ret_age_change'];
        $new_life = (int) $_POST['life_change'];

        $sql = "UPDATE Login_info SET
            ret_age = '$new_ret_age',
            life = '$new_life'
        WHERE username='$curr_username'";

        if(mysqli_query($mysqli, $sql)){
            $ret_output = "Successfully updated records!";
        }else{
            printf("Error message: %s\n", $mysqli->error);
        }
      }

    $query1 = $mysqli->query("SELECT * FROM Login_info WHERE username = '$curr_username'");
    $query = $query1->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Account Settings</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" type="text/css"
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        function clearAccountInput(){
            document.getElementById("name_change").value = <?php echo json_encode($query["full_name"]); ?>;
            document.getElementById("email_change").value = <?php echo json_encode($query["email"]); ?>;
            document.getElementById("curr_age_change").value = <?php echo ($query["curr_age"]); ?>;
        }
        function clearPasswordInput(){
            document.getElementById("old_pwd").value = "";
            document.getElementById("new_pwd").value = "";
            document.getElementById("confirm_new_pwd").value = "";
        }
        function clearRetirementInput(){
            document.getElementById("ret_age_change").value = <?php echo ($query["ret_age"]); ?>;
            document.getElementById("life_change").value = <?php echo ($query["life"]); ?>;
        }
    </script>


	<section>
		<br><br>
		<div class="container">
			<h1>Account Settings for <b><?php echo htmlspecialchars($curr_username); ?></b></h1>
            <a href="http://localhost/~sjakka/RetirementToolwUI/retirement_user.php" style="margin-left:10; color:white;">
				<button class="tablink">Return</button>
            </a>
            <br><br>
			<div>
				<div>
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab"
							aria-controls="account" aria-selected="true">
							Account
						</a>
						<a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab"
							aria-controls="password" aria-selected="false">
							Password
						</a>
						<a class="nav-link" id="retirement-tab" data-toggle="pill" href="#retirement" role="tab"
							aria-controls="application" aria-selected="false">
							Retirement Settings
						</a>
					</div>
				</div>
				<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <h3>Account Settings</h3>
                        <form name="Form" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input id = "name_change" name = "name_change" type="text" class="form-control" value = <?php echo json_encode($query["full_name"]); ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input id = "email_change" name = "email_change" type="text" class="form-control" value = <?php echo json_encode($query["email"]); ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input id = "curr_age_change" name = "curr_age_change" type="number" class="form-control" value = <?php echo ($query["curr_age"]); ?>>
                                    </div>
                                </div>
                            </div>
                            <input name="updateAcctInfo" type="submit" value="Update">
                        </form>
                            <button onclick = "clearAccountInput()">Cancel</button>
                            <br><br>
                            <div style = "color:black"><?php echo $acct_output; ?></div>
					</div>
					<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <h3>Password Settings</h3>
                        <form name="Form2" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input id = "old_pwd" type="password" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input id = "new_pwd" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm new password</label>
                                        <input id = "confirm_new_pwd" type="password" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <input name="updatePwdInfo" type="submit" value="Update">
                        </form>
                            <button onclick = "clearPasswordInput()">Cancel</button>
                            <br><br>
                            <div style = "color:black"><?php echo $pwd_output; ?></div>
					</div>
					<div class="tab-pane fade" id="retirement" role="tabpanel" aria-labelledby="retirement-tab">
                        <h3>Retirement Settings</h3>
                        <form name="Form3" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Age you plan to retire</label>
                                        <input id = "ret_age_change" name = "ret_age_change" type="number" class="form-control" value = <?php echo ($query["ret_age"]); ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Life Expectancy</label>
                                        <input id = "life_change" name = "life_change" type="number" class="form-control" value = <?php echo ($query["life"]); ?>>
                                    </div>
                                </div>
                            </div>
                            <input name="updateRetInfo" type="submit" value="Update">
                        </form>
                            <button onclick = "clearRetirementInput()">Cancel</button>
                            <br><br>
                            <div style = "color:black"><?php echo $ret_output; ?></div>
					</div>
				</div>
			</div>
        </div>
    </section>
</body>

</html>
